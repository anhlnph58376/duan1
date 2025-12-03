<?php
$guideId = $_SESSION['tour_guide_id'] ?? null;
$guide = null;
$assigned_tours = [];

if ($guideId) {
    $guideModel = new Guide();
    $guide = $guideModel->getGuideById($guideId);
    $assigned_tours = $guideModel->getGuideAssignedTours($guideId);
} else if (isset($_SESSION['user_id'])) {
    // If no guide ID in session, try to find it from the current user
    $userId = $_SESSION['user_id'];
    $guideModel = new Guide();
    $guide = $guideModel->getGuideByUserId($userId);

    if ($guide && !empty($guide['id'])) {
        $guideId = $guide['id'];
        $_SESSION['tour_guide_id'] = $guideId; // Update session for future requests
        $assigned_tours = $guideModel->getGuideAssignedTours($guideId);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Lịch làm việc của tôi - HDV</title>
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
        <li class="nav-item">
            <a class="nav-link" href="index.php?action=hdv_bookings">
                <i class="fas fa-fw fa-calendar-check"></i>
                <span>Bookings của tôi</span>
            </a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="index.php?action=hdv_schedule">
                <i class="fas fa-fw fa-calendar-alt"></i>
                <span>Lịch làm việc</span>
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
                <h1 class="h3 mb-4 text-gray-800">Lịch làm việc của tôi</h1>

                <?php if ($guide): ?>
                    <div class="row">
                        <!-- Guide Info Card -->
                        <div class="col-lg-4 mb-4">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Thông tin của tôi</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <?php if (!empty($guide['image']) && file_exists($guide['image'])): ?>
                                            <img src="<?= htmlspecialchars($guide['image']) ?>" alt="Avatar" class="img-fluid rounded-circle" style="width: 80px; height: 80px;">
                                        <?php else: ?>
                                            <img src="assets/img/undraw_profile_1.svg" alt="Avatar" class="img-fluid rounded-circle" style="width: 80px; height: 80px;">
                                        <?php endif; ?>
                                    </div>
                                    <h5 class="text-center mb-3"><?= htmlspecialchars($guide['name']) ?></h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <strong>SĐT:</strong> <?= htmlspecialchars($guide['phone']) ?>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Email:</strong> <?= htmlspecialchars($guide['email'] ?? 'N/A') ?>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Loại HDV:</strong>
                                            <span class="btn btn-primary btn-sm fw-bold px-3 py-2"><?= htmlspecialchars($guide['guide_type'] ?? 'N/A') ?></span>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Trạng thái:</strong>
                                            <span class="btn btn-<?= $guide['availability_status'] == 'Available' ? 'success' : ($guide['availability_status'] == 'Busy' ? 'warning' : ($guide['availability_status'] == 'On Leave' ? 'danger' : 'secondary')) ?> btn-sm fw-bold px-3 py-2">
                                                <?php
                                                $status_map = [
                                                    'Available' => 'Sẵn sàng',
                                                    'Busy' => 'Bận',
                                                    'On Leave' => 'Nghỉ phép',
                                                    'Unavailable' => 'Không khả dụng'
                                                ];
                                                echo htmlspecialchars($status_map[$guide['availability_status']] ?? 'Sẵn sàng');
                                                ?>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Management -->
                        <div class="col-lg-8 mb-4">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Trạng thái sẵn sàng của tôi</h6>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Lưu ý:</strong> Hệ thống sử dụng trạng thái sẵn sàng chung cho hướng dẫn viên.
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card border-left-success shadow h-100 py-2">
                                                <div class="card-body">
                                                    <div class="row no-gutters align-items-center">
                                                        <div class="col mr-2">
                                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                                Trạng thái hiện tại</div>
                                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                                <span class="btn btn-<?= $guide['availability_status'] == 'Available' ? 'success' : ($guide['availability_status'] == 'Busy' ? 'warning' : ($guide['availability_status'] == 'On Leave' ? 'danger' : 'secondary')) ?> btn-sm fw-bold px-3 py-2">
                                                                    <?php
                                                                    $status_map = [
                                                                        'Available' => 'Sẵn sàng',
                                                                        'Busy' => 'Bận',
                                                                        'On Leave' => 'Nghỉ phép',
                                                                        'Unavailable' => 'Không khả dụng'
                                                                    ];
                                                                    echo htmlspecialchars($status_map[$guide['availability_status']] ?? 'Sẵn sàng');
                                                                    ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card border-left-info shadow h-100 py-2">
                                                <div class="card-body">
                                                    <div class="row no-gutters align-items-center">
                                                        <div class="col mr-2">
                                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                                Cập nhật lần cuối</div>
                                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                                Hôm nay
                                                            </div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <h6 class="font-weight-bold text-primary mb-3">Cập nhật trạng thái của tôi</h6>
                                    <form action="index.php?action=hdv_update_availability" method="POST" class="mb-3">
                                        <input type="hidden" name="guide_id" value="<?= $guide['id'] ?>">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <select name="availability_status" class="form-control" required>
                                                    <option value="Available" <?= ($guide['availability_status'] ?? 'Available') == 'Available' ? 'selected' : '' ?>>Sẵn sàng</option>
                                                    <option value="Busy" <?= ($guide['availability_status'] ?? '') == 'Busy' ? 'selected' : '' ?>>Bận</option>
                                                    <option value="On Leave" <?= ($guide['availability_status'] ?? '') == 'On Leave' ? 'selected' : '' ?>>Nghỉ phép</option>
                                                    <option value="Unavailable" <?= ($guide['availability_status'] ?? '') == 'Unavailable' ? 'selected' : '' ?>>Không khả dụng</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-primary btn-block">
                                                    <i class="fas fa-save"></i> Cập nhật
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Assigned Tours Section -->
                        <div class="row">
                            <div class="col-lg-12 mb-4">
                                <div class="card shadow">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Tour đã được gán cho tôi</h6>
                                    </div>
                                    <div class="card-body">
                                        <?php if (!empty($assigned_tours)): ?>
                                            <div class="table-responsive">
                                                <table class="table table-bordered" width="100%" cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th>Mã Booking</th>
                                                            <th>Tên Tour</th>
                                                            <th>Ngày đặt</th>
                                                            <th>Trạng thái</th>
                                                            <th>Ngày khởi hành</th>
                                                            <th>Hành động</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($assigned_tours as $tour): ?>
                                                            <tr>
                                                                <td>
                                                                    <a href="index.php?action=hdv_booking_detail&id=<?= $tour['booking_id'] ?>" class="btn btn-sm btn-outline-primary">
                                                                        <?= htmlspecialchars($tour['booking_code']) ?>
                                                                    </a>
                                                                </td>
                                                                <td><?= htmlspecialchars($tour['tour_name'] ?? 'N/A') ?></td>
                                                                <td><?= date('d/m/Y', strtotime($tour['booking_date'])) ?></td>
                                                                <td>
                                                                    <span class="badge
                                                                        <?= $tour['booking_status'] == 'Completed' ? 'badge-success' :
                                                                           ($tour['booking_status'] == 'Canceled' ? 'badge-danger' :
                                                                           ($tour['booking_status'] == 'Deposited' ? 'badge-info' : 'badge-warning')) ?>">
                                                                        <?= htmlspecialchars($tour['booking_status']) ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <?php if (!empty($tour['departure_date'])): ?>
                                                                        <?= date('d/m/Y', strtotime($tour['departure_date'])) ?>
                                                                    <?php else: ?>
                                                                        Chưa xác định
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <a href="index.php?action=hdv_booking_detail&id=<?= $tour['booking_id'] ?>" class="btn btn-sm btn-info" title="Xem chi tiết booking">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                    <?php if (!empty($tour['tour_id'])): ?>
                                                                        <a href="index.php?action=tour_detail&id=<?= $tour['tour_id'] ?>" class="btn btn-sm btn-success" title="Xem chi tiết tour">
                                                                            <i class="fas fa-map-marked-alt"></i>
                                                                        </a>
                                                                    <?php endif; ?>
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
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle"></i> Hiện tại chưa có tour nào được gán cho bạn.
                                                <?php if (isset($guide) && !empty($guide['id'])): ?>
                                                    <br><small class="text-muted">Hệ thống đang kiểm tra các booking được gán...</small>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        Không tìm thấy thông tin hướng dẫn viên. Vui lòng liên hệ quản trị viên.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="assets/vendor/jquery/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/sb-admin-2.min.js"></script>
</body>
</html>