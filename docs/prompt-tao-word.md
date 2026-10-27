# PROMPT: Tạo file Word (.docx) — Bài thực hành Quản lý tài khoản thành viên bằng CodeIgniter 4

## YÊU CẦU TỔNG QUÁT

Tạo file `.docx` theo đúng bố cục tài liệu hướng dẫn thực hành (Hands-on Lab) gồm 10 phần chính + bìa + mục lục. Ngôn ngữ: **tiếng Việt**. Font: Times New Roman 13pt (nội dung), 16pt bold (heading 1), 14pt bold (heading 2), 13pt bold italic (heading 3). Lề: trên/dưới 2.5cm, trái 3cm, phải 2cm. Line spacing 1.5. Tên file đầu ra: `Hands-on-07-CodeIgniter.docx`.

---

## TRANG BÌA

```
BỘ GIÁO DỤC VÀ ĐÀO TẠO
TRƯỜNG ĐẠI HỌC SƯ PHẠM KỸ THUẬT TP. HỒ CHÍ MINH
KHOA CÔNG NGHỆ THÔNG TIN

-----

BÀI THỰC HÀNH SỐ 7

QUẢN LÝ TÀI KHOẢN THÀNH VIÊN
VỚI CODEIGNITER 4

Môn học: Lập trình web
Nhóm: [Điền nhóm]
Lớp: [Điền lớp]

Danh sách thành viên:
| STT | Họ và tên          | MSSV        |
|-----|--------------------|-------------|
| 1   | Phan Thanh Phúc    | 110123160   |
| 2   | [Điền thành viên]  | [Điền MSSV] |
| 3   | [Điền thành viên]  | [Điền MSSV] |

TP. Hồ Chí Minh, tháng 10 năm 2026
```

Sau trang bìa: thêm **MỤC LỤC** tự động (Table of Contents dựa trên heading).

---

## NỘI DUNG CÁC PHẦN

### 1. GIỚI THIỆU VÀ MỤC TIÊU

#### 1.1. Phạm vi
Xây dựng ứng dụng web **Quản lý tài khoản thành viên** sử dụng framework **CodeIgniter 4** (PHP), áp dụng mô hình **MVC** (Model–View–Controller). Ứng dụng cho phép:
- Đăng ký thành viên (username, họ tên, số điện thoại, mật khẩu).
- Đăng nhập / Đăng xuất (thành viên và quản trị viên).
- Quên mật khẩu — xác minh qua số điện thoại đã đăng ký.
- Cập nhật hồ sơ (họ tên, số điện thoại, ảnh đại diện, đổi mật khẩu yêu cầu nhập mật khẩu cũ).
- Quản trị viên: CRUD (Thêm/Sửa/Xóa/Tìm kiếm) thành viên.
- Bảo vệ route bằng Filter, mã hóa mật khẩu bằng `password_hash()`/`password_verify()`, bảo vệ CSRF.

#### 1.2. Kết quả đầu ra
- Trang đăng ký thành viên (4 trường: username, họ tên, số điện thoại, mật khẩu).
- Trang đăng nhập thành viên với link quên mật khẩu.
- Trang quên mật khẩu (xác minh username + số điện thoại, đặt mật khẩu mới).
- Trang hồ sơ cá nhân (cập nhật thông tin, upload ảnh đại diện, đổi mật khẩu).
- Trang đăng nhập quản trị.
- Trang danh sách thành viên (admin — bảng có cột: Thành viên, Số điện thoại, Thao tác; tìm kiếm theo tên/username/SĐT).
- Trang thêm/sửa thành viên (admin).

#### 1.3. Luồng xử lý MVC (vẽ sơ đồ đơn giản dạng text)

```
Browser → Routes (app/Config/Routes.php)
       → Filter (MemberAuth / AdminAuth / CSRF)
       → Controller (TaiKhoan / ThanhVien / Auth)
       → Model (ThanhVienModel / QuanTriModel)
       → Database (MySQL – bookstore)
       → View (taikhoan/*.php / thanhvien/*.php / auth/*.php)
       → Browser
```

