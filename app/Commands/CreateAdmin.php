<?php

namespace App\Commands;

use App\Models\QuanTriModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CreateAdmin extends BaseCommand
{
    protected $group = 'Auth';
    protected $name = 'admin:create';
    protected $description = 'Create the first administrator account interactively.';

    public function run(array $params)
    {
        $model = new QuanTriModel();
        if ($model->countAllResults() > 0) {
            CLI::error('Đã có tài khoản quản trị. Lệnh này chỉ tạo tài khoản đầu tiên.');
            return EXIT_ERROR;
        }

        $username = trim((string) CLI::prompt('Tên đăng nhập quản trị'));
        $name = trim((string) CLI::prompt('Họ tên quản trị'));
        $password = (string) CLI::getPassword(true, 'Mật khẩu (ít nhất 8 ký tự)');
        $confirmation = (string) CLI::getPassword(true, 'Nhập lại mật khẩu');

        if ($username === '' || strlen($username) > 50 || $name === '' || strlen($name) > 100) {
            CLI::error('Tên đăng nhập hoặc họ tên không hợp lệ.');
            return EXIT_ERROR;
        }
        if (strlen($password) < 8 || $password !== $confirmation) {
            CLI::error('Mật khẩu không hợp lệ hoặc xác nhận mật khẩu không khớp.');
            return EXIT_ERROR;
        }

        if (! $model->insert([
            'user_admin' => $username,
            'matkhau' => password_hash($password, PASSWORD_DEFAULT),
            'hoten' => $name,
        ])) {
            CLI::error('Không thể tạo tài khoản: ' . implode(' ', $model->errors()));
            return EXIT_ERROR;
        }

        CLI::write('Đã tạo tài khoản quản trị.');
        return EXIT_SUCCESS;
    }
}
