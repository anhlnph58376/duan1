<?php

$action = isset($_GET['action']) ? trim($_GET['action']) : '/';

// Public routes that don't require authentication
$publicRoutes = ['login', 'logout'];

// Check authentication for protected routes
if (!in_array($action, $publicRoutes)) {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error_message'] = 'Vui lòng đăng nhập để tiếp tục';
        header('Location: index.php?action=login');
        exit;
    }
}

// HDV-only routes
$hdvRoutes = ['hdv_dashboard', 'hdv_bookings', 'hdv_booking_detail', 'hdv_member_checkin', 'hdv_profile'];

// Admin-only routes
$adminRoutes = ['tours', 'tour_add', 'tour_edit', 'tour_delete', 'suppliers', 'supplier_add', 'supplier_edit', 'supplier_delete', 
                'departures', 'departure_add', 'departure_edit', 'departure_delete', 'guides', 'guide_add', 'guide_edit', 'guide_delete',
                'customers', 'customer_add', 'customer_edit', 'customer_delete', 'account_management', 'account_add', 'account_edit', 
                'account_delete', 'account_toggle_active', 'admin_reservations'];

// Role-based access control
$userRole = (int)($_SESSION['user_role'] ?? 0);

if ($userRole === 2 && in_array($action, $adminRoutes)) {
    // HDV trying to access admin route
    $_SESSION['error_message'] = 'Bạn không có quyền truy cập chức năng này';
    header('Location: index.php?action=hdv_dashboard');
    exit;
}

$homeController = new HomeController;
$bookingController = new BookingController;
$departureController = new DepartureController;
$customerController = new CustomerController;
$guideController = new GuideController;
$suppliersController = new SuppliersController;
$adminController = new AdminController;
// Auth
$authController = class_exists('AuthController') ? new AuthController : null;
// Account
$accountController = class_exists('AccountController') ? new AccountController : null;
// Profile
$profileController = class_exists('ProfileController') ? new ProfileController : null;

// Guard: explicitly handle login early to avoid edge-case mismatches
if ($action === 'login') {
    if ($authController) {
        $authController->login();
    } else {
        require_once PATH_VIEW . 'user_login.php';
    }
    exit;
}

// HDV dashboard route
if ($action === 'hdv_dashboard') {
    require_once PATH_VIEW . 'hdv_dashboard.php';
    exit;
}

// HDV bookings route
if ($action === 'hdv_bookings') {
    require_once PATH_VIEW . 'hdv_bookings.php';
    exit;
}

// HDV booking detail route
if ($action === 'hdv_booking_detail') {
    require_once PATH_VIEW . 'hdv_booking_detail.php';
    exit;
}

// HDV schedule route
if ($action === 'hdv_schedule') {
    require_once PATH_VIEW . 'hdv_schedule.php';
    exit;
}

// HDV update availability route
if ($action === 'hdv_update_availability') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $guide_id = $_POST['guide_id'] ?? null;
        $status = $_POST['availability_status'] ?? 'Available';

        if ($guide_id) {
            $guideModel = new Guide();
            $guideModel->updateAvailabilityStatus($guide_id, $status);
            $_SESSION['success_message'] = "Cập nhật trạng thái sẵn sàng thành công!";
        }

        header('Location: index.php?action=hdv_schedule');
        exit;
    }
}

// HDV profile route
if ($action === 'hdv_profile') {
    require_once PATH_VIEW . 'hdv_profile.php';
    exit;
}


