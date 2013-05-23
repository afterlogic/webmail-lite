<hr />
<table class="wm_contacts_view">
	<tr>
		<td class="wm_field_title">
			<span id="txtSiteName_label">
				Site name
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
				Web domain
			</span>
		</td>
		<td class="wm_field_value">
			<input type="text" class="wm_input override" name="txtWebDomain" id="txtWebDomain"
				value="<?php $this->Data->PrintInputValue('txtWebDomain'); ?>" />
			&nbsp;&nbsp;&nbsp;
			<a href="http://www.afterlogic.com/wiki/Configuring_web_domain_names_(WebMail_Pro)" target="_blank">Learn more</a>
		</td>
	</tr>
	<tr>
		<td class="wm_field_title">
			<span id="selSkin_label">
				Skin
			</span>
		</td>
		<td class="wm_field_value">
			<select name="selSkin" id="selSkin" class="wm_select override">
				<?php $this->Data->PrintValue('selSkinsOptions'); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="wm_field_value" colspan="2">
			<input type="checkbox" class="wm_checkbox override" value="1"
				name="chAllowUsersAccessInterfaveSettings" id="chAllowUsersAccessInterfaveSettings"
				<?php $this->Data->PrintCheckedValue('chAllowUsersAccessInterfaveSettings'); ?> />
			<label id="chAllowUsersAccessInterfaveSettings_label" for="chAllowUsersAccessInterfaveSettings">
				Allow users to access interface settings
			</label>
			
			<div class="wm_information_com">
				If the option is checked, users will be able to configure look and feel of webmail interface: skin, language and so on.
			</div>
		</td>
	</tr>
</table>
<br />