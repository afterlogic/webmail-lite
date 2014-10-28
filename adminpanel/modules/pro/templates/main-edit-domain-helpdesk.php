<table class="wm_contacts_view">
	<tr>
		<td class="wm_field_title" colspan="2">
			<input type="checkbox" class="wm_checkbox override" name="chEnableHelpdesk" id="chEnableHelpdesk"
				   value="1" <?php $this->Data->PrintCheckedValue('chEnableHelpdesk'); ?> />
			<label id="chEnableHelpdesk_label" for="chEnableHelpdesk"><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_HELPDESK_ENABLED');?></label>
		</td>
	</tr>
</table>