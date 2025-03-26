@extends('_layout')
@section('main_view')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="float-left"> List Paket Keluar </h4>
                <a href="#" class="btn btn-primary float-right" id="btn-add" data-toggle="modal"
                    data-target="#addModal"><i class="fa fa-plus"></i> Tambah</a>
            </div>
            <div class="card-body">
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
                                <th>Nama/NIPP</th>
                                <th>Paket</th>
                                <th>Tujuan Paket</th>
                                <th>Tanggal Terima</th>
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


<!-- Modal Add -->
<div class="modal fade" data-backdrop="static" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Input Data Paket Keluar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @include('paketkeluar/create')
        </div>
    </div>
</div>

<!-- Modal Edit paket Keluar -->
<div class="modal fade" data-backdrop="static" id="serahterimaModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Serah Terima Paket Keluar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @include('paketkeluar/serahterima')
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- End Modal Edit paket Keluar-->

<!-- Modal Delete paketkeluar-->
<div class="modal fade" id="deleteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Paket Keluar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="delete_form" action="#" method="POST">
                @method('delete')
                @csrf
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus Paket Keluar "<b class="nama_paket"></b>" Terminal "<b
                            class="nama_terminal"></b>" ini?</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_paketkeluar" class="id_paketkeluar">
                    <input type="hidden" name="nama_paket" class="nama_paket">
                    <button type="button" class="btn waves-effect waves-light btn-secondary"
                        data-dismiss="modal">No</button>
                    <button type="submit" class="btn waves-effect waves-light btn-primary" id="btn_delete">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Delete paketkeluar-->

<script type="text/javascript" src="{{ url('custom/js/paketkeluar.js') }}"></script>

<!-- webcam -->
<script src="{{ url('assets/libs/webcam/webcam.min.js') }}"></script>

@endSection