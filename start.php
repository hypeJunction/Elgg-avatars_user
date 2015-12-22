<?php

/**
 * User Avatar
 *
 * @author Ismayil Khayredinov <info@hypejunction.com>
 * @copyright Copyright (c) 2015, Ismayil Khayredinov
 */
require_once __DIR__ . '/autoloader.php';

elgg_register_event_handler('init', 'system', 'avatars_user_init');

/**
 * Initialize the plugin
 * @return void
 */
function avatars_user_init() {

	elgg_register_plugin_hook_handler('avatars:enabled', 'user', 'Elgg\Values::getTrue');
	elgg_register_plugin_hook_handler('route', 'avatar', 'avatars_user_route_hook');

}

/**
 * Route avatar pages to avatars page handler
 *
 * @param string $hook   "route"
 * @param string $type   "avatar"
 * @param array  $return Identifier and segments
 * @param array  $params Hook params
 * @return array
 */
function avatars_user_route_hook($hook, $type, $return, $params) {

	$segments = (array) elgg_extract('segments', $return, []);
	$page = array_shift($segments);
	$username = array_shift($segments);

	$avatar = false;
	$user = get_user_by_username($username);
	if ($user) {
		$avatar = avatars_get_avatar($user);
	}

	if ($page == 'edit' && !$avatar) {
		$page = 'upload';
	}

	if ($page == 'view' || $page == 'edit') {
		$guid = $avatar->guid;
	} else if ($page == 'upload') {
		$guid = $user->guid;
	}

	return [
		'identifier' => 'avatars',
		'segments' => [
			$page,
			$guid,
		]
	];

}
