<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\InventoryTransaction;
use App\Models\FinancialTransaction;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ============================================
        // 1. CREATE USERS
        // ============================================
        $user = User::create([
            'name'     => 'Salim',
            'username' => 'admin',
            'password' => Hash::make('password123'),
        ]);

        // ============================================
        // 2. CREATE CATEGORIES
        // ============================================
        $categories = [
            ['name' => 'Beras Premium',  'description' => 'Beras kualitas premium, butiran utuh dan pulen'],
            ['name' => 'Beras Medium',   'description' => 'Beras kualitas menengah untuk konsumsi harian'],
            ['name' => 'Beras Ketan',    'description' => 'Beras ketan putih dan hitam'],
            ['name' => 'Beras Organik',  'description' => 'Beras organik tanpa pestisida'],
            ['name' => 'Beras Curah',    'description' => 'Beras curah ekonomis'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // ============================================
        // 3. CREATE ITEMS
        // ============================================
        $items = [
            // Beras Premium (category_id = 1)
            ['category_id' => 1, 'sku_code' => 'BP-001', 'name' => 'Beras Pandan Wangi 5kg',   'unit' => 'Karung 5kg',  'purchase_price' => 55000, 'selling_price' => 65000, 'stock' => 50,  'min_stock' => 10],
            ['category_id' => 1, 'sku_code' => 'BP-002', 'name' => 'Beras Pandan Wangi 25kg',  'unit' => 'Karung 25kg', 'purchase_price' => 260000, 'selling_price' => 310000, 'stock' => 30, 'min_stock' => 5],
            ['category_id' => 1, 'sku_code' => 'BP-003', 'name' => 'Beras Rojolele 5kg',       'unit' => 'Karung 5kg',  'purchase_price' => 52000, 'selling_price' => 62000, 'stock' => 40,  'min_stock' => 10],

            // Beras Medium (category_id = 2)
            ['category_id' => 2, 'sku_code' => 'BM-001', 'name' => 'Beras IR64 5kg',           'unit' => 'Karung 5kg',  'purchase_price' => 45000, 'selling_price' => 55000, 'stock' => 80,  'min_stock' => 15],
            ['category_id' => 2, 'sku_code' => 'BM-002', 'name' => 'Beras IR64 25kg',          'unit' => 'Karung 25kg', 'purchase_price' => 210000, 'selling_price' => 260000, 'stock' => 25, 'min_stock' => 5],
            ['category_id' => 2, 'sku_code' => 'BM-003', 'name' => 'Beras C4 10kg',            'unit' => 'Karung 10kg', 'purchase_price' => 95000, 'selling_price' => 115000, 'stock' => 35,  'min_stock' => 8],

            // Beras Ketan (category_id = 3)
            ['category_id' => 3, 'sku_code' => 'BK-001', 'name' => 'Beras Ketan Putih 1kg',    'unit' => 'Kg',          'purchase_price' => 18000, 'selling_price' => 24000, 'stock' => 20,  'min_stock' => 5],
            ['category_id' => 3, 'sku_code' => 'BK-002', 'name' => 'Beras Ketan Hitam 1kg',    'unit' => 'Kg',          'purchase_price' => 22000, 'selling_price' => 30000, 'stock' => 3,   'min_stock' => 5],

            // Beras Organik (category_id = 4)
            ['category_id' => 4, 'sku_code' => 'BO-001', 'name' => 'Beras Organik Menthik 5kg', 'unit' => 'Karung 5kg', 'purchase_price' => 75000, 'selling_price' => 95000, 'stock' => 15,  'min_stock' => 5],

            // Beras Curah (category_id = 5)
            ['category_id' => 5, 'sku_code' => 'BC-001', 'name' => 'Beras Curah Medium 1kg',    'unit' => 'Kg',         'purchase_price' => 9500, 'selling_price' => 12000, 'stock' => 100, 'min_stock' => 20],
            ['category_id' => 5, 'sku_code' => 'BC-002', 'name' => 'Beras Curah Premium 1kg',   'unit' => 'Kg',         'purchase_price' => 11000, 'selling_price' => 14000, 'stock' => 4,  'min_stock' => 20],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }

        // ============================================
        // 4. SAMPLE INVENTORY TRANSACTIONS
        // ============================================
        $today = Carbon::today();

        $inventoryData = [
            // Barang masuk
            ['item_id' => 1, 'user_id' => $user->id, 'type' => 'in',  'quantity' => 20, 'notes' => 'Restock dari supplier CV Tani Makmur',  'created_at' => $today->copy()->subDays(3)],
            ['item_id' => 4, 'user_id' => $user->id, 'type' => 'in',  'quantity' => 50, 'notes' => 'Restock dari supplier UD Padi Jaya',     'created_at' => $today->copy()->subDays(2)],
            ['item_id' => 2, 'user_id' => $user->id, 'type' => 'in',  'quantity' => 10, 'notes' => 'Restock dari supplier CV Tani Makmur',  'created_at' => $today->copy()->subDays(1)],

            // Barang keluar (penjualan)
            ['item_id' => 1, 'user_id' => $user->id, 'type' => 'out', 'quantity' => 5,  'notes' => 'Penjualan ke Toko Berkah',                'created_at' => $today->copy()->subDays(2)],
            ['item_id' => 4, 'user_id' => $user->id, 'type' => 'out', 'quantity' => 10, 'notes' => 'Penjualan langsung ke pelanggan',         'created_at' => $today->copy()->subDays(1)],
            ['item_id' => 1, 'user_id' => $user->id, 'type' => 'out', 'quantity' => 3,  'notes' => 'Penjualan ke warung Pak Joko',            'created_at' => $today],
            ['item_id' => 3, 'user_id' => $user->id, 'type' => 'out', 'quantity' => 8,  'notes' => 'Penjualan ke Toko Makmur',                'created_at' => $today],
        ];

        foreach ($inventoryData as $inv) {
            InventoryTransaction::create($inv);
        }

        // ============================================
        // 5. SAMPLE FINANCIAL TRANSACTIONS
        // ============================================
        $financeData = [
            // Pemasukan (income)
            ['user_id' => $user->id, 'type' => 'income',  'category' => 'Penjualan',    'amount' => 325000,  'description' => 'Penjualan 5 karung Pandan Wangi 5kg',    'transaction_date' => $today->copy()->subDays(2)],
            ['user_id' => $user->id, 'type' => 'income',  'category' => 'Penjualan',    'amount' => 550000,  'description' => 'Penjualan 10 karung IR64 5kg',           'transaction_date' => $today->copy()->subDays(1)],
            ['user_id' => $user->id, 'type' => 'income',  'category' => 'Penjualan',    'amount' => 195000,  'description' => 'Penjualan 3 karung Pandan Wangi 5kg',    'transaction_date' => $today],
            ['user_id' => $user->id, 'type' => 'income',  'category' => 'Penjualan',    'amount' => 496000,  'description' => 'Penjualan 8 karung Rojolele 5kg',        'transaction_date' => $today],

            // Pengeluaran (expense)
            ['user_id' => $user->id, 'type' => 'expense', 'category' => 'Restock',      'amount' => 1100000, 'description' => 'Beli 20 karung Pandan Wangi dari CV Tani', 'transaction_date' => $today->copy()->subDays(3)],
            ['user_id' => $user->id, 'type' => 'expense', 'category' => 'Restock',      'amount' => 2250000, 'description' => 'Beli 50 karung IR64 dari UD Padi Jaya',    'transaction_date' => $today->copy()->subDays(2)],
            ['user_id' => $user->id, 'type' => 'expense', 'category' => 'Operasional',  'amount' => 150000,  'description' => 'Biaya listrik bulanan',                    'transaction_date' => $today->copy()->subDays(1)],
            ['user_id' => $user->id, 'type' => 'expense', 'category' => 'Operasional',  'amount' => 75000,   'description' => 'Transportasi angkut barang',               'transaction_date' => $today],
        ];

        foreach ($financeData as $fin) {
            FinancialTransaction::create($fin);
        }
    }
}
