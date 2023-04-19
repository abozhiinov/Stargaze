<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\FileUploadController;
use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Place;
use Carbon\Carbon;
use Psr\Http\Message\RequestInterface;
use Illuminate\Support\Facades\Mail;
use App\Mail\ArtistInvitation;

class AjaxController extends Controller {

	// $type Dashboard Filters.
	public function dashboardFilter( Request $request ) {
		$filtered_query  = DB::table( $request->type );
		$search_type = substr( $request->type, 0, -1 );

		if ( ! empty( $request->search ) && 'empty-search' !== $request->search ) {
			$filtered_query = $filtered_query->where( 'name', 'like', "%{$request->search}%" );
		}

		if ( $request->genre ) {
			if ( 'artists' === $request->type ) {
				if ( 'all-genres' !== $request->genre ) {
					$filtered_query->where( 'genre_id', $request->genre );
				}
			} elseif ( 'places' === $request->type ) {
				if ( 'all-places' !== $request->genre ) {
					$filtered_query->where( 'location_id', $request->genre );
				}
			}
		}

		if ( $request->order && 'alphabet-start' === $request->order ) {
			$filtered_query = $filtered_query->orderBy( 'name', 'ASC' );
		} elseif ( $request->order && 'alphabet-end' === $request->order ) {
			$filtered_query = $filtered_query->orderBy( 'name', 'DESC' );
		} elseif ( $request->order && 'popular' === $request->order ) {
			$filtered_query = $filtered_query->orderBy( 'likes', 'DESC' );
		} elseif ( $request->order && 'unpopular' === $request->order ) {
			$filtered_query = $filtered_query->orderBy( 'likes', 'ASC' );
		}

		$filtered_query  = $filtered_query->get();

		if ( count( $filtered_query ) > 0 ) :
			foreach ( $filtered_query as $filtered_entity ) : ?>
				<div class='<?php echo $search_type; ?>-box'>
					<a href='/<?php echo $search_type; ?>/<?php echo $filtered_entity->username; ?>'>
						<img src='images/profile-pictures/<?php echo $filtered_entity->profile_picture; ?>' class='<?php echo $search_type; ?>-thumbnail'>
						<div class='<?php echo $search_type; ?>-box-likes'>
							<img class='<?php echo $search_type; ?>-likes' src='/images/likes.svg'>
							<p class='<?php echo $search_type; ?>-likes-count'><?php echo $filtered_entity->likes; ?></p>
						</div>
						<div class='<?php echo $search_type; ?>-box-content'>
							<p class='<?php echo $search_type; ?>-title'>
								<?php echo $filtered_entity->name; ?>
								<span>
				<?php if ( $filtered_entity->verified == 1 ) { ?>
					<img class='<?php echo $search_type; ?>-verified' src='/images/verified.svg'>
				<?php } ?>
								</span>
							</p>
						</div>
					</a>
				</div>
		<?php
			endforeach;
		else :
		?>
			<h4 class="text-center">Няма намерени резултати.</h4>
		<?php
		endif;
	}

