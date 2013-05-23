<tr>
	<td width="250"></td>
	<td></td>
</tr>

<tr>
	<td colspan="2" class="wm_settings_list_select">
		<b>WebMail logging</b>
	</td>
</tr>

<tr><td colspan="2"><br /></td></tr>

<tr>
	<td align="left">
		<input type="checkbox" class="wm_checkbox" value="1" name="ch_EnableDebugLogging"
			id="ch_EnableDebugLogging" <?php $this->Data->PrintCheckedValue('ch_EnableDebugLogging') ?>/>
		<label for="ch_EnableDebugLogging">Enable debug logging</label>
	</td>
	<td align="left">
		<span>Verbosity</span><br />
		<select name="selVerbosity" id="selVerbosity" class="wm_select">
			<option value="<?php echo EnumConvert::ToPost(ELogLevel::Full, 'ELogLevel');
				?>" <?php $this->Data->PrintSelectedValue('selVerbosityFull'); ?>>Debug</option>
			<option value="<?php echo EnumConvert::ToPost(ELogLevel::Warning, 'ELogLevel');
				?>" <?php $this->Data->PrintSelectedValue('selVerbosityWarning'); ?>>Warnings</option>
			<option value="<?php echo EnumConvert::ToPost(ELogLevel::Error, 'ELogLevel');
				?>" <?php $this->Data->PrintSelectedValue('selVerbosityError'); ?>>Errors</option>
			<option value="<?php echo EnumConvert::ToPost(ELogLevel::Spec, 'ELogLevel');
				?>" <?php $this->Data->PrintSelectedValue('selVerbositySpec'); ?>>Specified User</option>
		</select>
	</td>
</tr>
<tr>
	<td align="left">
		<input id="btnDownloadLog" type="button" class="wm_button" value="Download log <?php $this->Data->PrintValue('DownloadLogSize') ?>" />
		<br />
		<input id="btnViewLog" type="button" class="wm_button" value="View log (last <?php $this->Data->PrintInputValue('MaxViewSize') ?>)" />
	</td>
	<td align="left">
		<input id="btnClearLog" name="btnClearLog" type="submit" class="wm_button" value="Clear log" />
	</td>
</tr>

<tr><td colspan="2"><br /></td></tr>

<tr>
	<td align="left" colspan="2">
		<input type="checkbox" class="wm_checkbox" value="1" name="ch_EnableUserActivityLogging"
			id="ch_EnableUserActivityLogging" <?php $this->Data->PrintCheckedValue('ch_EnableUserActivityLogging') ?>/>
		<label for="ch_EnableUserActivityLogging">Enable user activity logging</label>
	</td>
</tr>
<tr>
	<td align="left">
		<input id="btnUserActivityDownloadLog" type="button" class="wm_button" value="Download user activity log <?php $this->Data->PrintValue('DownloadUserActivityLogSize') ?>" />
		<br />
		<input id="btnUserActivityViewLog" type="button" class="wm_button" value="View log (last <?php $this->Data->PrintInputValue('MaxViewSize') ?>)" />
	</td>
	<td align="left">
		<input id="btnUserActivityClearLog"  name="btnUserActivityClearLog" type="submit" class="wm_button" value="Clear log" />
	</td>
</tr>

<tr><td colspan="2"><br /></td></tr>
