<form id="insert_form" action="#" method="POST">
    @csrf
    <div class="modal-body">
        <div id="warning-add">
        </div>

        <div class="form-group">
            <label for="kd_divisi">Kode Divisi</label>
            <code>*</code>
            <input type="text" name="kd_divisi" class="form-control kd_divisi" placeholder="Masukkan Kode Divisi"
                required>
        </div>
        <div class="form-group">
            <label for="nm_divisi">Nama Divisi</label>
            <code>*</code>
            <input type="text" name="nama_divisi" class="form-control nm_divisi" placeholder="Masukkan Nama Divisi"
                required>
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