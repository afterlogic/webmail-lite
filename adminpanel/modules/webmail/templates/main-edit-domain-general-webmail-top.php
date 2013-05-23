<table class="wm_contacts_view">
	<tr>
		<td class="wm_field_value">
			<input type="checkbox" class="wm_checkbox override" value="1"
				name="chAllowUsersAccessAccountsSettings" id="chAllowUsersAccessAccountsSettings"
				<?php $this->Data->PrintCheckedValue('chAllowUsersAccessAccountsSettings'); ?> />

			<label id="chAllowUsersAccessAccountsSettings_label" for="chAllowUsersAccessAccountsSettings">
				Allow users to access accounts settings
			</label>
			
			<div class="wm_information_com">
				If the option is checked, users will be able to reconfigure email account access options.
			</div>
		</td>
	</tr>
	<tr>
		<td class="wm_field_value">
			<input type="checkbox" class="wm_checkbox override" value="1"
				name="chAllowNewUsersRegister" id="chAllowNewUsersRegister"
				<?php $this->Data->PrintCheckedValue('chAllowNewUsersRegister'); ?> />

			<label id="chAllowNewUsersRegister_label" for="chAllowNewUsersRegister">
				Only already registered users can access WebMail.
			</label>

			<div class="wm_information_com">
				If set, a user having e-mail account on this <?php if($this->Data->GetValue('IsDefaultDomain', 'bool')) echo 'server'; else echo 'domain';?> will NOT be able to log in unless they already have a WebMail account. Only the admin can add users.
			</div>

			<div class="wm_information_com">
				If not set, a new WebMail account will be auto-provisioned for the user on the first login if the provided credentials denote a valid e-mail account on this <?php if($this->Data->GetValue('IsDefaultDomain', 'bool')) echo 'server'; else echo 'domain';?>.
			</div>

			<?php if($this->Data->GetValue('IsDefaultDomain', 'bool')){ ?>
			<div class="wm_information_com">
				If not set and Advanced Login is on, any user having e-mail account on any server will be able to log in.
			</div>
			<?php } ?>
		</td>
	</tr>
</table>
<br />

