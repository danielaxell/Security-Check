<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

function cek_cookie()
{
    // cek cookie
    if (request()->cookie('key1') != null && request()->cookie('key2') != null && request()->cookie('key3') != null) {
        $key1 = request()->cookie('key1');
        $key2 = request()->cookie('key2');
        $key3 = request()->cookie('key3');
        $hasil =  collect(DB::select("SELECT kd_cabang_induk, kd_terminal_induk, kd_cabang, kd_terminal, nama_terminal, nipp, nama, nama_role, kd_divisi FROM v_user_role where nipp='" . $key1 . "' and id_role='" . $key3 . "'"))
            ->first();
        if (hash('sha256', $hasil->nipp) === $key2) {
            request()->session()->put('nipp', $key1);
            request()->session()->put('kd_cabang_induk', $hasil->kd_cabang_induk);
            request()->session()->put('kd_terminal_induk', $hasil->kd_terminal_induk);
            request()->session()->put('kd_cabang', $hasil->kd_cabang);
            request()->session()->put('kd_terminal', $hasil->kd_terminal);
            request()->session()->put('nama', $hasil->nama);
            request()->session()->put('nama_terminal', $hasil->nama_terminal);
            request()->session()->put('id_role', $key3);
            request()->session()->put('nama_role', $hasil->nama_role);
            request()->session()->put('kd_divisi', $hasil->kd_divisi);
        }
    }
}


function cek_login()
{
    $result = true;
    if (request()->session()->get('nipp') == null) {
        $result = false;
    }
    return $result;
}

function cek_regional()
{
    $result = false;
    if (request()->session()->get('kd_cabang_induk') == request()->session()->get('kd_cabang') && request()->session()->get('kd_terminal_induk') == request()->session()->get('kd_terminal')) {
        $result = true;
    }
    return $result;
}

function cek_kanpus()
{
    $result = false;
    if (request()->session()->get('kd_cabang') == "1" && request()->session()->get('kd_terminal') == "1") {
        $result = true;
    }
    return $result;
}


function cek_sessionMenu($id_role)
{
    $result = false;
    $query = DB::table('tb_session')
        ->where('id_role', '=', $id_role)
        ->count();
    if ($query > 0) {
        $result = true;
    }
    return $result;
}
