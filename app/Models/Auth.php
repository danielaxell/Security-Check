<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    protected $table = 'tb_user';

    public function cek_user($nipp)
    { //done
        $query = DB::table($this->table)
            ->where([['nipp', '=', $nipp], ['rec_stat', '=', 'A'], ['is_user_login', '=', 'Y']])
            ->count();
        if ($query >  0) { //done
            $hasil =  DB::table($this->table)
                ->select('nipp', 'password')
                ->where([['nipp', '=', $nipp], ['rec_stat', '=', 'A'], ['is_user_login', '=', 'Y']])
                ->limit(1)
                ->first();
        } else {
            $hasil = array();
        }
        return $hasil;
    }

    public function getListRole($nipp)
    { //done
        $hasil = DB::select("SELECT id_role, nama_role FROM v_user_role where nipp='" . $nipp . "'");
        return $hasil;
    }

    public function cek_login($nipp, $id_role)
    { //done
        $hasil = collect(DB::select("SELECT kd_cabang_induk, kd_terminal_induk, kd_cabang, kd_terminal, nama_terminal, nama, nama_role, kd_divisi FROM v_user_role where nipp='" . $nipp . "' and id_role='" . $id_role . "'"))
            ->first();
        return $hasil;
    }

    public function updatePwd($data, $nipp)
    {
        return DB::table($this->table)->where('nipp', $nipp)->update($data);
    }
}
