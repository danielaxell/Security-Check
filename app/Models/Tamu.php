<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Tamu extends Model
{
    protected $table = 'tb_tamu';

    public function getTamu($id_tamu = false, $ktp = false)
    {
        if ($id_tamu === false && $ktp === false) { //done
            $hasil = DB::select("SELECT id_tamu, ktp, nama, alamat, instansi FROM tb_tamu ORDER BY tgl_updated DESC");
        } elseif ($id_tamu == true && $ktp === false) {
            $hasil = collect(DB::select("SELECT id_tamu, ktp, nama, alamat, instansi, file_foto FROM tb_tamu WHERE id_tamu ='" . $id_tamu . "' ORDER BY tgl_updated DESC"))->first();
        } else { //done
            $hasil = collect(DB::select("SELECT id_tamu, ktp, nama, alamat, instansi, file_foto FROM tb_tamu WHERE ktp ='" . $ktp . "' ORDER BY tgl_updated DESC"))->first();
        }
        return $hasil;
    }

    public function cekTamu($id_tamu, $ktp)
    {
        $hasil = DB::select("SELECT id_tamu FROM tb_tamu WHERE ktp ='" . $ktp . "' and id_tamu <> '" . $id_tamu . "'");
        return $hasil;
    }
    public function getListTamu($cari)
    {
        $cari = strtolower($cari);
        $hasil =  DB::select("SELECT id_tamu, ktp||' | '||nama as ktpnama FROM tb_tamu WHERE (lower(ktp) like '%" . $cari . "%' 
        OR lower(nama) like '%" . $cari . "%') ");
        return $hasil;
    }

    public function getTamuTerakhir()
    {
        $hasil = DB::table($this->table)
            ->select('id_tamu')
            ->where('user_created', '=', request()->session()->get('nipp'))
            ->orderBy('tgl_updated', 'desc')
            ->limit(1)
            ->first();
        return $hasil;
    }

    public function insertTamu($data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function updateTamu($data, $id_tamu)
    {
        return DB::table($this->table)->where('id_tamu', $id_tamu)->update($data);
    }

    public function deleteTamu($id_tamu)
    {
        return DB::table($this->table)->where('id_tamu', '=', $id_tamu)->delete();
    }
}
