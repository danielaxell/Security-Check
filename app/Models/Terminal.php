<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    protected $table = 'tb_terminal';

    public function getTerminal($id_terminal = false, $kd_cabang = false, $kd_terminal = false)
    {
        if ($id_terminal === false && $kd_cabang === false && $kd_terminal === false) { //done
            $hasil = DB::table($this->table)
                ->select('id_terminal', 'nama_terminal')
                ->get();
        } elseif ($id_terminal == false && $kd_cabang == true && $kd_terminal == true) { //done
            $hasil = DB::table($this->table)
                ->select('id_terminal', 'nama_terminal')
                ->where([['kd_cabang', '=', $kd_cabang], ['kd_terminal', '=', $kd_terminal]])
                ->first();
        } else { //done
            $hasil = DB::table($this->table)
                ->select('kd_cabang_induk', 'kd_terminal_induk', 'kd_cabang', 'kd_terminal', 'nama_terminal')
                ->where('id_terminal', '=', $id_terminal)
                ->first();
        }
        return $hasil;
    }

    public function getTerminalRegional($kd_cabang_induk, $kd_terminal_induk)
    {
        $hasil = DB::table($this->table)
            ->select('id_terminal', 'nama_terminal')
            ->where([['kd_cabang_induk', '=', $kd_cabang_induk], ['kd_terminal_induk', '=', $kd_terminal_induk]])
            ->get();
        return $hasil;
    }


    public function insertTerminal($data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function updateTerminal($data, $id_terminal)
    {
        return DB::table($this->table)->where('id_terminal', $id_terminal)->update($data);
    }

    public function deleteTerminal($id_terminal)
    {
        return DB::table($this->table)->where('id_terminal', '=', $id_terminal)->delete();
    }
}
