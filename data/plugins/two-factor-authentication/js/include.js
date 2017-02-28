(function () {

	var 
		oLoginViewModel,
		oSettingsViewModel
	;
	
	AfterLogicApi.addPluginHook('view-model-defined', function (sViewModelName, oViewModel) {
		if (oViewModel && ('CLoginViewModel' === sViewModelName))
		{
			oLoginViewModel = oViewModel;
		}
		if (oViewModel && ('CSettingsViewModel' === sViewModelName))
		{
			oSettingsViewModel = oViewModel;
			if (!AfterLogicApi.getAppDataItem('User').CanLoginWithPassword)
			{
				oSettingsViewModel.getViewModel('two_factor_authentication').visible(false);
			}			
		}
	});
	
	AfterLogicApi.addPluginHook('api-mail-on-password-specified-success', function () {
		oSettingsViewModel.getViewModel('two_factor_authentication').visible(true);
	});	

	AfterLogicApi.addPluginHook('ajax-default-request', function (sAction, oParameters) {
		if (('SystemLogin' === sAction))
		{
			this.oParams = oParameters;
		}
	});

	AfterLogicApi.addPluginHook('ajax-default-response', function (sAction, oData) {
		if (('SystemLogin' === sAction && oData.Result !== false && oData.ContinueAuth !== true))
		{
			oData['StopExecuteResponse'] = true;
			AfterLogicApi.showPopup(VerifyTokenPopup, [this.oParams.Email, this.oParams.IncLogin, oLoginViewModel]);
		}
	});

}());