<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class MapMenuRole extends Model
{
    protected $table = 'tb_menu_role';

    public function getMenuRole($id_menu_role = false)
    {
        if ($id_menu_role === false) { //done
            $hasil = DB::select("SELECT id_menu_role, id_role, nama_role, id_role||' | '|| nama_role id_nama_role, id_menu, nama_menu FROM v_menu_role");
        } else { //done
            $hasil = collect(DB::select("SELECT id_menu_role, id_role, nama_role, id_menu, nama_menu FROM v_menu_role WHERE id_menu_role='" . $id_menu_role . "'"))->first();
        }
        return $hasil;
    }

    public function cekSamaMenuRole($id_menu, $id_role)
    { //done
        $hasil = DB::table($this->table)
            ->where([['id_menu', '=', $id_menu], ['id_role', '=', $id_role]])
            ->count();
        return $hasil;
    }

    public function insertMenuRole($data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function updateMenuRole($data, $id_menu_role)
    {
        return DB::table($this->table)->where('id_menu_role', $id_menu_role)->update($data);
    }

    public function deleteMenuRole($id_menu_role)
    {
        return DB::table($this->table)->where('id_menu_role', '=', $id_menu_role)->delete();
    }
}