---

### 2. CHUẨN BỊ MÔI TRƯỜNG VÀ KIỂM TRA CÀI ĐẶT

#### 2.1. Yêu cầu phần mềm
| Phần mềm       | Phiên bản           | Ghi chú                              |
|-----------------|---------------------|---------------------------------------|
| PHP             | 8.4.x               | Cần bật extension: intl, mbstring, json, mysqlnd |
| Composer        | 2.x                 | Quản lý thư viện PHP                  |
| MySQL/MariaDB   | 10.x (XAMPP)        | Cơ sở dữ liệu                        |
| XAMPP            | 8.x                 | Tích hợp Apache + MySQL               |
| Git             | 2.x                 | Quản lý phiên bản                     |
| CodeIgniter 4   | 4.7.5               | Framework PHP MVC                     |
| Bootstrap       | 5.3.3               | Giao diện frontend (CDN)              |

#### 2.2. Kiểm tra cài đặt
```bash
php -v
# Output mong đợi: PHP 8.4.x

composer --version

mysql --version
```

---

### 3. CÀI ĐẶT VÀ KHỞI TẠO DỰ ÁN

#### 3.1. Clone dự án từ GitHub
```bash
cd C:\xampp\htdocs
git clone https://github.com/phhuynhphuc/member-account-ci4.git
cd member-account-ci4
```

#### 3.2. Cài đặt thư viện
```bash
composer install
```

#### 3.3. Cấu hình file .env
```
# Copy file env mẫu
cp env .env
```
Mở file `.env` và chỉnh sửa:
```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost/member-account-ci4/public/'

database.default.hostname = 127.0.0.1
database.default.database = bookstore
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.charset  = utf8mb4
```

#### 3.4. Chạy ứng dụng
```
Truy cập: http://localhost/member-account-ci4/public/
```
Hoặc dùng PHP built-in server:
```bash
php spark serve
# Truy cập: http://localhost:8080
```

---

### 4. THIẾT KẾ DỮ LIỆU VÀ CẤU HÌNH KẾT NỐI

#### 4.1. Sơ đồ bảng dữ liệu

**Bảng `thanhvien`** — Tài khoản người dùng:

| Cột           | Kiểu dữ liệu    | Ràng buộc                  | Mô tả                  |
|---------------|------------------|----------------------------|-------------------------|
| `username`    | VARCHAR(50)      | PRIMARY KEY, NOT NULL      | Tên đăng nhập (khóa chính) |
| `matkhau`     | VARCHAR(255)     | NOT NULL                   | Mật khẩu đã hash (bcrypt) |
| `hoten`       | VARCHAR(100)     | NOT NULL                   | Họ và tên               |
| `dienthoai`   | VARCHAR(15)      | DEFAULT NULL               | Số điện thoại           |
| `hinhdaidien` | VARCHAR(255)     | DEFAULT NULL               | Tên file ảnh đại diện   |

**Bảng `quantri`** — Tài khoản quản trị viên:

| Cột           | Kiểu dữ liệu    | Ràng buộc                  | Mô tả                  |
|---------------|------------------|----------------------------|-------------------------|
| `user_admin`  | VARCHAR(50)      | PRIMARY KEY, NOT NULL      | Tên đăng nhập admin     |
| `matkhau`     | VARCHAR(255)     | NOT NULL                   | Mật khẩu đã hash        |
| `hoten`       | VARCHAR(100)     | NOT NULL                   | Họ và tên admin          |

#### 4.2. Tạo cơ sở dữ liệu

