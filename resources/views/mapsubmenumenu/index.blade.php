@extends('_layout')
@section('main_view')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="float-left"> List Mapping Sub Menu - Menu </h4>
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
                                <th>Menu</th>
                                <th>ID Sub Menu</th>
                                <th>Sub Menu</th>
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
                <h4 class="modal-title">Tambah Mapping Sub Menu - Menu</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @include('mapsubmenumenu.create')
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Modal Edit-->
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Mapping Sub Menu - Menu</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @include('mapsubmenumenu.edit')
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- End Modal Edit -->


<!-- Modal Delete -->
<div class="modal fade" id="deleteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Data Sub Menu - Menu </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="delete_form" action="#" method="POST">
                @method('delete')
                @csrf
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus submenu-menu ini?</p>
                    <div class="form-group">
                        <label>Menu</label>
                        <input type="text" class="form-control nama_menu" disabled>
                    </div>
                    <div class="form-group">
                        <label>Sub Menu</label>
                        <input type="text" class="form-control nama_submenu" disabled>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_submenu_menu" class="id_submenu_menu">
                    <input type="hidden" name="nama_submenu" class="nama_submenu">
                    <input type="hidden" name="nama_menu" class="nama_menu">
                    <button type="button" class="btn waves-effect waves-light btn-secondary"
                        data-dismiss="modal">No</button>
                    <button type="submit" class="btn waves-effect waves-light btn-primary" id="btn_delete">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Delete -->

<script type="text/javascript" src="{{ url('custom/js/mapsubmenumenu.js') }}"></script>

@endSection