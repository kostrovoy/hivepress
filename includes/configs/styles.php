<?php
/**
 * Styles configuration.
 *
 * @package HivePress\Configs
 */

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

return [
	'fontawesome'       => [
		'handle' => 'fontawesome',
		'src'    => hivepress()->get_url() . '/assets/css/fontawesome/fontawesome.min.css',
		'scope'  => [ 'frontend', 'backend' ],
	],

	'fontawesome_solid' => [
		'handle' => 'fontawesome-solid',
		'src'    => hivepress()->get_url() . '/assets/css/fontawesome/solid.min.css',
		'scope'  => [ 'frontend', 'backend' ],
	],

	'fancybox'          => [
		'handle' => 'fancybox',
		'src'    => hivepress()->get_url() . '/assets/css/fancybox.min.css',
	],

	'slick'             => [
		'handle' => 'slick',
		'src'    => hivepress()->get_url() . '/assets/css/slick.min.css',
		'scope'  => 'listing_view_page',
	],

	'flatpickr'         => [
		'handle' => 'flatpickr',
		'src'    => hivepress()->get_url() . '/assets/css/flatpickr.min.css',
		'scope'  => [ 'frontend', 'backend' ],
	],

	'select2'           => [
		'handle' => 'select2',
		'src'    => hivepress()->get_url() . '/assets/css/select2.min.css',
		'scope'  => [ 'frontend', 'backend' ],
	],

	'jquery_ui'         => [
		'handle' => 'jquery-ui',
		'src'    => hivepress()->get_url() . '/assets/css/jquery-ui.min.css',
	],

	'grid'              => [
		'handle' => 'hivepress-grid',
		'src'    => hivepress()->get_url() . '/assets/css/grid.min.css',
		'scope'  => [ 'frontend', 'editor' ],
	],

	'core_frontend'     => [
		'handle' => 'hivepress-core-frontend',
		'src'    => hivepress()->get_url() . '/assets/css/frontend.min.css',
		'scope'  => [ 'frontend', 'editor' ],
	],

	'core_backend'      => [
		'handle' => 'hivepress-core-backend',
		'src'    => hivepress()->get_url() . '/assets/css/backend.min.css',
		'scope'  => 'backend',
	],
];
