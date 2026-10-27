<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', static fn () => redirect()->to(site_url('dang-nhap')));
$routes->get('dang-ky', 'TaiKhoan::register');
$routes->post('dang-ky', 'TaiKhoan::store', ['filter' => 'csrf']);
$routes->get('dang-nhap', 'TaiKhoan::login');
$routes->post('dang-nhap', 'TaiKhoan::login', ['filter' => 'csrf']);
$routes->get('quen-mat-khau', 'TaiKhoan::forgotPassword');
$routes->post('quen-mat-khau', 'TaiKhoan::resetPassword', ['filter' => 'csrf']);
$routes->get('tai-khoan', 'TaiKhoan::profile', ['filter' => 'memberauth']);
$routes->post('tai-khoan', 'TaiKhoan::update', ['filter' => ['memberauth', 'csrf']]);
$routes->get('anh/(:segment)', 'TaiKhoan::avatar/$1');
$routes->post('dang-xuat', 'TaiKhoan::logout', ['filter' => ['memberauth', 'csrf']]);

// Quản trị viên
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
