<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('admin/dashboard') ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-store"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Toko Kalina</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= (current_url() == base_url('admin/dashboard')) ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('admin/dashboard') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Katalog
    </div>

    <!-- Nav Item - Produk -->
    <li class="nav-item <?= (strpos(current_url(), base_url('admin/produk')) !== false) ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('admin/produk') ?>">
            <i class="fas fa-fw fa-box"></i>
            <span>Produk</span></a>
    </li>

    <!-- Nav Item - Kategori -->
    <li class="nav-item <?= (strpos(current_url(), base_url('admin/kategori')) !== false) ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('admin/kategori') ?>">
            <i class="fas fa-fw fa-list"></i>
            <span>Kategori</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Penjualan
    </div>

    <!-- Nav Item - Pesanan -->
    <li class="nav-item <?= (strpos(current_url(), base_url('admin/pesanan')) !== false) ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('admin/pesanan') ?>">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Pesanan</span></a>
    </li>

    <div class="sidebar-heading">
        Laporan
    </div>
    <li class="nav-item <?= (strpos(current_url(), base_url('admin/laporan')) !== false) ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('admin/laporan') ?>">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Laporan</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
