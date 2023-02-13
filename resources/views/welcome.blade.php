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
		@guest
		<a class="banner-button" href="/register">Създай своя мениджър профил</a>
		<a class="banner-bottom-text" href="/events">или разгледай събития</a>
		@endguest
	</div>
</div>
<div class="container homepage">
	@php $events = EventController::getTodayEvents(); @endphp
	@if ( count( $events ) )
	<div class="section-heading">
		<h2 class="dashboard-title">Събитията днес</h2>
		<a href="{{ url('/events') }}">Виж всички ></a>
	</div>
	<div class="event-swiper">
		<div class="swiper">
			<div class="swiper-wrapper">
				@foreach($events as $event)
				<div class="swiper-slide p-4">
					<div class="event-box">
						<img src="{{ url('images/event-thumbnails/' . $event['poster']  ) }}" class="event-thumbnail">
						<div class="event-box-content">
							<p class="event-title">{{$event["title"]}}</p>
							<p class="event-date">{{$event["event_date"]}}</p>
						</div>
					</div>
				</div>
				@endforeach
				</div>
			</div>
		@if ( count($events) > 2 )
		<div class="swiper-button-prev">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-left" viewBox="0 0 16 16">
				<path d="M10 12.796V3.204L4.519 8 10 12.796zm-.659.753-5.48-4.796a1 1 0 0 1 0-1.506l5.48-4.796A1 1 0 0 1 11 3.204v9.592a1 1 0 0 1-1.659.753z"/>
			</svg>
		</div>
		<div class="swiper-button-next">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-right" viewBox="0 0 16 16">
				<path d="M6 12.796V3.204L11.481 8 6 12.796zm.659.753 5.48-4.796a1 1 0 0 0 0-1.506L6.66 2.451C6.011 1.885 5 2.345 5 3.204v9.592a1 1 0 0 0 1.659.753z"/>
			</svg>
		</div>
		@endif
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
						<img src="{{ url('images/profile-pictures/' . $artist->profile_picture) }}" class="artist-thumbnail">
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
			@php $location = PlaceController::getSingleLocation( $place->location_id )[0]; @endphp
			<div class="place-box">
				<a href="/place/{{$place->username}}">
					<img src="{{ url('images/profile-pictures/' . $place->profile_picture) }}" class="place-thumbnail">
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
						<p class="place-location">
							{{$location->name}}
						</p>
					</div>
				</a>
			</div>
		@endforeach
	@endif
	</div>
</div>
@endsection
