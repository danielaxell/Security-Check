<?php

namespace App\Http\Controllers;

use App\Models\KartuAkses;
use App\Models\Terminal;
use Illuminate\Http\Request;

class KartuAksesController extends Controller
{
    private $kartuakses_model;
    private $data;
    private $kartuakses;


    public function __construct()
    {
        $this->kartuakses_model = new KartuAkses();
        $this->terminal_model = new Terminal();
    }


    public function index()
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        $this->data['title'] = 'Setting ID Card';
        echo view('kartuakses/index', $this->data);
    }

    public function getKartuAkses(Request $request)
    {
        if ($request->id_kartuakses) {
            $this->kartuakses = $this->kartuakses_model->getKartuAkses($request->id_kartuakses);
        } else {
            $this->kartuakses = $this->kartuakses_model->getKartuAkses();
        }
        return json_encode($this->kartuakses);
    }

    public function getListKartuAksesTersedia(Request $request)
    {
        $this->kartuakses = $this->kartuakses_model->getListKartuAksesTersedia($request->session()->get('kd_cabang'), $request->session()->get('kd_terminal'));
        return json_encode($this->kartuakses);
    }

    //function untuk simpan data
    public function store(Request $request)
    {
        // cek no kartu akses yg sudah terdaftar
        $this->kartuakses = $this->kartuakses_model->cekSamaKartuAkses($request->session()->get('kd_cabang'), $request->session()->get('kd_terminal'), false, $request->no_kartuakses);

        if ($this->kartuakses == TRUE) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Tidak Dapat Melakukan Proses Penyimpanan. No Kartu Akses sudah terdaftar.'
                )
            );
            exit();
        }

        $this->data = array(
            'kd_cabang'         => $request->session()->get('kd_cabang'),
            'kd_terminal'       => $request->session()->get('kd_terminal'),
            'no_kartuakses'     => $request->no_kartuakses,
            'nama_kartuakses'   => $request->nama_kartuakses
        );
        // insert data
        $simpan = $this->kartuakses_model->insertKartuAkses($this->data);
        if ($simpan) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Created Kartu Akses "' . $request->no_kartuakses . '" Terminal "' . $request->session()->get('nama_terminal') . '" successfully.'
            ));
        }
    }


    // function untuk mengupdate data
    public function update(Request $request, $id_kartuakses)
    {
        // validasi jika ada data no kartu akses yang sama        
        $this->kartuakses = $this->kartuakses_model->cekSamaKartuAkses(
            $request->session()->get('kd_cabang'),
            $request->session()->get('kd_terminal'),
            $id_kartuakses,
            $request->no_kartuakses
        );
        if ($this->kartuakses == TRUE) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Tidak Dapat Melakukan Proses Penyimpanan. No Kartu Akses sudah terdaftar.'
                )
            );
            exit();
        }

        $this->data = array(
            'no_kartuakses'     => $request->no_kartuakses,
            'nama_kartuakses'   => $request->nama_kartuakses
        );
        // update data
        $ubah =  $this->kartuakses_model->updateKartuAkses($this->data, $id_kartuakses);

        if ($ubah) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Updated Kartu Akses "' . $request->no_kartuakses . '" Terminal "' . $request->session()->get('nama_terminal') . '" successfully.'
            ));
        }
    }

    // function untuk delete data
    public function delete(Request $request, $id_kartuakses)
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        // catch korelasi dengan tabel lain(foreign key)
        try {
            $hapus = $this->kartuakses_model->deleteKartuAkses($id_kartuakses);
        } catch (\Exception $e) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Tidak dapat menghapus Kartu Akses "' . $request->no_kartuakses . '" Terminal "' . $request->session()->get('nama_terminal') . '". Pesan error : ' . $e->getMessage()
                )
            );
            exit();
        }

        // delete data
        if ($hapus) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Deleted Kartu Akses "' . $request->no_kartuakses . '" Terminal "' . $request->session()->get('nama_terminal') . '" successfully.'
            ));
        }
    }
}
