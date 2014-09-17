<table class="wm_contacts_view">
	<tr>
		<td align="left" style="width: 160px">
			<span id="txtOutgoingMailHost_label">
				<?php echo CApi::I18N('ADMIN_PANEL/FORM_OUT_MAIL'); ?>  *
			</span>
		</td>
		<td align="left">
			<input type="text" class="wm_input" name="txtOutgoingMailHost" id="txtOutgoingMailHost"
			   value="<?php $this->Data->PrintInputValue('txtOutgoingMailHost'); ?>" />

			<span id="txtOutgoingMailPort_label">
				<?php echo CApi::I18N('ADMIN_PANEL/FORM_PORT'); ?>  *
			</span>
			<input type="text" class="wm_input" name="txtOutgoingMailPort" id="txtOutgoingMailPort"
			   value="<?php $this->Data->PrintInputValue('txtOutgoingMailPort'); ?>"  size="6" />

			<span class="chOutgoingUseSSL_cont <?php $this->Data->PrintInputValue('classHideSsl'); ?>">
				<input type="checkbox" class="wm_checkbox" name="chOutgoingUseSSL" id="chOutgoingUseSSL" value="1"
					<?php $this->Data->PrintCheckedValue('chOutgoingUseSSL'); ?> />

				<label id="chOutgoingUseSSL_label" for="chOutgoingUseSSL"><?php echo CApi::I18N('ADMIN_PANEL/FORM_SSL'); ?> </label>
			</span>
		</td>
	</tr>
</table>

<table class="wm_contacts_view" style="width: 550px">
	<tr>
		<td align="left" >

			<fieldset style="padding: 14px">
				<legend><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_SMTP_AUTH'); ?></legend>

				<input type="radio" class="wm_checkbox" name="radioAuthType" id="radioAuthTypeNoAuth"
					value="<?php echo EnumConvert::ToPost(ESMTPAuthType::NoAuth, 'ESMTPAuthType');
					?>" <?php $this->Data->PrintCheckedValue('radioAuthTypeNoAuth'); ?> />
				<label id="radioAuthTypeNoAuth_label" for="radioAuthTypeNoAuth"><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_SMTP_AUTH_NO'); ?></label>

				<br /><br />

				<?php if(!$this->Data->GetValue('IsDefaultDomain', 'bool')){ ?>

					<input type="radio" class="wm_checkbox" name="radioAuthType" id="radioAuthTypeAuthSpecified"
						value="<?php echo EnumConvert::ToPost(ESMTPAuthType::AuthSpecified, 'ESMTPAuthType');
						?>" <?php $this->Data->PrintCheckedValue('radioAuthTypeAuthSpecified'); ?> />
					<label id="radioAuthTypeAuthSpecified_label" for="radioAuthTypeAuthSpecified"><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_SMTP_AUTH_LOGIN'); ?></label>

					&nbsp;&nbsp;&nbsp;

					<input type="text" name="txtOutgoingMailLogin" id="txtOutgoingMailLogin" class="wm_input"
						value="<?php $this->Data->PrintInputValue('txtOutgoingMailLogin'); ?>" />

					<span id="txtOutgoingMailPassword_label">
						<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_SMTP_AUTH_PASS'); ?>
					</span>
					<input type="password" name="txtOutgoingMailPassword" id="txtOutgoingMailPassword" class="wm_input"
						value="<?php $this->Data->PrintInputValue('txtOutgoingMailPassword'); ?>" />

					<br /><br />

				<?php } ?>

				<input type="radio" class="wm_checkbox" name="radioAuthType" id="radioAuthTypeAuthCurrentUser"
					value="<?php echo EnumConvert::ToPost(ESMTPAuthType::AuthCurrentUser, 'ESMTPAuthType');
					?>" <?php $this->Data->PrintCheckedValue('radioAuthTypeAuthCurrentUser'); ?> />
				<label id="radioAuthTypeAuthCurrentUser_label" for="radioAuthTypeAuthCurrentUser">
					<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_SMTP_AUTH_USER'); ?>
				</label>

			</fieldset>

		</td>
	</tr>

</table>