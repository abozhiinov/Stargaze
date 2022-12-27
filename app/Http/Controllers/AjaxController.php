<?php

namespace App\Http\Controllers;

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

		if ( $search ) {
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

		if ( 'alphabet' === $order ) {
			$query = $query->orderBy( 'name', 'ASC' );
		} elseif ( 'popular' === $order ) {
			$query = $query->orderBy( 'likes', 'DESC' );
		}

		$query  = $query->get();
		$response = '';
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
		return $response;
	}

	/**
	 * Order
	 */
	public function dashboardOrder( $type, $order, $sort_id = null, $search = null ) {
		$query  = DB::table( $type );
		$s_type = substr( $type, 0, -1 );

		if ( ! empty( $search ) ) {
			$query = $query->where( 'name', 'like', "%{$search}%" );
		}

		if ( 'alphabet' === $order ) {
			$query = $query->orderBy( 'name', 'ASC' );
		} elseif ( 'popular' === $order ) {
			$query = $query->orderBy( 'likes', 'DESC' );
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
		return $response;
	}

	public function dashboardSearch( $type, $search, $sort_id = null, $order = null ) {
		$query  = DB::table( $type )->where( 'name', 'like', "%{$search}%" );
		$s_type = substr( $type, 0, -1 );

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

		if ( $order && 'alphabet' === $order ) {
			$query = $query->orderBy( 'name', 'ASC' );
		} elseif ( $order && 'popular' === $order ) {
			$query = $query->orderBy( 'likes', 'DESC' );
		}

		$query  = $query->get();
		$response = '';
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
		return $response;
	}

	// Places Dashboard Filters.
}
