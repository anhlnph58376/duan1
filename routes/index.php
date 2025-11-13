<?php

$action = $_GET['action'] ?? '/';

$homeController = new HomeController;
$bookingController = new BookingController;

match ($action) {
    // Home routes
    '/'         => $homeController->index(),
    
    // Tour routes
    'tours'    => $homeController->tours(),
    'tour_edit'    => $homeController->tour_edit(),
    'updateTour'    => $homeController->updateTour(),
    'tour_add'    => $homeController->tour_add(),
    'addTour'    => $homeController->addTour(),
    'tour_delete'    => $homeController->tour_delete(),
    'tour_detail'    => $homeController->tour_detail(),
    
    // Booking routes
    'bookings'         => $bookingController->index(),
    'booking_search'   => $bookingController->search(),
    'booking_add'      => $bookingController->add(),
    'booking_store'    => $bookingController->store(),
    'booking_detail'   => $bookingController->detail(),
    'booking_edit'     => $bookingController->edit(),
    'booking_update'   => $bookingController->update(),
    'booking_delete'   => $bookingController->delete(),
    'assign_guide'     => $bookingController->assign_guide(),
    'store_assign_guide' => $bookingController->store_assign_guide(),
    'update_status'    => $bookingController->update_status(),
    'booking_export'   => $bookingController->export(),
    'get_tour_info'    => $bookingController->get_tour_info(),
};