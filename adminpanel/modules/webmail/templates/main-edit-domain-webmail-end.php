<table class="wm_contacts_view">
	<tr>
		<td class="wm_field_title">
			<span id="selMessagesPerPage_label">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_WEBMAIL_MESSAGES');?>
			</span>
		</td>
		<td class="wm_field_value">
			<select name="selMessagesPerPage" id="selMessagesPerPage" class="wm_select override">
				<?php $this->Data->PrintValue('selMessagesPerPageOptions'); ?>
			</select>
		</td>
	</tr>
	<!--<tr>
		<td class="wm_field_title">
			<span id="selLayout_label">
				<?php // echo CApi::I18N('ADMIN_PANEL/DOMAINS_WEBMAIL_LAYOUT');?>
			</span>
		</td>
		<td class="wm_field_value">
			<input type="radio" class="wm_checkbox override" name="radioLayout" id="radioLayoutSide"
				value="<?php // echo EnumConvert::ToPost(ELayout::Side, 'ELayout'); ?>" <?php //$this->Data->PrintCheckedValue('radioLayoutSide'); ?> x-data-label="radioLayout_lable" />
			<label class="wm_settings_layout_icon_side" for="radioLayoutSide"></label>
			
			<input type="radio" class="wm_checkbox override" name="radioLayout" id="radioLayoutBottom"
				value="<?php // echo EnumConvert::ToPost(ELayout::Bottom, 'ELayout'); ?>"  <?php //$this->Data->PrintCheckedValue('radioLayoutBottom'); ?>  x-data-label="radioLayout_lable" />
			<label class="wm_settings_layout_icon_bottom" for="radioLayoutBottom"></label>
		</td>
	</tr>
	<tr>-->
		<td class="wm_field_title">
			<span id="selAutocheckMail_label">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_WEBMAIL_AUTO');?>
			</span>
		</td>
		<td class="wm_field_value">
			<select name="selAutocheckMail" id="selAutocheckMail" class="wm_select override">
				<?php $this->Data->PrintValue('selAutocheckMailOptions'); ?>
			</select>
		</td>
	</tr>
</table>