<tr>
	<td valign="top" colspan="2">
		<b><?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL'); ?></b>
		<br />
		<br />
	</td>
</tr>

<tr>
	<td align="left" width="80" valign="top">
		<input type="checkbox" class="wm_checkbox" name="chSocialFacebookAllow" id="chSocialFacebookAllow" value="1" <?php $this->Data->PrintCheckedValue('chSocialFacebookAllow') ?>/>
		<label for="chSocialFacebookAllow"><?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL_FACEBOOK'); ?></label>
		<br />
		<br />
		<span><?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL_FACEBOOK_ID'); ?></span>
		<br />
		<br />
		<span><?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL_FACEBOOK_SECRET'); ?></span>
	</td>
	<td>
		<br />
		<br />
		<input type="text" id="txtSocialFacebookId" name="txtSocialFacebookId" value="<?php $this->Data->PrintInputValue('txtSocialFacebookId') ?>" size="60" />
		<br />
		<input type="text" id="txtSocialFacebookSecret" name="txtSocialFacebookSecret" value="<?php $this->Data->PrintInputValue('txtSocialFacebookSecret') ?>" size="60" />
	</td>
</tr>
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
<tr>
	<td align="left" width="80" valign="top">
		<input type="checkbox" class="wm_checkbox" name="chSocialTwitterAllow" id="chSocialTwitterAllow" value="1" <?php $this->Data->PrintCheckedValue('chSocialTwitterAllow') ?>/>
		<label for="chSocialTwitterAllow"><?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL_TWITTER'); ?></label>
		<br />
		<br />
		<span><?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL_TWITTER_ID'); ?></span>
		<br />
		<br />
		<span><?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL_TWITTER_SECRET'); ?></span>
	</td>
	<td>
		<br />
		<br />
		<input type="text" id="txtSocialTwitterId" name="txtSocialTwitterId" value="<?php $this->Data->PrintInputValue('txtSocialTwitterId') ?>" size="60" />
		<br />
		<input type="text" id="txtSocialTwitterSecret" name="txtSocialTwitterSecret" value="<?php $this->Data->PrintInputValue('txtSocialTwitterSecret') ?>" size="60" />
	</td>
</tr>

<tr>
	<td valign="top" colspan="2">
		<div class="wm_information_com">
			<?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL_DESK'); ?>
		</div>
	</td>
</tr>