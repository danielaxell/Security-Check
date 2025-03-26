<form id="insert_form" action="#" method="POST">
    @csrf
    <div class="modal-body">
        <div id="warning-add">
        </div>

        <div class="form-group">
            <label for="nm_ekspedisi">Nama Ekspedisi</label>
            <code>*</code>
            <input type="text" name="nm_ekspedisi" class="form-control nm_ekspedisi"
                placeholder="Masukkan Nama Ekspedisi" required>
        </div>
        <div class="form-group">
            <label for="ket_ekspedisi">Keterangan Ekspedisi</label>
            <code>*</code>
            <input type="text" name="ket_ekspedisi" class="form-control ket_ekspedisi"
                placeholder="Masukkan Keterangan Ekspedisi" required>
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