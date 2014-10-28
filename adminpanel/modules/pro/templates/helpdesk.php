<tr>
	<td valign="top" colspan="2">
		<b><?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_URL_OF_HELPDESK'); ?></b>
		<br />
		<br />
		<input readonly="true" type="text" value="<?php $this->Data->PrintInputValue('txtClientsHelpdeskURL') ?>" size="60" />
		<br />
		<div class="wm_information_com">
			<?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_URL_DESC'); ?>
		</div>
		<br />
	</td>
</tr>
<tr>
	<td valign="top" colspan="2">
		<b><?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_NAME'); ?></b>
		<br />
		<br />
		<input type="text" id="txtHelpdeskSiteName" name="txtHelpdeskSiteName" value="<?php $this->Data->PrintInputValue('txtHelpdeskSiteName') ?>" size="60" />
		<br />
		<br />
	</td>
</tr>
<tr>
	<td valign="top" colspan="2">
		<b><?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_EMAIL'); ?></b>
		<br />
		<br />
		<input type="text" id="txtAdminEmailAccount" name="txtAdminEmailAccount" value="<?php $this->Data->PrintInputValue('txtAdminEmailAccount') ?>" size="50" />
		<br />

		<div class="wm_information_com <?php $this->Data->PrintInputValue('classInfoEmptyEmail') ?>" style="color: red">
			<?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_EMAIL_DESC'); ?>
		</div>
		<div class="wm_information_com <?php $this->Data->PrintInputValue('classInfoUnknownEmail') ?>" style="color: red">
			<?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_EMAIL_NOT_EXIST'); ?>
		</div>
		<br />
		<input type="radio" class="wm_checkbox" name="radioHelpdeskFetcherType" id="radioHelpdeskFetcherTypeNone"
			value="<?php echo EnumConvert::ToPost(EHelpdeskFetcherType::NONE, 'EHelpdeskFetcherType'); ?>" <?php
			$this->Data->PrintCheckedValue('radioHelpdeskFetcherTypeNone'); ?> x-data-label="radioHelpdeskFetcherType_label" />
		<label id="radioHelpdeskFetcherTypeNone_label" for="radioHelpdeskFetcherTypeNone">
			<?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_FETCHER_ALLOW_NEVER'); ?>
		</label>
		<br />
		<input type="radio" class="wm_checkbox" name="radioHelpdeskFetcherType" id="radioHelpdeskFetcherTypeReply"
			value="<?php echo EnumConvert::ToPost(EHelpdeskFetcherType::REPLY, 'EHelpdeskFetcherType'); ?>" <?php
			$this->Data->PrintCheckedValue('radioHelpdeskFetcherTypeReply'); ?>  x-data-label="radioHelpdeskFetcherType_label" />
		<label id="radioHelpdeskFetcherTypeReply_label" for="radioHelpdeskFetcherTypeReply">
			<?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_FETCHER_ALLOW_REPLIES'); ?>
		</label>
		<br />
		<input type="radio" class="wm_checkbox" name="radioHelpdeskFetcherType" id="radioHelpdeskFetcherTypeAll"
			value="<?php echo EnumConvert::ToPost(EHelpdeskFetcherType::ALL, 'EHelpdeskFetcherType'); ?>" <?php
			$this->Data->PrintCheckedValue('radioHelpdeskFetcherTypeAll'); ?>  x-data-label="radioHelpdeskFetcherType_label" />
		<label id="radioHelpdeskFetcherTypeAll_label" for="radioHelpdeskFetcherTypeAll">
			<?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_FETCHER_ALLOW_NEW_REQUESTS'); ?>
		</label>
		<br />
		<br />
	</td>
</tr>
<tr>
	<td valign="top" colspan="2">
		<b><?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_IFRAME1'); ?></b>
		<br />
		<br />
		<input type="text" id="txtClientIframeUrl" name="txtClientIframeUrl" value="<?php $this->Data->PrintInputValue('txtClientIframeUrl') ?>" size="60" />
		<br />
		<br />
	</td>
</tr>
<tr>
	<td valign="top" colspan="2">
		<b><?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_IFRAME2'); ?></b>
		<br />
		<br />
		<input type="text" id="txtAgentIframeUrl" name="txtAgentIframeUrl" value="<?php $this->Data->PrintInputValue('txtAgentIframeUrl') ?>" size="60" />
		<br />
		<div class="wm_information_com">
			<?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_IFRAME_DESC'); ?>
		</div>
	</td>
