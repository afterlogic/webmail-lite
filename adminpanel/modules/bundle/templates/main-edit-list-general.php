<input type="hidden" name="hiddenMailingListId" id="hiddenMailingListId" value="<?php $this->Data->PrintInputValue('hiddenMailingListId'); ?>" />
<input type="hidden" name="hiddenDomainId" id="hiddenDomainId" value="<?php $this->Data->PrintInputValue('hiddenDomainId'); ?>" />
<table class="wm_contacts_view">
	<tr>
		<td align="left">
			<?php echo CApi::I18N('ADMIN_PANEL/LISTS_NAME'); ?>:
			<br />
			<input name="txtMailingListFriendlyName" type="text" id="txtMailingListFriendlyName" class="wm_input"
				style="width: 250px" maxlength="250" value="<?php $this->Data->PrintInputValue('txtMailingListFriendlyName'); ?>" />
		</td>
	</tr>
	<tr><td><br /></td></tr>
	<tr>
		<td align="left">
			<?php echo CApi::I18N('ADMIN_PANEL/LISTS_NEW_USER'); ?>:
			<br />
			<input type="text" name="txtNewUserAddress" id="txtNewUserAddress" class="wm_input" value="" />
			<br />
			<input type="button" name="btnAddUser" id="btnAddUser" class="wm_button" value="<?php echo CApi::I18N('ADMIN_PANEL/LISTS_ADD'); ?>" />
			<br /><br /><br />
			<?php echo CApi::I18N('ADMIN_PANEL/LISTS_MEMBERS'); ?>:
			<br />
			<select size="9" name="selListMembersDDL[]" id="selListMembersDDL" tabindex="15" class="wm_input" style="width: 250px;" multiple="multiple">
				<?php $this->Data->PrintValue('selListMembersDDL'); ?>
			</select>
			<br />
			<input type="button" name="btnDeleteUser" id="btnDeleteUser" class="wm_button" value="<?php echo CApi::I18N('ADMIN_PANEL/LISTS_DEL'); ?>" />
		</td>
	</tr>
</table>