<form id="insert_form" action="#" method="POST">
    @csrf
    <div class="form-body">
        <h5 class="card-title">Input Kehadiran Tamu</h5>
        <hr>
        <div class="row">
            <div class="col-md-2">
                <label class="control-label float-right">Cari Nama / No.KTP</label>
            </div>
            <div class="col-md-3">
                <select class="select2 form-control custom-select id_tamu">
                </select>
            </div>
            <div class="col-md-5"></div>
            <div class="col-md-2">
                <label class="control-label">Tambah Data</label>
                <a href="#" class="btn btn-primary waves-effect btn-rounded waves-light" id="btn-add"
                    data-toggle="tooltip" title="Tambah Data">
                    <i class="fa fa-plus"></i>
                </a>
            </div>
        </div>
        <hr>
        <div id="warning-add">
        </div>
        <div class="row form_input_tamu" style="display: none;">
            <div class="col-md-6">
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
                    <input type="text" name="instansi" class="form-control instansi" placeholder="Masukkan Instansi"
                        required>
                </div>
                <div class="form-group">
                    <label>Pegawai Yang Ditemui</label>
                    <code>*</code>
                    <select class="select2 form-control custom-select nipp" name="nipp" required>
                    </select>
                </div>
                <div class="form-group">
                    <label>Divisi Pegawai</label>
                    <code>*</code>
                    <select name="id_divisi" class="select2 form-control custom-select id_divisi" required>
                    </select>
                </div>
                <div class="form-group">
                    <label>Kartu Akses</label>
                    <code>*</code>
                    <select name="id_kartuakses" class="select2 form-control custom-select id_kartuakses" required>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tgl_datang">Tanggal Datang</label>
                    <code>*</code>
                    <div class="input-group">
                        <input type="text" name="tgl_datang" class="form-control tgl_datang singledatetime"
                            placeholder="Format (DD/MM/YYYY HH:mm)" autocomplete="off" required>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="ti-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <code>*wajib diisi</code>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="frame_file_foto">
                    <label>Foto Tamu</label><br>
                    <img id="data_file_foto" alt="Foto Tamu" class="mb-1"><br>
                    <!-- <button type="button" class="btn waves-effect waves-light btn-rounded btn-sm btn-info" id="btn-ubah-foto"><i class="fa fa-edit"></i> Ubah Foto </button> -->
                </div>
                <div class="form-group" id="frame_camera_preview">
                    <label>Camera Preview</label>
                    <code>*</code>
                    <br>
                    <div id="my_camera" class="mb-2"></div>
                    <button type=button class="btn waves-effect waves-light btn-rounded btn-sm btn-info"
                        id="take_snapshot"><i class="fa fa-camera"></i> Ambil Foto </button>
                </div>
                <div class="form-group">
                    <div id="result_snapshot"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-actions m-t-20 tombol_input_tamu" style="display: none;">
        <input type="hidden" name="id_tamu" id="id_tamu">
        <input type="hidden" name="akses" id="akses">
        <button type="button" class="btn btn-dark" id="btn-cancel">Cancel</button>
        <button type="submit" class="btn btn-success" id="btn-simpan"> <i class="fa fa-check"></i> Simpan</button>
    </div>
</form>