<?php

namespace App\Models;

use CodeIgniter\Model;

class PositionModel extends Model
{
    protected $table      = 'position';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    // Daftar kolom yang diizinkan untuk operasi INSERT/UPDATE
    protected $allowedFields = ['id', 'name', 'created_at', 'updated_at'];

    // Metode bawaan CI4 menggantikan CRUD sederhana

    /**
     * Mengambil semua data posisi (CRUD: Read All).
     * @return array
     */
    public function getAll()
    {
        return $this->findAll();
    }

    /**
     * Mengambil data posisi berdasarkan ID (CRUD: Read Single).
     * @param string $id
     * @return array|null
     */
    public function getById($id)
    {
        return $this->find($id);
    }

    // Catatan:
    // Metode insert, update, dan delete diwarisi dari CodeIgniter\Model.
    // Misalnya, di Controller:
    // $this->positionModel->insert($data);
    // $this->positionModel->update($id, $data);
    // $this->positionModel->delete($id);
}
