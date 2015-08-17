<?php
function igw_page_general() {
	igw_start_form('Settings Wp InstaWidget');
?>
<tr valign="middle">
	<div>
		<label for="igw_account">Your instagram account login</label>
	</div>
	<div>
		<input id="igw_account" type="text" name="<?=IGW_OPT_PREF . 'account'?>" value="<?=get_option( IGW_OPT_PREF . 'account' )?>" />
	</div>
</tr>
<?php
	igw_end_form();

	igw_getConstructor();
}