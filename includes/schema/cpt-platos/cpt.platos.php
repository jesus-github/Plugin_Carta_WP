<?php
/**
 * Incluimos un Campo personalizado llamado 'precio' al CPT plato
 */
include plugin_dir_path(__FILE__) . 'cf.platos.php';
include plugin_dir_path(__FILE__) . 'taxonomies.platos.php';
include plugin_dir_path(__FILE__) . 'shortcode.platos.php';

/**
 * Creamos un nuevo CPT Platos
 */
if ( ! function_exists('jmd_cpt_platos') ) {

	function jmd_cpt_platos() {

		$labels = array(
			'name'                  => _x( 'platos', 'Post Type General Name', 'jmd_platos' ),
			'singular_name'         => _x( 'plato', 'Post Type Singular Name', 'jmd_platos' ),
			'menu_name'             => __( 'Platos', 'jmd_platos' ),
			'name_admin_bar'        => __( 'Platos', 'jmd_platos' ),
			'archives'              => __( 'Platos', 'jmd_platos' ),
			'attributes'            => __( 'Plato atributos', 'jmd_platos' ),
			'parent_item_colon'     => __( 'Plato padre', 'jmd_platos' ),
			'all_items'             => __( 'Todos los platos', 'jmd_platos' ),
			'add_new_item'          => __( 'Añadir plato', 'jmd_platos' ),
			'add_new'               => __( 'Añadir nuevo', 'jmd_platos' ),
			'new_item'              => __( 'Nuevo plato', 'jmd_platos' ),
			'edit_item'             => __( 'Editar plato', 'jmd_platos' ),
			'update_item'           => __( 'Actualizar plato', 'jmd_platos' ),
			'view_item'             => __( 'Ver plato', 'jmd_platos' ),
			'view_items'            => __( 'Ver platos', 'jmd_platos' ),
			'search_items'          => __( 'Buscar plato', 'jmd_platos' ),
			'not_found'             => __( 'no encontrado', 'jmd_platos' ),
			'not_found_in_trash'    => __( 'No encontrado en la papelera', 'jmd_platos' ),
			'featured_image'        => __( 'Imagen destacada', 'jmd_platos' ),
			'set_featured_image'    => __( 'Seleccionar imagen destacada', 'jmd_platos' ),
			'remove_featured_image' => __( 'Borrar imagen destacada', 'jmd_platos' ),
			'use_featured_image'    => __( 'Usar como imagen destacada', 'jmd_platos' ),
			'insert_into_item'      => __( 'Insertar en plato', 'jmd_platos' ),
			'uploaded_to_this_item' => __( 'Subida a este plato', 'jmd_platos' ),
			'items_list'            => __( 'Lista de platos', 'jmd_platos' ),
			'items_list_navigation' => __( 'Lista de navegación de platos', 'jmd_platos' ),
			'filter_items_list'     => __( 'Lista filtrada de platos', 'jmd_platos' ),
		);
		$capabilities = array(
			'edit_post'             => 'edit_plato',
			'read_post'             => 'read_plato',
			'delete_post'           => 'delete_plato',
			'edit_posts'            => 'edit_platos',
			'edit_others_posts'     => 'edit_others_platos',
			'publish_posts'         => 'publish_platos',
			'read_private_posts'    => 'read_private_platos',
		);
		$args = array(
			'label'                 => __( 'plato', 'jmd_platos' ),
			'description'           => __( 'Platos que van a componer la carta', 'jmd_platos' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail'),
			'taxonomies'            => array( 'seccion', ' alergeno' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-food',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capabilities'          => $capabilities,
		);
		register_post_type( 'platos', $args );
		// Actualiza los enlaces permanentes
		flush_rewrite_rules();

	}
	// Añadimos la función jmd_platos al hook de WP 'init' para que cargue el CPT platos al iniciar
	add_action( 'init', 'jmd_cpt_platos', 0 );

}