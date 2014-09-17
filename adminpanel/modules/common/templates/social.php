<tr>
	<td align="left" width="80" valign="top">
		<input type="checkbox" class="wm_checkbox" name="chSocialGoogleAllow" id="chSocialGoogleAllow" value="1" <?php $this->Data->PrintCheckedValue('chSocialGoogleAllow') ?>/>
		<label for="chSocialGoogleAllow"><?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL_GOOGLE'); ?></label>
		<br />
		<br />
		<span><?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL_GOOGLE_ID'); ?></span>
		<br />
		<br />
		<span><?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL_GOOGLE_SECRET'); ?></span>
		<br />
		<br />
		<span><?php echo CApi::I18N('ADMIN_PANEL/SOCIAL_GOOGLE_API_KEY'); ?></span>
	</td>
	<td>
		<br />
		<br />
		<input type="text" id="txtSocialGoogleId" name="txtSocialGoogleId" value="<?php $this->Data->PrintInputValue('txtSocialGoogleId') ?>" size="60" />
		<br />
		<input type="text" id="txtSocialGoogleSecret" name="txtSocialGoogleSecret" value="<?php $this->Data->PrintInputValue('txtSocialGoogleSecret') ?>" size="60" />
		<br />
		<input type="text" id="txtSocialGoogleApiKey" name="txtSocialGoogleApiKey" value="<?php $this->Data->PrintInputValue('txtSocialGoogleApiKey') ?>" size="60" />
	</td>
</tr>
<tr>
	<td align="left" width="80" valign="top">
		<input type="checkbox" class="wm_checkbox" name="chSocialDropboxAllow" id="chSocialDropboxAllow" value="1" <?php $this->Data->PrintCheckedValue('chSocialDropboxAllow') ?>/>
		<label for="chSocialDropboxAllow"><?php echo CApi::I18N('ADMIN_PANEL/DROPBOX'); ?></label>
		<br />
		<br />
		<span><?php echo CApi::I18N('ADMIN_PANEL/SOCIAL_DROPBOX_SECRET'); ?></span>
		<br />
		<br />
		<span><?php echo CApi::I18N('ADMIN_PANEL/SOCIAL_DROPBOX_KEY'); ?></span>
	</td>
	<td>
		<br />
		<br />
		<input type="text" id="txtSocialDropboxSecret" name="txtSocialDropboxSecret" value="<?php $this->Data->PrintInputValue('txtSocialDropboxSecret') ?>" size="60" />
		<br />
		<input type="text" id="txtSocialDropboxKey" name="txtSocialDropboxKey" value="<?php $this->Data->PrintInputValue('txtSocialDropboxKey') ?>" size="60" />
	</td>
</tr>