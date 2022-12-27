<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Artist;
use Carbon\Carbon;

class ArtistController extends Controller {
	// Get all artists.
	public function allArtists() {
		return Artist::all();
		//return DB::table( 'artists' )->orderBy( 'likes', 'asc' )->get();
	}

	// Get the single view for an artist.
	public function getArtistView( $username ) {
		return view('single-artist', [ 'username' => $username ] );
	}

	// Get artist's data.
	public function getArtistData( $username ) {
		return Artist::where( 'username', $username )->get();
	}

	// Get artist's data.
	public function getArtistGenre( $genre_id ) {
		return DB::table( 'genres' )->find( $genre_id );
	}

	public function getArtistEvents( $id ) {
		$get_events = DB::table( 'events' )
			->where( 'artist_id', $id )
			->get();
		$upcoming_events = array();
		foreach ( $get_events as $event ) :
			$date   = Carbon::createFromFormat( 'Y-m-d', $event->date );
			$place  = DB::table( 'places' )->find( $event->club_id );
			$artist = DB::table( 'artists' )->find( $event->artist_id );
			array_push(
				$upcoming_events,
				array(
					'id'              => $event->id,
					'title'           => $event->title,
					'poster'          => $event->poster,
					'artist_username' => $artist->username,
					'artist_name'     => $artist->name,
					'artist_picture'  => $artist->profile_picture,
					'place_name'      => $place->name,
					'place_picture'   => $place->profile_picture,
					'event_date'      => $date->format( 'd M Y' ),
				)
			);
		endforeach;
		return $upcoming_events;
	}

	// Get other artists.
	public function otherArtists( $artist_id, $genre_id ) {
		return DB::table( 'artists' )
		->where([
			[ 'id', '!=', $artist_id ],
			[ 'genre_id', '=', $genre_id ],
		])
		->inRandomOrder()
		->limit( 3 )
		->get();
	}

	// Get admin's artists.
	public function getAdminArtists ( $admin_id ) {
		return Artist::where( 'admin_id', $admin_id )->get();
	}

	public function getAllGenres () {
		return DB::table('genres')->get();
	}
}