```sql
CREATE DATABASE IF NOT EXISTS `bookstore`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE `bookstore`;

CREATE TABLE IF NOT EXISTS `thanhvien` (
  `username` VARCHAR(50) NOT NULL PRIMARY KEY,
  `matkhau` VARCHAR(255) NOT NULL,
  `hoten` VARCHAR(100) NOT NULL,
  `dienthoai` VARCHAR(15) DEFAULT NULL,
  `hinhdaidien` VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `quantri` (
  `user_admin` VARCHAR(50) NOT NULL PRIMARY KEY,
  `matkhau` VARCHAR(255) NOT NULL,
  `hoten` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu mẫu (mật khẩu mặc định: 123456)
INSERT INTO `thanhvien` (`username`, `matkhau`, `hoten`, `dienthoai`) VALUES
('nguyenan', '$2y$12$DIhdF85vtDTUULblDcdg/O8.UDFcB25rFpQvmbGDlbN4n.KCdQ94O', 'Nguyễn Văn An', '0912345678'),
('tranbinh', '$2y$12$DIhdF85vtDTUULblDcdg/O8.UDFcB25rFpQvmbGDlbN4n.KCdQ94O', 'Trần Thị Bình', '0987654321')
ON DUPLICATE KEY UPDATE `hoten` = VALUES(`hoten`);

INSERT INTO `quantri` (`user_admin`, `matkhau`, `hoten`) VALUES
('admin', '$2y$12$DIhdF85vtDTUULblDcdg/O8.UDFcB25rFpQvmbGDlbN4n.KCdQ94O', 'Quản Trị Viên')
ON DUPLICATE KEY UPDATE `hoten` = VALUES(`hoten`);
```

#### 4.3. Cấu hình kết nối database
File `app/Config/Database.php` hoặc `.env`:
```ini
database.default.hostname = 127.0.0.1
database.default.database = bookstore
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.charset  = utf8mb4
```

---

### 5. TẠO MODEL

#### 5.1. ThanhVienModel (`app/Models/ThanhVienModel.php`)

```php
<?php

namespace App\Models;

use CodeIgniter\Model;

class ThanhVienModel extends Model
{
    protected $table = 'thanhvien';
    protected $primaryKey = 'username';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $protectFields = true;
    protected $allowedFields = ['username', 'matkhau', 'hoten', 'dienthoai', 'hinhdaidien'];

    public function findForLogin(string $username): ?array
    {
        return $this->select('username, matkhau')
            ->where('username', $username)
            ->first();
    }

    public function findForReset(string $username, string $phone): ?array
    {
        return $this->select('username, dienthoai')
            ->where('username', $username)
            ->where('dienthoai', $phone)
            ->first();
    }

    public function listing(string $search = ''): array
    {
        return $this->select('username, hoten, dienthoai, hinhdaidien')
            ->groupStart()
                ->like('username', $search)
                ->orLike('hoten', $search)
                ->orLike('dienthoai', $search)
            ->groupEnd()
            ->orderBy('hoten', 'ASC')
            ->findAll();
    }
}
```

**Giải thích:**
- `$primaryKey = 'username'` — Khóa chính là chuỗi, không auto-increment.
- `findForLogin()` — Truy vấn chỉ lấy username và mật khẩu hash để xác thực.
- `findForReset()` — Tìm thành viên theo username + số điện thoại (dùng cho quên mật khẩu).
- `listing()` — Danh sách thành viên có tìm kiếm, dùng `groupStart()`/`groupEnd()` cho điều kiện OR.

#### 5.2. QuanTriModel (tương tự, bảng `quantri`, primaryKey `user_admin`)
```php
// app/Models/QuanTriModel.php
class QuanTriModel extends Model
{
    protected $table = 'quantri';
    protected $primaryKey = 'user_admin';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['user_admin', 'matkhau', 'hoten'];

    public function findForLogin(string $username): ?array
    {
        return $this->select('user_admin, matkhau, hoten')
            ->where('user_admin', $username)->first();
    }
}
```

---

### 6. XÂY DỰNG CÁC CHỨC NĂNG THEO MVC

#### 6.1. Định tuyến (Routes) — `app/Config/Routes.php`

