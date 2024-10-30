<?php
global $wpdb;

//	Define some stuff for plugin..
define('POPS_NAME', 'Conversion Popup Survey');
define('POPS_FOLDER_NAME', 'conversion-popup-survey');
define('POPS_VERSION', '1.0.1');
define('POPS_TABLE', $wpdb->prefix.'pops_surveys');
define('POPS_DESIGN_TABLE', $wpdb->prefix.'pops_design');
define('POPS_UNIQUE_NAME', 'pops_survey');

require(WP_PLUGIN_DIR.'/'.POPS_FOLDER_NAME.'/pages/ajax.php');

/**
 * Install function.
 * It creates required tables and adds version entry to settings table.
 *
 */
function pops_install()
{
	global $wpdb;

	if($wpdb->get_var("show tables like '".POPS_TABLE."'") != POPS_TABLE)
	{
		$sql = "CREATE TABLE `".POPS_TABLE."` (
				`id` INT NOT NULL AUTO_INCREMENT,
				`name` VARCHAR( 255 ) NOT NULL ,
				`content_data` TEXT NOT NULL ,
				`page_id` VARCHAR( 250 ) NOT NULL,
				`post_id` VARCHAR( 250 ) NOT NULL,
				`active` TINYINT( 1 ) NOT NULL DEFAULT '0',
				`date_added` DATETIME NOT NULL,
				PRIMARY KEY (`id`)
				) ENGINE = MYISAM AUTO_INCREMENT=1 ;";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	if($wpdb->get_var("show tables like '".POPS_DESIGN_TABLE."'") != POPS_DESIGN_TABLE)
	{
		$sql = "CREATE TABLE `".POPS_DESIGN_TABLE."` (
				`id` INT NOT NULL AUTO_INCREMENT ,
				`name` VARCHAR( 255 ) NOT NULL ,
				`design_data` TEXT NOT NULL ,
				`date_created` DATETIME NOT NULL,
				PRIMARY KEY (`id`)
				) ENGINE = MYISAM AUTO_INCREMENT=1 ;";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	//	Add current database version to option table.
	//	This is helpful for updates in case we have some table changes in future.
	add_option('pops_version', POPS_VERSION, '', 'yes');
	//	This is in the settings page and will delete the tables if its selected on the uninstall
	add_option('pops_settings_delete_tables', '0', '', 'yes');
	//	License key field
	//	Since its brand new installation, it needs to be empty ;)
	add_option('pops_license_key', '', '', 'yes');
}
register_activation_hook(WP_PLUGIN_DIR.'/'.POPS_FOLDER_NAME.'/ltw-conversion-popup-survey.php', 'pops_install');

/**
 * Create main admin menu
 *
 */
function pops_main_menu()
{
	//	Add a new top-level menu
    add_menu_page(POPS_NAME, POPS_NAME, 'administrator', 'pops_manage', 'pops_manage');

	//	Create subpages
	$pops_manage = add_submenu_page('pops_manage', POPS_NAME.' - Manage', 'Manage', 'administrator', 'pops_manage', 'pops_manage');
	$pops_design = add_submenu_page('pops_manage', POPS_NAME.' - Design', 'Design', 'administrator', 'pops_design', 'pops_design');
	$pops_settings = add_submenu_page('pops_manage', POPS_NAME.' - Settings', 'Settings', 'administrator', 'pops_settings', 'pops_settings');

	//	This is used for custom CSS inside the admin pages
	add_action('admin_print_styles-'.$pops_manage, 'pops_admin_style');
	add_action('admin_print_styles-'.$pops_design, 'pops_admin_style');
	add_action('admin_print_styles-'.$pops_settings, 'pops_admin_style');

	//	Javascript for admin pages
	add_action('admin_print_scripts-'.$pops_design, 'pops_admin_style');

	//	Register settings
	add_action('admin_init', 'pops_admin_init');
}
add_action('admin_menu', 'pops_main_menu');

/**
 * Register settings.
 * This is used for the add_option function..
 *
 */
function pops_admin_init()
{
	register_setting('pops-settings', 'pops_settings_delete_tables');
}

function pops_admin_style()
{
	wp_enqueue_style('pops_admin_stylesheet', WP_PLUGIN_URL.'/'.POPS_FOLDER_NAME.'/css/style_admin.css', '', POPS_VERSION);
	wp_enqueue_style('pops_colorpicker_stylesheet', WP_PLUGIN_URL.'/'.POPS_FOLDER_NAME.'/css/colorpicker.css', '', POPS_VERSION);

	wp_enqueue_script('pops_jquery_color_picker', WP_PLUGIN_URL.'/'.POPS_FOLDER_NAME.'/js/colorpicker.js', '', POPS_VERSION);

	//	Loaded at pops_init
	wp_enqueue_script('pops_popup', WP_PLUGIN_URL.'/'.POPS_FOLDER_NAME.'/js/popup.js', '', POPS_VERSION);

	// embed the javascript file that makes the AJAX request
	wp_enqueue_script('pops_ajax', WP_PLUGIN_URL.'/'.POPS_FOLDER_NAME.'/js/pops_ajax.js', array('jquery'), POPS_VERSION);

	wp_localize_script('pops_ajax', 'pops_ajax', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('pops_ajax_nonce')
		)
	);
}

