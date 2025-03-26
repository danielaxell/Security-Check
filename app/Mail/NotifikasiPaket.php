<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiPaket extends Mailable
{
    use Queueable, SerializesModels;

    public $paket;
    public $jenis;

    public function __construct($paket, $jenis)
    {
        $this->paket = $paket;
        $this->jenis = $jenis;
    }

    public function build()
    {
        // Path yang benar ke folder foto
        $path = public_path('uploads/foto_paket/' . $this->paket->FILE_FOTO);
        
        // Log untuk debugging
        \Log::info('Mencoba mengirim email dengan foto', [
            'file_foto' => $this->paket->FILE_FOTO,
            'path' => $path,
            'file_exists' => file_exists($path)
        ]);

        $email = $this->view('email.index')
                      ->subject('Notifikasi Paket');

        // Cek dan lampirkan foto
        if (!empty($this->paket->FILE_FOTO) && file_exists($path)) {
            $email->attach($path, [
                'as' => 'foto_paket.jpg',
                'mime' => 'image/jpeg'
            ]);
        }

        return $email;
    }
} 