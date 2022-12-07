@extends('layouts.app')

@section('content')
<div class="container">
	<h2 class="text-center dashboard-title">Събития</h2>
	<form>
		<div class="row main-container d-flex justify-content-around p-4 mt-5">
			<input type="text" class="search-input-field" placeholder="Търси събитие...">
			<select class="search-input-field">
				<option value="" disabled selected>Филтри</option>
			</select>
			<select class="search-input-field">
				<option disabled selected>Сортирай</option>
				<option>Подреди по азбучен ред</option>
				<option>Подреди по популярност</option>
			</select>
		</div>
	</form>

	<div class="dashboard justify-content-start align-items-center">
	<?php for ( $count = 0; $count < 12; $count++ ) : ?>
		<div class="main-container col-sm-3 mb-4 dashboard-box">
			<img src="{{url('/images/megami.jpg')}}" class="dashboard-thumbnail">
		</div>
	<?php endfor; ?>
	</div>
</div>
@endsection