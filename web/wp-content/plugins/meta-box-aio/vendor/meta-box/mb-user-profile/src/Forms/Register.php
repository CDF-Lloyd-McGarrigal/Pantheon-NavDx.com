<?php
namespace MBUP\Forms;

class Register extends Base {
	protected $type = 'register';

	protected function has_privilege() {
		// Always show the form for non-logged in users.
		if ( ! is_user_logged_in() ) {
			return true;
		}

		// Show the form for users with proper capability like admins (for registering other users).
		if ( ! empty( $this->config['show_if_user_can'] ) && current_user_can( $this->config['show_if_user_can'] ) ) {
			return true;
		}

		esc_html_e( 'You are already logged in.', 'mb-user-profile' );
		return false;
	}

	protected function submit_button() {
		?>
		<div class="rwmb-field rwmb-button-wrapper rwmb-form-submit">
			<div class="rwmb-input">
				<button class="rwmb-button" id="<?= esc_attr( $this->config['id_submit'] ) ?>" name="rwmb_profile_submit_register" value="1"><?= esc_html( $this->config['label_submit'] ) ?></button>
			</div>
		</div>
		<?php
	}
}
