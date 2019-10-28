<?php
/**
* The public Truth class
*/
if ( ! class_exists( 'Truth_Notice' ) ) {

	/**
	* lets get started
	*/
	class Truth_Notice {

		/**
		* Static property to hold our singleton instance
		* @var $instance
		*/
		public $notice = array();

		/**
		* this is our constructor.
		* there are many like it, but this one is mine
		*/
		public function __construct( $notice ) {

			$this->notice = $notice;

			// AJAX action callback for authorizing truth
			add_action( 'wp_ajax_authorize_truth', array( $this, 'authorize_truth' ) );

		}

		public function show() {

			add_action( 'admin_notices', array( $this, 'display_notice' ) );

		}

		public function display_notice() {

			echo '<div id="' . esc_attr( $this->notice['notice'] ) . '" class="' . esc_attr( $this->notice['class'] ) . '">' . $this->notice['message'] . '</div>';

		}

		public function get() {

			return '<div id="' . esc_attr( $this->notice['notice'] ) . '" class="' . esc_attr( $this->notice['class'] ) . '">' . $this->notice['message'] . '</div>';

		}

		/**
		* AJAX action target that authorizes plugin
		*
		* @since    0.0.1
		*/
		public function authorize_truth() {

			// Check the nonce
			if ( check_ajax_referer( 'authorize-truth', 'truth_security' ) ) {

				$response = array();

				$authorize = add_option( 'truth_authorization', true );

				if ( $authorize ) {

					$args = array(
						'notice'  => 'truth-authorization-success',
						'class'   => 'updated fade',
						'message' => '<p>Thank you! Visit <code><a href="' . admin_url( 'options-reading.php#truth-engine' ) . '" data-security="' . wp_create_nonce( 'authorized-truth' ) . '">Settings > Reading</a></code> to customize <strong>(and save)</strong> your plugin settings.</p>',
						'echo'    => false
					);

					$success = new Truth_Notice( $args );

					$response['notice'] = $success->get();
					$response['success'] = true;

				} else {

					$args = array(
						'notice'  => 'truth-authorization-failure',
						'class'   => 'error',
						'message' => 'Failed to authorize the Truth plugin. Please try again.',
						'echo'    => false
					);

					$failure = new Truth_Notice( $args );
					$response['notice'] = $failure->get();
					$response['success'] = false;

				}

			} else {

				$args = array(
					'notice'  => 'truth-authorization-failure',
					'class'   => 'error',
					'message' => '<p><strong>Failed to authorize the Truth plugin. Please try again.</strong></p>',
					'echo'    => false
				);

				$failure = new Truth_Notice( $args );
				$response['notice'] = $failure->get();
				$response['success'] = false;

			}

			wp_send_json( $response );

		}

	}

}
