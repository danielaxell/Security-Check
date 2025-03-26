<?php

namespace App\Http\Controllers;

use App\Models\MapMenuRole;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Http\Request;

class MapMenuRoleController extends Controller
{
    private $menurole_model;
    private $menu_model;
    private $role_model;
    private $roles;
    private $menus;
    private $menuroles;
    private $data;

    public function __construct()
    {
        $this->menurole_model = new MapMenuRole();
        $this->menu_model = new Menu();
        $this->role_model = new Role();
    }

    public function index()
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        //get data dropdwon list menu aktif
        $this->data['menus'] = $this->menu_model->getmenuAktif();

        //get data dropdwon list role aktif
        $this->data['roles'] = $this->role_model->getRoleAktif();

        $this->data['title'] = 'Setting Mapping Menu - Role';
        echo view('mapmenurole.index', $this->data);
    }

    // $this->data['menus']  = ['' => ''] + array_column($this->menus, 'nama_menu', 'id_menu'); //array asosiative array(id => nama_terminal)
    // $this->data['roles']  = ['' => ''] + array_column($this->roles, 'idnamarole', 'id_role'); //array asosiative array(id => nama_terminal)

    // get data userrole di datatable
    public function getMenuRole(Request $request)
    {
        if ($request->id_menu_role) {
            $this->menuroles = $this->menurole_model->getMenuRole($request->id_menu_role);
        } else {
            //get data semua list userrole
            $this->menuroles = $this->menurole_model->getMenuRole();
        }
        echo json_encode($this->menuroles);
    }

    //function untuk simpan data
    public function store(Request $request)
    {
        $this->data = array(
            'id_menu'   => $request->id_menu,
            'id_role'   => $request->id_role
        );

        //  cek menu role yg sudah terdaftar
        $this->menuroles = $this->menurole_model->cekSamaMenuRole($this->data['id_menu'], $this->data['id_role']);
        if ($this->menuroles > 0) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Menu dengan Role tersebut sudah terdaftar.'
                )
            );
            exit();
        }
        // get role
        $this->roles = $this->role_model->getRole($this->data['id_role']);
        // get menu
        $this->menus = $this->menu_model->getMenu($this->data['id_menu']);
        $this->data = array(
            'id_menu'       => $this->data['id_menu'],
            'id_role'       => $this->data['id_role'],
            'tgl_created'   => date("Y-m-d H:i:s"),
            'tgl_updated'   => date("Y-m-d H:i:s"),
            'user_created'  => $request->session()->get('nipp'),
            'user_updated'  => $request->session()->get('nipp')
        );
        // insert data
        $simpan = $this->menurole_model->insertMenuRole($this->data);
        if ($simpan) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Created Menu - Role "' . $this->menus->nama_menu . '" "' . $this->roles->nama_role . '" successfully.'
            ));
        }
    }

    // function untuk mengupdate data
    public function update(Request $request, $id_menu_role)
    {
        // cek menu role yg sudah terdaftar
        $this->menuroles = $this->menurole_model->cekSamaMenuRole($request->id_menu, $request->id_role);
        if ($this->menuroles > 0) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Menu untuk Role tersebut sudah terdaftar.'
                )
            );
            exit();
        }
        // get menu
        $this->menus = $this->menu_model->getMenu($request->id_menu);
        $this->data = array(
            'id_menu'       => $request->id_menu,
            'tgl_updated'   => date("Y-m-d H:i:s"),
            'user_updated'  => $request->session()->get('nipp')
        );
        // update data
        $ubah =  $this->menurole_model->updateMenuRole($this->data, $id_menu_role);
        if ($ubah) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Updated Menu - Role "' . $this->menus->nama_menu . '" "' . $request->nama_role . '" successfully.'
            ));
        }
    }

    // function untuk delete data
    public function delete(Request $request, $id_menu_role)
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        // delete data
        $hapus = $this->menurole_model->deleteMenuRole($id_menu_role);
        if ($hapus) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Deleted Menu - Role "' . $request->nama_menu . '" "' . $request->nama_role . '" successfully.'
            ));
        }
    }
}
