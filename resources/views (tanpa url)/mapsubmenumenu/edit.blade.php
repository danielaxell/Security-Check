<form id="edit_form" action="#" method="POST">
    @method('put')
    @csrf
    <div class="modal-body">
        <div id="warning-edit">
        </div>

        <div class="form-group">
            <label for="nama_menu">Menu</label>
            <code>*</code>
            <input type="text" id="nama_menu" class="form-control" placeholder="Masukkan Menu" required readonly>
        </div>
        <div class="form-group">
            <div class="form-group">
                <label for="id_submenu">Sub Menu</label>
                <code>*</code>
                <select name="id_submenu" class="select2 form-control custom-select" id="id_submenu" required>
                    @foreach ($submenus as $submenu)
                    <option value="{{ $submenu->id_submenu }}">{{ $submenu->nama_submenu }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <input type="hidden" name="id_submenu_menu" id="id_submenu_menu">
        <input type="hidden" name="id_menu" id="id_menu">
        <input type="hidden" name="nama_menu" id="nama_menu">
        <button type="button" class="btn waves-effect waves-light btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn waves-effect waves-light btn-primary" id="btn-update">Update</button>
    </div>
</form>