<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
	// Get all events.
	public function allEvents() {
		$events = DB::table( 'events' )->orderBy( 'date', 'asc' )->get();
		$pass_events = array();

		foreach ( $events as $event ) :
			$date   = Carbon::createFromFormat( 'Y-m-d', $event->date );
			$place  = DB::table( 'places' )->find( $event->club_id );
			$artist = DB::table( 'artists' )->find( $event->artist_id );
			array_push(
				$pass_events,
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
		return $pass_events;
	}

	// Get the single view for an artist
	public function getEventView( $id ) {
		return view( 'single-event', [ 'id' => $id ] );
	}

	public function getEventData( $id ) {
		$event = DB::table( 'events' )->where( 'id', $id )->get()[0];
		$pass_event = array();

		$date   = Carbon::createFromFormat( 'Y-m-d', $event->date );
		$place  = DB::table( 'places' )->find( $event->club_id );
		$artist = DB::table( 'artists' )->find( $event->artist_id );
		array_push(
			$pass_event,
			array(
				'id'              => $event->id,
				'title'           => $event->title,
				'poster'          => $event->poster,
				'artist_username' => $artist->username,
				'artist_name'     => $artist->name,
				'artist_picture'  => $artist->cover_picture,
				'place_name'      => $place->name,
				'place_picture'   => $place->cover_picture,
				'event_date'      => $date->format( 'd M Y' ),
			)
		);

		return $pass_event;
	}
}