```php
<?php
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', static fn () => redirect()->to(site_url('dang-nhap')));

// === Thành viên (public) ===
$routes->get('dang-ky', 'TaiKhoan::register');
$routes->post('dang-ky', 'TaiKhoan::store', ['filter' => 'csrf']);
$routes->get('dang-nhap', 'TaiKhoan::login');
$routes->post('dang-nhap', 'TaiKhoan::login', ['filter' => 'csrf']);
$routes->get('quen-mat-khau', 'TaiKhoan::forgotPassword');
$routes->post('quen-mat-khau', 'TaiKhoan::resetPassword', ['filter' => 'csrf']);

// === Thành viên (yêu cầu đăng nhập) ===
$routes->get('tai-khoan', 'TaiKhoan::profile', ['filter' => 'memberauth']);
$routes->post('tai-khoan', 'TaiKhoan::update', ['filter' => ['memberauth', 'csrf']]);
$routes->get('anh/(:segment)', 'TaiKhoan::avatar/$1');
$routes->post('dang-xuat', 'TaiKhoan::logout', ['filter' => ['memberauth', 'csrf']]);

// === Quản trị ===
$routes->get('quan-tri/dang-nhap', 'Auth::login');
$routes->post('quan-tri/dang-nhap', 'Auth::login', ['filter' => 'csrf']);
$routes->post('quan-tri/dang-xuat', 'Auth::logout', ['filter' => ['adminauth', 'csrf']]);

$routes->group('thanhvien', ['filter' => 'adminauth'], static function ($routes) {
    $routes->get('/', 'ThanhVien::index');
    $routes->get('them', 'ThanhVien::create');
    $routes->post('/', 'ThanhVien::store', ['filter' => 'csrf']);
    $routes->post('them', 'ThanhVien::store', ['filter' => 'csrf']);
    $routes->get('sua/(:segment)', 'ThanhVien::edit/$1');
    $routes->post('cap-nhat/(:segment)', 'ThanhVien::update/$1', ['filter' => 'csrf']);
    $routes->post('xoa/(:segment)', 'ThanhVien::delete/$1', ['filter' => 'csrf']);
});
```

**Giải thích:** Route sử dụng URL tiếng Việt (dang-ky, dang-nhap, quen-mat-khau…). Filter `memberauth` bảo vệ route thành viên, `adminauth` bảo vệ route quản trị, `csrf` bảo vệ mọi POST request.

#### 6.2. Filter xác thực

**MemberAuth** (`app/Filters/MemberAuth.php`):
```php
<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class MemberAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session('member')) {
            return redirect()->to(site_url('dang-nhap'))
                ->with('error', 'Vui lòng đăng nhập để xem hồ sơ.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
```

**AdminAuth** (`app/Filters/AdminAuth.php`): Tương tự, kiểm tra `session('admin')`, redirect đến `quan-tri/dang-nhap`.

Đăng ký filter trong `app/Config/Filters.php`:
```php
public array $aliases = [
    'csrf'       => \CodeIgniter\Filters\CSRF::class,
    'memberauth' => \App\Filters\MemberAuth::class,
    'adminauth'  => \App\Filters\AdminAuth::class,
];
```

#### 6.3. Controller TaiKhoan (`app/Controllers/TaiKhoan.php`)

##### 6.3.1. Đăng ký — `store()`
```php
public function store()
{
    $rules = [
        'username'  => 'required|alpha_numeric_punct|max_length[50]|is_unique[thanhvien.username]',
        'hoten'     => 'required|regex_match[/\S/]|max_length[100]',
        'dienthoai' => 'required|regex_match[/^[0-9]{10,11}$/]',
        'matkhau'   => 'required|min_length[6]|max_length[255]',
    ];

    if (! $this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $model = new ThanhVienModel();
    $model->insert([
        'username'    => trim($this->request->getPost('username')),
        'matkhau'     => password_hash($this->request->getPost('matkhau'), PASSWORD_DEFAULT),
        'hoten'       => trim($this->request->getPost('hoten')),
        'dienthoai'   => trim($this->request->getPost('dienthoai')),
        'hinhdaidien' => null,
    ]);

    return redirect()->to(site_url('dang-nhap'))->with('success', 'Đăng ký thành công.');
}
```

**Giải thích:**
- Validate: username unique, SĐT 10–11 chữ số, mật khẩu tối thiểu 6 ký tự.
- `password_hash()` mã hóa bcrypt trước khi lưu.
- Đăng ký không yêu cầu ảnh đại diện (upload sau ở trang hồ sơ).

