<form id="insert_form" action="#" method="POST">
    @csrf
    <div class="modal-body">
        <div id="warning-add">
        </div>

        <div class="form-group">
            <label for="id_menu">Menu</label>
            <code>*</code>
            <select name="id_menu" class="select2 form-control custom-select id_menu" required>
                @foreach ($menus as $menu)
                <option value="{{ $menu->id_menu }}">{{ $menu->nama_menu }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="id_submenu">Sub Menu</label>
            <code>*</code>
            <select name="id_submenu" class="select2 form-control custom-select id_submenu" required>
                @foreach ($submenus as $submenu)
                <option value="{{ $submenu->id_submenu }}">{{ $submenu->nama_submenu }}</option>
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