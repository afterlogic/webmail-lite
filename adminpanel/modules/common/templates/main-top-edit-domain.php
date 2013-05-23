<table class="wm_contacts_view">
	<tr class="<?php $this->Data->PrintInputValue('classHideDefault') ?>">
		<td align="left" colspan="3">
			<a href="?tab=users<?php $this->Data->PrintInputValue('txtFilteHrefAdd') ?>">Show users of this domain</a>
		</td>
	</tr>
	<tr class="<?php $this->Data->PrintInputValue('classHideDefault') ?>">
		<td align="left" colspan="3">
			<input name="hiddenDomainId" type="hidden" value="<?php $this->Data->PrintInputValue('hiddenDomainId') ?>" />
			<input name="chOverrideSettings" type="checkbox" id="chOverrideSettings" class="wm_checkbox" value="1"
				<?php $this->Data->PrintCheckedValue('chOverrideSettings') ?> />
			<label for="chOverrideSettings">Override default domain settings</label>
		</td>
	</tr>
</table>