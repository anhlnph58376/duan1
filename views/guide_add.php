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
                <div class="alert alert-info">
                    <strong>Thông báo:</strong> Để thêm hướng dẫn viên mới, vui lòng tạo tài khoản với vai trò "Hướng dẫn viên" trong phần Quản lý tài khoản.
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thêm hướng dẫn viên</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <strong>Lưu ý:</strong> Chức năng thêm hướng dẫn viên trực tiếp đã được tắt. Vui lòng sử dụng phần Quản lý tài khoản để tạo tài khoản mới với vai trò "Hướng dẫn viên".
                        </div>
                        <form action="?action=account_management" method="GET">

                            <div class="mb-3">
                                <label for="name" class="form-label">Tên hướng dẫn viên:</label>
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
                                <label for="license_info" class="form-label">Thông tin giấy phép:</label>
                                <textarea class="form-control" id="license_info" name="license_info" rows="3"><?= $old_data['license_info'] ?? '' ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Hình ảnh:</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <?php if (!empty($old_data['image'])): ?>
                                    <small class="form-text text-muted">Hình ảnh hiện tại: <?= $old_data['image'] ?></small>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Trạng thái:</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="Active" <?= ($old_data['status'] ?? 'Active') == 'Active' ? 'selected' : '' ?>>Active</option>
                                    <option value="Inactive" <?= ($old_data['status'] ?? '') == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                                    <option value="Busy" <?= ($old_data['status'] ?? '') == 'Busy' ? 'selected' : '' ?>>Busy</option>
                                </select>
                            </div>

                            <!-- Personal Information Section -->
                            <hr class="my-4">
                            <h5 class="text-primary mb-3">Thông tin cá nhân</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="birth_date" class="form-label">Ngày sinh:</label>
                                        <input type="date" class="form-control" id="birth_date" name="birth_date" value="<?= $old_data['birth_date'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="experience_years" class="form-label">Số năm kinh nghiệm:</label>
                                        <input type="number" class="form-control" id="experience_years" name="experience_years" value="<?= $old_data['experience_years'] ?? '' ?>" min="0" max="50">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="languages" class="form-label">Ngôn ngữ sử dụng:</label>
                                <input type="text" class="form-control" id="languages" name="languages" value="<?= $old_data['languages'] ?? '' ?>" placeholder="Ví dụ: Tiếng Việt, Tiếng Anh, Tiếng Trung">
                            </div>

                            <div class="mb-3">
                                <label for="certificates" class="form-label">Chứng chỉ chuyên môn:</label>
                                <textarea class="form-control" id="certificates" name="certificates" rows="3" placeholder="Liệt kê các chứng chỉ, bằng cấp..."><?= $old_data['certificates'] ?? '' ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="emergency_contact" class="form-label">Liên hệ khẩn cấp:</label>
                                        <input type="text" class="form-control" id="emergency_contact" name="emergency_contact" value="<?= $old_data['emergency_contact'] ?? '' ?>" placeholder="Tên và số điện thoại">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="health_status" class="form-label">Tình trạng sức khỏe:</label>
                                        <select class="form-control" id="health_status" name="health_status">
                                            <option value="Excellent" <?= ($old_data['health_status'] ?? 'Good') == 'Excellent' ? 'selected' : '' ?>>Xuất sắc</option>
                                            <option value="Good" <?= ($old_data['health_status'] ?? 'Good') == 'Good' ? 'selected' : '' ?>>Tốt</option>
                                            <option value="Fair" <?= ($old_data['health_status'] ?? '') == 'Fair' ? 'selected' : '' ?>>Khá</option>
                                            <option value="Poor" <?= ($old_data['health_status'] ?? '') == 'Poor' ? 'selected' : '' ?>>Kém</option>
                                            <option value="Critical" <?= ($old_data['health_status'] ?? '') == 'Critical' ? 'selected' : '' ?>>Nghiêm trọng</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ:</label>
                                <textarea class="form-control" id="address" name="address" rows="2" placeholder="Địa chỉ hiện tại"><?= $old_data['address'] ?? '' ?></textarea>
                            </div>

                            <!-- Professional Information Section -->
                            <hr class="my-4">
                            <h5 class="text-primary mb-3">Thông tin chuyên môn</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="guide_type" class="form-label">Loại hướng dẫn viên:</label>
                                        <select class="form-control" id="guide_type" name="guide_type">
                                            <option value="Nội địa" <?= ($old_data['guide_type'] ?? 'Nội địa') == 'Nội địa' ? 'selected' : '' ?>>Nội địa</option>
                                            <option value="Quốc tế" <?= ($old_data['guide_type'] ?? '') == 'Quốc tế' ? 'selected' : '' ?>>Quốc tế</option>
                                            <option value="Cả hai" <?= ($old_data['guide_type'] ?? '') == 'Cả hai' ? 'selected' : '' ?>>Cả hai</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="availability_status" class="form-label">Trạng thái sẵn sàng:</label>
                                        <select class="form-control" id="availability_status" name="availability_status">
                                            <option value="Available" <?= ($old_data['availability_status'] ?? 'Available') == 'Available' ? 'selected' : '' ?>>Sẵn sàng</option>
                                            <option value="Busy" <?= ($old_data['availability_status'] ?? '') == 'Busy' ? 'selected' : '' ?>>Bận</option>
                                            <option value="On Leave" <?= ($old_data['availability_status'] ?? '') == 'On Leave' ? 'selected' : '' ?>>Đang nghỉ</option>
                                            <option value="Unavailable" <?= ($old_data['availability_status'] ?? '') == 'Unavailable' ? 'selected' : '' ?>>Không khả dụng</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="specialization" class="form-label">Chuyên môn/Chuyên tuyến:</label>
                                <input type="text" class="form-control" id="specialization" name="specialization" value="<?= $old_data['specialization'] ?? '' ?>" placeholder="Ví dụ: Du lịch mạo hiểm, Văn hóa Việt Nam, Biển đảo">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="performance_rating" class="form-label">Đánh giá năng lực (1-5):</label>
                                        <input type="number" class="form-control" id="performance_rating" name="performance_rating" value="<?= $old_data['performance_rating'] ?? '' ?>" min="1" max="5" step="0.1">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="join_date" class="form-label">Ngày gia nhập:</label>
                                        <input type="date" class="form-control" id="join_date" name="join_date" value="<?= $old_data['join_date'] ?? date('Y-m-d') ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Ghi chú:</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Thông tin bổ sung, ghi chú đặc biệt..."><?= $old_data['notes'] ?? '' ?></textarea>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="?action=guides" class="btn btn-secondary me-2">Hủy bỏ</a>
                                <button type="submit" class="btn btn-primary">Quản lý tài khoản</button>
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