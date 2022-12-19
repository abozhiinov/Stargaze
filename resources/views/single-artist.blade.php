@php use App\Http\Controllers\ArtistController; @endphp

@extends('layouts.app')

@section('content')
<div class="container">
	@php 
	$artist = ArtistController::getArtistData( $username )[0];
	$artist_genre = ArtistController::getArtistGenre( $artist->genre_id );
	@endphp
	@if($artist)
		<div class="back">
			<a href="{{ url('/artists') }}">< Назад</a>
		</div>
		<div class="artist-container">
			@if(!empty($artist->cover_picture))
			<img src="{{ url('images/' . $artist->cover_picture) }}" class="single-artist-thumbnail">
			@else
			<div class="single-artist-no-cover">
			@endif
			<div class="single-artist-info"> 
				<div class="single-artist-name">
					{{$artist->name}}
					@if($artist->verified == 1)
					<img class="single-artist-verified" src="{{url('/images/verified.svg')}}">
					@endif
				</div>
				@if(!empty($artist_genre))
				<p class="single-artist-genre">{{$artist_genre->genre}}</p>
				@endif
			</div>
			<a href="{{ url($artist->facebook) }}">
				<img class="single-artist-social facebook" src="{{url('/images/facebook.svg')}}">
			</a>
			<a href="{{ url($artist->instagram) }}">
				<img class="single-artist-social instagram" src="{{url('/images/instagram.svg')}}">
			</a>
			<a href="{{ url($artist->youtube) }}">
				<img class="single-artist-social youtube" src="{{url('/images/youtube.svg')}}">
			</a>
		</div>
		@if(empty($artist->cover_picture))
		</div>
		@endif

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
			<h5 class="no-events">{{$artist->name}} няма предстоящи изяви за момента.</h5>
		@endif

		@php $artists = ArtistController::otherArtists( $artist->id, $artist->genre_id ) @endphp
		@if(count($artists) > 0)
		<h4 class="mx-4">Други изпълнители</h4>
		<div class="other-artists">
			@foreach($artists as $artist)
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
		@endif
		</div>
	@endif
</div>
@endsection