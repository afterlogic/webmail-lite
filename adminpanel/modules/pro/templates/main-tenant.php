<input name="intTenantId" type="hidden" value="<?php $this->Data->PrintInputValue('intTenantId'); ?>" />
<table class="wm_contacts_view">
	<tr class="<?php $this->Data->PrintInputValue('hideClassForEditTenant'); ?>">
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_NAME'); ?> *
		</td>
		<td class="wm_field_value" colspan="2">
			<input name="txtLogin" type="text" id="txtLogin" class="wm_input" style="width: 350px" maxlength="100" value="<?php $this->Data->PrintInputValue('txtLogin'); ?>" />
		</td>
	</tr>
	<tr class="<?php $this->Data->PrintInputValue('hideClassForNewTenant'); ?>">
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_NAME'); ?> *
		</td>
		<td class="wm_field_value" colspan="2">
			<strong>
				<?php $this->Data->PrintValue('txtLogin'); ?>
				<?php $this->Data->PrintValue('txtChannelAdd'); ?>
			</strong>
		</td>
	</tr>
	<tr class="<?php $this->Data->PrintInputValue('hideClassForEditTenant'); ?>">
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_CHANNEL'); ?>
		</td>
		<td class="wm_field_value" colspan="2">
			<input name="txtChannel" type="text" id="txtChannel" class="wm_input" style="width: 350px" maxlength="100" value="<?php $this->Data->PrintInputValue('txtChannel'); ?>" />

			<div class="wm_information_com">
				<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_CHANNEL_DESC'); ?>
			</div>
		</td>
	</tr>
	<tr>
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_ADMIN_EMAIL'); ?>
		</td>
		<td class="wm_field_value" colspan="2">
			<input name="txtEmail" type="text" id="txtEmail" class="wm_input" style="width: 350px" maxlength="100" value="<?php $this->Data->PrintInputValue('txtEmail'); ?>" />

			<div class="wm_information_com">
				<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_ADMIN_EMAIL_DESC'); ?>
			</div>
		</td>
	</tr>
	<tr>
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_PASSWORD'); ?>
		</td>
		<td class="wm_field_value" colspan="2">
			<input name="txtPassword" type="password" maxlength="100" id="txtPassword" class="wm_input" style="width: 350px;" value="<?php $this->Data->PrintInputValue('txtPassword'); ?>" />
		</td>
	</tr>
	<tr>
		<td class="wm_field_title">
		</td>
		<td class="wm_field_value" colspan="2">
			<input name="chEnableAdminLogin" type="checkbox" id="chEnableAdminLogin" value="1" class="wm_checkbox"
				<?php $this->Data->PrintCheckedValue('chEnableAdminLogin'); ?> />
			<label for="chEnableAdminLogin">
				<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_ENABLE_ADMIN_ACCESS'); ?>
			</label>
			<div class="wm_information_com">
				<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_ENABLE_ADMIN_ACCESS_DESC'); ?>
			</div>
		</td>
	</tr>
	<tr>
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_USER_LIMIT'); ?>
		</td>
		<td class="wm_field_value" colspan="2">
			<input name="txtUserLimit" type="text" maxlength="10" id="txtUserLimit" class="wm_input" style="width: 100px;" value="<?php $this->Data->PrintInputValue('txtUserLimit'); ?>" />
			&nbsp;
			<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_USER_LIMIT2'); ?>
			&nbsp;
			<?php $this->Data->PrintValue('txtUserLimitDesk'); ?>
		</td>
	</tr>
	<tr class="<?php $this->Data->PrintInputValue('hideClassForSubscription'); ?>">
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_SUBSCRIPTIONS'); ?>
		</td>
		<td class="wm_field_value" colspan="2">
			<?php $this->Data->PrintValue('txtSubscriptionPlans'); ?>
			<br />
		</td>
	</tr>
	<tr>
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_QUOTA'); ?>
		</td>
		<td class="wm_field_value" colspan="2">
			<input name="txtQuota" type="text" maxlength="10" id="txtQuota" class="wm_input" style="width: 100px;" value="<?php $this->Data->PrintInputValue('txtQuota'); ?>" />
			&nbsp;
			<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_QUOTA_MB'); ?>
			&nbsp;
			<?php $this->Data->PrintValue('txtUsedText'); ?>

			<div class="wm_information_com">
				<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_QUOTA_DESC'); ?>
			</div>
		</td>
	</tr>
	<tr>
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_DESCRIPTION'); ?>
		</td>
		<td class="wm_field_value" colspan="2">
			<input name="txtDescription" type="text" maxlength="255" id="txtDescription" class="wm_input" style="width: 350px;" value="<?php $this->Data->PrintInputValue('txtDescription'); ?>" />
		</td>
	</tr>
	<tr class="<?php $this->Data->PrintInputValue('classCapa'); ?>">
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_CAPA'); ?>
		</td>
		<td class="wm_field_value" colspan="2">
			<input name="txtCapa" type="text" maxlength="255" id="txtCapa" class="wm_input" style="width: 350px;" value="<?php $this->Data->PrintInputValue('txtCapa'); ?>" />
		</td>
	</tr>
	<tr class="<?php $this->Data->PrintInputValue('hideClassForNewTenant'); ?>">
		<td class="wm_field_title">
			<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_DOMAINS'); ?>
		</td>
		<td class="wm_field_value" colspan="2">
			<select class="wm_input" size="5" multiple="multiple" style="width: 353px">
				<?php $this->Data->PrintValue('selDomains'); ?>
			</select>
			<div class="wm_information_com">
				<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_DOMAINS_DESC'); ?>
			</div>
		</td>
	</tr>
	<tr class="<?php $this->Data->PrintInputValue('hideClassForNewTenant'); ?>">
		<td class="wm_field_title">
		</td>
		<td class="wm_field_value" colspan="2">
			<input name="chTenantEnabled" type="checkbox" id="chTenantEnabled" value="1" class="wm_checkbox"
				<?php $this->Data->PrintCheckedValue('chTenantEnabled'); ?> />

			<label for="chTenantEnabled">
				<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_ENABLED'); ?>
			</label>
			<div class="wm_information_com">
				<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_ENABLED_DESC'); ?>
			</div>
		</td>
	</tr>
	<tr>
		<td class="wm_field_title">
		</td>
		<td class="wm_field_value" colspan="2">
			<input name="chTenantSipConfiguration" type="checkbox" id="chTenantSipConfiguration" value="1" class="wm_checkbox"
				<?php $this->Data->PrintCheckedValue('chTenantSipConfiguration'); ?> />

			<label for="chTenantSipConfiguration">
				<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_ENABLE_ACCESS_TO_SIP_FOR_ADMIN'); ?>
			</label>
			<div class="wm_information_com">
				<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_ENABLE_ACCESS_DESC'); ?>
			</div>
		</td>
	</tr>
	<tr>
		<td class="wm_field_title">
		</td>
		<td class="wm_field_value" colspan="2">
			<input name="chTenantTwilioConfiguration" type="checkbox" id="chTenantTwilioConfiguration" value="1" class="wm_checkbox"
				<?php $this->Data->PrintCheckedValue('chTenantTwilioConfiguration'); ?> />

			<label for="chTenantTwilioConfiguration">
				<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_ENABLE_ACCESS_TO_TWILIO_FOR_ADMIN'); ?>
			</label>
			<div class="wm_information_com">
				<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_ENABLE_ACCESS_TO_TWILIO_DESC'); ?>
			</div>
		</td>
	</tr>
</table>
