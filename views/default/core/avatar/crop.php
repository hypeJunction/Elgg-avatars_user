<?php

$entity = elgg_extract('entity', $vars);
$avatar = avatars_get_avatar($entity);
if (!$avatar) {
	return;
}

$form = elgg_view_form('avatars/crop', [
	'enctype' => 'multipart/form-data',
], [
	'entity' => $avatar,
]);

echo elgg_view_module('info', elgg_echo('avatar:crop:title'), $form);