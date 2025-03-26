<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class MapUserRole extends Model
{
    protected $table = 'tb_user_role';

    public function getUserRole($id_user_role = false)
    { //done
        if ($id_user_role === false) {
            $hasil = DB::select("SELECT nama_terminal, nipp, nama, id_user_role, id_role, nama_role FROM v_user_role ORDER BY tgl_updated DESC");
        } else { //done
            $hasil = collect(DB::select("SELECT nipp, id_user_role, id_role, nama_role FROM v_user_role WHERE id_user_role='" . $id_user_role . "'"))->first();
        }
        return $hasil;
    }

    public function cekSamaUserRole($nipp, $id_role)
    { //done
        $hasil = DB::table($this->table)
            ->where([['nipp', '=', $nipp], ['id_role', '=', $id_role]])
            ->count();
        return $hasil;
    }

    public function insertUserRole($data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function updateUserRole($data, $id_user_role)
    {
        return DB::table($this->table)->where('id_user_role', $id_user_role)->update($data);
    }

    public function deleteUserRole($id_user_role)
    {
        return DB::table($this->table)->where('id_user_role', '=', $id_user_role)->delete();
    }
}