</tr>
<tr>
	<td valign="top" colspan="2">
		<input type="checkbox" class="wm_checkbox" name="chHelpdeskStyleAllow" id="chHelpdeskStyleAllow" value="1" <?php $this->Data->PrintCheckedValue('chHelpdeskStyleAllow') ?>/>
		<b><label for="chHelpdeskStyleAllow"><?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_STYLE_ALLOW'); ?></label></b>
		<br />
		<br />
		<?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_STYLE_URL'); ?>
		<br />
		<input type="text" id="txtHelpdeskStyleImage" name="txtHelpdeskStyleImage" value="<?php $this->Data->PrintInputValue('txtHelpdeskStyleImage') ?>" size="60" />
		<br />
		<br />
		<?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_STYLE_TEXT'); ?>
		<br />
		<textarea id="txtHelpdeskStyleText" name="txtHelpdeskStyleText" cols="47" rows="20"><?php $this->Data->PrintInputValue('txtHelpdeskStyleText') ?></textarea>
		<br />
		<div class="wm_information_com"><?php echo CApi::I18N('ADMIN_PANEL/HELPDESK_STYLE_DESC'); ?></div>
		<br />
	</td>
</tr>

<!--<tr>
	<td valign="top" colspan="2">
		<b><?php /*echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL'); */?></b>
		<br />
	</td>
</tr>

<tr>
	<td align="left" width="80" valign="top">
		<input type="checkbox" class="wm_checkbox" name="chHelpdeskFacebookAllow" id="chHelpdeskFacebookAllow" value="1" <?php /*$this->Data->PrintCheckedValue('chHelpdeskFacebookAllow') */?>/>
		<label for="chHelpdeskFacebookAllow"><?php /*echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL_FACEBOOK'); */?></label>
		<br />
		<br />
		<span><?php /*echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL_FACEBOOK_ID'); */?></span>
		<br />
		<br />
		<span><?php /*echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL_FACEBOOK_SECRET'); */?></span>
	</td>
	<td>
		<br />
		<br />
		<input type="text" id="txtHelpdeskFacebookId" name="txtHelpdeskFacebookId" value="<?php /*$this->Data->PrintInputValue('txtHelpdeskFacebookId') */?>" size="60" />
		<br />
		<input type="text" id="txtHelpdeskFacebookSecret" name="txtHelpdeskFacebookSecret" value="<?php /*$this->Data->PrintInputValue('txtHelpdeskFacebookSecret') */?>" size="60" />
	</td>
</tr>
<tr>
	<td align="left" width="80" valign="top">
		<input type="checkbox" class="wm_checkbox" name="chHelpdeskGoogleAllow" id="chHelpdeskGoogleAllow" value="1" <?php /*$this->Data->PrintCheckedValue('chHelpdeskGoogleAllow') */?>/>
		<label for="chHelpdeskGoogleAllow"><?php /*echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL_GOOGLE'); */?></label>
		<br />
		<br />
		<span><?php /*echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL_GOOGLE_ID'); */?></span>
		<br />
		<br />
		<span><?php /*echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL_GOOGLE_SECRET'); */?></span>
	</td>
	<td>
		<br />
		<br />
		<input type="text" id="txtHelpdeskGoogleId" name="txtHelpdeskGoogleId" value="<?php /*$this->Data->PrintInputValue('txtHelpdeskGoogleId') */?>" size="60" />
		<br />
		<input type="text" id="txtHelpdeskGoogleSecret" name="txtHelpdeskGoogleSecret" value="<?php /*$this->Data->PrintInputValue('txtHelpdeskGoogleSecret') */?>" size="60" />
	</td>
</tr>
<tr>
	<td align="left" width="80" valign="top">
		<input type="checkbox" class="wm_checkbox" name="chHelpdeskTwitterAllow" id="chHelpdeskTwitterAllow" value="1" <?php /*$this->Data->PrintCheckedValue('chHelpdeskTwitterAllow') */?>/>
		<label for="chHelpdeskTwitterAllow"><?php /*echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL_TWITTER'); */?></label>
		<br />
		<br />
		<span><?php /*echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL_TWITTER_ID'); */?></span>
		<br />
		<br />
		<span><?php /*echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL_TWITTER_SECRET'); */?></span>
	</td>
	<td>
		<br />
		<br />
		<input type="text" id="txtHelpdeskTwitterId" name="txtHelpdeskTwitterId" value="<?php /*$this->Data->PrintInputValue('txtHelpdeskTwitterId') */?>" size="60" />
		<br />
		<input type="text" id="txtHelpdeskTwitterSecret" name="txtHelpdeskTwitterSecret" value="<?php /*$this->Data->PrintInputValue('txtHelpdeskTwitterSecret') */?>" size="60" />
	</td>
</tr>

<tr>
	<td valign="top" colspan="2">
		<div class="wm_information_com">
			<?php /*echo CApi::I18N('ADMIN_PANEL/HELPDESK_SOCIAL_DESK'); */?>
		</div>
	</td>
</tr>-->