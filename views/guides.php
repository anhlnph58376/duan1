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

    <!-- Phông chữ tùy chỉnh cho template này-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Styles tùy chỉnh cho template này-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">

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

                <!-- Begin Page Content -->
            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Quản lý hướng dẫn viên</h1>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Danh sách hướng dẫn viên</h6>
                        <a href="?action=account_management" class="btn btn-primary">Quản lý tài khoản</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên HDV</th>
                                        <th>SĐT</th>
                                        <th>Loại</th>
                                        <th>Kinh nghiệm</th>
                                        <th>Đánh giá</th>
                                        <th>Sức khỏe</th>
                                        <th>Sẵn sàng</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($guides as $guide) { ?>
                                        <tr>
                                            <td><?= $guide['id'] ?></td>
                                            <td>
                                                <strong><?= $guide['name'] ?></strong><br>
                                                <small class="text-muted"><?= $guide['email'] ?? 'N/A' ?></small>
                                            </td>
                                            <td><?= $guide['phone'] ?></td>
                                            <td>
                                                <span class="btn btn-primary btn-sm fw-bold px-3 py-2">
                                                    <?= $guide['guide_type'] ?? 'Nội địa' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (!empty($guide['experience_years'])): ?>
                                                    <?= $guide['experience_years'] ?> năm
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($guide['performance_rating'])): ?>
                                                    <span class="text-warning">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <i class="fas fa-star<?= $i <= $guide['performance_rating'] ? '' : '-half-alt' ?>"></i>
                                                        <?php endfor; ?>
                                                    </span>
                                                    <br><small>(<?= number_format($guide['performance_rating'], 1) ?>)</small>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="btn btn-<?= $guide['health_status'] == 'Excellent' ? 'success' : ($guide['health_status'] == 'Good' ? 'info' : 'warning') ?> btn-sm fw-bold px-3 py-2">
                                                    <?php
                                                    $health_map = [
                                                        'Excellent' => 'Xuất sắc',
                                                        'Good' => 'Tốt',
                                                        'Fair' => 'Khá',
                                                        'Poor' => 'Kém',
                                                        'Critical' => 'Nghiêm trọng'
                                                    ];
                                                    echo htmlspecialchars($health_map[$guide['health_status']] ?? 'Tốt');
                                                    ?>
                                                </span>
                                            </td>
                                            <td>
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
                                            </td>
                                            <td class="align-middle">
                                                <div class="btn-group" role="group">
                                                    <a href="?action=guide_detail&id=<?= $guide['id'] ?>" class="btn btn-info btn-circle btn-sm" title="Xem chi tiết">
                                                        <i class="fas fa-search"></i>
                                                    </a>
                                                    <a href="?action=guide_edit&id=<?= $guide['id'] ?>" class="btn btn-primary btn-circle btn-sm" title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="?action=guide_schedule&id=<?= $guide['id'] ?>" class="btn btn-warning btn-circle btn-sm" title="Lịch làm việc">
                                                        <i class="fas fa-calendar"></i>
                                                    </a>
                                                    <a href="?action=guide_performance&id=<?= $guide['id'] ?>" class="btn btn-success btn-circle btn-sm" title="Hiệu suất">
                                                        <i class="fas fa-chart-line"></i>
                                                    </a>
                                                    <a href="?action=guide_delete&id=<?= $guide['id'] ?>" class="btn btn-danger btn-circle btn-sm" title="Xóa"
                                                       onclick="return confirm('Bạn có chắc muốn xóa hướng dẫn viên này?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
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
                    <a class="btn btn-primary" href="?action=logout">Đăng Xuất</a>
                </div>
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