match ($action) {
    '/'         => $homeController->index(),
    // Logout
    'logout' => $authController ? $authController->logout() : header('Location: index.php?action=login'),

    // Các đường dẫn Tour
    'tours'    => $homeController->tours(),
    'tour_edit'    => $homeController->tour_edit(),
    'updateTour'    => $homeController->updateTour(),
    'tour_add'    => $homeController->tour_add(),
    'addTour'    => $homeController->addTour(),
    'tour_delete'    => $homeController->tour_delete(),
    'tour_detail'    => $homeController->tour_detail(),
    'deleteTourImage' => $homeController->deleteTourImage(),

    // Các đường dẫn Suppliers
    'suppliers'    => $suppliersController->suppliers(),
    'supplier_edit'    => $suppliersController->supplier_edit(),
    'updateSupplier'    => $suppliersController->updateSupplier(),
    'supplier_add'    => $suppliersController->supplier_add(),
    'addSupplier'    => $suppliersController->addSupplier(),
    'supplier_delete'    => $suppliersController->supplier_delete(),

    // Các đường dẫn Booking
    'bookings'          => $bookingController->bookings(),
    'booking_members'   => $bookingController->booking_members(),
    'booking_members_partial' => $bookingController->booking_members_partial(),
    'booking_detail'    => $bookingController->booking_detail(),
    'booking_add'       => $bookingController->booking_add(),
    'addBooking'        => $bookingController->addBooking(),
    'booking_edit'      => $bookingController->booking_edit(),
    'updateBooking'     => $bookingController->updateBooking(),
    'booking_delete'    => $bookingController->booking_delete(),
    'updateBookingStatus' => $bookingController->updateBookingStatus(),
    'bookTour'          => $bookingController->bookTour(),
    'booking_member_add' => $bookingController->booking_member_add(),
    'booking_member_store' => $bookingController->booking_member_store(),
    'booking_member_edit' => $bookingController->booking_member_edit(),
    'booking_member_update' => $bookingController->booking_member_update(),
    'booking_member_delete' => $bookingController->booking_member_delete(),
    'booking_member_detail' => $bookingController->booking_member_detail(),
    'booking_assign_guide' => $bookingController->booking_assign_guide(),
    'store_assign_guide' => $bookingController->store_assign_guide(),


    
    // Customer routes
    'customers'         => $customerController->index(),
    'customer_edit'     => $customerController->customer_edit(),
    'updateCustomer'    => $customerController->updateCustomer(),
    'customer_add'      => $customerController->customer_add(),
    'addCustomer'       => $customerController->addCustomer(),
    'customer_delete'   => $customerController->customer_delete(),
    'customer_detail'   => $customerController->customer_detail(),
    'updateCheckinStatus' => $customerController->updateCheckinStatus(),
    'updateRoomAllocation' => $customerController->updateRoomAllocation(),
    'printGroupList'    => $customerController->printGroupList(),

    // Guide routes
    'guides'         => $guideController->index(),
    'guide_edit'     => $guideController->guide_edit(),
    'updateGuide'    => $guideController->updateGuide(),
    'guide_add'      => $guideController->guide_add(),
    'addGuide'       => $guideController->addGuide(),
    'guide_delete'   => $guideController->guide_delete(),
    'guide_detail'   => $guideController->guide_detail(),
    'guide_schedule' => $guideController->guide_schedule(),
    'update_schedule' => $guideController->update_schedule(),
    'update_availability' => $guideController->update_availability(),
    'guide_performance' => $guideController->guide_performance(),
    'update_health_status' => $guideController->update_health_status(),
    'add_performance_log' => $guideController->add_performance_log(),
    'guides_by_type' => $guideController->guides_by_type(),
    'guides_by_specialization' => $guideController->guides_by_specialization(),
    'guide_dashboard' => $guideController->guide_dashboard(),


    'booking_add_to_departure' => $bookingController->addBookingToDeparture(),
    'booking_create_new_departure' => $bookingController->createNewDeparture(),
    'booking_process_new_departure' => $bookingController->processNewDeparture(),

    // Các đường dẫn Departure (Quản lý đoàn)
    'departures'          => $departureController->index(),
    'departure_detail'    => $departureController->detail(),
    'departure_add'       => $departureController->add(),
    'departure_create'    => $departureController->create(),
    'departure_edit'      => $departureController->edit(),
    'departure_update'    => $departureController->update(),
    'departure_delete'    => $departureController->delete(),
    'departure_add_existing_booking' => $departureController->addExistingBookingToDeparture(),
    'departure_create_new_booking' => $departureController->createNewBooking(),
    'departure_process_new_booking' => $departureController->processNewBooking(),
    // Admin reservation review
    'admin_reservations' => $adminController->reservations(),
    'admin_confirm_reservation' => $adminController->confirmReservation(),
    'admin_expire_reservation' => $adminController->expireReservation(),
    // Account Management routes
    'account_management' => $accountController ? $accountController->index() : require_once PATH_VIEW . 'account_management.php',
    'account_add' => $accountController ? $accountController->add() : require_once PATH_VIEW . 'account_management.php',
    'account_store' => $accountController ? $accountController->store() : require_once PATH_VIEW . 'account_management.php',
    'account_edit' => $accountController ? $accountController->edit() : require_once PATH_VIEW . 'account_management.php',
    'account_update' => $accountController ? $accountController->update() : require_once PATH_VIEW . 'account_management.php',
    'account_delete' => $accountController ? $accountController->delete() : require_once PATH_VIEW . 'account_management.php',
    'account_toggle_active' => $accountController ? $accountController->toggle_active() : require_once PATH_VIEW . 'account_management.php',
    'account_profile' => $accountController ? $accountController->profile() : require_once PATH_VIEW . 'account_management.php',
    'account_profile_update' => $accountController ? $accountController->profile_update() : require_once PATH_VIEW . 'account_management.php',
    // Profile routes (for logged-in user)
    'profile' => $profileController ? $profileController->index() : require_once PATH_VIEW . 'profile.php',
    'profile_update' => $profileController ? $profileController->update() : require_once PATH_VIEW . 'profile.php',
    default => require_once PATH_VIEW . '404.html',
};
?>
