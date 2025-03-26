<!DOCTYPE html>
<html>

<body>
    Dear {{ $details['nama_penerima'] }}
    <br><br>
    @if ($details['jns_email'] == 'paket_masuk')
    Paket "{{ $details['paket_masuk'] }}" telah sampai dan diterima di Kantor pada tanggal "{{
    $details['tgl_terima'] }}" dari ekspedisi "{{ $details['nm_ekspedisi'] }}".
    <br>Silahkan untuk dapat diambil di Lobby Kantor.
    @elseif ($details['jns_email'] == 'paket_keluar')
    Paket "{{ $details['paket_keluar'] }}" telah diserahkan pada tanggal "{{ $details['tgl_serah'] }}" kepada ekspedisi
    "{{ $details['nm_ekspedisi'] }}".
    @endif
    <br><br><br>
    Salam Sehat. Cegah Covid-19 dengan menerapkan 5M.
    <br>
    <b>NoReply (Pesan Otomatis)</b>
</body>

</html>