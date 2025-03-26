<?php

namespace App\Http\Controllers;

use App\Models\Akses;
use Illuminate\Http\Request;

class AksesController extends Controller
{
    private $akses_model;
    private $data;
    private $akses;

    public function __construct()
    {
        $this->akses_model = new Akses();
    }


    // get data akses user di datatable
    public function getAkses(Request $request)
    {
        if ($request->id_role) {
            $this->akses = $this->akses_model->getAkses($request->id_role);
        } else {
            //get data semua list hak akses user
            $this->akses = $this->akses_model->getAkses();
        }
        echo json_encode($this->akses);
    }


    // function untuk mengupdate data
    public function update(Request $request)
    {
        $id_akses  =  $request->id_akses;
        $C         =  $request->C;
        $R         =  $request->R;
        $U         =  $request->U;
        $D         =  $request->D;

        for ($i = 0; $i < count($id_akses); $i++) {
            $this->data = array(
                'id_akses'  => $id_akses[$i],
                'C'         => $C[$i] ?? 'T',
                'R'         => $R[$i] ?? 'T',
                'U'         => $U[$i] ?? 'T',
                'D'         => $D[$i] ?? 'T',
            );
            try {
                // update data
                $this->akses_model->updateAkses($this->data, $id_akses[$i]);
            } catch (\Exception $e) {
                echo json_encode(
                    array(
                        "status" => FALSE,
                        "pesan_warning" => 'Tidak dapat menyimpan permission hak akses . Pesan Error : "' . $e->getMessage() . '"'
                    )
                );
                exit();
            }
        }

        echo json_encode(array(
            "status"        => TRUE,
            "pesan_success" => 'Updated Permission Hak Akses "' . $request->nama_role . '" Successfully.'
        ));
    }
}