/**
 * Settings page
 *
 */
function pops_settings()
{
	global $wpdb;

	include('pages/settings.php');
}

/**
 * When the plugin is deactivated, delete all the tables, if user selected the checkbox ;)
 *
 */
function pops_plugin_uninstall()
{
	global $wpdb;

	//	Delete tables
	if (get_option('pops_settings_delete_tables') == '1')
	{
		$sql = "DROP TABLE IF EXISTS `".POPS_TABLE."`";
		$wpdb->query($sql);

		$sql = "DROP TABLE IF EXISTS `".POPS_DESIGN_TABLE."`";
		$wpdb->query($sql);
	}

  	//	Finally, delete the extra records from "options" table
  	delete_option('pops_version');
  	delete_option('pops_settings_delete_tables');
  	delete_option('pops_license_key');

	//	Settings
  	delete_option('pops_settings_delete_tables');
}
register_deactivation_hook(WP_PLUGIN_DIR.'/'.POPS_FOLDER_NAME.'/popup-survey.php', 'pops_plugin_uninstall');

/**
 * Design pages
 *
 */
function pops_design()
{
	global $wpdb;

	$current_sub_page = isset($_GET['sp']) ? $_GET['sp'] : '';

	switch($current_sub_page)
	{
		//	Show the "Edit" page
		case 'edit':
			include('pages/design_edit.php');
		break;

		//	Show the "Add New" page
		case 'add_new':
			include('pages/design_add_new.php');
		break;

		//	Default page, table with all available designs
		default:
			include('pages/design_index.php');
		break;
	}
}

/**
 * Manage popups pages
 *
 */
function pops_manage()
{
	global $wpdb;

	$current_sub_page = isset($_GET['sp']) ? $_GET['sp'] : '';

	switch($current_sub_page)
	{
		//	Show the "Edit" page
		case 'edit':
			include('pages/manage_edit.php');
		break;

		//	Show the "Add New" page
		case 'add_new':
			include('pages/manage_add_new.php');
		break;

		//	Default page, table with all available popups
		default:
			include('pages/manage_index.php');
		break;
	}
}

/**
 * Main Init
 *
 */
function pops_init()
{
	//	Load jQuery Javascript library
	wp_enqueue_script('jquery');
}
add_action('init', 'pops_init');

/**
 * Footer
 *
 */
