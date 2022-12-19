@php use App\Http\Controllers\ArtistController; @endphp

@extends('layouts.app')

@section('content')
<div class="container">
	<h2 class="dashboard-title text-center">Изпълнители</h2>
	<form>
		<div class="form-container d-flex justify-content-around p-4 mt-5">
			<input type="text" class="search-input-field" placeholder="Търси изпълнител...">
			<select class="search-input-field">
				<option value="" disabled selected>Жанрове</option>
			</select>
			<select class="search-input-field">
				<option value="" disabled selected>Сортирай</option>
			</select>
		</div>
	</form>

	@php $artists = ArtistController::allArtists() @endphp
	<div class="dashboard">
		@if(count($artists) > 0)
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
</div>
@endsection