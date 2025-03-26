@extends('_layout')
@section('main_view')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="float-left"> List SubMenu </h4>
                <a href="#" class="btn btn-primary float-right" id="btn-add" data-toggle="modal"
                    data-target="#addModal"><i class="fa fa-plus"></i> Tambah</a>
            </div>
            <div class="card-body">
                <div id="success-notif">
                </div>
                <div id="warning-delete">
                </div>

                <div class="table-responsive">
                    <table id="tabledata" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Urutan</th>
                                <th>ID Sub Menu</th>
                                <th>Nama Sub Menu</th>
                                <th>Url</th>
                                <th>Icon</th>
                                <th>Status</th>
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
<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Sub Menu</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @include('submenu/create')
        </div>
    </div>
</div>

<!-- Modal Edit Sub Menu -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Sub Menu</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @include('submenu/edit')
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- End Modal Edit Sub Menu-->


<!-- Modal Delete Sub Menu-->
<div class="modal fade" id="deleteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Data Sub Menu</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="delete_form" action="#" method="POST">
                @method('delete')
                @csrf
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus submenu ini?</p>
                    <div class="form-group">
                        <label>ID Sub Menu</label>
                        <input type="text" class="form-control id_submenu" disabled>
                    </div>
                    <div class="form-group">
                        <label>Nama Sub Menu</label>
                        <input type="text" class="form-control nama_submenu" disabled>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_submenu" class="id_submenu">
                    <input type="hidden" name="nama_submenu" class="nama_submenu">
                    <button type="button" class="btn waves-effect waves-light btn-secondary"
                        data-dismiss="modal">No</button>
                    <button type="submit" class="btn waves-effect waves-light btn-primary" id="btn_delete">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Delete User-->

<script type="text/javascript" src="/custom/js/submenu.js"></script>

@endSection