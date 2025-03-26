<form id="edit_form" action="#" method="POST">
    @method('put')
    @csrf
    <div class="modal-body">
        <div id="warning-edit">
        </div>

        <div class="form-group">
            <label for="nm_terminal">Nama Terminal</label>
            <code>*</code>
            <input type="text" id="nm_terminal" class="form-control" placeholder="Masukkan Nama Terminal" required
                readonly>
        </div>
        <div class="form-group">
            <label for="kd_divisi">Kode Divisi</label>
            <code>*</code>
            <input type="text" name="kd_divisi" id="kd_divisi" class="form-control" placeholder="Masukkan Kode Divisi"
                required>
        </div>
        <div class="form-group">
            <label for="nm_divisi">Nama Divisi</label>
            <code>*</code>
            <input type="text" name="nama_divisi" id="nm_divisi" class="form-control" placeholder="Masukkan Nama Divisi"
                required>
        </div>
        <div class="form-group">
            <code>*wajib diisi</code>
        </div>
    </div>

    <div class="modal-footer justify-content-between">
        <input type="hidden" name="id_divisi" id="id_divisi">
        <button type="button" class="btn waves-effect waves-light btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn waves-effect waves-light btn-primary" id="btn-update">Update</button>
    </div>
</form>