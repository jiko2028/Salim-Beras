# Product Requirement Document (PRD)
## Sistem Informasi Pengelolaan Keuangan dan Persediaan Beras pada Salim Beras

- **Nama Proyek:** Integrated Financial & Inventory Management System (Salim Beras)
- **Status Proyek:** Draft - Ready for Development
- **Versi Document:** 1.0.0
- **Tanggal:** Agustus 2026
- **Metodologi Pengembangan:** Waterfall Model (Analysis, Design, Implementation, Testing, Maintenance)

---

## 1. Pendahuluan & Latar Belakang

### 1.1 Latar Belakang
Salim Beras merupakan usaha dagang yang berlokasi di Salam, Kabupaten Magelang, yang bergerak dalam bidang penjualan beras dan kebutuhan pokok masyarakat. Saat ini, pencatatan persediaan barang (stok beras masuk/keluar) dan transaksi keuangan masih dilakukan secara konvensional menggunakan buku catatan harian. 

Pencatatan manual ini menimbulkan beberapa kendala operasional:
1. Kesulitan memantau jumlah stok fisik secara terupdate (real-time).
2. Risiko terjadinya selisih stok antara barang di gudang dengan catatan.
3. Keterlambatan dalam proses *restock* barang karena tidak adanya peringatan stok minimum.
4. Risiko human error pada kalkulasi arus kas (pemasukan & pengeluaran).
5. Lambatnya penyusunan laporan keuangan dan persediaan untuk evaluasi pemilik usaha.

### 1.2 Solusi
Membangun sistem informasi berbasis web menggunakan framework **Laravel 11** dan **Bootstrap 5** yang memusatkan pengelolaan persediaan beras dan arus kas usaha secara otomatis, rapi, dan dapat diakses secara cepat oleh pemilik usaha/admin.

---

## 2. Tujuan & Indikator Keberhasilan (KPI)

### 2.1 Tujuan Proyek
1. Mengotomatisasi pembaruan stok beras setiap kali terjadi transaksi barang masuk atau keluar.
2. Menyediakan fitur pencatatan kas (pemasukan dan pengeluaran) terintegrasi.
3. Mengurangi potensi kesalahan pencatatan dan risiko kehilangan data bisnis.
4. Menyajikan laporan stok dan laporan keuangan secara otomatis dan *real-time*.

### 2.2 Indikator Keberhasilan (Success Metrics)
- **Akurasi Data Stok:** 100% sinkronisasi antara transaksi di sistem dan perubahan jumlah stok di basis data.
- **Efisiensi Waktu Laporan:** Pembuatan laporan keuangan dan stok dapat dilakukan secara otomatis kurang dari 5 detik.
- **Usability:** Antarmuka responsif dan intuitif yang dapat digunakan oleh Admin/Owner tanpa pelatihan rumit.

---

## 3. Scope & Batasan Sistem

### 3.1 In-Scope (Fitur Terakup)
- Otentikasi Pengguna (Login/Logout dengan Hashing Password & Role-Based Access Control).
- Dashboard analitik ringkasan stok, arus kas harian, dan notifikasi stok kritis.
- Manajemen Data Master (Kategori Barang, Data Beras/Kemasan, Supplier/Pemasok).
- Transaksi Persediaan (Pencatatan Barang Masuk & Barang Keluar).
- Transaksi Keuangan (Pencatatan Pemasukan Penjualan & Pengeluaran Operasional).
- Peringatan Stok Minimum (Stock Alert System).
- Generasi Laporan (Filter tanggal, cetak/export PDF & Excel untuk Laporan Stok dan Arus Kas).

### 3.2 Out-of-Scope (Tidak Terakup)
- Transaksi e-commerce / penjualan online langsung oleh pembeli (Public E-Commerce).
- Payment Gateway interaktif (Midtrans/Xendit) — Sistem berfokus pada pencatatan kas internal.
- Integrasi sistem POS Hardware (Barcode Scanner / Mesin EDC) — Diakomodasi via input form manual web.
- Integrasi layanan ekspedisi / pengiriman barang.

---

## 4. Peran Pengguna (User Roles)

| Role | Deskripsi Hak Akses |
| :--- | :--- |
| **Owner (Pemilik)** | Akses penuh ke seluruh fitur: Dashboard analitik, manajemen pengguna/admin, pencatatan transaksi, pembatalan transaksi, serta cetak laporan keuangan & stok. |
| **Admin Gudang / Kasir** | Akses terbatas pada pencatatan barang masuk/keluar, pencatatan transaksi harian, dan melihat data stok barang. *Tidak memiliki hak akses mengubah/menghapus master data kritis atau manajemen akun.* |

---

## 5. Spesifikasi Kebutuhan Sistem

### 5.1 Kebutuhan Fungsional (Functional Requirements)

#### FR-01: Otentikasi & Otorisasi
- **FR-01.1:** Sistem harus mewajibkan pengguna untuk melakukan login dengan username dan password yang terenkripsi (Bcrypt).
- **FR-01.2:** Sistem harus membatasi menu dan aksi berdasarkan *role* pengguna (Owner / Admin).
- **FR-01.3:** Sistem harus menyediakan fungsi *Session Timeout* demi keamanan data.

#### FR-02: Dashboard Utama
- **FR-02.1:** Menampilkan Ringkasan Widget: Total Volume Stok (Kg), Pemasukan Hari Ini, Pengeluaran Hari Ini, dan Estimasi Kas Bersih.
- **FR-02.2:** Menampilkan *Alert Banner* otomatis apabila ada item beras yang jumlah stoknya $\le$ `min_stock`.
- **FR-02.3:** Menampilkan tabel *Quick Overview* stok kritis dan log aktivitas transaksi terakhir.

