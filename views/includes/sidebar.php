<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion sticky-sidebar" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= BASE_URL ?>">
        <div class="sidebar-brand-icon">
            <i class="fas fa-globe-asia"></i>
        </div>
        <div class="sidebar-brand-text mx-3">H2A</div>
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

    <li class="nav-item <?= (isset($_GET['action']) && in_array($_GET['action'], ['suppliers', 'suppliers_add', 'suppliers_edit'])) ? 'active' : '' ?>">
        <a class="nav-link" href="index.php?action=suppliers">
            <i class="fas fa-fw fa-handshake"></i>
            <span>Quản Lý Đối Tác</span>
        </a>
    </li>

    <!-- Nav Item - Bookings -->
    <li class="nav-item <?= (isset($_GET['action']) && in_array($_GET['action'], ['bookings', 'booking_detail', 'booking_add', 'booking_edit', 'booking_create_new_departure'])) ? 'active' : '' ?>">
        <a class="nav-link" href="index.php?action=bookings">
            <i class="fas fa-fw fa-calendar-check"></i>
            <span>Quản Lý Booking</span>
        </a>
    </li>

    <!-- Nav Item - Departures -->
    <li class="nav-item <?= (isset($_GET['action']) && in_array($_GET['action'], ['departures', 'departure_detail', 'departure_add', 'departure_edit', 'departure_create_new_booking'])) ? 'active' : '' ?>">
        <a class="nav-link" href="index.php?action=departures">
            <i class="fas fa-fw fa-plane-departure"></i>
            <span>Quản Lý Đoàn</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Customers -->
    <li class="nav-item <?= (isset($_GET['action']) && in_array($_GET['action'], ['customers', 'customer_detail', 'customer_add', 'customer_edit'])) ? 'active' : '' ?>">
        <a class="nav-link" href="index.php?action=customers">
            <i class="fas fa-fw fa-users"></i>
            <span>Quản Lý Khách Hàng</span>
        </a>
    </li>

    <!-- Nav Item - Guides -->
    <li class="nav-item <?= (isset($_GET['action']) && in_array($_GET['action'], ['guides', 'guide_detail', 'guide_add', 'guide_edit', 'guide_schedule', 'guide_performance'])) ? 'active' : '' ?>">
        <a class="nav-link" href="index.php?action=guides">
            <i class="fas fa-fw fa-user-tie"></i>
            <span>Quản Lý Hướng Dẫn Viên</span>
        </a>
    </li>

    <!-- Nav Item - Account Management -->
    <li class="nav-item <?= (isset($_GET['action']) && $_GET['action'] === 'account_management') ? 'active' : '' ?>">
        <a class="nav-link" href="index.php?action=account_management">
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