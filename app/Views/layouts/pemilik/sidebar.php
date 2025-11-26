<!-- Sidebar Pemilik: only show Laporan -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('pemilik/dashboard') ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-store"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Mantra Jaya Tani</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item <?= (current_url() == base_url('pemilik/dashboard')) ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('pemilik/dashboard') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <div class="sidebar-heading">Laporan</div>
    <li class="nav-item <?= (strpos(current_url(), base_url('pemilik/laporan')) !== false) ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('pemilik/laporan/penjualan') ?>">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Laporan</span></a>
    </li>

    <hr class="sidebar-divider">
    <!-- Logout (sidebar) -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('logout') ?>">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Keluar</span>
        </a>
    </li>
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
