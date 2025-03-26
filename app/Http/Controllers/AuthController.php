<?php

namespace App\Http\Controllers;

use App\Models\Auth;
use App\Models\User;
use App\Models\Divisi;
use App\Models\Terminal;

use Illuminate\Http\Request;
use function App\Helpers\cek_login;
use function App\Helpers\cek_cookie;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    private $data;
    private $auth_model;
    private $terminal_model;
    private $divisi_model;
    private $user_model;
    private $terminals;
    private $divisies;
    private $users;

    public function __construct()
    {
        $this->auth_model = new Auth();
        $this->terminal_model = new Terminal();
        $this->divisi_model = new Divisi();
        $this->user_model = new User();
    }

    public function index()
    {
        //cek cookie
        cek_cookie();
        // cek apakah sudah login
        if (cek_login() == TRUE) {
            return redirect('dashboard');
        }
        return view('auth.login');
    }

    public function login()
    {
        //cek cookie
        cek_cookie();
        // cek apakah sudah login
        if (cek_login() == TRUE) {
            return redirect('dashboard');
        }
        return view('auth.login');
    }

    public function akses(Request $request)
    {
        \Log::info('Login Attempt:', [
            'nipp' => $request->nipp
        ]);

        $user = DB::table('TB_USER')
            ->where('nipp', $request->nipp)
            ->first();

        \Log::info('User Found:', [
            'user_exists' => ($user ? 'yes' : 'no'),
            'password_verify_result' => $user ? password_verify($request->password, $user->password) : false
        ]);

        if ($user) {
            if (password_verify($request->password, $user->password)) {
                if ($request->remember) {
                    session()->put('remember', $request->remember);
                }
                session()->put('nipp', $user->nipp);
                return json_encode(["status" => TRUE]);
            } else {
                return json_encode([
                    "status" => FALSE,
                    "pesan_error" => 'Password yang Anda masukan salah.'
                ]);
            }
        } else {
            return json_encode([
                "status" => FALSE,
                "pesan_error" => 'NIPP yang Anda masukan tidak terdaftar.'
            ]);
        }
    }

    public function pilih_role(Request $request)
    {
        $this->data = ['roles' =>   $this->auth_model->getListRole($request->session()->get('nipp'))];
        return view('auth.pilih_role', $this->data);
    }


    public function proses_login(Request $request)
    {
        $this->data = [
            'id_role'   => $request->id_role,
            'nipp'      => $request->session()->get('nipp')
        ];
        // get data/informasi user
        $cek_login = $this->auth_model->cek_login($this->data['nipp'], $this->data['id_role']);
        // jika data ditemukan
        if ($cek_login == TRUE) {
            if ($request->session()->get('remember') != null) {
                // set cookie 1 bulan //
                $response = response()->json(array("status" => TRUE))->withCookie(cookie('key1', $this->data['nipp'], 43200))
                    ->withCookie(cookie('key2', hash('sha256', $this->data['nipp']), 43200))
                    ->withCookie(cookie('key3', $this->data['id_role'], 43200));
            } else {
                $response = json_encode(array("status" => TRUE));
            }
            // set session dari informasi/data user 
            $request->session()->put('kd_cabang_induk', $cek_login->kd_cabang_induk);
            $request->session()->put('kd_terminal_induk', $cek_login->kd_terminal_induk);
            $request->session()->put('kd_cabang', $cek_login->kd_cabang);
            $request->session()->put('kd_terminal', $cek_login->kd_terminal);
            $request->session()->put('nama', $cek_login->nama);
            $request->session()->put('nama_terminal', $cek_login->nama_terminal);
            $request->session()->put('id_role', $this->data['id_role']);
            $request->session()->put('nama_role', $cek_login->nama_role);
            $request->session()->put('kd_divisi', $cek_login->kd_divisi);

            return $response;
        }
    }


    public function change_pwd(Request $request)
    {
        $this->data = array(
            'old_password'        =>   $request->old_password,
            'password'            =>   $request->password,
            'confirm_password'    =>   $request->confirm_password
        );

        $cek_user = $this->auth_model->cek_user(session()->get('nipp'));
        // jika password sama
        if (password_verify($this->data['old_password'], $cek_user->PASSWORD)) {
            $this->data = array(
                'password'      => password_hash($request->password, PASSWORD_DEFAULT),
                'tgl_updated'   => date("Y-m-d H:i:s"),
                'user_updated'  => $request->session()->get('nipp')
            );
            // simpan data password
            $simpan = $this->auth_model->updatePwd($this->data, session()->get('nipp'));
            if ($simpan) {
                echo json_encode(
                    array(
                        'status' => TRUE,
                        'pesan_success' => 'Berhasil Ubah Password'
                    )
                );
            }
            // jika password salah
        } else {
            echo json_encode(
                array(
                    'status' => FALSE,
                    'pesan_warning' => 'Tidak Dapat Melakukan Proses Perubahan. Password Lama Salah'
                )
            );
            exit();
        }
    }


    // pilih terminal untuk change session
    public function getTerminal(Request $request)
    {
        //get data dropdwon list terminal
        $this->users = $this->user_model->getUser($request->session()->get('nipp'));
        if ($this->users->kd_cabang == $this->users->kd_cabang_induk && $this->users->kd_terminal == $this->users->kd_terminal_induk) { //jika regional
            $this->terminals = $this->terminal_model->getTerminalRegional($this->users->kd_cabang, $this->users->kd_terminal);
        } elseif ($this->users->kd_cabang == "1" && $this->users->kd_terminal == "1") { //jika kanpus
            $this->terminals = $this->terminal_model->getTerminal();
        } else {
            $this->terminals = $this->terminal_model->getTerminal(false, $this->users->kd_cabang, $this->users->kd_terminal);
        }
        echo json_encode($this->terminals);
    }

    public function change_session(Request $request)
    {
        $this->data = array(
            'id_terminal'   =>   $request->id_terminal,
            'id_divisi'     =>   $request->id_divisi
        );

        // dd($this->data);

        $this->terminals = $this->terminal_model->getTerminal($this->data['id_terminal']);
        $this->divisies = $this->divisi_model->getDivisi($this->data['id_divisi']);
        $request->session()->put('kd_cabang_induk', $this->terminals->kd_cabang_induk);
        $request->session()->put('kd_terminal_induk', $this->terminals->kd_terminal_induk);
        $request->session()->put('kd_cabang', $this->terminals->kd_cabang);
        $request->session()->put('kd_terminal', $this->terminals->kd_terminal);
        $request->session()->put('nama_terminal', $this->terminals->nama_terminal);
        $request->session()->put('kd_divisi', $this->divisies->kd_divisi);
        $request->session()->flash('success', 'Berhasil Ubah Session Terminal "' . $request->session()->get('nama_terminal') . '". Divisi "' . $this->divisies->nama_divisi . '"');
        echo json_encode(array('status' => TRUE));
    }

    public function blocked()
    {
        $this->data['title'] = '403 Forbidden Access';
        echo view('auth/blocked', $this->data);
    }

    public function logout(Request $request)
    {
        // detelet cookie
        Cookie::queue(Cookie::forget('key1'));
        Cookie::queue(Cookie::forget('key2'));
        Cookie::queue(Cookie::forget('key3'));

        // kill session
        $request->session()->flush();
        return redirect('auth/login');
    }

    public function changePassword(Request $request)
    {
        try {
            // Debug input
            \Log::info('Change Password Request:', [
                'nipp' => session()->get('nipp'),
                'old_password' => $request->old_password,
                'new_password' => $request->new_password
            ]);

            // Validasi input
            $validator = \Validator::make($request->all(), [
                'old_password' => 'required',
                'new_password' => 'required|min:3',
                'confirm_password' => 'required|same:new_password'
            ], [
                'old_password.required' => 'Password lama harus diisi',
                'new_password.required' => 'Password baru harus diisi',
                'new_password.min' => 'Password baru minimal 3 karakter',
                'confirm_password.required' => 'Konfirmasi password harus diisi',
                'confirm_password.same' => 'Konfirmasi password tidak sama dengan password baru'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Ambil data user
            $user = DB::table('TB_USER')
                ->where('nipp', session()->get('nipp'))
                ->first();

            \Log::info('Current User Data:', [
                'user' => $user,
                'password_verify_result' => password_verify($request->old_password, $user->password)
            ]);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            // Verifikasi password lama menggunakan password_verify
            if (!password_verify($request->old_password, $user->password)) {
                \Log::warning('Password verification failed', [
                    'old_password' => $request->old_password,
                    'stored_hash' => $user->password
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Password lama tidak sesuai'
                ], 422);
            }

            try {
                // Hash password baru dengan bcrypt
                $newPasswordHash = password_hash($request->new_password, PASSWORD_DEFAULT);
                
                // Update password
                $updated = DB::table('TB_USER')
                    ->where('nipp', session()->get('nipp'))
                    ->update([
                        'password' => $newPasswordHash,
                        'tgl_updated' => DB::raw('SYSDATE'),
                        'user_updated' => session()->get('nipp')
                    ]);

                \Log::info('Password Update Result:', [
                    'updated' => $updated,
                    'new_hash' => $newPasswordHash
                ]);

                if ($updated) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Password berhasil diubah'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal mengubah password'
                    ], 500);
                }

            } catch (\Exception $dbError) {
                \Log::error('Database Error:', [
                    'error' => $dbError->getMessage()
                ]);
                throw $dbError;
            }

        } catch (\Exception $e) {
            \Log::error('Change Password Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah password: ' . $e->getMessage()
            ], 500);
        }
    }

    public function forceUpdatePassword(Request $request)
    {
        try {
            // Validasi admin atau permission khusus seharusnya ditambahkan di sini
            
            $nipp = $request->nipp;
            $newPassword = $request->password;
            
            // Update password langsung ke database
            $updated = DB::table('TB_USER')
                ->where('NIPP', $nipp)
                ->update([
                    'password' => password_hash($newPassword, PASSWORD_DEFAULT),
                    'TGL_UPDATE' => DB::raw('SYSDATE')
                ]);
            
            // Log hasil update
            \Log::info('Password update attempt:', [
                'nipp' => $nipp,
                'success' => $updated,
                'new_password_hash' => password_hash($newPassword, PASSWORD_DEFAULT)
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diupdate di database'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error updating password: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate password: ' . $e->getMessage()
            ], 500);
        }
    }
}
