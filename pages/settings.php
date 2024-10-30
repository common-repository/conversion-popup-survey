<div class="wrap">
	<h2>Settings</h2>
	<div id="pops_top_menu">
		<?php include('top_menu.php'); ?>
	</div>
<?php
if ($_GET['updated'] == 'true')
{
?>
	<div id="message" class="updated fade"><p><strong>Settings Updated</strong></p></div>
<?php
}
?>
	<form method="post" action="<?php echo get_option('siteurl'); ?>/wp-admin/options.php">
		<div>
			<?php settings_fields('pops-settings'); ?>
		</div>
		<table class="form-table">
	        <tr valign="top">
	        	<th scope="row">Remove tables when deactivating plugin</th>
	        	<td>
	        		<input type="checkbox" name="pops_settings_delete_tables" value="1"<?php echo get_option('pops_settings_delete_tables') == '1' ? ' checked="checked"' : ''; ?>/> All records will be deleted!
				</td>
	        </tr>
	    </table>
	    <p class="submit"><input type="submit" class="button-primary" value="Save" /></p>
	</form>
</div>