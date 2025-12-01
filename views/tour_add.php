<?php
// Bắt đầu session nếu chưa có
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Hiển thị và xóa session lỗi
if (isset($_SESSION['error_tour_code'])): ?>
    <div class="alert alert-danger mb-4" role="alert">
        <?= $_SESSION['error_tour_code'] ?>
    </div>
<?php
    // Xóa session lỗi ngay sau khi hiển thị
    unset($_SESSION['error_tour_code']);
endif;

// Hiển thị và xóa session lỗi ảnh chính (nếu có)
if (isset($_SESSION['error_image'])): ?>
    <div class="alert alert-danger mb-4" role="alert">
        <?= $_SESSION['error_image'] ?>
    </div>
<?php
    unset($_SESSION['error_image']);
endif;


// Lấy dữ liệu cũ (nếu có) và Xóa sau khi lấy ra
$old_data = $_SESSION['old_data'] ?? [];
unset($_SESSION['old_data']);

/**
 * Hàm hỗ trợ lấy giá trị cũ từ $old_data, an toàn với HTML.
 * @param string $key Tên trường
 * @return string Giá trị cũ hoặc chuỗi rỗng
 */
$get_old = function ($key) use ($old_data) {
    // Sử dụng ?? '' để tránh lỗi nếu key không tồn tại
    return htmlspecialchars($old_data[$key] ?? '');
};

/**
 * Hàm hỗ trợ kiểm tra trạng thái 'checked' của radio button.
 * @param string $key Tên trường
 * @param string|int $value Giá trị cần kiểm tra
 * @return string 'checked' hoặc chuỗi rỗng
 */
