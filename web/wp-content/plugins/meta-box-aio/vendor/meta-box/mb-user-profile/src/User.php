<?php
namespace MBUP;
use MBUP\Email;

class User {
	public $user_id;
	public $config;
	private $error;

	public function __construct( $config = [] ) {
		$this->config = $config;
	}

	public function set_error( $error ) {
		$this->error = $error;
	}

	public function set_user_id( $user_id ) {
		$this->user_id = $user_id;
	}

	public function save() {
		do_action( 'rwmb_profile_before_save_user', $this );

		if ( $this->user_id ) {
			$this->update();
		} else {
			$this->create();
		}

		do_action( 'rwmb_profile_after_save_user', $this );

		return $this->user_id;
	}

	private function update() {
		$data = apply_filters( 'rwmb_profile_update_user_data', [], $this->config );

		// Do not update user data, only trigger an action for Meta Box to update custom fields.
		if ( empty( $data ) ) {
			$old_user_data = get_userdata( $this->user_id );
			if ( ! $old_user_data ) {
				$this->error->set( __( 'Invalid user ID.', 'mb-user-profile' ) );
				return;
			}
			do_action( 'profile_update', $this->user_id, $old_user_data );
			return;
		}

		// Update user data.
		$data['ID'] = $this->user_id;
		if ( isset( $data['user_pass'] ) && isset( $data['user_pass2'] ) && $data['user_pass'] !== $data['user_pass2'] ) {
			$this->error->set( __( 'Passwords do not coincide.', 'mb-user-profile' ) );
			return;
		}
		unset( $data['user_pass2'] );

		$result = wp_update_user( $data );
		if ( is_wp_error( $result ) ) {
			$this->error->set( $result->get_error_message() );
		}
	}

	private function create() {
		$data = apply_filters( 'rwmb_profile_insert_user_data', [], $this->config );
		if ( isset( $data['user_email'] ) && email_exists( $data['user_email'] ) ) {
			$this->error->set( __( 'Your email already exists.', 'mb-user-profile' ) );
			return;
		}
		if ( isset( $this->config['email_as_username'] ) && 'true' === $this->config['email_as_username'] && isset( $data['user_email'] ) ) {
			$data['user_login'] = $data['user_email'];
		}


		$role = $this->config['role'];
		if ( ! empty( $role ) && $GLOBALS['wp_roles']->is_role( $role ) ) {
			$data['role'] = $role;
		}
		if ( isset( $data['user_login'] ) && username_exists( $data['user_login'] ) ) {
			$this->error->set( __( 'Your username already exists.', 'mb-user-profile' ) );
			return;
		}
		if ( isset( $data['user_pass'] ) && isset( $data['user_pass2'] ) && $data['user_pass'] !== $data['user_pass2'] ) {
			$this->error->set( __( 'Passwords do not coincide.', 'mb-user-profile' ) );
			return;
		}
		unset( $data['user_pass2'] );

		$result = wp_insert_user( $data );
		if ( is_wp_error( $result ) ) {
			$this->error->set( $result->get_error_message() );
		} else {
			$this->user_id = $result;

			// Check if sent email confirmation
			if ( isset( $this->config['email_confirmation'] ) && 'true' === $this->config['email_confirmation'] ) {
				$email = new Email();
				$email->send_confirmation_email( $result, $data['user_email'], $data['user_login'] );
			}
		}
	}
}
