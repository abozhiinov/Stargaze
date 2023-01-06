<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\PlaceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;


class AjaxController extends Controller {

	// $type Dashboard Filters.

	/**
	 * Sort by genre.
	 */
	public function dashboardGenreSort( $type, $id, $order = null, $search = null ) {
		$query  = DB::table( $type );
		$s_type = substr( $type, 0, -1 );

		if ( $search && 'empty-search' !== $search ) {
			$query->where( 'name', 'like', "%{$search}%" );
		}

		if ( 'artists' === $type ) {
			if ( 'all-genres' !== $id ) {
				$query->where( 'genre_id', $id );
			}
		} elseif ( 'places' === $type ) {
			if ( 'all-places' !== $id ) {
				$query->where( 'location_id', $id );
			}
		}

		if ( 'alphabet-start' === $order ) {
			$query = $query->orderBy( 'name', 'ASC' );
		} elseif ( 'alphabet-end' === $order ) {
			$query = $query->orderBy( 'name', 'DESC' );
		} elseif ( 'popular' === $order ) {
			$query = $query->orderBy( 'likes', 'DESC' );
		} elseif ( 'unpopular' === $order ) {
			$query = $query->orderBy( 'likes', 'ASC' );
		}

		$query  = $query->get();
		$response = '';
		if ( count( $query ) > 0 ) :
			foreach ( $query as $q ) :
				$response .= "<div class='$s_type-box'>
					<a href='/$s_type/$q->username'>
						<img src='images/$q->profile_picture' class='$s_type-thumbnail'>
						<div class='$s_type-box-likes'>
							<img class='$s_type-likes' src='/images/likes.svg'>
							<p class='$s_type-likes-count'>$q->likes</p>
						</div>
						<div class='$s_type-box-content'>
							<p class='$s_type-title'>
								$q->name
								<span>
								";
				if ( $q->verified == 1 ) {
					$response .= "<img class='$s_type-verified' src='/images/verified.svg'>";
				}
				$response .= "</span>
							</p>
						</div>
					</a>
				</div>";
			endforeach;
		else :
			$response .= '<h4>Няма намерени резултати.</h4>';
		endif;
		return $response;
	}

	/**
	 * Order
	 */
	public function dashboardOrder( $type, $order, $sort_id = null, $search = null ) {
		$query  = DB::table( $type );
		$s_type = substr( $type, 0, -1 );

		if ( ! empty( $search ) && 'empty-search' !== $search ) {
			$query = $query->where( 'name', 'like', "%{$search}%" );
		}

		if ( 'alphabet-start' === $order ) {
			$query = $query->orderBy( 'name', 'ASC' );
		} elseif ( 'alphabet-end' === $order ) {
			$query = $query->orderBy( 'name', 'DESC' );
		} elseif ( 'popular' === $order ) {
			$query = $query->orderBy( 'likes', 'DESC' );
		} elseif ( 'unpopular' === $order ) {
			$query = $query->orderBy( 'likes', 'ASC' );
		}

		if ( $sort_id ) {
			if ( 'artists' === $type ) {
				if ( 'all-genres' !== $sort_id ) {
					$query->where( 'genre_id', $sort_id );
				}
			} elseif ( 'places' === $type ) {
				if ( 'all-places' !== $sort_id ) {
					$query->where( 'location_id', $sort_id );
				}
			}
		}
		$query  = $query->get();
		$response = '';
		if ( count( $query ) > 0 ) :
			foreach ( $query as $q ) :
				$response .= "<div class='$s_type-box'>
					<a href='/$s_type/$q->username'>
						<img src='images/$q->profile_picture' class='$s_type-thumbnail'>
						<div class='$s_type-box-likes'>
							<img class='$s_type-likes' src='/images/likes.svg'>
							<p class='$s_type-likes-count'>$q->likes</p>
						</div>
						<div class='$s_type-box-content'>
							<p class='$s_type-title'>
								$q->name
								<span>
								";
				if ( $q->verified == 1 ) {
					$response .= "<img class='$s_type-verified' src='/images/verified.svg'>";
				}
				$response .= "</span>
							</p>
						</div>
					</a>
				</div>";
			endforeach;
		else :
			$response .= '<h4>Няма намерени резултати.</h4>';
		endif;
		return $response;
	}

