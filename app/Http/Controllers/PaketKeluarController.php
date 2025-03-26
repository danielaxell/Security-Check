<?php

namespace App\Http\Controllers;

use App\Mail\Email;
use App\Models\User;
use App\Jobs\EmailJob;
use App\Models\Divisi;
use App\Models\Terminal;
use App\Models\Ekspedisi;
use App\Models\PaketKeluar;
use Illuminate\Http\Request;
use App\Models\PaketKeluarHist;
use Illuminate\Support\Facades\Mail;

class PaketKeluarController extends Controller
{
    private $paketkeluar_model;
    private $paketkeluarhist_model;
    private $user_model;
    private $divisi_model;
    private $ekspedisi_model;
    private $paketkeluars;
    private $users;
    private $divisies;
    private $ekspedisies;
    private $data;

    public function __construct()
    {
        $this->paketkeluar_model = new PaketKeluar();
        $this->paketkeluarhist_model = new PaketKeluarHist();
        $this->user_model = new User();
        $this->divisi_model = new Divisi();
        $this->terminal_model = new Terminal();
        $this->ekspedisi_model = new Ekspedisi();
    }


    public function index()
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        //get data dropdwon list ekspedisi
        $this->data['ekspedisies'] = $this->ekspedisi_model->getListEkspedisi();

        $this->data['title'] = 'Paket Keluar';
        echo view('paketkeluar/index', $this->data);
    }

    public function getPaketKeluar(Request $request)
    {
        if ($request->id_paketkeluar) {
            $this->paketkeluars = $this->paketkeluar_model->getPaketkeluar($request->id_paketkeluar);
        } else { // jika tidak, tampilkan data sesuai terminalnya
            //get data semua list user
            $this->paketkeluars = $this->paketkeluar_model->getPaketKeluar();
        }
        return json_encode($this->paketkeluars);
    }

    //function untuk simpan data
    public function store(Request $request)
    {
        $this->data = array(
            'id_divisi'     => $request->id_divisi,
            'nipp'          => $request->nipp,
            'nama_paket'    => $request->nama_paket,
            'tujuan_paket'  => $request->tujuan_paket,
            'tgl_terima'    => $request->tgl_terima
        );

        $this->divisies = $this->divisi_model->getDivisi($this->data['id_divisi']);
        $tgl_terima = date("Y-m-d", strtotime(str_replace('/', '-', $this->data['tgl_terima'])));
        $this->data = array(
            'kd_cabang'     => $request->session()->get('kd_cabang'),
            'kd_terminal'   => $request->session()->get('kd_terminal'),
            'kd_divisi'     => $this->divisies->kd_divisi,
            'nipp'          => $this->data['nipp'],
            'nama_paket'    => $this->data['nama_paket'],
            'tujuan_paket'  => $this->data['tujuan_paket'],
            'tgl_terima'    => $tgl_terima,
            'tgl_created'   => date("Y-m-d H:i:s"),
            'tgl_updated'   => date("Y-m-d H:i:s"),
            'user_created'  => $request->session()->get('nipp'),
            'user_updated'  => $request->session()->get('nipp')
        );
        // insert data
        $simpan = $this->paketkeluar_model->insertPaketKeluar($this->data);
        if ($simpan) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Created Paket Keluar "' . $this->data['nama_paket'] . '". Terminal "' . $request->session()->get('nama_terminal') . '" successfully.'
            ));
        }
    }

    // function untuk mengupdate data
    public function serahterima(Request $request, $id_paketkeluar)
    {
        $tgl_serah = date("Y-m-d", strtotime(str_replace('/', '-', $request->tgl_serah)));
        $nama_paket = $request->nama_paket;
        // proses rubah raw foto dan simpan file foto
        $file_foto = $request->file_foto;
        if ($file_foto) {
            $file_foto = str_replace('data:image/jpeg;base64,', '', $file_foto);
            $file_foto = base64_decode($file_foto);
            $filename = 'paketkeluar_'  . uniqid() . '_' . $tgl_serah . '.png';
            $direktori = 'uploads/foto_paket/';
            file_put_contents($direktori . $filename, $file_foto);
            $path_foto = $direktori . $filename;
        } else {
            $path_foto = '';
        }

        $this->data = array(
            'id_ekspedisi'  => $request->id_ekspedisi,
            'tgl_serah'     => $tgl_serah,
            'file_foto'     => $path_foto,
            'tgl_updated'   => date("Y-m-d H:i:s"),
            'user_updated'  => $request->session()->get('nipp')
        );
        // insert data ke hist
        $insert = $this->paketkeluarhist_model->insertPaketKeluar($id_paketkeluar);
        // update data di hist
        if ($insert) {
            $ubah =  $this->paketkeluarhist_model->updatePaketKeluar($this->data, $id_paketkeluar);
        }
        if ($ubah) {
            $hapus = $this->paketkeluar_model->deletePaketKeluar($id_paketkeluar);
            if ($hapus) {
                $this->ekspedisies = $this->ekspedisi_model->getEkspedisi($this->data['id_ekspedisi']);
                $this->users = $this->user_model->getUser($request->nipp);
                if ($this->users == true) { // kirim email
                    $detail_email = array(
                        'jns_email'     => 'paket_keluar',
                        'email'         => $this->users->email,
                        'subject'       => 'Paket Keluar -- Paket "' . $nama_paket . '" Telah Dikirim',
                        'nama_penerima' => $this->users->nama,
                        'paket_keluar'  => $nama_paket,
                        'tgl_serah'     => $request->tgl_serah,
                        'nm_ekspedisi'  => $this->ekspedisies->nm_ekspedisi,
                        'jam_kirim'     => now()->format('H:i:s'),
                        'foto_serah'    => $path_foto ? basename($path_foto) : null
                    );
                    Mail::to($this->users->email)->send(new Email($detail_email));
                    echo json_encode(array(
                        "status"        => TRUE,
                        "pesan_success" => 'Serah Terima dan Kirim Email Paket Keluar "' . $nama_paket . '". Terminal "' . $request->session()->get('nama_terminal') . '" successfully.'
                    ));
                } else {
                    echo json_encode(array(
                        "status"        => TRUE,
                        "pesan_success" => 'Serah Terima Paket Keluar "' . $nama_paket . '". Terminal "' . $request->session()->get('nama_terminal') . '" successfully.'
                    ));
                }
            }
        }
    }

    // function untuk delete data
    public function delete(Request $request, $id_paketkeluar)
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        // catch korelasi dengan tabel lain(foreign key)
        try {
            $hapus = $this->paketkeluar_model->deletePaketKeluar($id_paketkeluar);
        } catch (\Exception $e) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Tidak dapat menghapus Paket Keluar "' . $request->nama_paket . '" Terminal "' . $request->session()->get('nama_terminal') . '". (' . $e->getMessage() . ')'
                )
            );
            exit();
        }

        // delete data
        if ($hapus) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Deleted Paket Keluar "' . $request->nama_paket . '". Terminal "' . $request->session()->get('nama_terminal') . '" successfully.'
            ));
        }
    }
}
