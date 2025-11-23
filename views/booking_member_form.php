<?php
// expects $booking and optionally $member
$editing = !empty($member);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $editing ? 'Sửa thành viên' : 'Thêm thành viên'; ?> — Booking <?= htmlspecialchars($booking['booking_code'] ?? '') ?></title>
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .form-required::after { content: " *"; color: #e74a3b; }
        .small-muted { font-size: 0.85rem; color: #6c757d; }
        .note-count { font-size: 0.8rem; color: #6c757d; }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include __DIR__ . '/includes/sidebar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include __DIR__ . '/includes/topbar.php'; ?>
                <div class="container-fluid">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item"><a href="?action=bookings">Bookings</a></li>
                            <li class="breadcrumb-item"><a href="?action=booking_detail&id=<?php echo $booking['id']; ?>"><?= htmlspecialchars($booking['booking_code'] ?? '') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $editing ? 'Sửa thành viên' : 'Thêm thành viên'; ?></li>
                        </ol>
                    </nav>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary"><?php echo $editing ? 'Sửa thành viên' : 'Thêm thành viên'; ?></h6>
                            <div class="small-muted">Booking: <strong><?= htmlspecialchars($booking['booking_code'] ?? '') ?></strong></div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="?action=<?php echo $editing ? 'booking_member_update' : 'booking_member_store'; ?>" novalidate>
                                <?php if ($editing): ?>
                                    <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                                <?php endif; ?>
                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                <?php if (!empty($departure_id)): ?>
                                    <input type="hidden" name="departure_id" value="<?= htmlspecialchars($departure_id) ?>">
                                <?php endif; ?>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="form-required">Họ và tên</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-user"></i></div></div>
                                            <input type="text" name="full_name" class="form-control" placeholder="Nguyễn Văn A" value="<?php echo $editing ? htmlspecialchars($member['full_name']) : ''; ?>" required aria-label="Họ tên">
                                        </div>
                                        <div class="invalid-feedback">Vui lòng nhập họ tên.</div>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Tuổi</label>
                                        <input type="number" name="age" min="0" max="120" class="form-control" placeholder="Tuổi" value="<?php echo $editing ? htmlspecialchars($member['age']) : ''; ?>">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label class="form-required">Giới tính</label>
                                        <select name="gender" class="form-control" required>
                                            <option value="">Chọn...</option>
                                            <option value="Male" <?php echo ($editing && ($member['gender'] ?? '')=='Male') ? 'selected' : ''; ?>>Nam</option>
                                            <option value="Female" <?php echo ($editing && ($member['gender'] ?? '')=='Female') ? 'selected' : ''; ?>>Nữ</option>
                                            <option value="Other" <?php echo ($editing && ($member['gender'] ?? '')=='Other') ? 'selected' : ''; ?>>Khác</option>
                                        </select>
                                        <div class="invalid-feedback">Vui lòng chọn giới tính.</div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Số hộ chiếu / CMND</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-id-card"></i></div></div>
                                            <input type="text" name="passport_number" class="form-control" placeholder="A01234567 / 012345678" value="<?php echo $editing ? htmlspecialchars($member['passport_number']) : ''; ?>">
                                        </div>
                                        <small class="form-text text-muted">Nhập nếu có — phục vụ thủ tục bay/giấy tờ.</small>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Vai trò / Nhóm (tuỳ chọn)</label>
                                        <input type="text" name="role" class="form-control" placeholder="Ví dụ: Trưởng đoàn, Thành viên" value="<?php echo $editing ? htmlspecialchars($member['role'] ?? '') : ''; ?>">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>Trạng thái thanh toán</label>
                                        <select name="payment_status" class="form-control">
                                            <option value="unpaid" <?php echo ($editing && ($member['payment_status'] ?? '')=='unpaid')? 'selected':''; ?>>Chưa thanh toán</option>
                                            <option value="partial" <?php echo ($editing && ($member['payment_status'] ?? '')=='partial')? 'selected':''; ?>>Thanh toán 1 phần</option>
                                            <option value="paid" <?php echo ($editing && ($member['payment_status'] ?? '')=='paid')? 'selected':''; ?>>Đã thanh toán</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Số tiền đã thanh toán (VNĐ)</label>
                                        <input type="number" name="payment_amount" class="form-control" min="0" step="0.01" value="<?php echo $editing ? htmlspecialchars($member['payment_amount'] ?? '0') : '0'; ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Trạng thái check-in</label>
                                        <select name="checkin_status" class="form-control">
                                            <option value="not_arrived" <?php echo ($editing && ($member['checkin_status'] ?? '')=='not_arrived')? 'selected':''; ?>>Chưa đến</option>
                                            <option value="arrived" <?php echo ($editing && ($member['checkin_status'] ?? '')=='arrived')? 'selected':''; ?>>Đã đến</option>
                                            <option value="absent" <?php echo ($editing && ($member['checkin_status'] ?? '')=='absent')? 'selected':''; ?>>Vắng mặt</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label>Yêu cầu đặc biệt</label>
                                        <input type="text" name="special_request" class="form-control" placeholder="Ăn kiêng, giường đơn..." value="<?php echo $editing ? htmlspecialchars($member['special_request'] ?? '') : ''; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Ghi chú <span class="small-muted">(tùy chọn)</span></label>
                                    <textarea name="note" class="form-control" rows="4" maxlength="1000" placeholder="Ghi chú sức khỏe, yêu cầu đặc biệt..." id="noteField"><?php echo $editing ? htmlspecialchars($member['note']) : ''; ?></textarea>
                                    <div class="d-flex justify-content-between mt-1">
                                        <small class="note-count" id="noteCount">0 / 1000</small>
                                        <small class="small-muted">Bạn có thể dùng tiếng Việt có dấu.</small>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <?php if (!empty($departure_id)): ?>
                                        <a href="?action=departure_detail&id=<?php echo $departure_id; ?>" class="btn btn-light mr-2">Hủy</a>
                                    <?php else: ?>
                                        <a href="?action=booking_members&booking_id=<?php echo $booking['id']; ?>" class="btn btn-light mr-2">Hủy</a>
                                    <?php endif; ?>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <?php if ($editing): ?>
                                            <i class="fas fa-save mr-1"></i> Cập nhật
                                        <?php else: ?>
                                            <i class="fas fa-plus mr-1"></i> Thêm thành viên
                                        <?php endif; ?>
                                    </button>
                                </div>
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
    <script>
        // Client-side enhancements: note counter, basic Bootstrap validation, prevent double-submit
        (function() {
            'use strict';
            var form = document.querySelector('form');
            var submitBtn = document.getElementById('submitBtn');
            var noteField = document.getElementById('noteField');
            var noteCount = document.getElementById('noteCount');

            function updateNoteCount() {
                var len = noteField.value.length;
                noteCount.textContent = len + ' / ' + noteField.maxLength;
            }

            if (noteField) {
                updateNoteCount();
                noteField.addEventListener('input', updateNoteCount);
            }

            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                    form.classList.add('was-validated');
                    return false;
                }
                // prevent double submit
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Đang xử lý...';
            }, false);

            // autofocus on first input
            var firstInput = document.querySelector('input[name="full_name"]');
            if (firstInput) firstInput.focus();
        })();
    </script>
</body>
</html>
