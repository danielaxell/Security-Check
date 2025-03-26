@extends('_layout')
@section('main_view')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                @include('tamuhadir.create')
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">List Kehadiran Tamu</h5>
                <hr>
                <div id="success-notif">
                </div>
                <div id="warning-delete">
                </div>

                <div class="table-responsive">
                    <table id="tabledata" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Terminal/Divisi</th>
                                <th>Nama Pegawai/NIPP</th>
                                <th>No.KTP Tamu</th>
                                <th>Nama Tamu</th>
                                <th>Tanggal Datang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Kehadiran Tamu -->
<div class="modal fade" data-backdrop="static" id="serahterimaModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Serah Terima Kehadiran Tamu</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @include('tamuhadir.serahterima')
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- End Modal Edit Kehadiran Tamu-->

<!-- Modal Delete paketmasuk-->
<div class="modal fade" id="deleteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Kehadiran Tamu</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="delete_form" action="#" method="POST">
                @method('delete')
                @csrf
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus kehadiran tamu No.KTP "<b class="ktp"></b>", Nama "<b
                            class="nama"></b>", Tgl Datang "<b class="tgl_datang"></b>" ini?</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_tamuhadir" class="id_tamuhadir">
                    <input type="hidden" name="nama" class="nama">
                    <input type="hidden" name="tgl_datang" class="tgl_datang">
                    <button type="button" class="btn waves-effect waves-light btn-secondary"
                        data-dismiss="modal">No</button>
                    <button type="submit" class="btn waves-effect waves-light btn-primary" id="btn_delete">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Delete tamuhadir-->

<script type="text/javascript" src="{{ url('custom/js/tamuhadir.js') }}"></script>
<!-- webcam -->
<script src="{{ url('assets/libs/webcam/webcam.min.js') }}"></script>

@endSection