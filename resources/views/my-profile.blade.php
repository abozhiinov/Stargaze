@php use App\Http\Controllers\ArtistController; @endphp
@php use App\Http\Controllers\PlaceController; @endphp

@extends('layouts.app')

@section('content')
<div class="container">
    @php $user = auth()->user(); @endphp
    <div id='my-profile_personal-info'>
        <h2> {{$user->name}} </h2>
        <h4> {{ __('Администратор') }} </h4>
    </div>
    @php $artists = ArtistController::getAdminArtists( $user->id ); @endphp
    <div id='my-profile_my-artists' class="my-artists">
        <div id='my-profile_my-artists_title' class='d-flex justify-content-between'>
            <h3> {{_('Моите изпълнители')}} </h3>
            <button type="button" class="button-custom button-add-new-artist" data-toggle="modal" data-target="#addArtistModal"> {{_('+ Добави нов изпълнител')}} </button>
        </div>
        @if ( count($artists) > 0 )
        <div class="my-profile-dashboard">
        @foreach ( $artists as $artist ) 
            <div class="artist-box">
                <a href="/artist/{{$artist->username}}">
                    <img src="{{ url('images/' . $artist->profile_picture) }}" class="artist-thumbnail">
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
        </div>
        @else
        <h4>Няма налични изпълнители.</h4>
        @endif
    </div>

    @php $places = PlaceController::getAdminPlaces( $user->id ); @endphp
    <div id='my-profile_my-places' class="my-places">
        <div id='my-profile_my-places_title' class='d-flex justify-content-between'>
            <h3> {{_('Моите заведения')}} </h3>
            <button type="button" class='button-custom button-add-new-place' data-toggle="modal" data-target="#addPlaceModal"> {{_('+ Добави ново заведение')}} </button>
        </div>
        @if ( count($places) > 0 )
        <div class="my-profile-dashboard">
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
        </div>
        @else
        <h4 class="text-center p-4">Няма налични заведения.</h4>
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
                <form id="form-new-artist" onsubmit="return false">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="admin-id" id="admin-id" value="{{ $user->id }}">
                    <div class="form-group">
                    <input type="text" class="form-control" id="new-artist-name" placeholder="Име на изпълнителя*">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="new-artist-username" placeholder="Потребителско име на изпълнителя*">
                    </div>
                    <div class="form-group">
                        @php $genres = ArtistController::getAllGenres(); @endphp
                        <select class="form-control" id="new-artist-genre">
                            <option class="genre-option" value="" disabled selected>Жанр*</option>
                            @foreach ( $genres as $genre )
                            <option class="genre-option" value="{{$genre->id}}">{{$genre->name}}</option>
                            @endforeach
                        </select>
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
                        <input type="file" class="form-control" id="new-artist-profile-pic">
                    </div>
                    <div class="input-group custom-file-button">
                        <label class="input-group-text" for="new-artist-cover-pic">Корица</label>
                        <input type="file" class="form-control" id="new-artist-cover-pic">
                    </div>
                    <button type="submit" class="button-custom" id="submit-new-artist">Добави</button>
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
                    <div class="form-group">
                        <input type="text" class="form-control" id="new-place-name" placeholder="Име на заведението*">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="new-place-username" placeholder="Потребителско име на заведението*">
                    </div>
                    <div class="form-group">
                        @php $genres = ArtistController::getAllGenres(); @endphp
                        <select class="form-control" id="new-place-genre">
                            <option class="genre-option" value="all-genres" selected>Жанр*</option>
                            @foreach ( $genres as $genre )
                            <option class="genre-option" value="{{$genre->id}}">{{$genre->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex justify-content-between gap-4">
                        <select class="form-control" id="new-place-opening-hour">
                            <option class="opening-option" value="" selected>Отваря в</option>
                            @for ( $i = 0; $i < 24; $i++ )
                            <option class="opening-option" value="{{$i}}">@if( $i < 10 ){{'0'}}@endif{{$i.':00'}}</option>
                            <option class="opening-option" value="{{$i.':30'}}">@if( $i < 10 ){{'0'}}@endif{{$i.':30'}}</option>
                            @endfor
                        </select>
                        <select class="form-control" id="new-place-closing-hour">
                            <option class="closing-option" value="" selected>Затваря в</option>
                            @for ( $i = 0; $i < 24; $i++ )
                            <option class="closing-option" value="{{$i}}">@if( $i < 10 ){{'0'}}@endif{{$i.':00'}}</option>
                            <option class="closing-option" value="{{$i.':30'}}">@if( $i < 10 ){{'0'}}@endif{{$i.':30'}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        @php $locations = PlaceController::getAllLocations(); @endphp
                        <select class="form-control" id="new-place-location">
                            <option class="location-option" value="all-locations" selected>Локация</option>
                            @foreach ( $locations as $location )
                            <option class="loacation-option" value="{{$location->id}}">{{$location->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="url" class="form-control" id="new-place-facebook" placeholder="Facebook">
                    </div>
                    <div class="form-group">
                        <input type="url" class="form-control" id="new-place-instagram" placeholder="Instagram">
                    </div>
                    <div class="form-group">
                        <input type="url" class="form-control" id="new-place-youtube" placeholder="YouTube">
                    </div>
                    <div class="input-group custom-file-button">
                        <label class="input-group-text" for="new-place-profile-pic">Профилна снимка*</label>
                        <input type="file" class="form-control" id="new-place-profile-pic">
                    </div>
                    <div class="input-group custom-file-button">
                        <label class="input-group-text" for="new-place-profile-pic">Корица</label>
                        <input type="file" class="form-control" id="new-place-cover-pic">
                    </div>
                    <button type="submit" class="button-custom">Добави</button>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
