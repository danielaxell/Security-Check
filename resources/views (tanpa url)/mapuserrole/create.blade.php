<form id="insert_form" action="#" method="POST">
    @csrf
    <div class="modal-body">
        <div id="warning-add">
        </div>

        <div class="form-group">
            <label>NIPP/Nama</label>
            <code>*</code>
            <select class="select2 form-control custom-select nipp" name="nipp" required>
            </select>
        </div>
        <div class="form-group">
            <label for="id_role">Role</label>
            <code>*</code>
            <select name="id_role" class="select2 form-control custom-select id_role" required>
                @foreach ($roles as $role)
                <option value="{{ $role->id_role }}">{{ $role->idnamarole }}</option>
                @endforeach
            </select>
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