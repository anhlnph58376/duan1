<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Quản lý Tour - Admin</title>

    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">

    <style>
        .tour-image {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body id="page-top">

<div id="wrapper">

    <?php include 'views/includes/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">

        <div id="content">

            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin User</span>
                            <img class="img-profile rounded-circle" src="assets/img/undraw_profile.svg">
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

            <div class="container-fluid">

                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Quản lý tour</h1>
                </div>

                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Thành công!</strong> <?= $_SESSION['success_message']; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Lỗi!</strong> <?= $_SESSION['error_message']; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Danh sách tour hiện có</h6>
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['username'] !== 'guide1'): ?>
                        <a href="?action=tour_add" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Thêm tour mới
                        </a>
                        <?php endif; ?>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="dataTable" width="100%"
                                   cellspacing="0">
                                <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Ảnh</th>
                                    <th>Mã Tour</th>
                                    <th>Tên Tour</th>
                                    <th>Danh mục</th>
                                    <th>Khởi hành</th>
                                    <th>Điểm đến</th>
                                    <th>Thời gian</th>
                                    <th>Giá (Người lớn)</th>
                                    <th>Giá (Trẻ em)</th>
                                    <th>Giá (Em bé)</th>
                                    <th>Loại Tour</th>
                                    <th>Mô tả ngắn</th>
                                    <th style="width: 120px;">Thao tác</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php if (!empty($tours)): ?>
                                    <?php foreach ($tours as $tour): ?>
                                        <tr>
                                            <td><?= $tour['id'] ?></td>

                                            <td class="text-center">
                                                <?php if (!empty($tour['image'])): ?>
                                                    <img src="<?= htmlspecialchars($tour['image']) ?>" alt="Tour Image"
                                                         class="tour-image">
                                                <?php else: ?>
                                                    <span class="badge badge-secondary">No Image</span>
                                                <?php endif; ?>
                                            </td>

                                            <td class="font-weight-bold text-primary">
                                                <?= htmlspecialchars($tour['tour_code']) ?>
                                            </td>

                                            <td><?= htmlspecialchars($tour['name']) ?></td>

                                            <td>
                                                <?= htmlspecialchars($tour['category_name'] ?? '-') ?>
                                            </td>

                                            <td><?= htmlspecialchars($tour['departure_point'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($tour['destination'] ?? '-') ?></td>

                                            <td><?= htmlspecialchars($tour['duration'] ?? '-') ?></td>

                                            <td class="font-weight-bold text-danger">
                                                <?= number_format($tour['base_price'] ?? 0, 0, ',', '.') ?> đ
                                            </td>

                                            <td><?= number_format($tour['price_child'] ?? 0, 0, ',', '.') ?> đ</td>

                                            <td><?= number_format($tour['price_infant'] ?? 0, 0, ',', '.') ?> đ</td>

                                            <td class="text-center">
                                                <?php if (($tour['is_international'] ?? 0) == 1): ?>
                                                    <span class="badge badge-warning px-2 py-1">Quốc tế</span>
                                                <?php else: ?>
                                                    <span class="badge badge-info px-2 py-1">Nội địa</span>
                                                <?php endif; ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($tour['description'] ?? '-') ?>
                                            </td>

                                            <td class="align-middle text-center">
                                                <a href="?action=tour_detail&id=<?= $tour['id'] ?>"
                                                   class="btn btn-info btn-circle btn-sm" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <?php if (isset($_SESSION['user']) && $_SESSION['user']['username'] !== 'guide1'): ?>
                                                <a href="?action=tour_edit&id=<?= $tour['id'] ?>"
                                                   class="btn btn-warning btn-circle btn-sm" title="Sửa tour">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <a href="?action=tour_delete&id=<?= $tour['id'] ?>"
                                                   class="btn btn-danger btn-circle btn-sm"
                                                   onclick="return confirm('Bạn có chắc chắn muốn xóa tour này không? Mọi dữ liệu liên quan sẽ bị mất!');"
                                                   title="Xóa tour">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="17" class="text-center text-muted py-4">
                                            Chưa có dữ liệu tour nào.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Your Website 2024</span>
                </div>
            </div>
        </footer>

    </div>

</div>

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
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

<script src="assets/vendor/jquery/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="assets/js/sb-admin-2.min.js"></script>

</body>

</html>
