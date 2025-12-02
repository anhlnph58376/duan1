<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Quản lý Booking</title>

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

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= BASE_URL ?>">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-globe-asia"></i>
                </div>
                <div class="sidebar-brand-text mx-3">H2A</div>
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
            <!DOCTYPE html>
            <html lang="vi">

            <head>
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                <title>Quản Lý Đoàn Du Lịch</title>

                <!-- Bootstrap CSS -->
                <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
                <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
                <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
                <link href="assets/css/custom.css" rel="stylesheet">
                <link href="assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
                <link
                    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
                    rel="stylesheet">
            </head>

            <body id="page-top">

                <!-- Page Wrapper -->
                <div id="wrapper">

                    <?php include __DIR__ . '/includes/sidebar.php'; ?>

                    <!-- Content Wrapper -->
                    <div id="content-wrapper" class="d-flex flex-column">

                        <!-- Main Content -->
                        <div id="content">

                            <!-- Topbar -->
                            <?php include __DIR__ . '/includes/topbar.php'; ?>

                                <!-- Sidebar Toggle (Topbar) -->
                                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                                    <i class="fa fa-bars"></i>
                                </button>

                                <!-- Topbar Search -->
                                <form
                                    class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small" placeholder="Tìm kiếm..."
                                            aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <!-- Topbar Navbar -->
                                <ul class="navbar-nav ml-auto">
                                    <div class="topbar-divider d-none d-sm-block"></div>
                                    <!-- Nav Item - User Information -->
                                    <li class="nav-item dropdown no-arrow">
                                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                                            <img class="img-profile rounded-circle" src="https://via.placeholder.com/60x60">
                                        </a>
                                    </li>
                                </ul>

                            </nav>
                            <!-- End of Topbar -->

                            <!-- Begin Page Content -->
                            <div class="container-fluid">

                                <!-- Display Messages -->
                                <?php if (isset($_SESSION['message'])): ?>
                                <div class="alert alert-<?= $_SESSION['message_type'] == 'success' ? 'success' : 'danger' ?> alert-dismissible fade show"
                                    role="alert">
                                    <i
                                        class="fas fa-<?= $_SESSION['message_type'] == 'success' ? 'check-circle' : 'exclamation-triangle' ?> mr-2"></i>
                                    <?= htmlspecialchars($_SESSION['message']) ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <?php 
                                    unset($_SESSION['message']);
                                    unset($_SESSION['message_type']);
                                    ?>
                                <?php endif; ?>

                                <!-- Page Heading -->
                                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                    <h1 class="h3 mb-0 text-gray-800">Quản Lý Đoàn Du Lịch</h1>
                                </div>

                                <!-- DataTable -->
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Danh Sách Booking Có Thành Viên</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Mã booking</th>
                                                        <th>Tour</th>
                                                        <th>Khách hàng</th>
                                                        <th>Ngày booking</th>
                                                        <th>Trạng thái</th>
                                                        <th>Số thành viên</th>
                                                        <th>Hành động</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (isset($bookings_with_members) && !empty($bookings_with_members)): ?>
                                                        <?php foreach ($bookings_with_members as $b): ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($b['id']) ?></td>
                                                                <td><?= htmlspecialchars($b['booking_code'] ?? '') ?></td>
                                                                <td><?= htmlspecialchars($b['tour_name'] ?? '—') ?></td>
                                                                <td><?= htmlspecialchars($b['customer_name'] ?? 'N/A') ?>
                                                                    <br><small><?= htmlspecialchars($b['customer_phone'] ?? '') ?></small>
                                                                </td>
                                                                <td><?= !empty($b['booking_date']) ? date('d/m/Y H:i', strtotime($b['booking_date'])) : '' ?></td>
                                                                <td><?= htmlspecialchars($b['status'] ?? '') ?></td>
                                                                <td><?= htmlspecialchars($b['member_count'] ?? 0) ?></td>
                                                                <td>
                                                                    <button type="button" class="btn btn-sm btn-info btn-show-members" data-booking-id="<?= $b['id'] ?>">Thành viên</button>
                                                                    <a href="index.php?action=booking_member_add&booking_id=<?= $b['id'] ?>" class="btn btn-sm btn-success">Thêm thành viên</a>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="7" class="text-center">Chưa có booking có thành viên</td>
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

                    </div>
                    <!-- End of Content Wrapper -->

                </div>
                <!-- End of Page Wrapper -->

                <!-- Add Booking to Departure Modal -->
                <?php if (isset($departures) && !empty($departures)): ?>
                <?php foreach ($departures as $departure): ?>
                <div class="modal fade" id="addBookingToDepartureModal<?= $departure['id'] ?>" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Thêm booking vào đoàn #<?= $departure['id'] ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Thông tin đoàn:</label>
                                    <div class="alert alert-info">
                                        <strong><?= htmlspecialchars($departure['tour_name'] ?? 'N/A') ?></strong><br>
                                        Khởi hành: <?= date('d/m/Y', strtotime($departure['departure_date'])) ?><br>
                                        Còn lại: <?= ($departure['max_pax'] ?? 999) - ($departure['booking_count'] ?? 0) ?> chỗ
                                    </div>
                                </div>

                                <!-- Tab Navigation -->
                                <ul class="nav nav-tabs mb-3" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="existing-booking-tab<?= $departure['id'] ?>" data-toggle="tab" 
                                           href="#existing-booking<?= $departure['id'] ?>" role="tab">
                                            <i class="fas fa-list"></i> Chọn booking có sẵn
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="new-booking-tab<?= $departure['id'] ?>" data-toggle="tab" 
                                           href="#new-booking<?= $departure['id'] ?>" role="tab">
                                            <i class="fas fa-plus"></i> Tạo booking mới
                                        </a>
                                    </li>
                                </ul>

                                <!-- Tab Content -->
                                <div class="tab-content">
                                    <!-- Existing Booking Tab -->
                                    <div class="tab-pane fade show active" id="existing-booking<?= $departure['id'] ?>" role="tabpanel">
                                        <form method="POST" action="?action=departure_add_existing_booking" id="existingBookingForm<?= $departure['id'] ?>">
                                            <input type="hidden" name="departure_id" value="<?= $departure['id'] ?>">
                                
                                            <div class="form-group">
                                                <label for="booking_id<?= $departure['id'] ?>">Chọn booking:</label>
                                                <select class="form-control" name="booking_id" id="booking_id<?= $departure['id'] ?>" required>
                                                    <option value="">-- Chọn booking --</option>
                                                    <?php if (isset($availableBookings)): ?>
                                                        <?php foreach ($availableBookings as $booking): ?>
                                                            <option value="<?= $booking['id'] ?>">
                                                                #<?= $booking['id'] ?> - <?= htmlspecialchars($booking['customer_name'] ?? 'N/A') ?>
                                                                (<?= htmlspecialchars($booking['customer_phone'] ?? 'N/A') ?>) -
                                                                <?= number_format($booking['total_amount'] ?? 0) ?>₫
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                
                                            <div class="form-group">
                                                <label for="pax_count<?= $departure['id'] ?>">Số người tham gia:</label>
                                                <input type="number" class="form-control" name="pax_count" 
                                                       id="pax_count<?= $departure['id'] ?>" value="1" min="1" max="50" required>
                                            </div>
                                
                                            <div class="text-right">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-plus"></i> Thêm booking có sẵn
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- New Booking Tab -->
                                    <div class="tab-pane fade" id="new-booking<?= $departure['id'] ?>" role="tabpanel">
                                        <div class="text-center py-4">
                                            <p class="text-muted mb-3">
                                                <i class="fas fa-info-circle"></i> 
                                                Tạo booking mới và tự động thêm vào đoàn này
                                            </p>
                                            <a href="index.php?action=departure_create_new_booking&departure_id=<?= $departure['id'] ?>" 
                                               class="btn btn-primary btn-lg">
                                                <i class="fas fa-plus-circle"></i> Tạo booking mới
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>

                <!-- Members Modal (AJAX loaded) -->
                <div class="modal fade" id="bookingMembersModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Thành viên booking</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="bookingMembersContent">
                                <div class="text-center py-4">Đang tải...</div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Click handler for 'Thành viên' buttons moved to bottom to ensure jQuery is loaded -->

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
                <script src="assets/js/sidebar-sticky.js"></script>

                <!-- Page level plugins -->
                <script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
                <script src="assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

                <script>
                $(document).ready(function() {
                    // Handle show-members click: load partial into modal
                    $(document).on('click', '.btn-show-members', function(e) {
                        e.preventDefault();
                        var bookingId = $(this).data('booking-id');
                        $('#bookingMembersContent').html('<div class="text-center py-4">Đang tải...</div>');
                        $('#bookingMembersModal').modal('show');
                        $.get('index.php', { action: 'booking_members_partial', booking_id: bookingId }, function(html) {
                            $('#bookingMembersContent').html(html);
                        }).fail(function() {
                            $('#bookingMembersContent').html('<div class="alert alert-danger">Không tải được danh sách thành viên.</div>');
                        });
                    });
                    $('#dataTable').DataTable({
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Vietnamese.json"
                        },
                        "order": [
                            [0, "desc"]
                        ]
                    });
                });
                </script>
