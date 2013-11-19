<table class="wm_contacts_view">
	<tr>
		<td class="wm_field_title" colspan="2">
			<h3><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_REGIONAL_SETTINGS');?></h3>
		</td>
	</tr>
	<tr>
		<td class="wm_field_title">
			<span id="selLanguage_label">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_LANGUAGE');?>
			</span>
		</td>
		<td class="wm_field_value">
			<select name="selLanguage" id="selLanguage" class="wm_select override">
				 <?php $this->Data->PrintValue('selLanguageOptions'); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="wm_field_title">
			<span id="selTimeZone_label">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_TIMEZONE');?>
			</span>
		</td>
		<td class="wm_field_value">
			<select name="selTimeZone" id="selTimeZone" class="wm_select override">
				<?php $this->Data->PrintValue('selTimeZone'); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="wm_field_title">
			<span id="radioTimeFormat_label">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_TIMEFORMAT');?>
			</span>
		</td>
		<td class="wm_field_value">
			<input type="radio" class="wm_checkbox override" name="radioTimeFormat" id="radioTimeFormat12"
				value="<?php echo EnumConvert::ToPost(ETimeFormat::F12, 'ETimeFormat'); ?>" <?php
				$this->Data->PrintCheckedValue('radioTimeFormat12'); ?> x-data-label="radioTimeFormat_label" />
			<label id="radioTimeFormat12_label" for="radioTimeFormat12">1PM</label>
			&nbsp;&nbsp;&nbsp;
			<input type="radio" class="wm_checkbox override" name="radioTimeFormat" id="radioTimeFormat24"
				value="<?php echo EnumConvert::ToPost(ETimeFormat::F24, 'ETimeFormat'); ?>" <?php
				$this->Data->PrintCheckedValue('radioTimeFormat24'); ?>  x-data-label="radioTimeFormat_label" />
			<label id="radioTimeFormat24_label" for="radioTimeFormat24">13:00</label>
		</td>
	</tr>
	<tr>
		<td class="wm_field_title">
			<span id="radioTimeFormat_label">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_DATEFORMAT');?>
			</span>
		</td>
		<td class="wm_field_value">
			<select name="selDateformat" id="selDateformat" class="wm_select override">
				<option value="<?php echo EDateFormat::MMDDYYYY; ?>" <?php
					$this->Data->PrintSelectedValue('optDateFormatMMDDYYYY'); ?>><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_DATEFORMAT_MMDD');?></option>
				<option value="<?php echo EDateFormat::DDMMYYYY; ?>" <?php
					$this->Data->PrintSelectedValue('optDateFormatDDMMYYYY'); ?>><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_DATEFORMAT_DDMM');?></option>
				<option value="<?php echo EDateFormat::DD_MONTH_YYYY; ?>" <?php
					$this->Data->PrintSelectedValue('optDateFormatDDMonthYYYY'); ?>><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_DATEFORMAT_DD_MONTH_YYYY');?></option>
			</select>
		</td>
	</tr>

</table>