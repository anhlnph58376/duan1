<?php if (isset($_SESSION['error_phone'])): ?>
    <div class="alert alert-danger mb-4" role="alert">
        <?= $_SESSION['error_phone'] ?>
    </div>
<?php
    // Xóa session lỗi ngay sau khi hiển thị
    unset($_SESSION['error_phone']);
endif;

// Lấy dữ liệu cũ (nếu có)
$old_data = $_SESSION['old_data'] ?? [];
unset($_SESSION['old_data']); // Xóa sau khi lấy ra
?>
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
                        <h6 class="m-0 font-weight-bold text-primary">Thêm khách hàng</h6>
                    </div>
                    <div class="card-body">
                        <form action="?action=addCustomer" method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên khách hàng:</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= $old_data['name'] ?? '' ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại:</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?= $old_data['phone'] ?? '' ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= $old_data['email'] ?? '' ?>">
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ:</label>
                                <textarea class="form-control" id="address" name="address" rows="3"><?= $old_data['address'] ?? '' ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="gender" class="form-label">Giới tính:</label>
                                <select class="form-control" id="gender" name="gender">
                                    <option value="">Chọn giới tính</option>
                                    <option value="Nam" <?= ($old_data['gender'] ?? '') == 'Nam' ? 'selected' : '' ?>>Nam</option>
                                    <option value="Nữ" <?= ($old_data['gender'] ?? '') == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                                    <option value="Khác" <?= ($old_data['gender'] ?? '') == 'Khác' ? 'selected' : '' ?>>Khác</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="year_of_birth" class="form-label">Năm sinh:</label>
                                <input type="number" class="form-control" id="year_of_birth" name="year_of_birth" value="<?= $old_data['year_of_birth'] ?? '' ?>" min="1900" max="<?= date('Y') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="id_type" class="form-label">Loại giấy tờ:</label>
                                <select class="form-control" id="id_type" name="id_type">
                                    <option value="">Chọn loại giấy tờ</option>
                                    <option value="Căn cước công dân" <?= ($old_data['id_type'] ?? '') == 'Căn cước công dân' ? 'selected' : '' ?>>Căn cước công dân</option>
                                    <option value="Chứng minh nhân dân" <?= ($old_data['id_type'] ?? '') == 'Chứng minh nhân dân' ? 'selected' : '' ?>>Chứng minh nhân dân</option>
                                    <option value="Hộ chiếu" <?= ($old_data['id_type'] ?? '') == 'Hộ chiếu' ? 'selected' : '' ?>>Hộ chiếu</option>
                                    <option value="Giấy phép lái xe" <?= ($old_data['id_type'] ?? '') == 'Giấy phép lái xe' ? 'selected' : '' ?>>Giấy phép lái xe</option>
                                    <option value="Khác" <?= ($old_data['id_type'] ?? '') == 'Khác' ? 'selected' : '' ?>>Khác</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="id_number" class="form-label">Số giấy tờ tùy thân:</label>
                                <input type="text" class="form-control" id="id_number" name="id_number" value="<?= $old_data['id_number'] ?? '' ?>" placeholder="Nhập số giấy tờ">
                            </div>

                            <div class="mb-3">
                                <label for="payment_status" class="form-label">Tình trạng thanh toán:</label>
                                <select class="form-control" id="payment_status" name="payment_status">
                                    <option value="">Chọn tình trạng</option>
                                    <option value="Chưa thanh toán" <?= ($old_data['payment_status'] ?? '') == 'Chưa thanh toán' ? 'selected' : '' ?>>Chưa thanh toán</option>
                                    <option value="Đã thanh toán" <?= ($old_data['payment_status'] ?? '') == 'Đã thanh toán' ? 'selected' : '' ?>>Đã thanh toán</option>
                                    <option value="Thanh toán một phần" <?= ($old_data['payment_status'] ?? '') == 'Thanh toán một phần' ? 'selected' : '' ?>>Thanh toán một phần</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="personal_requests" class="form-label">Yêu cầu cá nhân:</label>
                                <textarea class="form-control" id="personal_requests" name="personal_requests" rows="3"><?= $old_data['personal_requests'] ?? '' ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="checkin_status" class="form-label">Trạng thái check-in:</label>
                                <select class="form-control" id="checkin_status" name="checkin_status">
                                    <option value="">Chọn trạng thái</option>
                                    <option value="Chưa đến" <?= ($old_data['checkin_status'] ?? '') == 'Chưa đến' ? 'selected' : '' ?>>Chưa đến</option>
                                    <option value="Đã đến" <?= ($old_data['checkin_status'] ?? '') == 'Đã đến' ? 'selected' : '' ?>>Đã đến</option>
                                    <option value="Vắng mặt" <?= ($old_data['checkin_status'] ?? '') == 'Vắng mặt' ? 'selected' : '' ?>>Vắng mặt</option>
                                </select>
                            </div>

                            <!-- Phân bổ phòng removed as requested -->

                            <div class="mb-3">
                                <label for="history_notes" class="form-label">Ghi chú lịch sử:</label>
                                <textarea class="form-control" id="history_notes" name="history_notes" rows="4"><?= $old_data['history_notes'] ?? '' ?></textarea>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="?action=customers" class="btn btn-secondary me-2">Hủy bỏ</a>
                                <button type="submit" class="btn btn-primary">Thêm khách hàng</button>
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