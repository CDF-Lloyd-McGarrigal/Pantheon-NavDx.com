<?php
namespace MBUP;

class Error {
	private $key;

	public function __construct( $key ) {
		$this->key = $key;
	}

	public function set( $error = false ) {
		if ( false === $error ) {
			$error = __( 'There are some errors submitting the form. Please correct and try again.', 'mb-user-profile' );
		}
		$_SESSION[ $this->key ] = $error;
	}

	public function has() {
		return ! empty( $_SESSION[ $this->key ] );
	}

	public function clear() {
		unset( $_SESSION[ $this->key ] );
	}

	public function show() {
		echo $this->get_message();
	}

	public function get_message() {
		return sprintf( '<div class="rwmb-error">%s</div>', wp_kses_post( $_SESSION[ $this->key ] ) );
	}
}