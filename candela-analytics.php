<?php
/**
 * @package Candela Analytics
 * @version 0.1
 */
/*
Plugin Name: Candela Analytics
Plugin URI: http://lumenlearning.com/
Description: Adds Google Analyics tracking code to the theme header. This plugin assumes that you will set CANDELA_ANALYTICS_WEB_PROPERTY_ID and CANDELA_ANALYTICS_COOKIE_DOMAIN in wp-config.php.
Version: 0.1
Author URI: http://lumenlearning.com
*/

//
add_action('wp_head', 'candela_analytics_script');

define('CANDELA_ANALYTICS_USERMETA_UUID', 'candela_analytics_uuid');
define('CANDELA_ANALYTICS_UUID_LENGTH', 32);

function candela_analytics_script() {
  if ( defined( 'CANDELA_ANALYTICS_WEB_PROPERTY_ID' ) && defined( 'CANDELA_ANALYTICS_COOKIE_DOMAIN' ) ) {
    candela_analytics_header();
    candela_analytics_custom();
    candela_analytics_footer();
  }
}

function candela_analytics_header() {
  print "<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ";
}

function candela_analytics_footer() {
  print "\n</script>\n";
}

/**
 * Outputs customized google analytics code.
 */
function candela_analytics_custom() {
  print "ga('create', '" . CANDELA_ANALYTICS_WEB_PROPERTY_ID . "', '" . CANDELA_ANALYTICS_COOKIE_DOMAIN . "');\n";
  print "ga('send', 'pageview');\n";

  $uuid = candela_analytics_get_current_user_uuid();
  if (!empty($uuid)) {
    print "ga('set', '&uid', '" . $uuid . "');\n";
  }
}

/**
 * Return a uuid for the current user or the empty string if the user is not
 * logged in. This has the side effect of creating a uuid for the current user
 * if one does not exist.
 */
function candela_analytics_get_current_user_uuid() {
  if (is_user_logged_in()) {
    $current_user = wp_get_current_user();
    switch_to_blog(1);
    $uuid = get_user_meta( $current_user->ID, CANDELA_ANALYTICS_USERMETA_UUID, TRUE);
    restore_current_blog();

    if (empty($uuid)) {
      $uuid = candela_analytics_next_uuid();
      switch_to_blog(1);
      update_user_meta( $current_user->ID, CANDELA_ANALYTICS_USERMETA_UUID, $uuid);
      restore_current_blog();
    }
    return $uuid;
  }
  return '';
}

/**
 * Find a suitable next uuid.
 */
function candela_analytics_next_uuid() {
  $uuid = base64_encode(openssl_random_pseudo_bytes(CANDELA_ANALYTICS_UUID_LENGTH));
  while (candela_analytics_uuid_exists($uuid)) {
    $uuid = base64_encode(openssl_random_pseudo_bytes(CANDELA_ANALYTICS_UUID_LENGTH));
  }
  return $uuid;
}

/**
 * Returns true if the given uuid exists for any user.
 */
function candela_analytics_uuid_exists($uuid) {
    switch_to_blog(1);
    $users = get_users(array(
      'meta_key' => CANDELA_ANALYTICS_USERMETA_UID,
      'meta_value' => $uuid,
    ));
    restore_current_blog();
    if (empty($users)) {
      return FALSE;
    }
    return TRUE;
}
