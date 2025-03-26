<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PaketMasuk extends Model
{
    protected $table = 'tb_paketmasuk';

    public function getPaketMasuk($id_paketmasuk = false)
    {
        if ($id_paketmasuk === false) { //done
            $hasil = DB::select("SELECT id_paketmasuk, nama_terminal, nama_divisi, nipp, nama, nama_paket, nm_ekspedisi, 
                TO_CHAR(TGL_TERIMA,'DD/MM/RRRR') tgl_terima, file_foto, file_ttd FROM v_paketmasuk 
                WHERE kd_cabang='" . session()->get('kd_cabang') . "' AND kd_terminal='" . session()->get('kd_terminal') . "' ORDER BY tgl_updated DESC");
        } else { //done
            $hasil = collect(DB::select("SELECT id_paketmasuk, nama_terminal, nama_divisi, nipp, nama, nama_paket, nm_ekspedisi, 
                TO_CHAR(TGL_TERIMA,'DD/MM/RRRR') tgl_terima, file_foto, file_ttd FROM v_paketmasuk WHERE id_paketmasuk='" . $id_paketmasuk . "'"))->first();
        }
        return $hasil;
    }

    public function insertPaketMasuk($data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function updatePaketMasuk($data, $id_paketmasuk)
    {
        return DB::table($this->table)->where('id_paketmasuk', $id_paketmasuk)->update($data);
    }

    public function deletePaketMasuk($id_paketmasuk)
    {
        return DB::table($this->table)->where('id_paketmasuk', '=', $id_paketmasuk)->delete();
    }
}
