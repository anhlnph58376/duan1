<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Chỉnh sửa Booking</title>

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
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="<?= BASE_URL ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Tours -->
            <li class="nav-item">
                <a class="nav-link" href="?action=tours">
                    <i class="fas fa-fw fa-map-marked-alt"></i>
                    <span>Tours</span>
                </a>
            </li>

            <!-- Nav Item - Bookings -->
            <li class="nav-item active">
                <a class="nav-link" href="?action=bookings">
                    <i class="fas fa-fw fa-calendar-check"></i>
                    <span>Bookings</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                                <img class="img-profile rounded-circle"
                                    src="assets/img/undraw_profile.svg">
                            </a>
                        </li>
                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Chỉnh sửa Booking</h1>
                        <a href="?action=bookings" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
                        </a>
                    </div>

                    <!-- Alert Messages -->
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $_SESSION['error_message'] ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php unset($_SESSION['error_message']); ?>
                    <?php endif; ?>

                    <!-- Booking Info Card -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card border-left-primary shadow">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Booking Code</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= $booking['booking_code'] ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Edit Booking -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Thông tin Booking</h6>
                        </div>
                        <div class="card-body">
                            <form action="?action=booking_update" method="POST">
                                <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                                
                                <div class="row">
                                    <!-- Khách hàng -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="user_id">Khách hàng <span class="text-danger">*</span></label>
                                            <select class="form-control" id="user_id" name="user_id" required>
                                                <option value="">Chọn khách hàng</option>
                                                <?php foreach ($users as $user): ?>
                                                    <option value="<?= $user['id'] ?>" 
                                                            <?= ($booking['user_id'] == $user['id']) ? 'selected' : '' ?>>
                                                        <?= $user['full_name'] ?> - <?= $user['email'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Tour -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tour_id">Tour <span class="text-danger">*</span></label>
                                            <select class="form-control" id="tour_id" name="tour_id" required>
                                                <option value="">Chọn tour</option>
                                                <?php foreach ($tours as $tour): ?>
                                                    <option value="<?= $tour['id'] ?>" 
                                                            data-price="<?= $tour['base_price'] ?>"
                                                            <?= ($booking['tour_id'] == $tour['id']) ? 'selected' : '' ?>>
                                                        <?= $tour['tour_code'] ?> - <?= $tour['name'] ?> 
                                                        (<?= number_format($tour['base_price']) ?> VND/người)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Ngày đặt tour -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="booking_date">Ngày đặt tour <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="booking_date" name="booking_date" 
                                                   value="<?= $booking['booking_date'] ?>" required>
                                        </div>
                                    </div>

                                    <!-- Số người -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="number_of_people">Số người <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="number_of_people" name="number_of_people" 
                                                   min="1" max="50" value="<?= $booking['number_of_people'] ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Tổng tiền -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_price">Tổng tiền (VND) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="total_price" name="total_price" 
                                                   value="<?= $booking['total_price'] ?>" required>
                                        </div>
                                    </div>

                                    <!-- Trạng thái -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Trạng thái <span class="text-danger">*</span></label>
                                            <select class="form-control" id="status" name="status" required>
                                                <option value="pending" <?= ($booking['status'] == 'pending') ? 'selected' : '' ?>>Chờ xử lý</option>
                                                <option value="confirmed" <?= ($booking['status'] == 'confirmed') ? 'selected' : '' ?>>Đã xác nhận</option>
                                                <option value="assigned" <?= ($booking['status'] == 'assigned') ? 'selected' : '' ?>>Đã gán HDV</option>
                                                <option value="in_progress" <?= ($booking['status'] == 'in_progress') ? 'selected' : '' ?>>Đang thực hiện</option>
                                                <option value="completed" <?= ($booking['status'] == 'completed') ? 'selected' : '' ?>>Hoàn thành</option>
                                                <option value="cancelled" <?= ($booking['status'] == 'cancelled') ? 'selected' : '' ?>>Đã hủy</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ghi chú -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="notes">Ghi chú</label>
                                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                                      placeholder="Nhập ghi chú thêm..."><?= $booking['notes'] ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Thông tin HDV hiện tại -->
                                <?php if ($booking['guide_name']): ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <strong>HDV hiện tại:</strong> <?= $booking['guide_name'] ?>
                                            <?php if ($booking['guide_email']): ?>
                                                (<?= $booking['guide_email'] ?>)
                                            <?php endif; ?>
                                            <a href="?action=assign_guide&id=<?= $booking['id'] ?>" class="btn btn-sm btn-primary ml-2">
                                                <i class="fas fa-user-edit"></i> Thay đổi HDV
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <div class="form-group text-right">
                                    <a href="?action=booking_detail&id=<?= $booking['id'] ?>" class="btn btn-info">
                                        <i class="fas fa-eye"></i> Xem chi tiết
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Cập nhật Booking
                                    </button>
                                </div>
                            </form>
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
                        <span>Copyright &copy; Your Website 2024</span>
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
        // Tự động tính tổng tiền khi thay đổi tour hoặc số người
        $(document).ready(function() {
            function calculateTotal() {
                var tourSelect = $('#tour_id');
                var numberOfPeople = $('#number_of_people').val();
                var selectedOption = tourSelect.find('option:selected');
                var basePrice = selectedOption.data('price');

                if (basePrice && numberOfPeople) {
                    var totalPrice = basePrice * numberOfPeople;
                    $('#total_price').val(totalPrice);
                }
            }

            $('#tour_id, #number_of_people').change(calculateTotal);
        });
    </script>

</body>

</html>