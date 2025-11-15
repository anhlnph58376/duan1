<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion sticky-sidebar" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= BASE_URL ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-route"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Tour Manager</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= (!isset($_GET['action']) || $_GET['action'] == '/') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= BASE_URL ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Tours -->
    <li class="nav-item <?= (isset($_GET['action']) && in_array($_GET['action'], ['tours', 'tour_detail', 'tour_add', 'tour_edit'])) ? 'active' : '' ?>">
        <a class="nav-link" href="index.php?action=tours">
            <i class="fas fa-fw fa-map-marked-alt"></i>
            <span>Quản Lý Tour</span>
        </a>
    </li>

    <!-- Nav Item - Đặt Tour -->
    <li class="nav-item <?= (isset($_GET['action']) && in_array($_GET['action'], ['dat_tour', 'dat_tour_chi_tiet', 'dat_tour_them', 'dat_tour_sua', 'dat_tour_tao_doan_moi'])) ? 'active' : '' ?>">
        <a class="nav-link" href="index.php?action=dat_tour">
            <i class="fas fa-fw fa-calendar-check"></i>
            <span>Quản Lý Đặt Tour</span>
        </a>
    </li>

    <!-- Nav Item - Đoàn -->
    <li class="nav-item <?= (isset($_GET['action']) && in_array($_GET['action'], ['doan', 'doan_chi_tiet', 'doan_them', 'doan_sua', 'doan_tao_booking_moi'])) ? 'active' : '' ?>">
        <a class="nav-link" href="index.php?action=doan">
            <i class="fas fa-fw fa-plane-departure"></i>
            <span>Quản Lý Đoàn</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Customers (Placeholder) -->
    <li class="nav-item">
        <a class="nav-link" href="#" onclick="return false;" style="opacity: 0.6;">
            <i class="fas fa-fw fa-users"></i>
            <span>Quản Lý Khách Hàng</span>
        </a>
    </li>

    <!-- Nav Item - Guides (Placeholder) -->
    <li class="nav-item">
        <a class="nav-link" href="#" onclick="return false;" style="opacity: 0.6;">
            <i class="fas fa-fw fa-user-tie"></i>
            <span>Quản Lý Hướng Dẫn Viên</span>
        </a>
    </li>

    <!-- Nav Item - Accounts (Placeholder) -->
    <li class="nav-item">
        <a class="nav-link" href="#" onclick="return false;" style="opacity: 0.6;">
            <i class="fas fa-fw fa-user-cog"></i>
            <span>Quản Lý Tài Khoản</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->