<hr />
<table class="wm_contacts_view">
	<tr>
		<td class="wm_field_title">
			<span id="txtSiteName_label">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_SITENAME');?>
			</span>
		</td>
		<td class="wm_field_value">
			<input type="text" class="wm_input override" name="txtSiteName" id="txtSiteName"
				value="<?php $this->Data->PrintInputValue('txtSiteName'); ?>" />
		</td>
	</tr>
	<tr class="<?php $this->Data->PrintInputValue('classHideDefault') ?>">
		<td class="wm_field_title">
			<span id="txtWebDomain_label">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_WEBDOMAIN');?>
			</span>
		</td>
		<td class="wm_field_value">
			<input type="text" class="wm_input override" name="txtWebDomain" id="txtWebDomain"
				value="<?php $this->Data->PrintInputValue('txtWebDomain'); ?>" />
			&nbsp;&nbsp;&nbsp;
			<a class="<?php $this->Data->PrintInputValue('classLinkWebDomain'); ?>"
				href="<?php $this->Data->PrintInputValue('linkWebDomain'); ?>" target="_blank"><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_WEBDOMAIN_MORE');?></a>
		</td>
	</tr>
	<tr>
		<td class="wm_field_title">
			<span id="selSkin_label">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_SKIN');?>
			</span>
		</td>
		<td class="wm_field_value">
			<select name="selSkin" id="selSkin" class="wm_select override">
				<?php $this->Data->PrintValue('selSkinsOptions'); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="wm_field_title">
			<span id="selSkin_label">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_DEFAULT_TAB');?>
			</span>
		</td>
		<td class="wm_field_value">
			<select name="selTab" id="selSkin" class="wm_select override">
				<?php $this->Data->PrintValue('selTabsOptions'); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="wm_field_value" colspan="2">
			<input type="checkbox" class="wm_checkbox override" value="1"
				name="chAllowUsersAccessInterfaveSettings" id="chAllowUsersAccessInterfaveSettings"
				<?php $this->Data->PrintCheckedValue('chAllowUsersAccessInterfaveSettings'); ?> />
			<label id="chAllowUsersAccessInterfaveSettings_label" for="chAllowUsersAccessInterfaveSettings">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_ACCESS_INTERFACE');?>
			</label>
			
			<div class="wm_information_com">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_GENERAL_ACCESS_INTERFACE_HINT');?>
			</div>
		</td>
	</tr>
</table>
<br />