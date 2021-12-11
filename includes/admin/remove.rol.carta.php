<?php
// Listado de permisos para nuestros CPT platos y custom taxonomies seccion y alergeno
$capabilities = array(
	'read_plato' => true,
	'delete_plato' => true,
	'edit_platos' => true,
	'edit_others_platos' => true,
	'publish_platos' => true,
	'read_private_platos' => true,
	'manage_seccion' => true,
	'edit_seccion' => true,
	'delete_seccion' => true,
	'assign_seccion' => true,
	'edit_plato' => true,
	'manage_alergeno' => true,
	'edit_alergeno' => true,
	'delete_alergeno' => true,
	'assign_alergeno' => true,
);
// Borramos el rol carta
remove_role('carta');

// Quitamos las capabilities que habíamos dado al instalar el plugin
$role = get_role( 'administrator' );
foreach ( $capabilities as $key => $value ) {
	$role->remove_cap($key);
}

$role = get_role( 'editor' );
foreach ( $capabilities as $key => $value ) {
	$role->remove_cap($key);
}