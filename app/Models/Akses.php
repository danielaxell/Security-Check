<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Akses extends Model
{
    protected $table = 'tb_akses';

    public function getAkses($id_role = false)
    {
        if ($id_role === false) { //done
            $hasil =  DB::select("SELECT id_akses, nama_role, urutan_menu ||'. '|| nama_menu nama_menu, nama_submenu, C, R, U, D FROM v_akses ORDER BY urutan_menu, urutan_submenu");
        } else { //done
            $hasil =  DB::select("SELECT id_akses, nama_role, urutan_menu ||'. '|| nama_menu nama_menu, nama_submenu, C, R, U, D FROM v_akses WHERE id_role='" . $id_role . "' ORDER BY urutan_menu, urutan_submenu");
        }
        return $hasil;
    }


    public function updateAkses($data, $id_akses)
    {
        return DB::table($this->table)->where('id_akses', $id_akses)->update($data);
    }
}
