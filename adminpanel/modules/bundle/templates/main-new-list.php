<input name="hiddenDomainId" type="hidden" id="hiddenDomainId" value="<?php $this->Data->PrintInputValue('hiddenDomainId'); ?>" />
<table class="wm_contacts_view">
	<tr>
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/LISTS_NAME'); ?>:
		</td>
		<td class="wm_field_value">
			<input name="txtMailingListFriendlyName" type="text" id="txtMailingListFriendlyName" class="wm_input"
				style="width: 250px" maxlength="250" value="" />
		</td>
	</tr>
	<tr>
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/LISTS_USERNAME'); ?>:
		</td>
		<td class="wm_field_value">
			<input name="txtMailingListUserName" type="text" id="txtMailingListUserName" class="wm_input"
				style="width: 150px" maxlength="50" value="" />
			
			<span style="font-size: large;">@<?php $this->Data->PrintValue('txtNewMailingListDomain'); ?></span>
		</td>
	</tr>
</table>