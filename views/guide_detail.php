<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title></title>

    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include 'includes/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php require_once 'views/includes/topbar.php'; ?>
                <!-- End of Topbar -->

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                            </a>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                            </a>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                                <img class="img-profile rounded-circle"
                                    src="assets/img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>


                </nav>

            </div>
            <!-- End of Main Content -->
            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Quản lý hướng dẫn viên</h1>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Chi tiết hướng dẫn viên</h6>
                    </div>
                    <div class="card-body">
                        <?php
                        // Kiểm tra và thoát nếu không có dữ liệu guide
                        if (!isset($guide)) {
                            echo '<div class="alert alert-danger">Không tìm thấy thông tin hướng dẫn viên.</div>';
                            return;
                        }
                        ?>

                        <div class="container-fluid mt-4">
                            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                <a href="?action=guides" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Quay lại Danh sách Hướng dẫn viên
                                </a>
                            </div>

                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h1 class="h3 mb-0 text-gray-800">
                                        <i class="fas fa-user"></i> Chi tiết Hướng dẫn viên: <?= htmlspecialchars($guide['name']) ?>
                                    </h1>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 mb-4">
                                            <h5 class="text-primary mb-3">Ảnh đại diện</h5>
                                            <div class="text-center">
                                                <?php if (!empty($guide['image'])): ?>
                                                    <img src="<?= htmlspecialchars($guide['image']) ?>"
                                                        alt="Guide Avatar"
                                                        class="img-fluid rounded-circle shadow-sm"
                                                        style="max-height: 200px; width: 200px; object-fit: cover;">
                                                <?php else: ?>
                                                    <img src="assets/img/undraw_profile_1.svg"
                                                        alt="Guide Avatar"
                                                        class="img-fluid rounded-circle shadow-sm"
                                                        style="max-height: 200px; width: 200px; object-fit: cover;">
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-8">
                                            <h5 class="text-primary mb-3">Thông tin Cá nhân</h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <ul class="list-group list-group-flush mb-4">
                                                        <li class="list-group-item">
                                                            <strong>ID:</strong>
                                                            <span class="btn btn-info btn-sm fw-bold px-3 py-2">#<?= htmlspecialchars($guide['id']) ?></span>
                                                        </li>
                                                        <?php if (!empty($guide['user_id'])): ?>
                                                        <li class="list-group-item">
                                                            <strong>ID Người dùng:</strong>
                                                            <span class="btn btn-secondary btn-sm fw-bold px-3 py-2">#<?= htmlspecialchars($guide['user_id']) ?></span>
                                                        </li>
                                                        <?php endif; ?>
                                                        <li class="list-group-item">
                                                            <strong>Tên hướng dẫn viên:</strong>
                                                            <?= htmlspecialchars($guide['name']) ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Số điện thoại:</strong>
                                                            <span class="text-primary fw-bold"><?= htmlspecialchars($guide['phone']) ?></span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Email:</strong>
                                                            <?= !empty($guide['email']) ? '<a href="mailto:' . htmlspecialchars($guide['email']) . '">' . htmlspecialchars($guide['email']) . '</a>' : '<span class="text-muted">Chưa cập nhật</span>' ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Ngày sinh:</strong>
                                                            <?= !empty($guide['birth_date']) ? date('d/m/Y', strtotime($guide['birth_date'])) : '<span class="text-muted">Chưa cập nhật</span>' ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Địa chỉ:</strong>
                                                            <?= !empty($guide['address']) ? htmlspecialchars($guide['address']) : '<span class="text-muted">Chưa cập nhật</span>' ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Liên hệ khẩn cấp:</strong>
                                                            <?= !empty($guide['emergency_contact']) ? htmlspecialchars($guide['emergency_contact']) : '<span class="text-muted">Chưa cập nhật</span>' ?>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <ul class="list-group list-group-flush mb-4">
                                                        <li class="list-group-item">
                                                            <strong>Trạng thái:</strong>
                                                            <span class="btn btn-<?= $guide['status'] == 'Active' ? 'success' : ($guide['status'] == 'Inactive' ? 'secondary' : 'warning') ?> btn-sm fw-bold px-3 py-2">
                                                                <?= htmlspecialchars($guide['status']) ?>
                                                            </span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Sẵn sàng:</strong>
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
                                                        <li class="list-group-item">
                                                            <strong>Loại HDV:</strong>
                                                            <span class="btn btn-primary btn-sm fw-bold px-3 py-2">
                                                                <?= htmlspecialchars($guide['guide_type'] ?? 'Nội địa') ?>
                                                            </span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Kinh nghiệm:</strong>
                                                            <?= !empty($guide['experience_years']) ? htmlspecialchars($guide['experience_years']) . ' năm' : '<span class="text-muted">Chưa cập nhật</span>' ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Đánh giá:</strong>
                                                            <?php if (!empty($guide['performance_rating'])): ?>
                                                                <span class="text-warning">
                                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                        <i class="fas fa-star<?= $i <= $guide['performance_rating'] ? '' : '-half-alt' ?>"></i>
                                                                    <?php endfor; ?>
                                                                    (<?= number_format($guide['performance_rating'], 1) ?>/5)
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="text-muted">Chưa đánh giá</span>
                                                            <?php endif; ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Sức khỏe:</strong>
                                                            <span class="btn btn-<?= $guide['health_status'] == 'Excellent' ? 'success' : ($guide['health_status'] == 'Good' ? 'info' : 'warning') ?> btn-sm fw-bold px-3 py-2">
                                                                <?= htmlspecialchars($guide['health_status'] ?? 'Good') ?>
                                                            </span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Ngày gia nhập:</strong>
                                                            <?= !empty($guide['join_date']) ? date('d/m/Y', strtotime($guide['join_date'])) : '<span class="text-muted">Chưa cập nhật</span>' ?>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <h5 class="text-primary mt-4 mb-3">Thông tin Chuyên môn</h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="p-3 bg-light rounded border mb-3">
                                                        <strong>Giấy phép:</strong><br>
                                                        <?= !empty($guide['license_info']) ? nl2br(htmlspecialchars($guide['license_info'])) : '<span class="text-muted">Chưa có thông tin giấy phép</span>' ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="p-3 bg-light rounded border mb-3">
                                                        <strong>Ngôn ngữ:</strong><br>
                                                        <?= !empty($guide['languages']) ? htmlspecialchars($guide['languages']) : '<span class="text-muted">Chưa cập nhật</span>' ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="p-3 bg-light rounded border mb-3">
                                                <strong>Chứng chỉ chuyên môn:</strong><br>
                                                <?= !empty($guide['certificates']) ? nl2br(htmlspecialchars($guide['certificates'])) : '<span class="text-muted">Chưa có chứng chỉ nào</span>' ?>
                                            </div>

                                            <div class="p-3 bg-light rounded border mb-3">
                                                <strong>Chuyên môn/Chuyên tuyến:</strong><br>
                                                <?= !empty($guide['specialization']) ? htmlspecialchars($guide['specialization']) : '<span class="text-muted">Chưa cập nhật</span>' ?>
                                            </div>

                                            <?php if (!empty($guide['notes'])): ?>
                                            <div class="p-3 bg-light rounded border">
                                                <strong>Ghi chú:</strong><br>
                                                <?= nl2br(htmlspecialchars($guide['notes'])) ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <a href="?action=guide_schedule&id=<?= $guide['id'] ?>" class="btn btn-warning me-2">
                                                <i class="fas fa-calendar"></i> Lịch làm việc
                                            </a>
                                            <a href="?action=guide_performance&id=<?= $guide['id'] ?>" class="btn btn-success me-2">
                                                <i class="fas fa-chart-line"></i> Hiệu suất
                                            </a>
                                        </div>
                                        <div>
                                            <?php if (isset($_SESSION['user']) && $_SESSION['user']['username'] !== 'guide1'): ?>
                                            <a href="?action=guide_edit&id=<?= $guide['id'] ?>" class="btn btn-primary me-2">
                                                <i class="fas fa-edit"></i> Chỉnh sửa Hướng dẫn viên
                                            </a>
                                            <a href="?action=guide_delete&id=<?= $guide['id'] ?>" class="btn btn-danger">
                                                <i class="fas fa-trash"></i> Xóa Hướng dẫn viên
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="assets/vendor/jquery/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="assets/js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="assets/vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="assets/js/demo/chart-area-demo.js"></script>
<script src="assets/js/demo/chart-pie-demo.js"></script>

</body>

</html>