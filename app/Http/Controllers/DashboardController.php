<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\FinancialTransaction;
use App\Models\InventoryTransaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama.
     */
    public function index()
    {
        $today = Carbon::today();

        // Widget: Jumlah Barang (total jenis item)
        $totalItems = Item::count();

        // Widget: Total Stok (semua item)
        $totalStock = Item::sum('stock');

        // Widget: Pemasukan Hari Ini
        $todayIncome = FinancialTransaction::where('type', 'income')
            ->whereDate('transaction_date', $today)
            ->sum('amount');

        // Widget: Pengeluaran Hari Ini
        $todayExpense = FinancialTransaction::where('type', 'expense')
            ->whereDate('transaction_date', $today)
            ->sum('amount');

        // Widget: Kondisi Keuangan (Total Pemasukan - Total Pengeluaran keseluruhan)
        $totalIncome  = FinancialTransaction::where('type', 'income')->sum('amount');
        $totalExpense = FinancialTransaction::where('type', 'expense')->sum('amount');
        $netBalance   = $totalIncome - $totalExpense;

        // Widget: Transaksi Penjualan Hari Ini (barang keluar)
        $todaySalesCount = InventoryTransaction::where('type', 'out')
            ->whereDate('created_at', $today)
            ->count();

        // Barang yang stoknya menipis (stock <= min_stock)
        $lowStockItems = Item::with('category')
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock', 'asc')
            ->get();

        // Log aktivitas transaksi terbaru (10 terakhir)
        $recentTransactions = InventoryTransaction::with(['item', 'user'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact(
            'totalItems',
            'totalStock',
            'todayIncome',
            'todayExpense',
            'netBalance',
            'todaySalesCount',
            'lowStockItems',
            'recentTransactions'
        ));
    }
}
