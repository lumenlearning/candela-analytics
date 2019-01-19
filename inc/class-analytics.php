<?php

/**
 * @author Lumen Learning
 * @package Candela Analytics
 * @copyright (c) Lumen Learning
 */

namespace Candela;

class Analytics {

	/**
	 * Register hooks
	 */
	public static function init() {
		add_action('wp_head', array( __CLASS__, 'candela_analytics_script' ) );
	}

	/**
	 * Assemble google analytics script
	 */
	public static function candela_analytics_script() {
		if ( defined( 'CANDELA_ANALYTICS_WEB_PROPERTY_ID' ) && defined( 'CANDELA_ANALYTICS_COOKIE_DOMAIN' ) ) {
			candela_analytics_header();
			candela_analytics_custom();
			candela_analytics_footer();
		}
	}

	/**
	 * Add opening script tag and define google analytics script
	 */
	public static function candela_analytics_header() {
		print "<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		";
	}

	/**
	 * Add google analytics uuid
	 */
	public static function candela_analytics_custom() {
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
	public static function candela_analytics_get_current_user_uuid() {
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
	public static function candela_analytics_next_uuid() {
		$uuid = base64_encode(openssl_random_pseudo_bytes(CANDELA_ANALYTICS_UUID_LENGTH));

		while (candela_analytics_uuid_exists($uuid)) {
			$uuid = base64_encode(openssl_random_pseudo_bytes(CANDELA_ANALYTICS_UUID_LENGTH));
		}

		return $uuid;
	}

	/**
	 * Returns true if the given uuid exists for any user.
	 */
	public static function candela_analytics_uuid_exists($uuid) {
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

	/**
	 * Append google analytics script closing tag
	 */
	public static function candela_analytics_footer() {
		print "\n</script>\n";
	}
}
