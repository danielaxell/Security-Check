<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    public function getCountPaketMasuk($kd_cabang = false, $kd_terminal = false)
    {
        if ($kd_cabang === false && $kd_terminal === false) { //done
            $hasil = collect(DB::select("SELECT count(id_paketmasuk) jml FROM v_paketmasuk"))
                ->first();
        } else {
            $hasil = collect(DB::select("SELECT count(id_paketmasuk) jml FROM v_paketmasuk WHERE kd_cabang='" . $kd_cabang . "' AND kd_terminal='" . $kd_terminal . "'"))
                ->first();
        }
        return $hasil;
    }

    public function getCountPaketMasukRegional($kd_cabang_induk = false, $kd_terminal_induk = false)
    {
        $hasil = collect(DB::select("SELECT count(id_paketmasuk) jml FROM v_paketmasuk WHERE kd_cabang_induk='" . $kd_cabang_induk . "' AND kd_terminal_induk='" . $kd_terminal_induk . "'"))
            ->first();

        return $hasil;
    }

    public function getCountPaketKeluar($kd_cabang = false, $kd_terminal = false)
    {
        if ($kd_cabang === false && $kd_terminal === false) { //done
            $hasil = collect(DB::select("SELECT count(id_paketkeluar) jml FROM v_paketkeluar"))
                ->first();
        } else {
            $hasil = collect(DB::select("SELECT count(id_paketkeluar) jml FROM v_paketkeluar WHERE kd_cabang='" . $kd_cabang . "' AND kd_terminal='" . $kd_terminal . "'"))
                ->first();
        }
        return $hasil;
    }

    public function getCountPaketKeluarRegional($kd_cabang_induk = false, $kd_terminal_induk = false)
    {
        $hasil = collect(DB::select("SELECT count(id_paketkeluar) jml FROM v_paketkeluar WHERE kd_cabang_induk='" . $kd_cabang_induk . "' AND kd_terminal_induk='" . $kd_terminal_induk . "'"))
            ->first();

        return $hasil;
    }

    public function getCountTamuHadir($kd_cabang = false, $kd_terminal = false)
    {
        if ($kd_cabang === false && $kd_terminal === false) { //done
            $hasil = collect(DB::select("SELECT count(id_tamuhadir) jml FROM v_tamuhadir"))
                ->first();
        } else {
            $hasil = collect(DB::select("SELECT count(id_tamuhadir) jml FROM v_tamuhadir WHERE kd_cabang='" . $kd_cabang . "' AND kd_terminal='" . $kd_terminal . "'"))
                ->first();
        }
        return $hasil;
    }

    public function getCountTamuHadirRegional($kd_cabang_induk = false, $kd_terminal_induk = false)
    {
        $hasil = collect(DB::select("SELECT count(id_tamuhadir) jml FROM v_tamuhadir WHERE kd_cabang_induk='" . $kd_cabang_induk . "' AND kd_terminal_induk='" . $kd_terminal_induk . "'"))
            ->first();

        return $hasil;
    }
}
