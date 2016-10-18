<tr>
	<td align="left" width="200">
		<span id="txtUserName_label"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_SECURITY_USERNAME'); ?></span>
	</td>
	<td>
		<input type="text" class="wm_input" name="txtUserName" id="txtUserName" value="<?php $this->Data->PrintInputValue('txtUserName') ?>" size="30" />
	</td>
</tr>
<tr>
	<td align="left">
		<span id="txtOldPassword_label"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_SECURITY_PASSWORD_OLD'); ?></span>
	</td>
	<td>
		<input type="password" class="wm_input" name="txtOldPassword" id="txtOldPassword" value="<?php $this->Data->PrintInputValue('txtOldPassword') ?>" size="30" />
	</td>
</tr>
<tr>
	<td align="left">
		<span id="txtNewPassword_label"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_SECURITY_PASSWORD'); ?></span>
	</td>
	<td>
		<input type="password" class="wm_input" name="txtNewPassword" id="txtNewPassword" value="<?php $this->Data->PrintInputValue('txtNewPassword') ?>" size="30" />
	</td>
</tr>
<tr>
	<td align="left">
		<span id="txtConfirmNewPassword_label"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_SECURITY_CONFIRM_PASSWORD'); ?></span>
	</td>
	<td>
		<input type="password" class="wm_input" name="txtConfirmNewPassword" id="txtConfirmNewPassword" value="<?php $this->Data->PrintInputValue('txtConfirmNewPassword') ?>" size="30" />
	</td>
</tr>

<input type="hidden" name="txtToken" value="<?php $this->Data->PrintInputValue('txtToken') ?>" />