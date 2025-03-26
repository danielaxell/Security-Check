@extends('_layout')
@section('main_view')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="float-left"> List Role </h4>
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
                                <th>ID Role</th>
                                <th>Nama Role</th>
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
                <h4 class="modal-title">Tambah Role User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @include('role.create')
        </div>
    </div>
</div>

<!-- Modal Akses -->
<div class="modal fade" id="aksesModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Setting Permission Hak Akses User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @include('role.akses')
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- End Modal Akses Role-->

<!-- Modal Edit -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Role User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @include('role.edit')
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- End Modal Edit Role-->


<!-- Modal Delete Role-->
<div class="modal fade" id="deleteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Data Role</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="delete_form" action="#" method="POST">
                @method('delete')
                @csrf
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus role ini?</p>
                    <div class="form-group">
                        <label>ID Role</label>
                        <input type="text" class="form-control id_role" disabled>
                    </div>
                    <div class="form-group">
                        <label>Nama Role</label>
                        <input type="text" name='nama_role' class="form-control nama_role" disabled>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_role" class="id_role">
                    <input type="hidden" name="nama_role" class="nama_role">
                    <button type="button" class="btn waves-effect waves-light btn-secondary"
                        data-dismiss="modal">No</button>
                    <button type="submit" class="btn waves-effect waves-light btn-primary" id="btn_delete">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Delete User-->

<script type="text/javascript" src="{{ url('custom/js/role.js') }}"></script>

@endSection