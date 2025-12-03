<?php



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Quản lý Đối Tác</title>

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

            <?php require_once 'views/includes/topbar.php'; ?>

            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Quản lý Đối tác</h1>
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
                        <h6 class="m-0 font-weight-bold text-primary">Danh sách đối tác hiện có</h6>
                        <a href="?action=supplier_add" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Thêm Đối Tác
                        </a>
                    </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="suppliersTable" width="100%">
                            <thead>
                                <th>ID</th>
                                <th>Tên đối tác</th>
                                <th>Người liên hệ</th>
                                <th>Số điện thoại</th>
                                <th>Email</th>
                                <th>Loại nhà cung cấp</th>
                                <th>Địa chỉ</th>
                                <th>Thao tác</th>
                            </thead>
                            <tbody>
                                <?php if(!empty($suppliers)): ?>
                                    <?php foreach($suppliers as $supplier): ?>
                                        <tr>
                                            <td><?= $supplier['id'] ?></td>
                                            <td><?= $supplier['name'] ?></td>
                                            <td><?= $supplier['contact_person'] ?></td>
                                            <td><?= $supplier['phone'] ?></td>
                                            <td><?= $supplier['email'] ?></td>
                                            <td><?= $supplier['supplier_type'] ?></td>
                                            <td><?= $supplier['address'] ?></td>
                                            <td>
                                                <a href="index.php?controller=suppliers&action=supplier_edit&id=<?= $supplier['id'] ?>" class="btn btn-primary">Sửa</a>
                                                <a href="index.php?controller=suppliers&action=supplier_delete&id=<?= $supplier['id'] ?>" class="btn btn-danger">Xóa</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
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
                <a class="btn btn-primary" href="login.html">Logout</a>
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
