$(function() {

	$('#IdTenantsNewTenantButton').click(function(){
		document.location = AP_INDEX + '?new';
	});

	$('#IdTenantsDeleteButton').click(function(){
		var oChecked = $('#table_form input:checkbox[name="chCollection[]"]:checked');
		if (0 < oChecked.length)
		{
			if (confirm(Lang.DeleteTenantConfirm)) {
				$('#table_form #action').val('delete');
				$('#table_form').submit();
			}
		}
		else
		{
			OnlineMsgError(Lang.NoTenantsSelected);
		}
	});

});
