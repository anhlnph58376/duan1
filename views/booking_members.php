<?php
// Flash messages will be rendered inside the page container for consistent layout
$flashError = $_SESSION['error'] ?? null;
if ($flashError) unset($_SESSION['error']);
$flashSuccess = $_SESSION['success'] ?? null;
if ($flashSuccess) unset($_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thành viên booking</title>
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include __DIR__ . '/includes/sidebar.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include __DIR__ . '/includes/topbar.php'; ?>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Thành viên của booking: <?= htmlspecialchars($booking['booking_code'] ?? '') ?></h1>
                        <div>
                            <a href="?action=booking_member_add&booking_id=<?= $booking['id'] ?>" class="btn btn-primary">Thêm thành viên</a>
                            <a href="?action=bookings" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </div>

                    <?php if ($flashError): ?>
                        <?php $short = preg_replace('/^Thêm thành viên thất bại:\s*/iu', '', $flashError); ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Lỗi:</strong> <?= htmlspecialchars($short) ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="mt-2">
                                <a data-toggle="collapse" href="#errorDetail" role="button" aria-expanded="false" aria-controls="errorDetail">Xem chi tiết lỗi</a>
                                <div class="collapse mt-2" id="errorDetail">
                                    <pre class="small bg-light p-3" style="white-space:pre-wrap;border-radius:.25rem;"><?= htmlspecialchars($flashError) ?></pre>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($flashSuccess): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($flashSuccess) ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <p>Khách: <?= htmlspecialchars($booking['customer_name'] ?? '') ?></p>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Họ tên</th>
                                            <th>Tuổi</th>
                                            <th>Giới tính</th>
                                            <th>Số hộ chiếu</th>
                                            <th>Thanh toán</th>
                                            <th>Check-in</th>
                                            <!-- <th>Phòng</th> -->
                                            <th>Ghi chú</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($members)): ?>
                                            <?php foreach ($members as $m): ?>
                                                <tr>
                                                    <td><?= $m['id'] ?></td>
                                                    <td><?= htmlspecialchars($m['full_name']) ?></td>
                                                    <td><?= htmlspecialchars($m['age']) ?></td>
                                                    <td><?= htmlspecialchars($m['gender']) ?></td>
                                                    <td><?= htmlspecialchars($m['passport_number']) ?></td>
                                                    <td>
                                                        <div><?= htmlspecialchars($m['payment_status'] ?? 'unpaid') ?></div>
                                                        <div class="small-muted"><?= number_format($m['payment_amount'] ?? 0,0,',','.') ?> VNĐ</div>
                                                    </td>
                                                    <td><?= htmlspecialchars($m['checkin_status'] ?? 'not_arrived') ?></td>
                                                    <!-- <td><?= htmlspecialchars($m['room_assignment'] ?? '') ?></td> -->
                                                    <td><?= htmlspecialchars($m['note']) ?><?php if (!empty($m['special_request'])): ?><div class="small-muted">Yêu cầu: <?= htmlspecialchars($m['special_request']) ?></div><?php endif; ?></td>
                                                    <td>
                                                        <a href="?action=booking_member_edit&id=<?= $m['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
                                                        <a href="?action=booking_member_delete&id=<?= $m['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xác nhận xóa?')">Xóa</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="7">Chưa có thành viên nào.</td></tr>
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
