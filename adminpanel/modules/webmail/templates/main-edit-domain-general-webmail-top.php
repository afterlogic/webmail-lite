<table class="wm_contacts_view">
	<tr>
		<td class="wm_field_value">
			<input type="checkbox" class="wm_checkbox override" value="1"
				name="chAllowUsersAccessAccountsSettings" id="chAllowUsersAccessAccountsSettings"
				<?php $this->Data->PrintCheckedValue('chAllowUsersAccessAccountsSettings'); ?> />

			<label id="chAllowUsersAccessAccountsSettings_label" for="chAllowUsersAccessAccountsSettings">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_ACCESS_SETTINGS'); ?>
			</label>
			
			<div class="wm_information_com">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_ACCESS_SETTINGS_HINT'); ?>
			</div>
		</td>
	</tr>
	<tr<?php if ($this->Data->GetValueAsBool('domainIsInternal')) { echo ' style="display:none"'; } ?>>
		<td class="wm_field_value">
			<input type="checkbox" class="wm_checkbox override" value="1"
				name="chAllowNewUsersRegister" id="chAllowNewUsersRegister"
				<?php $this->Data->PrintCheckedValue('chAllowNewUsersRegister'); ?> />

			<label id="chAllowNewUsersRegister_label" for="chAllowNewUsersRegister">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_REGISTERED_ONLY');?>
			</label>

			<div class="wm_information_com">
				<?php if($this->Data->GetValue('IsDefaultDomain', 'bool')) echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_REGISTERED_ONLY_HINT_SERVER'); else echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_REGISTERED_ONLY_HINT_DOMAIN');?>
			</div>

			<div class="wm_information_com">
				<?php if($this->Data->GetValue('IsDefaultDomain', 'bool')) echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_REGISTERED_ONLY_HINT_NOTSET_SERVER'); else echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_REGISTERED_ONLY_HINT_NOTSET_DOMAIN'); ?>
			</div>
		</td>
	</tr>
</table>
<br />

