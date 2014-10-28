<tr>
	<td class="wm_settings_list_select" colspan="2">
		<b>Resource usage</b>
	</td>
</tr>
<tr>
	<td colspan="2">
		<br />
	</td>
</tr>
<tr>
	<td align="left" width="150">
		<?php echo CApi::I18N('ADMIN_PANEL/RESOURCES_DISK'); ?>
	</td>
	<td>
		<?php $this->Data->PrintValue('txtDiskSpace') ?>
	</td>
</tr>
<tr>
	<td colspan="2">
		<br />
	</td>
</tr>
<tr>
	<td align="left" width="150">
		<?php echo CApi::I18N('ADMIN_PANEL/RESOURCES_USERS'); ?>
	</td>
	<td>
		<?php $this->Data->PrintValue('txtUsers') ?>
	</td>
</tr>
<tr>
	<td colspan="2">
		<br />
	</td>
</tr>
<tr class="<?php $this->Data->PrintInputValue('hideClassForSubscription'); ?>">
	<td align="left" width="150">
		<?php echo CApi::I18N('ADMIN_PANEL/TENANTS_SUBSCRIPTIONS'); ?>
	</td>
	<td>
		<?php $this->Data->PrintValue('txtSubscriptionPlans'); ?>
	</td>
</tr>
