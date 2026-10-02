<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\InventoryTransaction;
use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Ambil data inventory report berdasarkan rentang tanggal.
     */
    private function getInventoryData(Request $request): array
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->toDateString());

        $items = Item::with('category')->get()->map(function ($item) use ($startDate, $endDate) {
            $stockIn = InventoryTransaction::where('item_id', $item->id)
                ->where('type', 'in')
                ->whereBetween('created_at', [$startDate, Carbon::parse($endDate)->endOfDay()])
                ->sum('quantity');

            $stockOut = InventoryTransaction::where('item_id', $item->id)
                ->where('type', 'out')
                ->whereBetween('created_at', [$startDate, Carbon::parse($endDate)->endOfDay()])
                ->sum('quantity');

            $item->stock_in  = $stockIn;
            $item->stock_out = $stockOut;

            return $item;
        });

        return compact('items', 'startDate', 'endDate');
    }

    /**
     * Ambil data financial report berdasarkan rentang tanggal.
     */
    private function getFinancialData(Request $request): array
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->toDateString());

        $transactions = FinancialTransaction::with('user')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->orderBy('transaction_date', 'desc')
            ->get();

        $totalIncome  = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $netBalance   = $totalIncome - $totalExpense;

        return compact('transactions', 'totalIncome', 'totalExpense', 'netBalance', 'startDate', 'endDate');
    }

    /**
     * Laporan Persediaan Barang (halaman web).
     */
    public function inventory(Request $request)
    {
        $data = $this->getInventoryData($request);

        return view('reports.inventory', $data);
    }

    /**
     * Laporan Arus Kas (halaman web).
     */
    public function financial(Request $request)
    {
        $data = $this->getFinancialData($request);

        return view('reports.financial', $data);
    }

    /**
     * Export Laporan Persediaan ke PDF.
     */
    public function inventoryPdf(Request $request)
    {
        $data = $this->getInventoryData($request);

        $pdf = Pdf::loadView('reports.inventory-pdf', $data)
            ->setPaper('a4', 'landscape');

        $filename = 'Laporan_Stok_' . $data['startDate'] . '_sd_' . $data['endDate'] . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export Laporan Arus Kas ke PDF.
     */
    public function financialPdf(Request $request)
    {
        $data = $this->getFinancialData($request);

        $pdf = Pdf::loadView('reports.financial-pdf', $data)
            ->setPaper('a4', 'landscape');

        $filename = 'Laporan_Keuangan_' . $data['startDate'] . '_sd_' . $data['endDate'] . '.pdf';

        return $pdf->download($filename);
    }
}
