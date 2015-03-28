<?php
	$this->WriteTabs();
	$this->WriteTopMenu();
?>

<div class="wm_contacts" id="main_contacts">
	<div class="wm_contacts_list" id="contacts" >
		<div id="contact_list_div" class="wm_contact_list_div">
<?php $this->WriteSearch(); ?>
			<form autocomplete="off" id="table_form" action="<?php echo AP_INDEX_FILE; ?>?submit" method="POST">
				<input type="hidden" name="form_id" value="collection" />
				<input type="hidden" name="action" id="action" value="" />

				<div style="position:relative; overflow:hidden;border-top: 1px solid #c3c3c3;">

<table class="wm_hide" id="ps_container">
	<tr>
		<td><div class="wm_inbox_page_switcher_left"></div></td>
		<td class="wm_inbox_page_switcher_pages" id="ps_pages"></td>
		<td><div class="wm_inbox_page_switcher_right"></div></td>
	</tr>
</table>

<table style="width: 100%;" cellpadding="0" cellspacing="0" id="list">
<?php $this->WriteList(); ?>
</table>
				</div>
			</form>
		</div>
	</div>
	<div class="wm_contacts_view_edit" id="contacts_viewer">
<?php  $this->WriteCard(); ?>
		
	</div>
</div>
<div id="lowtoolbar" class="wm_lowtoolbar">
	<span class="wm_lowtoolbar_messages">
<?php $this->WriteLowToolBar(); ?>
		
	</span>
</div>