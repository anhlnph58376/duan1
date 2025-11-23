<?php
class AdminController
{
    protected $departureModel;
    protected $bookingModel;

    public function __construct()
    {
        $this->departureModel = new Departure();
        $this->bookingModel = new Booking();
    }

    // List current reservations (holds)
    public function reservations()
    {
        $reservations = $this->departureModel->getActiveReservations();
        require_once PATH_VIEW . 'admin_reservations.php';
    }

    // Confirm a reservation (move into departure_bookings)
    public function confirmReservation()
    {
        $id = $_POST['id'] ?? $_GET['id'] ?? 0;
        if (!$id) {
            $_SESSION['error'] = 'Reservation ID không hợp lệ.';
            header('Location: ?action=admin_reservations');
            exit;
        }

        // fetch reservation details before confirming
        try {
            $pdo = (new BaseModel())->getPdo();
            $stmt = $pdo->prepare('SELECT * FROM departure_reservations WHERE id = :id LIMIT 1');
            $stmt->execute([':id' => $id]);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $res = null;
        }

        $ok = $this->departureModel->confirmReservation($id);
        if ($ok) {
            if ($res) {
                $bookingId = $res['booking_id'];
                $old = $this->bookingModel->getBookingById($bookingId)['status'] ?? null;
                $new = 'Deposited';
                $this->bookingModel->updateBookingStatus($bookingId, $new);
                if (function_exists('writeStatusHistory')) writeStatusHistory($bookingId, $old, $new, $_SESSION['user_name'] ?? null);
            }
            $_SESSION['success'] = 'Xác nhận reservation thành công.';
        } else {
            $_SESSION['error'] = 'Xác nhận reservation thất bại.';
        }
        header('Location: ?action=admin_reservations');
        exit;
    }

    // Expire reservation
    public function expireReservation()
    {
        $id = $_POST['id'] ?? $_GET['id'] ?? 0;
        if (!$id) {
            $_SESSION['error'] = 'Reservation ID không hợp lệ.';
            header('Location: ?action=admin_reservations');
            exit;
        }

        $ok = $this->departureModel->expireReservationById($id);
        if ($ok) {
            $_SESSION['success'] = 'Đã huỷ reservation.';
        } else {
            $_SESSION['error'] = 'Không thể huỷ reservation.';
        }
        header('Location: ?action=admin_reservations');
        exit;
    }
}
