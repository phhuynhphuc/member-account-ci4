<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng ký thành viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('css/members.css') ?>" rel="stylesheet">
</head>
<body class="auth-page">
<main class="auth-panel">
    <a class="brand" href="<?= site_url('dang-nhap') ?>"><span class="brand-mark">TV</span><span>Tài khoản thành viên</span></a>
    <h1>Đăng ký</h1>
    <p class="intro">Tạo tài khoản thành viên mới.</p>
    <?php if (! empty($errors['general'])): ?><div class="alert alert-danger"><?= esc($errors['general']) ?></div><?php endif; ?>
    <form method="post" action="<?= site_url('dang-ky') ?>">
        <?= csrf_field() ?>
        <label class="form-label" for="username">Tên đăng nhập</label>
        <input class="form-control" id="username" name="username" maxlength="50" value="<?= old('username') ?>" autocomplete="username" required>
        <?php if (! empty($errors['username'])): ?><div class="field-error"><?= esc($errors['username']) ?></div><?php endif; ?>
        <label class="form-label mt-3" for="hoten">Họ và tên</label>
        <input class="form-control" id="hoten" name="hoten" maxlength="100" value="<?= old('hoten') ?>" required>
        <?php if (! empty($errors['hoten'])): ?><div class="field-error"><?= esc($errors['hoten']) ?></div><?php endif; ?>
        <label class="form-label mt-3" for="dienthoai">Số điện thoại</label>
        <input class="form-control" id="dienthoai" name="dienthoai" type="tel" maxlength="15" value="<?= old('dienthoai') ?>" placeholder="Ví dụ: 0912345678" required>
        <?php if (! empty($errors['dienthoai'])): ?><div class="field-error"><?= esc($errors['dienthoai']) ?></div><?php endif; ?>
        <label class="form-label mt-3" for="matkhau">Mật khẩu</label>
        <input class="form-control" id="matkhau" name="matkhau" type="password" minlength="6" maxlength="255" autocomplete="new-password" required>
        <?php if (! empty($errors['matkhau'])): ?><div class="field-error"><?= esc($errors['matkhau']) ?></div><?php endif; ?>
        <button class="btn btn-dark w-100 mt-4" type="submit">Tạo tài khoản</button>
    </form>
    <p class="auth-switch">Đã có tài khoản? <a href="<?= site_url('dang-nhap') ?>">Đăng nhập</a></p>
</main>
</body>
</html>
