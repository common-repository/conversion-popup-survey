jQuery(document).ready(function($) {
	$('#pops_load_design').change(function(){
		if ($(this).val() != '') {
			$.post(
				pops_ajax.ajaxurl,
				{
					action: 'pops_ajax_load_design',
					id: $(this).val(),
					nonce: pops_ajax.nonce
				},
				function(data) {
					$('#pops_general_location').val(data.pops_general_location);
					$('#pops_general_width').val(data.pops_general_width);
					$('#pops_general_height').val(data.pops_general_height);
					$('#pops_general_margin_tb').val(data.pops_general_margin_tb);
					$('#pops_general_margin_lr').val(data.pops_general_margin_lr);

					$('#pops_header_background_color').val(data.pops_header_background_color);
					$('#header_background_color_selector div').css('background-color', '#'+data.pops_header_background_color);
					$('#pops_header_font_face').val(data.pops_header_font_face);
					$('#pops_header_font_color').val(data.pops_header_font_color);
					$('#header_font_color_selector div').css('background-color', '#'+data.pops_header_font_color);
					$('#pops_header_font_size').val(data.pops_header_font_size);
					$('#pops_header_padding').val(data.pops_header_padding);

					$('#pops_content_background_color').val(data.pops_content_background_color);
					$('#content_color_selector div').css('background-color', '#'+data.pops_content_background_color);
					$('#pops_content_font_face').val(data.pops_content_font_face);
					$('#pops_content_font_color').val(data.pops_content_font_color);
					$('#content_font_color_selector div').css('background-color', '#'+data.pops_content_font_color);
					$('#pops_content_font_size').val(data.pops_content_font_size);
					$('#pops_content_padding').val(data.pops_content_padding);

					$('#pops_border_width').val(data.pops_border_width);
					$('#pops_border_style').val(data.pops_border_style);
					$('#pops_border_color').val(data.pops_border_color);
					$('#border_color_selector div').css('background-color', '#'+data.pops_border_color);
					$('#pops_border_padding').val(data.pops_border_padding);
					$('#pops_border_background_color').val(data.pops_border_background_color);
					$('#border_background_color_selector div').css('background-color', '#'+data.pops_border_background_color);
				}
			);
		}
	});
});