<?php
/**
 * Añadimos la taxonomía "seccion"
 */
if ( ! function_exists( 'jmd_add_taxonomy_seccion' ) ) {

/**
 * Añadimos la taxonomía "secciones"
 */
    function jmd_add_taxonomy_seccion() {

        $labels = array(
            'name'                       => _x( 'secciones', 'Taxonomy General Name', 'jmd_platos' ),
            'singular_name'              => _x( 'seccion', 'Taxonomy Singular Name', 'jmd_platos' ),
            'menu_name'                  => __( 'Secciones', 'jmd_platos' ),
            'all_items'                  => __( 'Todas las secciones', 'jmd_platos' ),
            'parent_item'                => __( 'Sección padre', 'jmd_platos' ),
            'parent_item_colon'          => __( 'Sección padre:', 'jmd_platos' ),
            'new_item_name'              => __( 'Nueva sección', 'jmd_platos' ),
            'add_new_item'               => __( 'Añadir sección', 'jmd_platos' ),
            'edit_item'                  => __( 'Editar sección', 'jmd_platos' ),
            'update_item'                => __( 'Actualizar sección', 'jmd_platos' ),
            'view_item'                  => __( 'Ver sección', 'jmd_platos' ),
            'separate_items_with_commas' => __( 'Separar secciones con comas', 'jmd_platos' ),
            'add_or_remove_items'        => __( 'Añadir o eliminar secciones', 'jmd_platos' ),
            'choose_from_most_used'      => __( 'Elegir entre las más usadas', 'jmd_platos' ),
            'popular_items'              => __( 'Secciones populares', 'jmd_platos' ),
            'search_items'               => __( 'Buscar secciones', 'jmd_platos' ),
            'not_found'                  => __( 'No encontrada', 'jmd_platos' ),
            'no_terms'                   => __( 'Ninguna sección', 'jmd_platos' ),
            'items_list'                 => __( 'Lista de secciones', 'jmd_platos' ),
            'items_list_navigation'      => __( 'Navegación por la lista de secciones', 'jmd_platos' ),
        );
	    $capabilities = array(
		    'manage_terms'               => 'manage_seccion',
		    'edit_terms'                 => 'edit_seccion',
		    'delete_terms'               => 'delete_seccion',
		    'assign_terms'               => 'assign_seccion',
	    );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_quick_edit'         => false,
            'capabilities'               => $capabilities,
            "meta_box_cb"                => "post_categories_meta_box" // muestra checkboxes para marcar las categorías
        );
        register_taxonomy( 'seccion', 'platos', $args );
    }
    add_action( 'init', 'jmd_add_taxonomy_seccion', 0 );


}

/**
 * Añadimos la taxonomía "alérgenos"
 */
if ( ! function_exists( 'jmd_add_taxonomy_alergeno' ) ) {

// Register Custom Taxonomy
	function jmd_add_taxonomy_alergeno() {

		$labels = array(
			'name'                       => _x( 'alergenos', 'Taxonomy General Name', 'jmd_platos' ),
			'singular_name'              => _x( 'alergeno', 'Taxonomy Singular Name', 'jmd_platos' ),
			'menu_name'                  => __( 'Alérgenos', 'jmd_platos' ),
			'all_items'                  => __( 'Todos los alérgenos', 'jmd_platos' ),
			'parent_item'                => __( 'Alérgeno padre', 'jmd_platos' ),
			'parent_item_colon'          => __( 'Alérgeno padre:', 'jmd_platos' ),
			'new_item_name'              => __( 'Nuevo alérgeno', 'jmd_platos' ),
			'add_new_item'               => __( 'Añadir alérgeno', 'jmd_platos' ),
			'edit_item'                  => __( 'Editar alérgenos', 'jmd_platos' ),
			'update_item'                => __( 'Actualizar alérgeno', 'jmd_platos' ),
			'view_item'                  => __( 'Ver alérgeno', 'jmd_platos' ),
			'separate_items_with_commas' => __( 'Separar alérgenos con comas', 'jmd_platos' ),
			'add_or_remove_items'        => __( 'Añadir o eliminar alérgenos', 'jmd_platos' ),
			'choose_from_most_used'      => __( 'Elegir entre los más usadas', 'jmd_platos' ),
			'popular_items'              => __( 'Alérgenos populares', 'jmd_platos' ),
			'search_items'               => __( 'Buscar alérgenos', 'jmd_platos' ),
			'not_found'                  => __( 'No encontrado', 'jmd_platos' ),
			'no_terms'                   => __( 'Ningún alérgenos', 'jmd_platos' ),
			'items_list'                 => __( 'Lista de alérgenos', 'jmd_platos' ),
			'items_list_navigation'      => __( 'Navegación por la lista de alérgenos', 'jmd_platos' ),
		);
		$capabilities = array(
			'manage_terms'               => 'manage_alergeno',
			'edit_terms'                 => 'edit_alergeno',
			'delete_terms'               => 'delete_alergeno',
			'assign_terms'               => 'assign_alergeno',
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => false,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_in_quick_edit'         => false,
			"meta_box_cb"                => "post_categories_meta_box", // muestra checkboxes para marcar las categorías
			'show_tagcloud'              => true,
			'capabilities'               => $capabilities,
		);
		register_taxonomy( 'alergeno', 'platos', $args );

	}
	add_action( 'init', 'jmd_add_taxonomy_alergeno', 0 );
}

