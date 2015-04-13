<table class="wm_contacts_view">
	<tr class="<?php $this->Data->PrintInputValue('classHideDefault') ?>">
		<td align="left" colspan="3">
			<a href="?tab=users<?php $this->Data->PrintInputValue('txtFilteHrefAdd') ?>"><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_USERLIST'); ?></a>
		</td>
	</tr>
	<tr class="<?php $this->Data->PrintInputValue('classHideOverrideSettings') ?>">
		<td align="left" colspan="3">
			<input name="hiddenDomainId" type="hidden" value="<?php $this->Data->PrintInputValue('hiddenDomainId') ?>" />
			<input name="chOverrideSettings" type="checkbox" id="chOverrideSettings" class="wm_checkbox domain_id_<?php $this->Data->PrintInputValue('hiddenDomainId') ?>" value="1"
				<?php $this->Data->PrintCheckedValue('chOverrideSettings') ?> />
			<label for="chOverrideSettings" id="chOverrideSettings_label"><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_DEFAULT_OVERRIDE'); ?></label>
		</td>
	</tr>
</table>