@extends('_layout')
@section('main_view')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">List Histori Kehadiran Tamu</h4>
                <hr>
                <form id="cari_form" action="#" method="GET">
                    @csrf
                    <div class="row">
                        <div class="col-md-2">
                            <h5 class="float-right">Range Tanggal</h5>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" name="tgl_cari" class="form-control tgl_cari rangedate"
                                        placeholder="Format (DD/MM/YYYY)" autocomplete="off" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="ti-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-2"></div>
                        <div class="col-md-10">
                            <button type="reset" class="btn waves-effect waves-light btn-secondary">Reset</button>
                            <button type="submit" class="btn waves-effect waves-light btn-primary"
                                id="btn-cari">Search</button>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="table-responsive" style="display:none">
                    <table id="tabledata" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Terminal</th>
                                <th>Divisi</th>
                                <th>NIPP</th>
                                <th>Nama Pegawai</th>
                                <th>No.KTP Tamu</th>
                                <th>Nama Tamu</th>
                                <th>Tanggal Datang</th>
                                <th>Tanggal Pulang</th>
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


<!-- Modal View -->
<div class="modal fade" id="viewModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail Kehadiran Tamu</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @include('tamuhadirhist.view')
        </div>
    </div>
</div>

<script type="text/javascript" src="/custom/js/tamuhadirhist.js"></script>

@endSection