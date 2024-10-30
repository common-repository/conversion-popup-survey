<?php
/**
 * Loads design data by returning JSON
 *
 */
function pops_ajax_load_design()
{
	global $wpdb;

	$pops_nonce = $_POST['nonce'];

	// check to see if the submitted nonce matches with the
	// generated nonce we created earlier
	if (wp_verify_nonce($pops_nonce, 'pops_ajax_nonce') == FALSE)
		die('Oops, you are not allowed to do that!');

	// get the submitted parameters
	$pops_design_id = $_POST['id'];

	//	Get requested design
	$sql = $wpdb->prepare("
			SELECT * FROM `".POPS_DESIGN_TABLE."`
			WHERE `id` = %d
			LIMIT 1",
			array($pops_design_id)
		);
	$pops_design = array();
	$pops_design = $wpdb->get_row($sql, ARRAY_A);

	//	Unserialize array
	$pops_design_un = unserialize($pops_design['design_data']);

	if (count($pops_design) > 0)
	{
		$pops_data = array();

		$pops_data = array(
			'pops_general_width' => $pops_design_un['pops_general_width'],
			'pops_general_height' => $pops_design_un['pops_general_height'],
			'pops_general_location' => $pops_design_un['pops_general_location'],
			'pops_general_margin_tb' => $pops_design_un['pops_general_margin_tb'],
			'pops_general_margin_lr' => $pops_design_un['pops_general_margin_lr'],

			'pops_header_background_color' => $pops_design_un['pops_header_background_color'],
			'pops_header_font_face' => $pops_design_un['pops_header_font_face'],
			'pops_header_font_color' => $pops_design_un['pops_header_font_color'],
			'pops_header_font_size' => $pops_design_un['pops_header_font_size'],
			'pops_header_padding' => $pops_design_un['pops_header_padding'],

			'pops_content_background_color' => $pops_design_un['pops_content_background_color'],
			'pops_content_font_face' => $pops_design_un['pops_content_font_face'],
			'pops_content_font_color' => $pops_design_un['pops_content_font_color'],
			'pops_content_font_size' => $pops_design_un['pops_content_font_size'],
			'pops_content_padding' => $pops_design_un['pops_content_padding'],

			'pops_border_style' => $pops_design_un['pops_border_style'],
			'pops_border_color' => $pops_design_un['pops_border_color'],
			'pops_border_width' => $pops_design_un['pops_border_width'],
			'pops_border_padding' => $pops_design_un['pops_border_padding'],
			'pops_border_background_color' => $pops_design_un['pops_border_background_color'],
		);

		// generate the response
		$pops_response = json_encode($pops_data);

		// response output
		header("Content-Type: application/json");
		echo $pops_response;
	}

	exit;
}
add_action('wp_ajax_pops_ajax_load_design', 'pops_ajax_load_design');
?>