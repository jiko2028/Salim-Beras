<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    /**
     * Tampilkan daftar semua barang.
     */
    public function index(Request $request)
    {
        $query = Item::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $items = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('items.index', compact('items', 'categories'));
    }

    /**
     * Tampilkan form tambah barang baru.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('items.create', compact('categories'));
    }

    /**
     * Simpan barang baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'    => ['required', 'exists:categories,id'],
            'sku_code'       => ['required', 'string', 'max:50', 'unique:items,sku_code'],
            'name'           => ['required', 'string', 'max:255'],
            'unit'           => ['required', 'string', 'max:50'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price'  => ['required', 'numeric', 'min:0'],
            'stock'          => ['required', 'integer', 'min:0'],
            'min_stock'      => ['required', 'integer', 'min:0'],
        ]);

        Item::create($validated);

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit barang.
     */
    public function edit(Item $item)
    {
        $categories = Category::orderBy('name')->get();

        return view('items.edit', compact('item', 'categories'));
    }

    /**
     * Update barang di database.
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'category_id'    => ['required', 'exists:categories,id'],
            'sku_code'       => ['required', 'string', 'max:50', Rule::unique('items')->ignore($item->id)],
            'name'           => ['required', 'string', 'max:255'],
            'unit'           => ['required', 'string', 'max:50'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price'  => ['required', 'numeric', 'min:0'],
            'stock'          => ['required', 'integer', 'min:0'],
            'min_stock'      => ['required', 'integer', 'min:0'],
        ]);

        $item->update($validated);

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Hapus barang dari database.
     */
    public function destroy(Item $item)
    {
        $trxCount = $item->inventoryTransactions()->count();
        if ($trxCount > 0) {
            return redirect()->route('items.index')
                ->with('error', "Barang '{$item->name}' tidak dapat dihapus karena memiliki {$trxCount} riwayat transaksi stok.");
        }

        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
}
