<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TamuHadirHist extends Model
{
    protected $table = 'tb_tamuhadir_hist';

    public function getTamuHadir($id_tamuhadir = false, $tgl_awal = false, $tgl_akhir = false)
    {
        if ($id_tamuhadir === false) { //done
            $hasil = DB::select("SELECT id_tamuhadir, nama_terminal, nama_divisi, nipp, nama_pegawai, ktp, nama_tamu, instansi, file_foto, 
           to_char(tgl_datang,'DD/MM/RRRR HH24:MI') tgl_datang,to_char(tgl_pulang,'DD/MM/RRRR HH24:MI') tgl_pulang FROM v_tamuhadir_hist WHERE
            kd_cabang='" . session()->get('kd_cabang') . "' AND kd_terminal='" . session()->get('kd_terminal') . "' AND 
            ((tgl_datang BETWEEN '" . $tgl_awal . "' AND '" . $tgl_akhir . "') 
            OR (tgl_pulang BETWEEN '" . $tgl_awal . "' AND '" . $tgl_akhir . "')) ORDER BY tgl_updated DESC");
        } else { //done
            $hasil = collect(DB::select("SELECT id_tamuhadir, no_kartuakses, nama_kartuakses, nama_terminal, nama_divisi, nipp, nama_pegawai, ktp, nama_tamu, instansi, file_foto, 
           to_char(tgl_datang,'DD/MM/RRRR HH24:MI') tgl_datang,to_char(tgl_pulang,'DD/MM/RRRR HH24:MI') tgl_pulang FROM v_tamuhadir_hist WHERE 
            id_tamuhadir='" . $id_tamuhadir . "'"))->first();
        }
        return $hasil;
    }

    public function insertTamuHadir($id_tamuhadir)
    {
        //done
        return DB::insert("INSERT INTO tb_tamuhadir_hist
        (id_tamuhadir, id_tamu, id_kartuakses, kd_cabang, kd_terminal, kd_divisi, nipp, tgl_datang, tgl_created, tgl_updated, user_created, user_updated)
        SELECT 
        id_tamuhadir, id_tamu, id_kartuakses, kd_cabang, kd_terminal, kd_divisi, nipp, tgl_datang, tgl_created, tgl_updated, user_created, user_updated
        FROM tb_tamuhadir WHERE id_tamuhadir = '" . $id_tamuhadir . "'");
    }

    public function updateTamuHadir($data, $id_tamuhadir)
    {
        return DB::table($this->table)->where('id_tamuhadir', $id_tamuhadir)->update($data);
    }
}
