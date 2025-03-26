<form id="edit_form" action="#" method="POST">
    @method('put')
    @csrf
    <div class="modal-body">
        <div id="warning-edit">
        </div>

        <div class="form-group">
            <label for="nm_ekspedisi">Nama Ekspedisi</label>
            <code>*</code>
            <input type="text" name="nm_ekspedisi" class="form-control" id="nm_ekspedisi"
                placeholder="Masukkan Nama Ekspedisi" required>
        </div>
        <div class="form-group">
            <label for="ket_ekspedisi">Keterangan Ekspedisi</label>
            <code>*</code>
            <input type="text" name="ket_ekspedisi" class="form-control" id="ket_ekspedisi"
                placeholder="Masukkan Keterangan Ekspedisi" required>
        </div>
        <div class="form-group">
            <code>*wajib diisi</code>
        </div>
    </div>

    <div class="modal-footer justify-content-between">
        <input type="hidden" name="id_ekspedisi" id="id_ekspedisi">
        <button type="button" class="btn waves-effect waves-light btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn waves-effect waves-light btn-primary" id="btn-update">Update</button>
    </div>
</form>