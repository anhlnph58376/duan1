<?php if (!isset($guide) || !isset($performance)) { ?>
    <div class="alert alert-danger">Không tìm thấy thông tin hướng dẫn viên hoặc dữ liệu hiệu suất.</div>
    <?php return; } ?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Hiệu suất - <?= htmlspecialchars($guide['name']) ?></title>

    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= BASE_URL ?>">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="<?= BASE_URL ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item-->
            <li class="nav-item">
                <a class="nav-link" href="?action=tours">
                    <span>Quản lý tour</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link" href="?action=bookings">
                    <span>Quản lý booking</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link" href="?action=customers">
                    <span>Quản lý khách hàng</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span>Quản lý đoàn</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <li class="nav-item active">
                <a class="nav-link" href="?action=guides">
                    <span>Quản lý hướng dẫn viên</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span>Quản lý tài khoản</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                                <img class="img-profile rounded-circle" src="assets/img/undraw_profile.svg">
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Begin Page Content -->
            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Hiệu suất: <?= htmlspecialchars($guide['name']) ?></h1>
                </div>

                <!-- Performance Overview Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Tổng số logs</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= $performance['total_logs'] ?? 0 ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Đánh giá trung bình</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php if (!empty($performance['avg_rating'])): ?>
                                                <?= number_format($performance['avg_rating'], 1) ?>/5
                                            <?php else: ?>
                                                N/A
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-star fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Tổng khách hàng</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= $performance['total_customers'] ?? 0 ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Kinh nghiệm</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= $guide['experience_years'] ?? 0 ?> năm
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-award fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Performance Details -->
                    <div class="col-lg-8 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Chi tiết hiệu suất</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Chỉ số</th>
                                                <th>Giá trị</th>
                                                <th>Đánh giá</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Tổng số logs hiệu suất</td>
                                                <td><strong><?= $performance['total_logs'] ?? 0 ?></strong></td>
                                                <td>
                                                    <?php
                                                    $logs = $performance['total_logs'] ?? 0;
                                                    if ($logs >= 20) echo '<span class="badge badge-success">Rất tích cực</span>';
                                                    elseif ($logs >= 10) echo '<span class="badge badge-info">Tích cực</span>';
                                                    elseif ($logs >= 5) echo '<span class="badge badge-warning">Khá</span>';
                                                    else echo '<span class="badge badge-secondary">Đang phát triển</span>';
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Đánh giá trung bình</td>
                                                <td>
                                                    <?php if (!empty($performance['avg_rating'])): ?>
                                                        <strong><?= number_format($performance['avg_rating'], 1) ?>/5</strong>
                                                        <div class="mt-1">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <i class="fas fa-star<?= $i <= $performance['avg_rating'] ? '' : '-half-alt' ?> text-warning"></i>
                                                            <?php endfor; ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted">Chưa có đánh giá</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $rating = $performance['avg_rating'] ?? 0;
                                                    if ($rating >= 4.5) echo '<span class="badge badge-success">Xuất sắc</span>';
                                                    elseif ($rating >= 4.0) echo '<span class="badge badge-info">Tốt</span>';
                                                    elseif ($rating >= 3.5) echo '<span class="badge badge-warning">Khá</span>';
                                                    elseif ($rating >= 3.0) echo '<span class="badge badge-secondary">Trung bình</span>';
                                                    else echo '<span class="badge badge-danger">Cần cải thiện</span>';
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tổng số khách hàng đã phục vụ</td>
                                                <td><strong><?= $performance['total_customers'] ?? 0 ?></strong></td>
                                                <td>
                                                    <?php
                                                    $customers = $performance['total_customers'] ?? 0;
                                                    if ($customers >= 50) echo '<span class="badge badge-success">Xuất sắc</span>';
                                                    elseif ($customers >= 20) echo '<span class="badge badge-info">Tốt</span>';
                                                    elseif ($customers >= 10) echo '<span class="badge badge-warning">Khá</span>';
                                                    else echo '<span class="badge badge-secondary">Đang phát triển</span>';
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Kinh nghiệm làm việc</td>
                                                <td><strong><?= $guide['experience_years'] ?? 0 ?> năm</strong></td>
                                                <td>
                                                    <?php
                                                    $exp = $guide['experience_years'] ?? 0;
                                                    if ($exp >= 10) echo '<span class="badge badge-success">Chuyên gia</span>';
                                                    elseif ($exp >= 5) echo '<span class="badge badge-info">Kinh nghiệm</span>';
                                                    elseif ($exp >= 2) echo '<span class="badge badge-warning">Trung cấp</span>';
                                                    else echo '<span class="badge badge-secondary">Mới</span>';
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tình trạng sức khỏe</td>
                                                <td>
                                                    <span class="badge badge-<?= $guide['health_status'] == 'Excellent' ? 'success' : ($guide['health_status'] == 'Good' ? 'info' : 'warning') ?>">
                                                        <?= htmlspecialchars($guide['health_status'] ?? 'Good') ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php
                                                    $health = $guide['health_status'] ?? 'Good';
                                                    if ($health == 'Excellent') echo '<span class="badge badge-success">Tốt</span>';
                                                    elseif ($health == 'Good') echo '<span class="badge badge-info">Khá</span>';
                                                    else echo '<span class="badge badge-warning">Cần theo dõi</span>';
                                                    ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Performance Logs -->
                        <div class="card shadow mt-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Lịch sử đánh giá gần đây</h6>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($performance['recent_logs'])): ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Ngày</th>
                                                    <th>Đánh giá</th>
                                                    <th>Nhận xét</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach (array_slice($performance['recent_logs'], 0, 10) as $log): ?>
                                                    <tr>
                                                        <td><?= date('d/m/Y', strtotime($log['log_date'])) ?></td>
                                                        <td>
                                                            <?php if ($log['rating']): ?>
                                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                    <i class="fas fa-star<?= $i <= $log['rating'] ? '' : '-half-alt' ?> text-warning"></i>
                                                                <?php endfor; ?>
                                                                <small>(<?= $log['rating'] ?>/5)</small>
                                                            <?php else: ?>
                                                                <span class="text-muted">-</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?= !empty($log['feedback']) ? htmlspecialchars(substr($log['feedback'], 0, 50)) . (strlen($log['feedback']) > 50 ? '...' : '') : '-' ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-chart-line fa-3x mb-3"></i>
                                        <p>Chưa có dữ liệu đánh giá</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Guide Profile Summary -->
                    <div class="col-lg-4 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Hồ sơ hướng dẫn viên</h6>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <img src="assets/img/undraw_profile_1.svg" alt="Avatar" class="img-fluid rounded-circle" style="width: 80px; height: 80px;">
                                </div>
                                <h5 class="text-center mb-3"><?= htmlspecialchars($guide['name']) ?></h5>

                                <div class="mb-3">
                                    <strong>Loại HDV:</strong><br>
                                    <span class="badge bg-primary"><?= htmlspecialchars($guide['guide_type'] ?? 'Nội địa') ?></span>
                                </div>

                                <div class="mb-3">
                                    <strong>Chuyên môn:</strong><br>
                                    <span class="text-muted small">
                                        <?= !empty($guide['specialization']) ? htmlspecialchars($guide['specialization']) : 'Chưa cập nhật' ?>
                                    </span>
                                </div>

                                <div class="mb-3">
                                    <strong>Ngôn ngữ:</strong><br>
                                    <span class="text-muted small">
                                        <?= !empty($guide['languages']) ? htmlspecialchars($guide['languages']) : 'Chưa cập nhật' ?>
                                    </span>
                                </div>

                                <div class="mb-3">
                                    <strong>Ngày gia nhập:</strong><br>
                                    <span class="text-muted small">
                                        <?= !empty($guide['join_date']) ? date('d/m/Y', strtotime($guide['join_date'])) : 'Chưa cập nhật' ?>
                                    </span>
                                </div>

                                <hr>

                                <div class="d-grid gap-2">
                                    <a href="?action=guide_detail&id=<?= $guide['id'] ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-user"></i> Xem hồ sơ đầy đủ
                                    </a>
                                    <a href="?action=guide_schedule&id=<?= $guide['id'] ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-calendar"></i> Xem lịch làm việc
                                    </a>
                                    <a href="?action=guides" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="card shadow mt-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Thao tác nhanh</h6>
                            </div>
                            <div class="card-body">
                                <button class="btn btn-success btn-block mb-2" onclick="updateHealthStatus('Excellent')">
                                    <i class="fas fa-heartbeat"></i> Cập nhật sức khỏe tốt
                                </button>
                                <button class="btn btn-warning btn-block mb-2" onclick="updateHealthStatus('Fair')">
                                    <i class="fas fa-exclamation-triangle"></i> Báo cáo vấn đề sức khỏe
                                </button>
                                <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#addPerformanceModal">
                                    <i class="fas fa-plus"></i> Thêm đánh giá
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Performance Modal -->
            <div class="modal fade" id="addPerformanceModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Thêm đánh giá hiệu suất</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <form action="?action=add_performance_log" method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="guide_id" value="<?= $guide['id'] ?>">
                                <div class="form-group">
                                    <label for="rating">Đánh giá (1-5):</label>
                                    <select class="form-control" id="rating" name="rating" required>
                                        <option value="">Chọn đánh giá</option>
                                        <option value="5">5 - Xuất sắc</option>
                                        <option value="4">4 - Tốt</option>
                                        <option value="3">3 - Khá</option>
                                        <option value="2">2 - Trung bình</option>
                                        <option value="1">1 - Cần cải thiện</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="feedback">Nhận xét:</label>
                                    <textarea class="form-control" id="feedback" name="feedback" rows="3" placeholder="Nhận xét về hiệu suất làm việc..."></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="log_date">Ngày đánh giá:</label>
                                    <input type="date" class="form-control" id="log_date" name="log_date" value="<?= date('Y-m-d') ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-primary">Lưu đánh giá</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Nhóm 1: &copy; Cao Đẳng FPT Polytechnic</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="assets/js/sb-admin-2.min.js"></script>

    <script>
        function updateHealthStatus(status) {
            if (confirm('Bạn có muốn cập nhật tình trạng sức khỏe của hướng dẫn viên này?')) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '?action=update_health_status';

                var guideIdInput = document.createElement('input');
                guideIdInput.type = 'hidden';
                guideIdInput.name = 'guide_id';
                guideIdInput.value = '<?= $guide['id'] ?>';

                var statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'health_status';
                statusInput.value = status;

                var notesInput = document.createElement('input');
                notesInput.type = 'hidden';
                notesInput.name = 'health_notes';
                notesInput.value = 'Cập nhật từ trang hiệu suất';

                form.appendChild(guideIdInput);
                form.appendChild(statusInput);
                form.appendChild(notesInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

</body>
</html>