<?php
// Script to expire due reservations. Run from CLI (PHP) or schedule via Task Scheduler.
require_once __DIR__ . '/../configs/env.php';
require_once __DIR__ . '/../models/BaseModel.php';
require_once __DIR__ . '/../models/Departure.php';
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../configs/helper.php';

$dep = new Departure();
$bm = new Booking();

try {
    // find reservations that are due to expire
    $pdo = (new BaseModel())->getPdo();
    $stmt = $pdo->prepare("SELECT * FROM departure_reservations WHERE status = 'reserved' AND expires_at IS NOT NULL AND expires_at <= NOW()");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rows)) {
        echo "No reservations to expire.\n";
        exit(0);
    }

    $count = 0;
    foreach ($rows as $r) {
        $resId = $r['id'];
        $bookingId = $r['booking_id'];

        // mark reservation expired
        $ok = $dep->expireReservationById($resId);
        if (!$ok) continue;

        // If booking is in Tentative status or similar, revert to Pending
        $booking = $bm->getBookingById($bookingId);
        $oldStatus = $booking['status'] ?? null;
        $shouldRevert = ($oldStatus === 'Tentative');
        if ($shouldRevert) {
            $bm->updateBookingStatus($bookingId, 'Pending');
            if (function_exists('writeStatusHistory')) writeStatusHistory($bookingId, $oldStatus, 'Pending', null);
        }

        $count++;
    }

    echo "Expired {$count} reservation(s).\n";
    exit(0);
} catch (Exception $e) {
    echo "Error expiring reservations: " . $e->getMessage() . "\n";
    exit(1);
}
