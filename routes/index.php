<?php

$action = $_GET['action'] ?? '/';

$homeController = new HomeController;
$datTourController = new DatTourController;
$doanController = new DoanController;
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

    // Các đường dẫn Đặt Tour
    'dat_tour'          => $datTourController->dat_tour(),
    'dat_tour_chi_tiet' => $datTourController->dat_tour_chi_tiet(),
    'dat_tour_them'     => $datTourController->dat_tour_them(),
    'themDatTour'       => $datTourController->themDatTour(),
    'dat_tour_sua'      => $datTourController->dat_tour_sua(),
    'capNhatDatTour'    => $datTourController->capNhatDatTour(),
    'dat_tour_xoa'      => $datTourController->dat_tour_xoa(),
    'capNhatTrangThaiDatTour' => $datTourController->capNhatTrangThaiDatTour(),
    'datTourTuChiTiet'  => $datTourController->datTourTuChiTiet(),
    'dat_tour_them_vao_doan' => $datTourController->addBookingToDeparture(),
    'dat_tour_tao_doan_moi' => $datTourController->taoDoanMoi(),
    'xu_ly_tao_doan_moi' => $datTourController->xuLyTaoDoanMoi(),

    // Các đường dẫn Quản lý đoàn
    'doan'               => $doanController->index(),
    'doan_chi_tiet'      => $doanController->detail(),
    'doan_them'          => $doanController->add(),
    'doan_tao'           => $doanController->create(),
    'doan_sua'           => $doanController->edit(),
    'doan_cap_nhat'      => $doanController->update(),
    'doan_xoa'           => $doanController->delete(),
    'doan_them_booking_co_san' => $doanController->addExistingBookingToDeparture(),
    'doan_tao_booking_moi' => $doanController->createNewBooking(),
    'doan_xu_ly_booking_moi' => $doanController->processNewBooking(),
};
?>