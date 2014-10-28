<input name="intChannelId" type="hidden" value="<?php $this->Data->PrintInputValue('intChannelId'); ?>" />
<table class="wm_contacts_view">
	<tr class="<?php $this->Data->PrintInputValue('hideClassForEditChannel'); ?>">
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_CHANNEL_NAME'); ?> *
		</td>
		<td class="wm_field_value" colspan="2">
			<input name="txtLogin" type="text" id="txtLogin" class="wm_input" style="width: 350px" maxlength="100" value="<?php $this->Data->PrintInputValue('txtLogin'); ?>" />
		</td>
	</tr>
	<tr class="<?php $this->Data->PrintInputValue('hideClassForNewChannel'); ?>">
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_CHANNEL_NAME'); ?> *
		</td>
		<td class="wm_field_value" colspan="2">
			<strong><?php $this->Data->PrintValue('txtLogin'); ?></strong>
		</td>
	</tr>
	<tr class="<?php $this->Data->PrintInputValue('hideClassForNewChannel'); ?>">
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_PASSWORD'); ?> *
		</td>
		<td class="wm_field_value" colspan="2">
			<strong><?php $this->Data->PrintValue('txtPassword'); ?></strong>
		</td>
	</tr>
	<tr>
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_CHANNEL_DESC'); ?>
		</td>
		<td class="wm_field_value" colspan="2">
			<input name="txtDescription" type="text" id="txtDescription" class="wm_input" style="width: 350px" maxlength="255" value="<?php $this->Data->PrintInputValue('txtDescription'); ?>" />
		</td>
	</tr>
</table>
