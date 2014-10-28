$(function() {

	$('#IdChannelsNewChannelButton').click(function(){
		document.location = AP_INDEX + '?new';
	});

	$('#IdChannelsDeleteButton').click(function(){
		var oChecked = $('#table_form input:checkbox[name="chCollection[]"]:checked');
		if (0 < oChecked.length)
		{
			if (confirm(Lang.DeleteChannelConfirm)) {
				$('#table_form #action').val('delete');
				$('#table_form').submit();
			}
		}
		else
		{
			OnlineMsgError(Lang.NoChannelsSelected);
		}
	});

});
