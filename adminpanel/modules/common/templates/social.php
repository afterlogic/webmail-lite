<?php foreach($this->Data->GetValue('Socials') as $sSocialKey => $SocialItem) { ?>
<tr>
	<td align="left" width="80" valign="top" colspan="2">
		<input type="checkbox" class="wm_checkbox" name="<?php echo $sSocialKey ?>_chSocialAllow" id="<?php echo $sSocialKey ?>_chSocialAllow" value="1" <?php $this->Data->ConvertBoolToChecked($SocialItem->SocialAllow) ?>/>
		<label for="<?php echo $sSocialKey ?>_chSocialAllow"><?php echo $SocialItem->SocialName; ?></label>
	</td>
</tr>
<tr>
	<td align="left" width="80" valign="top">
		<span><?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL_GOOGLE_ID'); ?></span>
	</td>
	<td>
		<input type="text" id="<?php echo $sSocialKey ?>_txtSocialId" name="<?php echo $sSocialKey ?>_txtSocialId" value="<?php echo $SocialItem->SocialId ?>" size="60" />
	</td>
</tr>
<tr>
	<td align="left" width="80" valign="top">
		<span><?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL_GOOGLE_SECRET'); ?></span>
	</td>
	<td>
		<input type="text" id="<?php echo $sSocialKey ?>_txtSocialSecret" name="<?php echo $sSocialKey ?>_txtSocialSecret" value="<?php echo $SocialItem->SocialSecret ?>" size="60" />
	</td>
</tr>
<?php if (is_string($SocialItem->SocialApiKey)) { ?>
<tr>
	<td align="left" width="80" valign="top">
		<span><?php echo CApi::I18N('ADMIN_PANEL/SOCIAL_GOOGLE_API_KEY'); ?></span>
	</td>
	<td>
		<input type="text" id="<?php echo $sSocialKey ?>_txtSocialApiKey" name="<?php echo $sSocialKey ?>_txtSocialApiKey" value="<?php echo $SocialItem->SocialApiKey ?>" size="60" />
	</td>
</tr>
<?php } ?>
<tr>
	<td align="left" width="80" valign="top">
		<span><?php echo CApi::I18N('ADMIN_PANEL/SOCIAL_SCOPES'); ?></span>
	</td>
	<td>
		<input type="text" id="<?php echo $sSocialKey ?>_txtSocialScopes" name="<?php echo $sSocialKey ?>_txtSocialScopes" value="<?php echo $SocialItem->SocialScopes ?>" size="60" />
	</td>
</tr>
<?php }