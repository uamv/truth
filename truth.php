<?php
/*
Plugin Name: Truth
Plugin URI: https://typewheel.xyz
Description: Generates YouVersion links for Biblical scripture references
Author: Joshua Vandercar
Version: 2.8
Requires WP: 3.6
Author URI: https://typewheel.xyz
GitHub Plugin URI: https://github.com/uamv/truth
*/

define( 'TRUTH_VERSION', '2.8' );
define( 'TRUTH_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'TRUTH_DIR', plugin_dir_path( __FILE__ ) );
define( 'TRUTH_FILE', __FILE__ );
define( 'TRUTH_AUTH_ALL', FALSE );

require_once ( TRUTH_DIR . 'class-truth.php' );

// Instantiate our class
$Truth = Truth::get_instance();