	public function dashboardSearch( $type, $search, $sort_id = null, $order = null ) {
		$query  = DB::table( $type );
		$s_type = substr( $type, 0, -1 );

		if ( ! empty( $search ) && 'empty-search' !== $search ) {
			$query = $query->where( 'name', 'like', "%{$search}%" );
		}

		if ( $sort_id ) {
			if ( 'artists' === $type ) {
				if ( 'all-genres' !== $sort_id ) {
					$query->where( 'genre_id', $sort_id );
				}
			} elseif ( 'places' === $type ) {
				if ( 'all-places' !== $sort_id ) {
					$query->where( 'location_id', $sort_id );
				}
			}
		}

		if ( $order && 'alphabet-start' === $order ) {
			$query = $query->orderBy( 'name', 'ASC' );
		} elseif ( $order && 'alphabet-end' === $order ) {
			$query = $query->orderBy( 'name', 'DESC' );
		} elseif ( $order && 'popular' === $order ) {
			$query = $query->orderBy( 'likes', 'DESC' );
		} elseif ( $order && 'unpopular' === $order ) {
			$query = $query->orderBy( 'likes', 'ASC' );
		}

		$query  = $query->get();
		$response = '';
		if ( count( $query ) > 0 ) :
			foreach ( $query as $q ) :
				$response .= "<div class='$s_type-box'>
					<a href='/$s_type/$q->username'>
						<img src='images/$q->profile_picture' class='$s_type-thumbnail'>
						<div class='$s_type-box-likes'>
							<img class='$s_type-likes' src='/images/likes.svg'>
							<p class='$s_type-likes-count'>$q->likes</p>
						</div>
						<div class='$s_type-box-content'>
							<p class='$s_type-title'>
								$q->name
								<span>
								";
				if ( $q->verified == 1 ) {
					$response .= "<img class='$s_type-verified' src='/images/verified.svg'>";
				}
				$response .= "</span>
							</p>
						</div>
					</a>
				</div>";
			endforeach;
		else :
			$response .= '<h4>Няма намерени резултати.</h4>';
		endif;
		return $response;
	}

	// Adding new data to DB.

	public function addNewArtist( $admin_id, $name, $username, $genre_id, $profile_pic, $cover_pic, $facebook = '', $instagram = '', $youtube = '' ) {
		$artist_data = array(
			'admin_id'        => $admin_id,
			'name'            => $name,
			'username'        => $username,
			'genre_id'        => $genre_id,
			'profile_picture' => $profile_pic,
			'cover_picture'   => $cover_pic,
			'facebook'        => $facebook,
			'instagram'       => $instagram,
			'youtube'         => $youtube,
			'verified'        => 0,
			'likes'           => 0,
		);

		DB::table( 'artists' )->insert( $artist_data );
	}

	public function addNewPlace( $admin_id, $name, $username, $genre_id, $location_id, $profile_pic, $cover_pic, $facebook = '', $instagram = '', $youtube = '' ) {
		$place_data = array(
			'admin_id'        => $admin_id,
			'name'            => $name,
			'username'        => $username,
			'genre_id'        => $genre_id,
			'location_id'     => $location_id,
			'profile_picture' => $profile_pic,
			'cover_picture'   => $cover_pic,
			'facebook'        => $facebook,
			'instagram'       => $instagram,
			'youtube'         => $youtube,
			'verified'        => 0,
			'likes'           => 0,
		);

		DB::table( 'places' )->insert( $place_data );
	}

	public function loadArtistInvitations( $username ) {
		$artist                  = ArtistController::getArtistData( $username )[0];
		$pending_invitations     = ArtistController::getArtistPendingInvitations( $artist->id );
		$approved_invitations    = ArtistController::getArtistApprovedInvitations( $artist->id );
		$disapproved_invitations = ArtistController::getArtistDisapprovedInvitations( $artist->id );

		$response = '';

		$response .= '<h3 class="invitations text-center my-4">Покани</h3>';
		if ( count( $pending_invitations ) ) :
			$response .= "<h4>Активни</h4>
			<div class='invitation-dashboard'> ";
			foreach ( $pending_invitations as $inv ) :
				$place = PlaceController::getPlaceDataById( $inv->place_id )[0];
				$response .= "<div class='invitation-single'>
				<div class='invitation-single-content'>
					<img class='invitation-single-place-thumbnail' src='/images/$place->cover_picture'>
					<p class='invitation-single-title'>$place->name, $inv->date</p>
					<p class='invitation-single-message'>$inv->message</p>
					<div class='invitation-buttons'>
						<button class='invitation-single-button'>Приеми</button>
						<button class='invitation-single-button'>Отхвърли</button>
					</div>
				</div>
			</div>";
			endforeach;
			$response .= '</div>';
		endif;

		if ( count( $approved_invitations ) ) :
			$response .= " <h4>Одобрени</h4><div class='invitation-dashboard'>";
			foreach ( $approved_invitations as $inv ) :
				$place = PlaceController::getPlaceDataById( $inv->place_id )[0];
				$response .= "<div class='invitation-single'>
				<div class='invitation-single-content'>
					<img class='invitation-single-place-thumbnail' src='/images/$place->cover_picture'>
					<p class='invitation-single-title'>$place->name, $inv->date</p>
					<p class='invitation-single-message'>$inv->message</p>
				</div>
			</div>";
			endforeach;
			$response .= '</div>';
		endif;

		if ( count( $disapproved_invitations ) ) :
			$response .= "<h4>Отхвърлени</h4><div class='invitation-dashboard'>";
			foreach ( $disapproved_invitations as $inv ) :
				$place = PlaceController::getPlaceDataById( $inv->place_id )[0];
				$response .= "<div class='invitation-single'>
				<div class='invitation-single-content'>
					<img class='invitation-single-place-thumbnail' src='/images/$place->cover_picture'>
					<p class='invitation-single-title'>$place->name, $inv->date</p>
					<p class='invitation-single-message'>$inv->message</p>
				</div>
			</div>";
			endforeach;
			$response .= '</div>';
		endif;

		return $response;
	}

