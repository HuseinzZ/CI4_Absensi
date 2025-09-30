<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    /**
     * Mengambil daftar menu berdasarkan role_id dari tabel user_access.
     * @param int $role_id
     * @return array
     */
    public function getMenuByRole(int $role_id): array
    {
        return $this->db->table('user_menu')
            ->select('user_menu.id, user_menu.menu')
            ->join('user_access', 'user_menu.id = user_access.menu_id')
            ->where('user_access.role_id', $role_id)
            ->orderBy('user_access.menu_id', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Mengambil daftar submenu berdasarkan menu_id yang aktif.
     * @param int $menuId
     * @return array
     */
    public function getSubMenuByMenuId(int $menuId): array
    {
        return $this->db->table('user_submenu')
            ->select('*')
            ->where('menu_id', $menuId)
            ->where('is_active', 1)
            ->orderBy('title', 'ASC')
            ->get()
            ->getResultArray();
    }
}
