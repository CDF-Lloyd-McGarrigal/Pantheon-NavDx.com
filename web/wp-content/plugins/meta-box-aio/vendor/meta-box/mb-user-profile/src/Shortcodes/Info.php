<?php
namespace MBUP\Shortcodes;

use RWMB_Helpers_Array as ArrayHelper;
use MBUP\Forms\Info as Form;
use MBUP\User;
use MBUP\Appearance;
use MBUP\Error;

class Info extends Base {
	/**
	 * Shortcode type.
	 *
	 * @var string
	 */
	protected $type = 'info';

	protected function get_form( $args, Error $error ) {
		$args = shortcode_atts( [
			// Meta Box ID.
			'id'                => '',

			// User fields.
			'user_id'           => get_current_user_id(),

			'redirect'          => '',
			'form_id'           => 'profile-form',

			// Google reCaptcha v3
			'recaptcha_key'       => '',
			'recaptcha_secret'    => '',

			// Appearance options.
			'label_password'    => __( 'New Password', 'mb-user-profile' ),
			'label_password2'   => __( 'Confirm Password', 'mb-user-profile' ),
			'label_submit'      => __( 'Submit', 'mb-user-profile' ),

			'id_password'       => 'user_pass',
			'id_password2'      => 'user_pass2',
			'id_submit'         => 'submit',

			'confirmation'      => __( 'Your information has been successfully submitted. Thank you.', 'mb-user-profile' ),

			'password_strength' => 'strong',
		], $args );

		// Compatible with old shortcode attributes.
		ArrayHelper::change_key( $args, 'submit_button', 'label_submit' );

		// Apply changes to appearance.
		$base_meta_box = rwmb_get_registry( 'meta_box' )->get( 'rwmb-user-info' );
		$appearance = new Appearance( $base_meta_box );

		$appearance->set( 'password.name', $args['label_password'] );
		$appearance->set( 'password.id', $args['id_password'] );

		$appearance->set( 'password2.name', $args['label_password2'] );
		$appearance->set( 'password2.id', $args['id_password2'] );

		$meta_box_ids = ArrayHelper::from_csv( $args['id'] );

		$meta_boxes        = [];
		$meta_boxes_unvail = [];
		foreach ( $meta_box_ids as $meta_box_id ) {
			$meta_box = rwmb_get_registry( 'meta_box' )->get( $meta_box_id );
			if ( empty( $meta_box ) ) {
				$meta_boxes_unvail[] = $meta_box_id;
			} else {
				$meta_box->object_id = $args['user_id'];
				$meta_boxes[]        = $meta_box;
			}
		}

		// Show error if no meta boxes available.
		if ( empty( $meta_boxes ) ) {
			$error->set( __( 'Error: No meta boxes are available!', 'mb-user-profile') );
			return null;
		}
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
		$user->set_user_id( $args['user_id'] );

		return new Form( $meta_boxes, $user, $args );
	}
}
