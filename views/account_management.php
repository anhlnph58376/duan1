<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quản Lý Tài Khoản</title>
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
                    <h1 class="h3 mb-0 text-gray-800">Quản Lý Tài Khoản</h1>
                    <a href="index.php?action=account_add" class="btn btn-primary btn-sm">
                        <i class="fas fa-user-plus"></i> Đăng ký tài khoản mới
                    </a>
                </div>

                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $_SESSION['success_message']; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $_SESSION['error_message']; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Danh sách tài khoản</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên đăng nhập</th>
                                        <th>Vai trò</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($users)): foreach ($users as $u): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($u['id']) ?></td>
                                            <td><?= htmlspecialchars($u['username']) ?></td>
                                            <td><?= htmlspecialchars($u['role_name'] ?? '') ?></td>
                                            <td>
                                                <?php if (($u['is_active'] ?? 1) == 1): ?>
                                                    <span class="badge badge-success">Đang hoạt động</span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary">Đã khóa</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-primary" href="index.php?action=account_profile&id=<?= (int)$u['id'] ?>" title="Xem hồ sơ">
                                                    <i class="fas fa-user"></i> Hồ sơ
                                                </a>
                                                <a class="btn btn-sm btn-info" href="index.php?action=account_edit&id=<?= (int)$u['id'] ?>">
                                                    <i class="fas fa-edit"></i> Sửa
                                                </a>
                                                <a class="btn btn-sm btn-warning" href="index.php?action=account_toggle_active&id=<?= (int)$u['id'] ?>">
                                                    <i class="fas fa-lock"></i> Khóa/Mở
                                                </a>
                                                <a class="btn btn-sm btn-danger" href="index.php?action=account_delete&id=<?= (int)$u['id'] ?>" onclick="return confirm('Xóa tài khoản này?');">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="5" class="text-center text-muted">Chưa có tài khoản nào.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/vendor/jquery/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/sb-admin-2.min.js"></script>
</body>
</html>
