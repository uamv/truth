(function( $ ) {
	'use strict';

	/**
	* All of the code for your Dashboard-specific JavaScript source
	* should reside in this file.
	*
	* Note that this assume you're going to use jQuery, so it prepares
	* the $ function reference to be used within the scope of this
	* function.
	*
	* From here, you're able to define handlers for when the DOM is
	* ready:
	*
	* $(function() {
		*
		* });
		*
		* Or when the window is loaded:
		*
		* $( window ).load(function() {
			*
			* });
			*
			* ...and so on.
			*
			* Remember that ideally, we should not attach any more than a single DOM-ready or window-load handler
			* for any particular page. Though other scripts in WordPress core, other plugins, and other themes may
			* be doing this, we should try to minimize doing that in our own work.
			*/

			// Toggle internal lable for site URL input
			$( document ).ready( function() {

				/* Send ajax after add site click */
				$( document ).on( 'click', '#authorize-truth', function(){

					$.post( TRUTH.ajaxurl, {

						action: 'authorize_truth',
						truth_security: $(this).data('security'),

					}, function (response) {

						if ( response.success ) {

							$('#truth-authorization').hide();
							$('#truth-authorization').after(response.notice);

						} else {

							$('#truth-authorization').after(response.notice);
							$('#truth-authorization-failure').delay(4000).slideUp();

						}

					});

				});

				// Modify UI on engine selection
				$('#truth-engine').change(function() {

					// Show/hide engine descriptors
					$('span[id^="description-engine"]:not([id="description-engine-'+$(this).find(':selected').val()+'"])').hide();
					$('span[id="description-engine-'+$(this).find(':selected').val()+'"]').show();

					// Change UI of engine options
					$('fieldset[id^="truth-"][id$="-settings"]:not([id="truth-'+$(this).find(':selected').val()+'-settings"]):not([id="truth-general-settings"])').hide();
					$('fieldset[id="truth-'+$(this).find(':selected').val()+'-settings"]').show();

				});

			});

		})( jQuery );
