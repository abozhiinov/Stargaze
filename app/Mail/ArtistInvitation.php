<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ArtistInvitation extends Mailable {
	use Queueable, SerializesModels;

	private $data = array();

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct( $data ) {
		$this->data = $data;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
		return $this->from( 'stargazebulgaria@gmail.com', 'Stargaze' )
		->subject( 'Нова покана' )->view( 'emails.index' )->with( 'data', $this->data );
	}
}
