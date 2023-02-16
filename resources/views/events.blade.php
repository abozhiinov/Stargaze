@php 
use App\Http\Controllers\EventController; 
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ArtistController;
@endphp

@extends('layouts.app')

@section('content')
<div class="container">
	<h2 class="text-center dashboard-title">Събития</h2>
	@php 
		$artist_controller = new ArtistController(); 
		$place_controller = new PlaceController();
		$event_controller = new EventController();
		$locations = $place_controller->getAllLocations(); 
		$genres = $artist_controller->getAllGenres();
	@endphp
	<form>
		<form id="event-filter-form" onsubmit="return false">
			<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
			<div class="form-container event-filter justify-content-around row p-4 mt-5">
				<div class="row justify-content-around">
					<input type="text" class="search-input-field col-sm-4 search-artist-events" id="search-artist-events" placeholder="Търси по изпълнител...">
					<input type="text" class="search-input-field col-sm-4 search-place-events" id="search-place-events" placeholder="Търси по заведение...">
					<input type="text" onfocus="(this.type='date')" class="search-input-field col-sm-4 search-date-events" id="search-date-events" placeholder="Търси по дата...">
				</div>
				<div class="row justify-content-around">
					<select class="search-input-field col-sm-4 sort-locations-events" id="sort-location-events">
						<option value="all-locations" selected>Всички локации</option>
						@foreach ( $locations as $location ) 
						<option value="{{$location->id}}">{{$location->name}}</option>
						@endforeach
					</select>
					<select class="search-input-field col-sm-4 sort-genres-events" id="sort-genres-events">
						<option value="all-places" selected>Всички жанрове</option>
						@foreach ( $genres as $genre ) 
						<option value="{{$genre->id}}">{{$genre->name}}</option>
						@endforeach
					</select>
					<select class="search-input-field col-sm-4 order-events" id="order-events">
						<option value="no-sort" selected>По подразбиране</option>
						<option value="alphabet-start">{{_('По азбучен ред - възходящо')}}</option>
						<option value="alphabet-end">{{_('По азбучен ред - низходящо')}}</option>
					</select>
				</div>
			</div>
		</form>
	</form>

	@php $events = $event_controller->allEvents(); @endphp
	<div class="event-dashboard">
	@if(count($events) > 0)
		@foreach($events as $event)
			<div class="event-box">
				<img src="{{ url('images/event-thumbnails/' . $event['poster']  ) }}" class="event-thumbnail">
				<div class="event-box-content">
					<p class="event-title">{{$event["title"]}}</p>
					<p class="event-date">{{$event["event_date"]}}</p>
				</div>
			</div>
		@endforeach
	@endif
	</div>
</div>
@endsection