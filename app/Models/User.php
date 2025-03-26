<?php

namespace App\Models;


use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'tb_user';

    public function getUser($nipp = false)
    {
        if ($nipp === false) {
            $hasil = DB::table('v_user')->select('id_terminal', 'nama_terminal', 'nipp', 'nama', 'email', 'id_divisi', 'nama_divisi', 'rec_stat', 'is_user_login')->orderBy('tgl_updated', 'desc');
            // $hasil = DB::select("SELECT id_terminal, nama_terminal, nipp, nama, email, id_divisi, nama_divisi, rec_stat, is_user_login 
            // FROM v_user ORDER BY tgl_updated DESC");
        } else { //done
            $hasil = collect(DB::select("SELECT kd_cabang_induk, kd_terminal_induk, kd_cabang, kd_terminal, id_terminal, nama_terminal, nipp, nama, 
            email, id_divisi, kd_divisi, nama_divisi, rec_stat, is_user_login FROM v_user WHERE nipp='" . $nipp . "'"))->first();
        }
        return $hasil;
    }

    public function getListUserAktif($cari)
    {
        $cari = strtolower($cari);
        $hasil =  DB::select("SELECT kd_cabang, kd_terminal, nipp, nipp||' | '||nama nippnama FROM tb_user WHERE rec_stat='A' 
                                AND (lower(nipp) like '%" . $cari . "%' OR lower(nama) like '%" . $cari . "%') ");
        return $hasil;
    }


    public function insertUser($data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function updateUser($data, $nipp)
    {
        return DB::table($this->table)->where('nipp', $nipp)->update($data);
    }

    public function deleteUser($nipp)
    {
        return DB::table($this->table)->where('nipp', '=', $nipp)->delete();
    }

    public function searchUsers($search)
    {
        // Tambahkan log untuk debugging
        \Log::info('Search Query Parameters:', [
            'search' => $search,
            'kd_cabang' => session()->get('kd_cabang'),
            'kd_terminal' => session()->get('kd_terminal')
        ]);

        $query = "SELECT 
                    u.nipp, 
                    u.nama, 
                    d.kd_divisi,
                    d.nama_divisi
                  FROM TB_USER u
                  JOIN TB_DIVISI d ON u.kd_divisi = d.kd_divisi
                  WHERE (UPPER(u.nama) LIKE UPPER('%$search%') 
                        OR u.nipp LIKE '%$search%')
                  AND u.kd_cabang = '" . session()->get('kd_cabang') . "'
                  AND u.kd_terminal = '" . session()->get('kd_terminal') . "'
                  ORDER BY u.nama ASC";

        // Log query untuk debugging
        \Log::info('Search Query:', ['query' => $query]);

        $result = DB::select($query);

        // Log hasil untuk debugging
        \Log::info('Search Results:', ['count' => count($result)]);

        return $result;
    }
}
