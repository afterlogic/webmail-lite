<table class="wm_contacts_view">
	<tr>
		<td align="left" style="width: 160px">
			<span id="txtOutgoingMailHost_label">
				Outgoing mail *
			</span>
		</td>
		<td align="left">
			<input type="text" class="wm_input" name="txtOutgoingMailHost" id="txtOutgoingMailHost"
			   value="<?php $this->Data->PrintInputValue('txtOutgoingMailHost'); ?>" />

			<span id="txtOutgoingMailPort_label">
				Port *
			</span>
			<input type="text" class="wm_input" name="txtOutgoingMailPort" id="txtOutgoingMailPort"
			   value="<?php $this->Data->PrintInputValue('txtOutgoingMailPort'); ?>"  size="6" />

			<span class="chOutgoingUseSSL_cont <?php $this->Data->PrintInputValue('classHideSsl'); ?>">
				<input type="checkbox" class="wm_checkbox" name="chOutgoingUseSSL" id="chOutgoingUseSSL" value="1"
					<?php $this->Data->PrintCheckedValue('chOutgoingUseSSL'); ?> />

				<label id="chOutgoingUseSSL_label" for="chOutgoingUseSSL">Use SSL</label>
			</span>
		</td>
	</tr>
</table>

<table class="wm_contacts_view" style="width: 550px">
	<tr>
		<td align="left" >

			<fieldset style="padding: 14px">
				<legend>SMTP Authentication</legend>

				<input type="radio" class="wm_checkbox" name="radioAuthType" id="radioAuthTypeNoAuth"
					value="<?php echo EnumConvert::ToPost(ESMTPAuthType::NoAuth, 'ESMTPAuthType');
					?>" <?php $this->Data->PrintCheckedValue('radioAuthTypeNoAuth'); ?> />
				<label id="radioAuthTypeNoAuth_label" for="radioAuthTypeNoAuth">No authentication</label>

				<br /><br />

				<?php if(!$this->Data->GetValue('IsDefaultDomain', 'bool')){ ?>

					<input type="radio" class="wm_checkbox" name="radioAuthType" id="radioAuthTypeAuthSpecified"
						value="<?php echo EnumConvert::ToPost(ESMTPAuthType::AuthSpecified, 'ESMTPAuthType');
						?>" <?php $this->Data->PrintCheckedValue('radioAuthTypeAuthSpecified'); ?> />
					<label id="radioAuthTypeAuthSpecified_label" for="radioAuthTypeAuthSpecified">Use specified login</label>

					&nbsp;&nbsp;&nbsp;

					<input type="text" name="txtOutgoingMailLogin" id="txtOutgoingMailLogin" class="wm_input"
						value="<?php $this->Data->PrintInputValue('txtOutgoingMailLogin'); ?>" />

					<span id="txtOutgoingMailPassword_label">
						password
					</span>
					<input type="password" name="txtOutgoingMailPassword" id="txtOutgoingMailPassword" class="wm_input"
						value="<?php $this->Data->PrintInputValue('txtOutgoingMailPassword'); ?>" />

					<br /><br />

				<?php } ?>

				<input type="radio" class="wm_checkbox" name="radioAuthType" id="radioAuthTypeAuthCurrentUser"
					value="<?php echo EnumConvert::ToPost(ESMTPAuthType::AuthCurrentUser, 'ESMTPAuthType');
					?>" <?php $this->Data->PrintCheckedValue('radioAuthTypeAuthCurrentUser'); ?> />
				<label id="radioAuthTypeAuthCurrentUser_label" for="radioAuthTypeAuthCurrentUser">
					Use incoming mail's login/password of the user
				</label>

			</fieldset>

		</td>
	</tr>

</table>