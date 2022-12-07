@extends('layouts.app')

@section('content')
<div class="container">
	<h2 class="dashboard-title text-center">Изпълнители</h2>
	<form>
		<div class="row main-container d-flex justify-content-around p-4 mt-5">
			<input type="text" class="search-input-field" placeholder="Търси изпълнител...">
			<select class="search-input-field">
				<option value="" disabled selected>Жанрове</option>
			</select>
			<select class="search-input-field">
				<option value="" disabled selected>Сортирай</option>
			</select>
		</div>
	</form>

	<div class="dashboard justify-content-start align-items-center">
		<?php for ( $count = 0; $count < 8; $count++ ) : ?>
			<div class="main-container col-sm-3 mb-4 dashboard-box">
				<img src="{{url('/images/galena.jpg')}}" class="dashboard-thumbnail">
			</div>
		<?php endfor; ?>
	</div>
</div>
@endsection