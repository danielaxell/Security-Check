<form id="edit_form" action="#" method="POST">
    @method('put')
    @csrf
    <div class="modal-body">
        <div id="warning-edit">
        </div>

        <div class="form-group">
            <label for="nipp">NIPP</label>
            <code>*</code>
            <input type="text" id="nipp" class="form-control" placeholder="Masukkan NIPP" required readonly>
        </div>
        <div class="form-group">
            <label for="id_role">Role</label>
            <code>*</code>
            <select name="id_role" class="select2 form-control custom-select id_role" id="id_role" required>
                @foreach ($roles as $role)
                <option value="{{ $role->id_role }}">{{ $role->idnamarole }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <input type="hidden" name="id_user_role" id="id_user_role">
        <input type="hidden" name="nipp" id="nipp">
        <button type="button" class="btn waves-effect waves-light btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn waves-effect waves-light btn-primary" id="btn-update">Update</button>
    </div>
</form>