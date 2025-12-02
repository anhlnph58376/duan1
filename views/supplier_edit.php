<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Controller should provide $supplier and optionally $supplierTypes
$supplier = $supplier ?? [];
$supplierTypes = $supplierTypes ?? [];

// Flash + old data handling
$error_message = $_SESSION['error_message'] ?? null;
$old_data = $_SESSION['old_data'] ?? [];
unset($_SESSION['error_message'], $_SESSION['old_data']);

/**
 * Helper to get old value > supplier value > default, escaped.
 */
function old_val($key, $supplier, $old_data, $default = '') {
    if (isset($old_data[$key])) return htmlspecialchars($old_data[$key], ENT_QUOTES, 'UTF-8');
    if (isset($supplier[$key])) return htmlspecialchars($supplier[$key], ENT_QUOTES, 'UTF-8');
    return htmlspecialchars($default, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Chỉnh sửa Đối tác</title>

    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include 'views/includes/sidebar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php require_once 'views/includes/topbar.php'; ?>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Chỉnh sửa Đối tác</h1>
                        <a href="?action=suppliers" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Quay lại</a>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Thông tin Đối tác</h6>
                        </div>
                        <div class="card-body">
                            <?php if ($error_message): ?>
                                <div class="alert alert-danger"><?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') ?></div>
                            <?php endif; ?>

                            <form action="?action=updateSupplier" method="POST" novalidate>
                                <input type="hidden" name="id" value="<?= old_val('id', $supplier, $old_data, $supplier['id'] ?? '') ?>">

                                <div class="form-group">
                                    <label for="name">Tên Đối tác</label>
                                    <input type="text" class="form-control" id="name" name="name" required value="<?= old_val('name', $supplier, $old_data) ?>">
                                </div>

                                <div class="form-group">
                                    <label for="contact_person">Người liên hệ</label>
                                    <input type="text" class="form-control" id="contact_person" name="contact_person" value="<?= old_val('contact_person', $supplier, $old_data) ?>">
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?= old_val('email', $supplier, $old_data) ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="phone">Số điện thoại</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="<?= old_val('phone', $supplier, $old_data) ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="address">Địa chỉ</label>
                                    <input type="text" class="form-control" id="address" name="address" value="<?= old_val('address', $supplier, $old_data) ?>">
                                </div>

                                <div class="form-group">
                                    <label for="supplier_type">Loại cung cấp</label>
                                    <?php if (!empty($supplierTypes)): ?>
                                        <select id="supplier_type" name="supplier_type" class="form-control">
                                            <option value="">-- Chọn loại --</option>
                                            <?php
                                                $current = $old_data['supplier_type'] ?? $supplier['supplier_type'] ?? '';
                                                foreach ($supplierTypes as $type): ?>
                                                <option value="<?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>" <?= ((string)$current === (string)$type) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else: ?>
                                        <input type="text" id="supplier_type" name="supplier_type" class="form-control" value="<?= old_val('supplier_type', $supplier, $old_data) ?>">
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <a href="?action=suppliers" class="btn btn-light mr-2">Hủy</a>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu thay đổi</button>
                                </div>
                            </form>
                        </div>

                        <footer class="sticky-footer bg-white mt-3">
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

                <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

            </div> <!-- /#content -->

        </div> <!-- /#content-wrapper -->
    </div> <!-- /#wrapper -->

    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="assets/js/sb-admin-2.min.js"></script>
</body>

</html>