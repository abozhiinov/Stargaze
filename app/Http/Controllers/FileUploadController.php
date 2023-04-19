<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class FileUploadController extends Controller
{
	/**
	 * Upload image into storage.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store( Request $request ) {
		if ( Auth::check() ) {
			if ( $request->hasFile( 'profile_picture' ) ) {
				$file = $request->file( 'profile_picture' );
	
				$name =  $request->timestamp . $request->file( 'profile_picture' )->getClientOriginalName();
				
				$file->move( public_path() . '/images/profile-pictures/', $name );
			}
	
			if ( $request->hasFile( 'cover_picture' ) ) {
				$file = $request->file( 'cover_picture' );
	
				$name = $request->timestamp . $request->file( 'cover_picture' )->getClientOriginalName();
	
				$file->move( public_path() . '/images/cover-pictures/', $name );
			}
	
			if ( $request->hasFile( 'event_thumbnail' ) ) {
				$file = $request->file( 'event_thumbnail' );
	
				$name = $request->timestamp . $request->file( 'event_thumbnail' )->getClientOriginalName();
	
				$file->move( public_path() . '/images/event-thumbnails/', $name );
			}
		}
	}
}
