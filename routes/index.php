<?php

$action = $_GET['action'] ?? '/';

$homeController = new HomeController;
$bookingController = new BookingController;
$customerController = new CustomerController;

match ($action) {
    '/'         => $homeController->index(),
    'tours'    => $homeController->tours(),
    'tour_edit'    => $homeController->tour_edit(),
    'updateTour'    => $homeController->updateTour(),
    'tour_add'    => $homeController->tour_add(),
    'addTour'    => $homeController->addTour(),
    'tour_delete'    => $homeController->tour_delete(),
    'tour_detail'    => $homeController->tour_detail(),
    

    // Customer routes
    'customers'         => $customerController->index(),
    'customer_edit'     => $customerController->customer_edit(),
    'updateCustomer'    => $customerController->updateCustomer(),
    'customer_add'      => $customerController->customer_add(),
    'addCustomer'       => $customerController->addCustomer(),
    'customer_delete'   => $customerController->customer_delete(),
    'customer_detail'   => $customerController->customer_detail(),

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
};