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
    @if ( count($artists) > 0 )
    <div id='my-profile_my-artists' class="my-artists">
        <div id='my-profile_my-artists_title' class='d-flex justify-content-between'>
            <h3> {{_('Моите изпълнители')}} </h3>
            <button class="button-custom button-add-new"> {{_('+ Добави нов изпълнител')}} </button>
        </div>
        <div class="my-profile-dashboard">
        @foreach ( $artists as $artist ) 
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
        </div>
    </div>
    @endif

    @php $places = PlaceController::getAdminPlaces( $user->id ); @endphp
    @if ( count($places) > 0 )
    <div id='my-profile_my-places' class="my-places">
        <div id='my-profile_my-places_title' class='d-flex justify-content-between'>
            <h3> {{_('Моите заведения')}} </h3>
            <button class='button-custom button-add-new'> {{_('+ Добави ново заведение')}} </button>
        </div>
        <div class="my-profile-dashboard">
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
        </div>
    </div>
    @endif
</div>
@endsection