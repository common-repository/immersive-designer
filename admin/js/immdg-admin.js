(function ($) {
	'use strict';
	jQuery(function ($) {
		// Set all variables to be used in scope
		var frame;

		let tr_list = $("#idg-form-table tbody tr");
		tr_list.each(function () {
			//remove tr.
			$(".wms_rm_tr").click(function () {
				$(this).closest('tr').remove();
			});
		});

		let tr_part_list = $("#idg-part-config-table tbody tr");
		tr_part_list.each(function () {
			//remove tr of part config.
			$(".wms_part_rm_tr").click(function () {
				$(this).closest('tr').remove();
			});
		});

		$("a[class*='upload-custom-icon']").each(function () {
			$(this).click(function (e) {
				let get_tr = $(this).closest('tr');
				let tr_key = get_tr.data('lastkey');
				e.preventDefault();
				let img_container = $('#show-icon-container' + tr_key);
				let img_id_input = $('#icon-input' + tr_key);
				let add_img_link = $('.upload-custom-icon' + tr_key);
				let del_img_link = $('.delete-custom-icon' + tr_key);
				setIcon(img_container, img_id_input, add_img_link, del_img_link);
			});
		});

		$("a[class*='delete-custom-icon']").each(function () {
			$(this).click(function (e) {
				let get_tr = $(this).closest('tr');
				let tr_key = get_tr.data('lastkey');
				e.preventDefault();
				let img_container = $('#show-icon-container' + tr_key);
				let img_id_input = $('#icon-input' + tr_key);
				let add_img_link = $('.upload-custom-icon' + tr_key);
				let del_img_link = $('.delete-custom-icon' + tr_key);
				removeIcon(img_container, img_id_input, add_img_link, del_img_link);
			});
		});

		$("select[id*='idg_main_config_type']").each(function (i) {
			$(this).on('change', function () {
				let get_tr = $(this).closest('tr');
				let tr_key = get_tr.data('lastkey');
				let type_val = $(this).val();
				let add_for_calcul = create_operators(tr_key, type_val);
				$('#idg_config_type_container_' + tr_key).html(add_for_calcul);

				$(".upload-type-assoc-file" + tr_key).click(function (e) {
					e.preventDefault();
					let type_assoc_container = $('#type-assoc-container' + tr_key);
					let type_assoc_input = $('#type-assoc-input' + tr_key);
					let add_assoc_link = $('.upload-type-assoc-file' + tr_key);
					let del_assoc_link = $('.delete-type-assoc-file' + tr_key);
					set_assoc_type_file(type_assoc_container, type_assoc_input, add_assoc_link, del_assoc_link);
				});

				$(".delete-type-assoc-file" + tr_key).click(function (e) {
					e.preventDefault();
					let type_assoc_container = $('#type-assoc-container' + tr_key);
					let type_assoc_input = $('#type-assoc-input' + tr_key);
					let add_assoc_link = $('.upload-type-assoc-file' + tr_key);
					let del_assoc_link = $('.delete-type-assoc-file' + tr_key);
					remove_assoc_type_file(type_assoc_container, type_assoc_input, add_assoc_link, del_assoc_link);
				});
			});
		});

		// part config meta.
		$("a[class*='upload-custom-config-part-icon']").each(function () {
			$(this).click(function (e) {
				let get_tr = $(this).closest('tr');
				let tr_key = get_tr.data('lastkey');
				e.preventDefault();
				let img_container = $('#show-config-part-icon-container' + tr_key);
				let img_id_input = $('#icon-config-part-input' + tr_key);
				let add_img_link = $('.upload-custom-config-part-icon' + tr_key);
				let del_img_link = $('.delete-custom-config-part-icon' + tr_key);
				setIcon(img_container, img_id_input, add_img_link, del_img_link);
			});
		});

		$("a[class*='delete-custom-config-part-icon']").each(function () {
			$(this).click(function (e) {
				let get_tr = $(this).closest('tr');
				let tr_key = get_tr.data('lastkey');
				e.preventDefault();
				let img_container = $('#show-config-part-icon-container' + tr_key);
				let img_id_input = $('#icon-config-part-input' + tr_key);
				let add_img_link = $('.upload-custom-config-part-icon' + tr_key);
				let del_img_link = $('.delete-custom-config-part-icon' + tr_key);
				removeIcon(img_container, img_id_input, add_img_link, del_img_link);
			});
		});

		$("select[id*='idg_part_config_type']").each(function (i) {
			$(this).on('change', function () {
				let get_tr = $(this).closest('tr');
				let tr_key = get_tr.data('lastkey');
				let type_val = $(this).val();
				let add_for_calcul = create_config_part_operators(tr_key, type_val);
				$('#idg_part_config_container_' + tr_key).html(add_for_calcul);

				$(".upload-custom-config-part-assoc-file" + tr_key).click(function (e) {
					e.preventDefault();
					let type_assoc_container = $('#type-config-part-assoc-container' + tr_key);
					let type_assoc_input = $('#type-config-part-assoc-input' + tr_key);
					let add_assoc_link = $('.upload-custom-config-part-assoc-file' + tr_key);
					let del_assoc_link = $('.delete-custom-config-part-assoc-file' + tr_key);
					set_assoc_type_file(type_assoc_container, type_assoc_input, add_assoc_link, del_assoc_link);
				});

				$(".delete-custom-config-part-assoc-file" + tr_key).click(function (e) {
					e.preventDefault();
					let type_assoc_container = $('#type-config-part-assoc-container' + tr_key);
					let type_assoc_input = $('#type-config-part-assoc-input' + tr_key);
					let add_assoc_link = $('.upload-custom-config-part-assoc-file' + tr_key);
					let del_assoc_link = $('.delete-custom-config-part-assoc-file' + tr_key);
					remove_assoc_type_file(type_assoc_container, type_assoc_input, add_assoc_link, del_assoc_link);
				});
			});
		});

		$("#idg-add-tr-form").on('click', function () {
			let tr_last = $("#idg-form-table tbody tr:last");
			let last_tr_key = tr_last.data('lastkey');
			if (isNaN(last_tr_key)) {
				last_tr_key = 0;
			} else {
				last_tr_key = last_tr_key + 1;
			}

			let form_add = create_content(last_tr_key);
			$("#idg-form-table tbody").append(form_add);

			$(".upload-custom-icon" + last_tr_key).click(function (e) {
				e.preventDefault();
				let img_container = $('#show-icon-container' + last_tr_key);
				let img_id_input = $('#icon-input' + last_tr_key);
				let add_img_link = $('.upload-custom-icon' + last_tr_key);
				let del_img_link = $('.delete-custom-icon' + last_tr_key);
				setIcon(img_container, img_id_input, add_img_link, del_img_link);
			});

			$(".delete-custom-icon" + last_tr_key).click(function (e) {
				e.preventDefault();
				let img_container = $('#show-icon-container' + last_tr_key);
				let img_id_input = $('#icon-input' + last_tr_key);
				let add_img_link = $('.upload-custom-icon' + last_tr_key);
				let del_img_link = $('.delete-custom-icon' + last_tr_key);
				removeIcon(img_container, img_id_input, add_img_link, del_img_link);
			});

			//remove tr.
			$(".wms_rm_tr").click(function () {
				$(this).closest('tr').remove();
			});

			$("#idg_main_config_type" + last_tr_key).on('change', function () {
				let type_val = $(this).val();
				let add_for_calcul = create_operators(last_tr_key, type_val);
				$('#idg_config_type_container_' + last_tr_key).html(add_for_calcul);

				$(".upload-type-assoc-file" + last_tr_key).click(function (e) {
					e.preventDefault();
					let type_assoc_container = $('#type-assoc-container' + last_tr_key);
					let type_assoc_input = $('#type-assoc-input' + last_tr_key);
					let add_assoc_link = $('.upload-type-assoc-file' + last_tr_key);
					let del_assoc_link = $('.delete-type-assoc-file' + last_tr_key);
					set_assoc_type_file(type_assoc_container, type_assoc_input, add_assoc_link, del_assoc_link);
				});

				$(".delete-type-assoc-file" + last_tr_key).click(function (e) {
					e.preventDefault();
					let type_assoc_container = $('#type-assoc-container' + last_tr_key);
					let type_assoc_input = $('#type-assoc-input' + last_tr_key);
					let add_assoc_link = $('.upload-type-assoc-file' + last_tr_key);
					let del_assoc_link = $('.delete-type-assoc-file' + last_tr_key);
					remove_assoc_type_file(type_assoc_container, type_assoc_input, add_assoc_link, del_assoc_link);
				});
			});
		});

		// config part meta.

		$("#idg-add-tr-part-config-form").on('click', function () {
			let part_config_tr_last = $("#idg-part-config-table tbody tr:last");
			let part_config_last_key = part_config_tr_last.data('lastkey');
			if (isNaN(part_config_last_key)) {
				part_config_last_key = 0;
			} else {
				part_config_last_key = part_config_last_key + 1;
			}

			let form_add = create_config_part_content(part_config_last_key);
			$("#idg-part-config-table tbody").append(form_add);

			$(".upload-custom-config-part-icon" + part_config_last_key).click(function (e) {
				e.preventDefault();
				let img_container = $('#show-config-part-icon-container' + part_config_last_key);
				let img_id_input = $('#icon-config-part-input' + part_config_last_key);
				let add_img_link = $('.upload-custom-config-part-icon' + part_config_last_key);
				let del_img_link = $('.delete-custom-config-part-icon' + part_config_last_key);
				setIcon(img_container, img_id_input, add_img_link, del_img_link);
			});

			$(".delete-custom-config-part-icon" + part_config_last_key).click(function (e) {
				e.preventDefault();
				let img_container = $('#show-config-part-icon-container' + part_config_last_key);
				let img_id_input = $('#icon-config-part-input' + part_config_last_key);
				let add_img_link = $('.upload-custom-config-part-icon' + part_config_last_key);
				let del_img_link = $('.delete-custom-config-part-icon' + part_config_last_key);
				removeIcon(img_container, img_id_input, add_img_link, del_img_link);
			});

			//remove tr.
			$(".wms_part_rm_tr").click(function () {
				$(this).closest('tr').remove();
			});

			$("#idg_part_config_type" + part_config_last_key).on('change', function () {
				let type_val = $(this).val();
				let add_for_calcul = create_config_part_operators(part_config_last_key, type_val);
				$('#idg_part_config_container_' + part_config_last_key).html(add_for_calcul);

				$(".upload-custom-config-part-assoc-file" + part_config_last_key).click(function (e) {
					e.preventDefault();
					let type_assoc_container = $('#type-config-part-assoc-container' + part_config_last_key);
					let type_assoc_input = $('#type-config-part-assoc-input' + part_config_last_key);
					let add_assoc_link = $('.upload-custom-config-part-assoc-file' + part_config_last_key);
					let del_assoc_link = $('.delete-custom-config-part-assoc-file' + part_config_last_key);
					set_assoc_type_file(type_assoc_container, type_assoc_input, add_assoc_link, del_assoc_link);
				});

				$(".delete-custom-config-part-assoc-file" + part_config_last_key).click(function (e) {
					e.preventDefault();
					let type_assoc_container = $('#type-config-part-assoc-container' + part_config_last_key);
					let type_assoc_input = $('#type-config-part-assoc-input' + part_config_last_key);
					let add_assoc_link = $('.upload-custom-config-part-assoc-file' + part_config_last_key);
					let del_assoc_link = $('.delete-custom-config-part-assoc-file' + part_config_last_key);
					remove_assoc_type_file(type_assoc_container, type_assoc_input, add_assoc_link, del_assoc_link);
				});
			});
		});

		function create_content(p) {
			let content = '<tr data-lastkey="' + p + '"><td><input type="text" name="immdg-options[idg_main_config][' + p + '][idg_main_config_name]"/><br />' +
				'</td><td><select id="idg_main_config_type' + p + '" name="immdg-options[idg_main_config][' + p + '][idg_main_config_type]" >' +
				'<option value="Color">Color</option>' +
				'<option value="Material" disabled>Material(not available)</option><option value="Texture" disabled>Texture (not available)</option></select></td>' +
				'<td><div id="idg_config_type_container_' + p + '"><input type="color" name="immdg-options[idg_main_config][' + p + '][idg_main_config_type_assoc]"/></div></td>' +
				'<td><div class="image-upload"><div id="show-icon-container' + p + '"></div>' +
				'<input id="icon-input' + p + '" type="hidden" value=" " name="immdg-options[idg_main_config][' + p + '][idg_main_config_icon]" />' +
				'<p class="hide-if-no-js"><a class="upload-custom-icon' + p + '" href="' + idg_vars.iframe_url + '">' +
				'Set custom image</a><a class="delete-custom-icon' + p + ' hidden" href="#">Remove this image</a></p></div></td>' +
				'<td class="wms_rm_tr">x<br /></td>' +
				'<td><input type="number" step="0.1" name="immdg-options[idg_main_config][' + p + '][idg_main_config_price]" /></td></tr>';
			return content;
		}

		function create_operators(p, val) {
			let content = '';

			if (0 == 'color'.localeCompare(val.toLowerCase())) {
				content = '<input type="color" name="immdg-options[idg_main_config][' + p + '][idg_main_config_type_assoc]"/>';
			} else if (0 == 'material'.localeCompare(val.toLowerCase()) || 0 == 'texture'.localeCompare(val.toLowerCase())) {
				content = '<div><div id="type-assoc-container' + p + '"></div>' +
					'<input id="type-assoc-input' + p + '" type="hidden" value=" " name="immdg-options[idg_main_config][' + p + '][idg_main_config_type_assoc]" />' +
					'<p class="hide-if-no-js"><a class="upload-type-assoc-file' + p + '" href="' + idg_vars.iframe_url + '">' +
					'Set file</a><a class="delete-type-assoc-file' + p + ' hidden" href="#">Remove file</a></p></div>';
			}
			return content;
		}

		function set_assoc_type_file(type_assoc_container, type_assoc_input, add_assoc_link, del_assoc_link) {
			frame = wp.media({
				title: 'Select or Upload Media Of Your Chosen',
				button: {
					text: 'Use this media'
				},
				multiple: false
			});

			// When an image is selected in the media frame...
			frame.on('select', function () {
				// Get media attachment details from the frame state
				var attachment = frame.state().get('selection').first().toJSON();

				// Send the attachment URL to our custom image input field.
				type_assoc_container.html('<div>' + attachment.filename + '</div>');

				// Send the attachment id to our hidden input
				type_assoc_input.val(attachment.id);

				// Hide the add image link
				add_assoc_link.addClass('hidden');

				// Unhide the remove image link
				del_assoc_link.removeClass('hidden');
			});
			frame.open();
		}

		function remove_assoc_type_file(type_assoc_container, type_assoc_input, add_assoc_link, del_assoc_link) {
			// Clear out the preview image
			type_assoc_container.html(' ');

			// Send the attachment id to our hidden input
			type_assoc_input.val('');

			// Hide the add image link
			add_assoc_link.removeClass('hidden');

			// Unhide the remove image link
			del_assoc_link.addClass('hidden');
		}

		function setIcon(img_container, img_id_input, add_img_link, del_img_link) {
			frame = wp.media({
				title: 'Select or Upload Media Of Your Chosen',
				button: {
					text: 'Use this media'
				},
				multiple: false  // Set to true to allow multiple files to be selected
			});

			// When an image is selected in the media frame...
			frame.on('select', function () {

				// Get media attachment details from the frame state
				var attachment = frame.state().get('selection').first().toJSON();

				// Send the attachment URL to our custom image input field.
				img_container.html('<img src="' + attachment.url + '" alt=""/>');

				// Send the attachment id to our hidden input
				img_id_input.val(attachment.id);

				// Hide the add image link
				add_img_link.addClass('hidden');

				// Unhide the remove image link
				del_img_link.removeClass('hidden');
			});
			// Finally, open the modal on click
			frame.open();
		}

		function removeIcon(img_container, img_id_input, add_img_link, del_img_link) {
			// Clear out the preview image
			img_container.html(' ');

			// Un-hide the add image link
			add_img_link.removeClass('hidden');

			// Hide the delete image link
			del_img_link.addClass('hidden');

			// Delete the image id from the hidden input
			img_id_input.val('');
		}

		//for config part meta
		function create_config_part_content(p, meta = 'immdg-part-configs', part_config = 'idg_part_config') {
			let content = '<tr data-lastkey="' + p + '"><td><input type="text" name="' + meta + '[' + part_config + '][' + p + '][' + part_config + '_name]"/><br /></td>' +
				'<td><select class ="idg-select-part-config" name="' + meta + '[' + part_config + '][' + p + '][' + part_config + '_choice]" ><option>select option</option></select></td>' +
				'<td><select id="' + part_config + '_type' + p + '" name="' + meta + '[' + part_config + '][' + p + '][' + part_config + '_type]" >' +
				'<option value="Color">Color</option>' +
				'<option value="Material" disabled>Material (not available)</option><option disabled value="Texture">Texture(not available)</option></select></td>' +
				'<td><div id="' + part_config + '_container_' + p + '"><input type="color" name="' + meta + '[' + part_config + '][' + p + '][' + part_config + '_type_assoc]"/></div></td>' +
				'<td><div class="image-upload"><div id="show-config-part-icon-container' + p + '"></div>' +
				'<input id="icon-config-part-input' + p + '" type="hidden" value=" " name="' + meta + '[' + part_config + '][' + p + '][' + part_config + '_icon]" />' +
				'<p class="hide-if-no-js"><a class="upload-custom-config-part-icon' + p + '" href="' + idg_vars.iframe_url + '">' +
				'Set custom image</a><a class="delete-custom-config-part-icon' + p + ' hidden" href="#">Remove this image</a></p></div></td>' +
				'<td class="wms_part_rm_tr">x<br /></td>' +
				'<td><input type="number" step="0.1" name="' + meta + '[' + part_config + '][' + p + '][' + part_config + '_opt_price]" /></td></tr>';
			return content;
		}

		function create_config_part_operators(p, val, meta = 'immdg-part-configs', part_config = 'idg_part_config') {
			let content = '';

			if (0 == 'color'.localeCompare(val.toLowerCase())) {
				content = '<input type="color" name="' + meta + '[' + part_config + '][' + p + '][' + part_config + '_type_assoc]"/>';
			} else if (0 == 'material'.localeCompare(val.toLowerCase()) || 0 == 'texture'.localeCompare(val.toLowerCase())) {
				content = '<div><div id="type-config-part-assoc-container' + p + '"></div>' +
					'<input id="type-config-part-assoc-input' + p + '" type="hidden" value=" " name="' + meta + '[' + part_config + '][' + p + '][' + part_config + '_type_assoc]" />' +
					'<p class="hide-if-no-js"><a class="upload-custom-config-part-assoc-file' + p + '" href="' + idg_vars.iframe_url + '">' +
					'Set file</a><a class="delete-custom-config-part-assoc-file' + p + ' hidden" href="#">Remove file</a></p></div>';
			}
			return content;
		}
	});
})(jQuery);
