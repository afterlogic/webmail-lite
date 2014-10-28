function MainFormDisable()
{
	var oCh = $('#main_form #top_switchers_content_div #chOverrideSettings');
	if (0 < oCh.length)
	{
		if (oCh.hasClass('domain_id_0'))
		{
			oCh.hide();
			oCh.prop('checked', true);
			$('#main_form #top_switchers_content_div #chOverrideSettings_label').hide();
		}

		if (oCh.is(':checked'))
		{
			$('#main_form #main_tab_container').show();
			$('#main_form #idExternalHosts').show();
		}
		else
		{
			$('#main_form #main_tab_container').hide();
			$('#main_form #idExternalHosts').hide();
		}

		if (window.ResizeElements)
		{
			ResizeElements();
		}
	}
	else
	{
		var oForm = $('#main_form');
		$('input:submit', oForm).val(
			$('#chOverrideSettings', oForm).is(':checked') ?
				Lang.DomainsNextButtonText : Lang.DomainsCreateButtonText);
	}
}
