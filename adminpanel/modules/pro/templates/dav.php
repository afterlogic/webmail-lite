	<tr>
		<td width="100"></td>
		<td></td>
	</tr>

	<tr>
		<td align="left" colspan="2">
			<input type="checkbox" class="wm_checkbox" value="1" name="ch_EnableMobileSync" id="ch_EnableMobileSync" <?php $this->Data->PrintCheckedValue('ch_EnableMobileSync') ?>/>
			<label for="ch_EnableMobileSync">
				<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_MOBILE_ENABLE'); ?>
			</label>
		</td>
	</tr>

	<tr><td colspan="2"><br /></td></tr>

	<tr>
		<td align="left">
			<span><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_MOBILE_URL'); ?></span>
		</td>
		<td align="left">
			<input type="text" class="wm_input" size="50" name="text_DAVUrl" id="text_DAVUrl" value="<?php $this->Data->PrintInputValue('text_DAVUrl') ?>" />
		</td>
	</tr>

	<tr><td colspan="2"><br /></td></tr>

	<tr>
		<td colspan="2" style="padding: 0px;">
			<div class="wm_safety_info">
				<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_MOBILE_URL_HINT'); ?>
				<br />
				<br />
				<a href="<?php $this->Data->PrintInputValue('text_WikiHref') ?>" target="_target">
					<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_MOBILE_URL_LEARN_MORE'); ?>
				</a>
			</div>
		</td>
	</tr>

	<tr><td colspan="2"><br /></td></tr>

	<tr>
		<td align="left">
			<span><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_MOBILE_IMAP'); ?></span>
		</td>
		<td align="left">
			<input type="text" class="wm_input" size="50" name="text_IMAPHostName"
				id="text_IMAPHostName" value="<?php $this->Data->PrintInputValue('text_IMAPHostName') ?>" />
		</td>
	</tr>

	<tr><td colspan="2"><br /></td></tr>

	<tr>
		<td colspan="2" style="padding: 0px;">
			<div class="wm_safety_info">
				<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_MOBILE_IMAP_HINT'); ?>
			</div>
		</td>
	</tr>

	<tr><td colspan="2"><br /></td></tr>

	<tr>
		<td align="left">
			<span><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_MOBILE_SMTP'); ?></span>
		</td>
		<td align="left">
			<input type="text" class="wm_input" size="50" name="text_SMTPHostName"
				id="text_SMTPHostName" value="<?php $this->Data->PrintInputValue('text_SMTPHostName') ?>" />
		</td>
	</tr>

	<tr><td colspan="2"><br /></td></tr>

	<tr>
		<td colspan="2" style="padding: 0px;">
			<div class="wm_safety_info">
				<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_MOBILE_SMTP_HINT'); ?>
			</div>
		</td>
	</tr>