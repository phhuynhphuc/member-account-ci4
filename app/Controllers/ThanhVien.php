<?php

namespace App\Controllers;

use App\Models\ThanhVienModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\Files\UploadedFile;
use Throwable;

class ThanhVien extends BaseController
{
    private const UPLOAD_PATH = WRITEPATH . 'uploads';

    public function index()
    {
        $search = trim((string) $this->request->getGet('q'));
        $members = (new ThanhVienModel())->listing($search);

        return view('thanhvien/index', [
            'members' => $members,
            'search' => $search,
        ]);
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

    public function create()
    {
        return view('thanhvien/form', [
            'member' => [],
            'isEdit' => false,
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function store()
    {
        $rules = [
            'username' => 'required|alpha_numeric_punct|max_length[50]|is_unique[thanhvien.username]',
            'hoten' => 'required|max_length[100]',
            'dienthoai' => 'required|regex_match[/^[0-9]{10,11}$/]',
            'matkhau' => 'required|min_length[6]|max_length[255]',
            'hinhdaidien' => 'permit_empty|is_image[hinhdaidien]|max_size[hinhdaidien,2048]|mime_in[hinhdaidien,image/jpeg,image/png,image/gif,image/webp]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new ThanhVienModel();
        $avatar = $this->saveAvatar($this->request->getFile('hinhdaidien'));

        if ($avatar === false) {
            return redirect()->back()->withInput()->with('errors', ['hinhdaidien' => 'Không thể lưu ảnh đại diện.']);
        }

        $data = $this->memberData();
        $data['username'] = trim((string) $this->request->getPost('username'));
        $data['matkhau'] = password_hash((string) $this->request->getPost('matkhau'), PASSWORD_DEFAULT);
        $data['hinhdaidien'] = $avatar;

        try {
            if (! $model->insert($data)) {
                $this->removeAvatar($avatar);
                return redirect()->back()->withInput()->with('errors', $model->errors());
            }
        } catch (Throwable $exception) {
            $this->removeAvatar($avatar);
            log_message('error', 'Could not create member: {message}', ['message' => $exception->getMessage()]);
            return redirect()->back()->withInput()->with('errors', ['general' => 'Không thể thêm thành viên. Vui lòng kiểm tra dữ liệu và kết nối cơ sở dữ liệu.']);
        }

        return redirect()->to(site_url('thanhvien'))->with('success', 'Đã thêm thành viên.');
    }

    public function edit(string $username)
    {
        $member = (new ThanhVienModel())->find($username);
        if (! $member) {
            return redirect()->to(site_url('thanhvien'))->with('error', 'Không tìm thấy thành viên.');
        }

        unset($member['matkhau']);

        return view('thanhvien/form', [
            'member' => $member,
            'isEdit' => true,
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function update(string $username)
    {
        $model = new ThanhVienModel();
        $member = $model->find($username);
        if (! $member) {
            return redirect()->to(site_url('thanhvien'))->with('error', 'Không tìm thấy thành viên.');
        }

        $rules = [
            'hoten' => 'required|max_length[100]',
            'dienthoai' => 'required|regex_match[/^[0-9]{10,11}$/]',
            'matkhau' => 'permit_empty|min_length[6]|max_length[255]',
            'hinhdaidien' => 'permit_empty|is_image[hinhdaidien]|max_size[hinhdaidien,2048]|mime_in[hinhdaidien,image/jpeg,image/png,image/gif,image/webp]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $newAvatar = $this->saveAvatar($this->request->getFile('hinhdaidien'));
        if ($newAvatar === false) {
            return redirect()->back()->withInput()->with('errors', ['hinhdaidien' => 'Không thể lưu ảnh đại diện.']);
        }

        $data = $this->memberData();
        $password = (string) $this->request->getPost('matkhau');
        if ($password !== '') {
            $data['matkhau'] = password_hash($password, PASSWORD_DEFAULT);
        }
        if ($newAvatar !== null) {
            $data['hinhdaidien'] = $newAvatar;
        }

        try {
            if (! $model->update($username, $data)) {
                $this->removeAvatar($newAvatar);
                return redirect()->back()->withInput()->with('errors', $model->errors());
            }
        } catch (Throwable $exception) {
            $this->removeAvatar($newAvatar);
            log_message('error', 'Could not update member: {message}', ['message' => $exception->getMessage()]);
            return redirect()->back()->withInput()->with('errors', ['general' => 'Không thể cập nhật thành viên.']);
        }

        if ($newAvatar !== null && $member['hinhdaidien']) {
            $this->removeAvatar($member['hinhdaidien']);
        }

        return redirect()->to(site_url('thanhvien'))->with('success', 'Đã cập nhật thành viên.');
    }

    public function delete(string $username)
    {
        $model = new ThanhVienModel();
        $member = $model->find($username);
        if (! $member) {
            return redirect()->to(site_url('thanhvien'))->with('error', 'Không tìm thấy thành viên.');
        }

        try {
            if (! $model->delete($username)) {
                return redirect()->to(site_url('thanhvien'))->with('error', 'Không thể xóa thành viên.');
            }
        } catch (Throwable $exception) {
            log_message('warning', 'Could not delete member: {message}', ['message' => $exception->getMessage()]);
            return redirect()->to(site_url('thanhvien'))->with('error', 'Không thể xóa thành viên này. Có thể thành viên đang có đơn hàng liên quan.');
        }

        $this->removeAvatar($member['hinhdaidien']);
        return redirect()->to(site_url('thanhvien'))->with('success', 'Đã xóa thành viên.');
    }

    private function memberData(): array
    {
        return [
            'hoten' => trim((string) $this->request->getPost('hoten')),
            'dienthoai' => trim((string) $this->request->getPost('dienthoai')),
        ];
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
        if (! $file->move(self::UPLOAD_PATH, $name)) {
            return false;
        }

        return $name;
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
