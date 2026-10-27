# Tài khoản thành viên — CodeIgniter 4

Ứng dụng web quản lý tài khoản thành viên (người dùng website) xây dựng trên nền tảng **CodeIgniter 4**, chạy cục bộ trên môi trường **XAMPP (Apache, MySQL/MariaDB)**, giao diện sử dụng **Bootstrap 5**.

---

## 🎯 Chức năng chính

### 1. Phân hệ Thành viên (Người dùng)
- **Đăng ký tài khoản (`/dang-ky`):** Tạo tài khoản mới với username (duy nhất), họ tên, mật khẩu, giới tính (`Nam`/`Nữ`), quốc gia và tải lên ảnh đại diện.
- **Đăng nhập (`/dang-nhap`):** Xác thực mật khẩu qua `password_hash()` (bcrypt); tự động tái tạo session ID (`session()->regenerate(true)`) chống tấn công Session Fixation.
- **Xem & Cập nhật hồ sơ (`/tai-khoan`):**
  - Chỉ thành viên đang đăng nhập mới truy cập được (bảo vệ bởi `memberauth` filter).
  - Tên đăng nhập được giữ cố định.
  - Cho phép đổi mật khẩu (để trống nếu giữ nguyên mật khẩu cũ).
  - Thay đổi ảnh đại diện (ảnh cũ tự động được dọn dẹp sau khi cập nhật thành công).
- **Đăng xuất (`POST /dang-xuat`):** Hủy session an toàn và có bảo vệ CSRF.
- **Phục vụ ảnh đại diện an toàn (`/anh/{filename}`):**
  - File ảnh lưu tại `writable/uploads/`, không truy cập trực tiếp qua public URL.
  - Kiểm tra MIME thực tế (`image/jpeg`, `image/png`, `image/gif`, `image/webp`).
  - Chống Path Traversal bằng regex kiểm tra định dạng tên tệp.
  - Thiết lập header `X-Content-Type-Options: nosniff` và `Cache-Control`.

### 2. Phân hệ Quản trị viên (Admin)
- Đăng nhập quản trị (`/quan-tri/dang-nhap`).
- Xem danh sách, tìm kiếm, thêm, sửa, xóa thành viên (`/thanhvien` — được bảo vệ bởi `adminauth` filter).

---

## 🗄️ Cơ sở dữ liệu

Database: **`bookstore`** (MySQL/MariaDB). Script khởi tạo nằm tại: `database/bookstore.sql`.

### Bảng `thanhvien`
| Cột | Kiểu | Mô tả |
|---|---|---|
| `username` | `VARCHAR(50)` | Khóa chính, tên đăng nhập |
| `matkhau` | `VARCHAR(255)` | Mật khẩu hash bằng `PASSWORD_DEFAULT` |
| `hoten` | `VARCHAR(100)` | Họ và tên |
| `gioitinh` | `VARCHAR(3)` | Giới tính (`Nam`, `Nữ`) |
| `quocgia` | `VARCHAR(50)` | Quốc gia |
| `hinhdaidien` | `VARCHAR(255)` | Tên tệp ảnh trong `writable/uploads` |

### Bảng `quantri`
| Cột | Kiểu | Mô tả |
|---|---|---|
| `user_admin` | `VARCHAR(50)` | Khóa chính |
| `matkhau` | `VARCHAR(255)` | Mật khẩu hash |
| `hoten` | `VARCHAR(100)` | Họ tên quản trị viên |

> **Tài khoản mẫu có sẵn trong database (Mật khẩu: `123456`):**
> - Thành viên: `nguyenan` / `123456`, `tranbinh` / `123456`
> - Quản trị: `admin` / `123456`

---

## 🚀 Hướng dẫn cài đặt & chạy trên XAMPP

### Bước 1: Yêu cầu môi trường
- XAMPP với **PHP 8.2 trở lên** (đã bật extension: `intl`, `mbstring`, `mysqli`).
- Đã cài đặt **Composer** trên máy.

### Bước 2: Cài đặt dependencies
Mở terminal tại thư mục dự án:
```powershell
composer install
```

### Bước 3: Cấu hình cơ sở dữ liệu
1. Mở XAMPP, Start **Apache** và **MySQL**.
2. Mở phpMyAdmin (`http://localhost/phpmyadmin/`).
3. Import file `database/bookstore.sql`. File sẽ tự động tạo database `bookstore` và 2 bảng `thanhvien`, `quantri` kèm dữ liệu mẫu.
4. Sao chép file cấu hình môi trường:
   ```powershell
   copy .env.example .env
   ```
5. Kiểm tra thông tin kết nối DB trong `.env` (mặc định: `localhost`, database `bookstore`, user `root`, mật khẩu trống).

### Bước 4: Chạy ứng dụng
Mở trình duyệt truy cập:
```text
http://localhost/member-account-ci4/public/
```

- Form đăng ký: `http://localhost/member-account-ci4/public/dang-ky`
- Form đăng nhập: `http://localhost/member-account-ci4/public/dang-nhap`
- Trang quản trị: `http://localhost/member-account-ci4/public/quan-tri/dang-nhap`

---

## 📁 Cấu trúc thư mục

```text
member-account-ci4/
├── app/
│   ├── Config/
│   │   ├── Database.php      # Cấu hình kết nối MySQL
│   │   ├── Filters.php       # Khai báo middleware (CSRF, MemberAuth, AdminAuth)
│   │   └── Routes.php        # Danh sách định tuyến URL
│   ├── Controllers/
│   │   ├── Auth.php          # Quản lý đăng nhập/đăng xuất Admin
│   │   ├── TaiKhoan.php      # Đăng ký, đăng nhập, hồ sơ, avatar Thành viên
│   │   └── ThanhVien.php     # CRUD thành viên (Admin)
│   ├── Filters/
│   │   ├── AdminAuth.php     # Filter bảo vệ route Quản trị
│   │   └── MemberAuth.php    # Filter bảo vệ route Thành viên
│   ├── Models/
│   │   ├── QuanTriModel.php
│   │   └── ThanhVienModel.php
│   └── Views/
│       ├── auth/             # Giao diện đăng nhập admin
│       ├── taikhoan/         # Giao diện đăng ký, đăng nhập, hồ sơ cá nhân
│       └── thanhvien/        # Giao diện danh sách và form thêm/sửa thành viên
├── database/
│   └── bookstore.sql         # File script SQL tạo CSDL và dữ liệu mẫu
├── public/
│   ├── index.php             # Front controller điểm vào của ứng dụng
│   └── css/members.css       # File định kiểu giao diện
├── writable/
│   └── uploads/              # Thư mục lưu trữ ảnh đại diện riêng tư
├── .env.example              # Mẫu cấu hình môi trường
├── composer.json             # Khai báo thư viện và PSR-4 autoload
└── README.md                 # Tài liệu hướng dẫn dự án
```
