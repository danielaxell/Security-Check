<form id="serahterima_form" action="#" method="POST">
    @method('put')
    @csrf
    <div class="modal-body">
        <div id="warning-serahterima">
        </div>

        <div class="form-group">
            <label for="tgl_pulang">Tanggal Pulang</label>
            <code>*</code>
            <div class="input-group">
                <input type="text" name="tgl_pulang" class="form-control tgl_pulang singledatetime"
                    placeholder="Format (DD/MM/YYYY HH:mm)" autocomplete="off" required>
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
                <img class="card-img-top img-responsive" id="file_foto_edit" alt="Foto Tamu">
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
                                <td>Nama Pegawai</td>
                                <td id="nama_pegawai"></td>
                            </tr>
                            <tr>
                                <td>No.KTP Tamu</td>
                                <td id="ktp"></td>
                            </tr>
                            <tr>
                                <td>Nama Tamu</td>
                                <td id="nama_tamu"></td>
                            </tr>
                            <tr>
                                <td>Instansi Tamu</td>
                                <td id="instansi"></td>
                            </tr>
                            <tr>
                                <td>Kartu Akses</td>
                                <td id="nonama_kartuakses"></td>
                            </tr>
                            <tr>
                                <td>Tanggal Datang</td>
                                <td id="tgl_datang"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer justify-content-between">
        <input type="hidden" name="id_tamuhadir" id="id_tamuhadir">
        <input type="hidden" name="nama" id="nama">
        <input type="hidden" name="ktp" id="ktp">
        <input type="hidden" name="tgl_datang" id="tgl_datang">
        <button type="button" class="btn waves-effect waves-light btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn waves-effect waves-light btn-primary" id="btn-update">Update</button>
    </div>
</form>