<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách đoàn khách</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .no-print {
            display: none;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DANH SÁCH ĐOÀN KHÁCH</h1>
        <p><strong>Ngày in:</strong> <?= date('d/m/Y H:i') ?></p>
        <?php if (isset($_GET['departure_id'])): ?>
        <p><strong>Mã đoàn:</strong> <?= htmlspecialchars($_GET['departure_id']) ?></p>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Họ tên</th>
                <th>Giới tính</th>
                <th>Năm sinh</th>
                <th>Số điện thoại</th>
                <th>Số giấy tờ</th>
                <th>Trạng thái thanh toán</th>
                <th>Yêu cầu cá nhân</th>
                <th>Phòng</th>
                <th>Check-in</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stt = 1;
            if (isset($customers) && is_array($customers) && count($customers) > 0):
                foreach ($customers as $customer):
            ?>
            <tr>
                <td><?= $stt++ ?></td>
                <td><?= htmlspecialchars($customer['name'] ?? '') ?></td>
                <td><?= htmlspecialchars($customer['gender'] ?? '') ?></td>
                <td><?= htmlspecialchars($customer['year_of_birth'] ?? '') ?></td>
                <td><?= htmlspecialchars($customer['phone'] ?? '') ?></td>
                <td>
                    <?php if (!empty($customer['id_type']) && !empty($customer['id_number'])): ?>
                        <?= htmlspecialchars($customer['id_type']) ?><br>
                        <?= htmlspecialchars($customer['id_number']) ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($customer['payment_status'] ?? '') ?></td>
                <td><?= htmlspecialchars($customer['personal_requests'] ?? '') ?></td>
                <td><?= htmlspecialchars($customer['room_allocation'] ?? '') ?></td>
                <td>
                    [<?= ($customer['checkin_status'] ?? '') == 'Đã đến' ? '✓' : (($customer['checkin_status'] ?? '') == 'Vắng mặt' ? '✗' : '○') ?>]
                    <?= htmlspecialchars($customer['checkin_status'] ?? 'Chưa đến') ?>
                </td>
            </tr>
            <?php
                endforeach;
            else:
            ?>
            <tr>
                <td colspan="10" style="text-align: center; color: #666;">
                    Không có khách hàng nào để hiển thị.<br>
                    <small>Vui lòng thêm khách hàng trước khi in danh sách.</small>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top: 50px;">
        <div style="display: flex; justify-content: space-between;">
            <div>
                <p><strong>Người lập danh sách</strong></p>
                <p>(Ký, ghi rõ họ tên)</p>
            </div>
            <div>
                <p><strong>Hướng dẫn viên</strong></p>
                <p>(Ký, ghi rõ họ tên)</p>
            </div>
            <div>
                <p><strong>Nhân viên vận hành</strong></p>
                <p>(Ký, ghi rõ họ tên)</p>
            </div>
        </div>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; margin-right: 10px;">
            <i class="fas fa-print"></i> In danh sách
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px;">
            <i class="fas fa-times"></i> Đóng
        </button>
    </div>

    <script>
        // Auto-print if not in preview mode
        window.onload = function() {
            <?php if (!isset($_GET['preview'])): ?>
            setTimeout(function() {
                window.print();
            }, 500);
            <?php endif; ?>
        }
    </script>
</body>
</html>