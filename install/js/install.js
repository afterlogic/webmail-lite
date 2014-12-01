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
				window.open('http://www.afterlogic.com/congratulations/webmail-lite-php');
			} else if (window.__awm_au) {
				window.open('http://www.afterlogic.com/congratulations/aurora');
			} else {
				window.open('http://www.afterlogic.com/congratulations/webmail-pro-php');
			}
		});
		
		$('#exit-btn-completed').click(function () {
			window.location = '../adminpanel/';
		});

		$('#chSqlTypeMySQL, #chSqlTypePostgreSQL').click(fCheckDbType);
		fCheckDbType();
	});

}(window, jQuery));