<table class="wm_contacts_view">
	<tr class="<?php $this->Data->PrintInputValue('classHideUseThreads') ?>">
		<td class="wm_field_value" colspan="2">
			<input type="checkbox" class="wm_checkbox override" value="1"
				name="chUseThreads" id="chUseThreads"
				<?php $this->Data->PrintCheckedValue('chUseThreads'); ?> />

			<label id="chUseThreads_label" for="chUseThreads">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_WEBMAIL_USE_THREADS');?>
			</label>
			<div class="wm_information_com">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_WEBMAIL_USE_THREADS_DESC');?>
			</div>
		</td>
	</tr>
	<tr>
		<td class="wm_field_value" colspan="2">
			<input type="checkbox" class="wm_checkbox override" value="1"
				name="chAllowUsersAddNewAccounts" id="chAllowUsersAddNewAccounts"
				<?php $this->Data->PrintCheckedValue('chAllowUsersAddNewAccounts'); ?> />

			<label id="chAllowUsersAddNewAccounts_label" for="chAllowUsersAddNewAccounts">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_WEBMAIL_EXTERNAL');?>
			</label>
		</td>
	</tr>
	<tr><td class="wm_field_value" colspan="2"></td></tr>
	<tr>
		<td class="wm_field_value" colspan="2">
			<input type="checkbox" class="wm_checkbox override" value="1"
				name="chAllowOpenPGP" id="chAllowOpenPGP"
				<?php $this->Data->PrintCheckedValue('chAllowOpenPGP'); ?> />

			<label id="chAllowUsersAddNewAccounts_label" for="chAllowOpenPGP">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_ALLOW_PGP'); ?>
			</label>
			<div class="wm_information_com">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_ALLOW_PGP_DESC');?>
			</div>
		</td>
	</tr>
</table>