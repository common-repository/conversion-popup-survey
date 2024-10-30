<?php
/**
 * First check if ID exist with requested ID
 *
 */
$pops_design_id = isset($_GET['id']) ? $_GET['id'] : '0';

$sql = $wpdb->prepare(
	"SELECT COUNT(*) AS `count` FROM ".POPS_DESIGN_TABLE."
	WHERE `id` = %d",
	array($pops_design_id)
);
$result = '0';
$result = $wpdb->get_var($sql);

if ($result != '1')
{
?>
<div class="wrap">
	<h2>Edit Design</h2>
	<div id="pops_top_menu">
		<?php include('top_menu.php'); ?>
	</div>
	<div class="error"><p><strong>Oops, that design doesn&#39;t exist.</strong></p><p><a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=pops_design">Click here</a> to go back to the design overview page.</p></div>
</div>
<?php
}
else
{
	$pops_errors = array();
	$pops_success = '';
	$pops_error_found = FALSE;

	$sql = $wpdb->prepare("
		SELECT *
		FROM `".POPS_DESIGN_TABLE."`
		WHERE `id` = %d
		LIMIT 1
		",
		array($pops_design_id)
	);
	$pops_design_info = array();
	$pops_design_info = $wpdb->get_row($sql, ARRAY_A);

	//	Unserialize array
	$pops_design_info_un = unserialize($pops_design_info['design_data']);

	/**
	 * Preset the form fields
	 *
	 */
	$form = array(
		'pops_design_name' => $pops_design_info['name'],

		'pops_general_width' => $pops_design_info_un['pops_general_width'],
		'pops_general_height' => $pops_design_info_un['pops_general_height'],
		'pops_general_location' => $pops_design_info_un['pops_general_location'],
		'pops_general_margin_tb' => $pops_design_info_un['pops_general_margin_tb'],
		'pops_general_margin_lr' => $pops_design_info_un['pops_general_margin_lr'],

		'pops_header_background_color' => $pops_design_info_un['pops_header_background_color'],
		'pops_header_font_face' => $pops_design_info_un['pops_header_font_face'],
		'pops_header_font_color' => $pops_design_info_un['pops_header_font_color'],
		'pops_header_font_size' => $pops_design_info_un['pops_header_font_size'],
		'pops_header_padding' => $pops_design_info_un['pops_header_padding'],

		'pops_content_background_color' => $pops_design_info_un['pops_content_background_color'],
		'pops_content_font_face' => $pops_design_info_un['pops_content_font_face'],
		'pops_content_font_color' => $pops_design_info_un['pops_content_font_color'],
		'pops_content_font_size' => $pops_design_info_un['pops_content_font_size'],
		'pops_content_padding' => $pops_design_info_un['pops_content_padding'],

		'pops_border_style' => $pops_design_info_un['pops_border_style'],
		'pops_border_color' => $pops_design_info_un['pops_border_color'],
		'pops_border_width' => $pops_design_info_un['pops_border_width'],
		'pops_border_padding' => $pops_design_info_un['pops_border_padding'],
		'pops_border_background_color' => $pops_design_info_un['pops_border_background_color'],
	);

	/**
	 * Form submitted, check the data
	 *
	 */
	if (isset($_POST['form_submit']) && $_POST['form_submit'] == 'yes')
	{
		//	Just a little ;) security thingy that wordpress offers us
		check_admin_referer('pops_edit_design');

		$form['pops_design_name'] = isset($_POST['pops_design_name']) ? $_POST['pops_design_name'] : '';
		if ($form['pops_design_name'] == '') {	$pops_errors[] = 'Please enter the name for this design'; $pops_error_found = TRUE; }

		//	General
		$form['pops_general_width'] = isset($_POST['pops_general_width']) ? $_POST['pops_general_width'] : '';
		if ($form['pops_general_width'] == '') {	$pops_errors[] = 'Please set the width of the popup'; $pops_error_found = TRUE; }

		$form['pops_general_height'] = isset($_POST['pops_general_height']) ? $_POST['pops_general_height'] : '';
		if ($form['pops_general_height'] == '') {	$pops_errors[] = 'Please set the height of the popup'; $pops_error_found = TRUE; }

		$form['pops_general_location'] = isset($_POST['pops_general_location']) ? $_POST['pops_general_location'] : '';
		if ($form['pops_general_location'] == '') {	$pops_errors[] = 'Please select the position of the popup'; $pops_error_found = TRUE; }

		$form['pops_general_margin_tb'] = isset($_POST['pops_general_margin_tb']) ? $_POST['pops_general_margin_tb'] : '';
		if ($form['pops_general_margin_tb'] == '') {	$pops_errors[] = 'Please set the top/bottom margin'; $pops_error_found = TRUE; }

		$form['pops_general_margin_lr'] = isset($_POST['pops_general_margin_lr']) ? $_POST['pops_general_margin_lr'] : '';
		if ($form['pops_general_margin_lr'] == '') {	$pops_errors[] = 'Please select the left/right margin'; $pops_error_found = TRUE; }

		//	Header
		$form['pops_header_background_color'] = isset($_POST['pops_header_background_color']) ? $_POST['pops_header_background_color'] : '';
		if ($form['pops_header_background_color'] == '') {	$pops_errors[] = 'Please select header background color'; $pops_error_found = TRUE; }

		$form['pops_header_font_face'] = isset($_POST['pops_header_font_face']) ? $_POST['pops_header_font_face'] : '';
		if ($form['pops_header_font_face'] == '') {	$pops_errors[] = 'Please select header font face'; $pops_error_found = TRUE; }

		$form['pops_header_font_color'] = isset($_POST['pops_header_font_color']) ? $_POST['pops_header_font_color'] : '';
		if ($form['pops_header_font_color'] == '') {	$pops_errors[] = 'Please select header font color'; $pops_error_found = TRUE; }

		$form['pops_header_font_size'] = isset($_POST['pops_header_font_size']) ? $_POST['pops_header_font_size'] : '';
		if ($form['pops_header_font_size'] == '') {	$pops_errors[] = 'Please set header font size'; $pops_error_found = TRUE; }

		$form['pops_header_padding'] = isset($_POST['pops_header_padding']) ? $_POST['pops_header_padding'] : '';
		if ($form['pops_header_padding'] == '') {	$pops_errors[] = 'Please set header padding'; $pops_error_found = TRUE; }

		//	Content
		$form['pops_content_background_color'] = isset($_POST['pops_content_background_color']) ? $_POST['pops_content_background_color'] : '';
		if ($form['pops_content_background_color'] == '') {	$pops_errors[] = 'Please select content background color'; $pops_error_found = TRUE; }

		$form['pops_content_font_face'] = isset($_POST['pops_content_font_face']) ? $_POST['pops_content_font_face'] : '';
		if ($form['pops_content_font_face'] == '') { $pops_errors[] = 'Please select content font face'; $pops_error_found = TRUE; }

		$form['pops_content_font_color'] = isset($_POST['pops_content_font_color']) ? $_POST['pops_content_font_color'] : '';
		if ($form['pops_content_font_color'] == '') { $pops_errors[] = 'Please select content font color'; $pops_error_found = TRUE; }

		$form['pops_content_font_size'] = isset($_POST['pops_content_font_size']) ? $_POST['pops_content_font_size'] : '';
		if ($form['pops_content_font_size'] == '') { $pops_errors[] = 'Please set content font size'; $pops_error_found = TRUE; }

		$form['pops_content_padding'] = isset($_POST['pops_content_padding']) ? $_POST['pops_content_padding'] : '';
		if ($form['pops_content_padding'] == '') { $pops_errors[] = 'Please set content padding'; $pops_error_found = TRUE; }

		//	Border
		$form['pops_border_style'] = isset($_POST['pops_border_style']) ? $_POST['pops_border_style'] : '';
		if ($form['pops_border_style'] == '') { $pops_errors[] = 'Please select border style'; $pops_error_found = TRUE; }

		$form['pops_border_color'] = isset($_POST['pops_border_color']) ? $_POST['pops_border_color'] : '';
		if ($form['pops_border_color'] == '') { $pops_errors[] = 'Please select border color'; $pops_error_found = TRUE; }

		$form['pops_border_width'] = isset($_POST['pops_border_width']) ? $_POST['pops_border_width'] : '';
		if ($form['pops_border_width'] == '') { $pops_errors[] = 'Please set border width'; $pops_error_found = TRUE; }

		$form['pops_border_padding'] = isset($_POST['pops_border_padding']) ? $_POST['pops_border_padding'] : '';
		if ($form['pops_border_padding'] == '') { $pops_errors[] = 'Please set border padding'; $pops_error_found = TRUE; }

		$form['pops_border_background_color'] = isset($_POST['pops_border_background_color']) ? $_POST['pops_border_background_color'] : '';
		if ($form['pops_border_background_color'] == '') { $pops_errors[] = 'Please select border background color'; $pops_error_found = TRUE; }

		if ($pops_error_found == FALSE)
		{
			$sql = $wpdb->prepare(
				"UPDATE `".POPS_DESIGN_TABLE."`
				SET `name` = %s,
				`design_data` = %s
				WHERE id = %d
				LIMIT 1",
				array($form['pops_design_name'], serialize($form), $pops_design_info['id'])
			);
			$wpdb->query($sql);

			$pops_success = 'Design successfully updated.';
		}
	}
?>
<div class="wrap">
	<h2>Edit Design</h2>
	<div id="pops_top_menu">
		<?php include('top_menu.php'); ?>
	</div>
<?php
	if ($pops_error_found == TRUE && isset($pops_errors[0]) == TRUE)
	{
?>
	<div class="error"><p><strong><?php echo $pops_errors[0]; ?></strong></p></div>
<?php
	}

	if ($pops_error_found == FALSE && strlen($pops_success) > 0)
	{
?>
	<div class="updated"><p><strong><?php echo $pops_success; ?></strong></p><p><a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=pops_design">Click here</a> to go back to the design overview page.</p></div>
<?php
	}
?>
	<script type="text/javascript">
	/* <![CDATA[ */
	jQuery(document).ready(function($) {
		$('#header_background_color_selector').ColorPicker({
			color: '#<?php echo esc_html(stripslashes($form['pops_header_background_color'])); ?>',
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				$('#header_background_color_selector div').css('background-color', '#' + hex);
				$('#pops_header_background_color').val(hex);
			}
		});

		$('#header_font_color_selector').ColorPicker({
			color: '#<?php echo esc_html(stripslashes($form['pops_header_font_color'])); ?>',
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				$('#header_font_color_selector div').css('background-color', '#' + hex);
				$('#pops_header_font_color').val(hex);
			}
		});

		$('#content_color_selector').ColorPicker({
			color: '#<?php echo esc_html(stripslashes($form['pops_border_color'])); ?>',
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				$('#content_color_selector div').css('background-color', '#' + hex);
				$('#pops_content_background_color').val(hex);
			}
		});

		$('#content_font_color_selector').ColorPicker({
			color: '#<?php echo esc_html(stripslashes($form['pops_content_font_color'])); ?>',
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				$('#content_font_color_selector div').css('background-color', '#' + hex);
				$('#pops_content_font_color').val(hex);
			}
		});

		$('#border_color_selector').ColorPicker({
			color: '#<?php echo esc_html(stripslashes($form['pops_border_color'])); ?>',
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				$('#border_color_selector div').css('background-color', '#' + hex);
				$('#pops_border_color').val(hex);
			}
		});

		$('#border_background_color_selector').ColorPicker({
			color: '#<?php echo esc_html(stripslashes($form['pops_border_background_color'])); ?>',
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				$('#border_background_color_selector div').css('background-color', '#' + hex);
				$('#pops_border_background_color').val(hex);
			}
		});

		$('#pops_preview_design').click(function(){
			$('#pops_popup').attr('pos', $('#pops_general_location').val());
			$('#pops_popup').css('width', $('#pops_general_width').val()+'px');
			if ($('#pops_general_height').val() != '0') {
				$('#pops_popup').css('height', $('#pops_general_height').val()+'px');
			}
			else {
				$('#pops_popup').css('height', 'auto');
			}
			if ($('#pops_general_location').val() == 'tl') {
				$('#pops_popup').css('margin-top', $('#pops_general_margin_tb').val()+'px');
				$('#pops_popup').css('margin-left', $('#pops_general_margin_lr').val()+'px');
			}
			else if ($('#pops_general_location').val() == 'tc') {
				$('#pops_popup').css('margin-top', $('#pops_general_margin_tb').val()+'px');
			}
			else if ($('#pops_general_location').val() == 'tr') {
				$('#pops_popup').css('margin-top', $('#pops_general_margin_tb').val()+'px');
				$('#pops_popup').css('margin-right', $('#pops_general_margin_lr').val()+'px');
			}
			else if ($('#pops_general_location').val() == 'ml') {
				$('#pops_popup').css('margin-left', $('#pops_general_margin_lr').val()+'px');
			}
			else if ($('#pops_general_location').val() == 'mr') {
				$('#pops_popup').css('margin-right', $('#pops_general_margin_lr').val()+'px');
			}
			else if ($('#pops_general_location').val() == 'bl') {
				$('#pops_popup').css('margin-bottom', $('#pops_general_margin_tb').val()+'px');
				$('#pops_popup').css('margin-left', $('#pops_general_margin_lr').val()+'px');
			}
			else if ($('#pops_general_location').val() == 'bc') {
				$('#pops_popup').css('margin-bottom', $('#pops_general_margin_tb').val()+'px');
			}
			else if ($('#pops_general_location').val() == 'br') {
				$('#pops_popup').css('margin-bottom', $('#pops_general_margin_tb').val()+'px');
				$('#pops_popup').css('margin-right', $('#pops_general_margin_lr').val()+'px');
			}

			$('#pops_popup #pops_popup_header').css('background-color', '#'+$('#pops_header_background_color').val());
			$('#pops_popup #pops_popup_header').css('font-family', $('#pops_header_font_face').val());
			$('#pops_popup #pops_popup_header').css('color', '#'+$('#pops_header_font_color').val());
			$('#pops_popup #pops_popup_header').css('font-size', $('#pops_header_font_size').val()+'px');
			$('#pops_popup #pops_popup_header').css('padding', $('#pops_header_padding').val()+'px');

			$('#pops_popup #pops_popup_content').css('background-color', '#'+$('#pops_content_background_color').val());
			$('#pops_popup #pops_popup_content').css('font-family', $('#pops_content_font_face').val());
			$('#pops_popup #pops_popup_content').css('color', '#'+$('#pops_content_font_color').val());
			$('#pops_popup #pops_popup_content').css('font-size', $('#pops_content_font_size').val()+'px');
			$('#pops_popup #pops_popup_content').css('padding', $('#pops_content_padding').val()+'px');

			$('#pops_popup #pops_popup_button').css('background-color', '#'+$('#pops_content_background_color').val());

			$('#pops_popup').css('border-width', $('#pops_border_width').val()+'px');
			$('#pops_popup').css('border-style', $('#pops_border_style').val());
			$('#pops_popup').css('border-color', '#'+$('#pops_border_color').val());
			$('#pops_popup').css('padding', $('#pops_border_padding').val()+'px');
			$('#pops_popup').css('background-color', '#'+$('#pops_border_background_color').val());

			pops_iPOP_init1();
		});

		$('.pops_toggle').toggle(function(){
			$('#pops_toggle_'+$(this).attr('id')).hide();
			$(this).html('+');
			$(this).removeClass('pops_toggle pops_close');
			$(this).addClass('pops_toggle pops_open');
			$(this).parent().removeClass('pops_table_title pops_table_open');
			$(this).parent().addClass('pops_table_title pops_table_close');
			$('#pops_toggle_'+$(this).attr('id')+'_div').hide();
	  	}, function(){
			$('#pops_toggle_'+$(this).attr('id')).show();
			$(this).html('&ndash;');
			$(this).removeClass('pops_toggle pops_open');
			$(this).addClass('pops_toggle pops_close');
			$(this).parent().removeClass('pops_table_title pops_table_close');
			$(this).parent().addClass('pops_table_title pops_table_open');
			$('#pops_toggle_'+$(this).attr('id')+'_div').show();
  		});
	});
	/* ]]> */
	</script>
	<form method="post" action="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=pops_design&amp;sp=edit&amp;id=<?php echo $pops_design_info['id']; ?>">
		<div>
			<input type="hidden" name="form_submit" value="yes"/>
			<input type="hidden" name="pops_header_background_color" id="pops_header_background_color" value="<?php echo esc_html(stripslashes($form['pops_header_background_color'])); ?>"/>
			<input type="hidden" name="pops_header_font_color" id="pops_header_font_color" value="<?php echo esc_html(stripslashes($form['pops_header_font_color'])); ?>"/>
			<input type="hidden" name="pops_content_background_color" id="pops_content_background_color" value="<?php echo esc_html(stripslashes($form['pops_content_background_color'])); ?>"/>
			<input type="hidden" name="pops_content_font_color" id="pops_content_font_color" value="<?php echo esc_html(stripslashes($form['pops_content_font_color'])); ?>"/>
			<input type="hidden" name="pops_border_color" id="pops_border_color" value="<?php echo esc_html(stripslashes($form['pops_border_color'])); ?>"/>
			<input type="hidden" name="pops_border_background_color" id="pops_border_background_color" value="<?php echo esc_html(stripslashes($form['pops_border_background_color'])); ?>"/>
			<?php wp_nonce_field('pops_edit_design'); ?>
		</div>
		<table class="form-table">
	        <tr valign="top">
	        	<th scope="row">Design Name</th>
	        	<td><input type="text" name="pops_design_name" id="pops_design_name" class="regular-text" value="<?php echo esc_html(stripslashes($form['pops_design_name'])); ?>" /></td>
			</tr>
		</table>
		<div class="pops_table_title pops_table_open">General <span class="pops_toggle pops_close" id="general_tb">&ndash;</span></div>
		<div class="pops_form_table_cont" id="pops_toggle_general_tb_div">
			<table class="form-table pops_form_table" id="pops_toggle_general_tb">
		        <tr valign="top">
		        	<th scope="row">Width</th>
		        	<td><input type="text" name="pops_general_width" id="pops_general_width" class="small-text" value="<?php echo esc_html(stripslashes($form['pops_general_width'])); ?>" />px</td>
		        	<th scope="row">Height</th>
		        	<td>
		        		<input type="text" name="pops_general_height" id="pops_general_height" class="small-text" value="<?php echo esc_html(stripslashes($form['pops_general_height'])); ?>" />px
		        		<br />
		        		<span class="description">Set to 0 for auto height</span>
					</td>
				</tr>
		        <tr valign="top">
		        	<th scope="row">Margin top/bottom</th>
		        	<td>
		        		<input type="text" name="pops_general_margin_tb" id="pops_general_margin_tb" class="small-text" value="<?php echo esc_html(stripslashes($form['pops_general_margin_tb'])); ?>" />px
		        		<br />
		        		<span class="description">Ignored when position is set to &quot;Middle center&quot;</span>
					</td>
					<th scope="row">Margin left/right</th>
					<td>
		        		<input type="text" name="pops_general_margin_lr" id="pops_general_margin_lr" class="small-text" value="<?php echo esc_html(stripslashes($form['pops_general_margin_lr'])); ?>" />px
		        		<br />
		        		<span class="description">Ignored when position is set to &quot;Middle center&quot;</span>
					</td>
		        </tr>
		        <tr valign="top">
		        	<th scope="row">Position</th>
		        	<td colspan="3">
		        		<select name="pops_general_location" id="pops_general_location">
		        			<option value="tl"<?php echo $form['pops_general_location'] == 'tl' || $form['pops_general_location'] == '' ? ' selected="selected"' : ''; ?>>Top left</option>
		        			<option value="tc"<?php echo $form['pops_general_location'] == 'tc' ? ' selected="selected"' : ''; ?>>Top center</option>
		        			<option value="tr"<?php echo $form['pops_general_location'] == 'tr' ? ' selected="selected"' : ''; ?>>Top right</option>
		        			<option value="ml"<?php echo $form['pops_general_location'] == 'ml' ? ' selected="selected"' : ''; ?>>Middle left</option>
		        			<option value="mc"<?php echo $form['pops_general_location'] == 'mc' ? ' selected="selected"' : ''; ?>>Middle center</option>
		        			<option value="mr"<?php echo $form['pops_general_location'] == 'mr' ? ' selected="selected"' : ''; ?>>Middle right</option>
		        			<option value="bl"<?php echo $form['pops_general_location'] == 'bl' ? ' selected="selected"' : ''; ?>>Bottom left</option>
		        			<option value="bc"<?php echo $form['pops_general_location'] == 'bc' ? ' selected="selected"' : ''; ?>>Bottom center</option>
		        			<option value="br"<?php echo $form['pops_general_location'] == 'br' ? ' selected="selected"' : ''; ?>>Bottom right</option>
						</select>
					</td>
		        </tr>
		    </table>
		</div>
	    <div class="pops_table_title pops_table_open">Header <span class="pops_toggle pops_close" id="header_tb">&ndash;</span></div>
	    <div class="pops_form_table_cont" id="pops_toggle_header_tb_div">
			<table class="form-table pops_form_table" id="pops_toggle_header_tb">
		        <tr valign="top">
		        	<th scope="row">Background Color</th>
		        	<td><div class="colorSelector" id="header_background_color_selector"><div style="background-color: #<?php echo esc_html(stripslashes($form['pops_header_background_color'])); ?>"></div></div></td>
		        	<th scope="row">Font Face</th>
		        	<td>
		        		<select name="pops_header_font_face" id="pops_header_font_face">
		        			<option value="Arial"<?php echo $form['pops_header_font_face'] == 'Arial' || $form['pops_content_font_face'] == '' ? ' selected="selected"' : ''; ?>>Arial</option>
		        			<option value="Helvetica"<?php echo $form['pops_header_font_face'] == 'Helvetica' ? ' selected="selected"' : ''; ?>>Helvetica</option>
		        			<option value="Times New Roman"<?php echo $form['pops_header_font_face'] == 'Times New Roman' ? ' selected="selected"' : ''; ?>>Times New Roman</option>
		        			<option value="Courier New"<?php echo $form['pops_header_font_face'] == 'Courier New' ? ' selected="selected"' : ''; ?>>Courier New</option>
		        			<option value="Verdana"<?php echo $form['pops_header_font_face'] == 'Verdana' ? ' selected="selected"' : ''; ?>>Verdana</option>
		        			<option value="Georgia"<?php echo $form['pops_header_font_face'] == 'Georgia' ? ' selected="selected"' : ''; ?>>Georgia</option>
		        			<option value="Impact"<?php echo $form['pops_header_font_face'] == 'double' ? ' selected="selected"' : ''; ?>>Impact</option>
						</select>
					</td>
				</tr>
		        <tr valign="top">
		        	<th scope="row">Font Color</th>
		        	<td><div class="colorSelector" id="header_font_color_selector"><div style="background-color: #<?php echo esc_html(stripslashes($form['pops_header_font_color'])); ?>"></div></div></td>
		        	<th scope="row">Font Size</th>
		        	<td><input type="text" name="pops_header_font_size" id="pops_header_font_size" class="small-text" value="<?php echo esc_html(stripslashes($form['pops_header_font_size'])); ?>" />px</td>
				</tr>
		        <tr valign="top">
		        	<th scope="row">Padding</th>
		        	<td colspan="3"><input type="text" name="pops_header_padding" id="pops_header_padding" class="small-text" value="<?php echo esc_html(stripslashes($form['pops_header_padding'])); ?>" />px</td>
		        </tr>
		    </table>
		</div>
		<div class="pops_table_title pops_table_open">Content <span class="pops_toggle pops_close" id="content_tb">&ndash;</span></div>
		<div class="pops_form_table_cont" id="pops_toggle_content_tb_div">
			<table class="form-table pops_form_table" id="pops_toggle_content_tb">
		        <tr valign="top">
		        	<th scope="row">Background Color</th>
		        	<td><div class="colorSelector" id="content_color_selector"><div style="background-color: #<?php echo esc_html(stripslashes($form['pops_content_background_color'])); ?>"></div></div></td>
					<th scope="row">Font Face</th>
		        	<td>
		        		<select name="pops_content_font_face" id="pops_content_font_face">
		        			<option value="Arial"<?php echo $form['pops_content_font_face'] == 'Arial' || $form['pops_content_font_face'] == '' ? ' selected="selected"' : ''; ?>>Arial</option>
		        			<option value="Helvetica"<?php echo $form['pops_content_font_face'] == 'Helvetica' ? ' selected="selected"' : ''; ?>>Helvetica</option>
		        			<option value="Times New Roman"<?php echo $form['pops_content_font_face'] == 'Times New Roman' ? ' selected="selected"' : ''; ?>>Times New Roman</option>
		        			<option value="Courier New"<?php echo $form['pops_content_font_face'] == 'Courier New' ? ' selected="selected"' : ''; ?>>Courier New</option>
		        			<option value="Verdana"<?php echo $form['pops_content_font_face'] == 'Verdana' ? ' selected="selected"' : ''; ?>>Verdana</option>
		        			<option value="Georgia"<?php echo $form['pops_content_font_face'] == 'Georgia' ? ' selected="selected"' : ''; ?>>Georgia</option>
		        			<option value="Impact"<?php echo $form['pops_content_font_face'] == 'double' ? ' selected="selected"' : ''; ?>>Impact</option>
						</select>
					</td>
		        </tr>
		        <tr valign="top">
		        	<th scope="row">Font Color</th>
		        	<td><div class="colorSelector" id="content_font_color_selector"><div style="background-color: #<?php echo esc_html(stripslashes($form['pops_content_font_color'])); ?>"></div></div></td>
					<th scope="row">Font Size</th>
		        	<td><input type="text" name="pops_content_font_size" id="pops_content_font_size" class="small-text" value="<?php echo esc_html(stripslashes($form['pops_content_font_size'])); ?>" />px</td>
		        </tr>
		        <tr valign="top">
		        	<th scope="row">Padding</th>
		        	<td colspan="3"><input type="text" name="pops_content_padding" id="pops_content_padding" class="small-text" value="<?php echo esc_html(stripslashes($form['pops_content_padding'])); ?>" />px</td>
		        </tr>
		    </table>
		</div>
		<div class="pops_table_title pops_table_open">Border <span class="pops_toggle pops_close" id="border_tb">&ndash;</span></div>
		<div class="pops_form_table_cont" id="pops_toggle_border_tb_div">
			<table class="form-table pops_form_table" id="pops_toggle_border_tb">
				<tr valign="top">
		        	<th scope="row">Style</th>
		        	<td>
		        		<select name="pops_border_style" id="pops_border_style">
		        			<option value="none"<?php echo $form['pops_border_style'] == 'none' ? ' selected="selected"' : ''; ?>>None</option>
		        			<option value="solid"<?php echo $form['pops_border_style'] == 'solid' || $form['pops_border_style'] == '' ? ' selected="selected"' : ''; ?>>Solid</option>
		        			<option value="dotted"<?php echo $form['pops_border_style'] == 'dotted' ? ' selected="selected"' : ''; ?>>Dotted</option>
		        			<option value="dashed"<?php echo $form['pops_border_style'] == 'dashed' ? ' selected="selected"' : ''; ?>>Dashed</option>
		        			<option value="double"<?php echo $form['pops_border_style'] == 'double' ? ' selected="selected"' : ''; ?>>Double</option>
						</select>
					</td>
		        	<th scope="row">Color</th>
		        	<td><div class="colorSelector" id="border_color_selector"><div style="background-color: #<?php echo esc_html(stripslashes($form['pops_border_color'])); ?>"></div></div></td>
		        </tr>
		        <tr valign="top">
		        	<th scope="row">Width</th>
		        	<td><input type="text" name="pops_border_width" id="pops_border_width" class="small-text" value="<?php echo esc_html(stripslashes($form['pops_border_width'])); ?>" />px</td>
		        	<th scope="row">Padding</th>
		        	<td><input type="text" name="pops_border_padding" id="pops_border_padding" class="small-text" value="<?php echo esc_html(stripslashes($form['pops_border_padding'])); ?>" />px</td>
		        </tr>
		        <tr valign="top">
		        	<th scope="row">Background Color</th>
		        	<td colspan="3"><div class="colorSelector" id="border_background_color_selector"><div style="background-color: #<?php echo esc_html(stripslashes($form['pops_border_background_color'])); ?>"></div></div></td>
		        </tr>
		    </table>
		</div>
	    <p class="submit"><input type="button" class="button-secondary" id="pops_preview_design" value="Preview Design" /> <input type="submit" class="button-primary" value="Update Design" /></p>
	</form>
