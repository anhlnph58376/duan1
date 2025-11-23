<?php if (isset($_SESSION['error'])): ?>
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

    <title>Chi tiết Booking</title>

    <!-- Custom fonts for this template -->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include 'views/includes/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include 'views/includes/topbar.php'; ?>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Chi tiết Booking #<?= $booking['id'] ?></h1>
                        <a href="?action=bookings" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
                        </a>
                    </div>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Thông tin khách hàng</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item"><strong>Tên:</strong>
                                            <?= htmlspecialchars($booking['customer_name'] ?? 'N/A') ?></li>
                                        <li class="list-group-item"><strong>Email:</strong>
                                            <?= htmlspecialchars($booking['customer_email'] ?? 'N/A') ?></li>
                                        <li class="list-group-item"><strong>Điện thoại:</strong>
                                            <?= htmlspecialchars($booking['customer_phone'] ?? 'N/A') ?></li>
                                        <li class="list-group-item"><strong>Địa chỉ:</strong>
                                            <?= htmlspecialchars($booking['customer_address'] ?? 'N/A') ?></li>
                                        <li class="list-group-item"><strong>Ngày booking:</strong>
                                            <?= date('d/m/Y H:i', strtotime($booking['booking_date'])) ?></li>
                                        <li class="list-group-item"><strong>Trạng thái:</strong>
                                            <?= htmlspecialchars($booking['status'] ?? 'N/A') ?></li>
                                    </ul>

                                    <div class="mt-3 text-right">
                                        <a href="?action=booking_edit&id=<?= $booking['id'] ?>" class="btn btn-primary">
                                            <i class="fas fa-edit"></i> Chỉnh sửa
                                        </a>
                                        <?php if (!empty($tour_id)): ?>
                                            <button class="btn btn-info btn-view-schedule" data-tour-id="<?= htmlspecialchars($tour_id) ?>">
                                                <i class="fas fa-route"></i> Xem lịch trình
                                            </button>
                                        <?php endif; ?>
                                        <!-- nút Tạo đoàn mới đã bị ẩn theo yêu cầu -->
                                        <a href="?action=booking_delete&id=<?= $booking['id'] ?>" class="btn btn-danger"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                            <i class="fas fa-trash"></i> Xóa
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Thông tin Tài chính</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Mã booking:</strong> <?= htmlspecialchars($booking['booking_code'] ?? 'N/A') ?></p>
                                    <p><strong>Tổng tiền:</strong>
                                        <?= number_format($booking['total_amount'] ?? 0, 0, ',', '.') ?> VNĐ</p>
                                    <p><strong>Tiền cọc:</strong>
                                        <?= number_format($booking['deposit_amount'] ?? 0, 0, ',', '.') ?> VNĐ</p>
                                    <p><strong>Còn lại:</strong>
                                        <?= number_format(($booking['total_amount'] ?? 0) - ($booking['deposit_amount'] ?? 0), 0, ',', '.') ?> VNĐ</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Ghi chú & Hành động</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="?action=updateBooking">
                                        <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                                        <div class="form-group">
                                            <label for="status">Trạng thái</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="Pending"
                                                    <?= ($booking['status']=='Pending')?'selected':'' ?>>Chờ xác nhận
                                                </option>
                                                <option value="Deposited"
                                                    <?= ($booking['status']=='Deposited')?'selected':'' ?>>Đã cọc
                                                </option>
                                                <option value="Completed"
                                                    <?= ($booking['status']=='Completed')?'selected':'' ?>>Hoàn thành
                                                </option>
                                                <option value="Canceled"
                                                    <?= ($booking['status']=='Canceled')?'selected':'' ?>>Đã hủy
                                                </option>
                                            </select>
                                        </div>

                                        <div class="text-right mt-3">
                                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                        </div>
                                    </form>

                                    <hr>

                                    <h6>Gửi yêu cầu cho HDV</h6>
                                    <form method="POST" action="?action=sendBookingToGuide">
                                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                        <div class="form-group">
                                            <label for="guide_email">Email HDV</label>
                                            <input type="email" name="guide_email" id="guide_email" class="form-control"
                                                required placeholder="hdv@example.com">
                                        </div>
                                        <div class="form-group">
                                            <label for="message">Ghi chú</label>
                                            <textarea name="message" id="message" class="form-control"
                                                rows="3"></textarea>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-success">Gửi yêu cầu</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

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
    <script>
    // load tour schedule modal (used on booking_detail page)
    $(document).on('click', '.btn-view-schedule', function(e){
        e.preventDefault();
        var tourId = $(this).data('tour-id');
        if ($('#tourScheduleModal').length === 0) {
            $('body').append('\n<div class="modal fade" id="tourScheduleModal" tabindex="-1" role="dialog" aria-hidden="true">\n  <div class="modal-dialog modal-lg" role="document">\n    <div class="modal-content">\n      <div class="modal-header">\n        <h5 class="modal-title">Lịch trình Tour</h5>\n        <button type="button" class="close" data-dismiss="modal" aria-label="Close">\n          <span aria-hidden="true">&times;</span>\n        </button>\n      </div>\n      <div class="modal-body">\n        <div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>\n      </div>\n    </div>\n  </div>\n</div>\n');
        }
        $('#tourScheduleModal .modal-body').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
        $('#tourScheduleModal').modal('show');
        $.get('index.php', { action: 'tour_schedule', id: tourId })
            .done(function(html){
                $('#tourScheduleModal .modal-body').html(html);
            }).fail(function(){
                $('#tourScheduleModal .modal-body').html('<div class="alert alert-danger">Không thể tải lịch trình.</div>');
            });
    });
    </script>
</body>

</html>