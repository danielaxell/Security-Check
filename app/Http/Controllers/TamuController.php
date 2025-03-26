<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use Illuminate\Http\Request;

class TamuController extends Controller
{
    private $tamu_model;
    private $data;
    private $tamus;

    public function __construct()
    {
        $this->tamu_model = new Tamu();
    }

    public function index()
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        $this->data['title'] = 'Master Tamu';
        echo view('tamu.index', $this->data);
    }

    // get data tamu json
    public function getTamu(Request $request)
    {
        if ($request->id_tamu) {
            $this->tamus = $this->tamu_model->getTamu($request->id_tamu);
        } else {
            $this->tamus = $this->tamu_model->getTamu();
        }
        return json_encode($this->tamus);
    }

    // get list user json
    public function getListTamu(Request $request)
    {
        if ($request->search) {
            $this->tamus = $this->tamu_model->getListTamu($request->search);
            // untuk ajax remote data, hasil query harus dibuat array dr objek, dan masing-masing objek harus memakai id dan text
            $hasil = array();
            foreach ($this->tamus as $row) {
                $hasil[] = array(
                    'id'    => $row->id_tamu,
                    'text'  => $row->ktpnama,
                );
            }
            return json_encode($hasil);
            // return $this->response->setJSON($hasil);
        }
    }

    //function untuk simpan data
    public function store(Request $request)
    {
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
 
            // Format baru: Foto_Masuk_1527_2024-03-19_123456.png
            $filename = sprintf(
                "Foto_Tamu_%s_%s.png",
                now()->format('Hi_Y-m-d'),
                $request->session()->get('nama')  // mengambil Nama dari session
            );
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
            //session()->setFlashdata('success', 'Created Tamu No KTP"' . $this->data['ktp'] . '", Nama "' . $this->data['nama'] . '" successfully');
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Created Tamu No KTP "' . $this->data['ktp'] . '", Nama "' . $this->data['nama'] . '" successfully.'
            ));
        }
    }

    // function untuk mengupdate data
    public function update(Request $request, $id_tamu)
    {
        // validasi jika ada ktp yg sama
        $this->tamus = $this->tamu_model->cekTamu($id_tamu, $request->ktp);
        if ($this->tamus == TRUE) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Tidak Dapat Melakukan Proses Penyimpanan. No KTP sudah terdaftar.'
                )
            );
            exit();
        }
        // proses delete foto lama dan rubah raw foto baru dan simpan file foto baru
        $file_foto = $request->file_foto_edit;
        $file_foto_lama = $this->tamu_model->getTamu($id_tamu);
        if ($file_foto) {
            // delete file foto lama dari server
            if ($file_foto_lama) {
                if (isset($file_foto_lama->file_foto)) {
                    unlink($file_foto_lama->file_foto);
                }
            }
            // rubah raw foto dan simpan file foto
            $file_foto = str_replace('data:image/jpeg;base64,', '', $file_foto);
            $file_foto = base64_decode($file_foto);
            $filename = 'fototamu_'  . uniqid() . '_' . date("Y-m-d") . '.png';

            // Format baru: Foto_Masuk_1527_2024-03-19_123456.png
            $filename = sprintf(
                "Foto_Tamu_%s_%s.png",
                now()->format('Hi_Y-m-d'),
                $request->session()->get('nama')  // mengambil Nama dari session
            );
                        file_put_contents($direktori . $filename, $file_foto);
            $path_foto = $direktori . $filename;
        } else {
            $path_foto = $file_foto_lama->file_foto;
        }
        // get data cabang dari inputan dropdown list
        $this->data = array(
            'ktp'           => $request->ktp,
            'nama'          => $request->nama,
            'alamat'        => $request->alamat,
            'instansi'      => $request->instansi,
            'file_foto'     => $path_foto,
            'tgl_updated'   => date("Y-m-d H:i:s"),
            'user_updated'  => $request->session()->get('nipp')
        );
        // update data
        $ubah =  $this->tamu_model->updateTamu($this->data, $id_tamu);
        if ($ubah) {
            // session()->setFlashdata('success', 'Updated Tamu No KTP"' . $this->data['ktp'] . '", Nama "' . $this->data['nama'] . '" successfully');
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Updated Tamu No KTP "' . $this->data['ktp'] . '", Nama "' . $this->data['nama'] . '" successfully.'
            ));
        }
    }

    // function untuk delete data
    public function delete(Request $request, $id_tamu)
    {
        $this->data = array(
            'ktp'       => $request->ktp,
            'nama'      => $request->nama,
            'file_foto' => $request->file_foto,
        );

        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        // catch korelasi dengan tabel lain(foreign key)
        try {
            $hapus = $this->tamu_model->deleteTamu($id_tamu);
            // delete file foto dari server
            if ($this->data['file_foto']) {
                unlink($this->data['file_foto']);
            }
        } catch (\Exception $e) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Tidak dapat menghapus data Tamu No KTP "' . $this->data['ktp'] . '", Nama "' . $this->data['nama'] . '". Pesan error : ' . $e->getMessage()
                )
            );
            exit();
        }

        // delete data
        if ($hapus) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Deleted Tamu No KTP "' . $this->data['ktp'] . '", Nama "' . $this->data['nama'] . '" successfully.'
            ));
        }
    }
}
