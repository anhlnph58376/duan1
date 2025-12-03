<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard HDV - H2A</title>
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php?action=hdv_dashboard">
            <div class="sidebar-brand-icon"><i class="fas fa-globe-asia"></i></div>
            <div class="sidebar-brand-text mx-3">H2A</div>
        </a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item active">
            <a class="nav-link" href="index.php?action=hdv_dashboard">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <hr class="sidebar-divider">
        <li class="nav-item">
            <a class="nav-link" href="index.php?action=hdv_bookings">
                <i class="fas fa-fw fa-calendar-check"></i>
                <span>Bookings của tôi</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php?action=hdv_profile">
                <i class="fas fa-fw fa-user"></i>
                <span>Hồ sơ của tôi</span>
            </a>
        </li>
        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                <?= htmlspecialchars($_SESSION['user_name'] ?? 'HDV') ?>
                            </span>
                            <?php
                            $currentUser = null;
                            if (isset($_SESSION['user_id'])) {
                                $userModel = new User();
                                $currentUser = $userModel->find($_SESSION['user_id']);
                            }
                            if ($currentUser && !empty($currentUser['image']) && file_exists($currentUser['image'])): ?>
                                <img class="img-profile rounded-circle" src="<?= htmlspecialchars($currentUser['image']) ?>" 
                                     style="width: 40px; height: 40px; object-fit: cover;">
                            <?php else: ?>
                                <img class="img-profile rounded-circle" src="assets/img/undraw_profile.svg">
                            <?php endif; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                            <a class="dropdown-item" href="index.php?action=hdv_profile">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                Hồ sơ
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="index.php?action=logout">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Đăng xuất
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            <!-- Content -->
            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Dashboard HDV</h1>

                <?php
                $guideId = $_SESSION['tour_guide_id'] ?? null;
                $bookingModel = new Booking();
                
                // Lấy thống kê bookings
                $upcomingBookings = [];
                $completedBookings = [];
                $totalBookings = 0;
                
                if ($guideId) {
                    $allBookings = $bookingModel->getBookingsByGuideId($guideId);
                    $totalBookings = count($allBookings);
                    
                    $today = date('Y-m-d');
                    foreach ($allBookings as $booking) {
                        if ($booking['status'] == 'Completed') {
                            $completedBookings[] = $booking;
                        } elseif ($booking['departure_date'] >= $today) {
                            $upcomingBookings[] = $booking;
                        }
                    }
                }
                ?>

                <!-- Stats Cards -->
                <div class="row">
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Tổng Bookings
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalBookings ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Sắp tới
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($upcomingBookings) ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Đã hoàn thành
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($completedBookings) ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Bookings -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Bookings sắp tới</h6>
                    </div>
                    <div class="card-body">
                        <?php if (count($upcomingBookings) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Mã Booking</th>
                                            <th>Tour</th>
                                            <th>Ngày khởi hành</th>
                                            <th>Số người</th>
                                            <th>Trạng thái</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($upcomingBookings, 0, 5) as $booking): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($booking['booking_code']) ?></td>
                                                <td><?= htmlspecialchars($booking['tour_name']) ?></td>
                                                <td><?= date('d/m/Y', strtotime($booking['departure_date'])) ?></td>
                                                <td><?= $booking['number_of_guests'] ?></td>
                                                <td>
                                                    <span class="badge badge-<?= $booking['status'] == 'Confirmed' ? 'success' : 'warning' ?>">
                                                        <?= htmlspecialchars($booking['status']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="index.php?action=hdv_booking_detail&id=<?= $booking['id'] ?>" 
                                                       class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i> Xem
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <a href="index.php?action=hdv_bookings" class="btn btn-primary mt-3">
                                Xem tất cả bookings <i class="fas fa-arrow-right"></i>
                            </a>
                        <?php else: ?>
                            <p class="text-center text-muted py-4">Không có booking nào sắp tới</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="assets/vendor/jquery/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/sb-admin-2.min.js"></script>
</body>
</html>
