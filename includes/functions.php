<?php

/**
 * This function create a folder.
 *
 * @param string $dir comment folder that will be create  path.
 * @return void
 */
function idg_create_dir( $dir ) {
	if ( ! is_dir( $dir ) ) {
		if ( ! mkdir( $dir, 0777, true ) ) {
			esc_html_e( 'failed to create directory.', 'immersive-designer' );
			die;
		}
	}
}

/**
 * This function sanitize input field.
 *
 * @param array $data array of array(or simple array) that will be sanitize.
 * @return array
 */
function immdg_sanitize_text_field_array_of_array( $data ) {
	$to_return = array();
	foreach ( $data as $key => $value ) {
		if ( is_array( $value ) ) {
			$to_return[ $key ] = immdg_sanitize_text_field_array_of_array( wp_unslash( $value ) );
		} else {
			$to_return[ $key ] = sanitize_text_field( wp_unslash( $value ) );
		}
	}
	return $to_return;
}

/**
 * This function return idg configuration parameters
 *
 * @param int $id the post id.
 */
function get_idg_main_config_by_id( $post_id ) {
	$idg = get_post_meta( $post_id, IMMDG_OPTIONS );
	return $idg[0];
}

/**
 * This function return idg configuration parameters
 *
 * @param int $id the post id.
 */
function get_idg_part_config_by_id( $post_id ) {
	$idg            = get_post_meta( $post_id, IMMDG_PART_CONFIG_OPTION );
	$categorize_opt = array();
	if ( isset( $idg[0]['idg_part_config'] ) && ! empty( $idg[0]['idg_part_config'] ) ) {
		foreach ( $idg[0]['idg_part_config'] as $option ) {
			$categorize_opt[ $option['idg_part_config_choice'] ][ $option['idg_part_config_name'] ] = $option;
		}
	}
	return $categorize_opt;
}

/**
 * This function return scene configuration parameters
 *
 * @param int $id the post id.
 */
function get_idg_scene_config_by_id( $post_id ) {
	$idg = get_post_meta( $post_id, 'immersive-designer' );
	return $idg[0];
}

function immdg_update_option( array $options ) {
	foreach ( $options as $key => $value ) {
		update_option( $key, $value );
	}
}
