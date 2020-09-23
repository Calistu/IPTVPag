<?php

/*Plugin Name: IPTV PAGs
 * Description: Plugin Wordpress para integração com a plataformas IPTV
 * Version: 1.0
 * License: GPLv2 or Later.
*/

define ('IPTVPATH','/wp-content/plugins/iptv-pag');

if ( ! defined( 'IPTV_PLUGIN_FILE' ) ) {
	define( 'IPTV_PLUGIN_FILE', __FILE__ );
}

require 'src/class-iptv-plugin.php';