##### 6.3.2. Đăng nhập — `login()`
```php
public function login()
{
    if (session('member')) {
        return redirect()->to(site_url('tai-khoan'));
    }

    if ($this->request->is('post')) {
        // validate...
        $member = (new ThanhVienModel())->findForLogin($username);
        if ($member && password_verify($password, $member['matkhau'])) {
            session()->regenerate(true);  // chống session fixation
            session()->set('member', ['username' => $member['username']]);
            return redirect()->to(site_url('tai-khoan'));
        }
        $error = 'Tên đăng nhập hoặc mật khẩu không đúng.';
    }

    return view('taikhoan/login', compact('errors', 'error'));
}
```

**Giải thích:** `session()->regenerate(true)` tạo session ID mới, chống tấn công session fixation. `password_verify()` so sánh mật khẩu nhập với hash trong DB.

##### 6.3.3. Quên mật khẩu — `resetPassword()`
```php
public function resetPassword()
{
    $rules = [
        'username'        => 'required|max_length[50]',
        'dienthoai'       => 'required|regex_match[/^[0-9]{10,11}$/]',
        'matkhau_moi'     => 'required|min_length[6]|max_length[255]',
        'matkhau_xacnhan' => 'required|matches[matkhau_moi]',
    ];

    if (! $this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $member = (new ThanhVienModel())->findForReset($username, $dienthoai);
    if (! $member) {
        return redirect()->back()->withInput()
            ->with('error', 'Tên đăng nhập hoặc số điện thoại không chính xác.');
    }

    $model->update($username, [
        'matkhau' => password_hash($newPassword, PASSWORD_DEFAULT),
    ]);

    return redirect()->to(site_url('dang-nhap'))
        ->with('success', 'Đặt lại mật khẩu thành công.');
}
```

**Giải thích:** Xác minh danh tính qua username + SĐT đã đăng ký. Mật khẩu mới phải nhập 2 lần khớp nhau (`matches[matkhau_moi]`).

##### 6.3.4. Cập nhật hồ sơ — `update()`
```php
public function update()
{
    // ... validate hoten, dienthoai, hinhdaidien (ảnh), matkhau_cu, matkhau ...

    $password = $this->request->getPost('matkhau');
    $oldPassword = $this->request->getPost('matkhau_cu');

    if ($password !== '') {
        if ($oldPassword === '') {
            return redirect()->back()->withInput()
                ->with('errors', ['matkhau_cu' => 'Vui lòng nhập mật khẩu cũ để đổi mật khẩu mới.']);
        }
        if (! password_verify($oldPassword, $member['matkhau'])) {
            return redirect()->back()->withInput()
                ->with('errors', ['matkhau_cu' => 'Mật khẩu cũ không chính xác.']);
        }
    }

    // Upload ảnh đại diện (lưu vào WRITEPATH/uploads, ngoài webroot)
    $avatar = $this->saveAvatar($this->request->getFile('hinhdaidien'));
    // ... update model ...
}
```

**Giải thích:** Đổi mật khẩu bắt buộc nhập đúng mật khẩu cũ. Ảnh upload lưu ngoài thư mục public (bảo mật), phục vụ qua controller `avatar()` với kiểm tra MIME type.

##### 6.3.5. Phục vụ ảnh đại diện — `avatar()`
```php
public function avatar(string $filename)
{
    // Kiểm tra filename hợp lệ (chỉ cho phép ký tự an toàn + đuôi ảnh)
    // Kiểm tra file tồn tại trong WRITEPATH/uploads
    // Kiểm tra MIME type (chỉ cho phép image/jpeg, png, gif, webp)
    // Trả về nội dung ảnh với header Content-Type, X-Content-Type-Options: nosniff
}
```

#### 6.4. Controller Auth — Đăng nhập quản trị (`app/Controllers/Auth.php`)

