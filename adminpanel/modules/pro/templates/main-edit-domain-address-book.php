<table class="wm_contacts_view">
	<tr>
		<td class="wm_field_title">
			<span id="selGlobalAddressBook_label">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_ADDRBOOK_GLOBAL');?>
			</span>
		</td>
		<td class="wm_field_value">
			<select name="selGlobalAddressBook" class="wm_select override" id="selGlobalAddressBook">
				<option value="<?php echo EnumConvert::ToPost(EContactsGABVisibility::Off, 'EContactsGABVisibility');
					?>" <?php $this->Data->PrintSelectedValue('optGlobalAddressBookOff'); ?>><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_ADDRBOOK_GLOBAL_OFF');?></option>
				<option value="<?php echo EnumConvert::ToPost(EContactsGABVisibility::DomainWide, 'EContactsGABVisibility');
					?>" <?php $this->Data->PrintSelectedValue('optGlobalAddressBookDomain'); ?>><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_ADDRBOOK_GLOBAL_DOMAIN');?></option>
				<?php if ($this->Data->GetValue('bRType')) { ?>
					<option value="<?php echo EnumConvert::ToPost(EContactsGABVisibility::TenantWide, 'EContactsGABVisibility');
						?>" <?php $this->Data->PrintSelectedValue('optGlobalAddressBookTenant'); ?>><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_ADDRBOOK_GLOBAL_TENANT');?></option>
				<?php } else { ?>
				<option value="<?php echo EnumConvert::ToPost(EContactsGABVisibility::SystemWide, 'EContactsGABVisibility');
					?>" <?php $this->Data->PrintSelectedValue('optGlobalAddressBookSystem'); ?>><?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_ADDRBOOK_GLOBAL_SYSTEM');?></option>
				<?php } ?>
			</select>
			<div class="wm_information_com">
				<?php echo CApi::I18N('ADMIN_PANEL/DOMAINS_ADDRBOOK_GLOBAL_DESC');?>
			</div>
		</td>
	</tr>
</table>