<?php

	/**
	 * Insert term and meta image.
	 *
	 * @param  String $url
	 * @param String $term
	 * @param String $taxonomy
	 * @param  Int    $parent_post_id
	 * @return Int    Attachment ID
	 */

	function jmd_insert_term_and_metaimage ($url, $term, $taxonomy, $parent_post_id = null) {

		if( !class_exists( 'WP_Http' ) )
			include_once( ABSPATH . WPINC . '/class-http.php' );

		$http = new WP_Http();
		$response = $http->request( $url );
		if( $response['response']['code'] != 200 ) {
			return false;
		}

		$upload = wp_upload_bits( basename($url), null, $response['body'] );
		if( !empty( $upload['error'] ) ) {
			return false;
		}

		$file_path = $upload['file'];
		$file_name = basename( $file_path );
		$file_type = wp_check_filetype( $file_name, null );
		$attachment_title = sanitize_file_name( pathinfo( $file_name, PATHINFO_FILENAME ) );
		$wp_upload_dir = wp_upload_dir();

		$post_info = array(
			'guid'           => $wp_upload_dir['url'] . '/' . $file_name,
			'post_mime_type' => $file_type['type'],
			'post_title'     => $attachment_title,
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		// Create the attachment
		$attach_id = wp_insert_attachment( $post_info, $file_path, $parent_post_id );

		// Include image.php
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		// Define attachment metadata
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );

		// Assign metadata to attachment
		wp_update_attachment_metadata( $attach_id,  $attach_data );

		// Tenemos $attach_id que es el id del post attachment

		// Introducimos el término en la taxonomía
		wp_insert_term($term,$taxonomy);

		// Nos llega a la función

		/*  Añadimos un metadato (alergeno-imagen) a altramuces
	    *      add_term_meta( int $term_id, string $meta_key, mixed $meta_value, bool $unique = false )
	    *      - $term = get_term_by('slug','altramuces', 'alergeno'); $term->term_id
	    *      - meta_key (string) – Custom field key. 'alergeno-imagen'
	    *      - meta_value (string|array) será $attach_id – Custom field value. (El meta_value es igual al id del post creado en el punto 2)
	    * */

		// Conseguimos el ID del término a partir del slug
		$getTerm = get_term_by('slug',$term ,$taxonomy);
		$term_id = $getTerm->term_id;

		// Añadimos el metadato al término
		add_term_meta($term_id,'alergeno-imagen',$attach_id);

		return $attach_id;

}

function jmd_insertar_alergenos($taxonomy, $object_type, $args){
	if ( 'alergeno' == $taxonomy ) {
		$alergenos = ['altramuces', 'apio', 'cacahuetes', 'crustaceos', 'frutos_cascara','gluten','huevos','lacteos', 'moluscos','mostaza', 'pescado', 'sesamo', 'soja', 'sulfitos'];
		//$url = plugin_dir_url( __FILE__ ) . 'alergenos/altramuces.png';
		foreach ($alergenos as $alergeno) {
			$url = plugin_dir_url( __FILE__ ) . 'alergenos/'.$alergeno.'.png';
			$term = $alergeno;
			//$taxonomy = 'alergeno';
			if (!term_exists($term,$taxonomy)){
				$id = jmd_insert_term_and_metaimage($url,$term,$taxonomy);
				//error_log( print_r( $id, true)  );
			}
		}
	}
}
add_action( 'registered_taxonomy', 'jmd_insertar_alergenos',10,3);
