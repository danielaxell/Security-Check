<form id="password_form" action="#" method="POST">
    @method('put')
    @csrf
    <div class="modal-body">
        <div id="warning-password">
        </div>

        <div class="input-group mb-3">
            <input type="password" name="old_password" id="old_password" class="form-control"
                placeholder="Password Lama" data-toggle="password" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="ti-lock"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="password" name="password" id="password" class="form-control" placeholder="Password Baru"
                data-toggle="password" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="ti-lock"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                placeholder="Konfirmasi Password Baru" data-toggle="password" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="ti-lock"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn waves-effect waves-light btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn waves-effect waves-light btn-primary" id="btn-upd-pwd">Update</button>
    </div>
</form>

<script type="text/javascript" src="/custom/js/password.js"></script>