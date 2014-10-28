$(function() {

	$('#IdUsersNewUserButton').click(function(){
		document.location = AP_INDEX + '?new';
	});

	$('#IdUsersDeleteButton').click(function(){
		var oChecked = $('#table_form input:checkbox[name="chCollection[]"]:checked');
		if (0 < oChecked.length)
		{
			if (confirm(Lang.DeleteUserConfirm)) {
				$('#table_form #action').val('delete');
				$('#table_form').submit();
			}
		}
		else
		{
			OnlineMsgError(Lang.NoUsersSelected);
		}
	});
	$('#IdUsersDisableUserButton').click(function(){
		var oChecked = $('#table_form input:checkbox[name="chCollection[]"]:checked');
		if (0 < oChecked.length)
		{
			$('#table_form #action').val('disable');
			$('#table_form').submit();
		}
		else
		{
			OnlineMsgError(Lang.NoUsersSelected);
		}

	});
	$('#IdUsersEnableUserButton').click(function(){
		var oChecked = $('#table_form input:checkbox[name="chCollection[]"]:checked');
		if (0 < oChecked.length)
		{
			$('#table_form #action').val('enable');
			$('#table_form').submit();
		}
		else
		{
			OnlineMsgError(Lang.NoUsersSelected);
		}
	});

	var oForm = $('#main_form');
	if (oForm && 0 < oForm.length && 'edit' === oForm.find('[name="QueryAction"]').val())
	{
		oForm.find('.wm_secondary_info').hide();
	}
});
