
import { OBJLoader } from '../../public/js/threejs/jsm/loaders/OBJLoader.js';
import { OrbitControls } from "../../public/js/threejs/jsm/controls/OrbitControls.js";
import { MTLLoader } from "../../public/js/threejs/jsm/loaders/MTLLoader.js";
(function ($) {
	"use strict";
	var frame;
	let camera, renderer, controls;
	let object;
	const scene = new THREE.Scene();
	var camera_settings = {fov: 75, aspect: 2, near: 0.1, far: 1000};
	var canvas_properties = {width: 300, heigth: 150};
	var scene_properties_to_load = {camera_controls : {position : {x : 0, y : 0, z: 5}, rotation : {x : 0, y : 0, z: 0}}, view_settings : {back_color : "", lock_cam_rot : "", lock_cam_zoom : ""}};
	let current_object;
	let mtl_url="";
	var option_part =[];

	$(document).ready(function () {

		//Get canvas dimensions
		canvas_properties.width = document.getElementById('idgCanvas').getBoundingClientRect().width;
		canvas_properties.heigth = document.getElementById('idgCanvas').getBoundingClientRect().height;

		camera_settings.aspect = canvas_properties.width / canvas_properties.heigth;

		//Get scene properties
		if($("#cam_posx").val() != 0) {
			scene_properties_to_load.camera_controls.position.x = $("#cam_posx").val();
		}
		if($("#cam_posy").val() != 0) {
			scene_properties_to_load.camera_controls.position.y = $("#cam_posy").val();
		}
		if($("#cam_posz").val() != 0) {
			scene_properties_to_load.camera_controls.position.z = $("#cam_posz").val();
		}

		if($("#cam_rotx").val() != 0) {
			scene_properties_to_load.camera_controls.rotation.x = $("#cam_rotx").val();
		}
		if($("#cam_roty").val() != 0) {
			scene_properties_to_load.camera_controls.rotation.y = $("#cam_roty").val();
		}
		if($("#cam_rotz").val() != 0) {
			scene_properties_to_load.camera_controls.rotation.z = $("#cam_rotz").val();
		}
		scene_properties_to_load.view_settings.back_color = $("#idg-background-color").val();

		camera = new THREE.PerspectiveCamera(camera_settings.fov, camera_settings.aspect, camera_settings.near, camera_settings.far);
		//Edit Camera position and rotation
		camera.position.set(scene_properties_to_load.camera_controls.position.x, scene_properties_to_load.camera_controls.position.y, scene_properties_to_load.camera_controls.position.z);
		camera.rotation.x = scene_properties_to_load.camera_controls.rotation.x;
		camera.rotation.y = scene_properties_to_load.camera_controls.rotation.y;
		camera.rotation.z = scene_properties_to_load.camera_controls.rotation.z;

		//init scene
		scene.background = new THREE.Color( scene_properties_to_load.view_settings.back_color );

		const skyColor = 0x9ca8af;  // light
		const groundColor = 0x000000;  // brownish orange
		const intensity = 0.5;
		const light = new THREE.HemisphereLight(skyColor, groundColor, intensity);
		scene.add(light);


		renderer = new THREE.WebGLRenderer({ canvas: idgCanvas });
		renderer.setSize(canvas_properties.width , canvas_properties.heigth);
		controls = new OrbitControls( camera, renderer.domElement);

		if($("#idg-lock-camrot").is(':checked')) {
			controls.enableRotate = false;
		}
		if($("#idg-lock-camzoom").is(':checked')) {
			controls.enableZoom = false;
		}

		controls.addEventListener( 'change', render_controls );

		if($("#idg-model-path").val() != ""){
			var model_path = $("#idg-model-path").val();
			var material_path = $("#idg-material-path").val();
			init_loadobjmodel_viewer(model_path, material_path);
		}else{
			add_basic_object_toscene();
		}

		$("#idg-btn-upload-model").click(function (e) {
			e.preventDefault();
			//create the media frame
			frame = wp.media({
				title: "Select or Upload Your 3D model",
				button: {
					text: "Use this media",
				},
				multiple: false, // Set to true to allow multiple files to be selected
			});

			// Finally, open the modal
			frame.open();

			frame.on("select", function () {
				// Get media attachment details from the frame state
				var attachment = frame.state().get("selection").first().toJSON();
				init_loadobjmodel_viewer(attachment.url, mtl_url);
				empty_all_option();
			});
		});

		$("#idg-btn-upload-material").click(function (e) {
			e.preventDefault();
			//create the media frame
			frame = wp.media({
				title: "Select or Upload Your Material",
				button: {
					text: "Use this media",
				},
				multiple: false, // Set to true to allow multiple files to be selected
			});

			// Finally, open the modal
			frame.open();

			frame.on("select", function () {
				// Get media attachment details from the frame state
				var attachment = frame.state().get("selection").first().toJSON();
				mtl_url = attachment.url;
				$("#idg-material-path").val(mtl_url);
			});
		});

		$("#idg-background-color").change(function () {
			var hex = $(this).val();
			scene.background = new THREE.Color( hex );
			render();
		});

		$("#idg-lock-camrot").change(function(){
			controls.enableRotate = !controls.enableRotate;
		});

		$("#idg-lock-camzoom").change(function(){
			controls.enableZoom = !controls.enableZoom;
		});

		$("#idg-model-type").change(function(){
			if ($(this).val() != 'obj') {
				$("#idg-btn-upload-material").hide();
			}else{
				$("#idg-btn-upload-material").show();
			}
		})

		$(".cam_pos").change(function(){
			var cam_posx =  $("#cam_posx").val() ;
			var cam_posy =  $("#cam_posy").val() ;
			var cam_posz = $("#cam_posz").val() ;
			camera.position.set(cam_posx, cam_posy, cam_posz);
			render();
		});

		$("#cam_rotx").change(function(){
			var cam_rot = $("#cam_rotx").val();
			camera.rotateOnAxis((new THREE.Vector3(1, 0, 0)).normalize(), cam_rot);
			render();
		});

		$("#cam_roty").change(function(){
			var cam_rot = $("#cam_roty").val();
			camera.rotateOnAxis((new THREE.Vector3(0, 1, 0)).normalize(), cam_rot);
			render();
		});

		$("#cam_rotz").change(function(){
			var cam_rot = $("#cam_rotz").val();
			camera.rotateOnAxis((new THREE.Vector3(0, 0, 1)).normalize(), cam_rot);
			render();
		});

		$("#idg-add-tr-part-config-form").click(function(e){
			load_part_options();
		});

		$("#idg-btn-reset-model").click(function(e){
			e.preventDefault();
			scene.remove(current_object);
			add_basic_object_toscene();
			render();
			$("#idg-material-path").val("");
			$("#idg-model-path").val("");
			empty_all_option();
		});
		render();
	});

	function onError() {}

	function onProgress( xhr ) {

		if ( xhr.lengthComputable ) {
			const percentComplete = xhr.loaded / xhr.total * 100;
			//console.log( 'model ' + Math.round( percentComplete, 2 ) + '% downloaded' );
		}
	}

	function render() {
		renderer.render( scene, camera );
	}

	function render_controls() {
		render();
		$("#cam_posx").val(camera.position.x.toFixed(4));
		$("#cam_posy").val(camera.position.y.toFixed(4));
		$("#cam_posz").val(camera.position.z.toFixed(4));
		$("#cam_rotx").val(camera.rotation.x.toFixed(4));
		$("#cam_roty").val(camera.rotation.y.toFixed(4));
		$("#cam_rotz").val(camera.rotation.z.toFixed(4));
	}

	function init_loadobjmodel_viewer(path_url, _mtl_url){
		function loadModel() {
			object.traverse( function ( child ) {
				//if ( child.isMesh ) child.material.map = texture;
				if (child.material){
					//console.log(child.material.name);
				}
			} );
			object.position.y = 0;
			scene.remove(current_object);
			scene.add( object );
			current_object = object;
			render();

			option_part = [];
			scene.traverse(function(cur_object){
				if(cur_object.material){
					if(cur_object.material.length>0){
						cur_object.material.forEach(element => {
							option_part.push(element.name);
						});
					}else{
						option_part.push(cur_object.material.name);
					}
				}
			});

			load_part_options();
		}


		const manager = new THREE.LoadingManager( loadModel );
		manager.onProgress = function ( item, loaded, total ) {	
			//console.log( item, loaded, total );
			$("#idg-model-path").val(path_url);
		};

		const loader = new OBJLoader( manager );
		const mtlLoader = new MTLLoader();
		if(_mtl_url != ""){
			mtlLoader.load(_mtl_url, (mtl)=>{
				mtl.preload();
				loader.setMaterials(mtl);
				loader.load( path_url, function ( obj ) {
					object = obj;
				}, onProgress, onError );
				
			});
		}

	}

	function load_part_options(){
		if (option_part.length>0){
			var opt_list ="";
			var un_option_part  = option_part.filter(function(itm, i, a) {
				return i == option_part.indexOf(itm);
			});
			un_option_part.forEach(element => {
				opt_list += `<option value="${element}"> ${element}</option>`;
			});

			$(".idg-select-part-config").each(function(){
				var length = $(this).children('option').length;
				if(length <= 2){
					$(this).append(opt_list);
				}
				var text_selected = $(this).val();
				$(this).children('option').each(function(){
					if($(this).val() == text_selected) {
						if(! $(this).is(':selected')){
							$(this).remove();
						}
					}
				});
			});
		}
	}

	function add_basic_object_toscene(){
		const boxWidth = 1;
		const boxHeight = 1;
		const boxDepth = 1;
		const geometry = new THREE.BoxGeometry(boxWidth, boxHeight, boxDepth);	  
		const material = new THREE.MeshBasicMaterial({color: 0x44aa88});  // greenish blue	  
		const cube = new THREE.Mesh(geometry, material);
		current_object  = cube;
		scene.add(cube);
	}

	function empty_all_option() {
		$(".idg-select-part-config").each(function(){
			$(this).empty();
			$(this).append('<option selected="selected">select option</option>');
		});
	}
})(jQuery);
