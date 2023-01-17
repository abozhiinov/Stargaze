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
		$locations = PlaceController::getAllLocations(); 
		$genres = ArtistController::getAllGenres();
	@endphp
	<form>
		<form onsubmit="return false">
			<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
			<div class="form-container justify-content-around row p-4 mt-5">
				<div class="row justify-content-around">
					<input type="text" class="search-input-field col-sm-5 search-dashboard events" id="search-dashboard" placeholder="Търси по изпълнител...">
					<input type="text" class="search-input-field col-sm-5 search-dashboard events" id="search-dashboard" placeholder="Търси по заведение...">
				</div>
				<div class="row justify-content-around">
					<select class="search-input-field col-sm-3 sort-genres-dashboard events" id="sort-genres-dashboard">
						<option value="all-places" selected>Всички локации</option>
						@foreach ( $locations as $location ) 
						<option value="{{$location->id}}">{{$location->name}}</option>
						@endforeach
					</select>
					<select class="search-input-field col-sm-3 sort-genres-dashboard events" id="sort-genres-dashboard">
						<option value="all-places" selected>Всички жанрове</option>
						@foreach ( $genres as $genre ) 
						<option value="{{$genre->id}}">{{$genre->name}}</option>
						@endforeach
					</select>
					<select class="search-input-field col-sm-3 order-dashboard events" id="order-dashboard">
						<option value="no-sort" selected>По подразбиране</option>
						<option value="alphabet-start">{{_('По дата')}}</option>
						<option value="alphabet-end">{{_('По азбучен ред - низходящо')}}</option>
						<option value="popular">{{_('По популярност - низходящо')}}</option>
						<option value="unpopular">{{_('По популярност - възходящо')}}</option>
					</select>
				</div>
			</div>
		</form>
	</form>

	@php $events = EventController::allEvents(); @endphp
	<div class="event-dashboard">
	@if(count($events) > 0)
		@foreach($events as $event)
			<div class="event-box">
				<img src="{{ url('images/' . $event['poster']  ) }}" class="event-thumbnail">
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