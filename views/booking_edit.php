<?php
if (isset($_SESSION['error_booking'])): ?>
<div class="alert alert-danger mb-4" role="alert">
    <?= $_SESSION['error_booking'] ?>
</div>
<?php
    unset($_SESSION['error_booking']);
endif;

// Lấy dữ liệu cũ (nếu có)
$old_data = $_SESSION['old_data'] ?? [];
unset($_SESSION['old_data']);

// Sử dụng dữ liệu cũ hoặc dữ liệu booking hiện tại
$data = !empty($old_data) ? $old_data : $booking;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sửa Booking #<?= $booking['id'] ?></title>

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

        <?php include 'views/includes/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php require_once 'views/includes/topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Sửa Booking #<?= $booking['id'] ?></h1>
                        <a href="?action=bookings" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
                        </a>
                    </div>

                    <!-- Form Edit Booking -->
                    <div class="row">
                        <div class="col-xl-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Thông tin Booking</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="?action=updateBooking">
                                        <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                                        
                                        <div class="row">
                                            <!-- Thông tin khách hàng -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="customer_name">Tên khách hàng <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="customer_name" name="customer_name" required
                                                           value="<?= htmlspecialchars($data['customer_name'] ?? '') ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="customer_phone">Số điện thoại <span class="text-danger">*</span></label>
                                                    <input type="tel" class="form-control" id="customer_phone" name="customer_phone" required
                                                           value="<?= htmlspecialchars($data['customer_phone'] ?? '') ?>">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="customer_email">Email</label>
                                                    <input type="email" class="form-control" id="customer_email" name="customer_email"
                                                           value="<?= htmlspecialchars($data['customer_email'] ?? '') ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="customer_address">Địa chỉ</label>
                                                    <input type="text" class="form-control" id="customer_address" name="customer_address"
                                                           value="<?= htmlspecialchars($data['customer_address'] ?? '') ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Thông tin booking -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="booking_code">Mã booking <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="booking_code" name="booking_code" required
                                                           value="<?= htmlspecialchars($data['booking_code'] ?? '') ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="tour_id">Tour</label>
                                                    <select class="form-control" id="tour_id" name="tour_id">
                                                        <option value="">-- Không chọn --</option>
                                                        <?php if (!empty($tours) && is_array($tours)): ?>
                                                            <?php foreach ($tours as $t): ?>
                                                                <option value="<?= htmlspecialchars($t['id']) ?>" <?= ((string)($data['tour_id'] ?? '') === (string)$t['id']) ? 'selected' : '' ?>><?= htmlspecialchars($t['name'] ?? $t['title'] ?? 'Untitled') ?></option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="booking_date">Ngày booking <span class="text-danger">*</span></label>
                                                    <input type="datetime-local" class="form-control" id="booking_date" name="booking_date" required
                                                           value="<?= !empty($data['booking_date']) ? date('Y-m-d\TH:i', strtotime($data['booking_date'])) : '' ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="total_amount">Tổng tiền <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="total_amount" name="total_amount" 
                                                           min="0" step="1000" required
                                                           value="<?= $data['total_amount'] ?? '' ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="deposit_amount">Tiền cọc</label>
                                                    <input type="number" class="form-control" id="deposit_amount" name="deposit_amount" 
                                                           min="0" step="1000"
                                                           value="<?= $data['deposit_amount'] ?? '0' ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="status">Trạng thái <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="status" name="status" required>
                                                        <option value="Pending" <?= ($data['status'] ?? '') == 'Pending' ? 'selected' : '' ?>>Chờ xác nhận</option>
                                                        <option value="Deposited" <?= ($data['status'] ?? '') == 'Deposited' ? 'selected' : '' ?>>Đã cọc</option>
                                                        <option value="Completed" <?= ($data['status'] ?? '') == 'Completed' ? 'selected' : '' ?>>Hoàn tất</option>
                                                        <option value="Canceled" <?= ($data['status'] ?? '') == 'Canceled' ? 'selected' : '' ?>>Hủy</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Cập nhật Booking
                                            </button>
                                            <a href="?action=bookings" class="btn btn-secondary">
                                                <i class="fas fa-times"></i> Hủy
                                            </a>
                                            <a href="?action=booking_delete&id=<?= $booking['id'] ?>" 
                                               class="btn btn-danger"
                                               onclick="return confirm('Bạn có chắc muốn xóa booking này?')">
                                                <i class="fas fa-trash"></i> Xóa
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin bổ sung -->
                        <div class="col-xl-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Thông tin hiện tại</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li><strong>ID:</strong> <?= $booking['id'] ?></li>
                                        <li><strong>Mã booking:</strong> <?= htmlspecialchars($booking['booking_code'] ?? '') ?></li>
                                        <li><strong>Tình trạng hiện tại:</strong> 
                                            <?php 
                                            $status_class = [
                                                'Pending' => 'warning',
                                                'Deposited' => 'info', 
                                                'Completed' => 'success',
                                                'Canceled' => 'danger'
                                            ];
                                            $status_text = [
                                                'Pending' => 'Chờ xác nhận',
                                                'Deposited' => 'Đã cọc',
                                                'Completed' => 'Hoàn tất', 
                                                'Canceled' => 'Hủy'
                                            ];
                                            $current_status = $booking['status'] ?? 'Pending';
                                            $class = $status_class[$current_status] ?? 'secondary';
                                            $text = $status_text[$current_status] ?? $current_status;
                                            ?>
                                            <span class="badge badge-<?= $class ?>"><?= $text ?></span>
                                        </li>
                                        <li><strong>Khách hàng:</strong> <?= htmlspecialchars($booking['customer_name'] ?? 'N/A') ?></li>
                                        <li><strong>Điện thoại:</strong> <?= htmlspecialchars($booking['customer_phone'] ?? 'N/A') ?></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Hướng dẫn</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-info-circle text-info"></i> Các trường có dấu (*) là bắt buộc</li>
                                        <li><i class="fas fa-edit text-warning"></i> Cập nhật sẽ thay đổi thông tin khách hàng</li>
                                        <li><i class="fas fa-money-bill text-primary"></i> Tổng tiền tính bằng VNĐ</li>
                                        <li><i class="fas fa-trash text-danger"></i> Xóa booking sẽ không thể khôi phục</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

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

    <!-- Bootstrap core JavaScript-->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="assets/js/sb-admin-2.min.js"></script>

    <script>
    // Tính toán tiền cọc tự động (20% tổng tiền)
    document.getElementById('total_amount').addEventListener('input', function() {
        const totalAmount = parseInt(this.value) || 0;
        const depositAmount = Math.round(totalAmount * 0.2);
        document.getElementById('deposit_amount').value = depositAmount;
    });
    </script>

</body>

</html>