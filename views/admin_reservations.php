<?php
// expects $reservations
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản trị Reservations</title>
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body>
    <?php include PATH_VIEW . 'includes/sidebar.php'; ?>
    <div class="container-fluid pt-4">
        <h3>Danh sách reservation đang giữ chỗ</h3>
        <?php if (isset($_SESSION['success'])): ?><div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div><?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?><div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div><?php endif; ?>

        <div class="card">
            <div class="card-body">
                <?php if (empty($reservations)): ?>
                    <p class="text-muted">Không có reservation nào đang được giữ.</p>
                <?php else: ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Booking</th>
                                <th>Khách</th>
                                <th>Departure</th>
                                <th>Pax</th>
                                <th>Reserved At</th>
                                <th>Expires At</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $r): ?>
                                <tr>
                                    <td><?= $r['id'] ?></td>
                                    <td><a href="?action=booking_detail&id=<?= $r['booking_id'] ?>"><?= htmlspecialchars($r['booking_code'] ?? ('BK#'.$r['booking_id'])) ?></a></td>
                                    <td><?= htmlspecialchars($r['customer_name'] ?? '') ?><br><?= htmlspecialchars($r['customer_phone'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($r['tour_name'] ?? '') ?><br><?= date('d/m/Y', strtotime($r['departure_date'] ?? 'now')) ?></td>
                                    <td><?= (int)$r['pax_count'] ?></td>
                                    <td><?= htmlspecialchars($r['reserved_at']) ?></td>
                                    <td><?= htmlspecialchars($r['expires_at']) ?></td>
                                    <td>
                                        <form method="post" action="?action=admin_confirm_reservation" style="display:inline-block">
                                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                            <button class="btn btn-sm btn-success" type="submit">Xác nhận</button>
                                        </form>
                                        <form method="post" action="?action=admin_expire_reservation" style="display:inline-block;margin-left:6px">
                                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                            <button class="btn btn-sm btn-danger" type="submit">Huỷ</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
