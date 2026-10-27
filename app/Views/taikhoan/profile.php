<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hồ sơ thành viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('css/members.css') ?>" rel="stylesheet">
</head>
<body>
<div class="workspace">
    <aside class="sidebar">
        <a class="brand" href="<?= site_url('tai-khoan') ?>"><span class="brand-mark">TV</span><span>Tài khoản thành viên</span></a>
        <div class="side-caption">TÀI KHOẢN</div>
        <a class="nav-link active" href="<?= site_url('tai-khoan') ?>">Hồ sơ của tôi</a>
        <div class="sidebar-bottom">
            <div class="admin-name">@<?= esc($member['username']) ?></div>
            <div class="admin-role">Thành viên</div>
            <form action="<?= site_url('dang-xuat') ?>" method="post" class="mt-3">
                <?= csrf_field() ?>
                <button class="btn btn-outline-secondary btn-sm w-100" type="submit">Đăng xuất</button>
            </form>
        </div>
    </aside>
    <main class="main-content form-content">
        <header class="page-header"><div><h1>Hồ sơ của tôi</h1><p>Xem và cập nhật thông tin tài khoản thành viên.</p></div></header>
        <?php if ($message = session()->getFlashdata('success')): ?><div class="alert alert-success"><?= esc($message) ?></div><?php endif; ?>
        <?php if (! empty($errors['general'])): ?><div class="alert alert-danger"><?= esc($errors['general']) ?></div><?php endif; ?>
        <section class="form-panel">
            <form method="post" enctype="multipart/form-data" action="<?= site_url('tai-khoan') ?>">
                <?= csrf_field() ?>
                <div class="profile-heading">
                    <?php if (! empty($member['hinhdaidien'])): ?>
                        <img class="avatar avatar-large" src="<?= site_url('anh/' . rawurlencode($member['hinhdaidien'])) ?>" alt="Ảnh đại diện">
                    <?php else: ?>
                        <span class="avatar avatar-fallback avatar-large" aria-hidden="true"><?= esc(mb_strtoupper(mb_substr($member['hoten'], 0, 1))) ?></span>
                    <?php endif; ?>
                    <span><strong><?= esc($member['hoten']) ?></strong><small>@<?= esc($member['username']) ?></small></span>
                </div>
                <div class="form-section-title">Thông tin thành viên</div>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label" for="username">Tên đăng nhập</label><input class="form-control" id="username" value="<?= esc($member['username']) ?>" readonly></div>
                    <div class="col-md-6"><label class="form-label" for="hoten">Họ và tên</label><input class="form-control" id="hoten" name="hoten" maxlength="100" value="<?= old('hoten', $member['hoten']) ?>" required><?php if (! empty($errors['hoten'])): ?><div class="field-error"><?= esc($errors['hoten']) ?></div><?php endif; ?></div>
                    <div class="col-md-6"><label class="form-label" for="dienthoai">Số điện thoại</label><input class="form-control" id="dienthoai" name="dienthoai" maxlength="15" value="<?= old('dienthoai', $member['dienthoai'] ?? '') ?>" placeholder="Ví dụ: 0912345678" required><?php if (! empty($errors['dienthoai'])): ?><div class="field-error"><?= esc($errors['dienthoai']) ?></div><?php endif; ?></div>
                    <div class="col-md-6"><label class="form-label" for="hinhdaidien">Ảnh đại diện mới</label><input class="form-control" id="hinhdaidien" name="hinhdaidien" type="file" accept="image/jpeg,image/png,image/gif,image/webp"><div class="form-text">JPG, PNG, GIF hoặc WebP · tối đa 2 MB</div><?php if (! empty($errors['hinhdaidien'])): ?><div class="field-error"><?= esc($errors['hinhdaidien']) ?></div><?php endif; ?></div>
                    <div class="col-md-6"><label class="form-label" for="matkhau_cu">Mật khẩu hiện tại</label><input class="form-control" id="matkhau_cu" name="matkhau_cu" type="password" maxlength="255" autocomplete="current-password" placeholder="Bắt buộc nếu đổi mật khẩu"><?php if (! empty($errors['matkhau_cu'])): ?><div class="field-error"><?= esc($errors['matkhau_cu']) ?></div><?php endif; ?></div>
                    <div class="col-md-6"><label class="form-label" for="matkhau">Mật khẩu mới</label><input class="form-control" id="matkhau" name="matkhau" type="password" minlength="6" maxlength="255" autocomplete="new-password" placeholder="Để trống nếu không đổi"><?php if (! empty($errors['matkhau'])): ?><div class="field-error"><?= esc($errors['matkhau']) ?></div><?php endif; ?></div>
                </div>
                <div class="form-actions"><button class="btn btn-dark" type="submit">Lưu thay đổi</button></div>
            </form>
        </section>
    </main>
</div>
</body>
</html>
