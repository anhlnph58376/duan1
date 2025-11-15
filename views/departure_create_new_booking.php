<?php
$page_title = "Tạo Booking Mới cho Đoàn #" . str_pad($departure['id'], 6, '0', STR_PAD_LEFT);
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
                        <a href="index.php?action=departure_detail&id=<?= $departure['id'] ?>" 
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

                    <!-- Departure Info Card -->
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Thông tin đoàn</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Mã đoàn:</strong> #<?= str_pad($departure['id'], 6, '0', STR_PAD_LEFT) ?></p>
                                            <p><strong>Tour:</strong> <?= htmlspecialchars($departure['tour_name'] ?? 'N/A') ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Ngày khởi hành:</strong> <?= date('d/m/Y', strtotime($departure['departure_date'])) ?></p>
                                            <p><strong>Giá tour:</strong> <?= number_format($departure['base_price'] ?? 0) ?> VND</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Create New Booking Form -->
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Tạo booking mới</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="index.php?action=departure_process_new_booking">
                                        <input type="hidden" name="departure_id" value="<?= $departure['id'] ?>">
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="customer_name">Tên khách hàng <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="customer_name" name="customer_name" 
                                                           placeholder="Nhập tên khách hàng" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="customer_phone">Số điện thoại <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="customer_phone" name="customer_phone" 
                                                           placeholder="Nhập số điện thoại" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="customer_email">Email</label>
                                                    <input type="email" class="form-control" id="customer_email" name="customer_email" 
                                                           placeholder="Nhập email">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="pax_count">Số người <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="pax_count" name="pax_count" 
                                                           value="1" min="1" max="50" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="customer_address">Địa chỉ</label>
                                            <textarea class="form-control" id="customer_address" name="customer_address" 
                                                      rows="2" placeholder="Nhập địa chỉ"></textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="total_amount">Tổng tiền <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="total_amount" name="total_amount" 
                                                           value="<?= $departure['base_price'] ?? 0 ?>" min="0" step="1000" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="deposit_amount">Tiền cọc</label>
                                                    <input type="number" class="form-control" id="deposit_amount" name="deposit_amount" 
                                                           value="0" min="0" step="1000">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="status">Trạng thái</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="Pending">Chờ xử lý</option>
                                                <option value="Deposited">Đã cọc</option>
                                                <option value="Completed">Hoàn tất</option>
                                                <option value="Canceled">Đã hủy</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Tạo booking
                                            </button>
                                            <a href="index.php?action=departure_detail&id=<?= $departure['id'] ?>" 
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
                                       <strong>Tạo booking mới cho đoàn</strong></p>
                                    <ul class="mb-0">
                                        <li>Nhập đầy đủ thông tin khách hàng</li>
                                        <li>Số người sẽ được tự động thêm vào đoàn</li>
                                        <li>Giá tour được lấy từ thông tin đoàn</li>
                                        <li>Có thể điều chỉnh tổng tiền và tiền cọc</li>
                                        <li>Booking sẽ tự động liên kết với đoàn này</li>
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
        // Tính tổng tiền tự động khi thay đổi số người
        $('#pax_count').change(function() {
            var paxCount = parseInt($('#pax_count').val()) || 1;
            var basePrice = <?= $departure['base_price'] ?? 0 ?>;
            var totalAmount = paxCount * basePrice;
            $('#total_amount').val(totalAmount);
            
            // Tự động tính tiền cọc (30% tổng tiền)
            var depositAmount = Math.round(totalAmount * 0.3);
            $('#deposit_amount').val(depositAmount);
        });

        // Trigger calculation on page load
        $('#pax_count').trigger('change');
    });
    </script>

</body>
</html>