<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ArtistInvitation;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailController extends Controller {

	public function index() {
		$data = array(
			'subject' => 'New Invitation',
		);

		try {
			Mail::to( 'abozhiinov@gmail.com' )->send( new ArtistInvitation( $data ) );
			return response()->json( ['sent'] );
		} catch ( \Throwable $th ) {
			return response()->json( ['not sent'] );
		}
	}

}
