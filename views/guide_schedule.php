<?php if (!isset($guide) || !isset($schedule)) { ?>
    <div class="alert alert-danger">Không tìm thấy thông tin hướng dẫn viên hoặc lịch làm việc.</div>
    <?php return; } ?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Lịch làm việc - <?= htmlspecialchars($guide['name']) ?></title>

    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include 'includes/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php require_once 'views/includes/topbar.php'; ?>
                <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Lịch làm việc: <?= htmlspecialchars($guide['name']) ?></h1>
                </div>

                <div class="row">
                    <!-- Guide Info Card -->
                    <div class="col-lg-4 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Thông tin hướng dẫn viên</h6>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <img src="assets/img/undraw_profile_1.svg" alt="Avatar" class="img-fluid rounded-circle" style="width: 80px; height: 80px;">
                                </div>
                                <h5 class="text-center mb-3"><?= htmlspecialchars($guide['name']) ?></h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <strong>SĐT:</strong> <?= htmlspecialchars($guide['phone']) ?>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Email:</strong> <?= htmlspecialchars($guide['email'] ?? 'N/A') ?>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Loại HDV:</strong>
                                        <span class="btn btn-primary btn-sm fw-bold px-3 py-2"><?= htmlspecialchars($guide['guide_type'] ?? 'Nội địa') ?></span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Trạng thái:</strong>
                                        <span class="btn btn-<?= $guide['availability_status'] == 'Available' ? 'success' : ($guide['availability_status'] == 'Busy' ? 'warning' : ($guide['availability_status'] == 'On Leave' ? 'danger' : 'secondary')) ?> btn-sm fw-bold px-3 py-2">
                                            <?php
                                            $status_map = [
                                                'Available' => 'Sẵn sàng',
                                                'Busy' => 'Bận',
                                                'On Leave' => 'Nghỉ phép',
                                                'Unavailable' => 'Không khả dụng'
                                            ];
                                            echo htmlspecialchars($status_map[$guide['availability_status']] ?? 'Sẵn sàng');
                                            ?>
                                        </span>
                                    </li>
                                </ul>
                                <div class="mt-3">
                                    <a href="?action=guide_detail&id=<?= $guide['id'] ?>" class="btn btn-info btn-sm btn-block">
                                        <i class="fas fa-user"></i> Xem hồ sơ
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Management -->
                    <div class="col-lg-8 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Quản lý trạng thái sẵn sàng</h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Lưu ý:</strong> Hệ thống sử dụng trạng thái sẵn sàng chung cho hướng dẫn viên.
                                   
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card border-left-success shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                            Trạng thái hiện tại</div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                            <span class="btn btn-<?= $guide['availability_status'] == 'Available' ? 'success' : ($guide['availability_status'] == 'Busy' ? 'warning' : ($guide['availability_status'] == 'On Leave' ? 'danger' : 'secondary')) ?> btn-sm fw-bold px-3 py-2">
                                                                <?php
                                                                $status_map = [
                                                                    'Available' => 'Sẵn sàng',
                                                                    'Busy' => 'Bận',
                                                                    'On Leave' => 'Nghỉ phép',
                                                                    'Unavailable' => 'Không khả dụng'
                                                                ];
                                                                echo htmlspecialchars($status_map[$guide['availability_status']] ?? 'Sẵn sàng');
                                                                ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-left-info shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                            Cập nhật lần cuối</div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                            Hôm nay
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <h6 class="font-weight-bold text-primary mb-3">Cập nhật trạng thái</h6>
                                <form action="?action=update_availability" method="POST" class="mb-3">
                                    <input type="hidden" name="guide_id" value="<?= $guide['id'] ?>">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <select name="availability_status" class="form-control" required>
                                                <option value="Available" <?= ($guide['availability_status'] ?? 'Available') == 'Available' ? 'selected' : '' ?>>Sẵn sàng</option>
                                                <option value="Busy" <?= ($guide['availability_status'] ?? '') == 'Busy' ? 'selected' : '' ?>>Bận</option>
                                                <option value="On Leave" <?= ($guide['availability_status'] ?? '') == 'On Leave' ? 'selected' : '' ?>>Nghỉ phép</option>
                                                <option value="Unavailable" <?= ($guide['availability_status'] ?? '') == 'Unavailable' ? 'selected' : '' ?>>Không khả dụng</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fas fa-save"></i> Cập nhật
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Thao tác nhanh</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <button class="btn btn-success btn-block" onclick="setAvailability('Available')">
                                            <i class="fas fa-check-circle"></i><br>
                                            <small>Đặt sẵn sàng</small>
                                        </button>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-warning btn-block" onclick="setAvailability('Busy')">
                                            <i class="fas fa-clock"></i><br>
                                            <small>Đặt bận</small>
                                        </button>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-danger btn-block" onclick="setAvailability('On Leave')">
                                            <i class="fas fa-plane"></i><br>
                                            <small>Đặt nghỉ phép</small>
                                        </button>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="?action=guides" class="btn btn-secondary btn-block">
                                            <i class="fas fa-arrow-left"></i><br>
                                            <small>Quay lại danh sách</small>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Nhóm 1: &copy; Cao Đẳng FPT Polytechnic</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>


    <!-- Bootstrap core JavaScript-->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="assets/js/sb-admin-2.min.js"></script>


</body>
</html>