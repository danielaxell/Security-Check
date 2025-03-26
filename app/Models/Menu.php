<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'tb_menu';

    public function getMenu($id_menu = false)
    {
        if ($id_menu === false) { //done
            $hasil = DB::table($this->table)
                ->select('urutan', 'id_menu', 'nama_menu', 'url', 'icon', 'rec_stat')
                ->orderBy('tgl_updated', 'desc')
                ->get();
        } else { //done
            $hasil = DB::table($this->table)
                ->select('urutan', 'id_menu', 'nama_menu', 'url', 'icon', 'rec_stat')
                ->where('id_menu', '=', $id_menu)
                ->first();
        }
        return $hasil;
    }

    public function getMenuAktif()
    { //done
        $hasil =  DB::select("SELECT id_menu, id_menu||' | '|| nama_menu nama_menu FROM tb_menu WHERE rec_stat='A'");
        return $hasil;
    }

    public function getIdMenu()
    { //done
        $query = DB::table($this->table)->count();
        if ($query < 1) {
            $hasil = collect(DB::select("SELECT 'M1' AS max_id_menu FROM DUAL"))->first();
        } else {
            $hasil = collect(DB::select("SELECT CONCAT('M',MAX(TO_NUMBER(SUBSTR(ID_MENU, 2, 5)))+1) as max_id_menu FROM tb_menu"))->first();
        }
        return $hasil;
    }

    public function getUrutanMenu()
    { //done
        $query = DB::table($this->table)->count();
        if ($query < 1) {
            $hasil = collect(DB::select("SELECT 1 AS max_urutan FROM DUAL"))->first();
        } else {
            $hasil = collect(DB::select("SELECT MAX(urutan)+1 as max_urutan FROM tb_menu"))->first();
        }
        return $hasil;
    }

    public function getEksistUrutanMenu($urutan)
    { //done
        $hasil = DB::table($this->table)
            ->select('id_menu')
            ->where('urutan', '=', $urutan)
            ->first();
        return $hasil;
    }

    public function getNamaSamaMenu($nama_menu = false, $id_menu = false)
    { //done
        if ($nama_menu == true && $id_menu == false) {
            $hasil =  DB::select("SELECT id_menu FROM tb_menu WHERE lower(nama_menu)=lower('" . $nama_menu . "')");
        } else {
            $hasil =  DB::select("SELECT id_menu FROM tb_menu WHERE lower(nama_menu)=lower('" . $nama_menu . "') AND id_menu !='" . $id_menu . "'");
        }
        return $hasil;
    }

    public function insertMenu($data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function updateMenu($data, $id_menu)
    {
        return DB::table($this->table)->where('id_menu', $id_menu)->update($data);
    }

    public function deleteMenu($id_menu)
    {
        return DB::table($this->table)->where('id_menu', '=', $id_menu)->delete();
    }
}
