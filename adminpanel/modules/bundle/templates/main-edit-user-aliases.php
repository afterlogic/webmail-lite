<table class="wm_contacts_view">
	<tr>
		<td align="left">
			<input type="hidden" name="hiddenAccountId" id="hiddenAccountId" value="<?php $this->Data->PrintInputValue('hiddenAccountId'); ?>" />
			<input type="hidden" name="hiddenDomainId" id="hiddenDomainId" value="<?php $this->Data->PrintInputValue('hiddenDomainId'); ?>" />
			<input type="hidden" name="hiddenDomainName" id="hiddenDomainName" value="<?php $this->Data->PrintInputValue('DomainName'); ?>" />
				<?php echo CApi::I18N('ADMIN_PANEL/ALIASES_ACCOUNT'); ?>:
			<br />
			<input type="text" name="txtNewUserAlias" id="txtNewUserAlias" class="wm_input" value="" /><span style="font-size: large;">@<?php $this->Data->PrintValue('DomainName'); ?></span>
			<br />
			<input type="button" name="btnAddUserAlias" id="btnAddUserAlias" class="wm_button" value="<?php echo CApi::I18N('ADMIN_PANEL/ALIASES_ADD'); ?>" />
			<br /><br /><br />
			<select size="9" name="selAliasesDDL[]" id="selAliasesDDL" tabindex="15" class="wm_input" style="width: 250px;" multiple="multiple">
				<?php $this->Data->PrintValue('selAliasesDDL'); ?>
			</select>
			<br />
			<input type="button" name="btnDeleteUserAlias" id="btnDeleteUserAlias" class="wm_button" value="<?php echo CApi::I18N('ADMIN_PANEL/ALIASES_DEL'); ?>" />
		</td>
	</tr>
</table>