```php
class Auth extends BaseController
{
    public function login()
    {
        if (session('admin')) {
            return redirect()->to(site_url('thanhvien'));
        }
        if ($this->request->is('post')) {
            $admin = (new QuanTriModel())->findForLogin($username);
            if ($admin && password_verify($password, $admin['matkhau'])) {
                session()->regenerate(true);
                session()->set('admin', [
                    'username' => $admin['user_admin'],
                    'name'     => $admin['hoten'],
                ]);
                return redirect()->to(site_url('thanhvien'));
            }
        }
        return view('auth/login', compact('errors', 'error'));
    }

    public function logout()
    {
        session()->remove('admin');
        session()->regenerate(true);
        return redirect()->to(site_url('quan-tri/dang-nhap'));
    }
}
```

#### 6.5. Controller ThanhVien — CRUD quản trị (`app/Controllers/ThanhVien.php`)

| Method       | HTTP     | Route                         | Chức năng                   |
|--------------|----------|-------------------------------|-----------------------------|
| `index()`    | GET      | `/thanhvien`                  | Danh sách + tìm kiếm        |
| `create()`   | GET      | `/thanhvien/them`             | Form thêm thành viên        |
| `store()`    | POST     | `/thanhvien`                  | Lưu thành viên mới          |
| `edit($u)`   | GET      | `/thanhvien/sua/{username}`   | Form sửa thành viên         |
| `update($u)` | POST     | `/thanhvien/cap-nhat/{username}` | Cập nhật thành viên       |
| `delete($u)` | POST     | `/thanhvien/xoa/{username}`   | Xóa thành viên              |

Validate: username unique (khi thêm), họ tên bắt buộc, SĐT 10–11 số, ảnh max 2MB (jpeg/png/gif/webp), mật khẩu min 6 ký tự.

#### 6.6. Views

##### 6.6.1. Trang đăng ký (`app/Views/taikhoan/register.php`)
- Layout: `auth-panel` (căn giữa, dạng card form).
- Các trường: Tên đăng nhập, Họ và tên, Số điện thoại (type="tel"), Mật khẩu.
- Hiển thị lỗi validation bên dưới mỗi trường (`field-error`).
- Link chuyển sang đăng nhập.
- CSS: Bootstrap 5.3.3 + custom `members.css`.

##### 6.6.2. Trang đăng nhập (`app/Views/taikhoan/login.php`)
- Các trường: Tên đăng nhập, Mật khẩu.
- Link: "Quên mật khẩu?" và "Đăng ký".
- Hiển thị thông báo flashdata (success/error).

##### 6.6.3. Trang quên mật khẩu (`app/Views/taikhoan/forgot.php`)
- Các trường: Tên đăng nhập, Số điện thoại đăng ký, Mật khẩu mới, Xác nhận mật khẩu mới.
- Link quay lại đăng nhập.

##### 6.6.4. Trang hồ sơ (`app/Views/taikhoan/profile.php`)
- Layout: sidebar (Tài khoản) + main content.
- Sidebar: logo, tên username, nút Đăng xuất.
- Form hiển thị: ảnh đại diện (hoặc chữ cái đầu), username (readonly), họ tên, SĐT, upload ảnh mới, mật khẩu hiện tại, mật khẩu mới.
- `enctype="multipart/form-data"` cho upload file.

##### 6.6.5. Trang đăng nhập quản trị (`app/Views/auth/login.php`)
- Tương tự trang đăng nhập thành viên, trường `user_admin` thay vì `username`.

##### 6.6.6. Trang danh sách thành viên — Admin (`app/Views/thanhvien/index.php`)
- Layout: sidebar (Quản lý) + main content.
- Sidebar: logo, menu "Thành viên", tên admin, nút Đăng xuất.
- Thanh tìm kiếm (GET `?q=`).
- Bảng: cột Thành viên (avatar + họ tên + @username), Số điện thoại, Thao tác (Sửa/Xóa).
- Nút "Thêm thành viên".
- Xóa có confirm JS.

##### 6.6.7. Form thêm/sửa thành viên — Admin (`app/Views/thanhvien/form.php`)
- Dùng chung 1 view: biến `$isEdit` phân biệt thêm/sửa.
- Khi sửa: username readonly, mật khẩu không bắt buộc, hiển thị ảnh hiện tại.
- Các trường: username, họ tên, SĐT, mật khẩu, ảnh đại diện (file upload).

