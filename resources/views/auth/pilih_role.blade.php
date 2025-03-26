@extends('auth/_layout_auth')
@section('main_view')


<form class="form-horizontal m-t-20" id="role_form" action="#" method="POST">
  @csrf

  <div class="input-group mb-3">
    <div class="form-group clearfix">
      @foreach ($roles as $row)
      <div class="custom-control custom-radio">
        <input type="radio" id="{{ $row->id_role }}" name="id_role" value="{{ $row->id_role }}"
          class="custom-control-input" required>
        <label class="custom-control-label" for="{{ $row->id_role }}">
          {{ $row->nama_role }}
        </label>
      </div>
      @endforeach
      <span id="validate_role"></span>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <button class="btn btn-block btn-lg btn-info" type="submit" id="btn-role"><i class="fa fa-sign-in-alt"></i>
        &nbsp;Pilih Role</button>
    </div>
    <!-- /.col -->
  </div>
</form>

<script type="text/javascript" src="{{ url('custom/js/pilih_role.js') }}"></script>
@endSection