@php use App\Http\Controllers\PlaceController; @endphp

@extends('layouts.app')

@section('content')
<div class="container">
    @php 
	$place = PlaceController::getPlaceData( $username )[0];
	$place_genre = PlaceController::getPlaceGenre( $place->genre_id );
	$is_current_admin = 0;
	@endphp
	@auth
		@php $is_current_admin = ( $place->admin_id === auth()->user()->id ); @endphp
	@endauth
	<div class="back">
		<a href="{{ url('/places') }}">< Назад</a>
	</div>

	@auth
	@if( $is_current_admin ) 
	<div class="admin-panel d-flex justify-content-around">
		<button class="place-see-invitations" data-username={{$username}}>{{_('Виж изпратени покани')}}</button>
		<button class="place-edit">{{_('Редактирай заведение')}}</button>
		<button class="place-delete">{{_('Изтрий заведение')}}</button>
	</div>
	@endif
	@endauth

	{{-- https://stackoverflow.com/questions/60880366/laravel-bootstrap-delete-confirmation-using-modal --}}
		<!-- Delete Warning Modal -->
		<div class="modal modal-danger fade" id="deletePlaceModal" tabindex="-1" role="dialog" aria-labelledby="Delete" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<div class=""></div>
						<h5 class="modal-title" id="deletePlaceModalLabel">Изтриване на заведение</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form id="form-delete-place" data-username={{$username}} onsubmit="return false">
							@csrf
							@method('DELETE')
							<h5 id="delete-place-text" class="text-center text-white ">Сигурни ли сте че искате да изтриете това заведение?</h5>
							<button id="delete-place-yes" type="submit" class="btn btn-sm btn-danger">Да</button>
							<button id="delete-place-no" type="button" class="btn btn-secondary" data-dismiss="modal">Не</button>
							<button id="delete-place-ok" class="button-custom" hidden>ОК!</button>
						</form>
					</div>
				</div>
			</div>
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
			<p class="single-place-genre">{{$place_genre->name}}</p>
			@endif
		</div>
		<div class="place-box-likes">
			<img class="place-likes" src="{{url('/images/likes.svg')}}">
			<p class="place-likes-count"> {{$place->likes}}</p>
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
				<a href="/place/{{$place->username}}">
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