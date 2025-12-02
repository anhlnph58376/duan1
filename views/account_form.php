<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= isset($user) ? 'Sửa Tài Khoản' : 'Đăng Ký Tài Khoản' ?></title>
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'views/includes/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php require_once 'views/includes/topbar.php'; ?>
            <div class="container-fluid">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><?= isset($user) ? 'Sửa Tài Khoản' : 'Đăng Ký Tài Khoản Mới' ?></h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="index.php?action=<?= isset($user) ? 'account_update' : 'account_store' ?>">
                            <?php if (isset($user)): ?>
                                <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
                            <?php endif; ?>
                            <div class="form-group">
                                <label>Tên đăng nhập</label>
                                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Mật khẩu <?= isset($user) ? '(để trống nếu giữ nguyên)' : '' ?></label>
                                <input type="password" name="password" class="form-control" <?= isset($user) ? '' : 'required' ?>>
                            </div>
                            <div class="form-group">
                                <label>Vai trò</label>
                                <select name="role_id" class="form-control">
                                    <option value="1" <?= isset($user) && (int)$user['role_id'] === 1 ? 'selected' : '' ?>>Admin</option>
                                    <option value="2" <?= isset($user) && (int)$user['role_id'] === 2 ? 'selected' : '' ?>>HDV</option>
                                </select>
                            </div>
                            <?php if (isset($user)): ?>
                            <div class="form-group">
                                <label>Trạng thái</label>
                                <select name="is_active" class="form-control">
                                    <option value="1" <?= ($user['is_active'] ?? 1) ? 'selected' : '' ?>>Đang hoạt động</option>
                                    <option value="0" <?= ($user['is_active'] ?? 1) ? '' : 'selected' ?>>Đã khóa</option>
                                </select>
                            </div>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
                            <a href="index.php?action=account_management" class="btn btn-secondary">Hủy</a>
                        </form>
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
