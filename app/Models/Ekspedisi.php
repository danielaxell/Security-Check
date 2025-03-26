<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Ekspedisi extends Model
{
    protected $table = 'tb_ekspedisi';

    public function getEkspedisi($id_ekspedisi = false)
    {
        if ($id_ekspedisi === false) { //done
            $hasil = DB::table($this->table)
                ->select('id_ekspedisi', 'nm_ekspedisi', 'ket_ekspedisi')
                ->orderBy('id_ekspedisi', 'desc')
                ->get();
        } else { //done
            $hasil = DB::table($this->table)
                ->select('id_ekspedisi', 'nm_ekspedisi', 'ket_ekspedisi')
                ->where('id_ekspedisi', '=', $id_ekspedisi)
                ->first();
        }
        return $hasil;
    }

    public function cekSamaEkspedisi($nm_ekspedisi, $id_eksepedisi = false)
    {
        if ($id_eksepedisi === false) {
            $hasil =  DB::select("SELECT id_ekspedisi FROM tb_ekspedisi WHERE lower(trim(nm_ekspedisi))=lower(trim('" . $nm_ekspedisi . "'))");
        } else {
            $hasil =  DB::select("SELECT id_ekspedisi FROM tb_ekspedisi WHERE lower(trim(nm_ekspedisi))=lower(trim('" . $nm_ekspedisi . "')) AND id_ekspedisi <> '" . $id_eksepedisi . "'");
        }
        return $hasil;
    }

    public function getListEkspedisi()
    {
        $hasil = DB::table($this->table)
            ->select('id_ekspedisi', 'nm_ekspedisi')
            ->orderBy('nm_ekspedisi', 'asc')
            ->get();
        return $hasil;
    }

    public function insertEkspedisi($data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function updateEkspedisi($data, $id_ekspedisi)
    {
        return DB::table($this->table)->where('id_ekspedisi', $id_ekspedisi)->update($data);
    }

    public function deleteEkspedisi($id_ekspedisi)
    {
        return DB::table($this->table)->where('id_ekspedisi', '=', $id_ekspedisi)->delete();
    }
}
