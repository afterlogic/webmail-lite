<tr>
	<td width="250"></td>
	<td></td>
</tr>

<tr>
	<td colspan="2" class="wm_settings_list_select">
                <b><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LOGGING_TITLE'); ?></b>
	</td>
</tr>

<tr><td colspan="2"><br /></td></tr>

<tr>
	<td align="left">
		<input type="checkbox" class="wm_checkbox" value="1" name="ch_EnableDebugLogging"
			id="ch_EnableDebugLogging" <?php $this->Data->PrintCheckedValue('ch_EnableDebugLogging') ?>/>
		<label for="ch_EnableDebugLogging"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LOGGING_DEBUG'); ?></label>
	</td>
	<td align="left">
		<span><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LOGGING_VERBOSITY'); ?></span><br />
		<select name="selVerbosity" id="selVerbosity" class="wm_select">
			<option value="<?php echo EnumConvert::ToPost(ELogLevel::Full, 'ELogLevel');
				?>" <?php $this->Data->PrintSelectedValue('selVerbosityFull'); ?>><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LOGGING_VERBOSITY_DEBUG'); ?></option>
			<option value="<?php echo EnumConvert::ToPost(ELogLevel::Warning, 'ELogLevel');
				?>" <?php $this->Data->PrintSelectedValue('selVerbosityWarning'); ?>><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LOGGING_VERBOSITY_WARNINGS'); ?></option>
			<option value="<?php echo EnumConvert::ToPost(ELogLevel::Error, 'ELogLevel');
				?>" <?php $this->Data->PrintSelectedValue('selVerbosityError'); ?>><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LOGGING_VERBOSITY_ERRORS'); ?></option>
			<option value="<?php echo EnumConvert::ToPost(ELogLevel::Spec, 'ELogLevel');
				?>" <?php $this->Data->PrintSelectedValue('selVerbositySpec'); ?>><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LOGGING_VERBOSITY_SPEC'); ?></option>
		</select>
	</td>
</tr>
<tr>
	<td align="left">
		<input id="btnDownloadLog" type="button" class="wm_button" value="<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LOGGING_DEBUG_DOWNLOAD'); ?> <?php $this->Data->PrintValue('DownloadLogSize') ?>" />
		<br />
		<input id="btnViewLog" type="button" class="wm_button" value="<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LOGGING_DEBUG_VIEW'); ?> (<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LOGGING_DEBUG_VIEWLAST'); ?> <?php $this->Data->PrintInputValue('MaxViewSize') ?>)" />
	</td>
	<td align="left">
		<input id="btnClearLog" name="btnClearLog" type="submit" class="wm_button" value="<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LOGGING_CLEAR'); ?>" />
	</td>
</tr>

<tr><td colspan="2"><br /></td></tr>

<tr>
	<td align="left" colspan="2">
		<input type="checkbox" class="wm_checkbox" value="1" name="ch_EnableUserActivityLogging"
			id="ch_EnableUserActivityLogging" <?php $this->Data->PrintCheckedValue('ch_EnableUserActivityLogging') ?>/>
		<label for="ch_EnableUserActivityLogging"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LOGGING_USER'); ?></label>
	</td>
</tr>
<tr>
	<td align="left">
		<input id="btnUserActivityDownloadLog" type="button" class="wm_button" value="<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LOGGING_USER_DOWNLOAD'); ?> <?php $this->Data->PrintValue('DownloadUserActivityLogSize') ?>" />
		<br />
		<input id="btnUserActivityViewLog" type="button" class="wm_button" value="<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LOGGING_USER_VIEW'); ?> (<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LOGGING_USER_VIEWLAST'); ?> <?php $this->Data->PrintInputValue('MaxViewSize') ?>)" />
	</td>
	<td align="left">
		<input id="btnUserActivityClearLog"  name="btnUserActivityClearLog" type="submit" class="wm_button" value="<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LOGGING_CLEAR'); ?>" />
	</td>
</tr>

<tr><td colspan="2"><br /></td></tr>
