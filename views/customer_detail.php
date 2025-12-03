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

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

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
            <li class="nav-item active">
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
                <a class="nav-link" href="#">
                    <span>Booking assignment</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <li class="nav-item active">
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

            <li class="nav-item">
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
                    <h1 class="h3 mb-0 text-gray-800">Quản lý khách hàng</h1>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Chi tiết khách hàng</h6>
                    </div>
                    <div class="card-body">
                        <?php
                        // Kiểm tra và thoát nếu không có dữ liệu khách hàng
                        if (!isset($customer)) {
                            echo '<div class="alert alert-danger">Không tìm thấy thông tin khách hàng.</div>';
                            return;
                        }
                        ?>

                        <div class="container-fluid mt-4">
                            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                <a href="?action=customers" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Quay lại Danh sách Khách hàng
                                </a>
                            </div>

                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h1 class="h3 mb-0 text-gray-800">
                                        <i class="fas fa-user"></i> Chi tiết Khách hàng: <?= htmlspecialchars($customer['name']) ?>
                                    </h1>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 mb-4">
                                            <h5 class="text-primary mb-3">Ảnh đại diện</h5>
                                            <div class="text-center">
                                                <img src="assets/img/undraw_profile_1.svg"
                                                    alt="Customer Avatar"
                                                    class="img-fluid rounded-circle shadow-sm"
                                                    style="max-height: 200px; width: 200px; object-fit: cover;">
                                            </div>
                                        </div>

                                        <div class="col-lg-8">
                                            <h5 class="text-primary mb-3">Thông tin Cá nhân</h5>
                                            <ul class="list-group list-group-flush mb-4">
                                                <li class="list-group-item">
                                                    <strong>ID:</strong>
                                                    <span class="badge bg-info text-dark">#<?= htmlspecialchars($customer['id']) ?></span>
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Tên khách hàng:</strong>
                                                    <?= htmlspecialchars($customer['name']) ?>
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Số điện thoại:</strong>
                                                    <span class="text-primary fw-bold"><?= htmlspecialchars($customer['phone']) ?></span>
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Email:</strong>
                                                    <?= !empty($customer['email']) ? '<a href="mailto:' . htmlspecialchars($customer['email']) . '">' . htmlspecialchars($customer['email']) . '</a>' : '<span class="text-muted">Chưa cập nhật</span>' ?>
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Địa chỉ:</strong>
                                                    <?= !empty($customer['address']) ? htmlspecialchars($customer['address']) : '<span class="text-muted">Chưa cập nhật</span>' ?>
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Giới tính:</strong>
                                                    <?= !empty($customer['gender']) ? htmlspecialchars($customer['gender']) : '<span class="text-muted">Chưa cập nhật</span>' ?>
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Năm sinh:</strong>
                                                    <?= !empty($customer['year_of_birth']) ? htmlspecialchars($customer['year_of_birth']) : '<span class="text-muted">Chưa cập nhật</span>' ?>
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Loại giấy tờ:</strong>
                                                    <?= !empty($customer['id_type']) ? htmlspecialchars($customer['id_type']) : '<span class="text-muted">Chưa cập nhật</span>' ?>
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Số giấy tờ tùy thân:</strong>
                                                    <?= !empty($customer['id_number']) ? htmlspecialchars($customer['id_number']) : '<span class="text-muted">Chưa cập nhật</span>' ?>
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Tình trạng thanh toán:</strong>
                                                    <?= !empty($customer['payment_status']) ? htmlspecialchars($customer['payment_status']) : '<span class="text-muted">Chưa cập nhật</span>' ?>
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Yêu cầu cá nhân:</strong>
                                                    <?= !empty($customer['personal_requests']) ? nl2br(htmlspecialchars($customer['personal_requests'])) : '<span class="text-muted">Chưa cập nhật</span>' ?>
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Trạng thái check-in:</strong>
                                                    <?= !empty($customer['checkin_status']) ? htmlspecialchars($customer['checkin_status']) : '<span class="text-muted">Chưa cập nhật</span>' ?>
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Phân bổ phòng:</strong>
                                                    <?= !empty($customer['room_allocation']) ? htmlspecialchars($customer['room_allocation']) : '<span class="text-muted">Chưa cập nhật</span>' ?>
                                                </li>
                                            </ul>

                                            <h5 class="text-primary mt-4 mb-3">Lịch sử Giao dịch & Ghi chú</h5>
                                            <div class="p-3 bg-light rounded border">
                                                <?= !empty($customer['history_notes']) ? nl2br(htmlspecialchars($customer['history_notes'])) : '<span class="text-muted">Chưa có ghi chú nào</span>' ?>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <div class="d-flex justify-content-end">
                                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['username'] !== 'guide1'): ?>
                                        <a href="?action=customer_edit&id=<?= $customer['id'] ?>" class="btn btn-primary me-2">
                                            <i class="fas fa-edit"></i> Chỉnh sửa Khách hàng
                                        </a>
                                        <a href="?action=customer_delete&id=<?= $customer['id'] ?>" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Xóa Khách hàng
                                        </a>
                                        <?php endif; ?>
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