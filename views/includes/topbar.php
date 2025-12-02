<?php
// Get current user info for avatar
$currentUser = null;
if (isset($_SESSION['user_id'])) {
    $userModel = new User();
    $currentUser = $userModel->find($_SESSION['user_id']);
}
?>
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <form class="form-inline">
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>
    </form>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    <?= htmlspecialchars($_SESSION['user_name'] ?? 'Guest', ENT_QUOTES, 'UTF-8') ?>
                </span>
                <?php if ($currentUser && !empty($currentUser['image']) && file_exists($currentUser['image'])): ?>
                    <img class="img-profile rounded-circle" src="<?= htmlspecialchars($currentUser['image']) ?>" 
                         style="width: 40px; height: 40px; object-fit: cover;">
                <?php else: ?>
                    <img class="img-profile rounded-circle" src="assets/img/undraw_profile.svg">
                <?php endif; ?>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="?action=profile">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Thông tin cá nhân
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="?action=logout">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Đăng xuất
                </a>
            </div>
        </li>

    </ul>

</nav>