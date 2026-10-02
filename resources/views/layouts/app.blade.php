<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Salim Beras</title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Materially UI Theme CSS -->
    <link href="{{ asset('css/materially.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>

    <!-- Mobile Backdrop -->
    <div class="mat-sidebar-backdrop" id="matSidebarBackdrop"></div>

    <!-- Materially Sidebar -->
    <aside class="mat-sidebar" id="matSidebar">
        <a href="{{ route('dashboard') }}" class="mat-sidebar-brand">
            <div class="mat-brand-icon">
                <i class="bi bi-box-seam-fill"></i>
            </div>
            <div class="mat-brand-text">
                <h4>Salim Beras</h4>
                <span>ADMIN PANEL</span>
            </div>
        </a>

        <nav class="mat-sidebar-nav">
            <div class="mat-nav-caption">Menu Utama</div>

            <a href="{{ route('dashboard') }}" class="mat-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" title="Dashboard">
                <i class="bi bi-grid-1x2"></i>
                <span>Dashboard</span>
            </a>

            <div class="mat-nav-caption">Data Master</div>

            <a href="{{ route('categories.index') }}" class="mat-nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}" title="Data Kategori">
                <i class="bi bi-tags"></i>
                <span>Kategori</span>
            </a>
            <a href="{{ route('items.index') }}" class="mat-nav-item {{ request()->routeIs('items.*') ? 'active' : '' }}" title="Data Barang">
                <i class="bi bi-box2"></i>
                <span>Data Barang</span>
            </a>

            <div class="mat-nav-caption">Transaksi</div>

            <a href="{{ route('inventory.index') }}" class="mat-nav-item {{ request()->routeIs('inventory.*') ? 'active' : '' }}" title="Stok Masuk / Keluar">
                <i class="bi bi-arrow-left-right"></i>
                <span>Stok Masuk / Keluar</span>
            </a>
            <a href="{{ route('finance.index') }}" class="mat-nav-item {{ request()->routeIs('finance.*') ? 'active' : '' }}" title="Transaksi Keuangan">
                <i class="bi bi-wallet2"></i>
                <span>Keuangan</span>
            </a>

            <div class="mat-nav-caption">Laporan</div>

            <a href="{{ route('reports.inventory') }}" class="mat-nav-item {{ request()->routeIs('reports.inventory') ? 'active' : '' }}" title="Laporan Stok">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Laporan Stok</span>
            </a>
            <a href="{{ route('reports.financial') }}" class="mat-nav-item {{ request()->routeIs('reports.financial') ? 'active' : '' }}" title="Laporan Keuangan">
                <i class="bi bi-file-earmark-spreadsheet"></i>
                <span>Laporan Keuangan</span>
            </a>
        </nav>
    </aside>

    <!-- Materially Header -->
    <header class="mat-header">
        <div class="mat-header-left">
            <button class="mat-btn-toggle" id="matToggleBtn" aria-label="Toggle Navigation">
                <i class="bi bi-list fs-5"></i>
            </button>
            <h1 class="mat-page-title">@yield('title', 'Dashboard')</h1>
        </div>

        <div class="mat-header-right">
            <div class="mat-user-pill">
                <div class="mat-user-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <span class="mat-user-name d-none d-sm-inline">{{ Auth::user()->name ?? 'Admin' }}</span>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="d-inline m-0" id="formLogout">
                @csrf
                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" title="Logout" id="btnLogout">
                    <i class="bi bi-box-arrow-right me-1"></i> <span class="d-none d-sm-inline">Logout</span>
                </button>
            </form>
        </div>
    </header>

    <!-- Main Wrapper -->
    <div class="mat-main-wrapper">
        <main class="mat-content">
            @yield('content')
        </main>

        <footer class="mat-footer">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>&copy; {{ date('Y') }} <strong>Salim Beras</strong> — Sistem Informasi Keuangan & Persediaan Beras.</div>
                <div>Salam, Kab. Magelang</div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const toggleBtn = document.getElementById('matToggleBtn');
        const sidebar = document.getElementById('matSidebar');
        const backdrop = document.getElementById('matSidebarBackdrop');

        // Restore collapsed state for desktop screen
        if (window.innerWidth >= 992 && localStorage.getItem('sidebar_collapsed') === 'true') {
            document.body.classList.add('sidebar-collapsed');
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                if (window.innerWidth >= 992) {
                    // Desktop toggle collapse
                    document.body.classList.toggle('sidebar-collapsed');
                    localStorage.setItem('sidebar_collapsed', document.body.classList.contains('sidebar-collapsed'));
                } else {
                    // Mobile overlay drawer toggle
                    sidebar.classList.toggle('show');
                    backdrop.classList.toggle('show');
                }
            });
        }

        if (backdrop) {
            backdrop.addEventListener('click', () => {
                sidebar.classList.remove('show');
                backdrop.classList.remove('show');
            });
        }

        // SweetAlert2 Toast Notification Setup
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        // Flash Messages with SweetAlert2
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            });
        @endif

        // Global SweetAlert2 Delete Confirmation
        document.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.btn-delete');
            if (deleteBtn) {
                e.preventDefault();
                const form = deleteBtn.closest('form');
                const itemName = form.dataset.name || 'data ini';

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    html: `Data <strong>${itemName}</strong> akan dihapus permanen dari sistem.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ff413a',
                    cancelButtonColor: '#60696d',
                    confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-4 shadow-lg',
                        confirmButton: 'btn btn-danger px-4 py-2 rounded-pill me-2',
                        cancelButton: 'btn btn-secondary px-4 py-2 rounded-pill'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });

        // Logout Confirmation with SweetAlert2
        const btnLogout = document.getElementById('btnLogout');
        const formLogout = document.getElementById('formLogout');
        if (btnLogout && formLogout) {
            btnLogout.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi Logout',
                    text: 'Apakah Anda yakin ingin keluar dari panel admin?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3366ff',
                    cancelButtonColor: '#60696d',
                    confirmButtonText: 'Ya, Logout',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-4 shadow-lg',
                        confirmButton: 'btn btn-primary px-4 py-2 rounded-pill me-2',
                        cancelButton: 'btn btn-secondary px-4 py-2 rounded-pill'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        formLogout.submit();
                    }
                });
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
