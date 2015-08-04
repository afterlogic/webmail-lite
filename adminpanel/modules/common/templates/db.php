<tr>
	<td class="wm_settings_list_select" colspan="2">
		<b><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_DATABASE_TITLE'); ?></b>
	</td>
</tr>
<tr>
	<td colspan="2">
		<br />
	</td>
</tr>
<tr class="<?php echo $this->Data->PrintValue('classSqlTypeVisibility'); ?>">
	<td align="left" width="200">
		<span id="txtSqlType_label"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_DATABASE_TYPE'); ?></span>
	</td>
	<td>
		<input type="radio" class="wm_checkbox" name="radioSqlType" id="radioSqlTypeMySQL"
			value="<?php echo EnumConvert::ToPost(EDbType::MySQL, 'EDbType'); ?>" <?php
			$this->Data->PrintCheckedValue('radioSqlTypeMySQL'); ?> x-data-label="radioSqlType_label" />
		<label id="radioSqlTypeMySQL_label" for="radioSqlTypeMySQL">MySQL</label>
		&nbsp;&nbsp;&nbsp;
		<input type="radio" class="wm_checkbox" name="radioSqlType" id="radioSqlTypePostgreSQL"
			value="<?php echo EnumConvert::ToPost(EDbType::PostgreSQL, 'EDbType'); ?>" <?php
			$this->Data->PrintCheckedValue('radioSqlTypePostgreSQL'); ?>  x-data-label="radioSqlType_label" />
		<label id="radioSqlTypePostgreSQL_label" for="radioSqlTypePostgreSQL">PostgreSQL (experimental)</label>
	</td>
</tr>
<tr>
	<td align="left" width="200">
		<span id="txtSqlLogin_label"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_DATABASE_USER'); ?></span>
	</td>
	<td>
		<input type="text" class="wm_input" name="txtSqlLogin" id="txtSqlLogin" value="<?php $this->Data->PrintInputValue('txtSqlLogin') ?>" size="35" />
	</td>
</tr>
<tr>
	<td align="left">
		<span id="txtSqlPassword_label"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_DATABASE_PASS'); ?></span>
	</td>
	<td>
		<input type="password" class="wm_input" name="txtSqlPassword" id="txtSqlPassword" value="<?php $this->Data->PrintInputValue('txtSqlPassword') ?>" size="35" />
	</td>
</tr>
<tr>
	<td align="left">
		<span id="txtSqlName_label"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_DATABASE_NAME'); ?></span>
	</td>
	<td>
		<input type="text" class="wm_input" name="txtSqlName" id="txtSqlName" value="<?php $this->Data->PrintInputValue('txtSqlName') ?>" size="35" />
	</td>
</tr>
<tr>
	<td align="left">
		<span id="txtSqlSrc_label"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_DATABASE_HOST'); ?></span>
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
		<input type="button" name="test_btn" id="test_btn" value="<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_DATABASE_TEST'); ?>" class="wm_button" style="font-size: 11px;" />
	</td>
</tr>

<tr><td colspan="2"><br /></td></tr>
<tr>
	<td colspan="2" valign="top">
		<input type="button" name="create_btn" id="create_btn" value="<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_DATABASE_CREATE'); ?>" class="wm_button" style="font-size: 11px;" />
		<input type="button" name="update_btn" id="update_btn" value="<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_DATABASE_UPDATE'); ?>" class="wm_button" style="font-size: 11px;" />
	</td>
</tr>

<input type="hidden" name="txtToken" value="<?php $this->Data->PrintInputValue('txtToken') ?>" />