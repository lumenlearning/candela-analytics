# Candela Analytics

A plugin that adds Google Analytics tracking code to the theme header.

## Installation

### Composer

Coming soon ...

### Manually

1. Download or clone Candela Analytics into your wordpress multisite plugins directory: `/path/to/wordpress/wp-content/plugins`
1. Log in to your Wordpress multisite instance and navigate to `Network Admin > Plugins` and activate the Candela Analytics plugin
1. Then set a few configuration constants in your wp-config.php file:

    ```
    define('CANDELA_ANALYTICS_WEB_PROPERTY_ID', 'UA-XXXX-Y');
    define('CANDELA_ANALYTICS_COOKIE_DOMAIN', 'auto');
    ```

*For testing use cookie domain, `none`.*
