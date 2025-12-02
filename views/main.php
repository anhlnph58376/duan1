<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include 'views/includes/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column" style="min-height:100vh;">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3" onclick="document.querySelector('.sticky-sidebar').classList.toggle('show');">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?= htmlspecialchars($_SESSION['user_name'] ?? 'Guest', ENT_QUOTES, 'UTF-8') ?>
                                </span>
                                <?php
                                // Get current user avatar
                                $currentUserAvatar = null;
                                if (isset($_SESSION['user_id'])) {
                                    $userModel = new User();
                                    $currentUserData = $userModel->find($_SESSION['user_id']);
                                    if ($currentUserData && !empty($currentUserData['image']) && file_exists($currentUserData['image'])) {
                                        $currentUserAvatar = $currentUserData['image'];
                                    }
                                }
                                ?>
                                <?php if ($currentUserAvatar): ?>
                                    <img class="img-profile rounded-circle" src="<?= htmlspecialchars($currentUserAvatar) ?>" 
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                <?php else: ?>
                                    <img class="img-profile rounded-circle" src="assets/img/undraw_profile.svg">
                                <?php endif; ?>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="?action=profile">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Thông tin cá nhân
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="index.php?action=logout">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Đăng xuất
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                                <?php
                                // Ensure default datasets exist to prevent JS errors and empty panels
                                $monthlyRevenue = (isset($monthlyRevenue) && is_array($monthlyRevenue)) ? $monthlyRevenue : array_fill(0, 12, 0);
                                $pieStats = (isset($pieStats) && is_array($pieStats) && count($pieStats) > 0) ? $pieStats : ['No data' => 1];
                                $topBookings = isset($topBookings) ? $topBookings : [];
                                $topCustomers = isset($topCustomers) ? $topCustomers : [];
                                $bookingStats = isset($bookingStats) ? $bookingStats : [];
                                $departureStats = isset($departureStats) ? $departureStats : [
                                    'total_departures' => 0,
                                    'total_bookings' => 0,
                                    'total_guests' => 0,
                                    'scheduled' => 0,
                                    'in_progress' => 0,
                                    'completed' => 0,
                                    'canceled' => 0
                                ];
                                ?>
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i></a>
                    </div>

                    <!-- Content Row: 3 key metric cards -->
                    <div class="row">
                        <div class="col-xl-4 col-md-6 mb-4 d-flex align-items-stretch">
                            <div class="card border-left-primary shadow h-100 w-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng doanh thu</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= isset($totalRevenue) ? number_format($totalRevenue, 0, ',', '.') . ' VNĐ' : '0 VNĐ'; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-coins fa-2x text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 mb-4 d-flex align-items-stretch">
                            <div class="card border-left-success shadow h-100 w-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tổng booking</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= isset($totalBookings) ? $totalBookings : 0; ?> Booking
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-book fa-2x text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-12 mb-4 d-flex align-items-stretch">
                            <div class="card border-left-info shadow h-100 w-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tổng khách hàng</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= isset($totalCustomers) ? $totalCustomers : 0; ?> Khách hàng
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-info"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top tables -->
                    <div class="row mb-4">
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow mb-4 h-100">
                                <div class="card-header py-3 text-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Top 5 booking mới nhất</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-center">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Mã booking</th>
                                                    <th>Khách hàng</th>
                                                    <th>Số tiền</th>
                                                    <th>Trạng thái</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($topBookings)) : ?>
                                                    <?php foreach ($topBookings as $b) : ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($b['booking_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                            <td><?= htmlspecialchars($b['customer_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                            <td><?= isset($b['total_amount']) ? number_format($b['total_amount'], 0, ',', '.') . ' VNĐ' : '0 VNĐ'; ?></td>
                                                            <td><?= htmlspecialchars($b['status'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <tr><td colspan="4">Không có dữ liệu.</td></tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow mb-4 h-100">
                                <div class="card-header py-3 text-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Top 5 khách hàng nhiều booking nhất</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-center">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Khách hàng</th>
                                                    <th>Số booking</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($topCustomers)) : ?>
                                                    <?php foreach ($topCustomers as $c) : ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($c['customer_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                            <td><span class="badge badge-success"><?= htmlspecialchars($c['count'] ?? 0, ENT_QUOTES, 'UTF-8'); ?></span></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <tr><td colspan="2">Không có dữ liệu.</td></tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Revenue chart -->
                        <div class="col-xl-8 col-lg-7 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Doanh thu theo tháng (<?= date('Y'); ?>)</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="revenueBarChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- Booking status pie -->
                        <div class="col-xl-4 col-lg-5 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Tỷ lệ trạng thái booking</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="bookingPieChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script src="assets/vendor/chart.js/Chart.min.js"></script>
                    <script>
                        // Revenue bar chart
                        var ctxBar = document.getElementById('revenueBarChart')?.getContext('2d');
                        if (ctxBar) {
                            new Chart(ctxBar, {
                                type: 'bar',
                                data: {
                                    labels: ["1","2","3","4","5","6","7","8","9","10","11","12"],
                                    datasets: [{
                                        label: 'Doanh thu (VNĐ)',
                                        backgroundColor: '#4e73df',
                                        borderColor: '#4e73df',
                                        data: <?= json_encode(array_values($monthlyRevenue)); ?>,
                                    }]
                                },
                                options: { responsive: true, scales: { y: { beginAtZero: true } } }
                            });
                        }

                        // Booking pie chart
                        var ctxPie = document.getElementById('bookingPieChart')?.getContext('2d');
                        if (ctxPie) {
                            new Chart(ctxPie, {
                                type: 'pie',
                                data: {
                                    labels: <?= json_encode(array_keys($pieStats)); ?>,
                                    datasets: [{ data: <?= json_encode(array_values($pieStats)); ?>, backgroundColor: ['#4e73df','#1cc88a','#36b9cc','#f6c23e'] }]
                                }
                            });
                        }
                    </script>

                        // Biểu đồ tròn trạng thái booking
                        var ctxPie = document.getElementById('bookingPieChart').getContext('2d');
                        var bookingPieChart = new Chart(ctxPie, {
                            type: 'pie',
                            data: {
                                labels: <?php echo json_encode(array_keys($pieStats)); ?>,
                                datasets: [{
                                    data: <?php echo json_encode(array_values($pieStats)); ?>,
                                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'],
                                }],
                            },
                            options: {
                                responsive: true
                            }
                        });
                    </script>
                    </div>

                    <!-- Content Row -->
                    <div class="row justify-content-center">
                        <div class="col-lg-8 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 text-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Thống kê trạng thái booking</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-center">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="text-center">Trạng thái</th>
                                                    <th class="text-center">Số booking</th>
                                                    <th class="text-center">Tổng doanh thu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($bookingStats)) : ?>
                                                    <?php foreach ($bookingStats as $stat) : ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($stat['status']); ?></td>
                                                            <td><span class="badge badge-primary"><?php echo $stat['count']; ?></span></td>
                                                            <td><span class="font-weight-bold text-success"><?php echo number_format($stat['total_amount'], 0, ',', '.'); ?> VNĐ</span></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <tr><td colspan="3">Không có dữ liệu thống kê trạng thái booking.</td></tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
>>>>>>> 86fafd4 (Commit all changes before pulling)
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <!-- Đã xóa copyright ở đây theo yêu cầu -->
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

<<<<<<< HEAD
=======
</body>

</html>

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
>>>>>>> 86fafd4 (Commit all changes before pulling)

    <!-- Bootstrap core JavaScript-->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="assets/js/sb-admin-2.min.js"></script>
    <script src="assets/js/sidebar-sticky.js"></script>

    <!-- Page level plugins -->
    <script src="assets/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="assets/js/demo/chart-area-demo.js"></script>
    <script src="assets/js/demo/chart-pie-demo.js"></script>

</body>

</html>