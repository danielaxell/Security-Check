<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'tb_role';

    public function getRole($id_role = false)
    {
        if ($id_role === false) { //done
            $hasil = DB::table($this->table)
                ->select('id_role', 'nama_role', 'rec_stat')
                ->orderBy('tgl_updated', 'desc')
                ->get();
        } else { //done
            $hasil = DB::table($this->table)
                ->select('id_role', 'nama_role', 'rec_stat')
                ->where('id_role', '=', $id_role)
                ->first();
        }
        return $hasil;
    }

    public function getRoleAktif()
    { //done
        $hasil =  DB::select("SELECT id_role, id_role||' | '||nama_role as idnamarole FROM tb_role WHERE rec_stat='A'");
        return $hasil;
    }


    public function getIdRole()
    { //done
        $query = DB::table($this->table)->count();
        if ($query < 1) {
            $hasil = collect(DB::select("SELECT 'R' AS max_id_role FROM DUAL"))->first();
        } else {
            $hasil = collect(DB::select("SELECT CONCAT('R',MAX(TO_NUMBER(SUBSTR(ID_ROLE, 2, 5)))+1) as max_id_role FROM tb_role"))->first();
        }
        return $hasil;
    }


    public function getNamaSamaRole($nama_role = false, $id_role = false)
    { //done
        if ($nama_role == true && $id_role == false) {
            $hasil =  DB::select("SELECT id_role FROM tb_role WHERE lower(nama_role)=lower('" . $nama_role . "')");
        } else {
            $hasil =  DB::select("SELECT id_role FROM tb_role WHERE lower(nama_role)=lower('" . $nama_role . "') AND id_role !='" . $id_role . "'");
        }
        return $hasil;
    }


    public function insertRole($data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function updateRole($data, $id_role)
    {
        return DB::table($this->table)->where('id_role', $id_role)->update($data);
    }

    public function deleteRole($id_role)
    {
        return DB::table($this->table)->where('id_role', '=', $id_role)->delete();
    }
}
