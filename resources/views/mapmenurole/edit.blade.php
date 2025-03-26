<form id="edit_form" action="#" method="POST">
    @method('put')
    @csrf
    <div class="modal-body">
        <div id="warning-edit">
        </div>

        <div class="form-group">
            <label for="nama_role">Role</label>
            <code>*</code>
            <input type="text" id="nama_role" class="form-control" placeholder="Masukkan Nama Role" required readonly>
        </div>
        <div class="form-group">
            <label for="id_menu">Menu</label>
            <code>*</code>
            <select name="id_menu" class="select2 form-control custom-select" id="id_menu" required>
                @foreach ($menus as $menu)
                <option value="{{ $menu->id_menu }}">{{ $menu->nama_menu }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <input type="hidden" name="id_menu_role" id="id_menu_role">
        <input type="hidden" name="id_role" id="id_role">
        <input type="hidden" name="nama_role" id="nama_role">
        <button type="button" class="btn waves-effect waves-light btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn waves-effect waves-light btn-primary" id="btn-update">Update</button>
    </div>
</form>