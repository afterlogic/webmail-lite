<table class="wm_contacts_view">
	<tr>
		<td class="wm_field_title"></td>
		<td class="wm_field_value">
			<input type="checkbox" class="wm_checkbox" value="1" name="chTwilioEnable" id="chTwilioEnable" <?php $this->Data->PrintCheckedValue('chTwilioEnable') ?> />
			<label for="chTwilioEnable" id="chTwilioEnable_label"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_TWILIO_ALLOW'); ?></label>
		</td>
	</tr>
	<tr><td colspan="2"><br /></td></tr>
	<tr>
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/USERS_TWILIO_NUMBER'); ?>
		</td>
		<td class="wm_field_value">
			<input name="txtTwilioNumber" type="text" id="txtTwilioNumber" class="wm_input" value="<?php $this->Data->PrintInputValue('txtTwilioNumber') ?>" />
			<div class="wm_information_com">
				<?php echo CApi::I18N('ADMIN_PANEL/USERS_TWILIO_NUMBER_DESC'); ?>
			</div>
		</td>
	</tr>
	<tr>
		<td class="wm_field_title"></td>
		<td class="wm_field_value">
			<input type="checkbox" class="wm_checkbox" value="1" name="chTwilioDefaultNumber" id="chTwilioDefaultNumber" <?php $this->Data->PrintCheckedValue('chTwilioDefaultNumber') ?> />
			<label for="chTwilioDefaultNumber" id="chTwilioDefaultNumber_label"><?php echo CApi::I18N('ADMIN_PANEL/USERS_TWILIO_DEFAULT_NUMBER'); ?></label>
			<div class="wm_information_com">
				<?php echo CApi::I18N('ADMIN_PANEL/USERS_TWILIO_DEFAULT_NUMBER_DESC'); ?>
			</div>
		</td>
	</tr>
</table>