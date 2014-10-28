<br />
<table class="wm_contacts_view">

	<tr>
		<td align="left" style="width: 160px">
			<span id="txtOutgoingMailLogin_label">
				<?php echo CApi::I18N('ADMIN_PANEL/USERS_OUT_LOGIN'); ?>
			</span>
		</td>
		<td align="left">
			<input type="text" class="wm_input" name="txtOutgoingMailLogin" id="txtOutgoingMailLogin"
			   value="<?php $this->Data->PrintInputValue('txtOutgoingMailLogin'); ?>" />
		</td>
	</tr>

	<tr>
		<td align="left">
			<span id="txtOutgoingMailLogin_label">
				<?php echo CApi::I18N('ADMIN_PANEL/USERS_OUT_PASS'); ?>
			</span>
		</td>
		<td align="left">
			<input type="password" class="wm_input" name="txtOutgoingMailPassword" id="txtOutgoingMailPassword"
			   value="<?php $this->Data->PrintInputValue('txtOutgoingMailPassword'); ?>" />
		</td>
	</tr>

	<tr>
		<td align="left">
			<span id="txtOutgoingMailHost_label">
				<?php echo CApi::I18N('ADMIN_PANEL/FORM_OUT_MAIL'); ?> *
			</span>
		</td>
		<td align="left">
			<input type="text" class="wm_input" name="txtOutgoingMailHost" id="txtOutgoingMailHost"
			   value="<?php $this->Data->PrintInputValue('txtOutgoingMailHost'); ?>" />

			<span id="txtOutgoingMailPort_label">
				<?php echo CApi::I18N('ADMIN_PANEL/FORM_PORT'); ?> *
			</span>
			<input type="text" class="wm_input" name="txtOutgoingMailPort" id="txtOutgoingMailPort"
			   value="<?php $this->Data->PrintInputValue('txtOutgoingMailPort'); ?>"  size="6" />

			<span class="chOutgoingUseSSL_cont <?php $this->Data->PrintInputValue('classHideSsl'); ?>">
				<input type="checkbox" class="wm_checkbox" name="chOutgoingUseSSL" id="chOutgoingUseSSL" value="1"
					<?php $this->Data->PrintCheckedValue('chOutgoingUseSSL'); ?> />

				<label id="chOutgoingUseSSL_label" for="chOutgoingUseSSL"><?php echo CApi::I18N('ADMIN_PANEL/FORM_SSL'); ?></label>
			</span>
		</td>
	</tr>

	<tr>
		<td align="left"></td>
		<td align="left">
			<input type="checkbox" class="wm_checkbox" name="chOutgoingAuth" id="chOutgoingAuth" value="1"
				<?php $this->Data->PrintCheckedValue('chOutgoingAuth'); ?> />

			<label id="txtOutgoingMailLogin_label" for="chOutgoingAuth">
				<?php echo CApi::I18N('ADMIN_PANEL/FORM_OUT_AUTH'); ?>
			</label>
		</td>
	</tr>

</table>