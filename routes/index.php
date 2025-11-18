<?php

$action = $_GET['action'] ?? '/';

$homeController = new HomeController;
$bookingController = new BookingController;
$customerController = new CustomerController;
$guideController = new GuideController;

match ($action) {
    '/'         => $homeController->index(),
    'tours'    => $homeController->tours(),
    'tour_edit'    => $homeController->tour_edit(),
    'updateTour'    => $homeController->updateTour(),
    'tour_add'    => $homeController->tour_add(),
    'addTour'    => $homeController->addTour(),
    'tour_delete'    => $homeController->tour_delete(),
    'tour_detail'    => $homeController->tour_detail(),
    


    // Các đường dẫn Booking
    'bookings'          => $bookingController->bookings(),
    'booking_detail'    => $bookingController->booking_detail(),
    'booking_add'       => $bookingController->booking_add(),
    'addBooking'        => $bookingController->addBooking(),
    'booking_edit'      => $bookingController->booking_edit(),
    'updateBooking'     => $bookingController->updateBooking(),
    'booking_delete'    => $bookingController->booking_delete(),
    'updateBookingStatus' => $bookingController->updateBookingStatus(),
    'bookTour'          => $bookingController->bookTour(),

    
    // Customer routes
    'customers'         => $customerController->index(),
    'customer_edit'     => $customerController->customer_edit(),
    'updateCustomer'    => $customerController->updateCustomer(),
    'customer_add'      => $customerController->customer_add(),
    'addCustomer'       => $customerController->addCustomer(),
    'customer_delete'   => $customerController->customer_delete(),
    'customer_detail'   => $customerController->customer_detail(),

    // Guide routes
    'guides'         => $guideController->index(),
    'guide_edit'     => $guideController->guide_edit(),
    'updateGuide'    => $guideController->updateGuide(),
    'guide_add'      => $guideController->guide_add(),
    'addGuide'       => $guideController->addGuide(),
    'guide_delete'   => $guideController->guide_delete(),
    'guide_detail'   => $guideController->guide_detail(),
};