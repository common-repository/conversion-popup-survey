<?php
$pops_errors = array();
$pops_success = '';
$pops_error_found = FALSE;

/**
 * Preset the form fields
 *
 */
$form = array(
	'pops_survey_name' => '',
	'pops_survey_active' => '0',
	'pops_survey_reoccurances' => '1',
	'pops_survey_reoccurances_days' => '3',
	'pops_survey_cookie_name' => pops_rand_str(),
	'pops_show_on_pages' => '',
	'pops_show_on_posts' => '',
	'pops_survey_delay' => '0',

	'pops_survey_header_title' => '',
	'pops_survey_open_text' => '',
	'pops_survey_entry' => array(),
	'pops_survey_button_text' => 'Submit',

	'pops_general_width' => '300',
	'pops_general_height' => '250',
	'pops_general_location' => 'tl',
	'pops_general_margin_tb' => '0',
	'pops_general_margin_lr' => '0',

	'pops_header_background_color' => '000000',
	'pops_header_font_face' => 'Arial',
	'pops_header_font_color' => 'ffffff',
	'pops_header_font_size' => '24',
	'pops_header_padding' => '5',

	'pops_content_background_color' => 'ffffff',
	'pops_content_font_face' => 'Arial',
	'pops_content_font_color' => '000000',
	'pops_content_font_size' => '12',
	'pops_content_padding' => '3',

	'pops_border_style' => 'solid',
	'pops_border_color' => '000000',
	'pops_border_width' => '1',
	'pops_border_padding' => '3',
	'pops_border_background_color' => 'ffffff',
);

/**
 * Form submitted, check the data
 *
 */
