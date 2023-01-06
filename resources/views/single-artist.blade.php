@php 
	use App\Http\Controllers\ArtistController; 
	use App\Http\Controllers\PlaceController; 
@endphp

@extends('layouts.app')

@section('content')
<div class="container">
	@php 
	$artist = ArtistController::getArtistData( $username )[0];
	$artist_genre = ArtistController::getArtistGenre( $artist->genre_id );
	$is_current_admin = 0;
	@endphp
	@auth
		@php $is_current_admin = ( $artist->admin_id === auth()->user()->id ); @endphp
	@endauth
	@if($artist)
		<div class="back">
			<a href="{{ url('/artists') }}">< Назад</a>
		</div>

		@auth
		@if( $is_current_admin ) 
		<div class="admin-panel d-flex justify-content-around">
			<button class="artist-see-invitations" data-username={{$username}}>{{_('Виж покани')}}</button>
			<button class="artist-edit" data-username={{$username}}>{{_('Редактирай изпълнител')}}</button>
			<button class="artist-delete">{{_('Изтрий изпълнител')}}</button>
		</div>
		@else
			@php $has_places = PlaceController::getAdminPlaces( auth()->user()->id ); @endphp
			@if ( count($has_places) )
			<div class="admin-panel d-flex justify-content-around">
				<button class="artist-invite">{{_('Покани изпълнител')}}</button>
			</div>
			@endif
		@endif
		@endauth

		<div class="artist-container">
			@if( $artist->cover_picture )
			<img src="{{ url('images/' . $artist->cover_picture) }}" class="single-artist-thumbnail">
			@else
			<div class="single-artist-no-cover">
			@endif
			<div class="single-artist-info"> 
				<div class="single-artist-name">
					{{$artist->name}}
					@if( $artist->verified )
					<img class="single-artist-verified" src="{{url('/images/verified.svg')}}">
					@endif
				</div>
				@if( $artist_genre )
				<p class="single-artist-genre">{{$artist_genre->name}}</p>
				@endif
			</div>
			<div class="artist-box-likes">
				<img class="artist-likes" src="{{url('/images/likes.svg')}}">
				<p class="artist-likes-count"> {{$artist->likes}}</p>
			</div>
			@if ( $artist->facebook )
			<a href="{{ url($artist->facebook) }}">
				<img class="single-artist-social facebook" src="{{url('/images/facebook.svg')}}">
			</a>
			@endif
			@if ( $artist->instagram )
			<a href="{{ url($artist->instagram) }}">
				<img class="single-artist-social instagram" src="{{url('/images/instagram.svg')}}">
			</a>
			@endif
			@if ( $artist->youtube )
			<a href="{{ url($artist->youtube) }}">
				<img class="single-artist-social youtube" src="{{url('/images/youtube.svg')}}">
			</a>
			@endif
		</div>
		@if(empty($artist->cover_picture))
		</div>
		@endif

		{{-- https://stackoverflow.com/questions/60880366/laravel-bootstrap-delete-confirmation-using-modal --}}
		<!-- Delete Warning Modal -->
		<div class="modal modal-danger fade" id="deleteArtistModal" tabindex="-1" role="dialog" aria-labelledby="Delete" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<div class=""></div>
						<h5 class="modal-title" id="deleteArtistModalLabel">Изтриване на изпълнител</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form id="form-delete-artist" data-username={{$username}} onsubmit="return false">
							@csrf
							@method('DELETE')
							<h5 id="delete-artist-text" class="text-center text-white ">Сигурни ли сте че искате да изтриете този изпълнител?</h5>
							<input id="delete-artist-username" type="hidden">
							<button id="delete-artist-yes" type="submit" class="btn btn-sm btn-danger">Да</button>
							<button id="delete-artist-no" type="button" class="btn btn-secondary" data-dismiss="modal">Не</button>
							<button id="delete-artist-ok" class="button-custom" hidden>ОК!</button>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="artist-content">
			@php 
			$events = ArtistController::getArtistEvents( $artist->id );
			@endphp
			@if( ! empty( $events ) )
			<h4 class="mx-4">Предстоящи изяви</h4>
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
				<h4 class="no-events">{{$artist->name}} няма предстоящи изяви за момента.</h4>
			@endif

			@if( ! $is_current_admin ) 
				@php $artists = ArtistController::otherArtists( $artist->id, $artist->genre_id ) @endphp
				@if(count($artists) > 0)
					<h4 class="mx-4">Други изпълнители</h4>
					<div class="other-artists">
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
			@endif
		</div>
	@endif
</div>
@endsection