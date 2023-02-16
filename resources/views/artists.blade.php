@php
	use App\Http\Controllers\ArtistController; 
	use Symfony\Component\Console\Input\Input;
@endphp

@extends('layouts.app')

@section('content')
<div class="container">
	<h2 class="dashboard-title text-center">Изпълнители</h2>
	@php 
	$artist_controller = new ArtistController(); 
	$artists = $artist_controller->allArtists(); 
	$genres = $artist_controller->getAllGenres();
	@endphp
	<form onsubmit="return false">
		<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
		<div class="form-container d-flex justify-content-around p-4">
			<input type="text" class="search-input-field search-dashboard artists" placeholder="Търси изпълнител..." id="search-dashboard">
			<select class="search-input-field sort-genres-dashboard artists" id="sort-genres-dashboard">
				<option value="all-genres" selected>Всички жанрове</option>
				@foreach ( $genres as $genre )
				<option value="{{$genre->id}}">{{$genre->name}}</option>
				@endforeach
			</select>
			<select class="search-input-field order-dashboard artists" id="order-dashboard">
				<option value="no-sort" selected>{{_('По подразбиране')}}</option>
				<option value="alphabet-start">{{_('По азбучен ред - възходящо')}}</option>
				<option value="alphabet-end">{{_('По азбучен ред - низходящо')}}</option>
				<option value="popular">{{_('По популярност - низходящо')}}</option>
				<option value="unpopular">{{_('По популярност - възходящо')}}</option>
			</select>
		</div>
	</form>
	<div class="dashboard">
		@if(count($artists) > 0)
			@foreach($artists as $artist)
			@php $genre = $artist_controller->getArtistGenre( $artist->genre_id ); @endphp
				<div class="artist-box">
					<a href="/artist/{{$artist->username}}">
						<img src="{{ url('images/profile-pictures/' . $artist->profile_picture) }}" class="artist-thumbnail">
						<div class="artist-box-likes">
							<div class="artist-likes">
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256" width="100px" height="100px" fill-rule="nonzero"><g fill="currentColor" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M25,48c-11.775,0 -20,-8.112 -20,-19.729c0,-6.065 3.922,-12.709 9.325,-15.797c0.151,-0.086 0.322,-0.132 0.496,-0.132c0.803,0 1.407,0.547 1.407,1.271c0,1.18 0.456,3.923 1.541,5.738c4.455,-1.65 9.074,-5.839 7.464,-16.308c-0.008,-0.051 -0.012,-0.102 -0.012,-0.152c0,-0.484 0.217,-0.907 0.579,-1.132c0.34,-0.208 0.764,-0.221 1.14,-0.034c4.233,2.083 18.06,10.167 18.06,26.682c0,10.987 -8.785,19.593 -20,19.593zM26.052,3.519c0.003,0.001 0.005,0.002 0.008,0.004c-0.003,-0.002 -0.005,-0.003 -0.008,-0.004z"/></g></g></svg>
								<p class="artist-likes-count"> {{$artist->likes}}</p>
							</div>
							
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
							<p class="artist-genre">
								{{$genre->name}}
							</p>
						</div>
					</a>
				</div>
			@endforeach
		@endif
	</div>
</div>
@endsection