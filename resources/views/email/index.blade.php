<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
        }
        .header {
            background-color: rgb(255, 255, 255);
            text-align: center;
            padding: 15px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .logo {
            max-width: 150px;
            display: block;
            margin: 0 auto;
        }
        .content {
            padding: 20px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        .highlight {
            font-weight: bold;
            color: #0073e6;
        }
        .attachment-preview {
            margin: 20px 0;
            text-align: center;
        }
        .attachment-preview img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .foto-paket {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
        }
        .foto-paket img {
            width: 100%;
            max-width: 500px;
            height: auto;
            border-radius: 4px;
            display: block;
            margin: 10px auto;
        }
        .foto-container {
            margin: 20px 0;
            text-align: center;
        }
        .foto-container img {
            max-width: 500px;
            height: auto;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    <div class="container">
        <div class="header">
            <!-- Tambahkan logo di sini -->
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/69/Logo_Baru_Pelindo_%282021%29.png" alt="Pelindo Logo" class="logo">
            <h2 style="color: #622F90;">Pemberitahuan Paket</h2>
        </div>
        <div class="content">
            <p>Halo {{ $detail_email['nama_penerima'] }},</p>

            @if ($detail_email['jns_email'] == 'paket_masuk')
            <p>
                Kami ingin menginformasikan bahwa paket Anda dengan keterangan "<span class="highlight">{{ $detail_email['paket_masuk'] }}</span>" 
                telah <b>diterima</b> di kantor pada <b>{{ $detail_email['tgl_terima'] }}</b> pukul <b>{{ $detail_email['jam_terima'] }}</b> dari ekspedisi <b>{{ $detail_email['nm_ekspedisi'] }}</b>.
            </p>
            
            @if(isset($detail_email['foto_terima']) && file_exists(storage_path('app/public/foto_terima/' . $detail_email['foto_terima'])))
            <div class="foto-container">
                <p><strong>Foto Paket:</strong></p>
                <img src="{{ $message->embedData(
                    file_get_contents(storage_path('app/public/foto_terima/' . $detail_email['foto_terima'])),
                    'foto_paket_masuk.jpg'
                ) }}" alt="Foto Paket">
            </div>
            @endif


            @elseif ($detail_email['jns_email'] == 'paket_keluar')
            <p>
                Kami ingin mengonfirmasi bahwa paket Anda dengan keterangan "<span class="highlight">{{ $detail_email['paket_keluar'] }}</span>" 
                telah <b>diserahkan</b> kepada ekspedisi <b>{{ $detail_email['nm_ekspedisi'] }}</b> 
                pada <b>{{ $detail_email['tgl_serah'] }}</b> pukul <b>{{ $detail_email['jam_kirim'] }}</b>.
            </p>
                    
            

            @if(isset($detail_email['foto_serah']))
                <!-- Debug info -->
                @php
                    $path = storage_path('app/public/foto_serah/' . $detail_email['foto_serah']);
                    \Log::info('Foto path:', [
                        'full_path' => $path,
                        'exists' => file_exists($path),
                        'readable' => is_readable($path),
                        'size' => file_exists($path) ? filesize($path) : 0
                    ]);
                @endphp
            @endif

            <p>
                Harap pantau status pengiriman melalui ekspedisi terkait.
            </p>
            @endif
            
            <p>Terima kasih atas perhatian Anda.</p>
        </div>
        <div class="footer">
            <p><b>NoReply (Pesan Otomatis)</b></p>
            <p>Salam AKHLAK.</p>
        </div>
    </div>

    <script>
        \Log::info('Email details:', {
            details: {{ json_encode($detail_email) }},
            foto_exists: {{ isset($detail_email['foto_terima']) || isset($detail_email['foto_serah']) ? 'true' : 'false' }},
            storage_path: '{{ storage_path('app/public/foto_terima') }}'
        });
    </script>

</body>
</html>
