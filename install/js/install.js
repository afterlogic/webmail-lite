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
		
		$('#next-btn-server-check').click(function () {
			if (window.__awm_lite) {
				window.open('http://www.afterlogic.com/congratulations/webmail-lite-php');
			} else {
				window.open('http://www.afterlogic.com/congratulations/webmail-pro-php');
			}
		});
		
		$('#exit-btn-completed').click(function () {
			window.location = '../adminpanel/';
		});
	});

}(window, jQuery));