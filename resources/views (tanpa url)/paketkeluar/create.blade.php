<form id="insert_form" action="#" method="POST">
    @csrf
    <div class="modal-body">
        <div id="warning-add">
        </div>

        <div class="form-group">
            <label>NIPP/Nama</label>
            <code>*</code>
            <select class="select2 form-control custom-select nipp" name="nipp" required>
            </select>
        </div>
        <div class="form-group">
            <label>Divisi</label>
            <code>*</code>
            <select name="id_divisi" class="select2 form-control custom-select id_divisi" required>
            </select>
        </div>
        <div class="form-group">
            <label for="nama_paket">Paket</label>
            <code>*</code>
            <input type="text" name="nama_paket" class="form-control nama_paket" placeholder="Masukkan Nama Paket"
                required>
        </div>
        <div class="form-group">
            <label for="tujuan_paket">Tujuan Paket</label>
            <code>*</code>
            <input type="text" name="tujuan_paket" class="form-control tujuan_paket" placeholder="Masukkan Tujuan Paket"
                required>
        </div>
        <div class="form-group">
            <label for="tgl_terima">Tanggal Terima</label>
            <code>*</code>
            <div class="input-group">
                <input type="text" name="tgl_terima" class="form-control tgl_terima singledateup"
                    placeholder="Format (DD/MM/YYYY)" autocomplete="off" required>
                <div class="input-group-append">
                    <span class="input-group-text"><i class="ti-calendar"></i></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <code>*wajib diisi</code>
        </div>
    </div>

    <div class="modal-footer justify-content-between">
        <button type="button" class="btn waves-effect waves-light btn-secondary" data-dismiss="modal">Back</button>
        <button type="submit" class="btn waves-effect waves-light btn-primary" id="btn-simpan">Simpan</button>
    </div>
</form>