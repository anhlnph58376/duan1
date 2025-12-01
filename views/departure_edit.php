<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Chỉnh sửa đoàn</title>

    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include 'views/includes/sidebar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <h1 class="h3 mb-2 text-gray-800">Chỉnh sửa đoàn:
                        <?= htmlspecialchars($departure['tour_name'] ?? 'N/A') ?></h1>
                    <p class="mb-4">Cập nhật thông tin đoàn</p>

                    <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?= $_SESSION['message_type'] ?? 'info' ?> alert-dismissible fade show"
                        role="alert">
                        <?= $_SESSION['message']; unset($_SESSION['message'], $_SESSION['message_type']); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php endif; ?>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Thông tin đoàn</h6>
                            <div>
                                <span class="badge badge-info">ID:
                                    #<?= str_pad($departure['id'], 6, '0', STR_PAD_LEFT) ?></span>
                                <?php if (($departure['booking_count'] ?? 0) > 0): ?>
                                <span class="badge badge-warning ml-2"><?= $departure['booking_count'] ?> booking đã
                                    đăng ký</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="index.php?action=departure_update&id=<?= $departure['id'] ?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="departure_date">Ngày khởi hành <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="departure_date"
                                                name="departure_date" required
                                                value="<?= date('Y-m-d', strtotime($departure['departure_date'])) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="return_date">Ngày kết thúc <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="return_date" name="return_date"
                                                required
                                                value="<?= date('Y-m-d', strtotime($departure['return_date'])) ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="min_pax">Số lượng tối thiểu <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="min_pax" name="min_pax"
                                                placeholder="Nhập số lượng tối thiểu" required min="1"
                                                value="<?= $departure['min_pax'] ?? 1 ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="max_pax">Số lượng tối đa <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="max_pax" name="max_pax"
                                                placeholder="Nhập số lượng tối đa" required min="1"
                                                value="<?= $departure['max_pax'] ?? 50 ?>">
                                            <?php if (($departure['booking_count'] ?? 0) > 0): ?>
                                            <small class="form-text text-info">
                                                <i class="fas fa-info-circle"></i>
                                                Hiện tại có <?= $departure['booking_count'] ?> người đã đăng ký.
                                            </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="actual_guide_id">Hướng dẫn viên</label>
                                            <select class="form-control" id="actual_guide_id" name="actual_guide_id">
                                                <option value="">Chọn hướng dẫn viên (tuỳ chọn)</option>
                                                <!-- TODO: Thêm danh sách hướng dẫn viên từ database -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Trạng thái</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="Scheduled"
                                                    <?= $departure['status'] == 'Scheduled' ? 'selected' : '' ?>>
                                                    Đã lên lịch
                                                </option>
                                                <option value="In Progress"
                                                    <?= $departure['status'] == 'In Progress' ? 'selected' : '' ?>>
                                                    Đang thực hiện
                                                </option>
                                                <option value="Completed"
                                                    <?= $departure['status'] == 'Completed' ? 'selected' : '' ?>>
                                                    Hoàn thành
                                                </option>
                                                <option value="Canceled"
                                                    <?= $departure['status'] == 'Canceled' ? 'selected' : '' ?>>
                                                    Hủy bỏ
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Cập nhật đoàn
                                    </button>
                                    <a href="index.php?action=departure_detail&id=<?= $departure['id'] ?>"
                                        class="btn btn-info">
                                        <i class="fas fa-eye"></i> Xem chi tiết
                                    </a>
                                    <a href="index.php?action=departures" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Quay lại
                                    </a>
                                    <?php if (($departure['booking_count'] ?? 0) == 0): ?>
                                    <a href="index.php?action=departure_delete&id=<?= $departure['id'] ?>"
                                        class="btn btn-danger"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa đoàn này không?')">
                                        <i class="fas fa-trash"></i> Xóa đoàn
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Booking Information -->
                    <?php if (($departure['booking_count'] ?? 0) > 0): ?>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Thông tin booking liên quan</h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info" role="alert">
                                <i class="fas fa-info-circle"></i>
                                <strong>Lưu ý:</strong> Đoàn này hiện có <?= $departure['booking_count'] ?> booking đã
                                đăng ký.
                            </div>
                            <div class="mt-3">
                                <a href="index.php?action=departure_detail&id=<?= $departure['id'] ?>"
                                    class="btn btn-outline-primary">
                                    <i class="fas fa-eye"></i> Xem danh sách booking
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

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

    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="assets/js/sb-admin-2.min.js"></script>

    <script>
    // Validate return date is after departure date
    $('#departure_date, #return_date').change(function() {
        var departureDate = $('#departure_date').val();
        var returnDate = $('#return_date').val();

        if (departureDate && returnDate) {
            if (new Date(returnDate) <= new Date(departureDate)) {
                alert('Ngày kết thúc phải sau ngày khởi hành');
                $('#return_date').val('');
            }
        }
    });

    // Validate max participants
    $('#max_participants').change(function() {
        var currentBookings = <?= $departure['booking_count'] ?? 0 ?>;
        var maxParticipants = parseInt($(this).val());

        if (maxParticipants < currentBookings) {
            alert('Số lượng tối đa không thể nhỏ hơn số người đã đăng ký (' + currentBookings + ' người)');
            $(this).val(Math.max(currentBookings, 1));
        }
    });

    // Set minimum date to today for new dates
    var today = new Date().toISOString().split('T')[0];
    $('#departure_date').attr('min', today);

    $('#departure_date').change(function() {
        $('#return_date').attr('min', $(this).val());
    });

    // Initialize return date minimum
    $('#return_date').attr('min', $('#departure_date').val());
    </script>

</body>

</html>