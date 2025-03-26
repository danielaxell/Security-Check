<form id="edit_form" action="#" method="POST">
    @method('put')
    @csrf
    <div class="modal-body">
        <div id="warning-edit">
        </div>

        <div class="form-group">
            <label for="nipp">NIPP</label>
            <br>
            <input type="text" name="nipp" class="form-control nipp" placeholder="Masukkan NIPP" readonly>
        </div>
        <div class="form-group">
            <label for="id_terminal">Terminal</label>
            <code>*</code>
            <select name="id_terminal" id="id_terminal" class="select2 form-control custom-select id_terminal" required>
                @foreach ($terminals as $terminal)
                <option value="{{ $terminal->id_terminal }}">{{ $terminal->nama_terminal }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Divisi</label>
            <code>*</code>
            <select name="id_divisi" id="id_divisi" class="select2 form-control custom-select id_divisi" required>
            </select>
        </div>
        <div class="form-group">
            <label for="nama">Nama</label>
            <code>*</code>
            <input type="text" name="nama" id="nama" class="form-control nama" placeholder="Masukkan Nama" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <code>*</code>
            <input type="text" name="email" id="email" class="form-control" placeholder="Masukkan Email" required>
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
        <div class="form-group bt-switch">
            <label>User Login? </label>
            <input type="checkbox" name="is_user_login" value="Y" id='is_user_login' data-size="small"
                data-on-color="success" data-off-color="danger" data-on-text="Ya" data-off-text="Tidak">
        </div>
        <div class="form-group">
            <code>*wajib diisi</code>
        </div>
    </div>

    <div class="modal-footer justify-content-between">
        <input type="hidden" name="nipp" class="nipp">
        <button type="button" class="btn waves-effect waves-light btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn waves-effect waves-light btn-primary" id="btn-update">Update</button>
    </div>
</form>