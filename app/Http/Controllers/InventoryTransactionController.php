<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\InventoryTransaction;
use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryTransactionController extends Controller
{
    /**
     * Tampilkan daftar transaksi stok dengan fitur filter.
     */
    public function index(Request $request)
    {
        $query = InventoryTransaction::with(['item', 'user']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('type') && in_array($request->type, ['in', 'out'])) {
            $query->where('type', $request->type);
        }

        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }

        $transactions = $query->latest()->paginate(10)->withQueryString();
        $items = Item::orderBy('name')->get();

        return view('inventory.index', compact('transactions', 'items'));
    }

    /**
     * Tampilkan form input transaksi stok (masuk/keluar).
     */
    public function create()
    {
        $items = Item::with('category')->orderBy('name')->get();

        return view('inventory.create', compact('items'));
    }

    /**
     * Simpan transaksi stok dan update stok barang secara otomatis.
     * Menggunakan DB::transaction untuk menjaga data integrity.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id'  => ['required', 'exists:items,id'],
            'type'     => ['required', 'in:in,out'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes'    => ['nullable', 'string'],
        ]);

        $item = Item::findOrFail($validated['item_id']);

        // Validasi: jika barang keluar, cek stok cukup
        if ($validated['type'] === 'out' && $item->stock < $validated['quantity']) {
            return back()
                ->withInput()
                ->withErrors(['quantity' => 'Stok tidak mencukupi! Stok tersedia: ' . $item->stock]);
        }

        DB::transaction(function () use ($validated, $item) {
            // 1. Catat transaksi inventory
            InventoryTransaction::create([
                'item_id'  => $validated['item_id'],
                'user_id'  => Auth::id(),
                'type'     => $validated['type'],
                'quantity' => $validated['quantity'],
                'notes'    => $validated['notes'],
            ]);

            // 2. Update stok barang
            if ($validated['type'] === 'in') {
                $item->increment('stock', $validated['quantity']);
            } else {
                $item->decrement('stock', $validated['quantity']);
            }

            // 3. Auto-catat transaksi keuangan
            if ($validated['type'] === 'in') {
                // Barang masuk = pengeluaran (beli dari supplier)
                FinancialTransaction::create([
                    'user_id'          => Auth::id(),
                    'type'             => 'expense',
                    'category'         => 'Restock',
                    'amount'           => $item->purchase_price * $validated['quantity'],
                    'description'      => 'Restock ' . $validated['quantity'] . ' ' . $item->unit . ' ' . $item->name,
                    'transaction_date' => now()->toDateString(),
                ]);
            } else {
                // Barang keluar = pemasukan (penjualan)
                FinancialTransaction::create([
                    'user_id'          => Auth::id(),
                    'type'             => 'income',
                    'category'         => 'Penjualan',
                    'amount'           => $item->selling_price * $validated['quantity'],
                    'description'      => 'Penjualan ' . $validated['quantity'] . ' ' . $item->unit . ' ' . $item->name,
                    'transaction_date' => now()->toDateString(),
                ]);
            }
        });

        $message = $validated['type'] === 'in' ? 'Barang masuk berhasil dicatat.' : 'Barang keluar berhasil dicatat.';

        return redirect()->route('inventory.index')
            ->with('success', $message);
    }
}
