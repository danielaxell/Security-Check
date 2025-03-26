@extends('auth._layout_auth')
@section('main_view')


<form class="form-horizontal m-t-20" id="login_form" action="#" method="POST">
  @csrf
  <div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text" id="nipp"><i class="ti-user"></i></span>
    </div>
    <input type="text" name="nipp" class="form-control form-control-lg" placeholder="Username/NIPP"
      aria-label="Username/NIPP" aria-describedby="nipp" required>
  </div>
  <div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text" id="password"><i class="ti-lock"></i></span>
    </div>
    <input type="password" name="password" autocomplete="off" class="form-control form-control-lg"
      placeholder="Password" aria-label="Password" aria-describedby="password" data-toggle="password" required>
  </div>
  <div class="form-group row">
    <div class="col-md-12">
      <div class="custom-control custom-checkbox">
        <input type="checkbox" name="remember" class="custom-control-input" id="remember">
        <label class="custom-control-label" for="remember">Remember me</label>
      </div>
    </div>
  </div>
  <div class="form-group text-center">
    <div class="col-xs-12 p-b-20">
      <button class="btn btn-block btn-lg btn-info" type="submit" id="btn-login"><i class="fa fa-sign-in-alt"></i>
        &nbsp;Sign In</button>
    </div>
  </div>
</form>

<script type="text/javascript" src="/custom/js/login.js"></script>
@endSection