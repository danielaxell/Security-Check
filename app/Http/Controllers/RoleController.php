<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private $role_model;
    private $data;
    private $roles;

    public function __construct()
    {
        $this->role_model = new Role();
    }

    public function index()
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        //get list semua role
        $this->data['roles'] = $this->role_model->getRole();

        $this->data['title'] = 'Setting Role';
        echo view('role/index', $this->data);
    }

    // get data user di datatable
    public function getRole(Request $request)
    {
        if ($request->id_role) {
            $this->roles = $this->role_model->getRole($request->id_role);
        } else {
            //get data semua list user
            $this->roles = $this->role_model->getRole();
        }
        echo json_encode($this->roles);
    }

    //function untuk simpan data
    public function store(Request $request)
    {
        $this->data = array(
            'nama_role'     => $request->nama_role,
            'rec_stat'      => $request->rec_stat
        );

        //cek nama role jika sama
        $this->roles = $this->role_model->getNamaSamaRole($this->data['nama_role']);
        if ($this->roles == TRUE) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Nama Role tersebut Sudah Ada.'
                )
            );
            exit();
        }
        $this->roles = $this->role_model->getIdRole();
        $this->data = array(
            'id_role'       => $this->roles->max_id_role,
            'nama_role'     => $this->data['nama_role'],
            'rec_stat'      => $this->data['rec_stat'],
            'tgl_created'   => date("Y-m-d H:i:s"),
            'tgl_updated'   => date("Y-m-d H:i:s"),
            'user_created'  => $request->session()->get('nipp'),
            'user_updated'  => $request->session()->get('nipp')
        );
        // insert data
        $simpan = $this->role_model->insertRole($this->data);
        if ($simpan) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Created Role "' . $this->data['id_role'] . '" "' . $this->data['nama_role'] . '" successfully.'
            ));
        }
    }


    // function untuk mengupdate data
    public function update(Request $request, $id_role)
    {
        $this->data = array(
            'nama_role'     => $request->nama_role,
            'rec_stat'      => $request->rec_stat
        );
        //cek nama role jika sama
        $this->roles = $this->role_model->getNamaSamaRole($this->data['nama_role'], $id_role);
        if ($this->roles == TRUE) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Nama Role tersebut Sudah Ada.'
                )
            );
            exit();
        }
        // validasi id_role jika ada inputan nipp asal-asalan
        $this->roles = $this->role_model->getRole($id_role);
        if (!isset($this->roles)) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'ID Role tidak diketemukan.'
                )
            );
            exit();
        }
        $this->data = array(
            'id_role'       => $id_role,
            'nama_role'     => $this->data['nama_role'],
            'rec_stat'      => $this->data['rec_stat'],
            'tgl_updated'   => date("Y-m-d H:i:s"),
            'user_updated'  => $request->session()->get('nipp')
        );
        // update data
        $ubah =  $this->role_model->updateRole($this->data, $this->data['id_role']);
        if ($ubah) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Updated Role "' . $this->data['id_role'] . '" "' . $this->data['nama_role'] . '" successfully.'
            ));
        }
    }


    // function untuk delete data
    public function delete(Request $request, $id_role)
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        // catch korelasi dengan tabel lain(foreign key)
        try {
            $hapus = $this->role_model->deleteRole($id_role);
        } catch (\Exception $e) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Tidak dapat menghapus Role "' . $id_role . '" "' . $request->nama_role . '" karena berkaitan dengan Menu - Role'
                )
            );
            exit();
        }
        // delete data
        if ($hapus) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Deleted Role "' . $id_role  . '" "' . $request->nama_role . '" successfully.'
            ));
        }
    }
}
