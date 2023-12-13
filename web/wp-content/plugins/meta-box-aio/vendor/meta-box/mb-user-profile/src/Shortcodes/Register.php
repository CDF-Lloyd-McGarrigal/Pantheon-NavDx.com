<?php
namespace MBUP\Shortcodes;

use RWMB_Helpers_Array as ArrayHelper;
use MBUP\Forms\Register as Form;
use MBUP\User;
use MBUP\Appearance;
use MBUP\Error;

class Register extends Base {
	/**
	 * Shortcode type.
	 *
	 * @var string
	 */
	protected $type = 'register';

	protected function get_form( $args, Error $error ) {
		$args = shortcode_atts( [
			// Meta Box ID.
			'id'                => '',

			'redirect'          => '',
			'form_id'           => 'register-form',

			// Google reCaptcha v3
			'recaptcha_key'       => '',
			'recaptcha_secret'    => '',

			// Appearance options.
			'label_username'    => __( 'Username', 'mb-user-profile' ),
			'label_email'       => __( 'Email', 'mb-user-profile' ),
			'label_password'    => __( 'Password', 'mb-user-profile' ),
			'label_password2'   => __( 'Confirm Password', 'mb-user-profile' ),
			'label_submit'      => __( 'Register', 'mb-user-profile' ),

			'id_username'       => 'user_login',
			'id_email'          => 'user_email',
			'id_password'       => 'user_pass',
			'id_password2'      => 'user_pass2',
			'id_submit'         => 'submit',

			'confirmation'		 => __( 'Your account has been created successfully.', 'mb-user-profile' ),
			'email_confirmation' => false,

			'password_strength' => 'strong',

			'email_as_username' => false,
			'show_if_user_can'  => '',
			'role'              => 'subscriber',
		], $args );
		if ( isset( $args[ 'email_confirmation' ] ) && 'true' === $args[ 'email_confirmation' ] ) {
			$args[ 'confirmation' ] = __( 'Your account has been created and is pending. Please check your email to activate your account.', 'mb-user-profile' );
		}

		// Compatible with old shortcode attributes.
		ArrayHelper::change_key( $args, 'submit_button', 'label_submit' );

		// Apply changes to appearance.
		$base_meta_box = rwmb_get_registry( 'meta_box' )->get( 'rwmb-user-register' );
		$appearance = new Appearance( $base_meta_box );

		$appearance->set( 'username.name', $args['label_username'] );
		$appearance->set( 'username.id', $args['id_username'] );

		$appearance->set( 'email.name', $args['label_email'] );
		$appearance->set( 'email.id', $args['id_email'] );

		$appearance->set( 'password.name', $args['label_password'] );
		$appearance->set( 'password.id', $args['id_password'] );

		$appearance->set( 'password2.name', $args['label_password2'] );
		$appearance->set( 'password2.id', $args['id_password2'] );

		if ( 'true' === $args['email_as_username'] ) {
			unset( $base_meta_box->meta_box['fields']['username'] );
		}

		$meta_box_ids      = ArrayHelper::from_csv( $args['id'] );
		$meta_boxes        = [];
		$meta_boxes_unvail = [];
		foreach ( $meta_box_ids as $meta_box_id ) {
			$meta_box = rwmb_get_registry( 'meta_box' )->get( $meta_box_id );
			if ( empty( $meta_box ) ) {
				$meta_boxes_unvail[] = $meta_box_id;
			} else {
				$meta_boxes[] = $meta_box;
			}
		}

		array_unshift( $meta_boxes, $base_meta_box );

		// Show warning if some meta boxes are not available.
		if ( ! empty( $meta_boxes_unvail ) ) {
			$error->set( sprintf(
				_n(
					'Warning: The following meta box are not available: "%s".',
					'Warning: The following meta boxes are not available: "%s".',
					count( $meta_boxes_unvail ),
					'mb-user-profile'
				),
				implode( ', ', $meta_boxes_unvail )
			), 'notice' );
		}

		$user = new User( $args );

		return new Form( $meta_boxes, $user, $args );
	}
}
