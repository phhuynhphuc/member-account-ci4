<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập thành viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('css/members.css') ?>" rel="stylesheet">
</head>
<body class="auth-page">
<main class="auth-panel">
    <a class="brand" href="<?= site_url('dang-nhap') ?>"><span class="brand-mark">TV</span><span>Tài khoản thành viên</span></a>
    <h1>Đăng nhập</h1>
    <p class="intro">Đăng nhập vào tài khoản thành viên của bạn.</p>
    <?php if ($message = session()->getFlashdata('success')): ?><div class="alert alert-success"><?= esc($message) ?></div><?php endif; ?>
    <?php if (! empty($error)): ?><div class="alert alert-danger"><?= esc($error) ?></div><?php endif; ?>
    <form method="post" action="<?= site_url('dang-nhap') ?>">
        <?= csrf_field() ?>
        <label class="form-label" for="username">Tên đăng nhập</label>
        <input class="form-control" id="username" name="username" value="<?= old('username') ?>" maxlength="50" autocomplete="username" required>
        <?php if (! empty($errors['username'])): ?><div class="field-error"><?= esc($errors['username']) ?></div><?php endif; ?>
        <label class="form-label mt-3" for="matkhau">Mật khẩu</label>
        <input class="form-control" id="matkhau" name="matkhau" type="password" autocomplete="current-password" required>
        <?php if (! empty($errors['matkhau'])): ?><div class="field-error"><?= esc($errors['matkhau']) ?></div><?php endif; ?>
        <button class="btn btn-dark w-100 mt-4" type="submit">Đăng nhập</button>
    </form>
    <p class="auth-switch"><a href="<?= site_url('quen-mat-khau') ?>">Quên mật khẩu?</a> · Chưa có tài khoản? <a href="<?= site_url('dang-ky') ?>">Đăng ký</a></p>
</main>
</body>
</html>
