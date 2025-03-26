<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Submenu extends Model
{
    protected $table = 'tb_submenu';

    public function getSubmenu($id_submenu = false)
    {
        if ($id_submenu === false) { //done
            $hasil = DB::table($this->table)
                ->select('urutan', 'id_submenu', 'nama_submenu', 'url', 'icon', 'rec_stat')
                ->orderBy('tgl_updated', 'DESC')
                ->get();
        } else { //done
            $hasil = DB::table($this->table)
                ->select('urutan', 'id_submenu', 'nama_submenu', 'url', 'icon', 'rec_stat')
                ->where('id_submenu', '=', $id_submenu)
                ->first();
        }
        return $hasil;
    }

    public function getSubmenuAktif()
    { //done
        $hasil =  DB::select("SELECT id_submenu, id_submenu||' | '|| nama_submenu nama_submenu FROM tb_submenu WHERE rec_stat='A'");
        return $hasil;
    }

    public function getIdSubmenu()
    { //done
        $query = DB::table($this->table)
            ->count();
        if ($query < 1) {
            $hasil = collect(DB::select("SELECT 'SM1' AS max_id_submenu FROM DUAL"))->first();
        } else {
            $hasil = collect(DB::select("SELECT CONCAT('SM',MAX(TO_NUMBER(SUBSTR(ID_SUBMENU, 3, 5)))+1) as max_id_submenu FROM tb_submenu"))->first();
        }
        return $hasil;
    }

    public function getUrutanSubmenu()
    { //done
        $query = DB::table($this->table)
            ->count();
        if ($query < 1) {
            $hasil = collect(DB::select("SELECT 1 AS max_urutan FROM DUAL"))->first();
        } else {
            $hasil = collect(DB::select("SELECT MAX(urutan)+1 as max_urutan FROM tb_submenu"))->first();
        }
        return $hasil;
    }

    public function getEksistUrutanSubmenu($urutan)
    { //done
        $hasil = DB::table($this->table)
            ->select('id_submenu')
            ->where('urutan', '=', $urutan)
            ->first();
        return $hasil;
    }

    public function getNamaSamaSubmenu($nama_submenu = false, $id_submenu = false)
    { //done
        if ($nama_submenu == true && $id_submenu == false) {
            $hasil =  DB::select("SELECT id_submenu FROM tb_submenu WHERE lower(nama_submenu)=lower('" . $nama_submenu . "')");
        } else {
            $hasil =  DB::select("SELECT id_submenu FROM tb_submenu WHERE lower(nama_submenu)=lower('" . $nama_submenu . "') AND id_submenu !='" . $id_submenu . "'");
        }
        return $hasil;
    }

    public function insertSubmenu($data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function updateSubmenu($data, $id_submenu)
    {
        return DB::table($this->table)->where('id_submenu', $id_submenu)->update($data);
    }

    public function deleteSubmenu($id_submenu)
    {
        return DB::table($this->table)->where('id_submenu', '=', $id_submenu)->delete();
    }
}
