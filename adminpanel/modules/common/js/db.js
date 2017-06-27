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
	$('#radioSqlTypeSQLite, #radioSqlTypeMySQL, #radioSqlTypePostgreSQL').change(function () {
		if ($('#radioSqlTypeSQLite').prop("checked"))
		{
			$('#txtSqlLogin').prop('disabled', true);
			$('#txtSqlPassword').prop('disabled', true);
			$('#txtSqlSrc').prop('disabled', true);
		}
		else
		{
			$('#txtSqlLogin').prop('disabled', false);
			$('#txtSqlPassword').prop('disabled', false);
			$('#txtSqlSrc').prop('disabled',false);
		}
	});
});