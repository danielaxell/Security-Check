<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function App\Helpers\cek_login;

class IsLoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (cek_login() == FALSE) {
            return redirect('/')->with('error_login', 'Silahkan login terlebih dahulu untuk mengakses data');
        } else {
            $id_role = request()->session()->get('id_role');
            $controller = request()->segment(1); //jika dipublish uri menggunakan segment 1
            $akses = collect(DB::select("SELECT count(*) jml FROM (SELECT id_role, 
                case 
                    when url_menu is not null then url_menu
                    else url_submenu
                end link 
                FROM v_role_menu_submenu where rec_stat_role ='A' and id_role = '" . $id_role . "') a WHERE link = '" . $controller . "'"))->first();
            if ($controller != 'auth') {
                if ($akses->jml < 1) {
                    if ($controller == 'akses') {
                        $role = collect(DB::select("select count(*) jml from v_akses where id_role='" . $id_role . "' and url_submenu='role'"))->first();
                        if ($role->jml < 1) {
                            return redirect('auth/blocked');
                        } else {
                            $role_update = collect(DB::select("select count(*) jml from v_akses where id_role='" . $id_role . "' and url_submenu='role' and U='Y'"))->first();
                            if ($role_update->jml < 1) {
                                echo json_encode(
                                    array(
                                        "status" => FALSE,
                                        "pesan_warning" => 'Anda Tidak Mempunya Hak Akses untuk Update Data'
                                    )
                                );
                                exit();
                            }
                        }
                    } else {
                        return redirect('auth/blocked');
                    }
                } else { // cek permission hak akses
                    $izin = $request->method(); //cek request method yg digunakan
                    if ($izin == "POST") {
                        $akses = collect(DB::select("select count(*) jml from v_akses where id_role='" . $id_role . "' and url_submenu='" . $controller . "' and C='Y'"))->first();
                        if ($akses->jml < 1) {
                            echo json_encode(
                                array(
                                    "status" => FALSE,
                                    "pesan_warning" => 'Anda Tidak Mempunya Hak Akses untuk Simpan Data'
                                )
                            );
                            exit();
                        }
                    } elseif ($izin == "PUT") {
                        $akses = collect(DB::select("select count(*) jml from v_akses where id_role='" . $id_role . "' and url_submenu='" . $controller . "' and U='Y'"))->first();
                        if ($akses->jml < 1) {
                            echo json_encode(
                                array(
                                    "status" => FALSE,
                                    "pesan_warning" => 'Anda Tidak Mempunya Hak Akses untuk Update Data'
                                )
                            );
                            exit();
                        }
                    } elseif ($izin == "DELETE") {
                        $akses = collect(DB::select("select count(*) jml from v_akses where id_role='" . $id_role . "' and url_submenu='" . $controller . "' and D='Y'"))->first();
                        if ($akses->jml < 1) {
                            echo json_encode(
                                array(
                                    "status" => FALSE,
                                    "pesan_warning" => 'Anda Tidak Mempunya Hak Akses untuk Delete Data'
                                )
                            );
                            exit();
                        }
                    }
                }
            }
        }
        return $next($request);
    }
}
