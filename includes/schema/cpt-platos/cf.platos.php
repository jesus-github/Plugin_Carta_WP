<?php

/**
 * Añadimos una metabox para introducir el CF precio en la pantalla de añadir
 */
function jmd_add_meta_box_platos() {
		add_meta_box(
			'meta_box_precio_plato',                // Unique ID
			__('Precio', 'jmd_platos'),                 // Box title
			'meta_box_precio_plato_callback',   // Content callback, must be of type callable
			'platos',                           // Post type
			'side'                             // Position
		);
}
// Ejecutamos la función en el hook 'add_meta_boxes'
add_action( 'add_meta_boxes', 'jmd_add_meta_box_platos' );

/**
 * Función de callback para meta-box de precio
 */
function meta_box_precio_plato_callback(){
    global $post;
    /* Extraemos el valor del meta-dato precio para mostrarlo en la meta-box mediante el atributo value del input. Para
    que si ya está introducido de antes nos aparezca en el input */
    $plato_precio = get_post_meta($post->ID,'plato_precio', true);

    ?>
		<label for="plato_precio"><strong>Precio del plato</strong></label>
		<input type="text" name="plato_precio" id="plato-precio" class="postbox" value="<?php echo $plato_precio?>">
		<small>No introduzcas el símbolo €</small>
	<?php
}

/**
 * Función para guardar el precio introducido en la meta-box
 *
 */
function jmd_save_post_plato($post_id, $post, $update) {
	if (isset($_POST['plato_precio'])){
        // Saneamos el precio introducido para evitar inyección de código php en el campo
        $jmd_plato_precio = sanitize_text_field($_POST['plato_precio']);
        update_post_meta( $post_id, 'plato_precio', $jmd_plato_precio);
	}
}
// Ejecutamos la función 'jmd_save_post_plato' en el hook 'save_post'
add_action('save_post','jmd_save_post_plato', 10, 3);