<?php

$action = $_GET['action'] ?? '/';

$homeController = new HomeController;
$bookingController = new BookingController;
$departureController = new DepartureController;

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
};
?>