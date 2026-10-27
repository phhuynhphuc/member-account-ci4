<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý thành viên</title>
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
            <form action="<?= site_url('quan-tri/dang-xuat') ?>" method="post" class="mt-3">
                <?= csrf_field() ?>
                <button class="btn btn-outline-secondary btn-sm w-100" type="submit">Đăng xuất</button>
            </form>
        </div>
    </aside>
    <main class="main-content">
        <header class="page-header">
            <div><h1>Thành viên</h1><p>Danh sách hồ sơ thành viên trong hệ thống.</p></div>
            <a class="btn btn-dark" href="<?= site_url('thanhvien/them') ?>">Thêm thành viên</a>
        </header>

        <?php if ($message = session()->getFlashdata('success')): ?><div class="alert alert-success"><?= esc($message) ?></div><?php endif; ?>
        <?php if ($message = session()->getFlashdata('error')): ?><div class="alert alert-danger"><?= esc($message) ?></div><?php endif; ?>

        <section class="list-panel">
            <div class="list-toolbar">
                <div><h2>Toàn bộ thành viên</h2><span class="record-count"><?= count($members) ?> thành viên</span></div>
                <form method="get" action="<?= site_url('thanhvien') ?>" class="search-form">
                    <label class="visually-hidden" for="member-search">Tìm thành viên</label>
                    <input id="member-search" class="form-control" type="search" name="q" value="<?= esc($search) ?>" placeholder="Tìm theo tên, username, quốc gia">
                    <button class="btn btn-outline-secondary" type="submit">Tìm</button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table member-table align-middle mb-0">
                    <thead><tr><th>Thành viên</th><th>Số điện thoại</th><th class="text-end">Thao tác</th></tr></thead>
                    <tbody>
                    <?php if ($members === []): ?>
                        <tr><td colspan="3" class="empty-state">Chưa có thành viên phù hợp. Thêm hồ sơ đầu tiên để bắt đầu.</td></tr>
                    <?php else: foreach ($members as $member): ?>
                        <tr>
                            <td>
                                <div class="member-cell">
                                    <?php if (! empty($member['hinhdaidien'])): ?>
                                        <img class="avatar" src="<?= site_url('anh/' . rawurlencode($member['hinhdaidien'])) ?>" alt="Ảnh đại diện của <?= esc($member['hoten']) ?>">
                                    <?php else: ?>
                                        <span class="avatar avatar-fallback" aria-hidden="true"><?= esc(mb_strtoupper(mb_substr($member['hoten'], 0, 1))) ?></span>
                                    <?php endif; ?>
                                    <span><strong><?= esc($member['hoten']) ?></strong><small>@<?= esc($member['username']) ?></small></span>
                                </div>
                            </td>
                            <td><?= esc($member['dienthoai'] ?: '—') ?></td>
                            <td class="text-end">
                                <div class="row-actions">
                                    <a class="btn btn-sm btn-outline-secondary" href="<?= site_url('thanhvien/sua/' . rawurlencode($member['username'])) ?>">Sửa</a>
                                    <form method="post" action="<?= site_url('thanhvien/xoa/' . rawurlencode($member['username'])) ?>" onsubmit="return confirm('Bạn có chắc muốn xóa thành viên <?= esc($member['hoten'], 'js') ?>?')">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
</body>
</html>
