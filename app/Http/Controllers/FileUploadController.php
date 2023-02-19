<?php

namespace App\Http\Controllers;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Illuminate\Http\Request;
use App\Models\File;

class FileUploadController extends Controller
{
	/**
	 * Upload image into storage.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store( Request $request ) {
		if ( $request->hasFile( 'profile_picture' ) ) {
			$file = $request->file( 'profile_picture' );

			$name = $request->file( 'profile_picture' )->getClientOriginalName();

			$file->move( public_path() . '/images/profile-pictures/', $name );
		}

		if ( $request->hasFile( 'cover_picture' ) ) {
			$file = $request->file( 'cover_picture' );

			$name = $request->file( 'cover_picture' )->getClientOriginalName();

			$file->move( public_path() . '/images/cover-pictures/', $name );
		}

		if ( $request->hasFile( 'event_thumbnail' ) ) {
			$file = $request->file( 'event_thumbnail' );

			$name = $request->file( 'event_thumbnail' )->getClientOriginalName();

			$file->move( public_path() . '/images/event-thumbnails/', $name );
		}
	}
}
