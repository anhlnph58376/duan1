<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Hồ Sơ HDV</title>
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
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                <?= htmlspecialchars($_SESSION['user_name'] ?? 'HDV', ENT_QUOTES, 'UTF-8') ?>
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
                $guideId = $_SESSION['tour_guide_id'] ?? null;
                $guide = null;

                if ($guideId) {
                    $guideModel = new Guide();
                    $guide = $guideModel->getGuideById($guideId);
                } else if (isset($_SESSION['user_id'])) {
                    // If no guide ID in session, try to find it from the current user
                    $userId = $_SESSION['user_id'];
                    $guideModel = new Guide();
                    $guide = $guideModel->getGuideByUserId($userId);

                    if ($guide && !empty($guide['id'])) {
                        $guideId = $guide['id'];
                        $_SESSION['tour_guide_id'] = $guideId; // Update session for future requests
                    }
                }
                ?>

                <h1 class="h3 mb-4 text-gray-800">Hồ Sơ Hướng Dẫn Viên</h1>

                <?php if ($guide): ?>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 bg-primary text-white">
                                    <h6 class="m-0 font-weight-bold">Thông Tin Cá Nhân</h6>
                                </div>
                                <div class="card-body text-center">
                                    <?php if (!empty($guide['image']) && file_exists($guide['image'])): ?>
                                        <img src="<?= htmlspecialchars($guide['image']) ?>" class="rounded-circle mb-3"
                                             style="width: 150px; height: 150px; object-fit: cover;" alt="Photo">
                                    <?php else: ?>
                                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                             style="width: 150px; height: 150px;">
                                            <i class="fas fa-user fa-4x text-gray-400"></i>
                                        </div>
                                    <?php endif; ?>
                                    <h5 class="font-weight-bold"><?= htmlspecialchars($guide['name']) ?></h5>
                                    <p class="text-muted">Mã: <?= htmlspecialchars($guide['id']) ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Thông Tin Chi Tiết</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Email:</strong>
                                            <p><?= htmlspecialchars($guide['email'] ?? 'N/A') ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Số điện thoại:</strong>
                                            <p><?= htmlspecialchars($guide['phone'] ?? 'N/A') ?></p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Ngày sinh:</strong>
                                            <p><?= $guide['birth_date'] ? date('d/m/Y', strtotime($guide['birth_date'])) : 'N/A' ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Giới tính:</strong>
                                            <p><?= htmlspecialchars($guide['gender'] ?? 'N/A') ?></p>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Địa chỉ:</strong>
                                        <p><?= htmlspecialchars($guide['address'] ?? 'N/A') ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Thông Tin Nghề Nghiệp</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Loại HDV:</strong>
                                            <p><?= htmlspecialchars($guide['guide_type'] ?? 'N/A') ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Chuyên môn:</strong>
                                            <p><?= htmlspecialchars($guide['specialization'] ?? 'N/A') ?></p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Số thẻ HDV:</strong>
                                            <p><?= htmlspecialchars($guide['license_info'] ?? 'N/A') ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Ngày cấp:</strong>
                                            <p>N/A</p>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Ngôn ngữ:</strong>
                                        <p><?= htmlspecialchars($guide['languages'] ?? 'N/A') ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Tình Trạng Sức Khỏe</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Trạng thái:</strong>
                                            <p>
                                                <?php
                                                $healthStatus = $guide['health_status'] ?? 'Good';
                                                $badgeClass = match($healthStatus) {
                                                    'Excellent' => 'success',
                                                    'Good' => 'info',
                                                    'Fair' => 'warning',
                                                    'Poor' => 'danger',
                                                    'Critical' => 'dark',
                                                    default => 'secondary'
                                                };
                                                ?>
                                                <span class="badge badge-<?= $badgeClass ?>"><?= htmlspecialchars($healthStatus) ?></span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Ghi chú sức khỏe:</strong>
                                            <p><?= htmlspecialchars($guide['notes'] ?? 'Không có') ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        Không tìm thấy thông tin hướng dẫn viên.
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
