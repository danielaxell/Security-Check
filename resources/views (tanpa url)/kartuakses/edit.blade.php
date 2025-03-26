<form id="edit_form" action="#" method="POST">
    @method('put')
    @csrf
    <div class="modal-body">
        <div id="warning-edit">
        </div>

        <div class="form-group">
            <label>Nama Terminal</label>
            <p id=nama_terminal></p>
        </div>
        <hr>
        <div class="form-group">
            <label for="no_kartuakses">No Kartu Akses</label>
            <code>*</code>
            <input type="text" name="no_kartuakses" class="form-control" id="no_kartuakses"
                placeholder="Masukkan No Kartu Akses" required>
        </div>
        <div class="form-group">
            <label for="nama_kartuakses">Nama Kartu Akses</label>
            <code>*</code>
            <input type="text" name="nama_kartuakses" class="form-control" id="nama_kartuakses"
                placeholder="Masukkan Nama Kartu Akses" required>
        </div>
        <div class="form-group">
            <code>*wajib diisi</code>
        </div>
    </div>

    <div class="modal-footer justify-content-between">
        <input type="hidden" name="id_kartuakses" id="id_kartuakses">
        <button type="button" class="btn waves-effect waves-light btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn waves-effect waves-light btn-primary" id="btn-update">Update</button>
    </div>
</form>