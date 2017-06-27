(function (window, $) {

	function fDisableInput(sName, bIsDisabled)
	{
		var 
			oInput = $(sName),
			oInputLabel = $(sName + '_label'),

			oInputJs = (oInput && 0 < oInput.length) ? oInput : null,
			oInputLabelJs = (oInputLabel && 0 < oInputLabel.length) ? oInputLabel : null
		;

		if (oInputJs)
		{
			if (bIsDisabled)
			{
				oInputJs.addClass('disabled').attr('disabled', 'disabled');
			}
			else
			{
				oInputJs.removeClass('disabled').attr('disabled', '');
			}

			if (oInputLabelJs)
			{
				if (bIsDisabled)
				{
					oInputLabelJs.addClass('disabled');
				}
				else
				{
					oInputLabelJs.removeClass('disabled');
				}
			}
		}
	}

	$(function () {

		var fCheckDbType = function () {
			if ($('#chSqlTypePostgreSQL').is(':checked'))
			{
				$('#create_db_btn').hide();
			}
			else
			{
				$('#create_db_btn').show();
			}
		};
		
		$('#next-btn-server-check').click(function () {
			if (window.__awm_lite) {
				window.open('https://afterlogic.com/congratulations/afterlogic-webmail-lite-php');
			} else if (window.__awm_au) {
				window.open('https://afterlogic.com/congratulations/aurora');
			} else {
				window.open('https://afterlogic.com/congratulations/afterlogic-webmail-pro-php');
			}
		});
		
		$('#exit-btn-completed').click(function () {
			window.location = '../adminpanel/';
		});

		$('#chSqlTypeMySQL, #chSqlTypePostgreSQL, #chSqlTypeSQLite').click(fCheckDbType);
		fCheckDbType();
		
		$('#chSqlTypeMySQL, #chSqlTypePostgreSQL, #chSqlTypeSQLite').change(function () {
			if ($('#chSqlTypeSQLite').attr("checked"))
			{
				$('#txtSqlLogin').attr('disabled', true);
				$('#txtSqlPassword').attr('disabled', true);
				$('#txtSqlSrc').attr('disabled', true);
			}
			else
			{
				$('#txtSqlLogin').attr('disabled', false);
				$('#txtSqlPassword').attr('disabled', false);
				$('#txtSqlSrc').attr('disabled',false);
			}
		});
	});

}(window, jQuery));