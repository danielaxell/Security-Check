<?php

namespace App\Http\Controllers;

use App\Models\MapSubmenuMenu;
use App\Models\Menu;
use App\Models\Submenu;
use Illuminate\Http\Request;

class MapSubmenuMenuController extends Controller
{
    private $submenumenu_model;
    private $menu_model;
    private $submenu_model;
    private $submenus;
    private $menus;
    private $submenumenu;
    private $data;

    public function __construct()
    {
        $this->submenumenu_model = new MapSubmenuMenu();
        $this->menu_model = new Menu();
        $this->submenu_model = new Submenu();
    }

    public function index()
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();
        //get data dropdwon list menu aktif
        $this->data['menus'] = $this->menu_model->getmenuAktif();
        //get data dropdwon list submenu aktif
        $this->data['submenus'] = $this->submenu_model->getSubmenuAktif();

        $this->data['title'] = 'Setting Mapping Sub Menu - Menu';
        echo view('mapsubmenumenu.index', $this->data);
    }

    // get data submenumenu di datatable
    public function getSubmenuMenu(Request $request)
    {
        if ($request->id_submenu_menu) {
            $this->submenumenu = $this->submenumenu_model->getSubmenuMenu($request->id_submenu_menu);
        } else {
            //get data semua list submenumenu
            $this->submenumenu = $this->submenumenu_model->getSubmenuMenu();
        }
        echo json_encode($this->submenumenu);
    }

    //function untuk simpan data
    public function store(Request $request)
    {
        $this->data = array(
            'id_menu'       => $request->id_menu,
            'id_submenu'    => $request->id_submenu
        );

        //  cek menu submenu yg sudah terdaftar
        $this->submenumenu = $this->submenumenu_model->cekSamaSubmenuMenu($this->data['id_menu'], $this->data['id_submenu']);
        if ($this->submenumenu > 0) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Sub Menu dengan Menu tersebut sudah terdaftar.'
                )
            );
            exit();
        }
        // get submenu
        $this->submenus = $this->submenu_model->getSubmenu($this->data['id_submenu']);
        // get menu
        $this->menus = $this->menu_model->getMenu($this->data['id_menu']);
        $this->data = array(
            'id_menu'       => $this->data['id_menu'],
            'id_submenu'    => $this->data['id_submenu'],
            'tgl_created'   => date("Y-m-d H:i:s"),
            'tgl_updated'   => date("Y-m-d H:i:s"),
            'user_created'  => $request->session()->get('nipp'),
            'user_updated'  => $request->session()->get('nipp')
        );
        // insert data
        $simpan = $this->submenumenu_model->insertSubmenuMenu($this->data);
        if ($simpan) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Created Sub Menu - Menu, Menu : "' . $this->menus->nama_menu . '", Submenu : "' . $this->submenus->nama_submenu . '" successfully.'
            ));
        }
    }

    // function untuk mengupdate data
    public function update(Request $request, $id_submenu_menu)
    {
        // cek submenu menu yg sudah terdaftar
        $this->submenumenu = $this->submenumenu_model->cekSamaSubmenuMenu($request->id_menu, $request->id_submenu);
        if ($this->submenumenu > 0) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Sub Menu untuk Menu tersebut sudah terdaftar.'
                )
            );
            exit();
        }
        // get menu
        $this->submenus = $this->submenu_model->getSubmenu($request->id_submenu);
        $this->data = array(
            'id_submenu'    => $request->id_submenu,
            'tgl_updated'   => date("Y-m-d H:i:s"),
            'user_updated'  => $request->session()->get('nipp')
        );
        // update data
        $ubah =  $this->submenumenu_model->updateSubmenuMenu($this->data, $id_submenu_menu);
        if ($ubah) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Updated Sub Menu - Menu, Menu : "' . $request->nama_menu . '", Submenu : "' . $this->submenus->nama_submenu . '" successfully.'
            ));
        }
    }

    // function untuk delete data
    public function delete(Request $request, $id_submenu_menu)
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        // delete data
        $hapus = $this->submenumenu_model->deleteSubmenuMenu($id_submenu_menu);
        if ($hapus) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Deleted Sub Menu - Menu, Menu : "' . $request->nama_menu . '", Submenu : "' . $request->nama_submenu . '" successfully.'
            ));
        }
    }
}
