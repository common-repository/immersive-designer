<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Immdg
 * @subpackage Immdg/include
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Immdg
 * @subpackage Immdg/include
 * @author     Bluegamediversion <bluegamediversion@gmail.com>
 */
class Immdg_Config {
	/**
	 * Add metabox to the cpt idg.
	 */
	public function add_idg_config_box() {

		$screens = 'immersive_config';
		add_meta_box(
			'upload-model',
			__( '3D FILE IMPORTER', 'immersive-designer' ),
			array( $this, 'upload_model_meta' ),
			$screens
		);

	}
	/**
	 * Rendering function for upload 3d model.
	 *
	 * @param string $post get the post id in order to save.
	 */
	public function upload_model_meta( $post ) {
		wp_enqueue_media();
		$post_id = get_the_ID();
		$idg     = get_post_meta( $post_id, 'immdg', true );

		if ( isset( $idg ) && ! empty( $idg ) ) {
			// camera position.
			$idg_meta['model-cam-posx'] = $idg['model-cam-posx'];
			$idg_meta['model-cam-posy'] = $idg['model-cam-posy'];
			$idg_meta['model-cam-posz'] = $idg['model-cam-posz'];
			// camera rotation.
			$idg_meta['model-cam-rotx']      = $idg['model-cam-rotx'];
			$idg_meta['model-cam-roty']      = $idg['model-cam-roty'];
			$idg_meta['model-cam-rotz']      = $idg['model-cam-rotz'];
			$idg_meta['model-path']          = $idg['model-path'];
			$idg_meta['material-path']       = $idg['material-path'];
			$idg_meta['model-lock-cam-zoom'] = ( isset( $idg['model-lock-cam-zoom'] ) ? $idg['model-lock-cam-zoom'] : 'no' );
			$idg_meta['model-lock-cam-rot']  = ( isset( $idg['model-lock-cam-rot'] ) ? $idg['model-lock-cam-rot'] : 'no' );

			$idg_meta['model-backg-color'] = $idg['model-backg-color'];
		} else {
			$idg_meta['model-config-name'] = '';
			$idg_meta['model-cam-fov']     = 70;
			$idg_meta['model-cam-near']    = 0.1;
			// camera position.
			$idg_meta['model-cam-posx'] = 0;
			$idg_meta['model-cam-posy'] = 0;
			$idg_meta['model-cam-posz'] = 2;
			// camera rotation.
			$idg_meta['model-cam-rotx']      = 0;
			$idg_meta['model-cam-roty']      = 0;
			$idg_meta['model-cam-rotz']      = 0;
			$idg_meta['model-path']          = '';
			$idg_meta['material-path']       = '';
			$idg_meta['model-lock-cam-zoom'] = 'no';
			$idg_meta['model-lock-cam-rot']  = 'no';
			$idg_meta['model-backg-color']   = '#dcdfe3';
		}

		?>
			<div id="model_upload" class="idg">
				<div class="idg-config-name">

					<div>
						<span>Choose model format</span>
						<select name="" id="idg-model-type">
							<option value="obj">OBJ</option>
							<option value="fbx" disabled>FBX (not available)</option>
							<option value="gltf" disabled>GLTF (not available)</option>
						</select>
					</div>

					<div>
						<button id="idg-btn-upload-material" class="idg-button">Upload material(mtl)</button>
						<button id="idg-btn-upload-model" class="idg-button">Upload model</button>	
					</div>

					<div>
						<button id="idg-btn-reset-model" class="idg-button">Reset</button>	
					</div>
					<div class="idg-inline-block">
						<input type="text" id="idg-model-path"  hidden name="immdg[model-path]" value="<?php echo esc_attr( $idg_meta['model-path'] ); ?>">
						<input type="text" id="idg-material-path"  hidden name="immdg[material-path]" value="<?php echo esc_attr( $idg_meta['material-path'] ); ?>">
					</div>
				</div>

				<div id="idg-model-config" class="model_upload_prev idg">
					<div class="model_prev idg" id="model_preview_canvas">
						<canvas id="idgCanvas"></canvas>
					</div>

					<div class="model_prev idg model_settings" id="idg-backgd-setting">
						<div style = "border-bottom-style: ridge;">
							<div>
								<span class="idg-title">CAMERA CONTROLS</span>
								<div class="idgc-cam-opt">
									<label class="idgc-" for="number">Position</label>
									<label class="idgc-"  for="number">x:</label>
									<input class="cam_pos" id="cam_posx" name="immdg[model-cam-posx]" type="number" step="0.0000000000000001" value="<?php echo esc_attr( $idg_meta['model-cam-posx'] ); ?>">
									<label class="idgc-"  for="number">y:</label>
									<input class="cam_pos" id="cam_posy" name="immdg[model-cam-posy]" type="number" step="0.0000000000000001" value="<?php echo esc_attr( $idg_meta['model-cam-posy'] ); ?>">
									<label class="idgc-"  for="number">z:</label>
									<input class="cam_pos" id="cam_posz" name="immdg[model-cam-posz]" type="number" step="0.0000000000000001" value="<?php echo esc_attr( $idg_meta['model-cam-posz'] ); ?>">
								</div>

								<div class="idgc-cam-opt">
									<label class="idgc-" for="number">Rotation</label>
									<label class="idgc-" for="number">x:</label>
									<input class="cam_rot" id="cam_rotx" name="immdg[model-cam-rotx]" type="number" step="0.0000000000000001" value="<?php echo esc_attr( $idg_meta['model-cam-rotx'] ); ?>">
									<label class="idgc-" for="number">y:</label>
									<input class="cam_rot" id="cam_roty" name="immdg[model-cam-roty]" type="number" step="0.0000000000000001" value="<?php echo esc_attr( $idg_meta['model-cam-roty'] ); ?>">
									<label class="idgc-" for="number">z:</label>
									<input class="cam_rot" id="cam_rotz" name="immdg[model-cam-rotz]" type="number" step="0.0000000000000001" value="<?php echo esc_attr( $idg_meta['model-cam-rotz'] ); ?>">
								</div>
							</div>
						</div>
					</div>

					<div class="model_prev idg model_settings" id="environment_renderingconfig">
						<div>
							<span class="idg-title">VIEW SETTINGS</span>
							<div  class="idg-backgd-setting">
								<label for="idg-background-color">VIEW BACKGROUNG COLOR :</label>
								<input type="color" id="idg-background-color" name="immdg[model-backg-color]" value="<?php echo esc_attr( $idg_meta['model-backg-color'] ); ?>"><br><br>
							</div>
						</div>

						<div class="idg-backgd-setting">
							<div >
								<input type="checkbox" id="idg-lock-camrot" name="immdg[model-lock-cam-rot]" 
								<?php
								if ( $idg_meta['model-lock-cam-rot'] != 'no' ) {
									echo 'checked';}
								?>
								>
								<label for="backg-rotate">Lock Camera Rotation</label><br>
							</div> <br>
							<div >
								<input type="checkbox" id="idg-lock-camzoom" name="immdg[model-lock-cam-zoom]" 
								<?php
								if ( $idg_meta['model-lock-cam-zoom'] != 'no' ) {
									echo 'checked';}
								?>
								>
								<label for="backg-zoom">Lock Camera Zooming</label><br>
							</div>
						</div>

					</div>
				</div>
			</div>
		<?php
	}

}
