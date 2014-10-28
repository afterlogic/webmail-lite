<tr>
	<td width="150"></td>
	<td width="100"></td>
	<td></td>
</tr>

<tr>
	<td colspan="3" class="wm_settings_list_select">
		<b>Server logging</b>
	</td>
</tr>

<tr><td colspan="3"><br /></td></tr>

<tr>
	<td colspan="2" align="left">
		<span id="PostmasterErrorEmail_label">Postmaster email address for errors</span>
	</td>
	<td align="left">
		<input type="text" class="wm_input" size="25" name="text_PostmasterErrorEmail" id="text_PostmasterErrorEmail" value="<?php $this->Data->PrintInputValue('text_PostmasterErrorEmail') ?>" />
	</td>
</tr>

<tr><td colspan="3"><br /></td></tr>

<tr>
	<td align="left" colspan="3">
		<input type="checkbox" class="wm_checkbox" value="1" name="ch_EnablePOP3Logging" id="ch_EnablePOP3Logging" <?php $this->Data->PrintCheckedValue('ch_EnablePOP3Logging') ?>/>
		<label for="ch_EnablePOP3Logging">Enable POP3 logging</label>
	</td>
</tr>
<tr>
	<td align="left">
		<input type="button" class="wm_button" value="View POP3 log" />
	</td>
	<td align="left" colspan="2">
		<input type="button" class="wm_button" value="Clear log" />
	</td>
</tr>

<tr>
	<td align="left" colspan="3">
		<input type="checkbox" class="wm_checkbox" value="1" name="ch_EnableIMAPLogging" id="ch_EnableIMAPLogging" <?php $this->Data->PrintCheckedValue('ch_EnableIMAPLogging') ?>/>
		<label for="ch_EnableIMAPLogging">Enable IMAP logging</label>
	</td>
</tr>
<tr>
	<td align="left">
		<input type="button" class="wm_button" value="View IMAP log" />
	</td>
	<td align="left" colspan="2">
		<input type="button" class="wm_button" value="Clear log" />
	</td>
</tr>

<tr>
	<td align="left" colspan="3">
		<input type="checkbox" class="wm_checkbox" value="1" name="ch_EnableSMTPLogging" id="ch_EnableSMTPLogging" <?php $this->Data->PrintCheckedValue('ch_EnableSMTPLogging') ?>/>
		<label for="ch_EnableSMTPLogging">Enable SMTP logging</label>
	</td>
</tr>
<tr>
	<td align="left">
		<input type="button" class="wm_button" value="View SMTP log" />
	</td>
	<td align="left" colspan="2">
		<input type="button" class="wm_button" value="Clear log" />
	</td>
</tr>

<tr><td colspan="3"><br /></td></tr>