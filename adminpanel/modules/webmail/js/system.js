$(function () {

	// logs
	$('#btnDownloadLog').click(function () {
		GoToLocation(AP_INDEX + '?pop&type=dllog');
	});

	$('#btnViewLog').click(function () {
		PopUpWindow(AP_INDEX + '?pop&type=log&action=view');
	});

	$('#btnUserActivityDownloadLog').click(function () {
		GoToLocation(AP_INDEX + '?pop&type=dluseractivity');
	});
	
	$('#btnUserActivityViewLog').click(function () {
		PopUpWindow(AP_INDEX + '?pop&type=useractivity&action=view');
	});
	// -- logs

	$('#sync_form .wm_secondary_info').show();
});