	public function eventsFilter( Request $request ) {
		$filtered_query = DB::table( 'events' )->where( 'date', '>=', date( 'Y-m-d' ) );

		if ( ! empty( $request->search_artist ) && 'empty-search' !== $request->search_artist ) {
			$artists    = DB::table( 'artists' )->where( 'name', 'like', "%{$request->search_artist}%" )->get( 'id' );
			$artists_id = array();
			foreach ( $artists as $key => $artist ) {
				$artists_id[ $key ] = $artist->id;
			}
			$filtered_query = $filtered_query->whereIn( 'artist_id', $artists_id );
		}

		if ( ! empty( $request->search_place ) && 'empty-search' !== $request->search_place ) {
			$places    = DB::table( 'places' )->where( 'name', 'like', "%{$request->search_place}%" )->get( 'id' );
			$places_id = array();
			foreach ( $places as $key => $place ) {
				$places_id[ $key ] = $place->id;
			}
			$filtered_query = $filtered_query->whereIn( 'club_id', $places_id );
		}

		if ( ! empty( $request->search_date ) ) {
			$filtered_query = $filtered_query->where( 'date', $request->search_date );
		}

		if ( ! empty( $request->location ) && 'all-locations' !== $request->location ) {
			$place_controller = new PlaceController();
			$location  = $place_controller->getSingleLocation( $request->location )[0];
			if ( $location ) :
				$places    = DB::table( 'places' )->where( 'location_id', $location->id )->get( 'id' );
				$places_id = array();
				foreach ( $places as $key => $place ) {
					$places_id[ $key ] = $place->id;
				}
				$filtered_query = $filtered_query->whereIn( 'club_id', $places_id );
			endif;
		}

		if ( ! empty( $request->genre ) && 'all-genres' !== $request->genre ) {
			$genre = DB::table( 'genres' )->where( 'id', $request->genre )->first();
			if ( $genre ) :
				$places    = DB::table( 'places' )->where( 'genre_id', $genre->id )->get( 'id' );
				$places_id = array();
				foreach ( $places as $key => $place ) {
					$places_id[ $key ] = $place->id;
				}
				$filtered_query = $filtered_query->whereIn( 'club_id', $places_id );
			endif;
		}

		if ( $request->order && 'alphabet-start' === $request->order ) {
			$filtered_query = $filtered_query->orderBy( 'title', 'ASC' );
		} elseif ( $request->order && 'alphabet-end' === $request->order ) {
			$filtered_query = $filtered_query->orderBy( 'title', 'DESC' );
		}

		$filtered_query = $filtered_query->get();

		if ( count( $filtered_query ) > 0 ) :
			foreach ( $filtered_query as $filtered_event ) : 
				$date = Carbon::createFromFormat( 'Y-m-d', $filtered_event->date )->format( 'd M Y' ); ?>
				<div class="event-box">
				<img src='images/event-thumbnails/<?php echo $filtered_event->poster; ?>' class="event-thumbnail">
				<div class="event-box-content">
					<p class="event-title"><?php echo $filtered_event->title; ?></p>
					<p class="event-date"><?php echo $date; ?></p>
				</div>
			</div>
		<?php
			endforeach;
		else :
		?>
			<h4>Няма намерени резултати.</h4>
		<?php
		endif;
	}

	// Adding new data to DB.

	public function addNewArtist( Request $request  ) {
		if( Auth::check() ) {
			$artist_data = array(
				'admin_id'        => $request->id,
				'name'            => $request->name,
				'username'        => $request->username,
				'genre_id'        => $request->genre_id,
				'profile_picture' => $request->profile_pic,
				'cover_picture'   => $request->cover_pic,
				'facebook'        => $request->facebook,
				'instagram'       => $request->instagram,
				'youtube'         => $request->youtube,
				'verified'        => 0,
				'likes'           => 0,
			);
	
			DB::table( 'artists' )->insert( $artist_data );
		}
	}

	public function addNewPlace( Request $request ) {
		if( Auth::check() ) {
			$place_data = array(
				'admin_id'        => $request->admin_id,
				'name'            => $request->name,
				'username'        => $request->username,
				'genre_id'        => $request->genre_id,
				'location_id'     => $request->location_id,
				'profile_picture' => $request->profile_pic,
				'cover_picture'   => $request->cover_pic,
				'facebook'        => $request->facebook,
				'instagram'       => $request->instagram,
				'verified'        => 0,
				'likes'           => 0,
			);

			DB::table( 'places' )->insert( $place_data );
		}
	}

