<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quên mật khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('css/members.css') ?>" rel="stylesheet">
</head>
<body class="auth-page">
<main class="auth-panel">
    <a class="brand" href="<?= site_url('dang-nhap') ?>"><span class="brand-mark">TV</span><span>Tài khoản thành viên</span></a>
    <h1>Quên mật khẩu</h1>
    <p class="intro">Nhập tên đăng nhập và số điện thoại đã đăng ký để đặt lại mật khẩu mới.</p>
    <?php if (! empty($error)): ?><div class="alert alert-danger"><?= esc($error) ?></div><?php endif; ?>
    <form method="post" action="<?= site_url('quen-mat-khau') ?>">
        <?= csrf_field() ?>
        <label class="form-label" for="username">Tên đăng nhập</label>
        <input class="form-control" id="username" name="username" value="<?= old('username') ?>" maxlength="50" autocomplete="username" required>
        <?php if (! empty($errors['username'])): ?><div class="field-error"><?= esc($errors['username']) ?></div><?php endif; ?>

        <label class="form-label mt-3" for="dienthoai">Số điện thoại đăng ký</label>
        <input class="form-control" id="dienthoai" name="dienthoai" type="tel" maxlength="15" value="<?= old('dienthoai') ?>" placeholder="Ví dụ: 0912345678" required>
        <?php if (! empty($errors['dienthoai'])): ?><div class="field-error"><?= esc($errors['dienthoai']) ?></div><?php endif; ?>

        <label class="form-label mt-3" for="matkhau_moi">Mật khẩu mới</label>
        <input class="form-control" id="matkhau_moi" name="matkhau_moi" type="password" minlength="6" maxlength="255" autocomplete="new-password" required>
        <?php if (! empty($errors['matkhau_moi'])): ?><div class="field-error"><?= esc($errors['matkhau_moi']) ?></div><?php endif; ?>

        <label class="form-label mt-3" for="matkhau_xacnhan">Xác nhận mật khẩu mới</label>
        <input class="form-control" id="matkhau_xacnhan" name="matkhau_xacnhan" type="password" minlength="6" maxlength="255" autocomplete="new-password" required>
        <?php if (! empty($errors['matkhau_xacnhan'])): ?><div class="field-error"><?= esc($errors['matkhau_xacnhan']) ?></div><?php endif; ?>

        <button class="btn btn-dark w-100 mt-4" type="submit">Đặt lại mật khẩu</button>
    </form>
    <p class="auth-switch">Nhớ mật khẩu? <a href="<?= site_url('dang-nhap') ?>">Đăng nhập</a></p>
</main>
</body>
</html>
