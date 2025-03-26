<form id="edit_form" action="#" method="POST">
    @method('put')
    @csrf
    <div class="modal-body">
        <div id="warning-edit">
        </div>
        <div class="form-group" id="frame_file_foto_edit">
            <label>Foto Tamu</label><br>
            <img id="file_foto" alt="Foto Tamu" class="mb-1"><br>
            <button type="button" class="btn waves-effect waves-light btn-rounded btn-sm btn-info" id="btn-ubah-foto"><i
                    class="fa fa-edit"></i> Ubah Foto </button>
        </div>
        <div class="form-group" id="frame_camera_preview_edit">
            <label>Camera Preview</label><br>
            <div id="my_camera_edit" class="mb-2"></div>
            <button type=button class="btn waves-effect waves-light btn-rounded btn-sm btn-info"
                id="take_snapshot_edit"><i class="fa fa-camera"></i> Ambil Foto </button>
        </div>
        <div class="form-group">
            <div id="result_snapshot_edit"></div>
        </div>
        <hr>
        <div class="form-group">
            <label for="ktp">KTP</label>
            <code>*</code>
            <input type="text" name="ktp" class="form-control" id="ktp" placeholder="Masukkan No.KTP" required>
        </div>
        <div class="form-group">
            <label for="nama">Nama</label>
            <code>*</code>
            <input type="text" name="nama" class="form-control" id="nama" placeholder="Masukkan Nama" required>
        </div>
        <div class="form-group">
            <label for="alamat">Alamat</label>
            <code>*</code>
            <input type="text" name="alamat" class="form-control" id="alamat" placeholder="Masukkan Alamat" required>
        </div>
        <div class="form-group">
            <label for="instansi">Instansi</label>
            <code>*</code>
            <input type="text" name="instansi" class="form-control" id="instansi" placeholder="Masukkan Instansi"
                required>
        </div>
        <div class="form-group">
            <code>*wajib diisi</code>
        </div>
    </div>

    <div class="modal-footer justify-content-between">
        <input type="hidden" name="id_tamu" id="id_tamu">
        <button type="button" class="btn waves-effect waves-light btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn waves-effect waves-light btn-primary" id="btn-update">Update</button>
    </div>
</form>