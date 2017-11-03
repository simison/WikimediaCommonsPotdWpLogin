<?php
/*
Plugin Name: Wikimedia Commons Picture of The Day for WP Login
Plugin URI: https://github.com/simison/WikimediaCommonsPotdWpLogin
Description: See today's Wikimedia Commons picture of the day behind WordPress login screen.
Version:     1.0
Author:      Mikael Korpela
Author URI:  http://www.mikaelkorpela.fi/
License:     GPL2

Wikimedia Commons Picture of The Day for WP Login  is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Wikimedia Commons Picture of The Day for WP Login  is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Wikimedia Commons Picture of The Day for WP Login .
If not, see https://www.gnu.org/licenses/gpl-2.0.en.html
*/

// Make sure we don't expose any info if called directly
if ( ! defined( 'ABSPATH' ) ) {
  echo 'Hi there! I\'m just a plugin, not much I can do when called directly.';
  exit;
}

// Admin dashboard features
require_once( plugin_dir_path( __FILE__ ) . 'wikimedia_commons_potd_login.class.php' );
add_action( 'init', array( 'WikimediaCommonsPotdLogin', 'init' ) );
