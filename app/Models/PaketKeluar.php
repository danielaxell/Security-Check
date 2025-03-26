<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PaketKeluar extends Model
{
    protected $table = 'tb_paketkeluar';

    public function getPaketKeluar($id_paketkeluar = false)
    {
        if ($id_paketkeluar === false) {
            $hasil = DB::select("SELECT id_paketkeluar, nama_terminal, nama_divisi, nipp, nama, nama_paket, tujuan_paket, 
            TO_CHAR(tgl_terima,'DD/MM/RRRR') tgl_terima FROM v_paketkeluar 
                WHERE kd_cabang='" . session()->get('kd_cabang') . "' AND kd_terminal='" . session()->get('kd_terminal') . "' ORDER BY tgl_updated DESC");
        } else { //done
            $hasil = collect(DB::select("SELECT id_paketkeluar, nama_terminal, nama_divisi, nipp, nama, nama_paket, tujuan_paket, 
            TO_CHAR(tgl_terima,'DD/MM/RRRR') tgl_terima FROM v_paketkeluar WHERE id_paketkeluar='" . $id_paketkeluar . "'"))->first();
        }
        return $hasil;
    }


    public function insertPaketKeluar($data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function updatePaketKeluar($data, $id_paketkeluar)
    {
        return DB::table($this->table)->where('id_paketkeluar', $id_paketkeluar)->update($data);
    }

    public function deletePaketKeluar($id_paketkeluar)
    {
        return DB::table($this->table)->where('id_paketkeluar', '=', $id_paketkeluar)->delete();
    }
}