	public function loadArtistInvitations( $username ) {
		if( Auth::check() ) {
			$artist_controller = new ArtistController();
			$place_controller = new PlaceController();
			$artist                  = $artist_controller->getArtistData( $username )[0];
			$pending_invitations     = $artist_controller->getArtistPendingInvitations( $artist->id );
			$approved_invitations    = $artist_controller->getArtistApprovedInvitations( $artist->id );
			$disapproved_invitations = $artist_controller->getArtistDisapprovedInvitations( $artist->id );

			?>
			<h3 class="invitations text-center my-4">Покани</h3>
			<?php if ( count( $pending_invitations ) ) : ?>
				<h4>Активни</h4>
				<div class='invitation-dashboard pending'>
				<?php
				foreach ( $pending_invitations as $single_invitation ) :
					$place    = $place_controller->getPlaceDataById( $single_invitation->place_id )[0];
					$location = $place_controller->getSingleLocation( $place->location_id )[0]->name;
					$date     = Carbon::createFromFormat( 'Y-m-d', $single_invitation->date )->format( 'd M Y' );
					$time     = Carbon::createFromFormat( 'H:i:s', $single_invitation->start_hour )->format( 'h:i' ) . ' - ' . Carbon::createFromFormat( 'H:i:s', $single_invitation->end_hour )->format( 'h:i' );
					?>
					<div class='invitation-single' id="inv-<?php echo $single_invitation->id; ?>">
						<div class='invitation-single-content'>
							<img class='invitation-single-place-thumbnail' src='/images/cover-pictures/<?php echo $place->cover_picture; ?>'>
							<p class='invitation-single-title'><?php echo $place->name . ', ' . $location; ?></p>
							<p class='invitation-single-info'><?php echo $date . ', ' . $time; ?></p>
							<p id='message' class='invitation-single-message'><?php echo $single_invitation->message; ?></p>
							<button class='invitation-single-see-more'>Виж повече ▼ </button>
							<button class='invitation-single-see-less'>Виж по-малко ▲</button>
							<div class='invitation-buttons' data-id=<?php echo $single_invitation->id; ?>>
								<button type="button" class='invitation-status' data-status=1>Приеми</button>
								<button type="button" class='invitation-status' data-status=-1>Отхвърли</button>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
				</div>
			<?php
			endif;

			if ( count( $approved_invitations ) ) : ?>
				<h4>Одобрени</h4>
				<div class='invitation-dashboard approved'>
				<?php
				foreach ( $approved_invitations as $single_invitation ) :
					$place    = $place_controller->getPlaceDataById( $single_invitation->place_id )[0];
					$location = $place_controller->getSingleLocation( $place->location_id )[0]->name;
					$date     = Carbon::createFromFormat( 'Y-m-d', $single_invitation->date )->format( 'd M Y' );
					$time     = Carbon::createFromFormat( 'H:i:s', $single_invitation->start_hour )->format( 'h:i' ) . ' - ' . Carbon::createFromFormat( 'H:i:s', $single_invitation->end_hour )->format( 'h:i' );
					?>
					<div class='invitation-single' id="inv-<?php echo $single_invitation->id; ?>">
						<div class='invitation-single-content'>
							<img class='invitation-single-place-thumbnail' src='/images/cover-pictures/<?php echo $place->cover_picture; ?>'>
							<p class='invitation-single-title'><?php echo $place->name . ', ' . $location; ?></p>
							<p class='invitation-single-info'><?php echo $date . ', ' . $time; ?></p>
							<p id='message' class='invitation-single-message'><?php echo $single_invitation->message; ?></p>
							<div class='invitation-buttons' data-event-id=<?php echo $single_invitation->id; ?>>
								<button class='invitation-single-create-event' data-date="<?php echo $single_invitation->date; ?>"  data-artist="<?php echo $artist->id; ?>" data-place="<?php echo $place->id; ?>" data-invitation=<?php echo $single_invitation->id; ?>>Създай събитие</button>
							</div>
							<button class='invitation-single-see-more'>Виж повече ▼</button>
							<button class='invitation-single-see-less'>Виж по-малко ▲</button>
						</div>
					</div>
				<?php endforeach; ?>
				</div>
			<?php
			endif;

			if ( count( $disapproved_invitations ) ) : ?>
				<h4>Отхвърлени</h4>
				<div class='invitation-dashboard disapproved'>
				<?php
				foreach ( $disapproved_invitations as $single_invitation ) :
					$place    = $place_controller->getPlaceDataById( $single_invitation->place_id )[0];
					$location = $place_controller->getSingleLocation( $place->location_id )[0]->name;
					$date     = Carbon::createFromFormat( 'Y-m-d', $single_invitation->date )->format( 'd M Y' );
					$time     = Carbon::createFromFormat( 'H:i:s', $single_invitation->start_hour )->format( 'h:i' ) . ' - ' . Carbon::createFromFormat( 'H:i:s', $single_invitation->end_hour )->format( 'h:i' );
					?>
					<div class='invitation-single' id="inv-<?php echo $single_invitation->id; ?>">
						<div class='invitation-single-content'>
							<img class='invitation-single-place-thumbnail' src='/images/cover-pictures/<?php echo $place->cover_picture; ?>'>
							<p class='invitation-single-title'><?php echo $place->name . ', ' . $location; ?></p>
							<p class='invitation-single-info'><?php echo $date . ', ' . $time; ?></p>
							<p id='message' class='invitation-single-message'><?php echo $single_invitation->message; ?></p>
							<div class='invitation-buttons' data-delete-id=<?php echo $single_invitation->id; ?>>
								<button class='invitation-single-delete'>Изтрий</button>
							</div>
							<button class='invitation-single-see-more'>Виж повече ▼</button>
							<button class='invitation-single-see-less'>Виж по-малко ▲</button>
						</div>
					</div>
				<?php endforeach; ?>
				</div>
			<?php
			endif;
		}
	}

