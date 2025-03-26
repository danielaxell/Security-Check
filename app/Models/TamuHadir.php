<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TamuHadir extends Model
{
    protected $table = 'tb_tamuhadir';

    public function getTamuHadir($id_tamuhadir = false)
    {
        if ($id_tamuhadir === false) { //done
            $hasil = DB::select("SELECT id_tamuhadir, nama_terminal, nama_divisi, nipp, nama_pegawai, ktp, nama_tamu, instansi, file_foto, 
            TO_CHAR(TGL_DATANG,'DD/MM/RRRR HH24:MI') tgl_datang FROM v_tamuhadir WHERE kd_cabang='" . session()->get('kd_cabang') . "' AND 
            kd_terminal='" . session()->get('kd_terminal') . "' ORDER BY tgl_updated DESC");
        } else { //done
            $hasil = collect(DB::select("SELECT id_tamuhadir, nama_terminal, nama_divisi, nipp, nama_pegawai, ktp, nama_tamu, no_kartuakses, nama_kartuakses, instansi, file_foto, 
            TO_CHAR(TGL_DATANG,'DD/MM/RRRR HH24:MI') tgl_datang FROM v_tamuhadir WHERE id_tamuhadir='" . $id_tamuhadir . "'"))->first();
        }
        return $hasil;
    }

    public function insertTamuHadir($data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function updateTamuHadir($data, $id_tamuhadir)
    {
        return DB::table($this->table)->where('id_tamuhadir', $id_tamuhadir)->update($data);
    }

    public function deleteTamuHadir($id_tamuhadir)
    {
        return DB::table($this->table)->where('id_tamuhadir', '=', $id_tamuhadir)->delete();
    }
}
