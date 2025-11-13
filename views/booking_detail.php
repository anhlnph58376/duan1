<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Chi tiết Booking</title>

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
                                <img class="img-profile rounded-circle" src="assets/img/undraw_profile.svg">
                            </a>
                        </li>
                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Chi tiết Booking</h1>
                        <div>
                            <a href="?action=bookings"
                                class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
                            </a>
                            <a href="?action=booking_edit&id=<?= $booking['id'] ?>"
                                class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                <i class="fas fa-edit fa-sm text-white-50"></i> Chỉnh sửa
                            </a>
                        </div>
                    </div>

                    <!-- Booking Code Card -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card border-left-primary shadow">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Mã Booking</div>
                                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                                <?= $booking['booking_code'] ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="text-right">
                                                <?php
                                                $statusClass = '';
                                                $statusText = '';
                                                switch ($booking['status']) {
                                                    case 'pending':
                                                        $statusClass = 'badge-warning';
                                                        $statusText = 'Chờ xử lý';
                                                        break;
                                                    case 'confirmed':
                                                        $statusClass = 'badge-info';
                                                        $statusText = 'Đã xác nhận';
                                                        break;
                                                    case 'assigned':
                                                        $statusClass = 'badge-primary';
                                                        $statusText = 'Đã gán HDV';
                                                        break;
                                                    case 'in_progress':
                                                        $statusClass = 'badge-secondary';
                                                        $statusText = 'Đang thực hiện';
                                                        break;
                                                    case 'completed':
                                                        $statusClass = 'badge-success';
                                                        $statusText = 'Hoàn thành';
                                                        break;
                                                    case 'cancelled':
                                                        $statusClass = 'badge-danger';
                                                        $statusText = 'Đã hủy';
                                                        break;
                                                    default:
                                                        $statusClass = 'badge-light';
                                                        $statusText = $booking['status'];
                                                }
                                                ?>
                                                <span
                                                    class="badge <?= $statusClass ?> badge-lg"><?= $statusText ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Information -->
                    <div class="row">

                        <!-- Customer Information -->
                        <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-user"></i> Thông tin Khách hàng
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Họ tên:</strong></td>
                                            <td><?= $booking['user_name'] ?? 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td><?= $booking['user_email'] ?? 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Điện thoại:</strong></td>
                                            <td><?= $booking['user_phone'] ?? 'N/A' ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Tour Information -->
                        <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-map-marked-alt"></i> Thông tin Tour
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Tên tour:</strong></td>
                                            <td><?= $booking['tour_name'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Mã tour:</strong></td>
                                            <td><span class="badge badge-info"><?= $booking['tour_code'] ?></span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Thời lượng:</strong></td>
                                            <td><?= $booking['duration'] ?> ngày</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Loại tour:</strong></td>
                                            <td>
                                                <?php if ($booking['is_international']): ?>
                                                <span class="badge badge-success">Quốc tế</span>
                                                <?php else: ?>
                                                <span class="badge badge-primary">Trong nước</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <!-- Booking Details -->
                        <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-calendar-check"></i> Chi tiết Booking
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Ngày đặt tour:</strong></td>
                                            <td><?= date('d/m/Y', strtotime($booking['booking_date'])) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Số người:</strong></td>
                                            <td><?= $booking['number_of_people'] ?> người</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Giá cơ bản:</strong></td>
                                            <td><?= number_format($booking['base_price']) ?> VND/người</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tổng tiền:</strong></td>
                                            <td><strong
                                                    class="text-success"><?= number_format($booking['total_price']) ?>
                                                    VND</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Ngày tạo:</strong></td>
                                            <td><?= date('d/m/Y H:i', strtotime($booking['created_at'])) ?></td>
                                        </tr>
                                        <?php if ($booking['updated_at']): ?>
                                        <tr>
                                            <td><strong>Cập nhật cuối:</strong></td>
                                            <td><?= date('d/m/Y H:i', strtotime($booking['updated_at'])) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Guide Information -->
                        <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-user-tie"></i> Thông tin Hướng dẫn viên
                                    </h6>
                                    <?php if (!$booking['guide_name']): ?>
                                    <a href="?action=assign_guide&id=<?= $booking['id'] ?>"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-user-plus"></i> Gán HDV
                                    </a>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <?php if ($booking['guide_name']): ?>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Họ tên:</strong></td>
                                            <td><?= $booking['guide_name'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td><?= $booking['guide_email'] ?? 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Điện thoại:</strong></td>
                                            <td><?= $booking['guide_phone'] ?? 'N/A' ?></td>
                                        </tr>
                                    </table>
                                    <div class="text-center mt-3">
                                        <a href="?action=assign_guide&id=<?= $booking['id'] ?>"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-user-edit"></i> Thay đổi HDV
                                        </a>
                                    </div>
                                    <?php else: ?>
                                    <div class="text-center text-muted">
                                        <i class="fas fa-user-slash fa-3x mb-3"></i>
                                        <p>Chưa có hướng dẫn viên được gán cho booking này</p>
                                        <a href="?action=assign_guide&id=<?= $booking['id'] ?>" class="btn btn-primary">
                                            <i class="fas fa-user-plus"></i> Gán HDV ngay
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Notes -->
                    <?php if (!empty($booking['notes'])): ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-sticky-note"></i> Ghi chú
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($booking['notes'])) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Tour Description -->
                    <?php if (!empty($booking['tour_description'])): ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-info-circle"></i> Mô tả Tour
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($booking['tour_description'])) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-cogs"></i> Thao tác
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Cập nhật trạng thái:</h6>
                                            <form action="?action=update_status" method="POST" class="d-inline-block">
                                                <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                                <div class="input-group">
                                                    <select name="status" class="form-control" required>
                                                        <option value="pending"
                                                            <?= ($booking['status'] == 'pending') ? 'selected' : '' ?>>
                                                            Chờ xử lý</option>
                                                        <option value="confirmed"
                                                            <?= ($booking['status'] == 'confirmed') ? 'selected' : '' ?>>
                                                            Đã xác nhận</option>
                                                        <option value="assigned"
                                                            <?= ($booking['status'] == 'assigned') ? 'selected' : '' ?>>
                                                            Đã gán HDV</option>
                                                        <option value="in_progress"
                                                            <?= ($booking['status'] == 'in_progress') ? 'selected' : '' ?>>
                                                            Đang thực hiện</option>
                                                        <option value="completed"
                                                            <?= ($booking['status'] == 'completed') ? 'selected' : '' ?>>
                                                            Hoàn thành</option>
                                                        <option value="cancelled"
                                                            <?= ($booking['status'] == 'cancelled') ? 'selected' : '' ?>>
                                                            Đã hủy</option>
                                                    </select>
                                                    <div class="input-group-append">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fas fa-save"></i> Cập nhật
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Các thao tác khác:</h6>
                                            <a href="?action=booking_edit&id=<?= $booking['id'] ?>"
                                                class="btn btn-primary">
                                                <i class="fas fa-edit"></i> Chỉnh sửa
                                            </a>
                                            <?php if (!$booking['guide_name']): ?>
                                            <a href="?action=assign_guide&id=<?= $booking['id'] ?>"
                                                class="btn btn-success">
                                                <i class="fas fa-user-plus"></i> Gán HDV
                                            </a>
                                            <?php endif; ?>
                                            <a href="?action=booking_delete&id=<?= $booking['id'] ?>"
                                                class="btn btn-danger"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa booking này?')">
                                                <i class="fas fa-trash"></i> Xóa
                                            </a>
                                        </div>
                                    </div>
                                </div>
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

</body>

</html>