$(function () {
	$('#test_btn').click(function () {
		$('#isTestConnection').val('1');
		this.form.submit();
	});
	$('#update_btn').click(function () {
		PopUpWindow(AP_INDEX + '?pop&type=db&action=update');
	});
	$('#create_btn').click(function () {
		PopUpWindow(AP_INDEX + '?pop&type=db&action=create');
	});
});