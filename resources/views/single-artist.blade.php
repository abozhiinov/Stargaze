@php 
	use App\Http\Controllers\ArtistController; 
	use App\Http\Controllers\PlaceController; 
@endphp

@extends('layouts.app')

@section('content')
<div class="container">
	@php 
	$artist_controller = new ArtistController();
	$place_controller = new PlaceController();
	$artist = $artist_controller->getArtistData( $username )[0];
	$artist_genre = $artist_controller->getArtistGenre( $artist->genre_id );
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
				@php $has_invitations = $artist_controller->hasInvitations( $artist->id ); @endphp
				@if ( $has_invitations )
				<button class="artist-see-invitations" data-username={{$username}}>{{_('Виж покани')}}</button>
				@endif
				<button class="artist-edit" data-username={{$username}}>{{_('Редактирай изпълнител')}}</button>
				<button class="artist-delete">{{_('Изтрий изпълнител')}}</button>
			</div>
			@else
				@php $manager_places = $place_controller->getAdminPlaces( auth()->user()->id ); @endphp
				@if ( count($manager_places) )
				<div class="admin-panel d-flex justify-content-around">
					<button class="artist-invite">{{_('Покани изпълнител')}}</button>
				</div>
				@endif
			@endif
		@endauth

		<div class="artist-container">
			@if( $artist->cover_picture )
			<img src="{{ url('images/cover-pictures/' . $artist->cover_picture) }}" class="single-artist-thumbnail">
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
							<button id="delete-artist-yes" type="submit" class="button-submit button-custom">Да</button>
							<button id="delete-artist-no" type="button" class="button-submit button-custom" data-dismiss="modal">Не</button>
							<button id="delete-artist-ok" class="button-custom" hidden>ОК!</button>
						</form>
					</div>
				</div>
			</div>
		</div>

		{{-- Create Event Modal --}}
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
								<span hidden id="error-event-title" class="event-error">Полето за заглавие на събитието е задължително.</span>
							</div>
							<div class="input-group custom-file-button">
								<label class="input-group-text" for="create-event-poster">Постер на събитието</label>
								<input type="file" class="form-control edit" id="create-event-poster">
								<span hidden id="error-event-poster" class="event-error">Полето за постер на събитието е задължително.</span>
							</div>
							<button type="submit" class="button-submit button-custom" id="submit-create-event">Създай</button>
						</form>
					</div>
				</div>
			</div>
		</div>

		{{-- Event success message --}}
		<div class="modal modal-danger fade" id="successfulEventModal" tabindex="-1" role="dialog" aria-labelledby="Delete" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<h5 id="successful-event-text" class="text-center text-white ">Събитието беше създадено успешно!</h5>
						<button id="successful-event-ok" class="button-custom">ОК!</button>
					</div>
				</div>
			</div>
		</div>

		{{-- Invitation success message --}}
		<div class="modal modal-danger fade" id="successfulInvitationModal" tabindex="-1" role="dialog" aria-labelledby="Delete" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<h5 id="successful-invitation-text" class="text-center text-white ">Поканата беше изпратена успешно!</h5>
						<button id="successful-invitation-ok" class="button-custom">ОК!</button>
					</div>
				</div>
			</div>
		</div>


	<!-- Edit Artist Modal -->
	@php
		$genres = $artist_controller->getAllGenres();
		$current_genre = $artist_controller->getArtistGenre( $artist->genre_id ); 
	@endphp
	<div class="modal modal-lg modal-danger fade" id="editArtistModal" tabindex="-1" role="dialog" aria-labelledby="Delete" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div class=""></div>
					<h5 class="modal-title" id="editArtistModalLabel">Редактирай изпълнител</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form method="" action="" id="form-edit-artist" onsubmit="return false">
						<div class="row align-items-center">
							<input type="hidden" name="_token" id="token" value="<?php echo csrf_token(); ?>">
							<input type="hidden" name="id" id="id" value="<?php echo $artist->id; ?>" data-id="<?php echo $artist->id; ?>">
							<div class="form-group col-sm-6">
								<input type="text" class="form-control edit" id="edit-artist-name" value="<?php echo  $artist->name; ?>" data-name="<?php echo  $artist->name; ?>" placeholder="Име на изпълнителя">
							</div>
							<div class="form-group col-sm-6">
								<input type="text" class="form-control edit" id="edit-artist-username" value="<?php echo $artist->username; ?>" data-username="<?php echo $artist->username; ?>" placeholder="Потребителско име на изпълнителя">
							</div>
							<div class="form-group col-sm-6">
								<select class="form-control edit select" id="edit-artist-genre">
									<option class="genre-option" data-genre="<?php echo $artist->genre_id; ?>" value="<?php echo $artist->genre_id; ?>" selected><?php echo $current_genre->name; ?></option>
									<?php foreach ( $genres as $genre ) : if ( $genre->id !== $artist->genre_id ) : ?>
										<option class="genre-option" value="<?php echo $genre->id; ?>"><?php echo $genre->name; ?></option>
									<?php endif; endforeach; ?>
								</select>
							</div>
							<div class="form-group col-sm-6">
							<input type="url" class="form-control edit" id="edit-artist-facebook" value="<?php echo $artist->facebook; ?>" data-facebook="<?php echo $artist->facebook; ?>" placeholder="Facebook">
							</div>
							<div class="form-group col-sm-6">
							<input type="url" class="form-control edit" id="edit-artist-instagram" value="<?php echo $artist->instagram; ?>" placeholder="Instagram">
							</div>
							<div class="form-group col-sm-6">
							<input type="url" class="form-control edit" id="edit-artist-youtube" value="<?php echo $artist->youtube; ?>" placeholder="YouTube">
							</div>
							<div class="input-group custom-file-button col-sm-5">
								<label class="input-group-text" for="edit-artist-profile-pic">Профилна снимка</label>
								<input type="file" class="form-control edit" id="edit-artist-profile-pic" value="<?php echo $artist->profile_picture; ?>" data-profile_pic="<?php echo $artist->profile_picture; ?>">
							</div>
							<div class="input-group custom-file-button col-sm-5">
								<label class="input-group-text" for="edit-artist-cover-pic">Корица</label>
								<input type="file" class="form-control edit" id="edit-artist-cover-pic" value="<?php echo $artist->cover_picture; ?>" data-cover_pic="<?php echo $artist->cover_picture; ?>">
							</div>
							<button type="submit" class="button-submit button-custom col-sm-5" id="submit-edit-artist">Запази</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		{{-- Invite artist modal --}}
		<div class="modal modal-lg modal-danger fade" id="inviteArtistModal" tabindex="-1" role="dialog" aria-labelledby="Delete" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<div class=""></div>
						<h5 class="modal-title" id="inviteArtistModalLabel">Покани изпълнител</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form method="" action="" id="form-invite-artist" onsubmit="return false">
							<input type="hidden" name="_token" id="token" value="<?php echo csrf_token(); ?>">
							<input type="hidden" name="artist-id" id="artist-id" value="<?php echo $artist->id; ?>">
							<div class="form-group">
								<select class="form-control select" id="invite-artist-place">
									<option class="place-option" value="" selected>Място на събитието *</option>
									<?php if ( isset( $manager_places ) ) : foreach ( $manager_places as $manager_place ) : ?>
										<option class="place-option" value="<?php echo $manager_place->id; ?>"><?php echo $manager_place->name; ?></option>
									<?php endforeach; endif; ?>
								</select>
								<span hidden id="error-invite-place" class="invite-error">Полето за място на събитието е задължително.</span>
							</div>
							<div class="form-group">
								<textarea rows="5" type="textarea" class="form-control edit" id="invite-artist-message" placeholder="Съобщение"></textarea>
							</div>
							<div class="form-group">
								<input type="text" onfocus="(this.type='date')" id="invite-artist-date" class="form-control edit" placeholder="Дата на събитието *">
								<span hidden id="error-invite-date" class="invite-error">Полето за дата на събитието е задължително.</span>
							</div>
							<div class="form-group">
								<select class="form-control select" id="invite-artist-start-hour">
									<option class="start-option" value="" selected>Начален час *</option>
									@for ( $i = 0; $i < 24; $i++ )
									<option class="start-option" value="@if( $i < 10 ){{'0'}}@endif{{$i.':00'}}">@if( $i < 10 ){{'0'}}@endif{{$i.':00'}}</option>
									<option class="start-option" value="@if( $i < 10 ){{'0'}}@endif{{$i.':30'}}">@if( $i < 10 ){{'0'}}@endif{{$i.':30'}}</option>
									@endfor
								</select>
								<span hidden id="error-invite-start-hour" class="invite-error">Полето за начален час на събитието е задължително.</span>
							</div>
							<div class="form-group">
								<select class="form-control select" id="invite-artist-end-hour">
									<option class="end-option" value="" selected>Краен час *</option>
									@for ( $i = 0; $i < 24; $i++ )
									<option class="end-option" value="@if( $i < 10 ){{'0'}}@endif{{$i.':00'}}">@if( $i < 10 ){{'0'}}@endif{{$i.':00'}}</option>
									<option class="end-option" value="@if( $i < 10 ){{'0'}}@endif{{$i.':30'}}">@if( $i < 10 ){{'0'}}@endif{{$i.':30'}}</option>
									@endfor
								</select>
								<span hidden id="error-invite-end-hour" class="invite-error">Полето за краен час на събитието е задължително.</span>
							</div>
							<div class="form-group">
								<input type="number" class="form-control edit" id="invite-artist-fee" placeholder="Хонорар *">
								<span hidden id="error-invite-fee" class="invite-error">Полето за хонорар на изпълнителя е задължително.</span>
							</div>
							<button type="submit" class="button-submit button-custom" id="submit-invite-artist">Изпрати покана</button>
						</form>
						</div>
					</div>
				</div>
			</div>

		<div class="artist-content">
			@php 
			$events = $artist_controller->getArtistEvents( $artist->id );
			@endphp
			@if( ! empty( $events ) )
			<h4 class="mx-4">Предстоящи изяви</h4>

			<div class="event-swiper">
				<div class="swiper">
					<div class="swiper-wrapper">
					@foreach( $events as $event )
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
			@else
				<h4 class="no-events">{{$artist->name}} няма предстоящи изяви за момента.</h4>
			@endif

			@if( ! $is_current_admin ) 
				@php $artists = $artist_controller->otherArtists( $artist->id, $artist->genre_id ) @endphp
				@if(count($artists) > 0)
					<h4 class="mx-4">Други изпълнители</h4>
					<div class="other-artists">
						@foreach($artists as $artist)
						@php $genre = $artist_controller->getArtistGenre( $artist->genre_id ); @endphp
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
									<p class="artist-genre">
										{{$genre->name}}
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