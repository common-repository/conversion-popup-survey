<?php
$pops_show_success_msg = FALSE;
$pops_set_success_msg = '';

/**
 * Check if "Bulk Action" was used
 *
 */
if (isset($_POST['form_submit']) && $_POST['form_submit'] == 'yes')
{
	if ((isset($_POST['action']) && $_POST['action'] == 'delete') || (isset($_POST['action2']) && $_POST['action2'] == 'delete'))
	{
		if (isset($_POST['pops_survey_id']) && count($_POST['pops_survey_id']) > 0)
		{
			//	Just a little ;) security thingy that wordpress offers us
			check_admin_referer('pops_survey_index');

			foreach ($_POST['pops_survey_id'] as $pops_survey_id)
			{
				//	Delete all selected records from the table
				$sql = $wpdb->prepare("DELETE FROM `".POPS_TABLE."`
						WHERE `id` = %d", $pops_survey_id);
				$wpdb->query($sql);

				//	Set success message
				$pops_show_success_msg = TRUE;
				$pops_set_success_msg = 'Selected surveys were successfully deleted.';
			}
		}
	}
}

/**
 * Check if we are deleting a record.
 * This is available per each testimonial group.
 *
 */
if (isset($_GET['sp']) && $_GET['sp'] == 'delete' && isset($_GET['id']) && $_GET['id'] != '')
{
	//	Just a little ;) security thingy that wordpress offers us
	check_admin_referer('pops_delete_survey');

	//	Delete selected record from the table
	$sql = $wpdb->prepare("DELETE FROM `".POPS_TABLE."`
			WHERE `id` = %d
			LIMIT 1", $_GET['id']);
	$wpdb->query($sql);

	//	Set success message
	$pops_show_success_msg = TRUE;
	$pops_set_success_msg = 'Selected servey was successfully deleted.';
}
?>
<div class="wrap">
	<h2>Popup Surveys <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=pops_manage&amp;sp=add_new">Add New Survey</a></h2>
	<div id="pops_top_menu">
		<?php include('top_menu.php'); ?>
	</div>
<?php
if ($pops_show_success_msg == TRUE)
{
?>
	<div class="updated"><p><strong><?php echo $pops_set_success_msg; ?></strong></p></div>
<?php
}

//	Get all surveys
$sql = "SELECT *, UNIX_TIMESTAMP(`date_added`) AS `date_added`
		FROM `".POPS_TABLE."`
		ORDER BY `name` ASC";
$pops_db_list = array();
$pops_db_list = $wpdb->get_results($sql, ARRAY_A);

if (count($pops_db_list) > 0)
{
?>
	<form action="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=pops_manage" method="post">
		<div>
			<input type="hidden" name="form_submit" value="yes"/>
			<?php wp_nonce_field('pops_survey_index'); ?>
		</div>
		<div class="tablenav">
			<div class="alignleft actions">
				<select name="action">
					<option selected="selected" value="">Bulk Actions</option>
					<option value="delete">Delete</option>
				</select>
				<input type="submit" class="button-secondary action" id="doaction" name="doaction" value="Apply">
			</div>
		</div>
		<table class="widefat fixed" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column"><input type="checkbox" /></th>
					<th scope="col" id="name" class="manage-column column-title">Name</th>
					<th scope="col" id="reoccurances" class="manage-column column-title">Reoccurances</th>
					<th scope="col" id="active" class="manage-column column-visible">Active</th>
					<th scope="col" id="create_date" class="manage-column column-date">Create Date</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="col" id="cb2" class="manage-column column-cb check-column"><input type="checkbox" /></th>
					<th scope="col" id="name2" class="manage-column column-title">Name</th>
					<th scope="col" id="reoccurances2" class="manage-column column-title">Reoccurances</th>
					<th scope="col" id="active2" class="manage-column column-visible">Active</th>
					<th scope="col" id="create_date2" class="manage-column column-date">Create Date</th>
				</tr>
			</tfoot>
			<tbody>
<?php
	$alternate_row = 0;

	foreach ($pops_db_list as $survey)
	{
		$alternate_row_class = ' alternate';
		if ($alternate_row == 1)
		{
			$alternate_row_class = '';
			$alternate_row = 0;
		}
		else
		{
			$alternate_row++;
		}

		$survey_unser = unserialize($survey['content_data']);

		if ($survey_unser['pops_survey_reoccurances'] == '2')
		{
			$survey_reoccurances = 'Every time';
		}
		else if ($survey_unser['pops_survey_reoccurances'] == '3')
		{
			$survey_reoccurances = 'Once every '.$survey_unser['pops_survey_reoccurances_days'].' days';
		}
		else
		{
			$survey_reoccurances = 'One time only';
		}
?>
				<tr class="iedit<?php echo $alternate_row_class; ?>">
					<th class="check-column" scope="row"><input type="checkbox" value="<?php echo $survey['id']; ?>" name="pops_survey_id[]"></th>
					<td class="column-title">
						<strong><?php echo esc_html(stripslashes($survey['name'])); ?></strong>
						<div class="row-actions">
							<span class="edit"><a title="Edit" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=pops_manage&amp;sp=edit&amp;id=<?php echo $survey['id']; ?>">Edit</a> | </span>
<?php
		//	Build nonce url
		$pops_delete_survey_url = wp_nonce_url(get_option('siteurl').'/wp-admin/admin.php?page=pops_manage&amp;sp=delete&amp;id='.$survey['id'], 'pops_delete_survey');
?>
							<span class="trash"><a href="<?php echo $pops_delete_survey_url; ?>" title="Delete" class="submitdelete">Delete</a></span>
						</div>
					</td>
					<td class="column-title"><?php echo $survey_reoccurances; ?></td>
					<td class="column-visible"><?php echo $survey['active'] == '1' ? 'Yes' : 'No'; ?></td>
					<td class="column-date"><?php echo date(get_option("date_format"), $survey['date_added']); ?><br /><?php echo date(get_option("time_format"), $survey['date_added']); ?></td>
				</tr>
<?php

	}
?>
			</tbody>
		</table>
		<div class="tablenav">
			<div class="alignleft actions">
				<select name="action2">
					<option selected="selected" value="">Bulk Actions</option>
					<option value="delete">Delete</option>
				</select>
				<input type="submit" class="button-secondary action" id="doaction" name="doaction" value="Apply">
			</div>
		</div>
	</form>
<?php
}
else
{
?>
	<p>No designs found.</p>
<?php
}
?>
</div>