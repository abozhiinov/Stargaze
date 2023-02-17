<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Place;
use Carbon\Carbon;

class PlaceController extends Controller
{
    // Get all places.
	public function allPlaces() {
		return Place::all();
	}

	// Get the single view for a place
	public function getPlaceView( $username ) {
		return view('single-place', [ 'username' => $username ] );
	}

	// Get place's data
	public function getPlaceData( $username ) {
		return Place::where( 'username', $username )->get();
	}

	// Get place's data by ID
	public function getPlaceDataById( $id ) {
		return Place::where( 'id', $id )->get();
	}

	// Get place's genre
	public function getPlaceGenre( $genre_id ) {
		return DB::table( 'genres' )->find( $genre_id );
	}

	// Get place's events
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

	// Get admin's places
	public function getAdminPlaces( $admin_id ) {
		return Place::where( 'admin_id', $admin_id )->get();
	}

	// Get a single location by ID
	public function getSingleLocation( $id ) {
		return DB::table( 'locations' )->where( 'id', $id )->get();
	}

	// Get all available locations
	public function getAllLocations() {
		return DB::table( 'locations' )->orderBy( 'name', 'ASC' )->get();
	}

	// Get top 3 most popular places right now
	public function getPopularPlaces() {
		return DB::table( 'places' )->orderBy( 'likes', 'DESC' )->limit( 3 )->get();
	}

	// Get place's pending invitations
	public function getPlacePendingInvitations( $id ) {
		return DB::table( 'invitations' )
		->where([
			[ 'place_id', '=', $id ],
			[ 'status', '=', 0 ],
		])->get();
	}

	// Get place's approved invitations
	public function getPlaceApprovedInvitations( $id ) {
		return DB::table( 'invitations' )
		->where([
			[ 'place_id', '=', $id ],
			[ 'status', '=', 1 ],
		])->get();
	}

	// Get place's disapproved invitations
	public function getPlaceDisapprovedInvitations( $id ) {
		return DB::table( 'invitations' )
		->where([
			[ 'place_id', '=', $id ],
			[ 'status', '=', -1 ],
		])->get();
	}

	// Get manager's places pending invitations
	public function getManagerPendingInvitations( $manager_id ) {
		$places = Place::where( 'admin_id', $manager_id )->get( 'id' );
		return DB::table( 'invitations' )
		->whereIn( 'place_id', $places )
		->where( 'status', '=', 0 )->get();
	}

	// Get manager's places approved invitations
	public function getManagerApprovedInvitations( $manager_id ) {
		$places = Place::where( 'admin_id', $manager_id )->get( 'id' );
		return DB::table( 'invitations' )
		->whereIn( 'place_id', $places )
		->where( 'status', '=', 1 )->get();
	}

	// Get manager's places disapproved invitations
	public function getManagerDisapprovedInvitations( $manager_id ) {
		$places = Place::where( 'admin_id', $manager_id )->get( 'id' );
		return DB::table( 'invitations' )
		->whereIn( 'place_id', $places )
		->where( 'status', '=', -1 )->get();
	}
}
