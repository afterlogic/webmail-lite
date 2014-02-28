<table class="wm_contacts_view">
	<tr>
		<td class="wm_field_value">
			<input type="checkbox" class="wm_checkbox override" name="chEnableWebmail" id="chEnableWebmail"
				   value="1" <?php $this->Data->PrintCheckedValue('chEnableWebmail'); ?> />
			<label id="chEnableWebmail_label" for="chEnableWebmail"><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_WEBMAIL_ENABLED');?></label>
		</td>
	</tr>
</table>