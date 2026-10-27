<?php

namespace App\Models;

use CodeIgniter\Model;

class QuanTriModel extends Model
{
    protected $table = 'quantri';
    protected $primaryKey = 'user_admin';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $protectFields = true;
    protected $allowedFields = ['user_admin', 'matkhau', 'hoten'];

    public function findForLogin(string $username): ?array
    {
        return $this->select('user_admin, matkhau, hoten')
            ->where('user_admin', $username)
            ->first();
    }
}
