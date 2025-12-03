<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Bookings của tôi - HDV</title>
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
    <!-- Sidebar cho HDV -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php?action=hdv_dashboard">
            <div class="sidebar-brand-icon"><i class="fas fa-globe-asia"></i></div>
            <div class="sidebar-brand-text mx-3">H2A</div>
        </a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item">
            <a class="nav-link" href="index.php?action=hdv_dashboard">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <hr class="sidebar-divider">
        <li class="nav-item active">
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
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= htmlspecialchars($_SESSION['user_name'] ?? 'HDV') ?></span>
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
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
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

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Bookings của tôi</h1>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Danh sách Bookings</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Mã Booking</th>
                                        <th>Tên khách</th>
                                        <th>Tour</th>
                                        <th>Số người</th>
                                        <th>Ngày bắt đầu</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $guideId = $_SESSION['tour_guide_id'] ?? null;
                                    $bookings = [];
                                    if ($guideId) {
                                        $bookingModel = new Booking();
                                        $pdo = $bookingModel->getPdo();

                                        // Debug: Log the guide ID and query
                                        error_log("HDV Bookings - Guide ID: " . $guideId);

                                        // Get bookings directly assigned to this guide
                                        $stmt = $pdo->prepare("
                                            SELECT b.id, b.booking_code, b.booking_date as start_date,
                                                   b.status,
                                                   t.id as tour_id, t.name as tour_title,
                                                   c.name as customer_name,
                                                   b.total_amount, b.deposit_amount
                                            FROM bookings b
                                            LEFT JOIN tours t ON b.tour_id = t.id
                                            LEFT JOIN customers c ON b.customer_id = c.id
                                            WHERE b.guide_id = :guide_id
                                            ORDER BY b.booking_date DESC
                                        ");
                                        $stmt->execute(['guide_id' => $guideId]);
                                        $bookings = $stmt->fetchAll();

                                        // Debug: Log the results
                                        error_log("HDV Bookings - Found " . count($bookings) . " bookings");

                                        // If no bookings found, try alternative approach with user_id
                                        if (empty($bookings)) {
                                            error_log("HDV Bookings - Trying alternative query with user_id");

                                            // Try to get the user_id for this guide
                                            $guideModel = new Guide();
                                            $guide = $guideModel->getGuideById($guideId);

                                            if ($guide && !empty($guide['user_id'])) {
                                                $userId = $guide['user_id'];
                                                error_log("HDV Bookings - Guide user_id: " . $userId);

                                                // Try to find bookings assigned to this user
                                                $stmt2 = $pdo->prepare("
                                                    SELECT b.id, b.booking_code, b.booking_date as start_date,
                                                           b.status,
                                                           t.id as tour_id, t.name as tour_title,
                                                           c.name as customer_name,
                                                           b.total_amount, b.deposit_amount
                                                    FROM bookings b
                                                    LEFT JOIN tours t ON b.tour_id = t.id
                                                    LEFT JOIN customers c ON b.customer_id = c.id
                                                    WHERE b.guide_id = :guide_id OR b.created_by = :user_id
                                                    ORDER BY b.booking_date DESC
                                                ");
                                                $stmt2->execute(['guide_id' => $guideId, 'user_id' => $userId]);
                                                $bookings = $stmt2->fetchAll();
                                                error_log("HDV Bookings - Alternative query found " . count($bookings) . " bookings");
                                            }
                                        }
                                    } else {
                                        // If no guide ID in session, try to find it from the current user
                                        error_log("HDV Bookings - No guide ID in session, trying to find from user");

                                        if (isset($_SESSION['user_id'])) {
                                            $userId = $_SESSION['user_id'];
                                            $guideModel = new Guide();
                                            $guide = $guideModel->getGuideByUserId($userId);

                                            if ($guide && !empty($guide['id'])) {
                                                $guideId = $guide['id'];
                                                $_SESSION['tour_guide_id'] = $guideId; // Update session for future requests
                                                error_log("HDV Bookings - Found guide ID from user: " . $guideId);

                                                // Now fetch bookings for this guide
                                                $bookingModel = new Booking();
                                                $pdo = $bookingModel->getPdo();

                                                $stmt = $pdo->prepare("
                                                    SELECT b.id, b.booking_code, b.booking_date as start_date,
                                                           b.status,
                                                           t.id as tour_id, t.name as tour_title,
                                                           c.name as customer_name,
                                                           b.total_amount, b.deposit_amount
                                                    FROM bookings b
                                                    LEFT JOIN tours t ON b.tour_id = t.id
                                                    LEFT JOIN customers c ON b.customer_id = c.id
                                                    WHERE b.guide_id = :guide_id
                                                    ORDER BY b.booking_date DESC
                                                ");
                                                $stmt->execute(['guide_id' => $guideId]);
                                                $bookings = $stmt->fetchAll();
                                                error_log("HDV Bookings - Found " . count($bookings) . " bookings after session update");
                                            }
                                        }
                                    }
                                    ?>
                                    <?php if (!empty($bookings)): foreach ($bookings as $booking): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($booking['booking_code'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($booking['customer_name'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($booking['tour_title'] ?? 'N/A') ?></td>
                                            <td>1</td> <!-- Default value since pax_count is not in bookings table -->
                                            <td><?= htmlspecialchars($booking['start_date'] ?? '') ?></td>
                                            <td>
                                                <?php
                                                $status = $booking['status'] ?? '';
                                                $badge = 'secondary';
                                                if ($status === 'confirmed') $badge = 'success';
                                                elseif ($status === 'in_progress') $badge = 'primary';
                                                elseif ($status === 'completed') $badge = 'info';
                                                ?>
                                                <span class="badge badge-<?= $badge ?>"><?= htmlspecialchars($status) ?></span>
                                            </td>
                                            <td>
                                                <a href="index.php?action=hdv_booking_detail&id=<?= (int)$booking['id'] ?>"
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> Chi tiết
                                                </a>
                                                <?php if (!empty($booking['total_amount'])): ?>
                                                    <br><small class="text-muted mt-1">Tổng: <?= number_format($booking['total_amount'], 0, ',', '.') ?> VNĐ</small>
                                                <?php endif; ?>
                                                <?php if (empty($booking['total_amount'])): ?>
                                                    <br><small class="text-warning mt-1"><i class="fas fa-exclamation-triangle"></i> Chưa có thông tin tài chính</small>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="7" class="text-center text-muted">Chưa có booking nào được gán. Vui lòng liên hệ quản trị viên nếu bạn vừa được gán booking.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
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
