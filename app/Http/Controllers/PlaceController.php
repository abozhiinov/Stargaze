<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Place;
use Carbon\Carbon;

class PlaceController extends Controller
{
    // Get all artists.
	public static function allPlaces() {
		return Place::all();
	}

	// Get the single view for an artist
	public static function getPlaceView( $username ) {
		return view('single-place', [ 'username' => $username ] );
	}

	// Get artist's data
	public static function getPlaceData( $username ) {
		return Place::where( 'username', $username )->get();
	}

	// Get artist's data
	public static function getPlaceDataById( $id ) {
		return Place::where( 'id', $id )->get();
	}

	// Get artist's data
	public static function getPlaceGenre( $genre_id ) {
		return DB::table( 'genres' )->find( $genre_id );
	}

	public static function getPlaceEvents( $id ) {
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
	public static function otherPlaces( $place_id, $genre_id ) {
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
	public static function getAdminPlaces( $admin_id ) {
		return Place::where( 'admin_id', $admin_id )->get();
	}

	public static function getSingleLocation( $id ) {
		return DB::table( 'locations' )->where( 'id', $id )->get();
	}

	public static function getAllLocations() {
		return DB::table( 'locations' )->orderBy( 'name', 'ASC' )->get();
	}

	public static function getPopularPlaces() {
		return DB::table( 'places' )->orderBy( 'likes', 'DESC' )->limit( 3 )->get();
	}

	public static function getPlacePendingInvitations( $id ) {
		return DB::table( 'invitations' )
		->where([
			[ 'place_id', '=', $id ],
			[ 'status', '=', 0 ],
		])->get();
	}

	public static function getPlaceApprovedInvitations( $id ) {
		return DB::table( 'invitations' )
		->where([
			[ 'place_id', '=', $id ],
			[ 'status', '=', 1 ],
		])->get();
	}

	public static function getPlaceDisapprovedInvitations( $id ) {
		return DB::table( 'invitations' )
		->where([
			[ 'place_id', '=', $id ],
			[ 'status', '=', -1 ],
		])->get();
	}

	public static function getManagerPendingInvitations( $manager_id ) {
		$places = Place::where( 'admin_id', $manager_id )->get( 'id' );
		return DB::table( 'invitations' )
		->whereIn( 'place_id', $places )
		->where( 'status', '=', 0 )->get();
	}

	public static function getManagerApprovedInvitations( $manager_id ) {
		$places = Place::where( 'admin_id', $manager_id )->get( 'id' );
		return DB::table( 'invitations' )
		->whereIn( 'place_id', $places )
		->where( 'status', '=', 1 )->get();
	}

	public static function getManagerDisapprovedInvitations( $manager_id ) {
		$places = Place::where( 'admin_id', $manager_id )->get( 'id' );
		return DB::table( 'invitations' )
		->whereIn( 'place_id', $places )
		->where( 'status', '=', -1 )->get();
	}
}
