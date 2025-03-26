<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Email extends Mailable
{
    use Queueable, SerializesModels;
    public $detail_email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($detail_email)
    {
        $this->detail_email = $detail_email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('email.index')
                     ->subject($this->detail_email['subject']);

        // Tambahkan foto sebagai attachment jika ada
        if (!empty($this->detail_email['foto_paket'])) {
            $nama_file = "Foto_Paket_Masuk_" . date('Y-m-d_His') . ".png";
            $email->attach(public_path($this->detail_email['foto_paket']), [
                'as' => $nama_file,
                'mime' => 'image/png'
            ]);
        }

        // Tambahkan foto serah terima paket keluar jika ada
        if (!empty($this->detail_email['foto_serah'])) {
            $path_foto = public_path('uploads/foto_paket/' . $this->detail_email['foto_serah']);
            if (file_exists($path_foto)) {
                $nama_file = "Foto_Serah_Terima_" . date('Y-m-d_His') . ".png";
                $email->attach($path_foto, [
                    'as' => $nama_file,
                    'mime' => 'image/png'
                ]);
            }
        }

        return $email;
    }
}
