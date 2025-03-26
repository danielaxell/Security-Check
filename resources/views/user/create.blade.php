<form id="insert_form" action="#" method="POST">
    @csrf
    <div class="modal-body">
        <div id="warning-add">
        </div>

        <div class="form-group">
            <label for="id_terminal">Terminal</label>
            <code>*</code>
            <select name="id_terminal" class="select2 form-control custom-select id_terminal" required>
                @foreach ($terminals as $terminal)
                <option value="{{ $terminal->id_terminal }}">{{ $terminal->nama_terminal }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="id_divisi">Divisi</label>
            <code>*</code>
            <select name="id_divisi" class="select2 form-control custom-select id_divisi" required>
            </select>
        </div>
        <div class="form-group">
            <label for="nipp">NIPP</label>
            <code>*</code>
            <input type="text" name="nipp" class="form-control nipp" placeholder="Masukkan NIPP" required>
        </div>
        <div class="form-group">
            <label for="nama">Nama</label>
            <code>*</code>
            <input type="text" name="nama" class="form-control nama" placeholder="Masukkan Nama" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <code>*</code>
            <input type="text" name="email" class="form-control email" placeholder="Masukkan Email" required>
        </div>
        <div class="form-group">
            <label for="rec_stat">Status</label>
            <code>*</code>
            <select name="rec_stat" class="form-control" required>
                <option value="" selected>Pilih</option>
                <option value="A">Aktif</option>
                <option value="D">Tdk Aktif</option>
            </select>
        </div>
        <div class="form-group bt-switch">
            <label>User Login? </label>
            <input type="checkbox" name="is_user_login" value="Y" data-size="small" data-on-color="success"
                data-off-color="danger" data-on-text="Ya" data-off-text="Tidak">
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