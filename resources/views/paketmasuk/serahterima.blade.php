<form id="serahterima_form" action="#" method="POST">
    @method('put')
    @csrf
    <div class="modal-body">
        <div id="warning-serahterima">
        </div>

        <div class="form-group">
            <label for="penerima_fisik">Penerima Fisik</label>
            <code>*</code>
            <input type="text" name="penerima_fisik" id="penerima_fisik" class="form-control"
                placeholder="Masukkan Penerima Fisik" required>
        </div>
        <div class="form-group">
            <label for="tgl_serah">Tanggal Serah</label>
            <code>*</code>
            <div class="input-group">
                <input type="text" name="tgl_serah" class="form-control tgl_serah singledateup"
                    placeholder="Format (DD/MM/YYYY)" autocomplete="off" required>
                <div class="input-group-append">
                    <span class="input-group-text"><i class="ti-calendar"></i></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <code>*wajib diisi</code>
        </div>
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">General Info</h3>
                <p id="file_foto_text"></p>
                <img class="card-img-top img-responsive" id="file_foto" alt="Foto Terima Paket Masuk">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Terminal</td>
                                <td id="nama_terminal"></td>
                            </tr>
                            <tr>
                                <td>Divisi</td>
                                <td id="nama_divisi"></td>
                            </tr>
                            <tr>
                                <td>NIPP</td>
                                <td id="nipp"></td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td id="nama"></td>
                            </tr>
                            <tr>
                                <td>Paket</td>
                                <td id="nama_paket"></td>
                            </tr>
                            <tr>
                                <td>Ekspedisi</td>
                                <td id="nm_ekspedisi"></td>
                            </tr>
                            <tr>
                                <td>Tanggal Terima</td>
                                <td id="tgl_terima"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer justify-content-between">
        <input type="hidden" name="id_paketmasuk" id="id_paketmasuk">
        <input type="hidden" name="nama_paket" id="nama_paket">
        <button type="button" class="btn waves-effect waves-light btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn waves-effect waves-light btn-primary" id="btn-update">Update</button>
    </div>
</form>