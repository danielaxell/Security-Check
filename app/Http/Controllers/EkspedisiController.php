<?php

namespace App\Http\Controllers;

use App\Models\Ekspedisi;
use Illuminate\Http\Request;

class EkspedisiController extends Controller
{
    private $ekspedisi_model;
    private $ekspedisies;
    private $data;

    public function __construct()
    {
        $this->ekspedisi_model = new Ekspedisi();
    }


    public function index()
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        $this->data['title'] = 'Setting Ekspedisi';
        echo view('ekspedisi/index', $this->data);
    }

    // get data ekspedisi datatable
    public function getEkspedisi()
    {
        $this->ekspedisies = $this->ekspedisi_model->getEkspedisi();
        echo json_encode($this->ekspedisies);
    }

    // get data detail ekspedisi
    public function getInfoEkspedisi(Request $request)
    {
        if ($request->id_ekspedisi) {
            $this->ekspedisies = $this->ekspedisi_model->getEkspedisi($request->id_ekspedisi);
        }
        echo json_encode($this->ekspedisies);
    }

    //function untuk simpan data
    public function store(Request $request)
    {
        $this->data = array(
            'nm_ekspedisi'  => $request->nm_ekspedisi,
            'ket_ekspedisi' => $request->ket_ekspedisi
        );

        // cek nama yg sudah terdaftar
        $this->ekspedisies = $this->ekspedisi_model->cekSamaEkspedisi($this->data['nm_ekspedisi']);
        if ($this->ekspedisies == TRUE) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Nama Ekspedisi tersebut Sudah Ada.'
                )
            );
            exit();
        }
        // insert data
        $simpan = $this->ekspedisi_model->insertEkspedisi($this->data);
        if ($simpan) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Created Ekspedisi "' . $this->data['nm_ekspedisi'] . '" successfully.'
            ));
        }
    }


    // function untuk mengupdate data
    public function update(Request $request, $id_ekspedisi)
    {
        $this->data = array(
            'nm_ekspedisi'  => $request->nm_ekspedisi,
            'ket_ekspedisi' => $request->ket_ekspedisi
        );

        // cek nama yg sudah terdaftar
        $this->ekspedisies = $this->ekspedisi_model->cekSamaEkspedisi($this->data['nm_ekspedisi'], $id_ekspedisi);
        if ($this->ekspedisies == TRUE) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Tidak Dapat Melakukan Proses Penyimpanan. Nama Ekspedisi tersebut Sudah Ada.'
                )
            );
            exit();
        }
        // update data
        $ubah =  $this->ekspedisi_model->updateEkspedisi($this->data, $id_ekspedisi);
        if ($ubah) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Updated Ekspedisi "' . $this->data['nm_ekspedisi'] . '" successfully.'
            ));
        }
    }

    // function untuk delete data
    public function delete(Request $request, $id_eksepedisi)
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        // catch korelasi dengan tabel lain(foreign key)
        try {
            $hapus = $this->ekspedisi_model->deleteEkspedisi($id_eksepedisi);
        } catch (\Exception $e) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Tidak dapat menghapus Ekspedisi "' . $request->nm_ekspedisi . '". Telah ada Histori Serah Terima Paket.'
                )
            );
            exit();
        }

        // delete data
        if ($hapus) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Deleted Ekspedisi "' . $request->nm_ekspedisi . '" successfully.'
            ));
        }
    }
}
