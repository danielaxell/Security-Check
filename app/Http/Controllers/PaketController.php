<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function serahTerima(Request $request)
    {
        // ... existing code ...
        
        // Tambahkan logging untuk debug
        \Log::info('Data foto: ', [
            'ada_foto' => $request->hasFile('foto'),
            'nama_file' => $request->file('foto')->getClientOriginalName()
        ]);

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $nama_foto = 'paketmasuk_' . uniqid() . '_' . date('Y-m-d');
            
            // Format waktu
            $waktu = now()->format('Hi_Y-m-d'); // Contoh: 1527_2024-03-19
            
            // Tentukan jenis paket
            $jenis = $request->jenis_paket == 'masuk' ? 'Masuk' : 'Keluar';
            
            // Ambil NIPP dari user yang login
            $nipp = auth()->user()->nipp ?? '000000'; // fallback jika tidak ada NIPP
            
            // Format yang diinginkan: Foto_Masuk_1527_2024-03-19_123456.jpg
            $nama_foto = sprintf(
                "Foto_%s_%s_%s.%s",
                $jenis,
                now()->format('Hi_Y-m-d'),
                auth()->user()->nipp,
                $foto->getClientOriginalExtension()
            );
            
            // Debug log
            \Log::info('Generate nama file foto:', [
                'nama_file' => $nama_foto,
                'jenis' => $jenis,
                'waktu' => $waktu,
                'nipp' => $nipp
            ]);
            
            // Simpan foto
            $foto->move(public_path('uploads/foto_paket'), $nama_foto);
            
            // Update database
            $paket->FILE_FOTO = $nama_foto;
            $paket->save();
            
            // Log untuk debugging
            \Log::info('Foto paket disimpan:', [
                'nama_file' => $nama_foto,
                'path' => public_path('uploads/foto_paket/' . $nama_foto)
            ]);
        }
        
        // ... existing code ...
    }
}