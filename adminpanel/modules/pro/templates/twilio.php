<tr>
	<td align="left" width="150">
	</td>
	<td>
		<input type="checkbox" class="wm_checkbox" name="chAllowTwilio" id="chAllowTwilio" value="1" <?php $this->Data->PrintCheckedValue('chAllowTwilio') ?>/>
		<label id="chAllowTwilio_label" for="chAllowTwilio">
			<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_TWILIO_ALLOW'); ?></span>
		</label>
	</td>
</tr>
<tr>
	<td align="left" width="150" valign="top">
		<span id="txtTwilioPhoneNumber_label"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_TWILIO_PHONE_NUMBER'); ?></span>
	</td>
	<td>
		<input type="text" class="wm_input" name="txtTwilioPhoneNumber" id="txtTwilioPhoneNumber" value="<?php $this->Data->PrintInputValue('txtTwilioPhoneNumber') ?>" />
		<div class="wm_information_com">
			<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_TWILIO_PHONE_NUMBER_DESC'); ?>
		</div>
	</td>
</tr>
<tr>
	<td align="left" width="150" valign="top">
		<span id="txtTwilioAccountSID_label"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_TWILIO_ACCOUNT_SID'); ?></span>
	</td>
	<td>
		<input type="text" class="wm_input" name="txtTwilioAccountSID" id="txtTwilioAccountSID" value="<?php $this->Data->PrintInputValue('txtTwilioAccountSID') ?>" />
		<div class="wm_information_com">
			<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_TWILIO_ACCOUNT_SID_DESC'); ?>
		</div>
	</td>
</tr>
<tr>
	<td align="left" width="150" valign="top">
		<span id="txtTwilioAuthToken_label"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_TWILIO_AUTH_TOKEN'); ?></span>
	</td>
	<td>
		<input type="text" class="wm_input" name="txtTwilioAuthToken" id="txtTwilioAuthToken" value="<?php $this->Data->PrintInputValue('txtTwilioAuthToken') ?>" />
		<div class="wm_information_com">
			<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_TWILIO_AUTH_TOKEN_DESC'); ?>
		</div>
	</td>
</tr>
<tr>
	<td align="left" width="150" valign="top">
		<span id="txtTwilioAppSID_label"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_APP_SID'); ?></span>
	</td>
	<td>
		<input type="text" class="wm_input" name="txtTwilioAppSID" id="txtTwilioAppSID" value="<?php $this->Data->PrintInputValue('txtTwilioAppSID') ?>" />
		<div class="wm_information_com">
			<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_TWILIO_APP_SID_DESC'); ?>
		</div>
	</td>
</tr>
<tr>
	<td colspan="2" style="padding: 0px;">
		<div class="wm_safety_info">
			<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_TWILIO_CONFIGURE_HINT'); ?>
		</div>
	</td>
</tr>