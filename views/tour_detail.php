<?php
// Bắt đầu session nếu chưa có (nên đặt ở đầu file nếu chưa có sẵn)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Hàm giả định để lấy Tên Danh mục từ ID (bạn cần triển khai logic này)
function getCategoryNameById($categoryId)
{
    // Trong môi trường thực tế, bạn sẽ truy vấn bảng tour_categories tại đây
    $categories = [
        1 => 'Du lịch Biển',
        2 => 'Du lịch Núi',
        3 => 'Tour thiết kế riêng (Custom)',
        4 => 'Thăm quan di tích',
        // Thêm các ID và Tên khác nếu cần
    ];
    return $categories[$categoryId] ?? 'Chưa phân loại (ID: ' . $categoryId . ')';
}

// Hiển thị và xóa session lỗi (thường dùng cho các form POST như Add/Edit)
if (isset($_SESSION['error_tour_code'])): ?>
    <div class="alert alert-danger mb-4" role="alert">
        <?= $_SESSION['error_tour_code'] ?>
    </div>
<?php
    unset($_SESSION['error_tour_code']);
endif;

// Lấy dữ liệu cũ (nếu có)
$old_data = $_SESSION['old_data'] ?? [];
unset($_SESSION['old_data']); // Xóa sau khi lấy ra

