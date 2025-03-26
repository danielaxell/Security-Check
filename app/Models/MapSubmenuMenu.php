<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class MapSubmenuMenu extends Model
{
    protected $table = 'tb_submenu_menu';

    public function getSubmenuMenu($id_submenu_menu = false)
    {
        if ($id_submenu_menu === false) { //done
            $hasil = DB::select("SELECT id_submenu_menu, id_submenu, nama_submenu, id_menu||' | '|| nama_menu id_nama_menu, id_menu, nama_menu FROM v_submenu_menu");
        } else { //done
            $hasil = collect(DB::select("SELECT id_submenu_menu, id_submenu, nama_submenu, id_menu, nama_menu FROM v_submenu_menu WHERE id_submenu_menu='" . $id_submenu_menu . "'"))->first();
        }
        return $hasil;
    }

    public function cekSamaSubmenuMenu($id_menu, $id_submenu)
    { //done
        $hasil = DB::table($this->table)
            ->where([['id_menu', '=', $id_menu], ['id_submenu', '=', $id_submenu]])
            ->count();
        return $hasil;
    }

    public function insertSubmenuMenu($data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function updateSubmenuMenu($data, $id_submenu_menu)
    {
        return DB::table($this->table)->where('id_submenu_menu', $id_submenu_menu)->update($data);
    }

    public function deleteSubmenuMenu($id_submenu_menu)
    {
        return DB::table($this->table)->where('id_submenu_menu', '=', $id_submenu_menu)->delete();
    }
}
