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
    'upload_files' => true,
    'read' => true,
);
// Añadimos un rol "carta" y le asignamos solo los permisos relacionados con la edición de la carta
add_role('carta', 'Editor de carta', $capabilities);

// Añadimos los permisos de edición de la carta a los demás roles (además de los que tienen)
$role = get_role( 'administrator' );
foreach ( $capabilities as $key => $value ) {
	$role->add_cap($key);
}

$role = get_role( 'editor' );
foreach ( $capabilities as $key => $value ) {
	$role->add_cap($key);
}
