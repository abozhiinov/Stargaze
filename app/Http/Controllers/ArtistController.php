<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;

class ArtistController extends Controller {
	public function allArtists() {
		return Artist::all();
	}

	public function getArtist( $username ) {
		return view('single-artist', [ 'username' => $username ] );
	}
}
