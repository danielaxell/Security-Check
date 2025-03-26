<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class KartuAkses extends Model
{
    protected $table = 'tb_kartuakses';

    public function getKartuAkses($id_kartuakses = false)
    {
        if ($id_kartuakses === false) {
            $hasil = DB::select("SELECT * FROM v_kartuakses WHERE kd_cabang ='" . session()->get('kd_cabang') . "' AND kd_terminal ='" . session()->get('kd_terminal') . "' ORDER BY id_kartuakses DESC");
        } else { //done
            $hasil = collect(DB::select("SELECT * FROM v_kartuakses WHERE id_kartuakses ='" . $id_kartuakses . "'"))->first();
        }
        return $hasil;
    }

    public function cekSamaKartuAkses($kd_cabang, $kd_terminal, $id_kartuakses = false, $no_kartuakses)
    {
        if ($id_kartuakses === false) {
            $hasil =  DB::select("SELECT id_kartuakses FROM tb_kartuakses WHERE kd_cabang='" . $kd_cabang . "' and kd_terminal='" . $kd_terminal . "'
            AND lower(trim(no_kartuakses))=lower(trim('" . $no_kartuakses . "'))");
        } else {
            $hasil =  DB::select("SELECT id_kartuakses FROM tb_kartuakses WHERE kd_cabang='" . $kd_cabang . "' and kd_terminal='" . $kd_terminal . "'
            AND lower(trim(no_kartuakses))=lower(trim('" . $no_kartuakses . "')) AND id_kartuakses <> '" . $id_kartuakses . "'");
        }
        return $hasil;
    }

    public function getListKartuAksesTersedia($kd_cabang, $kd_terminal)
    { //done
        $hasil =  DB::select("SELECT a.id_kartuakses, a.no_kartuakses||' | '||a.nama_kartuakses nonama_kartuakses FROM tb_kartuakses a
                WHERE a.kd_cabang='" . $kd_cabang . "' and a.kd_terminal='" . $kd_terminal . "' AND a.no_kartuakses = 'TANPA_KARTU'
                    UNION ALL
                SELECT a.id_kartuakses, a.no_kartuakses||' | '||a.nama_kartuakses nonama_kartuakses FROM tb_kartuakses a
                LEFT JOIN tb_tamuhadir b ON (a.kd_cabang=b.kd_cabang AND a.kd_terminal=b.kd_terminal AND a.id_kartuakses=b.id_kartuakses) 
                WHERE a.kd_cabang='" . $kd_cabang . "' and a.kd_terminal='" . $kd_terminal . "' AND b.id_tamuhadir IS NULL AND a.no_kartuakses != 'TANPA_KARTU' 
                ORDER BY nonama_kartuakses");
        return $hasil;
    }

    public function insertKartuAkses($data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function updateKartuAkses($data, $id_kartuakses)
    {
        return DB::table($this->table)->where('id_kartuakses', $id_kartuakses)->update($data);
    }

    public function deleteKartuAkses($id_kartuakses)
    {
        return DB::table($this->table)->where('id_kartuakses', '=', $id_kartuakses)->delete();
    }
}
