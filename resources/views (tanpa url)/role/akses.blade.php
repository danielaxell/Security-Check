<form id="akses_form" action="#" method="POST">
    @method('put')
    @csrf

    <div class="modal-body">
        <div id="warning-edit">
        </div>
        <div class="table-responsive">
            <table id="tabledata_akses" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Sub Menu</th>
                        <th>Create</th>
                        <th>Read</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <input type="hidden" name="nama_role" id="nama_role_akses">
        <button type="button" class="btn waves-effect waves-light btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn waves-effect waves-light btn-primary" id="btn-update-akses">Simpan</button>
    </div>
</form>