@php use App\Http\Controllers\ArtistController; @endphp
@php use App\Http\Controllers\PlaceController; @endphp
@php use App\Http\Controllers\EventController; @endphp

@php use Carbon\Carbon; @endphp

@extends('layouts.app')

@section('content')
<div class="container">
    @php $user = auth()->user(); @endphp
	@php 
		$artist_controller = new ArtistController(); 
		$place_controller = new PlaceController();
		$event_controller = new EventController();
	@endphp
    <div id='my-profile_personal-info' class='my-profile-top'>
        <div class="user-info">
            <h2> {{$user->name}} </h2>
            <h4> {{ __('Мениджър') }} </h4>
        </div>
        <button class='button-create-event button-custom'>Създай ново събитие</button>
    </div>
    @php $artists = $artist_controller->getAdminArtists( $user->id ); @endphp
    <div id='my-profile_my-artists' class="my-artists">
        <div id='my-profile_my-artists_title' class='d-flex justify-content-between'>
            <h3> {{_('Моите изпълнители')}} </h3>
            <button type="button" class="button-custom button-add-new-artist" data-toggle="modal" data-target="#addArtistModal"> {{_('+ Добави нов изпълнител')}} </button>
        </div>
        @if ( count($artists) > 0 )
        <div class="my-profile-dashboard">
        @foreach ( $artists as $artist ) 
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

        {{-- Create Event From Scratch Modal --}}
		<div class="modal modal-danger fade" id="createNewEventModal" tabindex="-1" role="dialog" aria-labelledby="Delete" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<div class=""></div>
						<h5 class="modal-title" id="createNewEventModalLabel">Създаване на събитие</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form method="" action="" id="form-create-new-event" onsubmit="return false">
							<input type="hidden" name="_token" id="token" value="<?php echo csrf_token(); ?>">
							<input type="hidden" name="data" id="create-new-event-data">
							<div class="form-group">
								<input type="text" class="form-control edit" id="create-new-event-title" placeholder="Заглавие на събитието">
								<span hidden id="error-new-event-title" class="new-event-error">Полето за заглавие на събитието е задължително.</span>
							</div>
                            <div class="form-group">
								<input type="text" onfocus="(this.type='date')" class="form-control edit" id="create-new-event-date" placeholder="Дата на събитието">
								<span hidden id="error-new-event-date" class="new-event-error">Полето за дата на събитието е задължително.</span>
							</div>
							<div class="input-group custom-file-button">
								<label class="input-group-text" for="create-new-event-poster">Постер на събитието</label>
								<input type="file" class="form-control edit" id="create-new-event-poster">
								<span hidden id="error-new-event-poster" class="new-event-error">Полето за постер на събитието е задължително.</span>
							</div>
							<button type="submit" class="button-submit button-custom" id="submit-create-new-event">Създай</button>
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

        @php 
        $pending_invitations     = $artist_controller->getManagerPendingInvitations( $user->id );
		$approved_invitations    = $artist_controller->getManagerApprovedInvitations( $user->id );
		$disapproved_invitations = $artist_controller->getManagerDisapprovedInvitations( $user->id ); 
        @endphp

        @if ( count( $pending_invitations ) || count( $approved_invitations ) || count( $disapproved_invitations ) )
		<h3 class="invitations text-center mt-5">Получени Покани</h3>
        @endif
		<?php if ( count( $pending_invitations ) ) : ?>
			<h4>Активни</h4>
			<div class='invitation-dashboard pending'>
			<?php
			foreach ( $pending_invitations as $single_invitation ) :
                $artist   = $artist_controller->getArtistDataById( $single_invitation->artist_id )[0];
				$place    = $place_controller->getPlaceDataById( $single_invitation->place_id )[0];
				$location = $place_controller->getSingleLocation( $place->location_id )[0]->name;
				$date     = Carbon::createFromFormat( 'Y-m-d', $single_invitation->date )->format( 'd M Y' );
				$time     = Carbon::createFromFormat( 'H:i:s', $single_invitation->start_hour )->format( 'h:i' ) . ' - ' . Carbon::createFromFormat( 'H:i:s', $single_invitation->end_hour )->format( 'h:i' );
				?>
				<div class='invitation-single' id="inv-<?php echo $single_invitation->id; ?>">
					<div class='invitation-single-content'>
						<img class='invitation-single-place-thumbnail' src='/images/cover-pictures/<?php echo $artist->cover_picture; ?>'>
						<p class='invitation-single-title'>
                            {{'Покана до '}}
                            <a class='invitation-single-title-link' href="/artist/<?php echo $artist->username; ?>">{{$artist->name}}</a>
                            {{' от '}}
                            <a class='invitation-single-title-link' href="/place/<?php echo $place->username; ?>">{{$place->name . ', ' . $location}}</a> 
                        </p>
						<p class='invitation-single-info'><?php echo $date . ', ' . $time; ?></p>
						<p id='message' class='invitation-single-message'><?php echo $single_invitation->message; ?></p>
						<?php if ( ! empty( $single_invitation->message ) ) : ?>
							<button class='invitation-single-see-more'>Виж повече ▼ </button>
							<button class='invitation-single-see-less'>Виж по-малко ▲</button>
						<?php endif; ?>
						<div class='invitation-buttons' data-id=<?php echo $single_invitation->id; ?>>
							<button type="button" class='invitation-status' data-status=1>Приеми</button>
							<button type="button" class='invitation-status' data-status=-1>Отхвърли</button>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
			</div>
		<?php
		endif;

		if ( count( $approved_invitations ) ) : ?>
			<h4>Одобрени</h4>
			<div class='invitation-dashboard approved'>
			<?php
			foreach ( $approved_invitations as $single_invitation ) :
                $artist   = $artist_controller->getArtistDataById( $single_invitation->artist_id )[0];
				$place    = $place_controller->getPlaceDataById( $single_invitation->place_id )[0];
				$location = $place_controller->getSingleLocation( $place->location_id )[0]->name;
				$date     = Carbon::createFromFormat( 'Y-m-d', $single_invitation->date )->format( 'd M Y' );
				$time     = Carbon::createFromFormat( 'H:i:s', $single_invitation->start_hour )->format( 'h:i' ) . ' - ' . Carbon::createFromFormat( 'H:i:s', $single_invitation->end_hour )->format( 'h:i' );
				?>
				<div class='invitation-single' id="inv-<?php echo $single_invitation->id; ?>">
					<div class='invitation-single-content'>
						<img class='invitation-single-place-thumbnail' src='/images/cover-pictures/<?php echo $artist->cover_picture; ?>'>
						<p class='invitation-single-title'>
                            {{'Покана до '}}
                            <a class='invitation-single-title-link' href="/artist/<?php echo $artist->username; ?>">{{$artist->name}}</a>
                            {{' от '}}
                            <a class='invitation-single-title-link' href="/place/<?php echo $place->username; ?>">{{$place->name . ', ' . $location}}</a> 
                        </p>
                        <p class='invitation-single-info'><?php echo $date . ', ' . $time; ?></p>
						<p id='message' class='invitation-single-message'><?php echo $single_invitation->message; ?></p>
						<div class='invitation-buttons' data-event-id=<?php echo $single_invitation->id; ?>>
							<button class='invitation-single-create-event' data-date="<?php echo $single_invitation->date; ?>"  data-artist="<?php echo $artist->id; ?>" data-place="<?php echo $place->id; ?>" data-invitation=<?php echo $single_invitation->id; ?>>Създай събитие</button>
						</div>
						<?php if ( ! empty( $single_invitation->message ) ) : ?>
							<button class='invitation-single-see-more'>Виж повече ▼ </button>
							<button class='invitation-single-see-less'>Виж по-малко ▲</button>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
			</div>
		<?php
		endif;

		if ( count( $disapproved_invitations ) ) : ?>
			<h4>Отхвърлени</h4>
			<div class='invitation-dashboard disapproved'>
			<?php
			foreach ( $disapproved_invitations as $single_invitation ) :
                $artist   = $artist_controller->getArtistDataById( $single_invitation->artist_id )[0];
				$place    = $place_controller->getPlaceDataById( $single_invitation->place_id )[0];
				$location = $place_controller->getSingleLocation( $place->location_id )[0]->name;
				$date     = Carbon::createFromFormat( 'Y-m-d', $single_invitation->date )->format( 'd M Y' );
				$time     = Carbon::createFromFormat( 'H:i:s', $single_invitation->start_hour )->format( 'h:i' ) . ' - ' . Carbon::createFromFormat( 'H:i:s', $single_invitation->end_hour )->format( 'h:i' );
				?>
				<div class='invitation-single' id="inv-<?php echo $single_invitation->id; ?>">
					<div class='invitation-single-content'>
						<img class='invitation-single-place-thumbnail' src='images/cover-pictures/<?php echo $artist->cover_picture; ?>'>
						<p class='invitation-single-title'>
                            {{'Покана до '}}
                            <a class='invitation-single-title-link' href="/artist/<?php echo $artist->username; ?>">{{$artist->name}}</a>
                            {{' от '}}
                            <a class='invitation-single-title-link' href="/place/<?php echo $place->username; ?>">{{$place->name . ', ' . $location}}</a> 
                        </p>
                        <p class='invitation-single-info'><?php echo $date . ', ' . $time; ?></p>
						<p id='message' class='invitation-single-message'><?php echo $single_invitation->message; ?></p>
						<div class='invitation-buttons' data-delete-id=<?php echo $single_invitation->id; ?>>
							<button class='invitation-single-delete'>Изтрий</button>
						</div>
						<?php if ( ! empty( $single_invitation->message ) ) : ?>
							<button class='invitation-single-see-more'>Виж повече ▼ </button>
							<button class='invitation-single-see-less'>Виж по-малко ▲</button>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
			</div>
		<?php endif; ?>
        @else
        <h4 class="p-4">Няма налични изпълнители.</h4>
        @endif
    </div>

    @php $places = $place_controller->getAdminPlaces( $user->id ); @endphp
    <div id='my-profile_my-places' class="my-places">
        <div id='my-profile_my-places_title' class='d-flex justify-content-between'>
            <h3> {{_('Моите заведения')}} </h3>
            <button type="button" class='button-custom button-add-new-place' data-toggle="modal" data-target="#addPlaceModal"> {{_('+ Добави ново заведение')}} </button>
        </div>
        @if ( count($places) > 0 )
        <div class="my-profile-dashboard">
            @foreach($places as $place)
				@php $location = $place_controller->getSingleLocation( $place->location_id )[0]; @endphp
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
        </div>


        @php 
        $pending_invitations     = $place_controller->getManagerPendingInvitations( $user->id );
		$approved_invitations    = $place_controller->getManagerApprovedInvitations( $user->id );
		$disapproved_invitations = $place_controller->getManagerDisapprovedInvitations( $user->id ); 
        @endphp
		<h3 class="invitations text-center my-4">Изпратени Покани</h3>
		<?php if ( count( $pending_invitations ) ) : ?>
			<h4>Активни</h4>
			<div class='invitation-dashboard pending'>
			<?php
			foreach ( $pending_invitations as $single_invitation ) :
				$artist = $artist_controller->getArtistDataById( $single_invitation->artist_id )[0];
                $place  = $place_controller->getPlaceDataById( $single_invitation->place_id )[0];
				$date   = Carbon::createFromFormat( 'Y-m-d', $single_invitation->date )->format( 'd M Y' );
				$time   = Carbon::createFromFormat( 'H:i:s', $single_invitation->start_hour )->format( 'H:i' ) . ' - ' . Carbon::createFromFormat( 'H:i:s', $single_invitation->end_hour )->format( 'H:i' );
				?>
				<div class='invitation-single' id="inv-<?php echo $single_invitation->id; ?>">
					<div class='invitation-single-content'>
						<img class='invitation-single-place-thumbnail' src='/images/cover-pictures/<?php echo $artist->cover_picture; ?>'>
						<p class='invitation-single-title'>
                            {{'Покана до '}}
                            <a class='invitation-single-title-link' href="/artist/<?php echo $artist->username; ?>">{{$artist->name}}</a>
                            {{' от '}}
                            <a class='invitation-single-title-link' href="/place/<?php echo $place->username; ?>">{{$place->name}}</a> 
                        </p>
						<p class='invitation-single-info'><?php echo $date . ', ' . $time; ?></p>
						<p id='message' class='invitation-single-message'><?php echo $single_invitation->message; ?></p>
						<?php if ( ! empty( $single_invitation->message ) ) : ?>
							<button class='invitation-single-see-more'>Виж повече ▼ </button>
							<button class='invitation-single-see-less'>Виж по-малко ▲</button>
						<?php endif; ?>
						<div class='invitation-buttons' data-delete-id=<?php echo $single_invitation->id; ?>>
							<button type="button" class='invitation-single-delete'>Изтрий покана</button>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
			</div>
		<?php
		endif;

		if ( count( $approved_invitations ) ) : ?>
			<h4>Одобрени</h4>
			<div class='invitation-dashboard approved'>
			<?php
			foreach ( $approved_invitations as $single_invitation ) :
				$artist = $artist_controller->getArtistDataById( $single_invitation->artist_id )[0];
                $place  = $place_controller->getPlaceDataById( $single_invitation->place_id )[0];
				$date   = Carbon::createFromFormat( 'Y-m-d', $single_invitation->date )->format( 'd M Y' );
				$time   = Carbon::createFromFormat( 'H:i:s', $single_invitation->start_hour )->format( 'H:i' ) . ' - ' . Carbon::createFromFormat( 'H:i:s', $single_invitation->end_hour )->format( 'H:i' );
				?>
				<div class='invitation-single' id="inv-<?php echo $single_invitation->id; ?>">
					<div class='invitation-single-content'>
						<img class='invitation-single-place-thumbnail' src='/images/cover-pictures/<?php echo $artist->cover_picture; ?>'>
						<p class='invitation-single-title'>
                            {{'Покана до '}}
                            <a class='invitation-single-title-link' href="/artist/<?php echo $artist->username; ?>">{{$artist->name}}</a>
                            {{' от '}}
                            <a class='invitation-single-title-link' href="/place/<?php echo $place->username; ?>">{{$place->name}}</a> 
                        </p>
						<p class='invitation-single-info'><?php echo $date . ', ' . $time; ?></p>
						<p id='message' class='invitation-single-message'><?php echo $single_invitation->message; ?></p>
						<?php if ( ! empty( $single_invitation->message ) ) : ?>
							<button class='invitation-single-see-more'>Виж повече ▼ </button>
							<button class='invitation-single-see-less'>Виж по-малко ▲</button>
						<?php endif; ?>
						<div class='invitation-buttons' data-event-id=<?php echo $single_invitation->id; ?>>
							<button class='invitation-single-create-event' data-date="<?php echo $single_invitation->date; ?>"  data-artist="<?php echo $artist->id; ?>" data-place="<?php echo $place->id; ?>" data-invitation=<?php echo $single_invitation->id; ?>>Създай събитие</button>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
			</div>
		<?php
		endif;

		if ( count( $disapproved_invitations ) ) : ?>
			<h4>Отхвърлени</h4>
			<div class='invitation-dashboard disapproved'>
			<?php
			foreach ( $disapproved_invitations as $single_invitation ) :
				$artist = $artist_controller->getArtistDataById( $single_invitation->artist_id )[0];
                $place  = $place_controller->getPlaceDataById( $single_invitation->place_id )[0];
				$date   = Carbon::createFromFormat( 'Y-m-d', $single_invitation->date )->format( 'd M Y' );
				$time   = Carbon::createFromFormat( 'H:i:s', $single_invitation->start_hour )->format( 'H:i' ) . ' - ' . Carbon::createFromFormat( 'H:i:s', $single_invitation->end_hour )->format( 'H:i' );
				?>
				<div class='invitation-single' id="inv-<?php echo $single_invitation->id; ?>">
					<div class='invitation-single-content'>
						<img class='invitation-single-place-thumbnail' src='/images/cover-pictures/<?php echo $artist->cover_picture; ?>'>
						<p class='invitation-single-title'>
                            {{'Покана до '}}
                            <a class='invitation-single-title-link' href="/artist/<?php echo $artist->username; ?>">{{$artist->name}}</a>
                            {{' от '}}
                            <a class='invitation-single-title-link' href="/place/<?php echo $place->username; ?>">{{$place->name}}</a> 
                        </p>
						<p class='invitation-single-info'><?php echo $date . ', ' . $time; ?></p>
						<p id='message' class='invitation-single-message'><?php echo $single_invitation->message; ?></p>
						<?php if ( ! empty( $single_invitation->message ) ) : ?>
							<button class='invitation-single-see-more'>Виж повече ▼ </button>
							<button class='invitation-single-see-less'>Виж по-малко ▲</button>
						<?php endif; ?>
						<div class='invitation-buttons' data-delete-id=<?php echo $single_invitation->id; ?>>
							<button class='invitation-single-delete'>Изтрий</button>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
			</div>
		<?php endif; ?>

        @else
        <h4 class="p-4">Няма налични заведения.</h4>
        @endif
    </div>
	  
    {{-- https://codepen.io/glebkema/pen/VwMQWGE choose file styles --}}
	<!-- Artist Modal -->
    <div class="modal fade" id="addArtistModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <div></div>
                <h5 class="modal-title" id="exampleModalLabel">Добави нов изпълнител</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-new-artist" onsubmit="return false" enctype="multipart/form-data">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="admin-id" id="admin-id" value="{{ $user->id }}">
                    <div class="form-group">
                    	<input type="text" class="form-control" id="new-artist-name" placeholder="Име на изпълнителя*">
						<span hidden id="error-new-artist-name" class="new-artist-error">Полето за име на изпълнител е задължително.</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="new-artist-username" placeholder="Потребителско име на изпълнителя*">
						<span hidden id="error-new-artist-username" class="new-artist-error">Полето за потребителско име на изпълнител е задължително.</span>
                    </div>
                    <div class="form-group">
                        @php $genres = $artist_controller->getAllGenres(); @endphp
                        <select class="form-control select" id="new-artist-genre">
                            <option class="genre-option" value="" disabled selected>Жанр*</option>
                            @foreach ( $genres as $genre )
                            <option class="genre-option" value="{{$genre->id}}">{{$genre->name}}</option>
                            @endforeach
                        </select>
						<span hidden id="error-new-artist-genre" class="new-artist-error">Полето за жанр на изпълнител е задължително.</span>
                    </div>
                    <div class="form-group">
                    <input type="url" class="form-control" id="new-artist-facebook" placeholder="Facebook">
                    </div>
                    <div class="form-group">
                    <input type="url" class="form-control" id="new-artist-instagram" placeholder="Instagram">
                    </div>
                    <div class="form-group">
                    <input type="url" class="form-control" id="new-artist-youtube" placeholder="YouTube">
                    </div>
                    <div class="input-group custom-file-button">
                        <label class="input-group-text" for="new-artist-profile-pic">Профилна снимка*</label>
                        <input type="file" class="form-control" id="new-artist-profile-pic" name="file">
						<span hidden id="error-new-artist-profile-pic" class="new-artist-error">Полето за профилна снимка на изпълнител е задължително.</span>
                    </div>
                    <div class="input-group custom-file-button">
                        <label class="input-group-text" for="new-artist-cover-pic">Корица</label>
                        <input type="file" class="form-control" id="new-artist-cover-pic">
                    </div>
                    <button type="submit" class="button-custom button-submit" id="submit-new-artist">Добави</button>
                </form>
            </div>
            </div>
        </div>
    </div>

    <!-- Place Modal -->
    <div class="modal fade" id="addPlaceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <div></div>
                <h5 class="modal-title" id="exampleModalLabel">Добави ново заведение</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-new-place" onsubmit="return false">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="admin-id" id="admin-id" value="{{ $user->id }}">
                    <div class="form-group">
                        <input type="text" class="form-control" id="new-place-name" placeholder="Име на заведението*">
						<span hidden id="error-new-place-name" class="new-place-error">Полето за име на заведението е задължително.</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="new-place-username" placeholder="Потребителско име на заведението*">
						<span hidden id="error-new-place-username" class="new-place-error">Полето за потребителско име на заведението е задължително.</span>
                    </div>
                    <div class="form-group">
                        @php $genres = $artist_controller->getAllGenres(); @endphp
                        <select class="form-control select" id="new-place-genre">
                            <option class="genre-option" value="all-genres" selected>Жанр*</option>
                            @foreach ( $genres as $genre )
                            <option class="genre-option" value="{{$genre->id}}">{{$genre->name}}</option>
                            @endforeach
                        </select>
						<span hidden id="error-new-place-genre" class="new-place-error">Полето за жанр на заведението е задължително.</span>
                    </div>
                    <div class="d-flex justify-content-between gap-3">
                        <select class="form-control select" id="new-place-opening-hour">
                            <option class="opening-option" value="" selected>Отваря в</option>
                            @for ( $i = 0; $i < 24; $i++ )
                            <option class="opening-option" value="{{$i}}">@if( $i < 10 ){{'0'}}@endif{{$i.':00'}}</option>
                            <option class="opening-option" value="{{$i.':30'}}">@if( $i < 10 ){{'0'}}@endif{{$i.':30'}}</option>
                            @endfor
                        </select>
                        <select class="form-control select" id="new-place-closing-hour">
                            <option class="closing-option" value="" selected>Затваря в</option>
                            @for ( $i = 0; $i < 24; $i++ )
                            <option class="closing-option" value="{{$i}}">@if( $i < 10 ){{'0'}}@endif{{$i.':00'}}</option>
                            <option class="closing-option" value="{{$i.':30'}}">@if( $i < 10 ){{'0'}}@endif{{$i.':30'}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        @php $locations = $place_controller->getAllLocations(); @endphp
                        <select class="form-control select" id="new-place-location">
                            <option class="location-option" value="all-locations" selected>Локация*</option>
                            @foreach ( $locations as $location )
                            <option class="loacation-option" value="{{$location->id}}">{{$location->name}}</option>
                            @endforeach
                        </select>
						<span hidden id="error-new-place-location" class="new-place-error">Полето за локация на заведението е задължително.</span>
                    </div>
                    <div class="form-group">
                        <input type="url" class="form-control" id="new-place-facebook" placeholder="Facebook">
                    </div>
                    <div class="form-group">
                        <input type="url" class="form-control" id="new-place-instagram" placeholder="Instagram">
                    </div>
                    <div class="input-group custom-file-button">
                        <label class="input-group-text" for="new-place-profile-pic">Профилна снимка*</label>
                        <input type="file" class="form-control" id="new-place-profile-pic">
						<span hidden id="error-new-place-profile-pic" class="new-place-error">Полето за профилна снимка на заведението е задължително.</span>
                    </div>
                    <div class="input-group custom-file-button">
                        <label class="input-group-text" for="new-place-profile-pic">Корица</label>
                        <input type="file" class="form-control" id="new-place-cover-pic">
                    </div>
                    <button type="submit" class="button-custom button-submit">Добави</button>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
