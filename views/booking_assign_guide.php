<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Gán Hướng dẫn viên</title>

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
                <?php require_once 'views/includes/topbar.php'; ?>
                <!-- End of Topbar -->
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
                        <h1 class="h3 mb-0 text-gray-800">Gán Hướng dẫn viên</h1>
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

                    <!-- Booking Information -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card border-left-primary shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Thông tin Booking</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>Mã Booking:</strong></td>
                                                    <td><?= $booking['booking_code'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Khách hàng:</strong></td>
                                                    <td><?= $booking['customer_name'] ?? 'N/A' ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tour:</strong></td>
                                                    <td><?= $booking['tour_name'] ?? 'N/A' ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>Ngày đặt:</strong></td>
                                                    <td><?= date('d/m/Y', strtotime($booking['booking_date'])) ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Số người:</strong></td>
                                                    <td><?= $booking['member_count'] ?? $booking['pax_count'] ?? 0 ?> người</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tổng tiền:</strong></td>
                                                    <td><?= number_format($booking['total_amount'] ?? 0) ?> VND</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    
                                    <!-- Current Guide Info -->
                                    <?php if (!empty($booking['guide_id'])): ?>
                                        <?php
                                        // Get guide details if guide_id exists
                                        $guideModel = new Guide();
                                        $currentGuide = $guideModel->getGuideById($booking['guide_id']);
                                        ?>
                                        <?php if ($currentGuide): ?>
                                            <div class="alert alert-info mt-3">
                                                <strong>HDV hiện tại:</strong> <?= $currentGuide['name'] ?? 'N/A' ?>
                                                <?php if (!empty($currentGuide['email'])): ?>
                                                    (<?= $currentGuide['email'] ?>)
                                                <?php endif; ?>
                                                <?php if (!empty($currentGuide['phone'])): ?>
                                                    - ĐT: <?= $currentGuide['phone'] ?>
                                                <?php endif; ?>
                                                <a href="?action=booking_detail&id=<?= $booking['id'] ?>" class="btn btn-sm btn-warning float-right">
                                                    <i class="fas fa-exchange-alt"></i> Thay đổi HDV
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-warning mt-3">
                                                <i class="fas fa-exclamation-triangle"></i> HDV đã được gán nhưng không tìm thấy thông tin chi tiết.
                                                <a href="?action=booking_detail&id=<?= $booking['id'] ?>" class="btn btn-sm btn-warning float-right">
                                                    <i class="fas fa-exchange-alt"></i> Cập nhật HDV
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search and Filter Section -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-search"></i> Tìm kiếm Hướng dẫn viên
                            </h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="?action=booking_assign_guide" class="mb-4">
                                <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                                <div class="form-row">
                                    <div class="form-group col-md-8">
                                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên, điện thoại hoặc email..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-search"></i> Tìm kiếm
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Assign Guide Form -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-plus"></i>
                        <?= !empty($booking['guide_id']) ? 'Xác nhận thay đổi' : 'Gán' ?> Hướng dẫn viên
                    </h6>
                </div>
                        <div class="card-body">
        <form action="?action=store_assign_guide" method="POST">
            <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">

            <div class="form-group">
                <label for="guide_id">Chọn Hướng dẫn viên <span class="text-danger">*</span></label>
                <select class="form-control" id="guide_id" name="guide_id" required>
                    <option value="">-- Chọn hướng dẫn viên --</option>
                    <?php
                    // Debug: Check if guides array is empty and what it contains
                    error_log("Guides count: " . count($guides));
                    error_log("Guides data: " . print_r($guides, true));

                    foreach ($guides as $guide): ?>
                        <option value="<?= $guide['id'] ?>"
                                data-email="<?= $guide['email'] ?>"
                                data-phone="<?= $guide['phone'] ?>"
                                <?= (!empty($booking['guide_id']) && $booking['guide_id'] == $guide['id']) ? 'selected' : '' ?>>
                            <?= $guide['name'] ?? 'N/A' ?> - <?= $guide['email'] ?? 'N/A' ?>
                            <?php if (!empty($guide['phone'])): ?>
                                - <?= $guide['phone'] ?>
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($guides)): ?>
                    <div class="alert alert-warning mt-2">
                        <i class="fas fa-exclamation-triangle"></i> Không có hướng dẫn viên nào có sẵn. Vui lòng tạo tài khoản hướng dẫn viên mới trong phần Quản lý tài khoản.
                    </div>
                <?php endif; ?>
            </div>

                                <!-- Guide Info Display -->
                                <div id="guide-info" class="card border-left-info" style="display: none;">
                                    <div class="card-body">
                                        <h6 class="font-weight-bold text-info">Thông tin HDV được chọn:</h6>
                                        <div id="guide-details"></div>
                                    </div>
                                </div>

                                <div class="form-group text-right mt-4">
                                    <a href="?action=booking_detail&id=<?= $booking['id'] ?>" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Hủy
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-user-check"></i>
                                        <?= !empty($booking['guide_id']) ? 'Xác nhận thay đổi' : 'Xác nhận gán HDV' ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Available Guides List -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-users"></i> Danh sách Hướng dẫn viên có sẵn
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($guides)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Tên HDV</th>
                                <th>Email</th>
                                <th>Điện thoại</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($guides as $guide): ?>
                                <tr>
                                    <td><?= $guide['name'] ?? 'N/A' ?></td>
                                    <td><?= $guide['email'] ?? 'N/A' ?></td>
                                    <td><?= $guide['phone'] ?? 'N/A' ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary select-guide"
                                                data-id="<?= $guide['id'] ?>"
                                                data-name="<?= $guide['name'] ?? 'N/A' ?>">
                                            <i class="fas fa-hand-point-up"></i> Chọn
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center text-muted">
                    <i class="fas fa-user-slash fa-3x mb-3"></i>
                    <p>Không có hướng dẫn viên nào có sẵn trong hệ thống</p>
                    <div class="mt-3">
                        <a href="?action=account_management" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Tạo tài khoản hướng dẫn viên mới
                        </a>
                    </div>
                </div>
            <?php endif; ?>
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
        $(document).ready(function() {
            // Hiển thị thông tin HDV khi chọn
            $('#guide_id').change(function() {
                var selectedOption = $(this).find('option:selected');
                var guideId = selectedOption.val();
                
                if (guideId) {
                    var guideName = selectedOption.text().split(' - ')[0];
                    var guideEmail = selectedOption.data('email');
                    var guidePhone = selectedOption.data('phone');
                    
                    var html = '<p><strong>Tên:</strong> ' + guideName + '</p>';
                    html += '<p><strong>Email:</strong> ' + guideEmail + '</p>';
                    html += '<p><strong>Điện thoại:</strong> ' + (guidePhone ? guidePhone : 'N/A') + '</p>';
                    
                    $('#guide-details').html(html);
                    $('#guide-info').show();
                } else {
                    $('#guide-info').hide();
                }
            });

            // Chọn HDV từ bảng
            $('.select-guide').click(function() {
                var guideId = $(this).data('id');
                var guideName = $(this).data('name');
                
                $('#guide_id').val(guideId);
                $('#guide_id').trigger('change');
                
                // Highlight row
                $('.table-responsive tbody tr').removeClass('table-active');
                $(this).closest('tr').addClass('table-active');
            });

            // Trigger change event on page load if guide is already selected
            $('#guide_id').trigger('change');
        });
    </script>

</body>

</html>