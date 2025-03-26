<form id="insert_form" action="#" method="POST">
    @csrf
    <div class="modal-body">
        <div id="warning-add">
        </div>

        <div class="form-group">
            <label>Camera Preview</label>
            <code>*</code>
            <br>
            <div id="my_camera" class="mb-2"></div>
            <button type=button class="btn waves-effect waves-light btn-info" id="take_snapshot">Ambil Foto</button>
        </div>
        <div class="form-group">
            <div id="result_snapshot"></div>
        </div>

        <div class="form-group">
            <label for="ktp">KTP</label>
            <code>*</code>
            <input type="text" name="ktp" class="form-control ktp" placeholder="Masukkan No.KTP" required>
        </div>
        <div class="form-group">
            <label for="nama">Nama</label>
            <code>*</code>
            <input type="text" name="nama" class="form-control nama" placeholder="Masukkan Nama" required>
        </div>
        <div class="form-group">
            <label for="alamat">Alamat</label>
            <code>*</code>
            <input type="text" name="alamat" class="form-control alamat" placeholder="Masukkan Alamat" required>
        </div>
        <div class="form-group">
            <label for="instansi">Instansi</label>
            <code>*</code>
            <input type="text" name="instansi" class="form-control instansi" placeholder="Masukkan Instansi" required>
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