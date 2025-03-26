<?php

namespace App\Http\Controllers;

use App\Models\MapUserRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class MapUserRoleController extends Controller
{
    private $userrole_model;
    private $user_model;
    private $role_model;
    private $userroles;
    private $cabangs;
    private $roles;
    private $data;

    public function __construct()
    {
        $this->userrole_model = new MapUserRole();
        $this->user_model = new User();
        $this->role_model = new Role();
    }

    public function index()
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();
        //get data dropdwon list role aktif
        $this->data['roles'] = $this->role_model->getRoleAktif();

        $this->data['title'] = 'Setting Mapping User - Role';
        echo view('mapuserrole.index', $this->data);
    }


    // get data userrole di datatable
    public function getUserRole(Request $request)
    {
        if ($request->id_user_role) {
            $this->userroles = $this->userrole_model->getUserRole($request->id_user_role);
        } else {
            //get data semua list userrole
            $this->userroles = $this->userrole_model->getUserRole();
        }
        echo json_encode($this->userroles);
    }

    //function untuk simpan data
    public function store(Request $request)
    {
        $this->data = array(
            'nipp'          => $request->nipp,
            'id_role'       => $request->id_role
        );

        //  cek user role yg sudah terdaftar
        $this->userroles = $this->userrole_model->cekSamaUserRole($this->data['nipp'], $this->data['id_role']);
        if ($this->userroles > 0) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'User dengan Role tersebut sudah terdaftar.'
                )
            );
            exit();
        }
        // get cabang
        $this->cabangs = $this->user_model->getUser($this->data['nipp']);

        // get role
        $this->roles = $this->role_model->getRole($this->data['id_role']);
        $this->data = array(
            'kd_cabang'     => $this->cabangs->kd_cabang,
            'kd_terminal'   => $this->cabangs->kd_terminal,
            'nipp'          => $this->data['nipp'],
            'id_role'       => $this->data['id_role'],
            'tgl_created'   => date("Y-m-d H:i:s"),
            'tgl_updated'   => date("Y-m-d H:i:s"),
            'user_created'  => $request->session()->get('nipp'),
            'user_updated'  => $request->session()->get('nipp')
        );
        // simpan data
        $simpan = $this->userrole_model->insertUserRole($this->data);
        if ($simpan) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Created User - Role "' . $this->data['nipp'] . '" "' . $this->roles->nama_role . '" successfully.'
            ));
        }
    }

    // function untuk mengupdate data
    public function update(Request $request, $id_user_role)
    {
        // cek user role yg telah terdaftar
        $this->userroles = $this->userrole_model->cekSamaUserRole($request->nipp, $request->id_role);
        if ($this->userroles > 0) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'User dengan Role tersebut sudah terdaftar.'
                )
            );
            exit();
        }
        $this->data = array(
            'nipp'          => $request->nipp,
            'id_role'       => $request->id_role,
            'tgl_updated'   => date("Y-m-d H:i:s"),
            'user_updated'  => $request->session()->get('nipp')
        );
        //get data role
        $this->roles = $this->role_model->getRole($request->id_role);
        // update data
        $ubah =  $this->userrole_model->updateUserRole($this->data, $id_user_role);
        if ($ubah) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Updated User - Role "' . $request->nipp . '" "' . $this->roles->nama_role . '" successfully.'
            ));
        }
    }

    // function untuk delete data
    public function delete(Request $request, $id_user_role)
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        // delete data
        $hapus = $this->userrole_model->deleteUserRole($id_user_role);

        if ($hapus) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Deleted User - Role "' . $request->nipp . '" "' . $request->nama_role . '" successfully.'
            ));
        }
    }
}
