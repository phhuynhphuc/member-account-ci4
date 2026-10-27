<?php

namespace App\Models;

use CodeIgniter\Model;

class ThanhVienModel extends Model
{
    protected $table = 'thanhvien';
    protected $primaryKey = 'username';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $protectFields = true;
    protected $allowedFields = ['username', 'matkhau', 'hoten', 'dienthoai', 'hinhdaidien'];

    public function findForLogin(string $username): ?array
    {
        return $this->select('username, matkhau')
            ->where('username', $username)
            ->first();
    }

    public function findForReset(string $username, string $phone): ?array
    {
        return $this->select('username, dienthoai')
            ->where('username', $username)
            ->where('dienthoai', $phone)
            ->first();
    }

    public function listing(string $search = ''): array
    {
        return $this->select('username, hoten, dienthoai, hinhdaidien')
            ->groupStart()
                ->like('username', $search)
                ->orLike('hoten', $search)
                ->orLike('dienthoai', $search)
            ->groupEnd()
            ->orderBy('hoten', 'ASC')
            ->findAll();
    }

}
