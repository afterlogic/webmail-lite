<table class="wm_contacts_view">
	<tr>
		<td align="left" width="100">
			<nobr><?php echo CApi::I18N('ADMIN_PANEL/USERS_PASSWORD');?> *</nobr>
		</td>
		<td align="left">
			<input name="txtEditPassword" type="password" id="txtEditPassword" class="wm_input"
				style="width: 150px" maxlength="100" value="<?php $this->Data->PrintInputValue('txtEditPassword') ?>" />
		</td>
	</tr>
<!--	<tr>
		<td align="left" width="100">
			<nobr><?php echo CApi::I18N('ADMIN_PANEL/USERS_QUOTA');?></nobr>
		</td>
		<td align="left">
			<input name="txtEditStorageQuota" type="text" id="txtEditStorageQuota" class="wm_input"
				style="width: 150px" maxlength="9" value="<?php $this->Data->PrintInputValue('txtEditStorageQuota') ?>" />
			<?php echo CApi::I18N('ADMIN_PANEL/USERS_QUOTA_MB');?>
		</td>
	</tr>
	<tr>
		<td align="left" width="100">
			<nobr><?php echo CApi::I18N('ADMIN_PANEL/USERS_QUOTA_USED');?></nobr>
		</td>
		<td align="left">
			<?php $this->Data->PrintValue('txtUsedSpaceDesc') ?>
		</td>
	</tr>-->
</table>