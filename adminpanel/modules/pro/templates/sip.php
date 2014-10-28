<tr>
	<td align="left" width="150">
	</td>
	<td>
		<input type="checkbox" class="wm_checkbox" name="chAllowSip" id="chAllowSip" value="1" <?php $this->Data->PrintCheckedValue('chAllowSip') ?>/>
		<label id="chAllowSip_label" for="chAllowSip">
			<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_SIP_ALLOW'); ?></span>
		</label>
	</td>
</tr>
<tr>
	<td align="left" width="150" valign="top">
		<span id="txtSipRealm_label"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_SIP_REALM'); ?></span>
	</td>
	<td>
		<input type="text" class="wm_input" name="txtSipRealm" id="txtSipRealm" value="<?php $this->Data->PrintInputValue('txtSipRealm') ?>" />
		<div class="wm_information_com">
			<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_SIP_REALM_DESC'); ?>
		</div>
	</td>
</tr>
<tr>
	<td align="left" width="150" valign="top">
		<span id="txtSipWebsocketProxyUrl_label"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_SIP_WEBSOCKET_PROXY_URL'); ?></span>
	</td>
	<td>
		<input type="text" class="wm_input" name="txtSipWebsocketProxyUrl" id="txtSipWebsocketProxyUrl" 
			value="<?php $this->Data->PrintInputValue('txtSipWebsocketProxyUrl') ?>" size="50" />
		<div class="wm_information_com">
			<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_SIP_WEBSOCKET_PROXY_URL_DESC'); ?>
		</div>
	</td>
</tr>
<tr>
	<td align="left" width="150" valign="top">
		<span id="txtSipOutboundProxyUrl_label"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_SIP_OUTBOUND_PROXY_URL'); ?></span>
	</td>
	<td>
		<input type="text" class="wm_input" name="txtSipOutboundProxyUrl" id="txtSipOutboundProxyUrl"
			value="<?php $this->Data->PrintInputValue('txtSipOutboundProxyUrl') ?>" size="50" />
		<div class="wm_information_com">
			<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_SIP_OUTBOUND_PROXY_URL_DESC'); ?>
		</div>
	</td>
</tr>
<tr>
	<td align="left" width="150" valign="top">
		<span id="txtSipOutboundProxyUrl_label"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_SIP_CALLER_ID'); ?></span>
	</td>
	<td>
		<input type="text" class="wm_input" name="txtSipCallerID" id="txtSipCallerID"
			value="<?php $this->Data->PrintInputValue('txtSipCallerID') ?>" size="50" />
		<div class="wm_information_com">
			<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_SIP_CALLER_ID_DESC'); ?>
		</div>
	</td>
</tr>