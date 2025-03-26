<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    protected $table = 'tb_divisi';

    public function getDivisi($id_divisi = false)
    {
        if ($id_divisi === false) {
            $hasil = DB::select("SELECT * FROM v_divisi WHERE kd_cabang ='" . request()->session()->get('kd_cabang') . "' 
            AND kd_terminal ='" . request()->session()->get('kd_terminal') . "' ORDER BY id_divisi DESC");
        } else { //done
            $hasil = collect(DB::select("SELECT * FROM v_divisi WHERE id_divisi ='" . $id_divisi . "' ORDER BY id_divisi DESC"))->first();
        }
        return $hasil;
    }

    public function getNamaDivisi($kd_cabang, $kd_terminal, $kd_divisi)
    {
        $hasil = DB::table($this->table)
            ->select('nama_divisi')
            ->where(['kd_cabang', '=', $kd_cabang], ['kd_terminal', '=', $kd_terminal], ['kd_divisi', '=', $kd_divisi])
            ->first();
        return $hasil;
    }

    public function cekSamaDivisi($kd_cabang, $kd_terminal, $id_divisi = false, $cek, $type)
    {
        if ($id_divisi === false) {
            if ($type == 'kd_divisi') {
                $hasil = DB::table($this->table)
                    ->select('kd_divisi')
                    ->where([['kd_cabang', '=', $kd_cabang], ['kd_terminal', '=', $kd_terminal], ['kd_divisi', '=', $cek]])
                    ->first();
            } elseif ($type == 'nama_divisi') {
                $hasil =  DB::select("SELECT kd_divisi FROM tb_divisi WHERE kd_cabang='" . $kd_cabang . "' and kd_terminal='" . $kd_terminal . "'
                        AND lower(trim(nama_divisi))=lower(trim('" . $cek . "'))");
            }
        } else {
            if ($type == 'kd_divisi') {
                $hasil =  DB::select("SELECT kd_divisi FROM tb_divisi WHERE kd_cabang='" . $kd_cabang . "' and kd_terminal='" . $kd_terminal . "'
                        AND lower(trim(kd_divisi))=lower(trim('" . $cek . "')) AND id_divisi <> '" . $id_divisi . "'");
            } elseif ($type == 'nama_divisi') {
                $hasil =  DB::select("SELECT kd_divisi FROM tb_divisi WHERE kd_cabang='" . $kd_cabang . "' and kd_terminal='" . $kd_terminal . "'
            AND lower(trim(nama_divisi))=lower(trim('" . $cek . "')) AND id_divisi <> '" . $id_divisi . "'");
            }
        }
        return $hasil;
    }

    public function getListDivisi($id_terminal = false, $kd_cabang = false, $kd_terminal = false)
    { //done
        if ($id_terminal == true && $kd_cabang === false && $kd_terminal === false) {
            $hasil =  DB::select("SELECT id_divisi, nama_divisi  FROM v_divisi WHERE id_terminal='" . $id_terminal . "'");
        } else {
            $hasil =  DB::select("SELECT id_divisi, nama_divisi  FROM v_divisi WHERE 
            kd_cabang='" . $kd_cabang . "' and kd_terminal='" . $kd_terminal . "'");
        }
        return $hasil;
    }

    public function insertDivisi($data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function updateDivisi($data, $id_divisi)
    {
        return DB::table($this->table)->where('id_divisi', $id_divisi)->update($data);
    }

    public function deleteDivisi($id_divisi)
    {
        return DB::table($this->table)->where('id_divisi', '=', $id_divisi)->delete();
    }
}
