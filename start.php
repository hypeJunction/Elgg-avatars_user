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
	elgg_register_plugin_hook_handler('thumb:directory', 'object', 'avatars_user_set_thumb_directory');
	elgg_register_plugin_hook_handler('thumb:filename', 'object', 'avatars_user_set_thumb_filename');

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

/**
 * Set user avatar thumb directory
 * 
 * @param string $hook   "thumb:directory"
 * @param string $type   "object"
 * @param string $return Directory
 * @param array  $params Hook params
 * @return string
 */
function avatars_user_set_thumb_directory($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);
	if ($entity instanceof hypeJunction\Images\Avatar && $entity->getContainerEntity() instanceof ElggUser) {
		return 'profile';
	}
}

/**
 * Set user avatar filename on filestore
 *
 * @param string $hook   "thumb:filename"
 * @param string $type   "object"
 * @param string $return Directory
 * @param array  $params Hook params
 * @return string
 */
function avatars_user_set_thumb_filename($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);
	$size = elgg_extract('size', $params);
	if ($entity instanceof hypeJunction\Images\Avatar && $entity->getContainerEntity() instanceof ElggUser) {
		return "{$entity->container_guid}{$size}.jpg";
	}
}