---

### 7. CÁC LỖI THƯỜNG GẶP

| STT | Lỗi                                               | Nguyên nhân                                         | Cách khắc phục                                          |
|-----|----------------------------------------------------|-----------------------------------------------------|---------------------------------------------------------|
| 1   | `Unknown column 'dienthoai' in 'field list'`       | Bảng DB chưa có cột `dienthoai`                    | Chạy `ALTER TABLE thanhvien ADD COLUMN dienthoai VARCHAR(15)` |
| 2   | `Class 'App\Models\ThanhVienModel' not found`       | Namespace hoặc tên file không khớp                  | Kiểm tra tên file/class phải trùng khớp, chạy `composer dump-autoload` |
| 3   | `The action you requested is not allowed` (CSRF)    | Token CSRF hết hạn hoặc thiếu `csrf_field()`        | Thêm `<?= csrf_field() ?>` vào mọi form POST            |
| 4   | Ảnh đại diện không hiển thị                         | File upload ngoài webroot, route `anh/` chưa đúng   | Kiểm tra route `anh/(:segment)` trỏ đến `TaiKhoan::avatar` |
| 5   | Redirect loop khi đăng nhập                         | `baseURL` trong `.env` sai đường dẫn                | Sửa `app.baseURL` đúng: `http://localhost/member-account-ci4/public/` |
| 6   | `mb_strtoupper()` lỗi encoding                     | PHP chưa bật extension `mbstring` hoặc `intl`       | Bật `extension=mbstring` và `extension=intl` trong `php.ini` |
| 7   | Password verify luôn false                          | `password_hash()` dùng hằng sai hoặc cột DB quá ngắn | Cột `matkhau` phải VARCHAR(255), dùng `PASSWORD_DEFAULT` |

---

### 8. GITHUB VÀ TỔNG KẾT

#### 8.1. Link GitHub
- Repository: `https://github.com/phhuynhphuc/member-account-ci4`
- README: mô tả cài đặt, cấu trúc thư mục, hướng dẫn chạy.

#### 8.2. Cấu trúc thư mục chính
```
member-account-ci4/
├── app/
│   ├── Config/
│   │   ├── Routes.php          ← Định tuyến URL
│   │   ├── Filters.php         ← Đăng ký filter
│   │   └── Database.php        ← Cấu hình DB
│   ├── Controllers/
│   │   ├── TaiKhoan.php        ← Đăng ký/Đăng nhập/Hồ sơ/Quên MK
│   │   ├── ThanhVien.php       ← CRUD admin
│   │   └── Auth.php            ← Đăng nhập admin
│   ├── Filters/
│   │   ├── MemberAuth.php      ← Bảo vệ route thành viên
│   │   └── AdminAuth.php       ← Bảo vệ route quản trị
│   ├── Models/
│   │   ├── ThanhVienModel.php  ← Model thành viên
│   │   └── QuanTriModel.php    ← Model quản trị
│   └── Views/
│       ├── taikhoan/
│       │   ├── register.php    ← Form đăng ký
│       │   ├── login.php       ← Form đăng nhập
│       │   ├── forgot.php      ← Form quên mật khẩu
│       │   └── profile.php     ← Trang hồ sơ cá nhân
│       ├── thanhvien/
│       │   ├── index.php       ← Danh sách (admin)
│       │   └── form.php        ← Thêm/sửa (admin)
│       └── auth/
│           └── login.php       ← Đăng nhập admin
├── database/
│   └── bookstore.sql           ← Script tạo DB
├── public/
│   ├── index.php               ← Entry point
│   └── css/members.css         ← Custom CSS
├── writable/uploads/           ← Ảnh đại diện (ngoài webroot)
└── .env                        ← Cấu hình môi trường
```

