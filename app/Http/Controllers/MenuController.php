<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    private $menu_model;
    private $menus;
    private $othermenus;
    private $otherdata;
    private $data;

    public function __construct()
    {
        $this->menu_model = new Menu();
    }

    public function index()
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        $this->data['title'] = 'Setting Menu';
        echo view('menu.index', $this->data);
    }

    // get data menu di datatable
    public function getMenu(Request $request)
    {
        if ($request->id_menu) {
            $this->menus = $this->menu_model->getMenu($request->id_menu);
        } else {
            //get data semua list menu
            $this->menus = $this->menu_model->getMenu();
        }
        echo json_encode($this->menus);
    }

    public function getMaxUrutanMenu()
    {
        // get urutan rekomendari (urutan terakhir + 1)       
        $maxurutan =  $this->menu_model->getUrutanMenu();
        echo json_encode($maxurutan);
    }

    //function untuk simpan data
    public function store(Request $request)
    {
        $this->data = array(
            'urutan'        => $request->urutan,
            'nama_menu'     => $request->nama_menu,
            'icon'          => $request->icon,
            'rec_stat'      => $request->rec_stat
        );

        //cek nama menu jika sama
        $this->menus = $this->menu_model->getNamaSamaMenu($this->data['nama_menu']);
        if ($this->menus == TRUE) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Nama Menu tersebut Sudah Ada.'
                )
            );
            exit();
        }
        //cek urutan menu yang sudah ada
        $this->menus = $this->menu_model->getEksistUrutanMenu($this->data['urutan']);
        if ($this->menus == TRUE) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Urutan Menu Sudah Ada. Masukkan sesuai rekomendasi.'
                )
            );
            exit();
        }

        $this->menus =  $this->menu_model->getIdmenu();
        $this->data = array(
            'id_menu'       =>  $this->menus->max_id_menu,
            'urutan'        =>  $this->data['urutan'],
            'nama_menu'     =>  $this->data['nama_menu'],
            'url'           =>  $request->url,
            'icon'          =>  $this->data['icon'],
            'rec_stat'      =>  $this->data['rec_stat'],
            'tgl_created'   => date("Y-m-d H:i:s"),
            'tgl_updated'   => date("Y-m-d H:i:s"),
            'user_created'  => $request->session()->get('nipp'),
            'user_updated'  => $request->session()->get('nipp')
        );
        // insert data
        $simpan = $this->menu_model->insertMenu($this->data);
        if ($simpan) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Created Menu "' . $this->data['id_menu'] . '" "' . $this->data['nama_menu'] . '" successfully.'
            ));
        }
    }


    // function untuk mengupdate data
    public function update(Request $request, $id_menu)
    {
        $this->data = array(
            'urutan'        => $request->urutan,
            'nama_menu'     => $request->nama_menu,
            'icon'          => $request->icon,
            'rec_stat'      => $request->rec_stat
        );
        //cek nama group inventaris jika sama
        $this->menus = $this->menu_model->getNamaSamaMenu($this->data['nama_menu'], $id_menu);
        if ($this->menus == TRUE) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Nama Menu tersebut Sudah Ada.'
                )
            );
            exit();
        }
        // validasi id_role jika ada inputan id_menu asal-asalan
        $this->menus = $this->menu_model->getMenu($id_menu);
        if ($this->menus == FALSE) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'ID Menu tidak diketemukan.'
                )
            );
            exit();
        }
        // jika urutan sudah ada
        $this->menus = $this->menu_model->getEksistUrutanMenu($this->data['urutan']);
        if ($this->menus == TRUE) {
            // get data urutan eksist
            $this->othermenus = $this->menu_model->getMenu($id_menu);
            $this->otherdata = array(
                'urutan' => $this->othermenus->urutan
            );
            $this->menu_model->updateMenu($this->otherdata, $this->menus->id_menu);
        }
        $this->data = array(
            'id_menu'       => $id_menu,
            'urutan'        => $this->data['urutan'],
            'nama_menu'     => $this->data['nama_menu'],
            'url'           => $request->url,
            'icon'          => $this->data['icon'],
            'rec_stat'      => $this->data['rec_stat'],
            'tgl_updated'   => date("Y-m-d H:i:s"),
            'user_updated'  => $request->session()->get('nipp')
        );

        // update data
        $ubah =  $this->menu_model->updateMenu($this->data, $this->data['id_menu']);
        if ($ubah) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Updated Menu "' . $this->data['id_menu'] . '" "' . $this->data['nama_menu'] . '" successfully.'
            ));
        }
    }

    // function untuk delete data
    public function delete(Request $request, $id_menu)
    {

        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();
        // catch korelasi dengan tabel lain(foreign key)
        try {
            $hapus = $this->menu_model->deletemenu($id_menu);
        } catch (\Exception $e) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Tidak dapat menghapus Menu "' . $id_menu . '" "' . $request->nama_menu . '" karena berkaitan dengan Menu - Role dan Submenu.'
                )
            );
            exit();
        }
        // delete data
        if ($hapus) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Deleted menu "' . $id_menu . '" "' . $request->nama_menu . '" successfully.'
            ));
        }
    }
}
