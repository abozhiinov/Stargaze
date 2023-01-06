@php use App\Http\Controllers\EventController; @endphp

@extends('layouts.app')

@section('content')
<div class="container">
	<h2 class="text-center dashboard-title">Събития</h2>
	<form>
		<div class="form-container d-flex justify-content-around p-4 mt-5">
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

	@php $events = EventController::allEvents(); @endphp
	<div class="event-dashboard">
	@if(count($events) > 0)
		@foreach($events as $event)
			<div class="event-box">
				<a>
				<div class="event-box-content">
					<img src="{{ url('images/' . $event['poster']  ) }}" class="event-thumbnail">
					{{-- <div class="event-info"> --}}
						<p class="event-title">{{$event["title"]}}</p>
						<p class="event-date">{{$event["event_date"]}}</p>
					{{-- </div> --}}
					<button class="event-book button-custom">Запази</button>
				</div>
				</a>
			</div>
		@endforeach
	@endif
	</div>
</div>
@endsection