	public function loadPlaceInvitations( $username ) {
		if( Auth::check() ) {
			$artist_controller = new ArtistController();
			$place_controller = new PlaceController();
			$place                  = $place_controller->getPlaceData( $username )[0];
			$pending_invitations     = $place_controller->getPlacePendingInvitations( $place->id );
			$approved_invitations    = $place_controller->getPlaceApprovedInvitations( $place->id );
			$disapproved_invitations = $place_controller->getPlaceDisapprovedInvitations( $place->id );

			?>
			<h3 class="invitations text-center my-4">Покани</h3>
			<?php if ( count( $pending_invitations ) ) : ?>
				<h4>Активни</h4>
				<div class='invitation-dashboard pending'>
				<?php
				foreach ( $pending_invitations as $single_invitation ) :
					$artist = $artist_controller->getArtistDataById( $single_invitation->artist_id )[0];
					$date   = Carbon::createFromFormat( 'Y-m-d', $single_invitation->date )->format( 'd M Y' );
					$time   = Carbon::createFromFormat( 'H:i:s', $single_invitation->start_hour )->format( 'H:i' ) . ' - ' . Carbon::createFromFormat( 'H:i:s', $single_invitation->end_hour )->format( 'H:i' );
					?>
					<div class='invitation-single' id="inv-<?php echo $single_invitation->id; ?>">
						<div class='invitation-single-content'>
							<img class='invitation-single-place-thumbnail' src='/images/cover-pictures/<?php echo $artist->cover_picture; ?>'>
							<p class='invitation-single-title'><?php echo $artist->name; ?></p>
							<p class='invitation-single-info'><?php echo $date . ', ' . $time; ?></p>
							<p id='message' class='invitation-single-message'><?php echo $single_invitation->message; ?></p>
							<button class='invitation-single-see-more'>Виж повече ▼ </button>
							<button class='invitation-single-see-less'>Виж по-малко ▲</button>
							<div class='invitation-buttons' data-delete-id=<?php echo $single_invitation->id; ?>>
								<button type="button" class='invitation-single-delete'>Изтрий покана</button>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
				</div>
			<?php
			endif;

			if ( count( $approved_invitations ) ) : ?>
				<h4>Одобрени</h4>
				<div class='invitation-dashboard approved'>
				<?php
				foreach ( $approved_invitations as $single_invitation ) :
					$artist = $artist_controller->getArtistDataById( $single_invitation->artist_id )[0];
					$date   = Carbon::createFromFormat( 'Y-m-d', $single_invitation->date )->format( 'd M Y' );
					$time   = Carbon::createFromFormat( 'H:i:s', $single_invitation->start_hour )->format( 'H:i' ) . ' - ' . Carbon::createFromFormat( 'H:i:s', $single_invitation->end_hour )->format( 'H:i' );
					?>
					<div class='invitation-single' id="inv-<?php echo $single_invitation->id; ?>">
						<div class='invitation-single-content'>
							<img class='invitation-single-place-thumbnail' src='/images/cover-pictures/<?php echo $artist->cover_picture; ?>'>
							<p class='invitation-single-title'><?php echo $artist->name; ?></p>
							<p class='invitation-single-info'><?php echo $date . ', ' . $time; ?></p>
							<p id='message' class='invitation-single-message'><?php echo $single_invitation->message; ?></p>
							<button class='invitation-single-see-more'>Виж повече ▼ </button>
							<button class='invitation-single-see-less'>Виж по-малко ▲</button>
							<div class='invitation-buttons' data-event-id=<?php echo $single_invitation->id; ?>>
								<button class='invitation-single-create-event' data-date="<?php echo $single_invitation->date; ?>"  data-artist="<?php echo $artist->id; ?>" data-place="<?php echo $place->id; ?>" data-invitation=<?php echo $single_invitation->id; ?>>Създай събитие</button>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
				</div>
			<?php
			endif;

			if ( count( $disapproved_invitations ) ) : ?>
				<h4>Отхвърлени</h4>
				<div class='invitation-dashboard disapproved'>
				<?php
				foreach ( $disapproved_invitations as $single_invitation ) :
					$artist = $artist_controller->getArtistDataById( $single_invitation->artist_id )[0];
					$date   = Carbon::createFromFormat( 'Y-m-d', $single_invitation->date )->format( 'd M Y' );
					$time   = Carbon::createFromFormat( 'H:i:s', $single_invitation->start_hour )->format( 'H:i' ) . ' - ' . Carbon::createFromFormat( 'H:i:s', $single_invitation->end_hour )->format( 'H:i' );
					?>
					<div class='invitation-single' id="inv-<?php echo $single_invitation->id; ?>">
						<div class='invitation-single-content'>
							<img class='invitation-single-place-thumbnail' src='/cover-pictures/<?php echo $artist->cover_picture; ?>'>
							<p class='invitation-single-title'><?php echo $artist->name; ?></p>
							<p class='invitation-single-info'><?php echo $date . ', ' . $time; ?></p>
							<p id='message' class='invitation-single-message'><?php echo $single_invitation->message; ?></p>
							<button class='invitation-single-see-more'>Виж повече ▼ </button>
							<button class='invitation-single-see-less'>Виж по-малко ▲</button>
							<div class='invitation-buttons' data-delete-id=<?php echo $single_invitation->id; ?>>
								<button class='invitation-single-delete'>Изтрий</button>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
				</div>
			<?php
			endif;
		}
	}

