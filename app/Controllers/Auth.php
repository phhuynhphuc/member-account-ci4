<?php

namespace App\Controllers;

use App\Models\QuanTriModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session('admin')) {
            return redirect()->to(site_url('thanhvien'));
        }

        $errors = session()->getFlashdata('errors') ?? [];
        $error = session()->getFlashdata('error');

        if ($this->request->is('post')) {
            $rules = [
                'user_admin' => 'required|max_length[50]',
                'matkhau' => 'required|max_length[255]',
            ];

            if (! $this->validate($rules)) {
                return view('auth/login', ['errors' => $this->validator->getErrors()]);
            }

            $username = trim((string) $this->request->getPost('user_admin'));
            $password = (string) $this->request->getPost('matkhau');
            $admin = (new QuanTriModel())->findForLogin($username);

            if ($admin && password_verify($password, $admin['matkhau'])) {
                session()->regenerate(true);
                session()->set('admin', [
                    'username' => $admin['user_admin'],
                    'name' => $admin['hoten'],
                ]);

                return redirect()->to(site_url('thanhvien'))->with('success', 'Đăng nhập thành công.');
            }

            $error = 'Tên đăng nhập hoặc mật khẩu không đúng.';
        }

        return view('auth/login', compact('errors', 'error'));
    }

    public function logout()
    {
        session()->remove('admin');
        session()->regenerate(true);

        return redirect()->to(site_url('quan-tri/dang-nhap'))->with('success', 'Bạn đã đăng xuất.');
    }
}
