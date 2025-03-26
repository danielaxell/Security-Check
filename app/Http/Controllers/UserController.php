<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Divisi;
use App\Models\Terminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private $user_model;
    private $divisi_model;
    private $terminal_model;
    private $data;
    private $users;
    private $divisies;
    private $cabangs;

    public function __construct()
    {
        $this->user_model = new User();
        $this->divisi_model = new Divisi();
        $this->terminal_model = new Terminal();
    }


    public function index()
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        //get data terminal
        $this->data['terminals'] = $this->terminal_model->getTerminal();

        $this->data['title'] = 'Setting User';
        echo view('user/index', $this->data);
    }

    // get data user di datatable
    public function getUser(Request $request)
    {
        if ($request->nipp) {
            $this->users = $this->user_model->getUser($request->nipp);
            return json_encode($this->users);
        } else {
            //get data semua list user
            $this->users = $this->user_model->getUser();
            return datatables($this->users)->toJson();
            // return datatables(User::query())->toJson(); 
            // seharusnya menggunakan ini, namun di modelnya perlu ditambakan protected $primaryKey='nipp' yg digunakan untuk sorting di querynya. dan protected $table diisi 'v_user';
            // shg query sbb : SELECT t2.* FROM ( SELECT rownum AS "rn", t1.* FROM (SELECT * FROM "V_USER" ORDER BY "NIPP" ASC) t1 WHERE rownum <= 10) t2 WHERE t2."rn" >= 1

            // return datatables(DB::table('v_user')->select('id_terminal', 'nama_terminal', 'nipp', 'nama', 'email', 'id_divisi', 'nama_divisi', 'rec_stat', 'is_user_login')->orderBy('tgl_updated', 'desc'))->toJson();
            // atau dapat menggunakan query di atas dan lbh flexible, dan kolom pertama yg diselect akan dijadikan acuan untuk sort data. jika kita tambah orderby lagi, maka yg jadi acuan order yg pertama dari settingan kita. sebaiknya tidak usah diberi order by lagi
            // shg query sbb : SELECT t2.* FROM ( SELECT rownum AS "rn", t1.* FROM (SELECT "ID_TERMINAL", "NAMA_TERMINAL", "NIPP", "NAMA", "EMAIL", "ID_DIVISI", "NAMA_DIVISI", "REC_STAT", "IS_USER_LOGIN" FROM "V_USER" ORDER BY "TGL_UPDATED" DESC, "ID_TERMINAL" ASC) t1 WHERE rownum <= 10) t2 WHERE t2."rn" >= 1

        }
        echo json_encode($this->users);
    }

    // get list user json
    public function getListUserAktif(Request $request)
    {
        if ($request->search) {
            $this->users = $this->user_model->getListUserAktif($request->search);
            // untuk ajax remote data, hasil query harus dibuat array dr objek, dan masing-masing objek harus memakai id dan text
            $hasil = array();
            foreach ($this->users as $row) {
                $hasil[] = array(
                    'id'    => $row->nipp,
                    'text'  => $row->nippnama,
                );
            }
            return json_encode($hasil);
            // return $this->response->setJSON($select);
        }
    }

    //function untuk simpan data
    public function store(Request $request)
    {
        $this->data = array(
            'id_terminal'   => $request->id_terminal,
            'nipp'          => $request->nipp,
            'nama'          => $request->nama,
            'email'         => $request->email,
            'id_divisi'     => $request->id_divisi,
            'rec_stat'      => $request->rec_stat
        );

        // cek nipp yg sudah terdaftar
        $this->users = $this->user_model->getUser($this->data['nipp']);
        if (isset($this->users)) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Tidak Dapat Melakukan Proses Penyimpanan. NIPP sudah terdaftar.'
                )
            );
            exit();
        }
        // get data cabang dari inputan dropdown list
        $this->cabangs = DB::table('tb_terminal')->select('kd_cabang', 'kd_terminal')->where('id_terminal', '=', $request->id_terminal)->first();
        $this->divisies = $this->divisi_model->getDivisi($this->data['id_divisi']);
        $this->data = array(
            'kd_cabang'     => $this->cabangs->kd_cabang,
            'kd_terminal'   => $this->cabangs->kd_terminal,
            'nipp'          => $this->data['nipp'],
            'password'      => password_hash('123', PASSWORD_DEFAULT),
            'nama'          => $this->data['nama'],
            'email'         => $this->data['email'],
            'kd_divisi'     => $this->divisies->kd_divisi,
            'is_user_login' => $request->is_user_login,
            'rec_stat'      => $this->data['rec_stat'],
            'tgl_created'   => date("Y-m-d H:i:s"),
            'tgl_updated'   => date("Y-m-d H:i:s"),
            'user_created'  => $request->session()->get('nipp'),
            'user_updated'  => $request->session()->get('nipp')
        );
        // insert data
        $simpan = $this->user_model->insertUser($this->data);
        if ($simpan) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Created User NIPP "' . $this->data['nipp'] . '" successfully.'
            ));
        }
    }


    // function untuk mengupdate data
    public function update(Request $request, $nipp)
    {
        $this->data = array(
            'id_terminal'   => $request->id_terminal,
            'nipp'          => $request->nipp,
            'nama'          => $request->nama,
            'email'         => $request->email,
            'id_divisi'     => $request->id_divisi,
            'rec_stat'      => $request->rec_stat
        );

        // validasi nipp jika ada inputan nipp asal-asalan
        $this->users = $this->user_model->getUser($this->data['nipp']);
        if (!(isset($this->users))) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Tidak Dapat Melakukan Proses Penyimpanan. NIPP tidak diketemukan.'
                )
            );
            exit();
        }
        // get data cabang dari inputan dropdown list
        $this->cabangs = DB::table('tb_terminal')->select('kd_cabang', 'kd_terminal')->where('id_terminal', '=', $request->id_terminal)->first();
        $this->divisies = $this->divisi_model->getDivisi($this->data['id_divisi']);
        $this->data = array(
            'kd_cabang'     => $this->cabangs->kd_cabang,
            'kd_terminal'   => $this->cabangs->kd_terminal,
            'nipp'          => $this->data['nipp'],
            'nama'          => $this->data['nama'],
            'email'         => $this->data['email'],
            'kd_divisi'     => $this->divisies->kd_divisi,
            'rec_stat'      => $this->data['rec_stat'],
            'is_user_login' => $request->is_user_login,
            'tgl_updated'   => date("Y-m-d H:i:s"),
            'user_updated'  => $request->session()->get('nipp')
        );
        // update data
        $ubah =  $this->user_model->updateUser($this->data, $this->data['nipp']);
        if ($ubah) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Updated User NIPP "' . $this->data['nipp'] . '" successfully.'
            ));
        }
    }

    // function untuk delete data
    public function delete($nipp)
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        // catch korelasi dengan tabel lain(foreign key)
        try {
            $hapus = $this->user_model->deleteUser($nipp);
        } catch (\Exception $e) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Tidak dapat menghapus User "' . $nipp . '" Pesan Error :' . $e->getMessage()
                )
            );
            exit();
        }

        // delete data
        if ($hapus) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Deleted User NIPP "' . $nipp . '" successfully.'
            ));
        }
    }

    public function getDivisiByNipp($nipp)
    {
        $user = $this->user_model->getUser($nipp);
        if ($user) {
            return json_encode([
                'id_divisi' => $user->id_divisi,
                'nama_divisi' => $user->nama_divisi
            ]);
        }
        return json_encode(null);
    }

    public function getUserDivisi($nipp)
    {
        // Debug log
        \Log::info('Getting divisi for NIPP: ' . $nipp);

        $user = $this->user_model->getUser($nipp);
        
        if ($user) {
            // Debug log
            \Log::info('User found:', [
                'nipp' => $nipp,
                'divisi' => $user->nama_divisi
            ]);

            return response()->json([
                'kd_divisi' => $user->kd_divisi,
                'nama_divisi' => $user->nama_divisi
            ]);
        }
        
        return response()->json([
            'error' => 'User not found'
        ], 404);
    }

    public function searchUsers(Request $request)
    {
        try {
            $search = $request->search;
            
            // Query untuk mencari user berdasarkan NIPP atau Nama
            $users = DB::select("SELECT 
                    u.nipp, 
                    u.nama, 
                    d.kd_divisi,
                    d.nama_divisi
                FROM TB_USER u
                JOIN TB_DIVISI d ON u.kd_divisi = d.kd_divisi
                WHERE (UPPER(u.nama) LIKE UPPER('%$search%') 
                    OR u.nipp LIKE '%$search%')
                AND u.kd_cabang = ? 
                AND u.kd_terminal = ?", 
                [
                    session()->get('kd_cabang'),
                    session()->get('kd_terminal')
                ]
            );

            return response()->json($users);
        } catch (\Exception $e) {
            \Log::error('Error searching users: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUserDetail($nipp)
    {
        try {
            $user = DB::selectOne("SELECT 
                    u.nipp, 
                    u.nama, 
                    d.kd_divisi,
                    d.nama_divisi
                FROM TB_USER u
                JOIN TB_DIVISI d ON u.kd_divisi = d.kd_divisi
                WHERE u.nipp = ?
                AND u.kd_cabang = ?
                AND u.kd_terminal = ?",
                [
                    $nipp,
                    session()->get('kd_cabang'),
                    session()->get('kd_terminal')
                ]
            );

            if ($user) {
                return response()->json($user);
            }

            return response()->json(['error' => 'User not found'], 404);
        } catch (\Exception $e) {
            \Log::error('Error getting user detail: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function searchByName(Request $request)
    {
        try {
            $search = $request->search;
            
            // Query untuk mencari user dari view v_user
            $users = DB::select("
                SELECT 
                    nipp,
                    nama,
                    kd_divisi,
                    nama_divisi
                FROM v_user 
                WHERE (
                    UPPER(nama) LIKE UPPER(?) 
                    OR UPPER(nipp) LIKE UPPER(?)
                )
                AND kd_cabang = ? 
                AND kd_terminal = ?
            ", [
                '%'.$search.'%',
                '%'.$search.'%',
                session()->get('kd_cabang'),
                session()->get('kd_terminal')
            ]);

            return response()->json($users);
        } catch (\Exception $e) {
            \Log::error('Error searching users: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
