-- Database: bookstore
-- Chạy trên MariaDB/MySQL (XAMPP)

CREATE DATABASE IF NOT EXISTS `bookstore` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bookstore`;

-- --------------------------------------------------------
-- Cấu trúc bảng `thanhvien` (Tài khoản người dùng)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `thanhvien` (
  `username` VARCHAR(50) NOT NULL PRIMARY KEY,
  `matkhau` VARCHAR(255) NOT NULL,
  `hoten` VARCHAR(100) NOT NULL,
  `dienthoai` VARCHAR(15) DEFAULT NULL,
  `hinhdaidien` VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Cấu trúc bảng `quantri` (Tài khoản quản trị viên)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `quantri` (
  `user_admin` VARCHAR(50) NOT NULL PRIMARY KEY,
  `matkhau` VARCHAR(255) NOT NULL,
  `hoten` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dữ liệu mẫu (Mật khẩu mặc định là: 123456)
-- --------------------------------------------------------
INSERT INTO `thanhvien` (`username`, `matkhau`, `hoten`, `dienthoai`, `hinhdaidien`) VALUES
('nguyenan', '$2y$12$DIhdF85vtDTUULblDcdg/O8.UDFcB25rFpQvmbGDlbN4n.KCdQ94O', 'Nguyễn Văn An', '0912345678', NULL),
('tranbinh', '$2y$12$DIhdF85vtDTUULblDcdg/O8.UDFcB25rFpQvmbGDlbN4n.KCdQ94O', 'Trần Thị Bình', '0987654321', NULL)
ON DUPLICATE KEY UPDATE `hoten` = VALUES(`hoten`);

INSERT INTO `quantri` (`user_admin`, `matkhau`, `hoten`) VALUES
('admin', '$2y$12$DIhdF85vtDTUULblDcdg/O8.UDFcB25rFpQvmbGDlbN4n.KCdQ94O', 'Quản Trị Viên')
ON DUPLICATE KEY UPDATE `hoten` = VALUES(`hoten`);