function pops_footer()
{
	global $wp_query, $wpdb;

	if (is_page() == TRUE || is_single() == TRUE)
	{
		$sql = "SELECT * FROM `".POPS_TABLE."`
				WHERE `active` = 1";
		$pops_info = array();
		$pops_info = $wpdb->get_results($sql, ARRAY_A);

		$pops_data = array();
		$pops_data_un = array();

		//	Get current page/post ID
		$pops_current_id = $wp_query->queried_object_id;

		//	Show HTML and the popup :)
		//	Only if match is found..
		$pops_show_html = FALSE;

		//	Loop over the table info, find the first ID that matches the above one
		//	and exit. Only one popup per page/post is allowed
		if (count($pops_info) > 0)
		{
			foreach ($pops_info as $pops_entry)
			{
				$pops_data = $pops_entry;
				$pops_data_un = unserialize($pops_data['content_data']);

				if (isset($pops_data_un['pops_show_on_pages']) && isset($pops_data_un['pops_show_on_posts']))
				{
					//	Check if we have ID match for page
					if (is_page() == TRUE)
					{
						$pops_page_list = explode(',', $pops_data_un['pops_show_on_pages']);

						foreach ($pops_page_list as $page_list_value)
						{
							if ($page_list_value == $pops_current_id)
							{
								$pops_show_html = TRUE;
								break(2);
							}
						}
					}

					//	Check if we have ID match for post
					if (is_single() == TRUE)
					{
						$pops_post_list = explode(',', $pops_data_un['pops_show_on_posts']);

						foreach ($pops_post_list as $post_list_value)
						{
							if ($post_list_value == $pops_current_id)
							{
								$pops_show_html = TRUE;
								break(2);
							}
						}
					}
				}
			}
		}

		if ($pops_show_html == TRUE && isset($_COOKIE['pops_'.$pops_data_un['pops_survey_cookie_name']]) == FALSE)
		{
			//	General CSS
			$popup_position = $pops_data_un['pops_general_location'];

			$popup_general_css = 'width:'.$pops_data_un['pops_general_width'].'px;';

			if ($pops_data_un['pops_general_height'] != '0')
			{
				$popup_general_css .= 'height:'.$pops_data_un['pops_general_height'].'px;';
			}
			else
			{
				$popup_general_css .= 'height:auto;';
			}

			if ($popup_position == 'tl')
			{
				$popup_general_css .= 'margin-top:'.$pops_data_un['pops_general_margin_tb'].'px;';
				$popup_general_css .= 'margin-left:'.$pops_data_un['pops_general_margin_lr'].'px;';
			}
			else if ($popup_position == 'tc')
			{
				$popup_general_css .= 'margin-top:'.$pops_data_un['pops_general_margin_tb'].'px;';
			}
			else if ($popup_position == 'tr')
			{
				$popup_general_css .= 'margin-top:'.$pops_data_un['pops_general_margin_tb'].'px;';
				$popup_general_css .= 'margin-right:'.$pops_data_un['pops_general_margin_lr'].'px;';
			}
			else if ($popup_position == 'ml')
			{
				$popup_general_css .= 'margin-left:'.$pops_data_un['pops_general_margin_lr'].'px;';
			}
			else if ($popup_position == 'mr')
			{
				$popup_general_css .= 'margin-right:'.$pops_data_un['pops_general_margin_lr'].'px;';
			}
			else if ($popup_position == 'bl')
			{
				$popup_general_css .= 'margin-bottom:'.$pops_data_un['pops_general_margin_tb'].'px;';
				$popup_general_css .= 'margin-left:'.$pops_data_un['pops_general_margin_lr'].'px;';
			}
			else if ($popup_position == 'bc')
			{
				$popup_general_css .= 'margin-bottom:'.$pops_data_un['pops_general_margin_tb'].'px;';
			}
			else if ($popup_position == 'br')
			{
				$popup_general_css .= 'margin-bottom:'.$pops_data_un['pops_general_margin_tb'].'px;';
				$popup_general_css .= 'margin-right:'.$pops_data_un['pops_general_margin_lr'].'px;';
			}

			//	Header CSS
			$popup_header_css = 'background-color:#'.$pops_data_un['pops_header_background_color'].';';
			$popup_header_css .= 'font-family:'.$pops_data_un['pops_header_font_face'].';';
			$popup_header_css .= 'color:#'.$pops_data_un['pops_header_font_color'].';';
			$popup_header_css .= 'font-size:'.$pops_data_un['pops_header_font_size'].'px;';
			$popup_header_css .= 'padding:'.$pops_data_un['pops_header_padding'].'px;';

			//	Content CSS
			$popup_content_css = 'background-color:#'.$pops_data_un['pops_content_background_color'].';';
			$popup_content_css .= 'font-family:'.$pops_data_un['pops_content_font_face'].';';
			$popup_content_css .= 'color:#'.$pops_data_un['pops_content_font_color'].';';
			$popup_content_css .= 'font-size:'.$pops_data_un['pops_content_font_size'].'px;';
			$popup_content_css .= 'padding:'.$pops_data_un['pops_content_padding'].'px;';

			//	Border CSS
			$popup_border_css = 'border-style:'.$pops_data_un['pops_border_style'].';';
			$popup_border_css .= 'border-color:#'.$pops_data_un['pops_border_color'].';';
			$popup_border_css .= 'border-width:'.$pops_data_un['pops_border_width'].'px;';
			$popup_border_css .= 'background-color:#'.$pops_data_un['pops_border_background_color'].';';
			$popup_border_css .= 'padding:'.$pops_data_un['pops_border_padding'].'px;';

			//	Button CSS
			$popup_button_css = 'background-color:#'.$pops_data_un['pops_content_background_color'].';';
?>
<script type="text/javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/<?php echo POPS_FOLDER_NAME; ?>/js/popup.js?ver=<?php echo POPS_VERSION; ?>"></script>
<script type="text/javascript">
/* <![CDATA[ */
function pops_iPOP_start() { setTimeout("pops_iPOP_init1()", <?php echo esc_html(stripslashes($pops_data_un['pops_survey_delay'])); ?>000); }
winListener("load", pops_iPOP_start, "pops_iPOP_start");

function submitForm(){
	for (var i=0; i < document.pops_survey.pops_popup_option.length; i++)
   	{
   		if (document.pops_survey.pops_popup_option[i].checked)
    	{
      		location.href = document.pops_survey.pops_popup_option[i].value;
      	}
   	}
}
/* ]]> */
</script>
<div id="pops_popup" pos="<?php echo $popup_position; ?>" style="overflow:hidden;display:none;top:5000;left:0;z-index:15500;<?php echo $popup_general_css.$popup_border_css; ?>">
	<div id="pops_popup_header" style="text-align:center;line-height:26px;<?php echo $popup_header_css; ?>">
		<span><?php echo esc_html(stripslashes($pops_data_un['pops_survey_header_title'])); ?></span>
	</div>
	<form name="pops_survey" action="" method="post">
		<div id="pops_popup_content" style="<?php echo $popup_content_css; ?>">
			<span><?php echo esc_html(stripslashes($pops_data_un['pops_survey_open_text'])); ?></span>
				<div style="margin-top: 5px; line-height: 20px; text-align: left;">
<?php
if (count($pops_data_un['pops_survey_entry']) > 0)
{
	foreach ($pops_data_un['pops_survey_entry'] as $pops_survey_entry)
	{
?>
				<div style="margin-bottom: 3px;"><input type="radio" name="pops_popup_option" value="<?php echo $pops_survey_entry['url']; ?>"/> <?php echo esc_html(stripslashes($pops_survey_entry['text'])); ?></div>
<?php
	}
}
?>
			</div>
		</div>
		<div id="pops_popup_button" style="padding:5px;<?php echo $popup_button_css; ?>"><input type="button" onclick="submitForm()" name="submit" value="<?php echo esc_html(stripslashes($pops_data_un['pops_survey_button_text'])); ?>" style="width: 100%; margin-top: 10px;"/></div>
	</form>
</div>
<?php
		}//	End $pops_show_html == TRUE
	}//	End is_page || is_single
}
add_action('wp_footer', 'pops_footer');

