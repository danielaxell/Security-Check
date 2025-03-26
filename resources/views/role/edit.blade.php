<form id="edit_form" action="#" method="POST">
    @method('put')
    @csrf
    <div class="modal-body">
        <div id="warning-edit">
        </div>

        <div class="form-group">
            <label for="id_role">ID Role</label>
            <code>*</code>
            <input type="text" name="id_role" id="id_role" class="form-control" placeholder="Masukkan ID Role" required
                readonly>
        </div>
        <div class="form-group">
            <label for="nama_role">Nama Role</label>
            <code>*</code>
            <input type="text" name="nama_role" id="nama_role" class="form-control" placeholder="Masukkan Nama Role"
                required>
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
        <div class="form-group">
            <code>*wajib diisi</code>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn waves-effect waves-light btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn waves-effect waves-light btn-primary" id="btn-update">Update</button>
    </div>
</form>