$is_checked = function ($key, $value) use ($old_data) {
    // Nếu có old_data, kiểm tra xem nó có trùng với $value không.
    if (isset($old_data[$key])) {
        // Sử dụng === để đảm bảo so sánh đúng kiểu (int 0/1)
        return $old_data[$key] == $value ? 'checked' : '';
    }
    // Mặc định chọn Nội địa (value="0") nếu không có dữ liệu cũ
    return $value == '0' ? 'checked' : '';
};
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Thêm Tour Mới</title>
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

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

                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <ul class="navbar-nav ml-auto">

                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
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

                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                            </a>
                        </li>

                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                            </a>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                                <img class="img-profile rounded-circle"
                                    src="assets/img/undraw_profile.svg">
                            </a>
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
            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Quản lý Tour</h1>
                    <a href="?action=tours" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại Danh sách Tour
                    </a>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thêm Tour Mới</h6>
                    </div>
                    <div class="card-body">
                        <form action="?action=addTour" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="tour_code" class="form-label">Mã Tour (Tour Code):</label>
                                <input type="text" class="form-control" id="tour_code" name="tour_code"
                                    value="<?= $get_old('tour_code') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Tên Tour:</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="<?= $get_old('name') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="duration" class="form-label">Thời lượng (Ví dụ: 3 ngày 2 đêm):</label>
                                <input type="text" class="form-control" id="duration" name="duration"
                                    value="<?= $get_old('duration') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="base_price" class="form-label">Giá Cơ bản (VNĐ/Người):</label>
                                <input type="number" class="form-control" id="base_price" name="base_price"
                                    value="<?= $get_old('base_price') ?>" min="0" required>
                                <small class="form-text text-muted">Nhập giá trị số (VNĐ), không có dấu phẩy hay dấu chấm.</small>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Hình ảnh Chính (Ảnh đại diện):</label>
                                <input type="file" class="form-control" id="image" name="image" required>
                                <small class="form-text text-danger">Lưu ý: Ảnh chính là bắt buộc khi thêm mới.</small>
                            </div>
                            <div class="mb-3">
                                                                <label class="form-label">Thư viện ảnh (Gallery):</label>
                                                                <div id="gallery-container">
                                                                                                            <div class="mb-2 input-group gallery-item">
                                                                                <input type="file" class="form-control gallery-input" name="gallery_images[]" onchange="addGalleryInput(this)">
                                                                            </div>
                                                                    </div>
                                                                <small class="form-text text-muted">Chọn một ảnh để tự động thêm ô input mới.</small>
                                                                                                <button type="button" class="btn btn-sm btn-info mt-2" onclick="addGalleryInput()">
                                                                        <i class="fas fa-plus"></i> Thêm ảnh
                                                                    </button>
                                                            </div>
                            <div class="mb-3">
                                <label class="form-label d-block">Loại Tour:</label>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_international" id="tourNoiDia" value="0" <?= $is_checked('is_international', 0) ?>>
                                    <label class="form-check-label" for="tourNoiDia">Nội địa</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_international" id="tourQuocTe" value="1" <?= $is_checked('is_international', 1) ?>>
                                    <label class="form-check-label" for="tourQuocTe">Quốc tế</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Danh mục Tour:</label>
                                <select class="form-control" id="category_id" name="category_id">
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php
                                    // Lặp qua dữ liệu đã lấy từ CSDL (từ $categories được truyền từ Controller)
                                    foreach ($categories as $id => $name):
                                    ?>
                                        <option value="<?= $id ?>" <?= $get_old('category_id') == $id ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($name) ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="departure_point" class="form-label">Điểm khởi hành:</label>
                                <input type="text" class="form-control" id="departure_point" name="departure_point"
                                    value="<?= $get_old('departure_point') ?>" placeholder="VD: Hà Nội">
                            </div>

                            <div class="mb-3">
                                <label for="destination" class="form-label">Điểm đến:</label>
                                <input type="text" class="form-control" id="destination" name="destination"
                                    value="<?= $get_old('destination') ?>" placeholder="VD: Hạ Long" required>
                            </div>

                            <div class="mb-3">
                                <label for="departure_date" class="form-label">Ngày khởi hành:</label>
                                <input type="date" class="form-control" id="departure_date" name="departure_date"
                                    value="<?= $get_old('departure_date') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="price_child" class="form-label">Giá trẻ em (VNĐ):</label>
                                <input type="number" class="form-control" id="price_child" name="price_child"
                                    value="<?= $get_old('price_child') ?? '0' ?>" min="0">
                            </div>

                            <div class="mb-3">
                                <label for="price_infant" class="form-label">Giá em bé (VNĐ):</label>
                                <input type="number" class="form-control" id="price_infant" name="price_infant"
                                    value="<?= $get_old('price_infant') ?? '0' ?>" min="0">
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả Tour:</label>
                                <textarea class="form-control" id="description" name="description" rows="5"><?= $get_old('description') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="included_services" class="form-label">Dịch vụ bao gồm:</label>
                                <textarea class="form-control" id="included_services" name="included_services" rows="3"><?= $get_old('included_services') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="excluded_services" class="form-label">Dịch vụ không bao gồm:</label>
                                <textarea class="form-control" id="excluded_services" name="excluded_services" rows="3"><?= $get_old('excluded_services') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="policy_booking" class="form-label">Quy định đặt tour:</label>
                                <textarea class="form-control" id="policy_booking" name="policy_booking" rows="3"><?= $get_old('policy_booking') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="policy_cancellation" class="form-label">Chính sách hoàn hủy:</label>
                                <textarea class="form-control" id="policy_cancellation" name="policy_cancellation" rows="3"><?= $get_old('policy_cancellation') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="policy_refund" class="form-label">Chính sách hoàn tiền:</label>
                                <textarea class="form-control" id="policy_refund" name="policy_refund" rows="3"><?= $get_old('policy_refund') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Đối tác liên kết</label>
                                <div id="supplier_checkboxes" class="p-2 border rounded" style="max-height: 200px; overflow-y: auto;">
                                    <?php
                                    // Khởi tạo mảng các ID đã chọn, đảm bảo nó là một mảng ID số nguyên
                                    $selectedSupplierIds = array_map('intval', (array)($old_data['supplier_ids'] ?? []));
                                    ?>
                                    <?php foreach ($suppliers as $s): ?>
                                        <?php
                                        // Chuyển đổi ID đối tác hiện tại thành số nguyên
                                        $supplierId = (int)$s['id'];
                                        // Kiểm tra xem ID của đối tác có nằm trong mảng đã chọn không
                                        $isChecked = in_array($supplierId, $selectedSupplierIds) ? 'checked' : '';
                                        ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="supplier_ids[]" id="supplier_<?= htmlspecialchars($supplierId) ?>" value="<?= htmlspecialchars($supplierId) ?>" <?= $isChecked ?>>
                                            <label class="form-check-label" for="supplier_<?= htmlspecialchars($supplierId) ?>">
                                                <?= htmlspecialchars($s['name']) ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <small class="form-text text-muted">Chọn (hoặc bỏ chọn) các đối tác liên kết.</small>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="?action=tours" class="btn btn-secondary me-2">Hủy bỏ</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Thêm Tour
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

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
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <script src="assets/js/sb-admin-2.min.js"></script>

    <script src="assets/vendor/chart.js/Chart.min.js"></script>

    <script src="assets/js/demo/chart-area-demo.js"></script>
    <script src="assets/js/demo/chart-pie-demo.js"></script>

    <script>
        /**
         * Hàm thêm một input file mới vào gallery, và thêm nút xóa cho input file hiện tại.
         * @param {HTMLElement|null} currentInput - Input file vừa được chọn, hoặc null nếu gọi từ nút "Thêm ảnh".
         */
        function addGalleryInput(currentInput) {
            const container = document.getElementById('gallery-container');

            // 1. Xử lý input file hiện tại (nếu có)
            if (currentInput) {
                // Kiểm tra xem input này đã có file được chọn chưa
                if (currentInput.files.length > 0) {
                    // Vô hiệu hóa sự kiện onchange sau khi đã chọn file
                    currentInput.onchange = null;

                    // Thêm nút xóa vào input file vừa được chọn
                    const parentDiv = currentInput.closest('.gallery-item');
                    if (parentDiv && !parentDiv.querySelector('.remove-btn')) {
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'btn btn-danger remove-btn';
                        removeBtn.innerHTML = '<i class="fas fa-trash"></i>';
                        removeBtn.onclick = function() {
                            parentDiv.remove();
                        };

                        const appendDiv = document.createElement('div');
                        appendDiv.className = 'input-group-append';
                        appendDiv.appendChild(removeBtn);

                        parentDiv.appendChild(appendDiv);
                    }
                } else {
                    // Nếu gọi từ onchange nhưng không có file (người dùng nhấn cancel), không làm gì cả
                    return;
                }
            }

            // 2. Chỉ thêm input mới nếu input cuối cùng đã có file
            // Hoặc nếu được gọi từ nút "Thêm ảnh" thủ công
            const lastInput = container.querySelector('.gallery-input:last-child');

            // Nếu có input cuối cùng VÀ nó chưa có file được chọn VÀ hàm được gọi bởi sự kiện onchange của nó, thì KHÔNG thêm
            if (currentInput && currentInput === lastInput && currentInput.files.length === 0) {
                return;
            }

            // Nếu có input cuối cùng và nó RỖNG, KHÔNG thêm input mới
            if (lastInput && lastInput.files.length === 0) {
                return;
            }


            // 3. Tạo input file mới
            const newInputGroup = document.createElement('div');
            newInputGroup.className = 'mb-2 input-group gallery-item';

            newInputGroup.innerHTML = `
                <input type="file" class="form-control gallery-input" name="gallery_images[]" onchange="addGalleryInput(this)">
            `;

            // 4. Thêm input file mới vào container
            container.appendChild(newInputGroup);

            // 5. Tự động focus vào input mới
            newInputGroup.querySelector('input').focus();
        }
    </script>

</body>

</html>