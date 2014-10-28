<table class="wm_contacts_view">
	<tr>
		<td class="wm_field_title"></td>
		<td class="wm_field_value">
			<input type="checkbox" class="wm_checkbox" value="1" name="chSipEnable" id="chSipEnable" <?php $this->Data->PrintCheckedValue('chSipEnable') ?> />
			<label for="chSipEnable" id="chSipEnable_label"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_SIP_ALLOW'); ?></label>
		</td>
	</tr>
	<tr><td colspan="2"><br /></td></tr>
	<tr>
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/USERS_SIP_IMPI'); ?>
		</td>
		<td class="wm_field_value">
			<input name="txtSipImpi" type="text" id="txtSipImpi" class="wm_input" value="<?php $this->Data->PrintInputValue('txtSipImpi') ?>" />
		</td>
	</tr>
	<tr>
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/USERS_SIP_PASSWORD'); ?>
		</td>
		<td class="wm_field_value">
			<input name="txtSipPassword" type="password" id="txtSipPassword" class="wm_input" value="<?php $this->Data->PrintInputValue('txtSipPassword') ?>" />
		</td>
	</tr>
</table>