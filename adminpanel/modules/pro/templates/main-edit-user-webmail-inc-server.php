<br />
<table class="wm_contacts_view">
	<tr>
		<td align="left" style="width: 160px">
			<span id="txtIncomingMailLogin_label">
				<?php echo CApi::I18N('ADMIN_PANEL/USERS_INC_LOGIN'); ?> *
			</span>
		</td>
		<td align="left">
			<input type="text" class="wm_input" name="txtIncomingMailLogin" id="txtIncomingMailLogin"
			   value="<?php $this->Data->PrintInputValue('txtIncomingMailLogin'); ?>" />
		</td>
	</tr>
	<tr>
		<td align="left">
			<nobr>
				<span id="txtIncomingMailHost_label">
					<?php echo CApi::I18N('ADMIN_PANEL/FORM_INC_MAIL'); ?> *
				</span>
				<span style="display: none">
					<select name="selIncomingMailProtocol" id="selIncomingMailProtocol" class="wm_input">
						<option <?php $this->Data->PrintSelectedValue('optIncomingProtocolIMAP') ?>
							value="<?php echo EnumConvert::ToPost(EMailProtocol::IMAP4, 'EMailProtocol'); ?>">IMAP</option>
						<option <?php $this->Data->PrintSelectedValue('optIncomingProtocolPOP3') ?>
							value="<?php echo EnumConvert::ToPost(EMailProtocol::POP3, 'EMailProtocol'); ?>">POP3</option>
					</select>
				</span>
			</nobr>
		</td>
		<td align="left">
			<input type="text" class="wm_input" name="txtIncomingMailHost" id="txtIncomingMailHost"
			   value="<?php $this->Data->PrintInputValue('txtIncomingMailHost'); ?>" />

			<span id="txtIncomingMailPort_label">
				<?php echo CApi::I18N('ADMIN_PANEL/FORM_PORT'); ?> *
			</span>
			<input type="text" class="wm_input" name="txtIncomingMailPort" id="txtIncomingMailPort"
			   value="<?php $this->Data->PrintInputValue('txtIncomingMailPort'); ?>" size="6" />
			
			<span class="chIncomingUseSSL_cont <?php $this->Data->PrintInputValue('classHideSsl'); ?>">
				<input type="checkbox" class="wm_checkbox" name="chIncomingUseSSL" id="chIncomingUseSSL" value="1"
					<?php $this->Data->PrintCheckedValue('chIncomingUseSSL'); ?> />

				<label id="chIncomingUseSSL_label" for="chIncomingUseSSL"><?php echo CApi::I18N('ADMIN_PANEL/FORM_SSL'); ?></label>
			</span>
		</td>
	</tr>
</table>