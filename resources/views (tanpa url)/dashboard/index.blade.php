@extends('_layout')
@section('main_view')

<div class="row">

    <!-- Column -->
    <div class="col-sm-12 col-md-6">
        <div class="card border-danger">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="align-self-center display-6"><span class="btn btn-circle btn-lg bg-danger"><i
                                class="ti-import text-white"></i></span></div>
                    <div class="p-10 align-self-center">
                        <h3 class="m-b-0">Paket Masuk</h3>
                        <a href="paketmasuk"
                            class="btn waves-effect waves-light btn-rounded btn-outline-danger btn-sm">More >></a>
                    </div>
                    <div class="ml-auto align-self-center">
                        <h1 class="font-medium m-b-0">{{ $paketmasuks->jml }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <div class="col-sm-12 col-md-6">
        <div class="card border-success">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="align-self-center display-6"><span class="btn btn-circle btn-lg bg-success"><i
                                class="ti-export text-white"></i></span></div>
                    <div class="p-10 align-self-center">
                        <h3 class="m-b-0">Paket Keluar</h3>
                        <a href="paketkeluar"
                            class="btn waves-effect waves-light btn-rounded btn-outline-success btn-sm">More >></a>
                    </div>
                    <div class="ml-auto align-self-center">
                        <h1 class="font-medium m-b-0">{{ $paketkeluars->jml }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
    </div>
    <div class="col-md-8">
        <div class="card border-primary">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="align-self-center display-6"><i class="fa fa-users"></i></div>
                    <div class="p-10 align-self-center">
                        <h3 class="m-b-0">Kehadiran Tamu</h3>
                        <a href="tamuhadir"
                            class="btn waves-effect waves-light btn-rounded btn-outline-primary btn-sm">More >></a>
                    </div>
                    <div class="ml-auto align-self-center">
                        <h1 class="font-medium m-b-0">{{ $tamuhadir->jml }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
    </div>
</div>

@endSection