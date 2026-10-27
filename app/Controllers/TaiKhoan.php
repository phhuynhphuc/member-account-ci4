<?php

namespace App\Controllers;

use App\Models\ThanhVienModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use Throwable;

class TaiKhoan extends BaseController
{
    private const UPLOAD_PATH = WRITEPATH . 'uploads';

    public function register()
    {
        return view('taikhoan/register', [
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function store()
    {
        $rules = [
            'username' => 'required|alpha_numeric_punct|max_length[50]|is_unique[thanhvien.username]',
            'hoten' => 'required|regex_match[/\S/]|max_length[100]',
            'dienthoai' => 'required|regex_match[/^[0-9]{10,11}$/]',
            'matkhau' => 'required|min_length[6]|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new ThanhVienModel();
        try {
            if (! $model->insert([
                'username' => trim((string) $this->request->getPost('username')),
                'matkhau' => password_hash((string) $this->request->getPost('matkhau'), PASSWORD_DEFAULT),
                'hoten' => trim((string) $this->request->getPost('hoten')),
                'dienthoai' => trim((string) $this->request->getPost('dienthoai')),
                'hinhdaidien' => null,
            ])) {
                return redirect()->back()->withInput()->with('errors', $model->errors());
            }
        } catch (Throwable $exception) {
            log_message('error', 'Could not register member: {message}', ['message' => $exception->getMessage()]);
            return redirect()->back()->withInput()->with('errors', ['general' => 'Không thể tạo tài khoản. Vui lòng thử lại.']);
        }

        return redirect()->to(site_url('dang-nhap'))->with('success', 'Đăng ký thành công. Bạn có thể đăng nhập.');
    }

    public function forgotPassword()
    {
        if (session('member')) {
            return redirect()->to(site_url('tai-khoan'));
        }

        return view('taikhoan/forgot', [
            'errors' => session()->getFlashdata('errors') ?? [],
            'error' => session()->getFlashdata('error'),
        ]);
    }

    public function resetPassword()
    {
        $rules = [
            'username' => 'required|max_length[50]',
            'dienthoai' => 'required|regex_match[/^[0-9]{10,11}$/]',
            'matkhau_moi' => 'required|min_length[6]|max_length[255]',
            'matkhau_xacnhan' => 'required|matches[matkhau_moi]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = trim((string) $this->request->getPost('username'));
        $dienthoai = trim((string) $this->request->getPost('dienthoai'));
        $newPassword = (string) $this->request->getPost('matkhau_moi');

        $model = new ThanhVienModel();
        $member = $model->findForReset($username, $dienthoai);

        if (! $member) {
            return redirect()->back()->withInput()->with('error', 'Tên đăng nhập hoặc số điện thoại không chính xác.');
        }

        try {
            $model->update($username, [
                'matkhau' => password_hash($newPassword, PASSWORD_DEFAULT),
            ]);
        } catch (Throwable $exception) {
            log_message('error', 'Could not reset password: {message}', ['message' => $exception->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Không thể đặt lại mật khẩu. Vui lòng thử lại.');
        }

        return redirect()->to(site_url('dang-nhap'))->with('success', 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập bằng mật khẩu mới.');
    }

    public function login()
    {
        if (session('member')) {
            return redirect()->to(site_url('tai-khoan'));
        }

        $errors = session()->getFlashdata('errors') ?? [];
        $error = session()->getFlashdata('error');
        if ($this->request->is('post')) {
            if (! $this->validate([
                'username' => 'required|max_length[50]',
                'matkhau' => 'required|max_length[255]',
            ])) {
                return view('taikhoan/login', ['errors' => $this->validator->getErrors(), 'error' => null]);
            }

            $member = (new ThanhVienModel())->findForLogin(trim((string) $this->request->getPost('username')));
            if ($member && password_verify((string) $this->request->getPost('matkhau'), $member['matkhau'])) {
                session()->regenerate(true);
                session()->set('member', ['username' => $member['username']]);

                return redirect()->to(site_url('tai-khoan'))->with('success', 'Đăng nhập thành công.');
            }

            $error = 'Tên đăng nhập hoặc mật khẩu không đúng.';
        }

        return view('taikhoan/login', compact('errors', 'error'));
    }

    public function logout()
    {
        session()->remove('member');
        session()->regenerate(true);

        return redirect()->to(site_url('dang-nhap'))->with('success', 'Bạn đã đăng xuất.');
    }

    public function profile()
    {
        $model = new ThanhVienModel();
        $member = $model->find(session('member')['username']);
        if (! $member) {
            session()->remove('member');
            return redirect()->to(site_url('dang-nhap'))->with('error', 'Không tìm thấy tài khoản. Vui lòng đăng nhập lại.');
        }

        unset($member['matkhau']);

        return view('taikhoan/profile', [
            'member' => $member,
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function update()
    {
        $username = session('member')['username'];
        $model = new ThanhVienModel();
        $member = $model->find($username);
        if (! $member) {
            return redirect()->to(site_url('dang-nhap'))->with('error', 'Không tìm thấy tài khoản.');
        }

        $rules = [
            'hoten' => 'required|regex_match[/\S/]|max_length[100]',
            'dienthoai' => 'required|regex_match[/^[0-9]{10,11}$/]',
            'matkhau_cu' => 'permit_empty|max_length[255]',
            'matkhau' => 'permit_empty|min_length[6]|max_length[255]',
            'hinhdaidien' => 'permit_empty|is_image[hinhdaidien]|max_size[hinhdaidien,2048]|mime_in[hinhdaidien,image/jpeg,image/png,image/gif,image/webp]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $password = (string) $this->request->getPost('matkhau');
        $oldPassword = (string) $this->request->getPost('matkhau_cu');

        if ($password !== '') {
            if ($oldPassword === '') {
                return redirect()->back()->withInput()->with('errors', ['matkhau_cu' => 'Vui lòng nhập mật khẩu cũ để đổi mật khẩu mới.']);
            }
            if (! password_verify($oldPassword, $member['matkhau'])) {
                return redirect()->back()->withInput()->with('errors', ['matkhau_cu' => 'Mật khẩu cũ không chính xác.']);
            }
        }

        $avatar = $this->saveAvatar($this->request->getFile('hinhdaidien'));
        if ($avatar === false) {
            return redirect()->back()->withInput()->with('errors', ['hinhdaidien' => 'Không thể lưu ảnh đại diện.']);
        }

        $data = [
            'hoten' => trim((string) $this->request->getPost('hoten')),
            'dienthoai' => trim((string) $this->request->getPost('dienthoai')),
        ];
        if ($password !== '') {
            $data['matkhau'] = password_hash($password, PASSWORD_DEFAULT);
        }
        if ($avatar !== null) {
            $data['hinhdaidien'] = $avatar;
        }

        try {
            if (! $model->update($username, $data)) {
                $this->removeAvatar($avatar);
                return redirect()->back()->withInput()->with('errors', $model->errors());
            }
        } catch (Throwable $exception) {
            $this->removeAvatar($avatar);
            log_message('error', 'Could not update member profile: {message}', ['message' => $exception->getMessage()]);
            return redirect()->back()->withInput()->with('errors', ['general' => 'Không thể cập nhật hồ sơ.']);
        }

        if ($avatar !== null) {
            $this->removeAvatar($member['hinhdaidien']);
        }

        return redirect()->to(site_url('tai-khoan'))->with('success', 'Đã cập nhật hồ sơ.');
    }

    public function avatar(string $filename)
    {
        if (basename($filename) !== $filename || ! preg_match('/^[a-zA-Z0-9_.-]+\.(jpg|jpeg|png|gif|webp)$/i', $filename)) {
            return $this->response->setStatusCode(404);
        }

        $path = self::UPLOAD_PATH . DIRECTORY_SEPARATOR . $filename;
        if (! is_file($path)) {
            return $this->response->setStatusCode(404);
        }

        $mime = mime_content_type($path);
        if (! in_array($mime, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], true)) {
            return $this->response->setStatusCode(404);
        }

        return $this->response->setHeader('Content-Type', $mime)
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setHeader('Cache-Control', 'public, max-age=86400')
            ->setBody(file_get_contents($path));
    }

    private function saveAvatar(?UploadedFile $file): string|null|false
    {
        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if (! $file->isValid() || $file->hasMoved()) {
            return false;
        }

        if (! is_dir(self::UPLOAD_PATH) && ! mkdir(self::UPLOAD_PATH, 0750, true) && ! is_dir(self::UPLOAD_PATH)) {
            return false;
        }

        $name = $file->getRandomName();
        return $file->move(self::UPLOAD_PATH, $name) ? $name : false;
    }

    private function removeAvatar(?string $filename): void
    {
        if (! $filename || basename($filename) !== $filename) {
            return;
        }

        $path = self::UPLOAD_PATH . DIRECTORY_SEPARATOR . $filename;
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
