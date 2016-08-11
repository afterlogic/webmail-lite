<?php foreach($this->Data->getValue('Socials') as $sSocialKey => $SocialItem) { ?>
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
<?php if ($SocialItem->HasApiKey){ ?>
<tr>
	<td align="left" width="80" valign="top">
		<span><?php echo CApi::I18N('ADMIN_PANEL/SOCIAL_GOOGLE_API_KEY'); ?></span>
	</td>
	<td>
		<input type="text" id="<?php echo $sSocialKey ?>_txtSocialApiKey" name="<?php echo $sSocialKey ?>_txtSocialApiKey" value="<?php echo $SocialItem->SocialApiKey ?>" size="60" />
	</td>
</tr>
<tr>
	<td align="left" width="80" valign="top"></td>
	<td>
		<div class="wm_information_com" style="margin: 0px 0px 5px 0px;"><?php echo CApi::I18N('ADMIN_PANEL/SOCIAL_GOOGLE_API_KEY_DESCRIPTION'); ?></div>
	</td>
</tr>
<?php } ?>
<tr>
	<td>
		<?php echo CApi::I18N('ADMIN_PANEL/SOCIAL_SCOPES'); ?>
	</td>
	<td>
<?php
foreach ($SocialItem->SupportedScopes as $iKey => $sScope)
{
?>
		<input type="checkbox" class="wm_checkbox" name="<?php echo $sSocialKey ?>_chSocialScopes[<?php echo $sScope; ?>]" id="<?php echo $sSocialKey ?>_chSocialScopes[<?php echo $sScope; ?>]" value="1" <?php $this->Data->ConvertBoolToChecked(in_array($sScope, explode(' ', $SocialItem->SocialScopes))) ?>/>
		<label for="<?php echo $sSocialKey ?>_chSocialScopes[<?php echo $sScope; ?>]"><?php echo $SocialItem->TranslatedScopes[$iKey]; ?></label>
<?php } ?>
	</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<?php }