</div>
<div id="pops_popup" pos="tl" style="overflow: hidden; display: none; top: 5000; left: 0; z-index: 100;">
	<div style="top: 20px; text-align: right;"><img style="cursor: pointer; position: absolute; top: -2px; right: -2px;" onclick="iPop_close();" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/<?php echo POPS_FOLDER_NAME; ?>/images/closebox.png" alt="Close"/></div>
	<div id="pops_popup_header" style="background-color: #000000; font-family: Arial; color: #ffffff; font-size: 24px; text-align: center; line-height: 26px; padding: 5px;">
		<span>This is just an example of the header title</span>
	</div>
	<form>
		<div id="pops_popup_content" style="background-color: #ffffff; font-family: Arial; color: #000000; font-size: 12px;">
			<span>This is just a simple test paragraph.</span>
				<div style="margin-top: 5px; line-height: 20px; text-align: left;">
				<div style="margin-bottom: 3px;"><input type="radio" name="pops_popup_option" value=""/> Option 1</div>
				<div style="margin-bottom: 3px;"><input type="radio" name="pops_popup_option" value=""/> Option 2</div>
				<div style="margin-bottom: 3px;"><input type="radio" name="pops_popup_option" value=""/> Option 3</div>
				<div style="margin-bottom: 3px;"><input type="radio" name="pops_popup_option" value=""/> Option 4</div>
				<div style="margin-bottom: 3px;"><input type="radio" name="pops_popup_option" value=""/> Option 5</div>
			</div>
		</div>
		<div id="pops_popup_button" style="background-color: #ffffff; padding: 5px;"><input type="button" name="submit" value="Submit" style="width: 100%; margin-top: 10px;"/></div>
	</form>
</div>
<?php
}
?>