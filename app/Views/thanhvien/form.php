<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $isEdit ? 'Sửa thành viên' : 'Thêm thành viên' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('css/members.css') ?>" rel="stylesheet">
</head>
<body>
<div class="workspace">
    <aside class="sidebar">
        <a class="brand" href="<?= site_url('thanhvien') ?>"><span class="brand-mark">TV</span><span>Quản lý thành viên</span></a>
        <div class="side-caption">DANH MỤC</div>
        <a class="nav-link active" href="<?= site_url('thanhvien') ?>">Thành viên</a>
        <div class="sidebar-bottom">
            <div class="admin-name"><?= esc(session('admin')['name'] ?? 'Quản trị viên') ?></div>
            <div class="admin-role">Quản trị</div>
            <form action="<?= site_url('quan-tri/dang-xuat') ?>" method="post" class="mt-2 mb-3">
                <?= csrf_field() ?>
                <button class="btn btn-outline-secondary btn-sm w-100" type="submit">Đăng xuất</button>
            </form>
            <a href="<?= site_url('thanhvien') ?>" class="back-link">← Quay lại danh sách</a>
        </div>
    </aside>
    <main class="main-content form-content">
        <header class="page-header"><div><h1><?= $isEdit ? 'Sửa thành viên' : 'Thêm thành viên' ?></h1><p><?= $isEdit ? 'Cập nhật thông tin thành viên.' : 'Thêm hồ sơ thành viên mới.' ?></p></div><a class="btn btn-outline-secondary" href="<?= site_url('thanhvien') ?>">Hủy</a></header>
        <?php if (! empty($errors['general'])): ?><div class="alert alert-danger"><?= esc($errors['general']) ?></div><?php endif; ?>
        <section class="form-panel">
            <form method="post" enctype="multipart/form-data" action="<?= $isEdit ? site_url('thanhvien/cap-nhat/' . rawurlencode($member['username'])) : site_url('thanhvien') ?>">
                <?= csrf_field() ?>
                <div class="form-section-title">Thông tin thành viên</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="username">Tên đăng nhập</label>
                        <input class="form-control" id="username" name="username" maxlength="50" value="<?= old('username', $member['username'] ?? '') ?>" <?= $isEdit ? 'readonly' : 'required' ?>>
                        <?php if (! empty($errors['username'])): ?><div class="field-error"><?= esc($errors['username']) ?></div><?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="hoten">Họ và tên</label>
                        <input class="form-control" id="hoten" name="hoten" maxlength="100" value="<?= old('hoten', $member['hoten'] ?? '') ?>" required>
                        <?php if (! empty($errors['hoten'])): ?><div class="field-error"><?= esc($errors['hoten']) ?></div><?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="dienthoai">Số điện thoại</label>
                        <input class="form-control" id="dienthoai" name="dienthoai" maxlength="15" value="<?= old('dienthoai', $member['dienthoai'] ?? '') ?>" placeholder="Ví dụ: 0912345678" required>
                        <?php if (! empty($errors['dienthoai'])): ?><div class="field-error"><?= esc($errors['dienthoai']) ?></div><?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="matkhau">Mật khẩu <?= $isEdit ? '(để trống nếu không đổi)' : '' ?></label>
                        <input class="form-control" id="matkhau" name="matkhau" type="password" minlength="6" maxlength="255" autocomplete="new-password" <?= $isEdit ? '' : 'required' ?>>
                        <?php if (! empty($errors['matkhau'])): ?><div class="field-error"><?= esc($errors['matkhau']) ?></div><?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="hinhdaidien">Ảnh đại diện</label>
                        <input class="form-control" id="hinhdaidien" name="hinhdaidien" type="file" accept="image/jpeg,image/png,image/gif,image/webp">
                        <div class="form-text">JPG, PNG, GIF hoặc WebP · tối đa 2 MB</div>
                        <?php if (! empty($errors['hinhdaidien'])): ?><div class="field-error"><?= esc($errors['hinhdaidien']) ?></div><?php endif; ?>
                    </div>
                    <?php if ($isEdit && ! empty($member['hinhdaidien'])): ?>
                        <div class="col-12 current-photo"><img class="avatar avatar-large" src="<?= site_url('anh/' . rawurlencode($member['hinhdaidien'])) ?>" alt="Ảnh đại diện hiện tại"><span>Ảnh hiện tại · tải ảnh mới để thay thế</span></div>
                    <?php endif; ?>
                </div>
                <div class="form-actions"><a class="btn btn-outline-secondary" href="<?= site_url('thanhvien') ?>">Hủy</a><button class="btn btn-dark" type="submit"><?= $isEdit ? 'Lưu thay đổi' : 'Tạo thành viên' ?></button></div>
            </form>
        </section>
    </main>
</div>
</body>
</html>
