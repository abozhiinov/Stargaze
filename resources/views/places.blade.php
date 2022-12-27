@php use App\Http\Controllers\PlaceController; @endphp

@extends('layouts.app')

@section('content')
<div class="container">
	<h2 class="text-center dashboard-title">Заведения</h2>
	@php $locations = PlaceController::getAllLocations(); @endphp
	<form onsubmit="return false">
		<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
		<div class="form-container d-flex justify-content-around p-4 mt-5">
			<input type="text" class="search-input-field search-dashboard places" id="search-dashboard" placeholder="Търси заведение...">
			<select class="search-input-field sort-genres-dashboard places" id="sort-genres-dashboard">
				<option value="all-places" selected>Всички локации</option>
				@foreach ( $locations as $location ) 
				<option value="{{$location->id}}">{{$location->name}}</option>
				@endforeach
			</select>
			<select class="search-input-field order-dashboard places" id="order-dashboard">
				<option value="no-sort" selected>По подразбиране</option>
				<option value="alphabet">По азбучен ред</option>
				<option value="popular">По популярност</option>
			</select>
		</div>
	</form>

	@php $places = PlaceController::allPlaces(); @endphp
	<div class="dashboard">
	@if(count($places) > 0)
		@foreach($places as $place)
			<div class="place-box">
				<a href="/place/{{$place->username}}">
					<img src="{{ url('images/' . $place->profile_picture) }}" class="place-thumbnail">
					<div class="place-box-likes">
						<img class="place-likes" src="{{url('/images/likes.svg')}}">
						<p class="place-likes-count"> {{$place->likes}}</p>
					</div>
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