@extends('_layout')
@section('main_view')

<style>
	h1#blocked {
		font-weight: lighter;
		letter-spacing: 0.8;
		font-size: 3rem;
		margin-top: 0;
		margin-bottom: 0;
		color: #222;
	}

	.wrap {
		font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
		max-width: 1024px;
		margin: 5rem auto;
		padding: 2rem;
		background: #fff;
		text-align: center;
		border: 1px solid #efefef;
		border-radius: 0.5rem;
		position: relative;
	}

	p#blocked {
		margin-top: 1.5rem;
	}
</style>

<div class="wrap">
	<h1 id="blocked">403 - Forbidden Access</h1>

	<p id="blocked">
		Peringatan! Role Anda tidak dapat mengakses Halaman ini.
	</p>
</div>

@endSection