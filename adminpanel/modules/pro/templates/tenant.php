
<tr>
	<td align="left" width="150">
		<span><?php echo CApi::I18N('ADMIN_PANEL/TENANTS_NAME'); ?></span>
	</td>
	<td align="left">
		<b><?php $this->Data->PrintEncodedHtmlValue('txtTenantName') ?></b>
	</td>
</tr>

<tr>
	<td align="left" valign="top">
		<span><?php echo CApi::I18N('ADMIN_PANEL/TENANTS_ADMIN_EMAIL'); ?></span>
	</td>
	<td align="left">
		<input class="wm_input <?php $this->Data->PrintInputValue('classTenantEmailInputHideClass') ?>" name="txtTenantAdminEmail" id="txtTenantAdminEmail" value="<?php $this->Data->PrintInputValue('txtTenantAdminEmailInput') ?>" />
		<b class="<?php $this->Data->PrintInputValue('classTenantEmailTextHideClass') ?>"><?php $this->Data->PrintEncodedHtmlValue('txtTenantAdminEmailText') ?></b>

		<div class="wm_information_com">
			<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_ADMIN_EMAIL_DESC'); ?>
		</div>
	</td>
</tr>

<tr class="<?php $this->Data->PrintInputValue('classTenantPasswordHideClass') ?>">
	<td align="left">
		<span><?php echo CApi::I18N('ADMIN_PANEL/TENANTS_PASSWORD'); ?></span>
	</td>
	<td align="left">
		<input type="password" class="wm_input" name="txtTenantPassword" id="txtTenantPassword" value="<?php $this->Data->PrintInputValue('txtTenantPassword') ?>" />
	</td>
</tr>
