<form id="session_form" action="#" method="POST">
    @method('put')
    @csrf
    <div class="modal-body">
        <div id="warning-session">
        </div>

        <div class="form-group mb-3">
            <select name="id_terminal" class="select2 form-control custom-select" id='id_terminal_session' required>
            </select>
        </div>

        <div class="form-group mb-3">
            <select name="id_divisi" class="select2 form-control custom-select" id='id_divisi_session' required>
            </select>
        </div>

    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn waves-effect waves-light btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn waves-effect waves-light btn-primary" id="btn-upd-sess">Update</button>
    </div>
</form>

<script type="text/javascript" src="/custom/js/session.js"></script>