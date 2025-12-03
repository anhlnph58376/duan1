<?php
// Kiểm tra session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include main template
include_once 'main.php';

function renderContent() {
    global $pending_bookings;
?>
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-clipboard-list"></i> Phân công Booking
            </h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    Danh sách Booking chờ xử lý (<?= count($pending_bookings) ?>)
                </h6>
            </div>
            <div class="card-body">
                <?php if (empty($pending_bookings)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                        <h5 class="text-muted">Không có booking nào cần xử lý</h5>
                        <p class="text-muted">Tất cả booking đã được xử lý xong.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Mã Booking</th>
                                    <th>Khách hàng</th>
                                    <th>Điện thoại</th>
                                    <th>Ngày booking</th>
                                    <th>Tổng tiền</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pending_bookings as $booking): ?>
                                <tr>
                                    <td><?= $booking['id'] ?></td>
                                    <td><?= htmlspecialchars($booking['booking_code']) ?></td>
                                    <td><?= htmlspecialchars($booking['customer_name']) ?></td>
                                    <td><?= htmlspecialchars($booking['customer_phone']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($booking['booking_date'])) ?></td>
                                    <td><?= number_format($booking['total_amount'], 0, ',', '.') ?> VNĐ</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="?action=booking_detail&id=<?= $booking['id'] ?>" 
                                               class="btn btn-info btn-sm" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="?action=booking_edit&id=<?= $booking['id'] ?>" 
                                               class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="?action=update_booking_status" class="d-inline">
                                                <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                                                <input type="hidden" name="status" value="Deposited">
                                                <button type="submit" class="btn btn-success btn-sm" 
                                                        onclick="return confirm('Xác nhận đã nhận cọc?')" 
                                                        title="Xác nhận đã cọc">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Hướng dẫn xử lý
                                </div>
                                <div class="text-gray-900">
                                    <ul class="mb-0">
                                        <li>Xem chi tiết booking để kiểm tra thông tin khách hàng</li>
                                        <li>Liên hệ khách hàng để xác nhận thông tin và yêu cầu cọc</li>
                                        <li>Sau khi nhận cọc, click nút "Xác nhận đã cọc" để cập nhật trạng thái</li>
                                        <li>Sử dụng chức năng chỉnh sửa để cập nhật thông tin nếu cần</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>