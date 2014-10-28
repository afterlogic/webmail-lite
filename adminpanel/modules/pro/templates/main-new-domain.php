<table class="wm_contacts_view">
	<tr>
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_DOMAIN_NAME'); ?> *
		</td>
		<td class="wm_field_value">
			<input name="txtDomainName" type="text" id="txtDomainName" class="wm_input" style="width: 200px" maxlength="50" value="" />
		</td>
	</tr>

	<tr class="<?php $this->Data->PrintInputValue('classHideTenantName') ?>">
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_TENANT_NAME'); ?>
		</td>
		<td class="wm_field_value">
			<input name="txtTenantName" type="text" id="txtTenantName" class="wm_input" style="width: 200px" value="" />

			<div class="wm_information_com">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_TENANT_HINT'); ?>
			</div>
		</td>
	</tr>
</table>