	public function deleteArtist( $username ) {
		DB::table( 'artists' )->where( 'username', $username )->delete();

		return '<p>Изпълнителят беше изтрит успешно!</p>';
	}

	public function deletePlace( $username ) {
		DB::table( 'places' )->where( 'username', $username )->delete();

		return '<p>Заведението беше изтрито успешно!</p>';
	}

	public function editArtist( $username ) {
		$artist = ArtistController::getArtistData( $username )[0];
		$genres = ArtistController::getAllGenres();

		$current_genre = ArtistController::getArtistGenre( $artist->genre_id );

		?>
		<div class="edit-form-container">
		<form method="post" action="" id="form-edit-artist" onsubmit="return false">
			<div class="row justify-content-around align-items-center">
				<input type="hidden" name="_token" id="token" value="' . csrf_token() . '">
				<input type="hidden" name="id" id="id" value="' . $artist->id . '">
				<div class="form-group col-sm-5 mb-4">
				<input type="text" class="form-control edit" id="edit-artist-name" value="<?php echo  $artist->name; ?>" placeholder="Име на изпълнителя">
				</div>
				<div class="form-group col-sm-5 mb-4">
					<input type="text" class="form-control edit" id="edit-artist-username" value="<?php echo $artist->username; ?>" placeholder="Потребителско име на изпълнителя">
				</div>
				<div class="form-group col-sm-5 mb-4">
					<select class="form-control edit" id="edit-artist-genre">
						<option class="genre-option" value="<?php echo $artist->genre_id; ?>" selected><?php echo $current_genre->name; ?></option>
		<?php 
		foreach ( $genres as $genre ) :
			if ( $genre->id !== $artist->genre_id ) :
				?>
				<option class="genre-option" value="' . $genre->id . '"><?php echo $genre->name; ?></option>
		<?php
			endif;
		endforeach;
		?>
					</select>
				</div>
				<div class="form-group col-sm-5 mb-4">
				<input type="url" class="form-control edit" id="edit-artist-facebook" value="<?php echo $artist->facebook; ?>" placeholder="Facebook">
				</div>
				<div class="form-group col-sm-5 mb-4">
				<input type="url" class="form-control edit" id="edit-artist-instagram" value="<?php echo $artist->instagram; ?>" placeholder="Instagram">
				</div>
				<div class="form-group col-sm-5 mb-4">
				<input type="url" class="form-control edit" id="edit-artist-youtube" value="<?php echo $artist->youtube; ?>" placeholder="YouTube">
				</div>
				<div class="input-group custom-file-button col-sm-5 mb-4">
					<label class="input-group-text" for="edit-artist-profile-pic">Профилна снимка</label>
					<input type="file" class="form-control edit" id="edit-artist-profile-pic">
				</div>
				<div class="input-group custom-file-button col-sm-5 mb-4">
					<label class="input-group-text" for="edit-artist-cover-pic">Корица</label>
					<input type="file" class="form-control edit" id="edit-artist-cover-pic">
				</div>
				<button type="submit" class="button-custom" id="submit-edit-artist">Запази</button>
				</div>
			</form>
		</div>
		<?php
	}

	public function updateEditedArtist( $id, $name, $username, $genre_id, $profile_pic, $cover_pic, $facebook = '', $instagram = '', $youtube = '' ) {
		$artist_data = array(
			'name'            => $name,
			'username'        => $username,
			'genre_id'        => $genre_id,
			'profile_picture' => $profile_pic,
			'cover_picture'   => $cover_pic,
			'facebook'        => $facebook,
			'instagram'       => $instagram,
			'youtube'         => $youtube,
		);

		DB::table( 'artists' )->where( 'id', '=', $id )->update( $artist_data );
	}
}
