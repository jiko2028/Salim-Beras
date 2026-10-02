<?php

namespace App\Http\Controllers;

use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialTransactionController extends Controller
{
    /**
     * Tampilkan daftar transaksi keuangan dengan fitur filter.
     */
    public function index(Request $request)
    {
        $query = FinancialTransaction::with('user');

        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        if ($request->filled('type') && in_array($request->type, ['income', 'expense'])) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category')) {
            $query->where('category', 'like', '%' . $request->category . '%');
        }

        $transactions = $query->latest('transaction_date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $totalIncome  = (clone $query)->where('type', 'income')->sum('amount');
        $totalExpense = (clone $query)->where('type', 'expense')->sum('amount');
        $netBalance   = $totalIncome - $totalExpense;

        return view('finance.index', compact('transactions', 'totalIncome', 'totalExpense', 'netBalance'));
    }

    /**
     * Tampilkan form tambah transaksi keuangan manual.
     */
    public function create()
    {
        return view('finance.create');
    }

    /**
     * Simpan transaksi keuangan manual.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'             => ['required', 'in:income,expense'],
            'category'         => ['required', 'string', 'max:100'],
            'amount'           => ['required', 'numeric', 'min:1'],
            'description'      => ['nullable', 'string'],
            'transaction_date' => ['required', 'date'],
        ]);

        $validated['user_id'] = Auth::id();

        FinancialTransaction::create($validated);

        $label = $validated['type'] === 'income' ? 'Pemasukan' : 'Pengeluaran';

        return redirect()->route('finance.index')
            ->with('success', $label . ' berhasil dicatat.');
    }

    /**
     * Hapus transaksi keuangan.
     */
    public function destroy(FinancialTransaction $finance)
    {
        $finance->delete();

        return redirect()->route('finance.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}
