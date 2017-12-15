<table class="wm_contacts_view">
	<tr>
		<td class="wm_field_value" colspan="2">
			<input type="checkbox" class="wm_checkbox override" value="1"
				name="chAllowCollaboration" id="chAllowCollaboration"
				<?php $this->Data->PrintCheckedValue('chAllowCollaboration'); ?> />
			<label id="chAllowCollaboration_label" for="chAllowCollaboration">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_ALLOW_COLLABORATIONS');?>
			</label>
			
			<div class="wm_information_com">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_ALLOW_COLLABORATIONS_HINT');?>
			</div>
		</td>
	</tr>
</table>
<br />