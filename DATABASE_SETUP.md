# Hướng dẫn Cài đặt Database

## 📋 Thông tin Database

- **Tên database**: `duan1_tuor`
- **Charset**: utf8mb4_unicode_ci (hỗ trợ tiếng Việt đầy đủ)
- **File chính**: `duan1_tuor.sql` (bao gồm tất cả bảng + dữ liệu mẫu)

## 🚀 Cách cài đặt

### Phương pháp 1: Sử dụng phpMyAdmin (Khuyên dùng)

1. Mở phpMyAdmin trong trình duyệt: `http://localhost/phpmyadmin`
2. Tạo database mới:

   - Click "New" ở sidebar trái
   - Database name: `duan1_tuor`
   - Collation: `utf8mb4_unicode_ci`
   - Click "Create"

3. Import file database:
   - Chọn database `duan1_tuor` vừa tạo
   - Click tab "Import"
   - Click "Choose File" và chọn file `duan1_tuor.sql`
   - Click "Go" để import

### Phương pháp 2: Sử dụng MySQL Command Line

```bash
# Tạo database
mysql -u root -p -e "CREATE DATABASE duan1_tuor CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import file
mysql -u root -p duan1_tuor < duan1_tuor.sql
```

## 🗂️ Cấu trúc Database

### Bảng chính:

- **bookings**: Quản lý booking của khách hàng
- **customers**: Thông tin khách hàng
- **tours**: Danh sách tour (có 4 tour mẫu)
- **tour_versions**: Các phiên bản tour
- **departures**: Chuyến khởi hành
- **users**: Tài khoản quản trị

### Bảng liên kết:

- **booking_guests**: Khách trong đoàn
- **departure_bookings**: Liên kết booking với chuyến đi
- **departure_assignments**: Phân công HDV/tài xế

### Bảng tài chính:

- **transactions**: Giao dịch thu/chi
- **revenue_items**: Chi tiết thu
- **expense_items**: Chi tiết chi

## ✅ Kiểm tra sau cài đặt

1. **Kiểm tra bảng**: Database có 15 bảng
2. **Dữ liệu mẫu**: Bảng `tours` có 4 bản ghi
3. **Kết nối**: Chạy trang web và kiểm tra không có lỗi database

## ⚙️ Cấu hình kết nối

File: `configs/env.php`

```php
define('DB_HOST',     'localhost');
define('DB_PORT',     '3306');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME',     'duan1_tuor');
```

## 🔧 Xử lý lỗi thường gặp

### Lỗi "Table doesn't exist"

- Kiểm tra đã import đúng file `duan1_tuor.sql`
- Kiểm tra tên database trong `env.php`

### Lỗi "Access denied"

- Kiểm tra username/password MySQL
- Đảm bảo MySQL service đang chạy

### Lỗi "Character set"

- Đảm bảo database dùng `utf8mb4_unicode_ci`
- Kiểm tra PHP extension mbstring đã enable

## 📄 Files quan trọng

- `duan1_tuor.sql` - File database chính (đã bao gồm cột notes)
- `configs/env.php` - Cấu hình kết nối
- `models/BaseModel.php` - Class kết nối PDO

---

💡 **Lưu ý**: Sau khi cài đặt database, hệ thống booking đã sẵn sàng với đầy đủ chức năng bao gồm ghi chú!
