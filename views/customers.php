<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title></title>

    <!-- Phông chữ tùy chỉnh cho template này-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Styles tùy chỉnh cho template này-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include 'views/includes/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php require_once 'views/includes/topbar.php'; ?>
                <!-- End of Topbar -->

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
                            </a>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                            </a>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

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

            </div>
            <!-- End of Main Content -->
            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Quản lý khách hàng</h1>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Danh sách khách hàng</h6>
                        <div>
                            <a href="?action=printGroupList&preview=1" target="_blank" class="btn btn-success me-2">
                                <i class="fas fa-print"></i> In danh sách đoàn
                            </a>
                            <a href="?action=customer_add" class="btn btn-primary">Thêm khách hàng</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên khách hàng</th>
                                        <th>Số điện thoại</th>
                                        <th>Email</th>
                                        <th>Giới tính</th>
                                        <th>Năm sinh</th>
                                        <th>Giấy tờ tùy thân</th>
                                        <th>Tình trạng thanh toán</th>
                                        <th>Check-in</th>
                                        <th>Phòng</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($customers as $customer) { ?>
                                    <tr>
                                        <td><?= $customer['id'] ?></td>
                                        <td><?= $customer['name'] ?></td>
                                        <td><?= $customer['phone'] ?></td>
                                        <td><?= $customer['email'] ?? 'N/A' ?></td>
                                        <td><?= $customer['gender'] ?? 'N/A' ?></td>
                                        <td><?= $customer['year_of_birth'] ?? 'N/A' ?></td>
                                        <td>
                                            <?php if (!empty($customer['id_type']) && !empty($customer['id_number'])): ?>
                                                <?= htmlspecialchars($customer['id_type']) ?><br>
                                                <small class="text-muted"><?= htmlspecialchars($customer['id_number']) ?></small>
                                            <?php else: ?>
                                                N/A
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="btn btn-<?= $customer['payment_status'] == 'Đã thanh toán' ? 'success' : ($customer['payment_status'] == 'Thanh toán một phần' ? 'warning' : 'danger') ?> btn-sm fw-bold px-3 py-2">
                                                <?= $customer['payment_status'] ?? 'Chưa thanh toán' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="btn btn-<?= $customer['checkin_status'] == 'Đã đến' ? 'success' : ($customer['checkin_status'] == 'Vắng mặt' ? 'danger' : 'secondary') ?> btn-sm fw-bold px-3 py-2">
                                                <?= $customer['checkin_status'] ?? 'Chưa đến' ?>
                                            </span>
                                        </td>
                                        <td><?= $customer['room_allocation'] ?? 'N/A' ?></td>
                                        <td class="align-middle">
                                            <div class="btn-group" role="group">
                                                <a href="?action=customer_edit&id=<?= $customer['id'] ?>"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?action=customer_delete&id=<?= $customer['id'] ?>"
                                                    class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <a href="?action=customer_detail&id=<?= $customer['id'] ?>"
                                                    class="btn btn-info btn-sm">
                                                    <i class="fas fa-search"></i>
                                                </a>
                                            </div>
                                            <div class="mt-1">
                                                <form method="POST" action="?action=updateCheckinStatus" class="d-inline">
                                                    <input type="hidden" name="customer_id" value="<?= $customer['id'] ?>">
                                                    <select name="checkin_status" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                                                        <option value="Chưa đến" <?= ($customer['checkin_status'] ?? '') == 'Chưa đến' ? 'selected' : '' ?>>Chưa đến</option>
                                                        <option value="Đã đến" <?= ($customer['checkin_status'] ?? '') == 'Đã đến' ? 'selected' : '' ?>>Đã đến</option>
                                                        <option value="Vắng mặt" <?= ($customer['checkin_status'] ?? '') == 'Vắng mặt' ? 'selected' : '' ?>>Vắng mặt</option>
                                                    </select>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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
                    <a class="btn btn-primary" href="?action=logout">Đăng Xuất</a>
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
    <script src="assets/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="assets/js/demo/chart-area-demo.js"></script>
    <script src="assets/js/demo/chart-pie-demo.js"></script>

</body>

</html>