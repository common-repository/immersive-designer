import { OBJLoader } from './threejs/jsm/loaders/OBJLoader.js';
import { OrbitControls } from "./threejs/jsm/controls/OrbitControls.js";
import { MTLLoader } from "./threejs/jsm/loaders/MTLLoader.js";
(function( $ ) {
	'use strict';
	let object;
	const scene = new THREE.Scene();
	var camera_settings = {fov: 75, aspect: 2, near: 0.1, far: 1000};
	var canvas_properties = {width: 300, heigth: 150};
	var scene_properties_to_load = {camera_controls : {position : {x : 0, y : 0, z: 0}, rotation : {x : 0, y : 0, z: 0}}, view_settings : {back_color : "", lock_cam_rot : "", lock_cam_zoom : ""}};
	let camera, renderer, controls;

	$(document).ready(function(){
		
		//init scene
		const ambientLight = new THREE.AmbientLight( 0xcccccc, 0.4 );
		scene.add( ambientLight );
		scene.background = new THREE.Color( 0xdcdfe3 );
		
		canvas_properties.width = document.getElementById('idgApp').getBoundingClientRect().width;
		canvas_properties.heigth = document.getElementById('idgApp').getBoundingClientRect().height;

		camera_settings.aspect = canvas_properties.width / canvas_properties.heigth;
		camera = new THREE.PerspectiveCamera(camera_settings.fov, camera_settings.aspect, camera_settings.near, camera_settings.far);
		camera.position.z = 5;

		//Get scene setting (Position)
		scene_properties_to_load.camera_controls.position.x = $("#idg-root-preview").attr("pos_x");
		scene_properties_to_load.camera_controls.position.y = $("#idg-root-preview").attr("pos_y");
		scene_properties_to_load.camera_controls.position.z = $("#idg-root-preview").attr("pos_z");

		//Get scene setting (Rotation)
		scene_properties_to_load.camera_controls.rotation.x = $("#idg-root-preview").attr("rot_x");
		scene_properties_to_load.camera_controls.rotation.y = $("#idg-root-preview").attr("rot_y");
		scene_properties_to_load.camera_controls.rotation.z = $("#idg-root-preview").attr("rot_z");

		//Get scene setting (Color background)
		scene_properties_to_load.view_settings.back_color = $("#idg-root-preview").attr("back_color");
		scene_properties_to_load.view_settings.lock_cam_rot = $("#idg-root-preview").attr("cam_rotlock");
		scene_properties_to_load.view_settings.lock_cam_zoom = $("#idg-root-preview").attr("cam_zoomlock");

		//Edit Camera position and rotation
		camera.position.set(scene_properties_to_load.camera_controls.position.x, scene_properties_to_load.camera_controls.position.y, scene_properties_to_load.camera_controls.position.z);
		camera.rotation.x = scene_properties_to_load.camera_controls.rotation.x;
		camera.rotation.y = scene_properties_to_load.camera_controls.rotation.y;
		camera.rotation.z = scene_properties_to_load.camera_controls.rotation.z;

		const skyColor = 0x9ca8af;  // light
		const groundColor = 0x000000;  // brownish orange
		const intensity = 0.5;
		const light = new THREE.HemisphereLight(skyColor, groundColor, intensity);
		scene.background = new THREE.Color( scene_properties_to_load.view_settings.back_color );
		scene.add(light);

		renderer = new THREE.WebGLRenderer({ canvas: idgApp });
		renderer.setSize(canvas_properties.width , canvas_properties.heigth);
		controls = new OrbitControls( camera, renderer.domElement);

		if(scene_properties_to_load.view_settings.lock_cam_rot == "on") {
			controls.enableRotate = false;
		}

		if(scene_properties_to_load.view_settings.lock_cam_zoom == "on") {
			controls.enableZoom = false;
		}

		//controls setings load
		controls.addEventListener( 'change', render );
		render();

		//Get scene info !!!
		var material_path = $("#idg-root-preview").attr("mtl");
		var model_path = $("#idg-root-preview").attr("mdl");
		init_loadobjmodel_viewer(model_path, material_path);

		$(".idg-option-thumb").click(function(){

			var target_mat  = $(this).attr("apply-part");
			var color_to_set  = $(this).attr("apply-color");
			var selected_class = target_mat + '-selected-opt';
			
			$("#"+target_mat+"-thumb").css("background-color", color_to_set);

			deselectOptionGroupbyClass(selected_class);
			$(this).addClass(selected_class);
			scene.traverse(function(object){
				if(object.material){
					if(object.material.length>0){
						object.material.forEach(element => {
							if(element.name == target_mat) {
								element.color.set(color_to_set);
								render();
							}
						});
					}else{
						if(object.material.name == target_mat) {
							object.material.color.set(color_to_set);
							render();
						}
					}
				}
			});
			update_configurator_price();
		});
	});

	function onError() {}

	function onProgress( xhr ) {

		if ( xhr.lengthComputable ) {
			const percentComplete = xhr.loaded / xhr.total * 100;
			console.log( 'model ' + Math.round( percentComplete, 2 ) + '% downloaded' );
		}
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
			scene.add( object );
			render();
		}

		const manager = new THREE.LoadingManager( loadModel );
		manager.onProgress = function ( item, loaded, total ) {	
			console.log( item, loaded, total );
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

	//Render the scene
	function render() {
		renderer.render( scene, camera );
	}

	//deselect selected group option
	function deselectOptionGroupbyClass(group_class) {
		$("."+group_class).each(function(){
			if($(this).hasClass(group_class)){
				$(this).removeClass(group_class);
			}
		});
	}

	function update_configurator_price(){
		var base_price = $("#idg-base-price").attr("idg-cart-price") * 1;
		var a_price = 0;
		$("[class$='-selected-opt']").each(function(){
			a_price = a_price + ($(this).attr("opt-price") *1);
			console.log($(this).attr("opt-price"));
		});
		var t_price = base_price + a_price;
		$("#idg-conf-price").text(t_price);
	}


})( jQuery );