// PHẢI đảm bảo biến $tour được truyền từ controller
// Giả định biến $tour chứa dữ liệu chi tiết tour
if (!isset($tour)) {
    // Xử lý lỗi nếu không tìm thấy dữ liệu tour (sử dụng HTML đơn giản để tránh lỗi cú pháp)
    echo '<!DOCTYPE html><title>Lỗi</title><div class="container-fluid mt-5"><div class="alert alert-danger">Không tìm thấy thông tin tour.</div><a href="?action=tours" class="btn btn-primary">Quay lại danh sách</a></div>';
    return;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Chi tiết Tour: <?= htmlspecialchars($tour['name']) ?></title>

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

                <?php require_once 'views/includes/topbar.php'; ?>

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
                                <img class="img-profile rounded-circle" src="assets/img/undraw_profile.svg">
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
                    <h1 class="h3 mb-0 text-gray-800">Chi tiết Tour</h1>
                    <a href="?action=tours" class="btn btn-sm btn-secondary shadow-sm">
                        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại Danh sách Tour
                    </a>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tour: <?= htmlspecialchars($tour['name']) ?></h6>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-lg-4 mb-4">
                                <h5 class="text-primary mb-3">Hình ảnh Tour</h5>
                                <?php if (!empty($tour['image'])): ?>
                                    <img src="<?= htmlspecialchars($tour['image']) ?>"
                                        alt="<?= htmlspecialchars($tour['name']) ?>"
                                        class="img-fluid rounded shadow-sm"
                                        style="max-height: 400px; width: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <div class="border p-4 text-center text-muted rounded">Không có hình ảnh
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-lg-8">
                                <h5 class="text-primary mb-3"><i class="fas fa-info-circle"></i> Thông tin Cơ bản</h5>
                                <ul class="list-group list-group-flush mb-4">
                                    <li class="list-group-item">
                                        <strong>Mã Tour:</strong>
                                        <span
                                            class="badge bg-info text-dark"><?= htmlspecialchars($tour['tour_code'] ?? 'N/A') ?></span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Tên Tour:</strong>
                                        <?= htmlspecialchars($tour['name'] ?? 'N/A') ?>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Danh mục Tour:</strong>
                                        <span class="badge bg-secondary text-white">
                                            <?= htmlspecialchars(getCategoryNameById($tour['category_id'] ?? 0)) ?>
                                        </span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Loại Tour:</strong>
                                        <span
                                            class="badge <?= ($tour['is_international'] ?? 0) == 1 ? 'bg-warning text-dark' : 'bg-success text-white' ?>">
                                            <?= ($tour['is_international'] ?? 0) == 1 ? 'Quốc tế' : 'Nội địa' ?>
                                        </span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Thời lượng:</strong>
                                        <?= htmlspecialchars($tour['duration'] ?? 'N/A') ?>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Điểm khởi hành:</strong>
                                        <?= htmlspecialchars($tour['departure_point'] ?? 'N/A') ?>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Điểm đến:</strong>
                                        <?= htmlspecialchars($tour['destination'] ?? 'N/A') ?>
                                    </li>
                                </ul>

                                <h5 class="text-primary mt-4 mb-3"><i class="fas fa-handshake"></i> Đối tác liên kết</h5>
                                <?php if (!empty($tourSuppliers) && is_array($tourSuppliers)): ?>
                                    <div class="list-group mb-4">
                                        <?php foreach ($tourSuppliers as $supplier): ?>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong><?= htmlspecialchars($supplier['name'] ?? 'Không tên') ?></strong>
                                                    <?php if (!empty($supplier['contact_person'])): ?>
                                                        <div class="small text-muted">Người liên hệ: <?= htmlspecialchars($supplier['contact_person']) ?></div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($supplier['phone'])): ?>
                                                        <div class="small text-muted">SĐT: <?= htmlspecialchars($supplier['phone']) ?></div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="text-right">
                                                    <a href="?action=supplier_edit&id=<?= htmlspecialchars($supplier['id']) ?>" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-info mb-4">Tour này chưa liên kết với đối tác nào.</div>
                                <?php endif; ?>

                                <h5 class="text-primary mt-4 mb-3"><i class="fas fa-dollar-sign"></i> Thông tin Giá</h5>
                                <ul class="list-group list-group-flush mb-4">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <strong>Giá Cơ bản (Người lớn):</strong>
                                        <span class="text-danger fw-bold fs-5">
                                            <?= number_format($tour['base_price'] ?? 0, 0, ',', '.') ?> VNĐ
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <strong>Giá Trẻ em (5-11 tuổi):</strong>
                                        <span class="text-success fw-bold">
                                            <?= number_format($tour['price_child'] ?? 0, 0, ',', '.') ?> VNĐ
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <strong>Giá Em bé (< 5 tuổi):</strong>
                                                <span class="text-info fw-bold">
                                                    <?= number_format($tour['price_infant'] ?? 0, 0, ',', '.') ?> VNĐ
                                                </span>
                                    </li>
                                </ul>

                                <h5 class="text-primary mt-4 mb-3"><i class="fas fa-file-alt"></i> Mô tả Chi tiết</h5>
                                <div class="p-3 bg-light rounded border">
                                    <?= nl2br(htmlspecialchars($tour['description'] ?? 'Không có mô tả.')) ?>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="text-primary mb-3"><i class="fas fa-shield-alt"></i> Thông tin Chính sách & Dịch vụ</h5>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="policy-booking-tab" data-toggle="tab" href="#policy-booking" role="tab" aria-controls="policy-booking" aria-selected="true">Chính sách Đặt tour</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="policy-cancellation-tab" data-toggle="tab" href="#policy-cancellation" role="tab" aria-controls="policy-cancellation" aria-selected="false">Chính sách Hủy tour</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="policy-refund-tab" data-toggle="tab" href="#policy-refund" role="tab" aria-controls="policy-refund" aria-selected="false">Chính sách Hoàn tiền</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="included-services-tab" data-toggle="tab" href="#included-services" role="tab" aria-controls="included-services" aria-selected="false">Dịch vụ Bao gồm</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="excluded-services-tab" data-toggle="tab" href="#excluded-services" role="tab" aria-controls="excluded-services" aria-selected="false">Dịch vụ Không bao gồm</a>
                            </li>
                        </ul>
                        <div class="tab-content border border-top-0 p-3 bg-light rounded-bottom" id="myTabContent">
                            <div class="tab-pane fade show active" id="policy-booking" role="tabpanel" aria-labelledby="policy-booking-tab">
                                <?= nl2br(htmlspecialchars($tour['policy_booking'] ?? 'Chưa có chính sách đặt tour.')) ?>
                            </div>
                            <div class="tab-pane fade" id="policy-cancellation" role="tabpanel" aria-labelledby="policy-cancellation-tab">
                                <?= nl2br(htmlspecialchars($tour['policy_cancellation'] ?? 'Chưa có chính sách hủy tour.')) ?>
                            </div>
                            <div class="tab-pane fade" id="policy-refund" role="tabpanel" aria-labelledby="policy-refund-tab">
                                <?= nl2br(htmlspecialchars($tour['policy_refund'] ?? 'Chưa có chính sách hoàn tiền.')) ?>
                            </div>
                            <div class="tab-pane fade" id="included-services" role="tabpanel" aria-labelledby="included-services-tab">
                                <?= nl2br(htmlspecialchars($tour['included_services'] ?? 'Không có thông tin dịch vụ bao gồm.')) ?>
                            </div>
                            <div class="tab-pane fade" id="excluded-services" role="tabpanel" aria-labelledby="excluded-services-tab">
                                <?= nl2br(htmlspecialchars($tour['excluded_services'] ?? 'Không có thông tin dịch vụ không bao gồm.')) ?>
                            </div>
                        </div>
                        <hr class="my-4">
                                               
                                                <h5 class="text-primary mb-3"><i class="fas fa-images"></i> Thư viện Ảnh Tour (<?= count($galleryImages ?? []) ?> ảnh)</h5>

                                                <?php if (!empty($galleryImages)):
                                                    // Gán một ID duy nhất cho Carousel
                                                    $carouselId = 'tourGalleryCarousel' . htmlspecialchars($tour['id']);
                                                ?>

                                                    <div id="<?= $carouselId ?>" class="carousel slide bg-dark p-2 rounded" data-ride="carousel">

                                <ol class="carousel-indicators">
                                    <?php foreach ($galleryImages as $index => $image): ?>
                                        <li data-target="#<?= $carouselId ?>" data-slide-to="<?= $index ?>"
                                            class="<?= $index === 0 ? 'active' : '' ?>"></li>
                                    <?php endforeach; ?>
                                </ol>

                                <div class="carousel-inner">
                                    <?php foreach ($galleryImages as $index => $image): ?>
                                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                            <img src="<?= htmlspecialchars($image['image_path']) ?>"
                                                class="d-block w-100"
                                                alt="Gallery Image <?= $index + 1 ?>"
                                                style="height: 400px; object-fit: contain;">
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <a class="carousel-control-prev" href="#<?= $carouselId ?>" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#<?= $carouselId ?>" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>

                                    <?php else: ?>
                                        <div class="alert alert-info text-center">Tour này chưa có ảnh thư viện.</div>
                                    <?php endif; ?>
                        <div class="d-flex justify-content-end">
                            <a href="?action=tour_edit&id=<?= htmlspecialchars($tour['id'] ?? '') ?>" class="btn btn-primary mx-2">
                                <i class="fas fa-edit"></i> Chỉnh sửa Tour
                            </a>
                            <a href="?action=tour_delete&id=<?= htmlspecialchars($tour['id'] ?? '') ?>" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Xóa Tour
                            </a>
                        </div>
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

</body>

</html>