if (isset($_POST['form_submit']) && $_POST['form_submit'] == 'yes')
{
	//	Just a little ;) security thingy that wordpress offers us
	check_admin_referer('pops_add_new_survey');

	$form['pops_survey_name'] = isset($_POST['pops_survey_name']) ? $_POST['pops_survey_name'] : '';
	if ($form['pops_survey_name'] == '') {	$pops_errors[] = 'Please enter the name for this survey'; $pops_error_found = TRUE; }

	$form['pops_survey_active'] = isset($_POST['pops_survey_active']) ? '1' : '0';

	$form['pops_survey_reoccurances'] = isset($_POST['pops_survey_reoccurances']) ? $_POST['pops_survey_reoccurances'] : '';
	if ($form['pops_survey_reoccurances'] == '') {	$pops_errors[] = 'Please select survey reoccurances'; $pops_error_found = TRUE; }

	if ($form['pops_survey_reoccurances'] == '3')
	{
		$form['pops_survey_reoccurances_days'] = isset($_POST['pops_survey_reoccurances_days']) ? $_POST['pops_survey_reoccurances_days'] : '';
		if ($form['pops_survey_reoccurances_days'] == '') {	$pops_errors[] = 'Please set survey reoccurance days'; $pops_error_found = TRUE; }
	}

	$form['pops_survey_cookie_name'] = isset($_POST['pops_survey_cookie_name']) ? $_POST['pops_survey_cookie_name'] : '';
	if ($form['pops_survey_cookie_name'] == '') {	$pops_errors[] = 'Please enter cookie name'; $pops_error_found = TRUE; }

	$form['pops_show_on_pages'] = isset($_POST['pops_show_on_pages']) ? $_POST['pops_show_on_pages'] : '';
	$form['pops_show_on_posts'] = isset($_POST['pops_show_on_posts']) ? $_POST['pops_show_on_posts'] : '';

	if ($form['pops_show_on_pages'] == '' && $form['pops_show_on_posts'] == '')
	{ $pops_errors[] = 'Please set at least one page'; $pops_error_found = TRUE; }

	$form['pops_survey_delay'] = isset($_POST['pops_survey_delay']) ? $_POST['pops_survey_delay'] : '';
	if ($form['pops_survey_delay'] == '') {	$pops_errors[] = 'Please set survey delay time'; $pops_error_found = TRUE; }

	//	Survey Content
	$form['pops_survey_header_title'] = isset($_POST['pops_survey_header_title']) ? $_POST['pops_survey_header_title'] : '';
	$form['pops_survey_open_text'] = isset($_POST['pops_survey_open_text']) ? $_POST['pops_survey_open_text'] : '';

	$form['pops_survey_button_text'] = isset($_POST['pops_survey_button_text']) ? $_POST['pops_survey_button_text'] : '';
	if ($form['pops_survey_button_text'] == '') {	$pops_errors[] = 'Please enter the submission button text'; $pops_error_found = TRUE; }

	//	Survey Options
	if (isset($_POST['pops_survey_entry']) == TRUE)
	{
		if (count($_POST['pops_survey_entry']) > 0)
		{
			foreach ($_POST['pops_survey_entry'] as $pops_entry)
			{
				if (isset($pops_entry['text']) == FALSE || isset($pops_entry['url']) == FALSE)
				{
					$pops_errors[] = 'Please enter at least one survey option'; $pops_error_found = TRUE;
					break;
				}
				else
				{
					if ($pops_entry['text'] == '' || $pops_entry['url'] == '')
					{
						$pops_errors[] = 'Please enter both survey options, text and url'; $pops_error_found = TRUE;
						$form['pops_survey_entry'][] = array('text' => $pops_entry['text'], 'url' => $pops_entry['url']);
						//break;
					}
					else
					{
						$form['pops_survey_entry'][] = array('text' => $pops_entry['text'], 'url' => $pops_entry['url']);
					}
				}
			}
		}
		else
		{
			$pops_errors[] = 'Please enter at least one survey option'; $pops_error_found = TRUE;
		}
	}
	else
	{
		$pops_errors[] = 'Please enter at least one survey option'; $pops_error_found = TRUE;
	}

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
			"INSERT INTO `".POPS_TABLE."`
			(`name`, `content_data`, `page_id`, `post_id`, `active`, `date_added`)
			VALUES(%s, %s, %s, %s, %d, NOW())",
			array($form['pops_survey_name'], serialize($form), $form['pops_show_on_pages'], $form['pops_show_on_posts'], $form['pops_survey_active'])
		);
		$wpdb->query($sql);

		$pops_success = 'Survey successfully saved.';

		//	Set the fields to default values
		$form = array(
			'pops_survey_name' => '',
			'pops_survey_active' => '0',
			'pops_survey_reoccurances' => '1',
			'pops_survey_reoccurances_days' => '3',
			'pops_survey_cookie_name' => pops_rand_str(),
			'pops_show_on_pages' => '',
			'pops_show_on_posts' => '',
			'pops_survey_delay' => '0',

			'pops_survey_header_title' => '',
			'pops_survey_open_text' => '',
			'pops_survey_entry' => array(),
			'pops_survey_button_text' => 'Submit',

			'pops_general_width' => '300',
			'pops_general_height' => '250',
			'pops_general_location' => 'tl',
			'pops_general_margin_tb' => '0',
			'pops_general_margin_lr' => '0',

			'pops_header_background_color' => '000000',
			'pops_header_font_face' => 'Arial',
			'pops_header_font_color' => 'ffffff',
			'pops_header_font_size' => '24',
			'pops_header_padding' => '5',

			'pops_content_background_color' => 'ffffff',
			'pops_content_font_face' => 'Arial',
			'pops_content_font_color' => '000000',
			'pops_content_font_size' => '12',
			'pops_content_padding' => '3',

			'pops_border_style' => 'solid',
			'pops_border_color' => '000000',
			'pops_border_width' => '1',
			'pops_border_padding' => '3',
			'pops_border_background_color' => 'ffffff'
		);
	}
}
?>
<div class="wrap">
	<h2>Add New Survey</h2>
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
	<div class="updated"><p><strong><?php echo $pops_success; ?></strong></p><p><a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=pops_manage">Click here</a> to go back to the surveys overview page.</p></div>
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

			$('#pops_popup #pops_popup_header span').html($('#pops_survey_header_title').val());

			$('#pops_popup #pops_popup_content').css('background-color', '#'+$('#pops_content_background_color').val());
			$('#pops_popup #pops_popup_content').css('font-family', $('#pops_content_font_face').val());
			$('#pops_popup #pops_popup_content').css('color', '#'+$('#pops_content_font_color').val());
			$('#pops_popup #pops_popup_content').css('font-size', $('#pops_content_font_size').val()+'px');
			$('#pops_popup #pops_popup_content').css('padding', $('#pops_content_padding').val()+'px');

			$('#pops_popup #pops_popup_content span').html($('#pops_survey_open_text').val().replace(/\n/g, '<br/>'));

			$('#pops_popup #pops_popup_button').css('background-color', '#'+$('#pops_content_background_color').val());
			$('#pops_popup #pops_popup_button input').val($('#pops_survey_button_text').val());

			$('#pops_popup').css('border-width', $('#pops_border_width').val()+'px');
			$('#pops_popup').css('border-style', $('#pops_border_style').val());
			$('#pops_popup').css('border-color', '#'+$('#pops_border_color').val());
			$('#pops_popup').css('padding', $('#pops_border_padding').val()+'px');
			$('#pops_popup').css('background-color', '#'+$('#pops_border_background_color').val());

			$('#pops_survey_list_options').html('');
			$("#pops_survey_options_cont :input[name$='[text]']").each(function(){
				$('#pops_survey_list_options').append('<div style="margin-bottom: 3px;"><input type="radio" name="pops_popup_option" value=""/> '+$(this).val()+'</div>');
			});

			pops_iPOP_init1();
		});

		$('.pops_toggle').toggle(function(){
			$('#pops_toggle_'+$(this).attr('id')).show();
			$(this).html('&ndash;');
			$(this).removeClass('pops_toggle pops_open');
			$(this).addClass('pops_toggle pops_close');
			$(this).parent().removeClass('pops_table_title pops_table_close');
			$(this).parent().addClass('pops_table_title pops_table_open');
			$('#pops_toggle_'+$(this).attr('id')+'_div').show();
	  	}, function(){
			$('#pops_toggle_'+$(this).attr('id')).hide();
			$(this).html('+');
			$(this).removeClass('pops_toggle pops_close');
			$(this).addClass('pops_toggle pops_open');
			$(this).parent().removeClass('pops_table_title pops_table_open');
			$(this).parent().addClass('pops_table_title pops_table_close');
			$('#pops_toggle_'+$(this).attr('id')+'_div').hide();
  		});

  		$('#pops_survey_reoccurances').change(function(){
  			if ($(this).val() == '3') {
  				$('#pops_survey_reoccurances_days_span').show();
  			}
  			else {
  				$('#pops_survey_reoccurances_days_span').hide();
  			}
		});

