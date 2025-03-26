<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PaketMasukHist extends Model
{
    protected $table = 'tb_paketmasuk_hist';

    public function getPaketMasuk($id_paketmasuk = false, $tgl_awal = false, $tgl_akhir = false)
    {
        if ($tgl_awal == true && $tgl_akhir == true && $id_paketmasuk === false) {
            $hasil = DB::select("SELECT id_paketmasuk, nama_terminal, nama_divisi, nama, nama_paket, nm_ekspedisi, TO_CHAR(TGL_TERIMA,'DD/MM/RRRR') tgl_terima, 
            TO_CHAR(TGL_SERAH,'DD/MM/RRRR') tgl_serah FROM v_paketmasuk_hist WHERE kd_cabang='" . session()->get('kd_cabang') . "' AND 
            kd_terminal='" . session()->get('kd_terminal') . "' AND ((tgl_terima BETWEEN '" . $tgl_awal . "' AND '" . $tgl_akhir . "') 
            OR (tgl_serah BETWEEN '" . $tgl_awal . "' AND '" . $tgl_akhir . "')) ORDER BY tgl_updated DESC");
        } else { //done
            $hasil = collect(DB::select("SELECT id_paketmasuk, nama_terminal, nama_divisi, nipp, nama, nama_paket, penerima_fisik, nm_ekspedisi, TO_CHAR(TGL_TERIMA,'DD/MM/RRRR') tgl_terima, 
            TO_CHAR(TGL_SERAH,'DD/MM/RRRR') tgl_serah, file_foto, file_ttd FROM v_paketmasuk_hist WHERE id_paketmasuk='" . $id_paketmasuk . "'"))->first();
        }
        return $hasil;
    }

    public function insertPaketMasuk($id_paketmasuk)
    { //done
        return DB::insert("INSERT INTO tb_paketmasuk_hist
        (id_paketmasuk, kd_cabang, kd_terminal, kd_divisi, nipp, nama_paket, id_ekspedisi, tgl_terima, file_foto, file_ttd, tgl_created, tgl_updated, user_created, user_updated)
        SELECT 
        id_paketmasuk, kd_cabang, kd_terminal, kd_divisi, nipp, nama_paket, id_ekspedisi, tgl_terima, file_foto, file_ttd, tgl_created, tgl_updated, user_created, user_updated
        FROM tb_paketmasuk WHERE id_paketmasuk = '" . $id_paketmasuk . "'");
    }

    public function updatePaketMasuk($data, $id_paketmasuk)
    {
        return DB::table($this->table)->where('id_paketmasuk', $id_paketmasuk)->update($data);
    }
}