	public function deleteArtist( $username ) {
		if( Auth::check() ) {
			DB::table( 'artists' )->where( 'username', $username )->delete();

			return '<p>Изпълнителят беше изтрит успешно!</p>';
		}
	}

	public function deletePlace( $username ) {
		if( Auth::check() ) {
			DB::table( 'places' )->where( 'username', $username )->delete();

			return '<p>Заведението беше изтрито успешно!</p>';
		}
	}

	public function updateEditedArtist( Request $request ) {
		if( Auth::check() ) {
			$artist_data = array(
				'name'      => $request->name,
				'username'  => $request->username,
				'genre_id'  => $request->genre_id,
				'facebook'  => $request->facebook,
				'instagram' => $request->instagram,
				'youtube'   => $request->youtube,
			);

			if ( ! empty( $request->profile_pic ) ) {
				$artist_data += [ 'profile_picture' => $request->profile_pic ];
			}

			if ( ! empty( $request->cover_pic ) ) {
				$artist_data += [ 'cover_picture' => $request->cover_pic ];
			}

			DB::table( 'artists' )->where( 'id', '=', $request->id )->update( $artist_data );
		}
	}

	public function updateEditedPlace( Request $request ) {
		if( Auth::check() ) {
			$place_data = array(
				'name'        => $request->name,
				'username'    => $request->username,
				'genre_id'    => $request->genre_id,
				'location_id' => $request->location_id,
				'facebook'    => $request->facebook,
				'instagram'   => $request->instagram,
			);

			if ( ! empty( $request->profile_pic ) ) {
				$place_data += [ 'profile_picture' => $request->profile_pic ];
			}

			if ( ! empty( $request->cover_pic ) ) {
				$place_data += [ 'cover_picture' => $request->cover_pic ];
			}

			DB::table( 'places' )->where( 'id', '=', $request->id )->update( $place_data );
		}
	}

	public function statusInvitation( Request $request ) {
		if( Auth::check() ) {
			$status_update = [ 'status' => $request->status ];
			DB::table( 'invitations' )->where( 'id', $request->id )->update( $status_update );
		}
	}

	public function deleteInvitation( Request $request ) {
		if( Auth::check() ) {
			DB::table( 'invitations' )->delete( $request->id );
		}
	}

	public function inviteArtist( Request $request ) {
		if( Auth::check() ) {
			$artist_controller = new ArtistController();
			$place_controller = new PlaceController();
			
			$email      = $artist_controller->getArtistAdminEmail( $request->id );
			$invitation = array(
				'artist_id'  => $request->id,
				'place_id'   => $request->place,
				'message'    => $request->message,
				'date'       => $request->date,
				'start_hour' => $request->start_hour,
				'end_hour'   => $request->end_hour,
				'status'     => 0,
			);

			DB::table( 'invitations' )->insert( $invitation );

			$artist = $artist_controller->getArtistDataById( $request->id )[0];
			$place  = $place_controller->getPlaceDataById( $request->place )[0];

			$data = array(
				'artist'  => $artist->name,
				'place'   => $place->name,
				'message' => $request->message,
				'date'    => $request->date,
			);
			try {
				Mail::to( $email )->send( new ArtistInvitation( $data ) );
				return 'success' . $email;
			} catch ( \Throwable $th ) {
				return 'fail' . $email;
			}
		}
	}

	public function createEvent( Request $request ) {
		if( Auth::check() ) {
			$event = array(
				'artist_id' => $request->artist_id,
				'club_id'   => $request->place_id,
				'date'      => $request->date,
				'title'     => $request->title,
				'poster'    => $request->poster,
			);

			DB::table( 'events' )->insert( $event );
		}
	}

	public function createNewEvent( Request $request ) {
		if( Auth::check() ) {
			$event = array(
				'date'   => $request->date,
				'title'  => $request->title,
				'poster' => $request->poster,
			);

			DB::table( 'events' )->insert( $event );
		}
	}
}
