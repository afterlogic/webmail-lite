<table class="wm_contacts_view">
	<tr>
		<td align="left">
			<input type="checkbox" class="wm_checkbox override" name="chEnableSignUp" id="chEnableSignUp"
				   value="1" <?php $this->Data->PrintCheckedValue('chEnableSignUp'); ?> />
			<label id="chEnableSignUp_label" for="chEnableSignUp"><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_ENABLE_SIGNUP'); ?></label>
		</td>
	</tr>
	<tr>
		<td align="left">
			<input type="checkbox" class="wm_checkbox override" name="chAllowUsersResetPassword" id="chAllowUsersResetPassword"
				   value="1" <?php $this->Data->PrintCheckedValue('chAllowUsersResetPassword'); ?> />
			<label id="chAllowUsersResetPassword_label" for="chAllowUsersResetPassword"><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_ENABLE_RESET'); ?></label>
		</td>
	</tr>
</table>
<br />