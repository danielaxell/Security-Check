<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PaketKeluarHist extends Model
{
    protected $table = 'tb_paketkeluar_hist';

    public function getPaketKeluar($id_paketkeluar = false, $tgl_awal = false, $tgl_akhir = false)
    {
        if ($tgl_awal == true && $tgl_akhir == true && $id_paketkeluar === false) {
            $hasil = DB::select("SELECT id_paketkeluar, nama_terminal, nama_divisi, nama, nama_paket, tujuan_paket, nm_ekspedisi, TO_CHAR(TGL_TERIMA,'DD/MM/RRRR') tgl_terima, 
            TO_CHAR(TGL_SERAH,'DD/MM/RRRR') tgl_serah FROM v_paketkeluar_hist WHERE kd_cabang='" . session()->get('kd_cabang') . "' AND 
            kd_terminal='" . session()->get('kd_terminal') . "' AND ((tgl_terima BETWEEN '" . $tgl_awal . "' AND '" . $tgl_akhir . "') 
            OR (tgl_serah BETWEEN '" . $tgl_awal . "' AND '" . $tgl_akhir . "')) ORDER BY tgl_updated DESC");
        } else { //done
            $hasil = collect(DB::select("SELECT id_paketkeluar, nama_terminal, nama_divisi, nipp, nama, nama_paket, tujuan_paket, nm_ekspedisi, 
            TO_CHAR(TGL_TERIMA,'DD/MM/RRRR') tgl_terima, file_foto, file_ttd, TO_CHAR(TGL_SERAH,'DD/MM/RRRR') tgl_serah FROM v_paketkeluar_hist 
            WHERE id_paketkeluar='" . $id_paketkeluar . "'"))->first();
        }
        return $hasil;
    }

    public function insertPaketKeluar($id_paketkeluar)
    { //done
        return DB::insert("INSERT INTO tb_paketkeluar_hist
        (id_paketkeluar, kd_cabang, kd_terminal, kd_divisi, nipp, nama_paket, tujuan_paket, tgl_terima, tgl_created, tgl_updated, user_created, user_updated)
        SELECT 
        id_paketkeluar, kd_cabang, kd_terminal, kd_divisi, nipp, nama_paket, tujuan_paket, tgl_terima, tgl_created, tgl_updated, user_created, user_updated
        FROM tb_paketkeluar WHERE id_paketkeluar = '" . $id_paketkeluar . "'");
    }

    public function updatePaketKeluar($data, $id_paketkeluar)
    {
        return DB::table($this->table)->where('id_paketkeluar', $id_paketkeluar)->update($data);
    }
}
