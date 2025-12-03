# Dự Án 1 - Hệ Thống Quản Lý Booking Tour Du Lịch

## 📋 Mô Tả Dự Án

Hệ thống quản lý booking tour du lịch được xây dựng bằng PHP thuần với kiến trúc MVC, sử dụng MySQL làm cơ sở dữ liệu và giao diện SB Admin 2 template.

## ⚡ Tính Năng Chính

### 🏠 Dashboard
- Tổng quan thông tin hệ thống
- Thống kê booking và tour

### 🗺️ Quản Lý Tour
- Thêm, sửa, xóa tour
- Quản lý thông tin chi tiết tour
- Upload hình ảnh tour

### 📅 Quản Lý Booking
- **Thêm booking mới**: Form đầy đủ thông tin khách hàng và booking
- **Danh sách booking**: Hiển thị tất cả booking với filter theo trạng thái
- **Chi tiết booking**: Xem thông tin đầy đủ của một booking
- **Sửa booking**: Cập nhật thông tin booking và khách hàng
- **Quản lý trạng thái**: Pending, Deposited, Completed, Canceled

### 👥 Quản Lý Khách Hàng
- Tự động tạo hoặc cập nhật khách hàng
- Quản lý thông tin liên hệ
- Lịch sử booking của khách hàng

## 🛠️ Công Nghệ Sử Dụng

### Backend
- **PHP 8.1+**: Ngôn ngữ lập trình chính
- **MySQL 8.0+**: Hệ quản trị cơ sở dữ liệu
- **PDO**: Thao tác database an toàn
- **Session**: Quản lý phiên người dùng

### Frontend
- **HTML5/CSS3**: Cấu trúc và giao diện
- **Bootstrap 4**: Framework CSS responsive
- **SB Admin 2**: Template quản trị đẹp mắt
- **Font Awesome**: Bộ icon phong phú
- **JavaScript**: Tương tác người dùng

### Architecture
- **MVC Pattern**: Model-View-Controller
- **Single Entry Point**: Tất cả request qua index.php
- **Auto Routing**: Định tuyến tự động theo action

## 📁 Cấu Trúc Thư Mục

```
duan1/
├── assets/                  # Tài nguyên tĩnh
│   ├── css/                # Stylesheet
│   ├── js/                 # JavaScript files
│   ├── img/                # Hình ảnh
│   └── vendor/             # Thư viện bên thứ 3
├── configs/                # Cấu hình
│   ├── env.php            # Cấu hình môi trường
│   └── helper.php         # Hàm hỗ trợ
├── models/                 # Models & Controllers
│   ├── BaseModel.php      # Model cơ sở
│   ├── Booking.php        # Model Booking
│   ├── BookingController.php # Controller Booking
│   ├── Tours.php          # Model Tours
│   └── HomeController.php # Controller Home
├── routes/                 # Định tuyến
│   └── index.php          # Route handler
├── uploads/                # File upload
│   └── tours/             # Hình ảnh tours
├── views/                  # Giao diện
│   ├── main.php           # Layout chính
│   ├── booking_*.php      # Views booking
│   └── tour_*.php         # Views tour
├── duan1_tuor.sql         # Database schema
├── DATABASE_SETUP.md      # Hướng dẫn setup DB
└── index.php              # Entry point
```

## 🗄️ Database Schema

### Bảng Chính
- **bookings**: Quản lý booking của khách hàng
- **customers**: Thông tin khách hàng
- **tours**: Danh sách tour (có 4 tour mẫu)
- **tour_versions**: Các phiên bản tour
- **departures**: Chuyến khởi hành
- **users**: Tài khoản quản trị

### Bảng Liên Kết
- **booking_guests**: Khách trong đoàn
- **tour_schedules**: Lịch trình tour
- **bookings_tours**: Liên kết booking và tour

## 🚀 Hướng Dẫn Cài Đặt

### Yêu Cầu Hệ Thống
- **PHP**: >= 8.1
- **MySQL**: >= 8.0
- **Apache/Nginx**: Web server
- **Laragon/XAMPP**: Local development (khuyến nghị Laragon)

### Bước 1: Clone Repository
```bash
git clone https://github.com/anhlnph58376/duan1.git
cd duan1
```

### Bước 2: Cấu Hình Database
1. Tạo database: `duan1_tuor`
2. Import file: `duan1_tuor.sql`
3. Cập nhật thông tin kết nối trong `configs/env.php`

### Bước 3: Cấu Hình Web Server
- **Laragon**: Copy vào `C:\laragon\www\duan1`
- **XAMPP**: Copy vào `C:\xampp\htdocs\duan1`

### Bước 4: Truy Cập Ứng Dụng
```
http://localhost/duan1
```

## 🎯 Chức Năng Booking Chi Tiết

### Form Thêm Booking
- **Thông tin khách hàng**: Tên, điện thoại (bắt buộc), email, địa chỉ
- **Thông tin booking**: Ngày booking, trạng thái, tổng tiền, tiền cọc
- **Validation**: Tiền cọc không vượt tổng tiền, format số tự động
- **Auto-complete**: Tự động tạo mã booking

### Quản Lý Trạng Thái
- **Pending**: Chờ xác nhận (màu vàng)
- **Deposited**: Đã cọc (màu xanh dương)
- **Completed**: Hoàn tất (màu xanh lá)
- **Canceled**: Hủy (màu đỏ)

### Tìm Kiếm & Filter
- Lọc theo trạng thái
- Tìm kiếm theo tên khách hàng
- Sắp xếp theo ngày booking

## 👨‍💻 Tác Giả

**Anh Lê Nguyên Phúc**
- MSSV: anhlnph58376
- Email: anhlnph58376@fpt.edu.vn
- Trường: FPT Polytechnic

## 📝 Ghi Chú Kỹ Thuật

### Best Practices Đã Áp Dụng
- **Security**: Sử dụng PDO prepared statements
- **Clean Code**: Cấu trúc MVC rõ ràng
- **UX/UI**: Giao diện responsive, thông báo error/success
- **Database**: Foreign key constraints, proper indexing
- **Error Handling**: Try-catch, session-based messaging

### Điểm Nổi Bật
- **Single Form**: Gộp 2 form booking thành 1 giao diện thống nhất
- **Auto Customer**: Tự động tạo/cập nhật thông tin khách hàng
- **Real-time Validation**: JavaScript validation cho form
- **Clean Database**: Loại bỏ các tính năng không cần thiết (notes, assignments)

---

*Dự án được phát triển trong khuôn khổ môn học Web Development tại FPT Polytechnic*