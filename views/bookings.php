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

    <title>Quản lý Booking</title>

    <!-- Custom fonts for this template -->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include 'views/includes/sidebar.php'; ?>
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
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                                <img class="img-profile rounded-circle" src="assets/img/undraw_profile.svg">
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
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Quản lý Booking</h1>
                        <a href="?action=booking_add" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-plus fa-sm text-white-50"></i> Thêm Booking
                        </a>
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Danh sách Booking</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Thông tin booking</th>
                                            <th>Tour</th>
                                            <th>Khách hàng</th>
                                            <th>Email</th>
                                            <th>Số điện thoại</th>
                                            <th>Tiền cọc</th>
                                            <th>Trạng thái</th>
                                            <th>Tổng tiền</th>
                                            <th>Đoàn đã tham gia</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($bookings) && !empty($bookings)): ?>
                                        <?php foreach ($bookings as $booking): ?>
                                        <tr>
                                            <td><?= $booking['id'] ?></td>
                                            <td>
                                                <strong>Mã:
                                                    <?= htmlspecialchars($booking['booking_code'] ?? 'N/A') ?></strong><br>
                                                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($booking['booking_date'])) ?></small>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($booking['tour_name'] ?? ($booking['tour'] ?? 'N/A')) ?>
                                                   <?php if (!empty($booking['tour_id'])): ?>
                                                        <button class="btn btn-sm btn-secondary btn-view-schedule" data-tour-id="<?= $booking['tour_id'] ?>" title="Xem lịch trình">
                                                            <i class="fas fa-route"></i>
                                                        </button>
                                                   <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($booking['customer_name'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($booking['customer_email'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($booking['customer_phone'] ?? 'N/A') ?></td>
                                            <td><?= number_format($booking['deposit_amount'] ?? 0, 0, ',', '.') ?> VNĐ
                                            </td>
                                            <td>
                                                <?php
                                                        $status_class = match ($booking['status']) {
                                                            'Pending' => 'badge-warning',
                                                            'Deposited' => 'badge-info',
                                                            'Completed' => 'badge-success',
                                                            'Canceled' => 'badge-danger',
                                                            default => 'badge-secondary'
                                                        };
                                                        $status_text = match ($booking['status']) {
                                                            'Pending' => 'Chờ xác nhận',
                                                            'Deposited' => 'Đã cọc',
                                                            'Completed' => 'Hoàn thành',
                                                            'Canceled' => 'Đã hủy',
                                                            default => 'Không xác định'
                                                        };
                                                        ?>
                                                <span class="badge <?= $status_class ?>"><?= $status_text ?></span>
                                            </td>
                                            <td>
                                                <?= number_format($booking['total_amount'] ?? 0, 0, ',', '.') ?>
                                                VNĐ
                                            </td>
                                            <td>
                                                <?php
                                                // Kiểm tra xem booking đã có trong đoàn nào chưa
                                                $bookingDepartures = $booking['departures'] ?? [];
                                                ?>
                                                <?php if (!empty($bookingDepartures)): ?>
                                                    <?php foreach ($bookingDepartures as $dep): ?>
                                                        <span class="badge badge-success mb-1">
                                                            <a href="?action=departure_detail&id=<?= $dep['departure_id'] ?>" class="text-white text-decoration-none">
                                                                Đoàn #<?= str_pad($dep['departure_id'], 6, '0', STR_PAD_LEFT) ?>
                                                            </a>
                                                        </span>
                                                        <a href="?action=booking_member_add&booking_id=<?= $booking['id'] ?>&departure_id=<?= $dep['departure_id'] ?>" class="btn btn-sm btn-outline-primary ml-1 mb-1">Thêm thành viên</a>
                                                        <br>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div class="mt-1">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary btn-view-members" data-booking-id="<?= $booking['id'] ?>">
                                                            <i class="fas fa-users"></i> Xem thành viên
                                                        </button>
                                                        <a href="?action=booking_member_add&booking_id=<?= $booking['id'] ?>" class="btn btn-success btn-sm ml-1">
                                                            <i class="fas fa-user-plus"></i> Thêm thành viên
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="?action=booking_detail&id=<?= $booking['id'] ?>"
                                                        class="btn btn-info btn-sm" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="?action=booking_edit&id=<?= $booking['id'] ?>"
                                                        class="btn btn-primary btn-sm" title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="?action=booking_delete&id=<?= $booking['id'] ?>"
                                                        class="btn btn-danger btn-sm" title="Xóa"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa booking này?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>

                                                <!-- Quick status update -->
                                                <div class="dropdown mt-1">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                        type="button" data-toggle="dropdown" aria-expanded="false">
                                                        Cập nhật
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <form method="POST" action="?action=updateBookingStatus"
                                                                style="margin: 0;">
                                                                <input type="hidden" name="id"
                                                                    value="<?= $booking['id'] ?>">
                                                                <input type="hidden" name="status" value="pending">
                                                                <button type="submit" class="dropdown-item">Đang
                                                                    chờ</button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form method="POST" action="?action=updateBookingStatus"
                                                                style="margin: 0;">
                                                                <input type="hidden" name="id"
                                                                    value="<?= $booking['id'] ?>">
                                                                <input type="hidden" name="status" value="confirmed">
                                                                <button type="submit" class="dropdown-item">Đã xác
                                                                    nhận</button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form method="POST" action="?action=updateBookingStatus"
                                                                style="margin: 0;">
                                                                <input type="hidden" name="id"
                                                                    value="<?= $booking['id'] ?>">
                                                                <input type="hidden" name="status" value="completed">
                                                                <button type="submit" class="dropdown-item">Đã hoàn
                                                                    thành</button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form method="POST" action="?action=updateBookingStatus"
                                                                style="margin: 0;">
                                                                <input type="hidden" name="id"
                                                                    value="<?= $booking['id'] ?>">
                                                                <input type="hidden" name="status" value="cancelled">
                                                                <button type="submit" class="dropdown-item">Đã
                                                                    hủy</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center">Không có booking nào</td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
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

    <!-- Members Modal (AJAX) -->
    <div class="modal fade" id="membersModal" tabindex="-1" role="dialog" aria-labelledby="membersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="membersModalLabel">Thành viên</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="membersModalBody">
                    <div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Đóng</button>
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
    <script src="assets/js/sidebar-sticky.js"></script>

    <!-- Page level plugins -->
    <script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="assets/js/demo/datatables-demo.js"></script>

    <script>
    // AJAX load members into modal
    $(document).on('click', '.btn-view-members', function(e) {
        e.preventDefault();
        var bookingId = $(this).data('booking-id');
        $('#membersModalBody').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
        $('#membersModal').modal('show');
        $.get('index.php', { action: 'booking_members_partial', booking_id: bookingId })
            .done(function(html) {
                $('#membersModalBody').html(html);
            }).fail(function() {
                $('#membersModalBody').html('<div class="alert alert-danger">Không thể tải danh sách thành viên.</div>');
            });
    });
    
    // AJAX load tour schedule into modal
    $(document).on('click', '.btn-view-schedule', function(e) {
        e.preventDefault();
        var tourId = $(this).data('tour-id');
        // create/open modal for schedule
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