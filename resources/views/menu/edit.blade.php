<form id="edit_form" action="#" method="POST">
    @method('put')
    @csrf
    <div class="modal-body">
        <div id="warning-edit">
        </div>

        <div class="form-group">
            <label for="id_menu">ID Menu</label>
            <code>*</code>
            <input type="text" name="id_menu" id="id_menu" class="form-control" placeholder="Masukkan ID Menu" required
                readonly>
        </div>
        <div class="form-group">
            <label for="urutan">Urutan Menu</label>
            <code>*</code>
            <input type="number" name="urutan" min=1 id="urutan" class="form-control" placeholder="Masukkan Urutan Menu"
                required>
            <code>rekomendasi urutan : <strong><span class="max_urutan"></span></strong></code>
        </div>
        <div class="form-group">
            <label for="urutan">Nama Menu</label>
            <code>*</code>
            <input type="text" name="nama_menu" id="nama_menu" class="form-control" placeholder="Masukkan Nama Menu"
                required>
        </div>
        <div class="form-group">
            <label for="urutan">Nama Url</label>
            <code>*</code>
            <input type="text" name="url" id="url" class="form-control" placeholder="Masukkan Nama Url">
        </div>
        <div class="form-group">
            <label for="urutan">Nama Icon</label>
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