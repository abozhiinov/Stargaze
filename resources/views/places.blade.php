@php use App\Http\Controllers\PlaceController; @endphp

@extends('layouts.app')

@section('content')
<div class="container">
	<h2 class="text-center dashboard-title">Заведения</h2>
	<form>
		<div class="form-container d-flex justify-content-around p-4 mt-5">
			<input type="text" class="search-input-field" placeholder="Търси заведение...">
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

	@php $places = PlaceController::allPlaces(); @endphp
	<div class="dashboard">
	@if(count($places) > 0)
		@foreach($places as $place)
			<div class="place-box">
				<a href="/place/{{$place->name}}">
					<img src="{{ url('images/' . $place->profile_picture) }}" class="place-thumbnail">
					<div class="place-box-content">
						<p class="place-title">
							{{$place->name}}
							<span>
							@if($place->verified == 1)
								<img class="place-verified" src="{{url('/images/verified.svg')}}">
							@endif
							</span>
						</p>
					</div>
				</a>
			</div>
		@endforeach
	@endif
	</div>
</div>
@endsection