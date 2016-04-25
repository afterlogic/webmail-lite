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