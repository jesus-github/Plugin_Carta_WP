<?php
// Función para cargar los shortcodes al iniciar WP
function jmd_init_shortcodes(){
	// Shortcode para mostrar todos los platos
	function jmd_create_shortcode_platos_post_type(){
		ob_start();
	    require WP_PLUGIN_DIR.'/carta_wp/includes/public/mostrar_carta.php';
		$out = ob_get_clean();
		return $out;
	}
	add_shortcode('wp-mostrar-carta', 'jmd_create_shortcode_platos_post_type');
}
add_action('init', 'jmd_init_shortcodes');

