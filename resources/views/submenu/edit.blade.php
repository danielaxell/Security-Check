<form id="edit_form" action="#" method="POST">
    @method('put')
    @csrf
    <div class="modal-body">
        <div id="warning-edit">
        </div>

        <div class="form-group">
            <label for="id_submenu">ID Sub Menu</label>
            <code>*</code>
            <input type="text" name="id_submenu" id="id_submenu" class="form-control" placeholder="Masukkan ID Sub Menu"
                required readonly>
        </div>
        <div class="form-group">
            <label for="urutan">Urutan Sub Menu</label>
            <code>*</code>
            <input type="text" name="urutan" id="urutan" class="form-control" placeholder="Masukkan Urutan Sub Menu"
                required>
            <code>rekomendasi urutan : <strong><span class="max_urutan"></span></strong></code>
        </div>
        <div class="form-group">
            <label for="nama_submenu">Nama Sub Menu</label>
            <code>*</code>
            <input type="text" name="nama_submenu" id="nama_submenu" class="form-control"
                placeholder="Masukkan Nama Sub Menu" required>
        </div>
        <div class="form-group">
            <label for="url">Nama Url</label>
            <code>*</code>
            <input type="text" name="url" id="url" class="form-control" placeholder="Masukkan Nama Url" required>
        </div>
        <div class="form-group">
            <label for="icon">Nama Icon</label>
            <code>*</code>
            <input type="text" name="icon" id="icon" class="form-control" placeholder="Masukkan Nama Icon" required>
        </div>
        <div class="form-group">
            <label for="rec_stat">Status</label>
            <code>*</code>
            <select name="rec_stat" id="rec_stat" class="form-control" required>
                <option value="" selected>Pilih</option>
                <option value="A">Aktif</option>
                <option value="D">Tdk Aktif</option>
            </select>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <input type="hidden" name="nipp" class="nipp">
        <button type="button" class="btn waves-effect waves-light btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn waves-effect waves-light btn-primary" id="btn-update">Update</button>
    </div>
</form>