<form id="insert_form" action="#" method="POST">
    @csrf
    <div class="modal-body">
        <div id="warning-add">
        </div>

        <div class="form-group">
            <label for="no_kartuakses">No Kartu Akses</label>
            <code>*</code>
            <input type="text" name="no_kartuakses" class="form-control no_kartuakses"
                placeholder="Masukkan No Kartu Akses" required>
        </div>
        <div class="form-group">
            <label for="nama_kartuakses">Nama Kartu Akses</label>
            <code>*</code>
            <input type="text" name="nama_kartuakses" class="form-control nama_kartuakses"
                placeholder="Masukkan Nama Kartu Akses" required>
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