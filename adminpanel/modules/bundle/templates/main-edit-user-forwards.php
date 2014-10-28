<table class="wm_contacts_view">
	<tr>
		<td align="left">
			<input type="hidden" name="hiddenAccountId" id="hiddenAccountId" value="<?php $this->Data->PrintInputValue('hiddenAccountId'); ?>" />
			<input type="hidden" name="hiddenDomainId" id="hiddenDomainId" value="<?php $this->Data->PrintInputValue('hiddenDomainId'); ?>" />
			Account Forwards:
			<br />
			<input type="text" name="txtNewUserForward" id="txtNewUserForward" class="wm_input" value="" />
			<br />
			<input type="button" name="btnAddUserForward" id="btnAddUserForward" class="wm_button" value="Add New Forward" />
			<br /><br /><br />
			<select size="9" name="selForwardsDDL[]" id="selForwardsDDL" tabindex="15" class="wm_input" style="width: 250px;" multiple="multiple">
				<?php $this->Data->PrintValue('selForwardsDDL'); ?>
			</select>
			<br />
			<input type="button" name="btnDeleteUserForward" id="btnDeleteUserForward" class="wm_button" value="Delete Forward" />
		</td>
	</tr>
</table>