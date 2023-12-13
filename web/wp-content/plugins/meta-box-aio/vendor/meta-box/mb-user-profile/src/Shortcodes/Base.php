<?php
namespace MBUP\Shortcodes;

use MBUP\Error;
use MBUP\ConfigStorage;

abstract class Base {
	protected $type;

	public function __construct() {
		add_shortcode( "mb_user_profile_{$this->type}", [ $this, 'shortcode' ] );

		add_action( 'template_redirect', [ $this, 'init_session' ] );

		add_action( 'template_redirect', [ $this, 'handle_request' ] );
	}

	/**
	 * Output the user form in the frontend.
	 *
	 * @param array $atts Form parameters.
	 *
	 * @return string
	 */
	public function shortcode( $atts ) {
		/*
		 * Do not render the shortcode in the admin.
		 * Prevent errors with enqueue assets in Gutenberg where requests are made via REST to preload the post content.
		 */
		if ( is_admin() ) {
			return '';
		}

		wp_enqueue_style( 'mbup', MBUP_URL . 'assets/user-profile.css', [], MBUP_VER );

		$error = new Error( 'mbup-shortcode-error-' . md5( serialize( $atts ) ) );
		$error->clear();
		$form = $this->get_form( $atts, $error );
		if ( ! $form ) {
			return $error->has() ? $error->get_message() : '';
		}

		ob_start();
		if ( $error->has() ) {
			$error->show();
		}
		$form->render();

		return ob_get_clean();
	}

	public function init_session() {
		if ( session_status() === PHP_SESSION_NONE && ! headers_sent() ) {
			session_start();
		}
	}

	public function handle_request() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );
		if ( $action === $this->type ) {
			$this->process();
		}
	}

	/**
	 * Handle the form submit.
	 */
	public function process() {
		$config_key = filter_input( INPUT_POST, 'rwmb_form_config', FILTER_SANITIZE_STRING );
		$config     = ConfigStorage::get( $config_key );
		if ( empty( $config ) ) {
			return;
		}
		$error = new Error( 'mbup-shortcode-error-' . md5( serialize( $config ) ) );
		$error->clear();
		$form = $this->get_form( $config, $error );
		if ( ! $form ) {
			return;
		}

		// Make sure to include the WordPress media uploader functions to process uploaded files.
		if ( ! function_exists( 'media_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
		}

		$user_id  = $form->process();
		$redirect = add_query_arg( 'rwmb-form-submitted', $user_id ? $config_key : 'error' );

		if ( $user_id && $config['redirect'] ) {
			$redirect = $config['redirect'];
		}

		$redirect = apply_filters( 'rwmb_profile_redirect', $redirect, $config );
		wp_safe_redirect( $redirect );
		die;
	}

	abstract protected function get_form( $atts, Error $error );
}
