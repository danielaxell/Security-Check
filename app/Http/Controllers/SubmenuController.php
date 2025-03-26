<?php

namespace App\Http\Controllers;

use App\Models\Submenu;
use Illuminate\Http\Request;

class SubmenuController extends Controller
{
    private $submenu_model;
    private $submenus;
    private $othersubmenus;
    private $otherdata;
    private $data;

    public function __construct()
    {
        $this->submenu_model = new Submenu();
    }

    public function index()
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        $this->data['title'] = 'Setting Sub Menu';
        echo view('submenu/index', $this->data);
    }

    // get data Sub Menu di datatable
    public function getSubmenu(Request $request)
    {
        if ($request->id_submenu) {
            $this->submenus = $this->submenu_model->getSubmenu($request->id_submenu);
        } else {
            //get data semua list Sub Menu
            $this->submenus = $this->submenu_model->getSubmenu();
        }
        echo json_encode($this->submenus);
    }

    public function getMaxUrutanSubmenu()
    {
        // get urutan rekomendasi (urutan terakhir + 1)       
        $maxurutan =  $this->submenu_model->getUrutanSubmenu();
        echo json_encode($maxurutan);
    }

    //function untuk simpan data
    public function store(Request $request)
    {
        $this->data = array(
            'urutan'        => $request->urutan,
            'nama_submenu'  => $request->nama_submenu,
            'icon'          => $request->icon,
            'rec_stat'      => $request->rec_stat
        );

        //cek nama Sub Menu jika sama
        $this->submenus = $this->submenu_model->getNamaSamaSubmenu($this->data['nama_submenu']);
        if ($this->submenus == TRUE) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Nama Sub Menu tersebut Sudah Ada.'
                )
            );
            exit();
        }
        //cek urutan Sub Menu yang sudah ada
        $this->submenus = $this->submenu_model->getEksistUrutanSubmenu($this->data['urutan']);
        if ($this->submenus == TRUE) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Urutan Sub Menu Sudah Ada. Masukkan sesuai rekomendasi.'
                )
            );
            exit();
        }

        $this->submenus =  $this->submenu_model->getIdSubmenu();
        $this->data = array(
            'id_submenu'    =>  $this->submenus->max_id_submenu,
            'urutan'        =>  $this->data['urutan'],
            'nama_submenu'  =>  $this->data['nama_submenu'],
            'url'           =>  $request->url,
            'icon'          =>  $this->data['icon'],
            'rec_stat'      =>  $this->data['rec_stat'],
            'tgl_created'   => date("Y-m-d H:i:s"),
            'tgl_updated'   => date("Y-m-d H:i:s"),
            'user_created'  => $request->session()->get('nipp'),
            'user_updated'  => $request->session()->get('nipp')
        );
        // insert data
        $simpan = $this->submenu_model->insertSubmenu($this->data);
        if ($simpan) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Created Sub Menu "' . $this->data['id_submenu'] . '" "' . $this->data['nama_submenu'] . '" successfully.'
            ));
        }
    }


    // function untuk mengupdate data
    public function update(Request $request, $id_submenu)
    {
        $this->data = array(
            'urutan'        => $request->urutan,
            'nama_submenu'  => $request->nama_submenu,
            'icon'          => $request->icon,
            'rec_stat'      => $request->rec_stat
        );
        //cek nama group inventaris jika sama
        $this->submenus = $this->submenu_model->getNamaSamaSubmenu($this->data['nama_submenu'], $id_submenu);
        if ($this->submenus == TRUE) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Nama Sub Menu tersebut Sudah Ada.'
                )
            );
            exit();
        }
        // validasi id_role jika ada inputan id_submenu asal-asalan
        $this->submenus = $this->submenu_model->getSubmenu($id_submenu);
        if ($this->submenus == FALSE) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'ID Sub Menu tidak diketemukan.'
                )
            );
            exit();
        }
        // jika urutan sudah ada
        $this->submenus = $this->submenu_model->getEksistUrutanSubmenu($this->data['urutan']);
        if ($this->submenus == TRUE) {
            // get data urutan eksist
            $this->othersubmenus = $this->submenu_model->getSubmenu($id_submenu);
            $this->otherdata = array(
                'urutan' => $this->othersubmenus->urutan
            );
            $this->submenu_model->updateSubmenu($this->otherdata, $this->submenus->id_submenu);
        }
        $this->data = array(
            'id_submenu'    => $id_submenu,
            'urutan'        => $this->data['urutan'],
            'nama_submenu'  => $this->data['nama_submenu'],
            'url'           => $request->url,
            'icon'          => $this->data['icon'],
            'rec_stat'      => $this->data['rec_stat'],
            'tgl_updated'   => date("Y-m-d H:i:s"),
            'user_updated'  => $request->session()->get('nipp')
        );

        // update data
        $ubah =  $this->submenu_model->updateSubmenu($this->data, $this->data['id_submenu']);
        if ($ubah) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Updated Sub Menu "' . $this->data['id_submenu'] . '" "' . $this->data['nama_submenu'] . '" successfully.'
            ));
        }
    }

    // function untuk delete data
    public function delete(Request $request, $id_submenu)
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();
        // catch korelasi dengan tabel lain(foreign key)
        try {
            $hapus = $this->submenu_model->deleteSubmenu($id_submenu);
        } catch (\Exception $e) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Tidak dapat menghapus Sub Menu . Pesan Error : "' . $e->getMessage() . '"'
                )
            );
            exit();
        }
        // delete data
        if ($hapus) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Deleted Sub Menu "' . $id_submenu . '" "' . $request->nama_submenu . '" successfully.'
            ));
        }
    }
}
