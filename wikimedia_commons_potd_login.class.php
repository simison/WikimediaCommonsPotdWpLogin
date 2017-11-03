<?php

/**
 * Fetch and return URL for the Photo of The Day in Wikimedia Commons
 * and show it behind login screens.
 *
 * @link https://commons.wikimedia.org/wiki/Commons:Picture_of_the_day
 */
class WikimediaCommonsPotdLogin {
  // https://commons.wikimedia.org/wiki/File:Saffron_finch_(Sicalis_flaveola)_male.JPG
  public static $default_image = 'Saffron_finch_(Sicalis_flaveola)_male.JPG';

  private static $default_image_width = 1200; // in Pixels
  private static $wiki_url = 'https://commons.wikimedia.org/';
  private static $wiki_api_url = 'w/api.php';
  private static $wiki_filepath_url = 'wiki/Special:FilePath/';

  /**
   * Initialize plugin
   */
  public static function init() {
    add_action( 'login_enqueue_scripts', array( 'WikimediaCommonsPotdLogin', 'load_styles' )  );
  }

  /**
   * Construct CSS styles
   */
  public static function load_styles() {
		wp_register_style(
			'wikimedia-commons-potd-login',
			plugins_url( 'wikimedia-commons-potd-login.css', __FILE__ ),
			array(),
			'1.0'
		);

		wp_enqueue_style( 'wikimedia-commons-potd-login' );

    // Get URL of PotD
    $url = self::getPotdUrl( 1200 );

    // Create inline style
    $css = 'body.login { background-image: url("' . esc_url( $url, ['https'] ) . '") }';

    // Attach inline style to previously enqueued style
    wp_add_inline_style( 'wikimedia-commons-potd-login', $css );
  }

  /**
   * Attempts to get and parse picture of the day response from wiki API
   *
   * @return mixed Returns URL string on success, otherwise false
   */
  private static function fetchImageAPI() {
    $api_query_params = http_build_query( array(
      'action'       => 'parse',
      'text'         => '{{Potd}}',
      'contentmodel' => 'wikitext',
      'prop'         => 'images',
      'format'       => 'json',
    ) );

    $api_url =
      self::$wiki_url .
      self::$wiki_api_url .
      '?' . $api_query_params;

    $api_result = wp_remote_get( $api_url );

    if ( ! is_array( $api_result ) || wp_remote_retrieve_response_code( $api_result ) >= 400 ) {
      return false;
    }

    $json = json_decode( wp_remote_retrieve_body( $api_result ) );

    if ( empty( $json ) ||
         !isset( $json->parse ) ||
         !isset( $json->parse->images ) ||
         !isset( $json->parse->images[0] )
    ) {
      return false;
    }

    return (string) $json->parse->images[0];
  }

  /**
   * Get default picture URL
   *
   * @param int Width of the image in pixels
   * @return string URL
   */
  private static function getDefaultImageUrl( $width ) {
    return
      self::$wiki_url .
      self::$wiki_filepath_url .
      self::$default_image .
      '?width=' . $width;
  }

  /**
   * Get picture of the day
   *
   * @param int Width of the image in pixels
   * @return string URL
   */
  private static function getPotdUrl( $width ) {
    $width = !empty( $width ) ? absint( $width ) : self::$default_image_width;

    $image_name = self::fetchImageAPI();

    if ( ! $image_name ) {
      return self::getDefaultImageUrl( $width );
    }

    return self::$wiki_url . self::$wiki_filepath_url . $image_name . '?width=' . $width;
  }
}