#### 8.3. Kết luận
Dự án đã xây dựng thành công ứng dụng quản lý tài khoản thành viên với CodeIgniter 4, áp dụng:
- Mô hình MVC rõ ràng (Model xử lý DB, Controller xử lý logic, View hiển thị).
- Xác thực và phân quyền bằng Session + Filter.
- Bảo mật: mã hóa mật khẩu bcrypt, CSRF token, session regeneration, upload file ngoài webroot với kiểm tra MIME.
- Quên mật khẩu qua xác minh số điện thoại.
- Đổi mật khẩu bắt buộc nhập mật khẩu cũ.
- Giao diện responsive với Bootstrap 5.

#### 8.4. Giới hạn
- Quên mật khẩu chỉ xác minh SĐT, chưa gửi OTP thực tế.
- Chưa có phân trang cho danh sách thành viên.
- Chưa tích hợp gửi email xác nhận đăng ký.

---

### 9. PHỤ LỤC A. PHIẾU THÔNG TIN NHÓM

```
PHIẾU THÔNG TIN NHÓM
Nhóm: [Điền số nhóm]        Lớp: [Điền lớp]

BẢNG PHÂN CÔNG CÔNG VIỆC:
| STT | Họ và tên         | MSSV        | Công việc                                   | Hoàn thành |
|-----|-------------------|-------------|---------------------------------------------|------------|
| 1   | Phan Thanh Phúc   | 110123160   | Thiết kế DB, xây dựng Model, Controller     | 100%       |
| 2   | [Điền]            | [Điền]      | [Mô tả công việc]                           | [%]        |
| 3   | [Điền]            | [Điền]      | [Mô tả công việc]                           | [%]        |
```

---

### 10. PHỤ LỤC B. NHẬT KÝ THỰC HÀNH

```
NHẬT KÝ THỰC HÀNH
| Buổi | Ngày       | Nội dung thực hiện                             | Ghi chú            |
|------|------------|------------------------------------------------|--------------------|
| 1    | [Điền]     | Khảo sát yêu cầu, thiết kế DB, CSDL bookstore | Hoàn thành         |
| 2    | [Điền]     | Xây dựng Model, Controller TaiKhoan, Auth      | Hoàn thành         |
| 3    | [Điền]     | Xây dựng Views, Controller ThanhVien (CRUD)     | Hoàn thành         |
| 4    | [Điền]     | Thêm quên mật khẩu, đổi MK, upload ảnh, test   | Hoàn thành         |
| 5    | [Điền]     | Soạn tài liệu, push GitHub, nộp bài            | Hoàn thành         |
```

---

## HƯỚNG DẪN ĐỊNH DẠNG DOCX

1. **Heading 1**: Tên phần (1. GIỚI THIỆU VÀ MỤC TIÊU, 2. CHUẨN BỊ MÔI TRƯỜNG…) — Times New Roman 16pt Bold, màu xanh đậm.
2. **Heading 2**: Tên mục con (1.1. Phạm vi, 6.3. Controller TaiKhoan…) — Times New Roman 14pt Bold.
3. **Heading 3**: Tên mục nhỏ (6.3.1. Đăng ký — `store()`…) — Times New Roman 13pt Bold Italic.
4. **Nội dung**: Times New Roman 13pt, line spacing 1.5.
5. **Code block**: Font Consolas hoặc Courier New 11pt, nền xám nhạt (#F2F2F2), viền 1pt.
6. **Bảng**: Có viền, header in đậm nền xám, nội dung canh trái.
7. **Trang bìa**: Căn giữa, không đánh số trang.
8. **Mục lục**: Tự động từ heading, sau trang bìa.
9. **Đánh số trang**: Bắt đầu từ trang nội dung (sau mục lục).

---

## LƯU Ý QUAN TRỌNG

- Toàn bộ nội dung viết bằng **tiếng Việt**.
- Code trong tài liệu phải **khớp chính xác** với mã nguồn đã cung cấp ở trên.
- Không thêm cột/trường nào không có trong schema (KHÔNG có giới tính, quốc gia).
- `[Điền]` là placeholder để người dùng tự điền sau.
- Tên file đầu ra: `Hands-on-07-CodeIgniter.docx`.
