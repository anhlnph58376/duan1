<?php
$page_title = "Tạo Đoàn Mới cho Booking #" . $booking['id'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $page_title ?> - Quản lý Tour</title>

    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

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

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?= $page_title ?></h1>
                        <a href="index.php?action=booking_detail&id=<?= $booking['id'] ?>" 
                           class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
                        </a>
                    </div>

                    <!-- Display Messages -->
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-<?= $_SESSION['message_type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
                            <?= $_SESSION['message'] ?>
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                    <?php endif; ?>

                    <!-- Booking Info Card -->
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Thông tin booking</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Mã booking:</strong> #<?= $booking['id'] ?></p>
                                            <p><strong>Khách hàng:</strong> <?= htmlspecialchars($booking['customer_name'] ?? 'N/A') ?></p>
                                            <p><strong>Điện thoại:</strong> <?= htmlspecialchars($booking['customer_phone'] ?? 'N/A') ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Email:</strong> <?= htmlspecialchars($booking['customer_email'] ?? 'N/A') ?></p>
                                            <p><strong>Tổng tiền:</strong> <?= number_format($booking['total_amount'] ?? 0) ?> VND</p>
                                            <p><strong>Trạng thái:</strong> 
                                                <span class="badge badge-info"><?= htmlspecialchars($booking['status'] ?? 'N/A') ?></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Create New Departure Form -->
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Tạo đoàn mới</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="index.php?action=booking_process_new_departure">
                                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="tour_id">Chọn tour <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="tour_id" name="tour_id" required>
                                                        <option value="">-- Chọn tour --</option>
                                                        <?php if (isset($tours)): ?>
                                                            <?php foreach ($tours as $tour): ?>
                                                                <option value="<?= $tour['id'] ?>" data-price="<?= $tour['base_price'] ?>">
                                                                    <?= htmlspecialchars($tour['name']) ?> - 
                                                                    <?= number_format($tour['base_price']) ?> VND
                                                                </option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="pax_count">Số người từ booking này</label>
                                                    <input type="number" class="form-control" id="pax_count" name="pax_count" 
                                                           value="1" min="1" max="50" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="departure_date">Ngày khởi hành <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" id="departure_date" name="departure_date" 
                                                           value="<?= date('Y-m-d', strtotime('+7 days')) ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="return_date">Ngày kết thúc <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" id="return_date" name="return_date" 
                                                           value="<?= date('Y-m-d', strtotime('+10 days')) ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="min_pax">Số lượng tối thiểu <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="min_pax" name="min_pax" 
                                                           value="1" min="1" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="max_pax">Số lượng tối đa <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="max_pax" name="max_pax" 
                                                           value="50" min="1" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="status">Trạng thái đoàn</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="Scheduled">Đã lên lịch</option>
                                                <option value="In Progress">Đang thực hiện</option>
                                                <option value="Completed">Hoàn thành</option>
                                                <option value="Canceled">Hủy bỏ</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Tạo đoàn và thêm booking
                                            </button>
                                            <a href="index.php?action=booking_detail&id=<?= $booking['id'] ?>" 
                                               class="btn btn-secondary">
                                                <i class="fas fa-times"></i> Hủy
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="col-lg-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-info">Hướng dẫn</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-3"><i class="fas fa-info-circle text-info"></i> 
                                       <strong>Tạo đoàn mới từ booking</strong></p>
                                    <ul class="mb-0">
                                        <li>Chọn tour phù hợp với booking</li>
                                        <li>Đặt ngày khởi hành và kết thúc</li>
                                        <li>Thiết lập giới hạn số lượng khách</li>
                                        <li>Booking hiện tại sẽ tự động được thêm vào đoàn</li>
                                        <li>Có thể thêm booking khác sau khi tạo đoàn</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Bootstrap core JavaScript-->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="assets/js/sb-admin-2.min.js"></script>

    <script>
    $(document).ready(function() {
        // Auto calculate return date based on tour duration
        $('#tour_id').change(function() {
            // This would need tour duration data from backend
            // For now, we'll add 3 days to departure date
            updateReturnDate();
        });

        $('#departure_date').change(function() {
            updateReturnDate();
        });

        function updateReturnDate() {
            var departureDate = $('#departure_date').val();
            if (departureDate) {
                var date = new Date(departureDate);
                date.setDate(date.getDate() + 3); // Default 3 days tour
                $('#return_date').val(date.toISOString().split('T')[0]);
            }
        }

        // Set minimum pax to match booking pax count
        $('#pax_count').change(function() {
            var paxCount = parseInt($(this).val()) || 1;
            $('#min_pax').val(paxCount);
        });
    });
    </script>

</body>
</html>