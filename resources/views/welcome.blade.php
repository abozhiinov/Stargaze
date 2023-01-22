@php 
use App\Http\Controllers\ArtistController; 
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\EventController;
@endphp

@extends('layouts.app')

@section('content')
<div class="welcome-banner">
	<img src="{{url('/images/homepage-party.jpg')}}">
	<div class="banner-content">
		<p class="banner-title">Това е STARGAZE</p>
		<p class="banner-body">
			Твоят помощник в организацията на 
			<span>най-добрите</span> музикални събития
		</p>
		<a class="banner-button" href="/register">Създай своя мениджър профил</a>
		<a class="banner-bottom-text" href="/events">или разгледай събития</a>
	</div>
</div>
<div class="container homepage">
	@php $events = EventController::getTodayEvents(); @endphp
	@if ( count( $events ) )
	<div class="section-heading">
		<h2 class="dashboard-title">Събитията днес</h2>
		<a href="{{ url('/events') }}">Виж всички ></a>
	</div>
	<div class="event-dashboard">
		@foreach($events as $event)
			<div class="event-box">
				<img src="{{ url('images/' . $event['poster']  ) }}" class="event-thumbnail">
				<div class="event-box-content">
					<p class="event-title">{{$event["title"]}}</p>
					<p class="event-date">{{$event["event_date"]}}</p>
					{{-- <button class="event-book button-custom">Запази</button> --}}
				</div>
			</div>
		@endforeach
	</div>
	@endif

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
