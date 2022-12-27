@php
	use App\Http\Controllers\ArtistController; 
	use Symfony\Component\Console\Input\Input;
@endphp

@extends('layouts.app')

@section('content')
<div class="container">
	<h2 class="dashboard-title text-center">Изпълнители</h2>
	@php $genres = ArtistController::getAllGenres(); @endphp
	<form onsubmit="return false">
		<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
		<div class="form-container d-flex justify-content-around p-4 mt-5">
			<input type="text" class="search-input-field search-dashboard artists" placeholder="Търси изпълнител..." id="search-dashboard">
			<select class="search-input-field sort-genres-dashboard artists" id="sort-genres-dashboard">
				<option value="all-genres" selected>Всички жанрове</option>
				@foreach ( $genres as $genre )
				<option value="{{$genre->id}}">{{$genre->name}}</option>
				@endforeach
			</select>
			<select class="search-input-field order-dashboard artists" id="order-dashboard">
				<option value="no-sort" selected>По подразбиране</option>
				<option value="alphabet">По азбучен ред</option>
				<option value="popular">По популярност</option>
			</select>
		</div>
	</form>
	@php $artists = ArtistController::allArtists() @endphp
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
</div>
@endsection