/**
 * Creates cookie if the popup reoccurances is set
 *
 */
function pops_cookie()
{
	global $wp_query, $wpdb;

	if (is_page() == TRUE || is_single() == TRUE)
	{
		$sql = "SELECT * FROM `".POPS_TABLE."`
				WHERE `active` = 1";
		$pops_info = array();
		$pops_info = $wpdb->get_results($sql, ARRAY_A);

		$pops_data = array();
		$pops_data_un = array();

		//	Get current page/post ID
		$pops_current_id = $wp_query->queried_object_id;

		//	Show HTML and the popup :)
		//	Only if match is found..
		$pops_continue = FALSE;

		//	Loop over the table info, find the first ID that matches the above one
		//	and exit. Only one popup per page/post is allowed
		if (count($pops_info) > 0)
		{
			foreach ($pops_info as $pops_entry)
			{
				$pops_data = $pops_entry;
				$pops_data_un = unserialize($pops_data['content_data']);

				if (isset($pops_data_un['pops_show_on_pages']) && isset($pops_data_un['pops_show_on_posts']))
				{
					//	Check if we have ID match for page
					if (is_page() == TRUE)
					{
						$pops_page_list = explode(',', $pops_data_un['pops_show_on_pages']);

						foreach ($pops_page_list as $page_list_value)
						{
							if ($page_list_value == $pops_current_id)
							{
								$pops_continue = TRUE;
								break(2);
							}
						}
					}

					//	Check if we have ID match for post
					if (is_single() == TRUE)
					{
						$pops_post_list = explode(',', $pops_data_un['pops_show_on_posts']);

						foreach ($pops_post_list as $post_list_value)
						{
							if ($post_list_value == $pops_current_id)
							{
								$pops_continue = TRUE;
								break(2);
							}
						}
					}
				}
			}
		}

		if ($pops_continue == TRUE)
		{
			if ($pops_data_un['pops_survey_reoccurances'] == '1')
			{
				setcookie("pops_".$pops_data_un['pops_survey_cookie_name'], time(), time()+60*60*24*365);
			}
			else if ($pops_data_un['pops_survey_reoccurances'] == '3')
			{
				setcookie("pops_".$pops_data_un['pops_survey_cookie_name'], time(), time()+60*60*24*$pops_data_un['pops_survey_reoccurances_days']);
			}
		}
	}
}
add_action('wp', 'pops_cookie');

/**
 * Create random non-word :) for cookie name
 *
 */
function pops_rand_str()
{
	$length = 10;
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';

    // Length of character list
    $chars_length = (strlen($chars) - 1);

    // Start our string
    $string = $chars{rand(0, $chars_length)};

    // Generate random string
    for ($i = 1; $i < $length; $i = strlen($string))
    {
        // Grab a random character from our list
        $r = $chars{rand(0, $chars_length)};

        // Make sure the same two characters don't appear next to each other
        if ($r != $string{$i - 1}) $string .=  $r;
    }

    // Return the string
    return $string;
}
?>