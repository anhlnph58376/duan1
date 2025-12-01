<?php

$action = $_GET['action'] ?? '/';


$homeController = new HomeController;
$bookingController = new BookingController;
$departureController = new DepartureController;
$customerController = new CustomerController;
$guideController = new GuideController;
$suppliersController = new SuppliersController;
$adminController = new AdminController;


match ($action) {
    '/'         => $homeController->index(),

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
    // 'tour_schedule'  => $homeController->tour_schedule(), // Đã xóa vì không có hàm này

    // Các đường dẫn Suppliers
    'suppliers'    => $suppliersController->suppliers(),

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
    default => require_once PATH_VIEW . '404.html',
};
?>
