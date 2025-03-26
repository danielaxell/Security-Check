<?php

namespace App\Http\Controllers;

use App\Mail\Email;
use App\Models\User;
use App\Jobs\EmailJob;
use App\Models\Divisi;
use App\Models\Terminal;
use App\Models\Ekspedisi;
use App\Models\PaketMasuk;
use Illuminate\Http\Request;
use App\Models\PaketMasukHist;
use Illuminate\Support\Facades\Mail;

class PaketMasukController extends Controller
{
    private $paketmasuk_model;
    private $paketmasukhist_model;
    private $user_model;
    private $divisi_model;
    private $ekspedisi_model;
    private $paketmasuks;
    private $users;
    private $divisies;
    private $ekspedisies;
    private $data;

    public function __construct()
    {
        $this->paketmasuk_model = new PaketMasuk();
        $this->paketmasukhist_model = new PaketMasukHist();
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

        $this->data['title'] = 'Paket Masuk';
        echo view('paketmasuk/index', $this->data);
    }

    // get data paket masuk json
    public function getPaketMasuk(Request $request)
    {
        if ($request->id_paketmasuk) {
            $this->paketmasuks = $this->paketmasuk_model->getPaketMasuk($request->id_paketmasuk);
        } else {
            $this->paketmasuks = $this->paketmasuk_model->getPaketMasuk();
        }
        return json_encode($this->paketmasuks);
    }

    //function untuk simpan data
    public function store(Request $request)
    {
        $this->data = array(
            'id_divisi'     => $request->id_divisi,
            'nipp'          => $request->nipp,
            'nama_paket'    => $request->nama_paket,
            'id_ekspedisi'  => $request->id_ekspedisi,
            'tgl_terima'    => $request->tgl_terima
        );

        // get data divisi dari inputan dropdown list
        $this->divisies = $this->divisi_model->getDivisi($this->data['id_divisi']);
        // ubah format tgl terima
        $tgl_terima = date("Y-m-d", strtotime(str_replace('/', '-', $this->data['tgl_terima'])));
        // proses rubah raw foto dan simpan file foto
        $file_foto = $request->file_foto;
        if ($file_foto) {
            $file_foto = str_replace('data:image/jpeg;base64,', '', $file_foto);
            $file_foto = base64_decode($file_foto);
            
            // Format baru: Foto_Masuk_1527_2024-03-19_123456.png
            $filename = sprintf(
                "Foto_Masuk_%s_%s.png",
                now()->format('Hi_Y-m-d'),
                $request->session()->get('nipp')  // mengambil NIPP dari session
            );
            
            $direktori = 'uploads/foto_paket/';
            file_put_contents($direktori . $filename, $file_foto);
            $path_foto = $direktori . $filename;
        } else {
            $path_foto = '';
        }

        $this->data = array(
            'kd_cabang'     => $request->session()->get('kd_cabang'),
            'kd_terminal'   => $request->session()->get('kd_terminal'),
            'kd_divisi'     => $this->divisies->kd_divisi,
            'nipp'          => $this->data['nipp'],
            'nama_paket'    => $this->data['nama_paket'],
            'id_ekspedisi'  => $this->data['id_ekspedisi'],
            'tgl_terima'    => $tgl_terima,
            'file_foto'     => $path_foto,
            'tgl_created'   => date("Y-m-d H:i:s"),
            'tgl_updated'   => date("Y-m-d H:i:s"),
            'user_created'  => $request->session()->get('nipp'),
            'user_updated'  => $request->session()->get('nipp')
        );
        // insert data
        $simpan = $this->paketmasuk_model->insertPaketMasuk($this->data);
        if ($simpan) {
            $this->ekspedisies = $this->ekspedisi_model->getEkspedisi($this->data['id_ekspedisi']);
            $this->users = $this->user_model->getUser($this->data['nipp']);
            if ($this->users == true) { // kirim email
                $detail_email = array(
                    'jns_email'     => 'paket_masuk',
                    'email'         => $this->users->email,
                    'subject'       => 'Paket Masuk -- Paket "' . $this->data['nama_paket'] . '" Telah Diterima',
                    'nama_penerima' => $this->users->nama,
                    'paket_masuk'   => $this->data['nama_paket'],
                    'tgl_terima'    => $request->tgl_terima,
                    'nm_ekspedisi'  => $this->ekspedisies->nm_ekspedisi,
                    'jam_terima'    => now()->format('H:i:s'),
                    'foto_paket'    => $path_foto  // path foto relatif dari public
                );
                
                Mail::to($this->users->email)->send(new Email($detail_email));
                // dispatch(new EmailJob($detail_email));
                echo json_encode(array(
                    "status"        => TRUE,
                    "pesan_success" => 'Created dan Kirim Email Paket Masuk "' . $this->data['nama_paket'] . '". Terminal "' . $request->session()->get('nama_terminal') . '" successfully.'
                ));
            } else {
                echo json_encode(array(
                    "status"        => TRUE,
                    "pesan_success" => 'Created Paket Masuk "' . $this->data['nama_paket'] . '". Terminal "' . $request->session()->get('nama_terminal') . '" successfully.'
                ));
            }
        }
    }

    // function untuk mengupdate data
    public function serahterima(Request $request, $id_paketmasuk)
    {
        $tgl_serah = date("Y-m-d", strtotime(str_replace('/', '-', $request->tgl_serah)));
        $this->data = array(
            'penerima_fisik'    => $request->penerima_fisik,
            'tgl_serah'         => $tgl_serah,
            'tgl_updated'       => date("Y-m-d H:i:s"),
            'user_updated'      => $request->session()->get('nipp')
        );
        // insert data ke hist
        $insert = $this->paketmasukhist_model->insertPaketMasuk($id_paketmasuk);
        // update data di hist
        if ($insert) {
            $ubah =  $this->paketmasukhist_model->updatePaketMasuk($this->data, $id_paketmasuk);
        }
        if ($ubah) {
            $hapus = $this->paketmasuk_model->deletePaketMasuk($id_paketmasuk);
            if ($hapus) {
                echo json_encode(array(
                    "status"        => TRUE,
                    "pesan_success" => 'Serah Terima Paket Masuk "' . $request->nama_paket . '". Terminal "' . $request->session()->get('nama_terminal') . '" successfully.'
                ));
            }
        }
    }

    // function untuk delete data
    public function delete(Request $request, $id_paketmasuk)
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        // catch korelasi dengan tabel lain(foreign key)
        try {
            $hapus = $this->paketmasuk_model->deletePaketMasuk($id_paketmasuk);
            // delete file foto dari server
            if ($request->file_foto) {
                unlink($request->file_foto);
            }
        } catch (\Exception $e) {
            echo json_encode(
                array(
                    "status" => FALSE,
                    "pesan_warning" => 'Tidak dapat menghapus Paket Masuk "' . $request->nama_paket . '" Terminal "' . $request->session()->get('nama_terminal') . '". (' . $e->getMessage() . ')'
                )
            );
            exit();
        }

        // delete data
        if ($hapus) {
            echo json_encode(array(
                "status"        => TRUE,
                "pesan_success" => 'Deleted Paket Masuk "' . $request->nama_paket . '". Terminal "' . $request->session()->get('nama_terminal') . '" successfully.'
            ));
        }
    }
}
