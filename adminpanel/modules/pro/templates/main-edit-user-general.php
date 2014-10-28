<table class="wm_contacts_view">
	<tr>
		<td class="wm_field_value" colspan="2">
			<input type="checkbox" class="wm_checkbox" value="1" name="chEnableUser" id="chEnableUser" <?php $this->Data->PrintCheckedValue('chEnableUser') ?> />
			<label for="chEnableUser" id="chEnableUser_label"><?php echo CApi::I18N('ADMIN_PANEL/USERS_ENABLED'); ?></label>
		</td>
	</tr>
	<tr><td colspan="2"><br /></td></tr>
	<tr>
		<td class="wm_field_title">
			<?php if ($this->Data->GetValueAsBool('domainIsInternal')) { ?>
				<?php echo CApi::I18N('ADMIN_PANEL/USERS_USERNAME'); ?>
			<?php } else { ?>
				<?php echo CApi::I18N('ADMIN_PANEL/USERS_LOGIN'); ?>
			<?php } ?>
		</td>
		<td class="wm_field_value">
			<input name="hiddenDomainId" type="hidden" id="hiddenDomainId" value="<?php $this->Data->PrintInputValue('hiddenDomainId') ?>" />
			<input name="hiddenAccountId" type="hidden" id="hiddenAccountId" value="<?php $this->Data->PrintInputValue('hiddenAccountId') ?>" />
			<input name="hiddenUserId" type="hidden" id="hiddenUserId" value="<?php $this->Data->PrintInputValue('hiddenUserId') ?>" />
			<?php $this->Data->PrintValue('txtEditLogin') ?>
		</td>
	</tr>
	<tr><td colspan="2"></td></tr>
	<tr>
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/USERS_DISPLAY_NAME'); ?>
		</td>
		<td class="wm_field_value">
			<input name="txtFullName" type="text" id="txtFullName" class="wm_input"
				style="width: 150px" maxlength="100" value="<?php $this->Data->PrintInputValue('txtFullName') ?>" />
		</td>
	</tr>
	<?php if ($this->Data->GetValueAsBool('isGlobalContactsSupported')) { ?>
	<tr><td colspan="2"></td></tr>
	<tr>
		<td class="wm_field_title"></td>
		<td class="wm_field_value">
			<input type="checkbox" class="wm_checkbox" value="1" name="chHideInGAB" id="chHideInGAB" <?php $this->Data->PrintCheckedValue('chHideInGAB') ?> />
			<label for="chHideInGAB" id="chHideInGAB"><?php echo CApi::I18N('ADMIN_PANEL/USERS_HIDE_IN_GAB'); ?></label>
		</td>
	</tr>
	<?php } ?>
	<?php if ($this->Data->GetValueAsBool('domainIsInternal')) { ?>
	<tr><td colspan="2"></td></tr>
	<tr>
		<td align="left" width="100">
			<nobr><?php echo CApi::I18N('ADMIN_PANEL/USERS_QUOTA');?></nobr>
		</td>
		<td align="left">
			<input name="txtEditStorageQuota" type="text" id="txtEditStorageQuota" class="wm_input"
				style="width: 150px" maxlength="9" value="<?php $this->Data->PrintInputValue('txtEditStorageQuota') ?>" />
			<?php echo CApi::I18N('ADMIN_PANEL/USERS_QUOTA_MB');?>
		</td>
	</tr>
	<?php } ?>
	<?php if ($this->Data->GetValueAsBool('domainIsInternal')) { ?>
	<tr><td colspan="2"></td></tr>
	<tr>
		<td align="left" width="100">
			<nobr><?php echo CApi::I18N('ADMIN_PANEL/USERS_QUOTA_USED');?></nobr>
		</td>
		<td align="left">
			<?php $this->Data->PrintValue('txtUsedSpaceDesc') ?>
		</td>
	</tr>
	<?php } ?>
	<?php if ($this->Data->GetValueAsBool('subscriptionsSupported')) { ?>
	<tr>
		<td align="left" width="100">
			<span id="selSubscribtions_label">
				<nobr><?php echo CApi::I18N('ADMIN_PANEL/USERS_SUBSCRIPTION'); ?></nobr>
			</span>
		</td>
		<td align="left">
			<?php $this->Data->PrintValue('selSubscribtionsOptions'); ?>
		</td>
	</tr>
	<tr>
		<td align="left" width="100" style="vertical-align: top; padding-top: 8px">
			<span id="selSubscribtions_label">
				Enabled extensions and modules
			</span>
		</td>
		<td align="left" style="vertical-align: top; padding-top: 8px">
			<?php $this->Data->PrintValue('chsSubscribtions'); ?>
		</td>
	</tr>
	<?php } ?>
</table>

