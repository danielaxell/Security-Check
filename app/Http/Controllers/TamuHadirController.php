<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Tamu;
use App\Models\TamuHadir;
use App\Models\TamuHadirHist;
use Illuminate\Http\Request;

class TamuHadirController extends Controller
{
    private $tamu_model;
    private $tamuhadir_model;
    private $tamuhadirhist_model;
    private $divisi_model;
    private $tamu;
    private $tamuhadir;
    private $data;

    public function __construct()
    {
        $this->tamuhadir_model = new TamuHadir();
        $this->tamuhadirhist_model = new TamuHadirHist();
        $this->tamu_model = new Tamu();
        $this->divisi_model = new Divisi();
    }


    public function index()
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        $this->data['title'] = 'Kehadiran Tamu';
        echo view('tamuhadir.index', $this->data);
    }

    public function getTamuHadir(Request $request)
    {
        if ($request->id_tamuhadir) {
            $this->tamuhadir = $this->tamuhadir_model->getTamuHadir($request->id_tamuhadir);
        } else {
            $this->tamuhadir = $this->tamuhadir_model->getTamuHadir();
        }
        return json_encode($this->tamuhadir);
    }

    //function untuk simpan data
    public function store(Request $request)
    {
        if ($request->akses == 'add_not_master_tamu') {
            // get data divisi dari inputan dropdown list
            $this->divisies = $this->divisi_model->getDivisi($request->id_divisi);
            // penyesuaian format tgl datang
            $tgl_datang = date("Y-m-d H:i", strtotime(str_replace('/', '-', $request->tgl_datang)));

            $this->data = array(
                'kd_cabang'     => $request->session()->get('kd_cabang'),
                'kd_terminal'   => $request->session()->get('kd_terminal'),
                'kd_divisi'     => $this->divisies->kd_divisi,
                'nipp'          => $request->nipp,
                'id_tamu'       => $request->id_tamu,
                'id_kartuakses' => $request->id_kartuakses,
                'tgl_datang'    => $tgl_datang,
                'tgl_created'   => date("Y-m-d H:i:s"),
                'tgl_updated'   => date("Y-m-d H:i:s"),
                'user_created'  => $request->session()->get('nipp'),
                'user_updated'  => $request->session()->get('nipp')
            );
            // insert data
            $simpan = $this->tamuhadir_model->insertTamuHadir($this->data);
            if ($simpan) {
                echo json_encode(array(
                    "status"        => TRUE,
                    "pesan_success" => 'Created Tamu Hadir "' . $request->nama . '". No.KTP "' . $request->ktp . '" successfully.'
                ));
            }
        } else { // add_master_tamu

            // SIMPAN MASTER TAMU
            // cek nipp yg sudah terdaftar
            $this->tamus = $this->tamu_model->getTamu(false, $request->ktp);
            if (isset($this->tamus)) {
                echo json_encode(
                    array(
                        "status" => FALSE,
                        "pesan_warning" => 'Tidak Dapat Melakukan Proses Penyimpanan. No KTP sudah terdaftar.'
                    )
                );
                exit();
            };

            // validasi jika tidak foto tamu
            $file_foto = $request->file_foto;
            if (!isset($file_foto)) {
                echo json_encode(
                    array(
                        "status" => FALSE,
                        "pesan_warning" => 'Tidak Dapat Melakukan Proses Penyimpanan. Mohon Melakukan Foto Tamu Dulu.'
                    )
                );
                exit();
            };
            // proses rubah raw foto dan simpan file foto
            if ($file_foto) {
                $file_foto = str_replace('data:image/jpeg;base64,', '', $file_foto);
                $file_foto = base64_decode($file_foto);
                $filename = 'fototamu_'  . uniqid() . '_' . date("Y-m-d") . '.png';
                $direktori = 'uploads/foto_tamu/';
                file_put_contents($direktori . $filename, $file_foto);
                $path_foto = $direktori . $filename;
            } else {
                $path_foto = null;
            }

            $this->data = array(
                'ktp'           => $request->ktp,
                'nama'          => $request->nama,
                'alamat'        => $request->alamat,
                'instansi'      => $request->instansi,
                'file_foto'     => $path_foto,
                'tgl_created'   => date("Y-m-d H:i:s"),
                'tgl_updated'   => date("Y-m-d H:i:s"),
                'user_created'  => $request->session()->get('nipp'),
                'user_updated'  => $request->session()->get('nipp')
            );

            // insert data
            $simpan = $this->tamu_model->insertTamu($this->data);
            if ($simpan) {
                // get id_tamu yg baru diinputkan
                $this->tamu = $this->tamu_model->getTamuTerakhir();
                // get data divisi dari inputan dropdown list
                $this->divisies = $this->divisi_model->getDivisi($request->id_divisi);
                // penyesuaian format tgl datang
                $tgl_datang = date("Y-m-d H:i", strtotime(str_replace('/', '-', $request->tgl_datang)));

                $this->data = array(
                    'kd_cabang'     => $request->session()->get('kd_cabang'),
                    'kd_terminal'   => $request->session()->get('kd_terminal'),
                    'kd_divisi'     => $this->divisies->kd_divisi,
                    'nipp'          => $request->nipp,
                    'id_tamu'       => $this->tamu->id_tamu,
                    'id_kartuakses' => $request->id_kartuakses,
                    'tgl_datang'    => $tgl_datang,
                    'tgl_created'   => date("Y-m-d H:i:s"),
                    'tgl_updated'   => date("Y-m-d H:i:s"),
                    'user_created'  => $request->session()->get('nipp'),
                    'user_updated'  => $request->session()->get('nipp')
                );
                // insert data
                $simpan = $this->tamuhadir_model->insertTamuHadir($this->data);
                if ($simpan) {
                    echo json_encode(array(
                        "status"        => TRUE,
                        "pesan_success" => 'Created Tamu Hadir "' . $request->nama . '". No.KTP "' . $request->ktp . '". Tanggal Datang "' . $request->tgl_datang . '" successfully.'
                    ));
                }
            }
        }
    }


    // function untuk mengupdate data
    public function serahterima(Request $request, $id_tamuhadir)
    {
        // penyesuaian format tgl datang
        $tgl_pulang = date("Y-m-d H:i", strtotime(str_replace('/', '-', $request->tgl_pulang)));

        $this->data = array(
            'tgl_pulang'    => $tgl_pulang,
            'tgl_updated'   => date("Y-m-d H:i:s"),
            'user_updated'  => $request->session()->get('nipp')
        );
        // insert data ke hist
        $insert = $this->tamuhadirhist_model->insertTamuHadir($id_tamuhadir);
        // update data di hist
        if ($insert) {
            $ubah =  $this->tamuhadirhist_model->updateTamuHadir($this->data, $id_tamuhadir);
        }
        if ($ubah) {
            $hapus = $this->tamuhadir_model->deleteTamuHadir($id_tamuhadir);
            if ($hapus) {
                echo json_encode(array(
                    "status"        => TRUE,
                    "pesan_success" => 'Serah Terima Kehadiran Tamu Nama "' . $request->nama . '". No.KTP "' . $request->ktp . '". Tanggal Datang "' . $request->tgl_datang . '" successfully.'
                ));
            }
        }
    }

    // function untuk delete data
    public function delete(Request $request, $id_tamuhadir)
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        // catch korelasi dengan tabel lain(foreign key)
        try {
            $hapus = $this->tamuhadir_model->deleteTamuHadir($id_tamuhadir);
        } catch (\Exception $e) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Tidak dapat menghapus Data Kehadiran Tamu No.KTP "' . $request->ktp . '", 
                    Nama "' . $request->nama . '", Tanggal Datang "' . $request->tgl_datang . '". Pesan Error : (' . $e->getMessage() . ')'
                )
            );
            exit();
        }

        // delete data
        if ($hapus) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Deleted Data Kehadiran Tamu No.KTP "' . $request->ktp . '", 
                Nama "' . $request->nama . '", Tanggal Datang "' . $request->tgl_datang . '" successfully.'
            ));
        }
    }
}
