<?php
// Lấy dữ liệu cũ (nếu có)
$old_data = $_SESSION['old_data'] ?? [];
unset($_SESSION['old_data']);

// Sử dụng dữ liệu cũ hoặc dữ liệu mặc định
$data = !empty($old_data) ? $old_data : [];

if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger mb-4" role="alert">
    <?= $_SESSION['error'] ?>
</div>
<?php
    unset($_SESSION['error']);
endif;

if (isset($_SESSION['success'])): ?>
<div class="alert alert-success mb-4" role="alert">
    <?= $_SESSION['success'] ?>
</div>
<?php
    unset($_SESSION['success']);
endif;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Thêm Booking Mới</title>

    <!-- Font tùy chỉnh cho template này-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Kiểu dáng tùy chỉnh cho template này-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Bọc trang -->
    <div id="wrapper">

        <?php include 'views/includes/sidebar.php'; ?>
        <!-- Kết thúc Sidebar -->

        <!-- Bọc nội dung -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Nội dung chính -->
            <div id="content">

                <!-- Thanh trên -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Nút chuyển đổi Sidebar (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                    <!-- Navbar Topbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Mục Menu - Thông tin Người dùng -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                                <img class="img-profile rounded-circle" src="assets/img/undraw_profile.svg">
                            </a>
                        </li>
                    </ul>

                </nav>
                <!-- Kết thúc Thanh trên -->

                <!-- Bắt đầu Nội dung Trang -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">
                            <i class="fas fa-plus-circle"></i> Thêm Booking Mới
                        </h1>
                        <a href="?action=bookings" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
                        </a>
                    </div>

                    <div class="row">
                        <!-- Form thêm booking -->
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-user-plus"></i> Thông tin Booking
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="?action=addBooking" id="bookingForm">
                                        <!-- Thông tin khách hàng -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="customer_name">Tên khách hàng <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="customer_name"
                                                        name="customer_name" required
                                                        value="<?= htmlspecialchars($data['customer_name'] ?? '') ?>"
                                                        placeholder="Nhập tên khách hàng">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="customer_phone">Số điện thoại <span
                                                            class="text-danger">*</span></label>
                                                    <input type="tel" class="form-control" id="customer_phone"
                                                        name="customer_phone" required
                                                        value="<?= htmlspecialchars($data['customer_phone'] ?? '') ?>"
                                                        placeholder="Nhập số điện thoại">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="customer_email">Email</label>
                                                    <input type="email" class="form-control" id="customer_email"
                                                        name="customer_email"
                                                        value="<?= htmlspecialchars($data['customer_email'] ?? '') ?>"
                                                        placeholder="Nhập email khách hàng">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="customer_address">Địa chỉ</label>
                                                    <input type="text" class="form-control" id="customer_address"
                                                        name="customer_address"
                                                        value="<?= htmlspecialchars($data['customer_address'] ?? '') ?>"
                                                        placeholder="Nhập địa chỉ khách hàng">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Thông tin booking -->
                                        <hr class="my-4">
                                        <h6 class="text-primary mb-3">
                                            <i class="fas fa-calendar-alt"></i> Thông tin đặt tour
                                        </h6>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="booking_date">Ngày booking <span
                                                            class="text-danger">*</span></label>
                                                    <input type="datetime-local" class="form-control" id="booking_date"
                                                        name="booking_date" required
                                                        value="<?= $data['booking_date'] ?? date('Y-m-d\TH:i') ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="status">Trạng thái</label>
                                                    <select class="form-control" id="status" name="status">
                                                        <option value="Pending"
                                                            <?= (($data['status'] ?? 'Pending') == 'Pending') ? 'selected' : '' ?>>
                                                            Chờ xác nhận</option>
                                                        <option value="Deposited"
                                                            <?= (($data['status'] ?? '') == 'Deposited') ? 'selected' : '' ?>>
                                                            Đã cọc</option>
                                                        <option value="Completed"
                                                            <?= (($data['status'] ?? '') == 'Completed') ? 'selected' : '' ?>>
                                                            Hoàn tất</option>
                                                        <option value="Canceled"
                                                            <?= (($data['status'] ?? '') == 'Canceled') ? 'selected' : '' ?>>
                                                            Hủy</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="total_amount">Tổng tiền <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="total_amount"
                                                        name="total_amount" required
                                                        value="<?= $data['total_amount'] ?? '' ?>" placeholder="0">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="deposit_amount">Tiền cọc</label>
                                                    <input type="text" class="form-control" id="deposit_amount"
                                                        name="deposit_amount"
                                                        value="<?= $data['deposit_amount'] ?? '0' ?>" placeholder="0">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group mt-4">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="fas fa-save"></i> Thêm Booking
                                            </button>
                                            <a href="?action=bookings" class="btn btn-secondary btn-lg ml-2">
                                                <i class="fas fa-times"></i> Hủy
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin hướng dẫn -->
                        <div class="col-lg-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Hướng dẫn
                                            </div>
                                            <div class="text-gray-900">
                                                <ul class="mb-0">
                                                    <li>Các trường có dấu (*) là bắt buộc</li>
                                                    <li>Số điện thoại sẽ được dùng để liên hệ</li>
                                                    <li>Mã booking sẽ tự động tạo</li>
                                                    <li>Tổng tiền tính bằng VNĐ</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-info-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-left-success shadow mt-4">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Trạng thái Booking
                                    </div>
                                    <div class="text-gray-900 small">
                                        <p class="mb-1"><span class="badge badge-warning">Pending</span> - Chờ xác nhận
                                        </p>
                                        <p class="mb-1"><span class="badge badge-info">Deposited</span> - Đã cọc</p>
                                        <p class="mb-1"><span class="badge badge-success">Completed</span> - Hoàn tất
                                        </p>
                                        <p class="mb-0"><span class="badge badge-danger">Canceled</span> - Hủy</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- Kết thúc Nội dung Chính -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- Kết thúc Footer -->

        </div>
        <!-- Kết thúc Bọc Nội dung -->

    </div>
    <!-- Kết thúc Bọc Trang -->

    <!-- Nút Cuộn lên Đầu-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- JavaScript cơ bản Bootstrap-->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript plugin cơ bản-->
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Script tùy chỉnh cho tất cả trang-->
    <script src="assets/js/sb-admin-2.min.js"></script>

    <script>
    // Tự động định dạng số tiền
    document.getElementById('total_amount').addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^0-9]/g, '');
        if (value) {
            e.target.value = parseInt(value).toLocaleString('vi-VN').replace(/,/g, '');
        }
    });

    document.getElementById('deposit_amount').addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^0-9]/g, '');
        if (value) {
            e.target.value = parseInt(value).toLocaleString('vi-VN').replace(/,/g, '');
        }
    });

    // Kiểm tra form
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        const totalAmount = document.getElementById('total_amount').value;
        const depositAmount = document.getElementById('deposit_amount').value;

        if (totalAmount && depositAmount) {
            const total = parseInt(totalAmount.replace(/[^0-9]/g, ''));
            const deposit = parseInt(depositAmount.replace(/[^0-9]/g, ''));

            if (deposit > total) {
                e.preventDefault();
                alert('Tiền cọc không thể lớn hơn tổng tiền!');
                return false;
            }
        }
    });
    </script>

</body>

</html>