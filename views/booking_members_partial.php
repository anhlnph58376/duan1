<?php
// Partial view: expects $booking and $members to be defined
?>
<div class="card">
    <div class="card-body">
        <h5>Thành viên của booking #<?= htmlspecialchars($booking['id']) ?> -
            <?= htmlspecialchars($booking['booking_code'] ?? '') ?></h5>
        <table class="table table-sm table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Họ tên</th>
                    <th>Tuổi</th>
                    <th>Giới tính</th>
                    <th>Thanh toán</th>
                    <th>Check-in</th>
                    <th>Yêu cầu</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($members)): ?>
                <?php foreach ($members as $i => $m): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($m['full_name']) ?></td>
                    <td><?= htmlspecialchars($m['age']) ?></td>
                    <td><?= htmlspecialchars($m['gender']) ?></td>
                    <td>
                        <div><?= htmlspecialchars($m['payment_status'] ?? 'unpaid') ?></div>
                        <div class="small-muted">
                            <?= isset($m['payment_amount']) ? number_format($m['payment_amount'],0,',','.') . ' VNĐ' : '' ?>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($m['checkin_status'] ?? 'not_arrived') ?></td>
                    <td style="max-width:320px; white-space:pre-wrap; word-break:break-word;">
                        <?php
                            // Fallback order: member.special_request -> member.note -> booking.special_requests
                            $req = '';
                            if (!empty($m['special_request'])) $req = $m['special_request'];
                            elseif (!empty($m['note'])) $req = $m['note'];
                            elseif (!empty($booking['special_requests'])) $req = $booking['special_requests'];

                            $req = trim((string)$req);
                            if ($req === '') {
                                echo '<span class="text-muted">-</span>';
                            } else {
                                $short = mb_strlen($req) > 120 ? mb_substr($req, 0, 120) . '...' : $req;
                                $escapedFull = htmlspecialchars($req);
                                $escapedShort = nl2br(htmlspecialchars($short));
                                echo '<span title="' . $escapedFull . '">' . $escapedShort . '</span>';
                            }
                        ?>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info btn-view-member" data-id="<?= $m['id'] ?>">Xem</button>
                        <a href="index.php?action=booking_member_edit&id=<?= $m['id'] ?>"
                            class="btn btn-sm btn-warning">Sửa</a>
                        <a href="index.php?action=booking_member_delete&id=<?= $m['id'] ?>"
                            class="btn btn-sm btn-danger" onclick="return confirm('Xác nhận xóa thành viên?')">Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Chưa có thành viên</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="text-right">
            <a href="index.php?action=booking_member_add&booking_id=<?= $booking['id'] ?>"
                class="btn btn-success btn-sm">Thêm thành viên</a>
        </div>
    </div>
</div>

<!-- Modal for member detail -->
<div class="modal fade" id="memberDetailModal" tabindex="-1" role="dialog" aria-labelledby="memberDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center p-4">Đang tải...</div>
            </div>
        </div>
    </div>
</div>

<script>
        (function($){
                $(document).on('click', '.btn-view-member', function(e){
                        e.preventDefault();
                        var id = $(this).data('id');
                        $('#memberDetailModal .modal-body').html('<div class="text-center p-4">Đang tải...</div>');
                        $('#memberDetailModal').modal('show');
                        $.get('index.php?action=booking_member_detail&id=' + id, function(resp){
                                $('#memberDetailModal .modal-body').html(resp);
                        }).fail(function(){
                                $('#memberDetailModal .modal-body').html('<div class="text-danger p-4">Không tải được dữ liệu.</div>');
                        });
                });
        })(jQuery);
</script>
