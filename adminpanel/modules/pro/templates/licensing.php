<tr>
	<td colspan="2">
		<div class="wm_safety_info">
			<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LICENSING_HINT'); ?>
		</div>
	</td>
</tr>
<tr>
	<td align="left" width="200">
		<span id="txtLicenseKey_label"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LICENSING_KEY'); ?></span>
	</td>
	<td>
		<input type="text" class="wm_input" name="txtLicenseKey" id="txtLicenseKey" value="<?php $this->Data->PrintInputValue('txtLicenseKey') ?>" size="60" />
	</td>
</tr>
<tr>
	<td align="left">
		<span id="txtCurrentNumberOfUsers_label"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LICENSING_USERS'); ?></span>
	</td>
	<td>
		<b><?php $this->Data->PrintValue('txtCurrentNumberOfUsers') ?></b>
	</td>
</tr>
<tr>
	<td align="left">
		<span id="txtLicenseType_label"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LICENSING_TYPE'); ?></span>
	</td>
	<td>
		<b><?php $this->Data->PrintValue('txtLicenseType') ?></b>
	</td>
</tr>

<tr class="<?php $this->Data->PrintValue('classHideTrialText') ?>">
	<td>
	</td>
	<td>
		<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LICENSING_TRIAL'); ?>
		<a href="<?php $this->Data->PrintInputValue('linkLicensePurchase') ?>" target="_blank"><?php $this->Data->PrintValue('linkLicensePurchase') ?></a>
	</td>
</tr>




