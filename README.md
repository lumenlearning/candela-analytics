# Candela Analytics

A plugin that adds Google Analytics tracking code to the theme header.

## Installation

### Composer

1.  From the root Wordpress installation, add the following to `composer.json` (replacing `1.0.0` with desired version):

    ```json
    {
      "repositories": [
        {
          "type": "vcs",
            "url": "https://github.com/lumenlearning/candela-analytics"
        }
      ],
      "require": {
        "lumenlearning/candela-analytics": "1.0.0"
      }
    }
    ```

1.  Run `composer install` in the terminal

### Manually

1.  Download or clone Candela Analytics into your Wordpress multisite plugins directory: `/path/to/wordpress/wp-content/plugins`
1.  Log in to your Wordpress multisite instance and navigate to `Network Admin > Plugins` and activate the Candela Analytics plugin
1.  Then set a few configuration constants in your `wp-config.php` file:

    ```php
    define('CANDELA_ANALYTICS_WEB_PROPERTY_ID', 'UA-XXXX-Y');
    define('CANDELA_ANALYTICS_COOKIE_DOMAIN', 'auto');
    ```

*For testing use cookie domain, `none`.*
