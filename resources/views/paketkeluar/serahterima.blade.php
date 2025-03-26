<form id="serahterima_form" action="#" method="POST">
    @method('put')
    @csrf
    <div class="modal-body">
        <div id="warning-serahterima">
        </div>

        <div class="form-group">
            <label>Camera Preview</label><br>
            <div id="my_camera" class="mb-2"></div>
            <button type=button class="btn waves-effect waves-light btn-rounded btn-sm btn-info" id="take_snapshot"><i
                    class="fa fa-camera"></i> Ambil Foto</button>
        </div>
        <div class="form-group">
            <div id="result_snapshot"></div>
        </div>
        <div class="form-group">
            <label for="id_ekspedisi">Ekspedisi</label>
            <code>*</code>
            <select name="id_ekspedisi" class="select2 form-control custom-select" id="id_ekspedisi" required>
                @foreach ($ekspedisies as $ekspedisi)
                <option value="{{ $ekspedisi->id_ekspedisi }}">{{ $ekspedisi->nm_ekspedisi }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="tgl_serah">Tanggal Serah</label>
            <code>*</code>
            <div class="input-group">
                <input type="text" name="tgl_serah" class="form-control singledateup" id="tgl_serah"
                    placeholder="Format (DD/MM/YYYY)" autocomplete="off" required>
                <div class="input-group-append">
                    <span class="input-group-text"><i class="ti-calendar"></i></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <code>*wajib diisi</code>
        </div>
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">General Info</h3>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Terminal</td>
                                <td id="nama_terminal"></td>
                            </tr>
                            <tr>
                                <td>Divisi</td>
                                <td id="nama_divisi"></td>
                            </tr>
                            <tr>
                                <td>NIPP</td>
                                <td id="nipp"></td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td id="nama"></td>
                            </tr>
                            <tr>
                                <td>Paket</td>
                                <td id="nama_paket"></td>
                            </tr>
                            <tr>
                                <td>Tujuan Paket</td>
                                <td id="tujuan_paket"></td>
                            </tr>
                            <tr>
                                <td>Tanggal Terima</td>
                                <td id="tgl_terima"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer justify-content-between">
        <input type="hidden" name="id_paketkeluar" id="id_paketkeluar">
        <input type="hidden" name="nipp" id="nipp">
        <input type="hidden" name="nama_paket" id="nama_paket">
        <button type="button" class="btn waves-effect waves-light btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn waves-effect waves-light btn-primary" id="btn-update">Update</button>
    </div>
</form>