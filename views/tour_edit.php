<?php
// Đây là phần xử lý logic/khởi tạo dữ liệu tour trước khi render form
// Giả định file này nằm trong thư mục views/admin (hoặc tương tự)
// và đã được Controller gọi với các biến $tour, $categories, $galleryImages được gán.

// Bắt đầu hoặc tiếp tục session nếu chưa có
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- Khởi tạo biến để tránh lỗi Undefined Variable nếu Controller chưa gán ---
// $tour: Dữ liệu của tour đang chỉnh sửa (từ DB)
// $categories: Danh sách danh mục (từ DB)
// $galleryImages: Danh sách ảnh gallery hiện có của tour (từ DB)
$tour = $tour ?? [];
$categories = $categories ?? [];
$galleryImages = $galleryImages ?? [];

// Lấy dữ liệu cũ từ session nếu có lỗi validation
$old_data = $_SESSION['old_data'] ?? [];

// Hàm helper để lấy giá trị đã được escape (ưu tiên old_data > tour_data > default)
function e(array $tour_data, string $key, $default = '')
{
    global $old_data; // Sử dụng biến global $old_data
    // Kiểm tra $old_data trước, sau đó là $tour_data, nếu không có thì dùng $default
    $value = $old_data[$key] ?? $tour_data[$key] ?? $default;
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Xóa old_data sau khi đã áp dụng cho form (chỉ dùng 1 lần)
if (isset($_SESSION['old_data'])) {
    unset($_SESSION['old_data']);
}

// Xử lý thông báo lỗi (giữ nguyên)
$error_tour_code = null;
if (isset($_SESSION['error_tour_code'])) {
    $error_tour_code = $_SESSION['error_tour_code'];
    unset($_SESSION['error_tour_code']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sửa Tour - Admin</title>

    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .form-section-title {
            border-bottom: 2px solid #4e73df;
            padding-bottom: 5px;
            margin-bottom: 20px;
            margin-top: 20px;
            color: #4e73df;
            font-weight: bold;
        }
        .gallery-image-wrapper {
            position: relative;
            display: inline-block;
            margin-right: 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            padding: 5px;
            transition: opacity 0.3s, filter 0.3s;
        }
        .gallery-image-wrapper img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
        .delete-image-btn {
            position: absolute;
            top: -5px;
            right: -5px;
            cursor: pointer;
            z-index: 10;
            color: white;
            background-color: #e74a3b;
            border-radius: 50%;
            padding: 2px 5px;
            font-size: 0.8rem;
            line-height: 1;
            border: 2px solid white;
            transition: background-color 0.3s;
        }

        .image-deleted {
            opacity: 0.3;
            pointer-events: none;
            /* Ngăn chặn click vào ảnh đã xóa */
            filter: grayscale(100%);
        }

        .image-deleted .delete-image-btn {
            background-color: #1cc88a;
            /* Đổi màu nút khi hoàn tác */
        }
    </style>
</head>

<body id="page-top">

    <div id="wrapper">

        <?php
        // Giả định các file này tồn tại
        // include 'views/includes/sidebar.php'; 
        // include 'views/includes/topbar.php'; 
        ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Quản lý Tour</h1>
                    </div>

                    <?php if (isset($error_tour_code)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error_tour_code) ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Chỉnh sửa Tour: <?= e($tour, 'tour_code') ?></h6>
                        </div>
                        <div class="card-body">

                            <form action="?action=updateTour" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?= e($tour, 'id') ?>">
                                <input type="hidden" name="current_image" value="<?= e($tour, 'image') ?>">

                                <h5 class="form-section-title"><i class="fas fa-info-circle"></i> Thông tin cơ bản</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tour_code" class="form-label font-weight-bold">Mã Tour <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="tour_code" name="tour_code" value="<?= e($tour, 'tour_code') ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label font-weight-bold">Tên Tour <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name" value="<?= e($tour, 'name') ?>" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="duration" class="form-label">Thời gian (VD: 3N2Đ)</label>
                                            <input type="text" class="form-control" id="duration" name="duration" value="<?= e($tour, 'duration') ?>" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Danh mục Tour:</label>
                                            <select class="form-control" id="category_id" name="category_id">
                                                <option value="">-- Chọn danh mục --</option>
                                                <?php $current_category_id = e($tour, 'category_id'); ?>
                                                <?php foreach ($categories as $id => $name): ?>
                                                    <option value="<?= $id ?>" <?= ($id == $current_category_id) ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($name) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="departure_point" class="form-label">Điểm khởi hành</label>
                                            <input type="text" class="form-control" id="departure_point" name="departure_point" value="<?= e($tour, 'departure_point') ?>" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="destination" class="form-label">Điểm đến</label>
                                            <input type="text" class="form-control" id="destination" name="destination" value="<?= e($tour, 'destination') ?>" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="departure_date" class="form-label">Ngày khởi hành</label>
                                            <input type="date" class="form-control" id="departure_date" name="departure_date" value="<?= e($tour, 'departure_date') ?>" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label d-block font-weight-bold">Loại Tour</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="is_international" id="tourNoiDia" value="0" <?= (e($tour, 'is_international', 0) == 0) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="tourNoiDia">Trong nước</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="is_international" id="tourQuocTe" value="1" <?= (e($tour, 'is_international', 0) == 1) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="tourQuocTe">Quốc tế</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label font-weight-bold">Mô tả ngắn</label>
                                            <textarea class="form-control" id="description" name="description" rows="3" required><?= e($tour, 'description') ?></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label font-weight-bold">Hình ảnh đại diện</label>
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($tour['image'])): ?>
                                                    <div class="mr-3 text-center">
                                                        <img src="<?= e($tour, 'image') ?>" alt="Ảnh cũ" class="img-thumbnail" style="width: 150px; height: 100px; object-fit: cover;">
                                                        <div class="small text-muted mt-1">Ảnh hiện tại</div>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="flex-grow-1">
                                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                                    <small class="text-muted">Chọn ảnh mới nếu muốn thay đổi.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <h5 class="form-section-title"><i class="fas fa-images"></i> Bộ sưu tập hình ảnh</h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label font-weight-bold mb-2">Ảnh hiện có:</label>
                                        <div id="currentGalleryImages" class="d-flex flex-wrap mb-3">
                                            <?php foreach ($galleryImages as $image):
                                                $image_id = $image['id'] ?? null;
                                                $image_path = $image['image_path'] ?? '';
                                                if ($image_id && $image_path):
                                            ?>
                                                    <div class="gallery-image-wrapper" data-image-id="<?= $image_id ?>">
                                                        <img src="<?= htmlspecialchars($image_path) ?>" alt="Gallery Image" class="img-thumbnail">
                                                        <span class="delete-image-btn" onclick="markImageForDelete(<?= $image_id ?>, this)">
                                                            <i class="fas fa-times"></i>
                                                        </span>
                                                        <!-- Hidden input để track ảnh cần xóa -->
                                                        <input type="hidden" name="delete_gallery_images[<?= $image_id ?>]" value="0" class="delete-flag-input">
                                                    </div>
                                            <?php endif;
                                            endforeach; ?>
                                        </div>

                                        <label class="form-label font-weight-bold mb-2">Thêm ảnh mới:</label>
                                        <div id="newGalleryInputs">
                                            <div class="input-group mb-2 new-image-input-group">
                                                <input type="file" class="form-control" name="new_gallery_images[]" accept="image/*">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="button" onclick="addGalleryInput(this)">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <small class="text-muted">Bạn có thể thêm nhiều ảnh bằng cách nhấn nút **"+"**.</small>
                                    </div>
                                </div>
                                <h5 class="form-section-title"><i class="fas fa-dollar-sign"></i> Cấu hình Giá</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="base_price" class="form-label font-weight-bold">Giá Người lớn</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="base_price" name="base_price" value="<?= e($tour, 'base_price', 0) ?>" required min="0">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">VNĐ</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="price_child" class="form-label font-weight-bold">Giá Trẻ em (5-11t)</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="price_child" name="price_child" value="<?= e($tour, 'price_child', 0) ?>" min="0">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">VNĐ</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="price_infant" class="form-label font-weight-bold">Giá Em bé (dưới 2t) </label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="price_infant" name="price_infant" value="<?= e($tour, 'price_infant', 0) ?>" min="0">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">VNĐ</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="form-section-title"><i class="fas fa-file-contract"></i> Chính sách & Dịch vụ</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label font-weight-bold">Dịch vụ bao gồm</label>
                                            <textarea class="form-control" name="included_services" rows="4" placeholder="- Xe đưa đón&#10;- Khách sạn 4 sao..."><?= e($tour, 'included_services') ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label font-weight-bold">Dịch vụ không bao gồm</label>
                                            <textarea class="form-control" name="excluded_services" rows="4" placeholder="- Chi phí cá nhân&#10;- VAT..."><?= e($tour, 'excluded_services') ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label font-weight-bold">Quy định đặt tour</label>
                                            <textarea class="form-control" name="policy_booking" rows="4"><?= e($tour, 'policy_booking') ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label font-weight-bold">Chính sách hoàn hủy</label>
                                            <textarea class="form-control" name="policy_cancellation" rows="4"><?= e($tour, 'policy_cancellation') ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label font-weight-bold">Chính sách hoàn tiền</label>
                                            <textarea class="form-control" name="policy_refund" rows="4"><?= e($tour, 'policy_refund') ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                $suppliers = $suppliers ?? [];
                                $tourSuppliers = $tourSuppliers ?? [];
                                $linkedSupplierIds = array_map('intval', array_column($tourSuppliers, 'id'));
                                ?>
                                <div class="form-group">
                                    <label>Đối tác liên kết</label>
                                    <div id="supplier_checkboxes" class="form-control" style="height: auto; min-height: 38px;">
                                        <?php foreach ($suppliers as $s): ?>
                                            <?php
                                            $isChecked = in_array((int)$s['id'], $linkedSupplierIds) ? 'checked' : '';
                                            ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="supplier_ids[]" id="supplier_<?= htmlspecialchars($s['id']) ?>" value="<?= htmlspecialchars($s['id']) ?>" <?= $isChecked ?>>
                                                <label class="form-check-label" for="supplier_<?= htmlspecialchars($s['id']) ?>">
                                                    <?= htmlspecialchars($s['name']) ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <small class="form-text text-muted">Chọn (hoặc bỏ chọn) các đối tác liên kết.</small>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <a href="?action=tours" class="btn btn-secondary mr-2">
                                        <i class="fas fa-arrow-left"></i> Quay lại
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save"></i> Cập nhật Tour
                                    </button>
                                </div>

                            </form>
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

    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="assets/js/sb-admin-2.min.js"></script>

    <script>
        /**
         * Đánh dấu ảnh để xóa (toggle)
         */
        function markImageForDelete(imageId, btn) {
            const wrapper = btn.closest('.gallery-image-wrapper');
            const flagInput = wrapper.querySelector('.delete-flag-input');

            if (wrapper.classList.contains('image-deleted')) {
                // HOÀN TẠC: không xóa
                wrapper.classList.remove('image-deleted');
                flagInput.value = '0';
            } else {
                // ĐÁNH DẤU XÓA
                wrapper.classList.add('image-deleted');
                flagInput.value = '1';
            }
        }

        /**
         * Thêm một input file mới cho gallery
         */
        function addGalleryInput(btn) {
            const container = document.getElementById('newGalleryInputs');
            const newGroup = document.createElement('div');
            newGroup.className = 'input-group mb-2 new-image-input-group';

            const newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.className = 'form-control';
            newInput.name = 'new_gallery_images[]';
            newInput.accept = 'image/*';
            const newAddBtn = document.createElement('button');
            newAddBtn.className = 'btn btn-primary';
            newAddBtn.type = 'button';
            newAddBtn.setAttribute('onclick', 'addGalleryInput(this)');
            newAddBtn.innerHTML = '<i class="fas fa-plus"></i>';

            const newAppendDiv = document.createElement('div');
            newAppendDiv.className = 'input-group-append';
            newAppendDiv.appendChild(newAddBtn);

            newGroup.appendChild(newInput);
            newGroup.appendChild(newAppendDiv);

            // Sửa nút cũ thành nút xóa
            const oldGroup = btn.closest('.input-group');
            const oldAppend = btn.parentElement;

            btn.innerHTML = '<i class="fas fa-minus"></i>';
            btn.className = 'btn btn-danger';
            btn.setAttribute('onclick', 'removeGalleryInput(this)');

            oldGroup.querySelector('input[type="file"]').required = false;
            container.appendChild(newGroup);
        }

        /**
         * Xóa input file khỏi form
         */
        function removeGalleryInput(btn) {
            const inputGroup = btn.closest('.input-group');
            const container = document.getElementById('newGalleryInputs');

            if (container.querySelectorAll('.new-image-input-group').length === 1) {
                const fileInput = inputGroup.querySelector('input[type="file"]');
                fileInput.value = '';
                btn.innerHTML = '<i class="fas fa-plus"></i>';
                btn.className = 'btn btn-primary';
                btn.setAttribute('onclick', 'addGalleryInput(this)');
                return;
            }

            inputGroup.remove();
        }
    </script>

</body>

</html>