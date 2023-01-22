@php 
	use App\Http\Controllers\ArtistController; 
	use App\Http\Controllers\PlaceController; 
@endphp

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

		<!-- Create Event Modal -->
		<div class="modal modal-danger fade" id="createEventModal" tabindex="-1" role="dialog" aria-labelledby="Delete" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<div class=""></div>
						<h5 class="modal-title" id="createEventModalLabel">Създаване на събитие</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form method="" action="" id="form-create-event" onsubmit="return false">
							<input type="hidden" name="_token" id="token" value="<?php echo csrf_token(); ?>">
							<input type="hidden" name="data" id="create-event-data" data-artist data-place data-invitation>
							<div class="form-group">
								<input type="text" class="form-control edit" id="create-event-title" placeholder="Заглавие на събитието">
							</div>
							<div class="input-group custom-file-button">
								<label class="input-group-text" for="create-event-poster">Постер на събитието</label>
								<input type="file" class="form-control edit" id="create-event-poster">
							</div>
							<button type="submit" class="button-submit button-custom" id="submit-create-event">Създай</button>
						</form>
					</div>
				</div>
			</div>
		</div>

		<!-- Edit Artist Modal -->
		@php
		$genres = ArtistController::getAllGenres();
		$locations = PlaceController::getAllLocations();
		$current_location = PlaceController::getSingleLocation( $place->location_id )[0];
		$current_genre = PlaceController::getPlaceGenre( $place->genre_id ); 
		@endphp
		<div class="modal modal-lg modal-danger fade" id="editPlaceModal" tabindex="-1" role="dialog" aria-labelledby="Delete" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<div class=""></div>
						<h5 class="modal-title" id="editPlaceModalLabel">Редактирай заведение</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form method="" action="" id="form-edit-place" onsubmit="return false">
							<div class="row align-items-center">
								<input type="hidden" name="_token" id="token" value="<?php echo csrf_token(); ?>">
								<input type="hidden" name="id" id="id" value="<?php echo $place->id; ?>" data-id="<?php echo $place->id; ?>">
								<div class="form-group col-sm-6">
									<input type="text" class="form-control edit" id="edit-place-name" value="<?php echo  $place->name; ?>" placeholder="Име на изпълнителя">
								</div>
								<div class="form-group col-sm-6">
									<input type="text" class="form-control edit" id="edit-place-username" value="<?php echo $place->username; ?>" placeholder="Потребителско име на изпълнителя">
								</div>
								<div class="form-group col-sm-6">
									<select class="form-control edit select" id="edit-place-genre">
										<option class="genre-option" value="<?php echo $place->genre_id; ?>" selected><?php echo $current_genre->name; ?></option>
										<?php foreach ( $genres as $genre ) : if ( $genre->id !== $place->genre_id ) : ?>
											<option class="genre-option" value="<?php echo $genre->id; ?>"><?php echo $genre->name; ?></option>
										<?php endif; endforeach; ?>
									</select>
								</div>
								<div class="form-group col-sm-6">
									<select class="form-control edit select" id="edit-place-location">
										<option class="location-option" value="<?php echo $place->genre_id; ?>" selected><?php echo $current_location->name; ?></option>
										<?php foreach ( $locations as $location ) : if ( $location->id !== $place->genre_id ) : ?>
											<option class="location-option" value="<?php echo $location->id; ?>"><?php echo $location->name; ?></option>
										<?php endif; endforeach; ?>
									</select>
								</div>
								<div class="form-group col-sm-6">
								<input type="url" class="form-control edit" id="edit-place-facebook" value="<?php echo $place->facebook; ?>" placeholder="Facebook">
								</div>
								<div class="form-group col-sm-6">
								<input type="url" class="form-control edit" id="edit-place-instagram" value="<?php echo $place->instagram; ?>" placeholder="Instagram">
								</div>
								<div class="input-group custom-file-button col-sm-5">
									<label class="input-group-text" for="edit-place-profile-pic">Профилна снимка</label>
									<input type="file" class="form-control edit" id="edit-place-profile-pic" value="<?php echo $place->profile_picture; ?>">
								</div>
								<div class="input-group custom-file-button col-sm-5">
									<label class="input-group-text" for="edit-place-cover-pic">Корица</label>
									<input type="file" class="form-control edit" id="edit-place-cover-pic" value="<?php echo $place->cover_picture; ?>">
								</div>
								<button type="submit" class="button-submit button-custom col-sm-5" id="submit-edit-place">Запази</button>
							</div>
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
		@if ($place->facebook)
		<a href="{{ url($place->facebook) }}">
			<img class="single-place-social facebook" src="{{url('/images/facebook.svg')}}">
		</a>
		@endif
		@if ($place->instagram)
		<a href="{{ url($place->instagram) }}">
			<img class="single-place-social instagram" src="{{url('/images/instagram.svg')}}">
		</a>
		@endif
	</div>
	@if(empty($place->cover_picture))
	</div>
	@endif

	<div class="place-content">
		@php 
		$events = PlaceController::getPlaceEvents( $place->id );
		@endphp
		@if( ! empty( $events ) )
		<h4 class="mx-4">Предстоящи събития</h4>
		<div class="event-dashboard">
			@foreach( $events as $event )
				<div class="event-box">
					<img src="{{ url('images/' . $event['poster']  ) }}" class="event-thumbnail">
					{{-- <button class="event-book button-custom">Запази</button> --}}
					<div class="event-box-content">
						<p class="event-title">{{$event["title"]}}</p>
						<p class="event-date">{{$event["event_date"]}}</p>
					</div>
				</div>
				@endforeach
		</div>
		@else
			<h4 class="no-events">В {{$place->name}} няма предстоящи събития за момента.</h4>
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
</div>
@endsection