<?php
$pops_survey_count_js = 2;
if (count($form['pops_survey_entry']) > 0)
{
	$pops_survey_count_js = count($form['pops_survey_entry']) + 1;
}
?>
		var pops_so_count = <?php echo $pops_survey_count_js; ?>;

		$('#pops_another_survey_option').live('click', function(){
			$('#pops_survey_options_cont').append('<table class="pop_survey_options_table" id="pop_survey_remove_id_'+pops_so_count+'"><tr valign="top"><th scope="row">Text Entry</th><td><input type="text" name="pops_survey_entry['+pops_so_count+'][text]" class="regular-text" value=""/></td><th scope="row">Redirect URL</th><td><input type="text" name="pops_survey_entry['+pops_so_count+'][url]" class="regular-text" value=""/> <a href="#" id="remove_id_'+pops_so_count+'" style="position:absolute;margin-left:5px;margin-top:2px;" class="pops_delete_survey_option">Delete</a></td></tr></table>');
			pops_so_count++;

			return false;
		});

		$('.pops_delete_survey_option').live('click', function(){
			var delete_id = $(this).attr('id');

			$('#pop_survey_'+delete_id).remove();

			return false;
		});
	});
	/* ]]> */
	</script>
	<form method="post" action="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=pops_manage&amp;sp=add_new">
		<div>
			<input type="hidden" name="form_submit" value="yes"/>
			<input type="hidden" name="pops_header_background_color" id="pops_header_background_color" value="<?php echo esc_html(stripslashes($form['pops_header_background_color'])); ?>"/>
			<input type="hidden" name="pops_header_font_color" id="pops_header_font_color" value="<?php echo esc_html(stripslashes($form['pops_header_font_color'])); ?>"/>
			<input type="hidden" name="pops_content_background_color" id="pops_content_background_color" value="<?php echo esc_html(stripslashes($form['pops_content_background_color'])); ?>"/>
			<input type="hidden" name="pops_content_font_color" id="pops_content_font_color" value="<?php echo esc_html(stripslashes($form['pops_content_font_color'])); ?>"/>
			<input type="hidden" name="pops_border_color" id="pops_border_color" value="<?php echo esc_html(stripslashes($form['pops_border_color'])); ?>"/>
			<input type="hidden" name="pops_border_background_color" id="pops_border_background_color" value="<?php echo esc_html(stripslashes($form['pops_border_background_color'])); ?>"/>
			<?php wp_nonce_field('pops_add_new_survey'); ?>
		</div>
		<table class="form-table">
	        <tr valign="top">
	        	<th scope="row">Survey Name</th>
	        	<td>
	        		<input type="text" name="pops_survey_name" id="pops_survey_name" class="regular-text" value="<?php echo esc_html(stripslashes($form['pops_survey_name'])); ?>" />
	        		<br />
	        		<span class="description">For your eyes only</span>
				</td>
			</tr>
			<tr valign="top">
	        	<th scope="row">Survey Active</th>
	        	<td><input type="checkbox" name="pops_survey_active" id="pops_survey_active" value="1"<?php echo esc_html(stripslashes($form['pops_survey_active'])) == '1' ? ' checked="checked"' : ''; ?> /></td>
			</tr>
			<tr valign="top">
	        	<th scope="row">Show on Pages</th>
	        	<td>
	        		<input type="text" name="pops_show_on_pages" id="pops_show_on_pages" class="regular-text" value="<?php echo esc_html(stripslashes($form['pops_show_on_pages'])); ?>" />
	        		<br />
	        		<span class="description">Comma separated list of page ID's where you want this survey to appear. For example: 2,15,7</span>
				</td>
			</tr>
			<tr valign="top">
	        	<th scope="row">Show on Posts</th>
	        	<td>
	        		<input type="text" name="pops_show_on_posts" id="pops_show_on_posts" class="regular-text" value="<?php echo esc_html(stripslashes($form['pops_show_on_posts'])); ?>" />
	        		<br />
	        		<span class="description">Comma separated list of post ID's where you want this survey to appear. For example: 2,15,7</span>
				</td>
			</tr>
			<tr valign="top">
	        	<th scope="row">Reoccurances</th>
	        	<td>
	        		<select name="pops_survey_reoccurances" id="pops_survey_reoccurances">
	        			<option value="1"<?php echo $form['pops_survey_reoccurances'] == '1' || $form['pops_survey_reoccurances'] == '' ? ' selected="selected"' : ''; ?>>One time only</option>
	        			<option value="2"<?php echo $form['pops_survey_reoccurances'] == '2' ? ' selected="selected"' : ''; ?>>Every time</option>
	        			<option value="3"<?php echo $form['pops_survey_reoccurances'] == '3' ? ' selected="selected"' : ''; ?>>Once every...</option>
					</select>
	        		<span id="pops_survey_reoccurances_days_span" style="display: <?php echo $form['pops_survey_reoccurances'] == '3' ? 'inline' : 'none'; ?>"><input type="text" name="pops_survey_reoccurances_days" id="pops_survey_reoccurances_days" class="small-text" value="<?php echo esc_html(stripslashes($form['pops_survey_reoccurances_days'])); ?>" /> days</span>
	        	</td>
			</tr>
			<tr valign="top">
	        	<th scope="row">Cookie Name</th>
	        	<td>
	        		<input type="text" name="pops_survey_cookie_name" id="pops_survey_cookie_name" class="regular-text" value="<?php echo esc_html(stripslashes($form['pops_survey_cookie_name'])); ?>" />
	        		<br />
	        		<span class="description">If you change reoccurances, you should also change cookie name (random value) if you wish that visitors who already visited will also get the new reoccurance setting</span>
				</td>
			</tr>
			<tr valign="top">
	        	<th scope="row">Delay</th>
	        	<td>
	        		<input type="text" name="pops_survey_delay" id="pops_survey_delay" class="small-text" value="<?php echo esc_html(stripslashes($form['pops_survey_delay'])); ?>" /> seconds
	        		<br />
	        		<span class="description">Set to 0 for no delay</span>
				</td>
			</tr>
		</table>
		<h3>Survey Content</h3>
		<table class="form-table">
	        <tr valign="top">
	        	<th scope="row">Header Title</th>
	        	<td>
	        		<input type="text" name="pops_survey_header_title" id="pops_survey_header_title" class="regular-text" value="<?php echo esc_html(stripslashes($form['pops_survey_header_title'])); ?>" />
	        		<br />
	        		<span class="description">Leave empty if you do not wish to use the header title</span>
				</td>
			</tr>
			<tr valign="top">
	        	<th scope="row">Opening Paragraph Text</th>
	        	<td>
	        		<textarea class="large-text code" cols="50" rows="10" name="pops_survey_open_text" id="pops_survey_open_text"><?php echo esc_html(stripslashes($form['pops_survey_open_text'])); ?></textarea>
	        		<br />
	        		<span class="description">Leave empty if you do not wish to use the opening paragraph text</span>
				</td>
			</tr>
			<tr valign="top">
	        	<th scope="row">Set Survey Options</th>
	        	<td>
	        		<div id="pops_survey_options_cont">
