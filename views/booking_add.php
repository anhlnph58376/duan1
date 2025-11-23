<?php
// Lấy dữ liệu cũ (nếu có)
$old_data = $_SESSION['old_data'] ?? [];
unset($_SESSION['old_data']);

// Sử dụng dữ liệu cũ hoặc dữ liệu mặc định
$data = !empty($old_data) ? $old_data : [];

// Message alerts
if (isset($_SESSION['error'])): ?>
    <div class="container mt-3">
      <div class="alert alert-danger" role="alert"><?= $_SESSION['error'] ?></div>
    </div>
<?php unset($_SESSION['error']); endif;

if (isset($_SESSION['success'])): ?>
    <div class="container mt-3">
      <div class="alert alert-success" role="alert"><?= $_SESSION['success'] ?></div>
    </div>
<?php unset($_SESSION['success']); endif; ?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Thêm Booking</title>
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include __DIR__ . '/includes/sidebar.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include __DIR__ . '/includes/topbar.php'; ?>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Thêm Booking</h1>
                        <a href="?action=bookings" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
                    </div>

                    <div class="row">
                        <?php if (!empty($customers) && is_array($customers)): ?>
                        <div class="form-group col-12">
                            <label for="existing_customer">Chọn khách có sẵn (tùy chọn)</label>
                            <select id="existing_customer" class="form-control">
                                <option value="">-- Chọn khách --</option>
                                <?php foreach ($customers as $cust): ?>
                                    <option value="<?= $cust['id'] ?>" data-name="<?= htmlspecialchars($cust['name']) ?>" data-email="<?= htmlspecialchars($cust['email']) ?>" data-phone="<?= htmlspecialchars($cust['phone']) ?>" data-address="<?= htmlspecialchars($cust['address'] ?? '') ?>"><?= htmlspecialchars($cust['name']) ?> - <?= htmlspecialchars($cust['phone']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header">
                                    <strong class="text-primary">Thông tin Booking</strong>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="?action=addBooking" id="bookingForm">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="customer_name">Tên khách hàng <span class="text-danger">*</span></label>
                                                <input autofocus type="text" class="form-control" id="customer_name" name="customer_name" required value="<?= htmlspecialchars($data['customer_name'] ?? '') ?>" placeholder="Nhập tên khách hàng">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="customer_phone">Số điện thoại <span class="text-danger">*</span></label>
                                                <input type="tel" inputmode="tel" pattern="[0-9+\-\s()]{6,}" class="form-control" id="customer_phone" name="customer_phone" required value="<?= htmlspecialchars($data['customer_phone'] ?? '') ?>" placeholder="Nhập số điện thoại">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="customer_email">Email</label>
                                                <input type="email" class="form-control" id="customer_email" name="customer_email" value="<?= htmlspecialchars($data['customer_email'] ?? '') ?>" placeholder="Nhập email khách hàng">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="customer_address">Địa chỉ</label>
                                                <input type="text" class="form-control" id="customer_address" name="customer_address" value="<?= htmlspecialchars($data['customer_address'] ?? '') ?>" placeholder="Nhập địa chỉ khách hàng">
                                            </div>
                                        </div>

                                        <hr>
                                        <h6 class="text-primary"><i class="fas fa-calendar-alt"></i> Thông tin đặt tour</h6>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="booking_date">Ngày booking</label>
                                                <input type="datetime-local" class="form-control" id="booking_date" name="booking_date" value="<?= $data['booking_date'] ?? date('Y-m-d\TH:i') ?>">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="pax_count">Số lượng</label>
                                                <input type="number" min="1" step="1" class="form-control" id="pax_count" name="pax_count" value="<?= htmlspecialchars($data['pax_count'] ?? 1) ?>">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="status">Trạng thái</label>
                                                <select class="form-control" id="status" name="status">
                                                    <option value="Pending" <?= (($data['status'] ?? 'Pending') == 'Pending') ? 'selected' : '' ?>>Chờ xác nhận</option>
                                                    <option value="Deposited" <?= (($data['status'] ?? '') == 'Deposited') ? 'selected' : '' ?>>Đã cọc</option>
                                                    <option value="Completed" <?= (($data['status'] ?? '') == 'Completed') ? 'selected' : '' ?>>Hoàn tất</option>
                                                    <option value="Canceled" <?= (($data['status'] ?? '') == 'Canceled') ? 'selected' : '' ?>>Hủy</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="tour_id">Loại tour</label>
                                                <select id="tour_id" name="tour_id" class="form-control">
                                                    <option value="">-- Chọn tour (không bắt buộc) --</option>
                                                    <?php if (!empty($tours) && is_array($tours)): foreach ($tours as $t): ?>
                                                        <option value="<?= $t['id'] ?>" <?= (isset($data['tour_id']) && $data['tour_id'] == $t['id']) ? 'selected' : '' ?>><?= htmlspecialchars($t['name']) ?></option>
                                                    <?php endforeach; endif; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="booking_type">Loại đặt</label>
                                                <select id="booking_type" name="booking_type" class="form-control">
                                                    <option value="individual" <?= (($data['booking_type'] ?? 'individual') == 'individual') ? 'selected' : '' ?>>Khách lẻ</option>
                                                    <option value="group" <?= (($data['booking_type'] ?? '') == 'group') ? 'selected' : '' ?>>Đoàn</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="special_requests">Yêu cầu đặc biệt</label>
                                                <input type="text" id="special_requests" name="special_requests" class="form-control" value="<?= htmlspecialchars($data['special_requests'] ?? '') ?>" placeholder="Ăn kiêng, chỗ ngồi, ...">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="total_amount">Tổng tiền (VNĐ)</label>
                                                <input type="number" step="0.01" min="0" class="form-control" id="total_amount" name="total_amount" value="<?= htmlspecialchars($data['total_amount'] ?? '') ?>" placeholder="0">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="deposit_amount">Tiền cọc (VNĐ)</label>
                                                <input type="number" step="0.01" min="0" class="form-control" id="deposit_amount" name="deposit_amount" value="<?= htmlspecialchars($data['deposit_amount'] ?? '0') ?>" placeholder="0">
                                            </div>
                                        </div>

                                        <div class="form-group mt-3">
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Thêm Booking</button>
                                            <a href="?action=bookings" class="btn btn-secondary ml-2"><i class="fas fa-times"></i> Hủy</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card shadow mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <strong class="text-info">Hướng dẫn</strong>
                                    <button class="btn btn-sm btn-light" type="button" data-toggle="collapse" data-target="#helpBox" aria-expanded="true" aria-controls="#helpBox">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                                <div id="helpBox" class="collapse show">
                                    <div class="card-body">
                                        <ul>
                                            <li>Các trường có dấu (*) là bắt buộc.</li>
                                            <li>Số điện thoại dùng để liên hệ.</li>
                                            <li>Mã booking sẽ tự động tạo.</li>
                                            <li>Tổng tiền nhập theo VNĐ, chỉ nhập số.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/sb-admin-2.min.js"></script>

    <script>
    // Safe DOM helpers
    function el(id) { return document.getElementById(id); }

    if (el('customer_name')) {
        try { el('customer_name').focus(); } catch (e) {}
    }

    // Attach listeners only when elements exist
    if (el('total_amount') && el('deposit_amount') && el('bookingForm')) {
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            var total = parseFloat(el('total_amount').value || '0');
            var deposit = parseFloat(el('deposit_amount').value || '0');
            if (deposit > total) {
                e.preventDefault();
                alert('Tiền cọc không thể lớn hơn tổng tiền!');
                return false;
            }
        });
    }

    // When an existing customer is selected, fill the customer fields
    var existingSelect = el('existing_customer');
    if (existingSelect) {
        existingSelect.addEventListener('change', function() {
            var opt = existingSelect.options[existingSelect.selectedIndex];
            if (!opt || !opt.value) {
                // clear fields
                if (el('customer_name')) el('customer_name').value = '';
                if (el('customer_phone')) el('customer_phone').value = '';
                if (el('customer_email')) el('customer_email').value = '';
                if (el('customer_address')) el('customer_address').value = '';
                return;
            }
            var name = opt.getAttribute('data-name') || '';
            var email = opt.getAttribute('data-email') || '';
            var phone = opt.getAttribute('data-phone') || '';
            var address = opt.getAttribute('data-address') || '';
            if (el('customer_name')) el('customer_name').value = name;
            if (el('customer_phone')) el('customer_phone').value = phone;
            if (el('customer_email')) el('customer_email').value = email;
            if (el('customer_address')) el('customer_address').value = address;
        });
    }
    </script>

</body>

</html>
