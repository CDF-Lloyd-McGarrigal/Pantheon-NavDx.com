<?php
namespace MBUP\Forms;

use MBUP\Error;

class Login extends Base {
	protected $type = 'login';

	protected function has_privilege() {
		if ( is_user_logged_in() && ! $this->is_processed() ) {
			esc_html_e( 'You are already logged in.', 'mb-user-profile' );
			return false;
		}
		return true;
	}

	public function process() {
		if ( isset( $_GET['lost-password'] ) ) {
			return $this->retrieve_password();
		}

		if ( isset( $_GET['reset-password'] ) ) {
			return $this->reset_password();
		}
		$this->error->clear();
		$request     = rwmb_request();
		$credentials = [
			'user_login'    => $request->post( 'user_login' ),
			'user_password' => $request->post( 'user_pass' ),
			'remember'      => (bool) $request->post( 'remember' ),
		];
		$user = $this->get_user( $credentials['user_login'] );
		if ( ! $user ) {
			$this->error->set( __( 'Account invalid.', 'mb-user-profile' ) );
			return null;
		}
		$user_confirmation_code = get_user_meta( $user->ID, 'mbup_confirmation_code', true );
		if ( $user_confirmation_code ) {
			$this->error->set( __( 'Your account not comfirm yet. Please contact with admin.', 'mb-user-profile' ) );
			return null;
		}

		add_filter( 'lostpassword_url', [ $this, 'change_lost_password_url' ] );
		$user = wp_signon( $credentials, is_ssl() );
		remove_filter( 'lostpassword_url', [ $this, 'change_lost_password_url' ] );

		if ( is_wp_error( $user ) ) {
			$this->error->set( $user->get_error_message() );
			return null;
		}

		return $user->ID;
	}

	private function retrieve_password() {
		$this->error->clear();

		$login = rwmb_request()->post( 'user_login' );

		if ( ! $login ) {
			$this->error->set( __( 'Please enter a username or email address.', 'mb-user-profile' ) );
			return null;
		}

		$user = get_user_by( 'login', $login );
		if ( ! $user && is_email( $login ) ) {
			$user = get_user_by( 'email', $login );
		}

		if ( ! $user ) {
			$this->error->set( __( 'Invalid username or email.', 'mb-user-profile' ) );
			return null;
		}

		$key = get_password_reset_key( $user );

		$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );

		$url = remove_query_arg( ['lost-password', 'rwmb-form-submitted'], $current_url );
		$url = add_query_arg( [
			'reset-password' => 'true',
			'key'            => $key,
			'login'          => $user->user_login,
		], $url );

		$subject = sprintf( __( '[%s] Password Reset', 'mb-user-profile' ), get_bloginfo( 'name' ) );

		$message = '<p>' . esc_html( sprintf( __( 'Hi, %s', 'mb-user-profile' ), $user->display_name ) ) . '</p>';
		$message .= '<p>' . esc_html( sprintf( __( 'Someone has requested a new password for your account on %s site.', 'mb-user-profile' ), get_bloginfo( 'name' ) ) ) . '</p>';
		$message .= '<p><a href="' . esc_url( $url ) . '">' . esc_html__( 'Click here to reset your password', 'mb-user-profile' ) . '</a></p>';

		$headers = ['Content-type: text/html'];

		$result = wp_mail( $user->user_email, $subject, $message, $headers );

		if ( ! $result ) {
			$this->error->set( __( 'Error sending email. Please try again.', 'mb-user-profile' ) );
			return null;
		}

		$redirect = add_query_arg( 'rwmb-form-submitted', 'success', $current_url );
		wp_safe_redirect( $redirect );
		die;
	}

	private function reset_password() {
		$this->error->clear();

		$request = rwmb_request();

		$key = $request->get( 'key' );
		$login = $request->get( 'login' );

		$user = check_password_reset_key( $key, $login );

		if ( is_wp_error( $user ) ) {
			$this->error->set( __( 'This key is invalid or has already been used. Please reset your password again if needed.', 'mb-user-profile' ) );
			$redirect = remove_query_arg( ['reset-password', 'key', 'login', 'rwmb-form-submitted'] );
			$redirect = add_query_arg( 'lost-password', 'true', $redirect );
			wp_safe_redirect( $redirect );
			die;
		}

		$password = $request->post( 'user_pass' );
		$password2 = $request->post( 'user_pass2' );

		if ( ! $password || ! $password2 ) {
			$this->error->set( __( 'Please enter a valid password.', 'mb-user-profile' ) );
			return null;
		}
		if ( $password !== $password2 ) {
			$this->error->set( __( 'Password does not coincide.', 'mb-user-profile' ) );
			return null;
		}

		$result = wp_update_user( [
			'ID'        => $user->ID,
			'user_pass' => $password,
		] );

		if ( is_wp_error( $result ) ) {
			$this->error->set( $result->get_error_message() );
			return null;
		}

		$redirect = add_query_arg( 'rwmb-form-submitted', 'success' );
		wp_safe_redirect( $redirect );
		die;
	}

	protected function display_confirmation() {
		$confirmation = $this->config['confirmation'];
		if ( isset( $_GET['lost-password'] ) ) {
			$confirmation = __( 'Please check your email for the confirmation link.', 'mb-user-profile' );
		}
		if ( isset( $_GET['reset-password'] ) ) {
			$confirmation = __( 'Your password has been reset.', 'mb-user-profile' ) . ' <a href="' . remove_query_arg( ['lost-password', 'reset-password', 'rwmb-form-submitted', 'key', 'login'] ) . '">' . __( 'Log in', 'mb-user-profile' ) . '</a>';
		}
		?>
		<div class="rwmb-confirmation"><?= wp_kses_post( $confirmation ); ?></div>
		<?php
	}

	public function change_lost_password_url( $url ) {
		$url = remove_query_arg( 'rwmb-form-submitted' );
		return add_query_arg( 'lost-password', 'true', $url );
	}
	public function get_user( $user_login ) {
		$attrs = array( 'login', 'email' );
		foreach ( $attrs as $attr ) {
			if ( ! empty( $user =  get_user_by( $attr, $user_login ) ) ) {
				return $user;
			}
		}
		return false;
	}

}
