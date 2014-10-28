<table class="wm_contacts_view">
	<tr>
		<td class="wm_field_title" colspan="2">
			<input type="checkbox" class="wm_checkbox override" name="chEnableFiles" id="chEnableFiles"
				   value="1" <?php $this->Data->PrintCheckedValue('chEnableFiles'); ?> />
			<label id="chEnableFiles_label" for="chEnableFiles"><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_FILES_ENABLED');?></label>
		</td>
	</tr>
</table>