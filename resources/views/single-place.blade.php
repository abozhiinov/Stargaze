@php use App\Http\Controllers\PlaceController; @endphp

@extends('layouts.app')

@section('content')
<div class="container">
    @php 
	$place = PlaceController::getPlaceData( $name )[0];
	$place_genre = PlaceController::getPlaceGenre( $place->genre_id );
	@endphp
	<div class="back">
		<a href="{{ url('/places') }}">< Назад</a>
	</div>
	<div class="place-container">
		@if(!empty($place->cover_picture))
		<img src="{{ url('images/' . $place->cover_picture) }}" class="single-place-thumbnail">
		@else
		<div class="single-place-no-cover">
		@endif
		<div class="single-place-info"> 
			<div class="single-place-title">
				{{$place->name}}
				@if($place->verified == 1)
				<img class="single-place-verified" src="{{url('/images/verified.svg')}}">
				@endif
			</div>
			@if(!empty($place_genre))
			<p class="single-place-genre">{{$place_genre->genre}}</p>
			@endif
		</div>
		<a href="{{ url($place->facebook) }}">
			<img class="single-place-social facebook" src="{{url('/images/facebook.svg')}}">
		</a>
		<a href="{{ url($place->instagram) }}">
			<img class="single-place-social instagram" src="{{url('/images/instagram.svg')}}">
		</a>
		<a href="{{ url($place->youtube) }}">
			<img class="single-place-social youtube" src="{{url('/images/youtube.svg')}}">
		</a>
	</div>
	@if(empty($place->cover_picture))
	</div>
	@endif

	@php 
	$events = PlaceController::getPlaceEvents( $place->id );
	@endphp
	@if( ! empty( $events ) )
	<h4 class="mx-4">Предстоящи събития</h4>
	<div class="upcoming-events">
		@foreach( $events as $event )
			<div class="event-box">
				<img src="{{ url('images/' . $event['poster']  ) }}" class="event-thumbnail">
				<button class="event-book button-custom">Запази</button>
				<div class="event-info">
					<p class="event-title">{{$event["title"]}}</p>
					<p class="event-date">{{$event["event_date"]}}</p>
				</div>
			</div>
			@endforeach
	</div>
	@else
		<h5 class="no-events">В {{$place->name}} няма предстоящи събития за момента.</h5>
	@endif

	
	@php $places = PlaceController::otherPlaces( $place->id, $place->genre_id ) @endphp
	@if(count($places) > 0)
	<h4 class="mx-4">Други заведения</h4>
	<div class="other-artists">
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