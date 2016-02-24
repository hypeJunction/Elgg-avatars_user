<?php

$entity = elgg_extract('entity', $vars);

$avatar = avatars_get_avatar($entity);

$form = elgg_view_form('avatars/upload', [
	'enctype' => 'multipart/form-data',
], [
	'entity' => $avatar,
	'container_guid' => $entity->guid,
]);

echo elgg_view_module('info', elgg_echo('avatar:upload'), $form);