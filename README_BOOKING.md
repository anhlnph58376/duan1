# Hệ thống Quản lý Booking Tours

## Mô tả tính năng

Hệ thống quản lý booking tours với đầy đủ các chức năng CRUD và gán hướng dẫn viên (HDV), bao gồm:

### 1. Quản lý Booking (CRUD)

- **Thêm booking mới**: Tạo booking cho khách hàng với thông tin tour, ngày đi, số người
- **Xem danh sách booking**: Hiển thị tất cả booking với thống kê theo trạng thái
- **Chỉnh sửa booking**: Cập nhật thông tin booking, thay đổi trạng thái
- **Xóa booking**: Xóa booking không cần thiết
- **Xem chi tiết**: Hiển thị đầy đủ thông tin booking, khách hàng, tour và HDV

### 2. Quản lý Hướng dẫn viên (HDV)

- **Gán HDV cho booking**: Chọn HDV phù hợp cho từng tour
- **Thay đổi HDV**: Thay đổi HDV đã gán khi cần thiết
- **Xem thông tin HDV**: Hiển thị chi tiết HDV được gán

### 3. Tính năng bổ sung

- **Tìm kiếm và lọc**: Tìm theo mã booking, tên KH, tên tour, trạng thái, ngày
- **Thống kê**: Hiển thị số lượng booking theo từng trạng thái
- **Xuất báo cáo**: Xuất danh sách booking ra file Excel/CSV
- **Tính toán tự động**: Tự động tính tổng tiền dựa trên giá tour và số người

## Cài đặt và Thiết lập

### 1. Cơ sở dữ liệu

Chạy file `database_booking.sql` để tạo các bảng cần thiết:

```sql
-- Tạo bảng users (khách hàng, HDV, admin)
-- Tạo bảng bookings
-- Thêm dữ liệu mẫu
-- Tạo các index tối ưu
```

### 2. Cấu trúc bảng

#### Bảng `users`:

- `id`: ID người dùng
- `full_name`: Họ tên
- `email`: Email (unique)
- `phone`: Số điện thoại
- `role`: Vai trò (user/guide/admin)
- `status`: Trạng thái (active/inactive)

#### Bảng `bookings`:

- `id`: ID booking
- `booking_code`: Mã booking (tự động)
- `user_id`: ID khách hàng
- `tour_id`: ID tour
- `guide_id`: ID hướng dẫn viên (có thể null)
- `booking_date`: Ngày đặt tour
- `number_of_people`: Số người
- `total_price`: Tổng tiền
- `notes`: Ghi chú
- `status`: Trạng thái booking
- `created_at`, `updated_at`: Thời gian tạo/cập nhật

### 3. Trạng thái Booking

- `pending`: Chờ xử lý
- `confirmed`: Đã xác nhận
- `assigned`: Đã gán HDV
- `in_progress`: Đang thực hiện
- `completed`: Hoàn thành
- `cancelled`: Đã hủy

## Hướng dẫn sử dụng

### 1. Truy cập hệ thống

```
URL: http://localhost/Duan1/?action=bookings
```

### 2. Các chức năng chính

#### A. Xem danh sách booking

- Truy cập: `?action=bookings`
- Hiển thị: Danh sách tất cả booking với thống kê
- Tính năng: Tìm kiếm, lọc, phân trang

#### B. Thêm booking mới

- Truy cập: `?action=booking_add`
- Form: Chọn khách hàng, tour, ngày đi, số người
- Tự động: Tính tổng tiền, tạo mã booking

#### C. Chỉnh sửa booking

- Truy cập: `?action=booking_edit&id={id}`
- Cho phép: Sửa tất cả thông tin booking
- Cập nhật: Trạng thái, thông tin tour

#### D. Xem chi tiết booking

- Truy cập: `?action=booking_detail&id={id}`
- Hiển thị: Đầy đủ thông tin booking, KH, tour, HDV
- Thao tác: Cập nhật trạng thái nhanh

#### E. Gán HDV cho booking

- Truy cập: `?action=assign_guide&id={id}`
- Chọn: HDV từ danh sách có sẵn
- Cập nhật: Trạng thái thành "assigned"

### 3. Tìm kiếm và lọc

- **Từ khóa**: Tìm theo mã booking, tên KH, tên tour
- **Trạng thái**: Lọc theo trạng thái booking
- **Ngày**: Lọc theo khoảng thời gian booking

### 4. Xuất báo cáo

- Truy cập: `?action=booking_export`
- Format: CSV với encoding UTF-8
- Nội dung: Tất cả thông tin booking

## API Endpoints

```php
// Danh sách booking
GET ?action=bookings

// Tìm kiếm booking
GET ?action=booking_search&keyword={keyword}&status={status}

// Thêm booking
GET ?action=booking_add
POST ?action=booking_store

// Chi tiết booking
GET ?action=booking_detail&id={id}

// Sửa booking
GET ?action=booking_edit&id={id}
POST ?action=booking_update

// Xóa booking
GET ?action=booking_delete&id={id}

// Gán HDV
GET ?action=assign_guide&id={id}
POST ?action=store_assign_guide

// Cập nhật trạng thái
POST ?action=update_status

// Xuất báo cáo
GET ?action=booking_export

// Lấy thông tin tour (Ajax)
GET ?action=get_tour_info&tour_id={id}
```

## Bảo mật và Validation

### 1. Validation dữ liệu

- Required fields: user_id, tour_id, booking_date, number_of_people
- Date validation: Ngày booking phải >= ngày hiện tại + 1
- Number validation: Số người 1-50

### 2. Session Messages

- Success messages: Thông báo thành công
- Error messages: Thông báo lỗi validation/database

### 3. SQL Injection Prevention

- Sử dụng Prepared Statements
- Validate input parameters

## Ghi chú kỹ thuật

### 1. Autoloader

- Tự động load Model và Controller classes
- Pattern: `ClassName.php` trong thư mục tương ứng

### 2. Database Connection

- Sử dụng PDO với error handling
- Base Model class cho các operations chung

### 3. MVC Architecture

- Model: `models/Booking.php`
- Controller: `controllers/BookingController.php`
- Views: `views/booking_*.php`
- Routes: `routes/index.php`

### 4. Frontend Framework

- Bootstrap 4 (SB Admin 2)
- jQuery cho interactions
- DataTables cho hiển thị dữ liệu

## Troubleshooting

### 1. Lỗi không hiển thị booking

- Kiểm tra bảng `bookings` đã tạo chưa
- Verify foreign key constraints

### 2. Lỗi autoload class

- Đảm bảo tên file = tên class
- Kiểm tra case-sensitive

### 3. Lỗi gán HDV

- Kiểm tra role = 'guide' trong bảng users
- Verify user status = 'active'

### 4. Lỗi tính toán giá

- Kiểm tra giá tour trong database
- Validate số người > 0

## Phát triển tiếp

### Tính năng có thể mở rộng:

1. **Email notifications**: Gửi email khi gán HDV
2. **Payment integration**: Thanh toán online
3. **Mobile responsiveness**: Tối ưu cho mobile
4. **Advanced reporting**: Báo cáo doanh thu theo thời gian
5. **Tour calendar**: Lịch tour với availability
6. **Customer reviews**: Đánh giá tour và HDV
7. **Multi-language**: Hỗ trợ đa ngôn ngữ
8. **API REST**: Tạo API cho mobile app
