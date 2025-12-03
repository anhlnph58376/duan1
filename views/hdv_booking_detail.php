<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Chi tiết Booking - HDV</title>
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .member-card { border-left: 3px solid #4e73df; }
        .checked-in { border-left-color: #1cc88a; }
    </style>
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
                <a href="index.php?action=hdv_bookings" class="btn btn-link">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
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
                <?php
                $bookingId = (int)($_GET['id'] ?? 0);
                $booking = null;
                $tour = null;
                $members = [];

                if ($bookingId > 0) {
                    $bookingModel = new Booking();
                    $pdo = $bookingModel->getPdo();

                    // First try to get booking details directly assigned to this guide
                    $stmt = $pdo->prepare("
                        SELECT b.id, b.booking_code, b.booking_date as start_date,
                               b.status,
                               t.name as tour_title, t.description as tour_description,
                               c.name as customer_name, c.phone as customer_phone, c.email as customer_email,
                               b.total_amount, b.deposit_amount
                        FROM bookings b
                        LEFT JOIN tours t ON b.tour_id = t.id
                        LEFT JOIN customers c ON b.customer_id = c.id
                        WHERE b.id = :id AND b.guide_id = :guide_id
                    ");
                    $stmt->execute(['id' => $bookingId, 'guide_id' => $_SESSION['tour_guide_id'] ?? 0]);
                    $booking = $stmt->fetch();

                    // If not found as direct booking, try departure approach
                    if (!$booking) {
                        $stmt = $pdo->prepare("
                            SELECT d.id, CONCAT('DEP-', d.id) as booking_code, d.departure_date as start_date,
                                   d.status, d.max_pax as number_of_people, '' as tour_title,
                                   '' as tour_description, '' as customer_name, '' as customer_phone,
                                   '' as customer_email
                            FROM departures d
                            WHERE d.id = :id AND d.actual_guide_id = :guide_id
                        ");
                        $stmt->execute(['id' => $bookingId, 'guide_id' => $_SESSION['tour_guide_id'] ?? 0]);
                        $booking = $stmt->fetch();
                    }

                    // Get members based on booking type
                    if ($booking) {
                        if (isset($booking['customer_name']) && !empty($booking['customer_name'])) {
                            // This is a direct booking, get booking members
                            $stmt = $pdo->prepare("
                                SELECT bm.id, bm.full_name, bm.passport_number as id_number,
                                       bm.age as date_of_birth,
                                       CASE WHEN bm.checkin_status = 'checked_in' THEN 1 ELSE 0 END as checked_in
                                FROM booking_members bm
                                WHERE bm.booking_id = :booking_id
                                ORDER BY bm.id
                            ");
                            $stmt->execute(['booking_id' => $bookingId]);
                            $members = $stmt->fetchAll();
                        } else {
                            // This is a departure, get guests from bookings in this departure
                            $stmt = $pdo->prepare("
                                SELECT bg.id, bg.guest_name as full_name, bg.guest_phone as phone,
                                       '' as id_number, '' as date_of_birth, 0 as checked_in
                                FROM booking_guests bg
                                INNER JOIN departure_bookings db ON db.booking_id = bg.booking_id
                                WHERE db.departure_id = :departure_id
                                ORDER BY bg.id
                            ");
                            $stmt->execute(['departure_id' => $bookingId]);
                            $members = $stmt->fetchAll();
                        }
                    }
                }

                if (!$booking) {
                    echo '<div class="alert alert-danger">Không tìm thấy booking hoặc bạn không có quyền truy cập</div>';
                    exit;
                }
                ?>

                <h1 class="h3 mb-4 text-gray-800">Chi tiết Booking: <?= htmlspecialchars($booking['booking_code']) ?></h1>

                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= $_SESSION['success_message']; ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>

                <div class="row">
                    <!-- Booking Info -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Thông tin Booking</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Mã booking:</strong> <?= htmlspecialchars($booking['booking_code']) ?></p>
                                <p><strong>Tour:</strong> <?= htmlspecialchars($booking['tour_title']) ?></p>
                                <p><strong>Khách hàng:</strong> <?= htmlspecialchars($booking['customer_name']) ?></p>
                                <p><strong>SĐT:</strong> <?= htmlspecialchars($booking['customer_phone']) ?></p>
                                <p><strong>Email:</strong> <?= htmlspecialchars($booking['customer_email']) ?></p>
                                <p><strong>Số người:</strong> 1</p> <!-- Default value since pax_count is not in bookings table -->
                                <p><strong>Ngày bắt đầu:</strong> <?= htmlspecialchars($booking['start_date']) ?></p>
                                <p><strong>Trạng thái:</strong> <span class="badge badge-success"><?= htmlspecialchars($booking['status']) ?></span></p>
                                <?php if (!empty($booking['total_amount'])): ?>
                                    <p><strong>Tổng tiền:</strong> <?= number_format($booking['total_amount'], 0, ',', '.') ?> VNĐ</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Tour Schedule -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Lịch trình Tour</h6>
                            </div>
                            <div class="card-body">
                                <?= nl2br(htmlspecialchars($booking['tour_description'] ?? 'Chưa có lịch trình chi tiết')) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Members List -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Danh sách thành viên (<?= count($members) ?>)</h6>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($members)): ?>
                            <div class="row">
                                <?php foreach ($members as $member): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card member-card <?= ($member['checked_in'] ?? 0) ? 'checked-in' : '' ?>">
                                            <div class="card-body">
                                                <h5 class="mb-2"><?= htmlspecialchars($member['full_name'] ?? 'N/A') ?></h5>
                                                <p class="mb-1"><i class="fas fa-phone"></i> <?= htmlspecialchars($member['phone'] ?? 'N/A') ?></p>
                                                <p class="mb-1"><i class="fas fa-id-card"></i> <?= htmlspecialchars($member['id_number'] ?? 'N/A') ?></p>
                                                <p class="mb-2"><i class="fas fa-birthday-cake"></i> <?= htmlspecialchars($member['date_of_birth'] ?? 'N/A') ?></p>
                                                <?php if (($member['checked_in'] ?? 0) == 1): ?>
                                                    <span class="badge badge-success"><i class="fas fa-check"></i> Đã check-in</span>
                                                <?php else: ?>
                                                    <a href="index.php?action=hdv_member_checkin&booking_id=<?= $bookingId ?>&member_id=<?= (int)$member['id'] ?>" 
                                                       class="btn btn-sm btn-primary">
                                                        <i class="fas fa-check"></i> Check-in
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted text-center">Chưa có thành viên nào</p>
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
