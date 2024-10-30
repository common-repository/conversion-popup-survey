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
		if (isset($_POST['pops_design_id']) && count($_POST['pops_design_id']) > 0)
		{
			//	Just a little ;) security thingy that wordpress offers us
			check_admin_referer('pops_groups_index');

			foreach ($_POST['pops_design_id'] as $pops_design_id)
			{
				//	Delete all selected records from the table
				$sql = $wpdb->prepare("DELETE FROM `".POPS_DESIGN_TABLE."`
						WHERE `id` = %d", $pops_design_id);
				$wpdb->query($sql);

				//	Set success message
				$pops_show_success_msg = TRUE;
				$pops_set_success_msg = 'Selected designs successfully deleted.';
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
	check_admin_referer('pops_delete_design');

	//	Delete selected record from the table
	$sql = $wpdb->prepare("DELETE FROM `".POPS_DESIGN_TABLE."`
			WHERE `id` = %d
			LIMIT 1", $_GET['id']);
	$wpdb->query($sql);

	//	Set success message
	$pops_show_success_msg = TRUE;
	$pops_set_success_msg = 'Selected design was successfully deleted.';
}
?>
<div class="wrap">
	<h2>Designs <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=pops_design&amp;sp=add_new">Add New Design</a></h2>
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

//	Get all designs
$sql = "SELECT *, UNIX_TIMESTAMP(`date_created`) AS `date_created`
		FROM `".POPS_DESIGN_TABLE."`
		ORDER BY `name` ASC";
$pops_db_list = array();
$pops_db_list = $wpdb->get_results($sql, ARRAY_A);

if (count($pops_db_list) > 0)
{
?>
	<form action="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=pops_design" method="post">
		<div>
			<input type="hidden" name="form_submit" value="yes"/>
			<?php wp_nonce_field('pops_groups_index'); ?>
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
					<th scope="col" id="create_date" class="manage-column column-date">Create Date</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="col" id="cb2" class="manage-column column-cb check-column"><input type="checkbox" /></th>
					<th scope="col" id="name2" class="manage-column column-title">Name</th>
					<th scope="col" id="create_date2" class="manage-column column-date">Create Date</th>
				</tr>
			</tfoot>
			<tbody>
<?php
	$alternate_row = 0;

	foreach ($pops_db_list as $design)
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
?>
				<tr class="iedit<?php echo $alternate_row_class; ?>">
					<th class="check-column" scope="row"><input type="checkbox" value="<?php echo $design['id']; ?>" name="pops_design_id[]"></th>
					<td class="column-title">
						<strong><?php echo esc_html(stripslashes($design['name'])); ?></strong>
						<div class="row-actions">
							<span class="edit"><a title="Edit" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=pops_design&amp;sp=edit&amp;id=<?php echo $design['id']; ?>">Edit</a> | </span>
<?php
		//	Build nonce url
		$pops_delete_design_url = wp_nonce_url(get_option('siteurl').'/wp-admin/admin.php?page=pops_design&amp;sp=delete&amp;id='.$design['id'], 'pops_delete_design');
?>
							<span class="trash"><a href="<?php echo $pops_delete_design_url; ?>" title="Delete" class="submitdelete">Delete</a></span>
						</div>
					</td>
					<td class="column-date"><?php echo date(get_option("date_format"), $design['date_created']); ?><br /><?php echo date(get_option("time_format"), $design['date_created']); ?></td>
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