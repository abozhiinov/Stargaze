@php 
use App\Http\Controllers\ArtistController; 
use App\Http\Controllers\PlaceController;
@endphp

@extends('layouts.app')

@section('content')
<div class="container homepage">
	<div id="homepage_search-box" class="row p-5 form-container">
		<form method="" onsubmit="return false">
			<div id="homepage_search-row" class="row justify-content-around align-items-center">
				<input type="text" class="col-sm-5 mb-4" placeholder="Търси изпълнител...">
				<input type="text" class="col-sm-5 mb-4" placeholder="Търси място...">
				<div class="w-100"></div>
				<input type="text" onfocus="(this.type='date')" class="col-sm-5 mb-4" placeholder="Търси по дата...">
				<select class="col-sm-5 mb-4">
					<option value="" disabled selected>Търси по жанр...</option>
				</select>
				<input type="submit" value="Търси" class="button-custom w-50">
			</div>
		</form>
	</div>

	@php $artists = ArtistController::getPopularArtists() @endphp
	<div class="section-heading">
		<h2 class="dashboard-title">Популярни изпълнители</h2>
		<a href="{{ url('/artists') }}">Виж всички ></a>
	</div>
	<div class="dashboard">
		@if(count($artists) > 0)
			@foreach($artists as $artist)
				<div class="artist-box">
					<a href="/artist/{{$artist->username}}">
						<img src="{{ url('images/' . $artist->profile_picture) }}" class="artist-thumbnail">
						<div class="artist-box-likes">
							<img class="artist-likes" src="{{url('/images/likes.svg')}}">
							<p class="artist-likes-count"> {{$artist->likes}}</p>
						</div>
						<div class="artist-box-content">
							<p class="artist-title">
								{{$artist->name}}
								<span>
								@if($artist->verified == 1)
								<img class="artist-verified" src="{{url('/images/verified.svg')}}">
								@endif
								</span>
							</p>
						</div>
					</a>
				</div>
			@endforeach
		@endif
	</div>

	@php $places = PlaceController::getPopularPlaces(); @endphp
	<div class="section-heading">
		<h2 class="dashboard-title">Популярни заведения</h2>
		<a href="{{ url('/places') }}">Виж всички ></a>
	</div>
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