<?php
$pops_survey_counter = 1;
if (count($form['pops_survey_entry']) > 0)
{
	foreach ($form['pops_survey_entry'] as $survey_entry)
	{
		$pops_survey_delete_link = '';
		if ($pops_survey_counter > 1)
		{
			$pops_survey_delete_link = ' <a href="#" id="remove_id_'.$pops_survey_counter.'" style="position:absolute;margin-left:5px;margin-top:2px;" class="pops_delete_survey_option">Delete</a>';
		}
?>
						<table class="pop_survey_options_table" id="pop_survey_remove_id_<?php echo $pops_survey_counter; ?>">
					        <tr valign="top">
					        	<th scope="row">Text Entry</th>
					        	<td><input type="text" name="pops_survey_entry[<?php echo $pops_survey_counter; ?>][text]" class="regular-text pops_survey_entry_text" value="<?php echo esc_html(stripslashes($survey_entry['text'])); ?>" /></td>
					        	<th scope="row">Redirect URL</th>
					        	<td><input type="text" name="pops_survey_entry[<?php echo $pops_survey_counter; ?>][url]" class="regular-text pops_survey_entry_url" value="<?php echo esc_html(stripslashes($survey_entry['url'])); ?>" /><?php echo $pops_survey_delete_link; ?></td>
							</tr>
						</table>
<?php
		$pops_survey_counter++;
	}
}
else
{
?>
        				<table class="pop_survey_options_table" id="pop_survey_remove_id_1">
					        <tr valign="top">
					        	<th scope="row">Text Entry</th>
					        	<td><input type="text" name="pops_survey_entry[1][text]" class="regular-text" value="" /></td>
					        	<th scope="row">Redirect URL</th>
					        	<td><input type="text" name="pops_survey_entry[1][url]" class="regular-text" value="" /></td>
							</tr>
						</table>
<?php
}
?>
					</div>
					<div><a href="#" id="pops_another_survey_option">+ Add Another Option</a></div>
				</td>
			</tr>
			<tr valign="top">
	        	<th scope="row">Submission Button Text</th>
	        	<td><input type="text" name="pops_survey_button_text" id="pops_survey_button_text" value="<?php echo esc_html(stripslashes($form['pops_survey_button_text'])); ?>" /></td>
			</tr>
		</table>
		<h3>Design</h3>
		<table class="form-table">
	        <tr valign="top">
	        	<th scope="row">Load Survey Design</th>
	        	<td>
	        		<select name="pops_load_design" id="pops_load_design">
						<option></option>
