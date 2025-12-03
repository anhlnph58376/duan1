<?php
// Partial view: expects $member to be defined
?>
<div class="card">
    <div class="card-header">
        <strong>Chi tiết thành viên</strong>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="card-body">
        <dl class="row">
            <dt class="col-sm-4">Họ tên</dt>
            <dd class="col-sm-8"><?= htmlspecialchars($member['full_name'] ?? '') ?></dd>

            <dt class="col-sm-4">Tuổi</dt>
            <dd class="col-sm-8"><?= htmlspecialchars($member['age'] ?? '') ?></dd>

            <dt class="col-sm-4">Giới tính</dt>
            <dd class="col-sm-8"><?= htmlspecialchars($member['gender'] ?? '') ?></dd>

            <dt class="col-sm-4">Số hộ chiếu / CMND</dt>
            <dd class="col-sm-8"><?= htmlspecialchars($member['passport_number'] ?? '') ?></dd>

            <dt class="col-sm-4">Tình trạng thanh toán</dt>
            <dd class="col-sm-8"><?= htmlspecialchars($member['payment_status'] ?? '') ?> <?= isset($member['payment_amount']) && $member['payment_amount'] !== null ? ' - ' . number_format($member['payment_amount'],0,',','.') . ' VNĐ' : '' ?></dd>

            <dt class="col-sm-4">Check-in</dt>
            <dd class="col-sm-8"><?= htmlspecialchars($member['checkin_status'] ?? '') ?></dd>

            <dt class="col-sm-4">Yêu cầu đặc biệt</dt>
            <dd class="col-sm-8">
                <?php
                    // Fallback: member.special_request -> member.note -> booking.special_requests (if booking available in scope)
                    $req = '';
                    if (!empty($member['special_request'])) $req = $member['special_request'];
                    elseif (!empty($member['note'])) $req = $member['note'];
                    elseif (!empty($booking['special_requests'])) $req = $booking['special_requests'] ?? '';
                    echo nl2br(htmlspecialchars(trim((string)$req)) ?: '<span class="text-muted">-</span>');
                ?>
            </dd>

            <dt class="col-sm-4">Ghi chú</dt>
            <dd class="col-sm-8"><?= nl2br(htmlspecialchars($member['note'] ?? '')) ?></dd>
        </dl>
    </div>
    <div class="card-footer text-right">
        <a href="index.php?action=booking_member_edit&id=<?= $member['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Đóng</button>
    </div>
</div>
