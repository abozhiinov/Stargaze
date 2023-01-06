<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Place;
use Carbon\Carbon;

class PlaceController extends Controller
{
    // Get all artists.
	public function allPlaces() {
		return Place::all();
	}

	// Get the single view for an artist
	public function getPlaceView( $username ) {
		return view('single-place', [ 'username' => $username ] );
	}

	// Get artist's data
	public function getPlaceData( $username ) {
		return Place::where( 'username', $username )->get();
	}

	// Get artist's data
	public function getPlaceDataById( $id ) {
		return Place::where( 'id', $id )->get();
	}

	// Get artist's data
	public function getPlaceGenre( $genre_id ) {
		return DB::table( 'genres' )->find( $genre_id );
	}

	public function getPlaceEvents( $id ) {
		$get_events = DB::table( 'events' )->where( 'club_id', $id )->get();
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

	// Get other places.
	public function otherPlaces( $place_id, $genre_id ) {
		return DB::table( 'places' )
		->where([
			[ 'id', '!=', $place_id ],
			[ 'genre_id', '=', $genre_id ],
		])
		->inRandomOrder()
		->limit( 3 )
		->get();
	}

	// Get artists by admin
	public function getAdminPlaces( $admin_id ) {
		return Place::where( 'admin_id', $admin_id )->get();
	}

	public function getAllLocations() {
		return DB::table( 'locations' )->orderBy( 'name', 'ASC' )->get();
	}

	public function getPopularPlaces() {
		return DB::table( 'places' )->orderBy( 'likes', 'DESC' )->limit( 3 )->get();
	}
}
