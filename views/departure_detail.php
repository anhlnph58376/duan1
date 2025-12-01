<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Chi tiết đoàn</title>

    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

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

                    <!-- Topbar Search -->
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

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 7, 2019</div>
                                        $290.29 has been deposited into your account!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 2, 2019</div>
                                        Spending Alert: We've noticed unusually high spending for your account.
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_1.svg" alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                            problem I've been having.</div>
                                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_2.svg" alt="...">
                                        <div class="status-indicator"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">I have the photos that you ordered last month, how
                                            would you like them sent to you?</div>
                                        <div class="small text-gray-500">Jae Chun · 1d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_3.svg" alt="...">
                                        <div class="status-indicator bg-warning"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Last month's report looks great, I am very happy with
                                            the progress so far, keep up the good work!</div>
                                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="https://source.unsplash.com/60x60/?portrait"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                            told me that people say this to all dogs, even if they aren't good...</div>
                                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
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
                        <h1 class="h3 mb-0 text-gray-800">Chi tiết đoàn:
                            <?= htmlspecialchars($departure['tour_name'] ?? 'N/A') ?></h1>
                        <div>
                            <a href="?action=departure_edit&id=<?= $departure['id'] ?>"
                                class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                <i class="fas fa-edit fa-sm text-white-50"></i> Chỉnh sửa
                            </a>
                            <a href="?action=departures"
                                class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm ml-2">
                                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
                            </a>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Departure Info Card -->
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Thông tin đoàn</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Mã đoàn:</strong>
                                                #<?= str_pad($departure['id'], 6, '0', STR_PAD_LEFT) ?></p>
                                            <p><strong>Tour:</strong>
                                                <?php if (!empty($departure['tour_id'])): ?>
                                                <a href="?action=tour_detail&id=<?= $departure['tour_id'] ?>"
                                                    class="text-decoration-none">
                                                    <?= htmlspecialchars($departure['tour_name'] ?? 'N/A') ?>
                                                </a>
                                                <?php else: ?>
                                                    <?= htmlspecialchars($departure['tour_name'] ?? 'N/A') ?>
                                                <?php endif; ?>
                                            </p>
                                            <p><strong>Ngày khởi hành:</strong>
                                                <?= date('d/m/Y', strtotime($departure['departure_date'])) ?></p>
                                            <p><strong>Ngày kết thúc:</strong>
                                                <?= date('d/m/Y', strtotime($departure['return_date'])) ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Số lượng tối thiểu:</strong>
                                                <?= number_format($departure['min_pax'] ?? 0) ?> người</p>
                                            <p><strong>Số lượng tối đa:</strong>
                                                <?= number_format($departure['max_pax'] ?? 0) ?> người</p>
                                            <p><strong>Đã đăng ký:</strong>
                                                <?= number_format($departure['booking_count'] ?? 0) ?> người</p>
                                            <p><strong>Còn lại:</strong>
                                                <?= number_format(($departure['max_pax'] ?? 0) - ($departure['booking_count'] ?? 0)) ?>
                                                chỗ</p>
                                            <p><strong>Hướng dẫn viên:</strong>
                                                <?= htmlspecialchars($departure['guide_name'] ?? 'Chưa phân công') ?></p>
                                            <p><strong>Giá tour:</strong> <?= number_format($departure['base_price'] ?? 0) ?> VND
                                            </p>
                                            <p><strong>Trạng thái:</strong>
                                                <?php
                                                $status_badges = [
                                                    'Scheduled' => '<span class="badge badge-primary">Đã lên lịch</span>',
                                                    'In Progress' => '<span class="badge badge-info">Đang thực hiện</span>',
                                                    'Completed' => '<span class="badge badge-success">Hoàn thành</span>',
                                                    'Canceled' => '<span class="badge badge-danger">Hủy bỏ</span>'
                                                ];
                                                echo $status_badges[$departure['status']] ?? '<span class="badge badge-secondary">Không xác định</span>';
                                                ?>
                                            </p>
                                        </div>
                                    </div>

                                    <?php if (!empty($departure['notes'])): ?>
                                    <hr>
                                    <div class="row">
                                        <div class="col-12">
                                            <strong>Ghi chú:</strong>
                                            <p class="mt-2 text-muted">
                                                <?= nl2br(htmlspecialchars($departure['notes'] ?? '')) ?></p>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics Cards -->
                        <div class="col-lg-4">

                            <!-- Booking Count Card -->
                            <div class="card border-left-primary shadow h-100 py-2 mb-4">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Số booking</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= number_format($departure['booking_count'] ?? 0) ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Occupancy Rate Card -->
                            <div class="card border-left-info shadow h-100 py-2 mb-4">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Tỷ lệ lấp đầy</div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                        <?php 
                                                        $occupancy = ($departure['max_pax'] ?? 0) > 0 
                                                            ? round(($departure['booking_count'] ?? 0) / ($departure['max_pax'] ?? 1) * 100, 1)
                                                            : 0;
                                                        echo $occupancy;
                                                        ?>%
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: <?= $occupancy ?>%"
                                                            aria-valuenow="<?= $occupancy ?>" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Revenue Card -->
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Doanh thu ước tính</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= number_format(($departure['booking_count'] ?? 0) * ($departure['base_price'] ?? 0)) ?>
                                                VND
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bookings Table -->
                    <?php if (!empty($bookings)): ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Danh sách booking trong đoàn</h6>
                                    <div>
                                        <a href="index.php?action=departure_add_booking&departure_id=<?= $departure['id'] ?>" 
                                           class="btn btn-success btn-sm mr-2">
                                            <i class="fas fa-plus"></i> Thêm booking có sẵn
                                        </a>
                                        <a href="index.php?action=departure_create_new_booking&departure_id=<?= $departure['id'] ?>" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-user-plus"></i> Tạo booking mới
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Mã booking</th>
                                                    <th>Tên khách hàng</th>
                                                    <th>Email</th>
                                                    <th>Số điện thoại</th>
                                                    <th>Số người</th>
                                                    <th>Tổng tiền</th>
                                                    <th>Trạng thái</th>
                                                    <th>Ngày đặt</th>
                                                    <th>Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($bookings as $index => $booking): ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td>#<?= str_pad($booking['id'], 6, '0', STR_PAD_LEFT) ?></td>
                                                    <td><?= htmlspecialchars($booking['name'] ?? $booking['customer_name'] ?? 'N/A') ?></td>
                                                    <td><?= htmlspecialchars($booking['email'] ?? $booking['customer_email'] ?? 'N/A') ?></td>
                                                    <td><?= htmlspecialchars($booking['phone'] ?? $booking['customer_phone'] ?? 'N/A') ?></td>
                                                    <td><?= number_format($booking['quantity'] ?? $booking['pax_count'] ?? 0) ?></td>
                                                    <td><?= number_format($booking['total_amount'] ?? 0) ?> VND</td>
                                                    <td>
                                                        <?php
                                                        $booking_badges = [
                                                            'pending' => '<span class="badge badge-warning">Chờ xác nhận</span>',
                                                            'confirmed' => '<span class="badge badge-success">Đã xác nhận</span>',
                                                            'cancelled' => '<span class="badge badge-danger">Đã hủy</span>',
                                                            'completed' => '<span class="badge badge-primary">Hoàn thành</span>'
                                                        ];
                                                        echo $booking_badges[$booking['status'] ?? 'pending'] ?? '<span class="badge badge-secondary">Không xác định</span>';
                                                        ?>
                                                    </td>
                                                    <td><?= date('d/m/Y H:i', strtotime($booking['created_at'] ?? $booking['booking_date'] ?? 'now')) ?></td>
                                                    <td>
                                                        <a href="?action=booking_detail&id=<?= $booking['id'] ?>"
                                                            class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Danh sách booking trong đoàn</h6>
                                </div>
                                <div class="card-body text-center">
                                    <div class="my-4">
                                        <i class="fas fa-users fa-4x text-gray-300 mb-3"></i>
                                        <h5 class="text-gray-500">Chưa có booking nào</h5>
                                        <p class="text-gray-400">Đoàn này chưa có booking nào được đăng ký</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

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

    <!-- Bootstrap core JavaScript-->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="assets/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Vietnamese.json"
            }
        });
    });
    </script>

</body>

</html>