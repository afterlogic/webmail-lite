<table class="wm_contacts_view" style="width: 550px">
	<tr>
		<td align="left">

			<fieldset id="idExternalHosts" style="padding: 14px">

				<legend>Externals</legend>

				<table>
					<tr>
						<td align="left" style="width: 140px">
							<span id="txtExternalHostNameOfDAVServer_label">
								DAV server URL
							</span>
						</td>
						<td align="left">
							<input type="text" class="wm_input" size="40" name="txtExternalHostNameOfDAVServer" id="txtExternalHostNameOfDAVServer"
								value="<?php $this->Data->PrintInputValue('txtExternalHostNameOfDAVServer'); ?>" />
						</td>
					</tr>
					<tr>
						<td align="left">
							<span id="txtExternalHostNameOfLocalImap_label">
								IMAP host name
							</span>
						</td>
						<td align="left">
							<input type="text" class="wm_input" size="40" name="txtExternalHostNameOfLocalImap" id="txtExternalHostNameOfLocalImap"
								value="<?php $this->Data->PrintInputValue('txtExternalHostNameOfLocalImap'); ?>" />
						</td>
					</tr>
					<tr>
						<td align="left">
							<span id="txtExternalHostNameOfLocalSmtp_label">
								SMTP host name
							</span>
						</td>
						<td align="left">
							<input type="text" class="wm_input" size="40" name="txtExternalHostNameOfLocalSmtp" id="txtExternalHostNameOfLocalSmtp"
								value="<?php $this->Data->PrintInputValue('txtExternalHostNameOfLocalSmtp'); ?>" />
						</td>
					</tr>
				</table>

			</fieldset>

		</td>
	</tr>

</table>