/**
 * Ocultamos "seleccionar padre" del meta_box_cb al añadir una sección o alérgeno desde la página de insertar plato.
 */
if ( ! function_exists( 'jmd_hide_taxonomy_parent' ) ) {
	function jmd_hide_taxonomy_parent( $slug ) {
		add_action( 'admin_head', function () use ( $slug ) {
			echo "<style>
            .taxonomy-{$slug} .term-parent-wrap,
            [for='new{$slug}_parent'],
            #new{$slug}_parent {
                display: none !important;
                visibility: hidden !important;
            }
        </style>";
		} );
	}
}
jmd_hide_taxonomy_parent( 'alergeno');
jmd_hide_taxonomy_parent( 'seccion');

/**
 * Añadimos una campo personalizado a la taxonomía alérgeno de tipo imagen.
 * Nos va a servir para añadir el icono de cada alérgeno
 */


class Jmd_add_alergeno_imagen{
	private $meta_fields = array(
		array(
			'label' => 'Icono (tamaño recomendado 30x42 px)',
			'id' => 'alergeno-imagen',
			'type' => 'media',
		)

	);
	public function __construct() {
		if ( is_admin() ) {
			add_action( 'alergeno_add_form_fields', array( $this, 'create_fields' ), 10, 2 );
			add_action( 'alergeno_edit_form_fields', array( $this, 'edit_fields' ),  10, 2 );
			add_action( 'created_alergeno', array( $this, 'save_fields' ), 10, 1 );
			add_action( 'edited_alergeno',  array( $this, 'save_fields' ), 10, 1 );
			add_action( 'admin_footer', array( $this, 'media_fields' ) );
			add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );

		}
	}

	public function media_fields() {
		?><script>
            jQuery(document).ready(function($){
                if ( typeof wp.media !== 'undefined' ) {
                    var _custom_media = true,
                        _orig_send_attachment = wp.media.editor.send.attachment;
                    $('.taxokey-media').click(function(e) {
                        var send_attachment_bkp = wp.media.editor.send.attachment;
                        var button = $(this);
                        var id = button.attr('id').replace('_button', '');
                        _custom_media = true;
                        wp.media.editor.send.attachment = function(props, attachment){
                            if ( _custom_media ) {
                                $('input#'+id).val(attachment.id);
                                $('div#preview'+id).css('background-image', 'url('+attachment.url+')');
                            } else {
                                return _orig_send_attachment.apply( this, [props, attachment] );
                            }
                        }
                        wp.media.editor.open(button);
                        return false;
                    });
                    $('.add_media').on('click', function(){
                        _custom_media = false;
                    });
                    $('.remove-media').on('click', function(){
                        var parent = $(this).parents('td');
                        parent.find('input[type="text"]').val('');
                        parent.find('div').css('background-image', 'url()');
                    });
                }
            });
        </script><?php
	}

	public function create_fields( $taxonomy ) {
		$meta_value = null;
		$output = '';
		foreach ( $this->meta_fields as $meta_field ) {
			$label = '<label for="' . $meta_field['id'] . '">' . $meta_field['label'] . '</label>';
			if ( empty( $meta_value ) ) {
				if ( isset( $meta_field['default'] ) ) {
					$meta_value = $meta_field['default'];
				}
			}
			switch ( $meta_field['type'] ) {
				case 'media':
					$meta_url = '';
					if ($meta_value) {
						$meta_url = wp_get_attachment_url($meta_value);
					}
					$input = sprintf(
						'<input style="display:none;" id="%s" name="%s" type="text" value="%s"><div id="preview%s" style="margin-right:10px;border:2px solid #eee;display:inline-block;width: 100px;height:100px;background-image:url(%s);background-size:contain;background-repeat:no-repeat;"></div><input style="width: 22%%;margin:10px;" class="button taxokey-media" id="%s_button" name="%s_button" type="button" value="Seleccionar..." /><input style="margin:10px;width: 19%%;" class="button remove-media" id="%s_buttonremove" name="%s_buttonremove" type="button" value="Eliminar" />',
						$meta_field['id'],
						$meta_field['id'],
						$meta_value,
						$meta_field['id'],
						$meta_url,
						$meta_field['id'],
						$meta_field['id'],
						$meta_field['id'],
						$meta_field['id']
					);
					break;
				default:
					$input = sprintf(
						'<input %s id="%s" name="%s" type="%s" value="%s">',
						$meta_field['type'] !== 'color' ? '' : '',
						$meta_field['id'],
						$meta_field['id'],
						$meta_field['type'],
						$meta_value
					);
			}
			$output .= '<div class="form-field">'.$this->format_rows( $label, $input ).'</div>';
		}
		echo $output;
	}
	public function edit_fields( $term, $taxonomy ) {
		$output = '';
		foreach ( $this->meta_fields as $meta_field ) {
			$label = '<label for="' . $meta_field['id'] . '">' . $meta_field['label'] . '</label>';
			$meta_value = get_term_meta( $term->term_id, $meta_field['id'], true );
			switch ( $meta_field['type'] ) {

				case 'media':
					$meta_url = '';
					if ($meta_value) {
						$meta_url = wp_get_attachment_url($meta_value);
					}
					$input = sprintf(
						'<input style="display:none;" id="%s" name="%s" type="text" value="%s"><div id="preview%s" style="margin-right:10px;border:2px solid #eee;display:inline-block;width: 100px;height:100px;background-image:url(%s);background-size:contain;background-repeat:no-repeat;"></div><input style="width: 19%%;margin-right:5px;" class="button taxokey-media" id="%s_button" name="%s_button" type="button" value="Seleccionar..." /><input style="width: 19%%;" class="button remove-media" id="%s_buttonremove" name="%s_buttonremove" type="button" value="Eliminar" />',
						$meta_field['id'],
						$meta_field['id'],
						$meta_value,
						$meta_field['id'],
						$meta_url,
						$meta_field['id'],
						$meta_field['id'],
						$meta_field['id'],
						$meta_field['id']
					);
					break;

				default:
					$input = sprintf(
						'<input %s id="%s" name="%s" type="%s" value="%s">',
						$meta_field['type'] !== 'color' ? '' : '',
						$meta_field['id'],
						$meta_field['id'],
						$meta_field['type'],
						$meta_value
					);
			}
			$output .= $this->format_rows( $label, $input );
		}
		echo '<div class="form-field">' . $output . '</div>';
	}
	public function format_rows( $label, $input ) {
		return '<tr class="form-field"><th>'.$label.'</th><td>'.$input.'</td></tr>';
	}
	public function save_fields( $term_id ) {
		foreach ( $this->meta_fields as $meta_field ) {
			if ( isset( $_POST[ $meta_field['id'] ] ) ) {
				switch ( $meta_field['type'] ) {
					case 'email':
						$_POST[ $meta_field['id'] ] = sanitize_email( $_POST[ $meta_field['id'] ] );
						break;
					case 'text':
						$_POST[ $meta_field['id'] ] = sanitize_text_field( $_POST[ $meta_field['id'] ] );
						break;
				}
				update_term_meta( $term_id, $meta_field['id'], $_POST[ $meta_field['id']] );
			} else if ( $meta_field['type'] === 'checkbox' ) {
				update_term_meta( $term_id, $meta_field['id'], '0' );
			}
		}
	}
}
if (class_exists('Jmd_add_alergeno_imagen')) {
	new Jmd_add_alergeno_imagen;
};


/**
 * Añadimos los alérgenos existentes a la taxonomía alergenos'
 */
include plugin_dir_path(__FILE__) . 'alergeno.terms.php';