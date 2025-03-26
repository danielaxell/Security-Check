<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Terminal;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    private $divisi_model;
    private $data;
    private $divisies;

    public function __construct()
    {
        $this->divisi_model = new Divisi();
        $this->terminal_model = new Terminal();
    }


    public function index()
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        $this->data['title'] = 'Setting Divisi';
        echo view('divisi/index', $this->data);
    }

    public function getDivisi(Request $request)
    {
        if ($request->id_divisi) {
            $this->divisies = $this->divisi_model->getDivisi($request->id_divisi);
        } else {
            $this->divisies = $this->divisi_model->getDivisi();
        }
        return json_encode($this->divisies);
    }

    public function getListDivisi(Request $request)
    {
        if ($request->id_terminal) {
            $this->divisies = $this->divisi_model->getListDivisi($request->id_terminal);
        } else {
            $this->divisies = $this->divisi_model->getListDivisi(false, $request->session()->get('kd_cabang'), $request->session()->get('kd_terminal'));
        }
        return json_encode($this->divisies);
    }

    //function untuk simpan data
    public function store(Request $request)
    {
        $this->data = array(
            'kd_divisi'     => $request->kd_divisi,
            'nama_divisi'   => $request->nama_divisi
        );

        // cek kd_divisi yg sudah terdaftar
        $this->divisies = $this->divisi_model->cekSamaDivisi($request->session()->get('kd_cabang'), $request->session()->get('kd_terminal'), false, $this->data['kd_divisi'], 'kd_divisi');
        if ($this->divisies == TRUE) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Tidak Dapat Melakukan Proses Penyimpanan. Kode Divisi sudah terdaftar.'
                )
            );
            exit();
        }
        // cek nama divisi yg sudah terdaftar
        $this->divisies = $this->divisi_model->cekSamaDivisi($request->session()->get('kd_cabang'), $request->session()->get('kd_terminal'), false, $this->data['nama_divisi'], 'nama_divisi');
        if ($this->divisies == TRUE) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Tidak Dapat Melakukan Proses Penyimpanan. Nama Divisi sudah terdaftar.'
                )
            );
            exit();
        }

        $this->data = array(
            'kd_cabang'     => $request->session()->get('kd_cabang'),
            'kd_terminal'   => $request->session()->get('kd_terminal'),
            'kd_divisi'     => $this->data['kd_divisi'],
            'nama_divisi'   => $this->data['nama_divisi']
        );
        // insert data
        $simpan = $this->divisi_model->insertDivisi($this->data);
        if ($simpan) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Created Divisi "' . $this->data['nama_divisi'] . '" Terminal "' . $request->session()->get('nama_terminal') . '" successfully.'
            ));
        }
    }


    // function untuk mengupdate data
    public function update(Request $request, $id_divisi)
    {
        $this->data = array(
            'kd_divisi'     => $request->kd_divisi,
            'nama_divisi'   => $request->nama_divisi
        );

        // cek kd_divisi yg sudah terdaftar
        $this->divisies = $this->divisi_model->cekSamaDivisi(
            $request->session()->get('kd_cabang'),
            $request->session()->get('kd_terminal'),
            $id_divisi,
            $this->data['kd_divisi'],
            'kd_divisi'
        );
        if ($this->divisies == TRUE) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Tidak Dapat Melakukan Proses Penyimpanan. Kode Divisi sudah terdaftar.'
                )
            );
            exit();
        }
        // cek nama divisi yg sudah terdaftar
        $this->divisies = $this->divisi_model->cekSamaDivisi(
            $request->session()->get('kd_cabang'),
            $request->session()->get('kd_terminal'),
            $id_divisi,
            $this->data['nama_divisi'],
            'nama_divisi'
        );
        if ($this->divisies == TRUE) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Tidak Dapat Melakukan Proses Penyimpanan. Nama Divisi sudah terdaftar.'
                )
            );
            exit();
        }

        // update data
        $ubah =  $this->divisi_model->updateDivisi($this->data, $id_divisi);
        if ($ubah) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Updated Divisi "' . $request->nama_divisi . '" Terminal "' . $request->session()->get('nama_terminal') . '" successfully.'
            ));
        }
    }

    // function untuk delete data
    public function delete(Request $request, $id_divisi)
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        // catch korelasi dengan tabel lain(foreign key)
        try {
            $hapus = $this->divisi_model->deleteDivisi($id_divisi);
        } catch (\Exception $e) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Tidak dapat menghapus Divisi "' . $request->nama_divisi . '" Terminal "' . $request->session()->get('nama_terminal') . '" karena berkaitan dengan User'
                )
            );
            exit();
        }

        // delete data
        if ($hapus) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Deleted Divisi "' . $request->nama_divisi . '" Terminal "' . $request->session()->get('nama_terminal') . '" successfully.'
            ));
        }
    }
}
