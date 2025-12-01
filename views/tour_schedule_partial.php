<?php
// Partial: expects $tour (from Tours::getTourById) and optional $versions array
?>
<div class="p-3">
    <h4 class="mb-2">Lịch trình: <?= htmlspecialchars($tour['name'] ?? '') ?></h4>
    <p class="text-muted mb-1"><strong>Thời lượng:</strong> <?= htmlspecialchars($tour['duration'] ?? 'N/A') ?></p>
    <p class="text-muted mb-3"><strong>Mã tour:</strong> <span class="badge badge-info"><?= htmlspecialchars($tour['tour_code'] ?? '') ?></span></p>

    <h6>Mô tả ngắn</h6>
    <div class="mb-3" style="white-space:pre-wrap;"><?= nl2br(htmlspecialchars($tour['description'] ?? 'Không có mô tả.')) ?></div>

    <?php if (!empty($versions)): ?>
        <h6>Phiên bản lịch trình</h6>
        <div class="mb-3">
            <?php foreach ($versions as $v): ?>
                <div class="card mb-2">
                    <div class="card-body p-2">
                        <strong><?= htmlspecialchars($v['version_name'] ?? 'Phiên bản') ?></strong>
                        <div class="small text-muted">Hiệu lực: <?= htmlspecialchars($v['effective_date'] ?? '') ?></div>
                        <?php if (!empty($v['itinerary'])): ?>
                            <div class="mt-2" style="white-space:pre-wrap;"><?= nl2br(htmlspecialchars($v['itinerary'])) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Chưa có lịch trình chi tiết cho tour này. Vui lòng mở trang chi tiết tour để xem thêm.</div>
    <?php endif; ?>

    <div class="text-right mt-3">
        <a href="?action=tour_detail&id=<?= htmlspecialchars($tour['id']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">Mở trang Tour</a>
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Đóng</button>
    </div>
</div>
