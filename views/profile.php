<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Hồ Sơ Cá Nhân - H2A</title>
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php require_once 'views/includes/sidebar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php require_once 'views/includes/topbar.php'; ?>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Thông Tin Cá Nhân</h1>
                    </div>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php 
                            echo htmlspecialchars($_SESSION['success']); 
                            unset($_SESSION['success']);
                            ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php 
                            echo htmlspecialchars($_SESSION['error']); 
                            unset($_SESSION['error']);
                            ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['errors'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                <?php foreach ($_SESSION['errors'] as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php unset($_SESSION['errors']); ?>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Cập Nhật Thông Tin</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="?action=profile_update" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="username">Tên đăng nhập <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="username" name="username" 
                                                   value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="role">Vai trò</label>
                                            <input type="text" class="form-control" id="role" 
                                                   value="<?php echo htmlspecialchars($user['role_name'] ?? 'N/A'); ?>" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="avatar">Ảnh đại diện</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="avatar" name="avatar" 
                                                       accept="image/jpeg,image/png,image/gif,image/jpg">
                                                <label class="custom-file-label" for="avatar">Chọn ảnh...</label>
                                            </div>
                                            <small class="form-text text-muted">
                                                Định dạng: JPG, JPEG, PNG, GIF. Kích thước tối đa: 5MB
                                            </small>
                                        </div>

                                        <hr class="my-4">
                                        <h6 class="font-weight-bold text-primary mb-3">Đổi Mật Khẩu (Tùy chọn)</h6>

                                        <div class="form-group">
                                            <label for="current_password">Mật khẩu hiện tại</label>
                                            <input type="password" class="form-control" id="current_password" name="current_password">
                                            <small class="form-text text-muted">Chỉ cần nhập nếu bạn muốn đổi mật khẩu</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="new_password">Mật khẩu mới</label>
                                            <input type="password" class="form-control" id="new_password" name="new_password">
                                            <small class="form-text text-muted">Tối thiểu 6 ký tự</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="confirm_password">Xác nhận mật khẩu mới</label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                        </div>

                                        <div class="form-group mb-0">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Cập nhật
                                            </button>
                                            <a href="?" class="btn btn-secondary">
                                                <i class="fas fa-arrow-left"></i> Quay lại
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Ảnh Đại Diện</h6>
                                </div>
                                <div class="card-body text-center">
                                    <?php if (!empty($user['image']) && file_exists($user['image'])): ?>
                                        <img src="<?php echo htmlspecialchars($user['image']); ?>" 
                                             alt="Avatar" class="img-fluid rounded-circle mb-3" 
                                             style="width: 200px; height: 200px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                             style="width: 200px; height: 200px;">
                                            <i class="fas fa-user fa-5x text-gray-400"></i>
                                        </div>
                                    <?php endif; ?>
                                    <h5 class="font-weight-bold"><?php echo htmlspecialchars($user['username']); ?></h5>
                                    <p class="text-muted mb-0"><?php echo htmlspecialchars($user['role_name'] ?? 'N/A'); ?></p>
                                </div>
                            </div>

                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Thông Tin Tài Khoản</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <small class="text-muted">Trạng thái</small>
                                        <p class="mb-0">
                                            <?php if ($user['is_active']): ?>
                                                <span class="badge badge-success">Hoạt động</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Bị khóa</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Ngày tạo</small>
                                        <p class="mb-0"><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/sb-admin-2.min.js"></script>
    <script>
        // Update file input label with selected filename
        document.querySelector('.custom-file-input').addEventListener('change', function(e) {
            var fileName = e.target.files[0]?.name || 'Chọn ảnh...';
            var label = e.target.nextElementSibling;
            label.textContent = fileName;
        });
    </script>
</body>
</html>