#### FR-03: Manajemen Data Master
- **FR-03.1:** Sistem dapat mengelola kategori produk (misal: Beras Premium, Beras Medium, Minyak, Gula).
- **FR-03.2:** Sistem dapat mengelola master item barang (Kode SKU, Nama Barang, Kategori, Satuan/Kemasan, Harga Beli, Harga Jual, Stok Awal, Batas Stok Minimum).

#### FR-04: Pengelolaan Persediaan (Inventory Control)
- **FR-04.1:** **Barang Masuk (Restock):** Input penambahan stok dari supplier. Otomatis menambah jumlah stok item pada database.
- **FR-04.2:** **Barang Keluar (Penjualan/Rusak):** Input pengurangan stok. Otomatis memvalidasi ketersediaan stok; transaksi ditolak jika stok tidak mencukupi.

#### FR-05: Pengelolaan Keuangan (Finance Tracking)
- **FR-05.1:** Otomatis mencatat arus kas masuk dari hasil transaksi barang keluar/penjualan.
- **FR-05.2:** Memfasilitasi input pengeluaran manual untuk beban operasional (misal: Beli Stok, Listrik, Transportasi, Gaji).
- **FR-05.3:** Menghitung total rekapitulasi Pemasukan, Pengeluaran, dan Saldo Kas Bersih secara otomatis berdasarkan rentang waktu yang dipilih.

#### FR-06: Pelaporan & Export Data
- **FR-06.1:** Menggenerasi **Laporan Persediaan Barang** (Stok Awal, Barang Masuk, Barang Keluar, Stok Akhir).
- **FR-06.2:** Menggenerasi **Laporan Arus Kas** (Detail Pemasukan & Pengeluaran, Total Kas Bersih).
- **FR-06.3:** Fitur filter laporan berdasarkan Rentang Tanggal (Harian, Mingguan, Bulanan).
- **FR-06.4:** Export laporan ke format **PDF** dan **Excel (.xlsx)**.

---

### 5.2 Kebutuhan Non-Fungsional (Non-Functional Requirements)

- **Kinerja (Performance):** Respon waktu pemuatan halaman maksimal 2 detik pada koneksi internet standar (3G/4G).
- **Keamanan (Security):** 
  - Proteksi terhadap serangan umum web: Cross-Site Request Forgery (CSRF), SQL Injection, dan Cross-Site Scripting (XSS).
  - Enkripsi password menggunakan standar `Bcrypt`.
- **Keandalan (Reliability):** Database dilengkapi fungsi transaksi (`DB::transaction`) untuk memastikan *data integrity* saat terjadi kegagalan sistem sewaktu update stok & kas secara simultan.
- **Usability & Responsiveness:** Tampilan web responsif mengadopsi Bootstrap 5 yang dapat diakses dengan baik melalui Desktop, Tablet, maupun Smartphone.

---

## 6. Arsitektur Data & Skema Database

Sistem ini menggunakan database **MySQL** dengan rancangan tabel sebagai berikut:

```sql
-- 1. Tabel Users
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('owner', 'admin') DEFAULT 'admin',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- 2. Tabel Categories
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- 3. Tabel Items (Barang/Beras)
CREATE TABLE items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    sku_code VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    unit VARCHAR(50) NOT NULL, -- e.g. Karung 5kg, Karung 25kg, Kg
    purchase_price DECIMAL(12, 2) NOT NULL DEFAULT 0,
    selling_price DECIMAL(12, 2) NOT NULL DEFAULT 0,
    stock INT NOT NULL DEFAULT 0,
    min_stock INT NOT NULL DEFAULT 5,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- 4. Tabel Inventory Transactions (Stok Masuk/Keluar)
CREATE TABLE inventory_transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    item_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    type ENUM('in', 'out') NOT NULL,
    quantity INT NOT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 5. Tabel Financial Transactions (Kas Pemasukan/Pengeluaran)
CREATE TABLE financial_transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    type ENUM('income', 'expense') NOT NULL,
    category VARCHAR(100) NOT NULL, -- e.g. Penjualan, Operasional, Restock
    amount DECIMAL(12, 2) NOT NULL,
    description TEXT NULL,
    transaction_date DATE NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

7. Tech Stack & Environment
Backend: PHP 8.2+, Framework Laravel 11.x

Frontend: HTML5, CSS3, JavaScript (Vanilla ES6), Bootstrap 5.3 Framework, Bootstrap Icons

Database: MySQL 8.0 / MariaDB

Server Local (Development): XAMPP / Laragon

Library Tambahan Laravel:

barryvdh/laravel-dompdf (Cetak PDF)

maatwebsite/excel (Export Excel)

8. Alur Pengerjaan Proyek (Waterfall Method Implementation)
Proyek ini dikembangkan secara sekuensial mengikuti siklus Waterfall:

[Requirement Analysis] ➔ [System Design] ➔ [Implementation] ➔ [Testing] ➔ [Deployment]
Requirement Analysis: Pemetaan proses bisnis manual Salim Beras dan penyusunan PRD ini.

System Design: Pembuatan ERD, Flowchart, Wireframe, dan Setup UI Template (Bootstrap 5).

Implementation:

Migration & Seeder Database di Laravel.

Pembangunan Eloquent Model & Controller Logic.

Pembangunan Blade Views UI.

Testing: Pengujian fitur menggunakan metode Black Box Testing (Validasi input, form login, kalkulasi otomatis stok & kas).

Deployment: Pemasangan sistem pada server lokal / hosting usaha Salim Beras.