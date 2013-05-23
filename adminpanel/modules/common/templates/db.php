<tr>
	<td class="wm_settings_list_select" colspan="2">
		<b>MySQL Connection Settings</b>
	</td>
</tr>
<tr>
	<td colspan="2">
		<br />
	</td>
</tr>
<tr>
	<td align="left" width="200">
		<span id="txtSqlLogin_label">SQL&nbsp;login</span>
	</td>
	<td>
		<input type="text" class="wm_input" name="txtSqlLogin" id="txtSqlLogin" value="<?php $this->Data->PrintInputValue('txtSqlLogin') ?>" size="35" />
	</td>
</tr>
<tr>
	<td align="left">
		<span id="txtSqlPassword_label">SQL&nbsp;password</span>
	</td>
	<td>
		<input type="password" class="wm_input" name="txtSqlPassword" id="txtSqlPassword" value="<?php $this->Data->PrintInputValue('txtSqlPassword') ?>" size="35" />
	</td>
</tr>
<tr>
	<td align="left">
		<span id="txtSqlName_label">Database&nbsp;name</span>
	</td>
	<td>
		<input type="text" class="wm_input" name="txtSqlName" id="txtSqlName" value="<?php $this->Data->PrintInputValue('txtSqlName') ?>" size="35" />
	</td>
</tr>
<tr>
	<td align="left">
		<span id="txtSqlSrc_label">Host</span>
	</td>
	<td>
		<input type="text" class="wm_input" name="txtSqlSrc" id="txtSqlSrc" value="<?php $this->Data->PrintInputValue('txtSqlSrc') ?>" size="35" />
	</td>
</tr>

<tr>
	<td align="left">
	</td>
	<td>
		<input type="hidden" name="isTestConnection" id="isTestConnection" value="0" />
		<input type="button" name="test_btn" id="test_btn" value="Test connection" class="wm_button" style="font-size: 11px;" />
	</td>
</tr>

<tr><td colspan="2"><br /></td></tr>
<tr>
	<td colspan="2" valign="top">
		<input type="button" name="create_btn" id="create_btn" value="Create tables" class="wm_button" style="font-size: 11px;" />
		<input type="button" name="update_btn" id="update_btn" value="Update tables" class="wm_button" style="font-size: 11px;" />
	</td>
</tr>