<?php
//	Get all designs
$sql = "SELECT *
		FROM `".POPS_DESIGN_TABLE."`
		ORDER BY `name` ASC";
$pops_design_list = array();
$pops_design_list = $wpdb->get_results($sql, ARRAY_A);

if (count($pops_design_list) > 0)
{
	foreach ($pops_design_list as $design)
	{
		$selected_design = '';
		if ($design['id'] == $form['pops_design_name'])
		{
			$selected_design = ' selected="selected"';
		}
?>
						<option value="<?php echo $design['id']; ?>"<?php echo $selected_design; ?>><?php echo $design['name']; ?></option>
<?php
	}
}
?>
					</select>
	        		<br />
	        		<span class="description">Loaded design can still be modified by opening the boxes below</span>
				</td>
			</tr>
		</table>
		<div class="pops_table_title pops_table_close">General <span class="pops_toggle pops_open" id="general_tb">+</span></div>
		<div class="pops_form_table_cont" id="pops_toggle_general_tb_div" style="display: none;">
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
	    <div class="pops_table_title pops_table_close">Header <span class="pops_toggle pops_open" id="header_tb">+</span></div>
	    <div class="pops_form_table_cont" id="pops_toggle_header_tb_div" style="display: none;">
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
		<div class="pops_table_title pops_table_close">Content <span class="pops_toggle pops_open" id="content_tb">+</span></div>
		<div class="pops_form_table_cont" id="pops_toggle_content_tb_div" style="display: none;">
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
		<div class="pops_table_title pops_table_close">Border <span class="pops_toggle pops_open" id="border_tb">+</span></div>
		<div class="pops_form_table_cont" id="pops_toggle_border_tb_div" style="display: none;">
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
	    <p class="submit"><input type="button" class="button-secondary" id="pops_preview_design" value="Preview Survey" /> <input type="submit" class="button-primary" value="Save Survey" /></p>
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
			<div id="pops_survey_list_options" style="margin-top: 5px; line-height: 20px; text-align: left;"></div>
		</div>
		<div id="pops_popup_button" style="background-color: #ffffff; padding: 5px;"><input type="button" name="submit" value="Submit" style="width: 100%; margin-top: 10px;"/></div>
	</form>
</div>