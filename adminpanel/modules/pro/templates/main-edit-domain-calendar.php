<table class="wm_contacts_view">
	<tr>
		<td class="wm_field_title" colspan="2">
			<input type="checkbox" class="wm_checkbox override" name="chEnableCalendar" id="chEnableCalendar"
				   value="1" <?php $this->Data->PrintCheckedValue('chEnableCalendar'); ?> />
			<label id="chEnableCalendar_label" for="chEnableCalendar"><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_CALENDAR_ENABLED');?></label>
		</td>
	</tr>
	<tr>
		<td class="wm_field_title">
			<span id="selWeekStartsOn_label" >
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_CALENDAR_WEEKSTART');?>
			</span>
		</td>
		<td class="wm_field_value">
			<select name="selWeekStartsOn" class="wm_select override" id="selWeekStartsOn">
				<option value="<?php echo EnumConvert::ToPost(ECalendarWeekStartOn::Saturday, 'ECalendarWeekStartOn');
					?>" <?php $this->Data->PrintSelectedValue('optWeekStartsOnSaturday'); ?>><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_CALENDAR_WEEKSTART_SAT');?></option>
				<option value="<?php echo EnumConvert::ToPost(ECalendarWeekStartOn::Sunday, 'ECalendarWeekStartOn');
					?>" <?php $this->Data->PrintSelectedValue('optWeekStartsOnSunday'); ?>><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_CALENDAR_WEEKSTART_SUN');?></option>
				<option value="<?php echo EnumConvert::ToPost(ECalendarWeekStartOn::Monday, 'ECalendarWeekStartOn');
					?>" <?php $this->Data->PrintSelectedValue('optWeekStartsOnMonday'); ?>><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_CALENDAR_WEEKSTART_MON');?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="wm_field_title" colspan="2">
			<input type="checkbox" class="wm_checkbox override" name="chShowWeekends" id="chShowWeekends"
				   value="1" <?php $this->Data->PrintCheckedValue('chShowWeekends'); ?> />
			<label id="chShowWeekends_label" for="chShowWeekends"><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_CALENDAR_WEEKEND');?></label>
		</td>
	</tr>
	<tr>
		<td class="wm_field_title">
			<span id="selWorkdayStarts_label" >
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_CALENDAR_WORKDAY_START');?>
			</span>
		</td>
		<td class="wm_field_value">
			<select name="selWorkdayStarts" class="override" id="selWorkdayStarts">
				<?php $this->Data->PrintValue('selWorkdayStartsOptions'); ?>
			</select>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<span id="selWorkdayEnds_label" >
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_CALENDAR_WORKDAY_END');?>
			</span>
			<select name="selWorkdayEnds" class="override" id="selWorkdayEnds">
				<?php $this->Data->PrintValue('selWorkdayEndsOptions'); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="wm_field_title" colspan="2">
			<input type="checkbox" class="wm_checkbox override" name="chShowWorkday" id="chShowWorkday"
				   value="1" <?php $this->Data->PrintCheckedValue('chShowWorkday'); ?> />
			<label id="chShowWorkday_label" for="chShowWorkday"><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_CALENDAR_WORKDAY');?></label>
		</td>
	</tr>
	
	<tr>
		<td class="wm_field_title">
			<span id="radioDefaultTab_label" >
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_CALENDAR_TAB');?>
			</span>
		</td>
		<td class="wm_field_value">
			<input type="radio" class="wm_checkbox override" name="radioDefaultTab" id="radioDefaultTabDay"
				value="<?php echo EnumConvert::ToPost(ECalendarDefaultTab::Day, 'ECalendarDefaultTab');
					?>" <?php $this->Data->PrintCheckedValue('radioDefaultTabDay'); ?>
				x-data-label="radioDefaultTab_label" />
			<label id="radioDefaultTabDay_label" for="radioDefaultTabDay"><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_CALENDAR_TAB_DAY');?></label>
			&nbsp;&nbsp;&nbsp;
			<input type="radio" class="wm_checkbox override" name="radioDefaultTab" id="radioDefaultTabWeek"
				value="<?php echo EnumConvert::ToPost(ECalendarDefaultTab::Week, 'ECalendarDefaultTab');
					?>" <?php $this->Data->PrintCheckedValue('radioDefaultTabWeek'); ?>
				x-data-label="radioDefaultTab_label" />
			<label id="radioDefaultTabWeek_label" for="radioDefaultTabWeek"><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_CALENDAR_TAB_WEEK');?></label>
			&nbsp;&nbsp;&nbsp;
			<input type="radio" class="wm_checkbox override" name="radioDefaultTab" id="radioDefaultTabMonth"
				value="<?php echo EnumConvert::ToPost(ECalendarDefaultTab::Month, 'ECalendarDefaultTab');
					?>" <?php $this->Data->PrintCheckedValue('radioDefaultTabMonth'); ?>
				x-data-label="radioDefaultTab_label" />
			<label id="radioDefaultTabMonth_label" for="radioDefaultTabMonth"><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_CALENDAR_TAB_MONTH');?></label>
		</td>
	</tr>
</table>