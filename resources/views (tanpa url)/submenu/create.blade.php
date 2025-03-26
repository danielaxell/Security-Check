<form id="insert_form" action="#" method="POST">
    @csrf
    <div class="modal-body">
        <div id="warning-add">
        </div>

        <div class="form-group">
            <label for="urutan">Urutan Sub Menu</label>
            <code>*</code>
            <input type="number" name="urutan" min=1 class="form-control" placeholder="Masukkan Urutan Sub Menu"
                required>
            <code>rekomendasi urutan : <strong><span class="max_urutan"></span></strong></code>
        </div>
        <div class="form-group">
            <label for="nama_submenu">Nama Sub Menu</label>
            <code>*</code>
            <input type="text" name="nama_submenu" class="form-control" placeholder="Masukkan Nama Sub Menu" required>
        </div>
        <div class="form-group">
            <label for="url">Nama Url</label>
            <code>*</code>
            <input type="text" name="url" class="form-control" placeholder="Masukkan Nama Url" required>
        </div>
        <div class="form-group">
            <label for="icon">Nama Icon</label>
            <code>*</code>
            <input type="text" name="icon" class="form-control" placeholder="Masukkan Nama Icon" required>
        </div>
        <div class="form-group">
            <label for="rec_stat">Status</label>
            <code>*</code>
            <select name="rec_stat" class="form-control" required>
                <option value="" selected>Pilih</option>
                <option value="A">Aktif</option>
                <option value="D">Tdk Aktif</option>
            </select>
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