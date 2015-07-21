/**
 * @constructor
 */
function ValidatePasswordPopup()
{
	this.password = ko.observable();

	this.fCallback = null;
	this.verifyButtonText = ko.observable(AfterLogicApi.i18n('AUTHENTICATION_PLUGIN/VERIFY_PASSWORD'));
    this.enterPasswordHint = ko.observable(AfterLogicApi.i18n(AfterLogicApi.i18n('AUTHENTICATION_PLUGIN/ENTER_PASSWORD_HINT', {'EMAIL': AfterLogicApi.getCurrentAccountData().Email})));

	this.iAutoReloadTimer = -1;
	this.opened = ko.observable(false);
    this.oVerifyPasswordViewModel = null;
    this.isFocused = ko.observable(false);
}

/**
 * @return {string}
 */
ValidatePasswordPopup.prototype.popupTemplate = function ()
{
	return 'Plugin_ValidatePasswordPopup';
};

ValidatePasswordPopup.prototype.setAutoReloadTimer = function ()
{
	var self = this;
	clearTimeout(this.iAutoReloadTimer);
	
	this.iAutoReloadTimer = setTimeout(function () {
		self.Status();
	}, 10 * 1000);
};


ValidatePasswordPopup.prototype.onShow = function (oVerifyPasswordViewModel, fCallback)
{
    this.fCallback = fCallback || null;
    this.oVerifyPasswordViewModel = oVerifyPasswordViewModel;
	this.opened(true);
    this.isFocused(true);
};

ValidatePasswordPopup.prototype.Status = function ()
{
	this.setAutoReloadTimer();
};

ValidatePasswordPopup.prototype.Response = function (oResponse)
{
    this.verifyButtonText(AfterLogicApi.i18n('AUTHENTICATION_PLUGIN/VERIFY_PASSWORD'));

    this.password('');
    if (oResponse.Result)
    {
        this.onCancelClick();
        this.fCallback(oResponse);
    } else {
        AfterLogicApi.showError(AfterLogicApi.i18n('AUTHENTICATION_PLUGIN/WRONG_PASSWORD'));
    }
};

ValidatePasswordPopup.prototype.onVerifyClick = function ()
{
    App.Ajax.send({
            'Action': 'ValidatePassword',
            'Password': this.password
        },
        this.Response,
        this
    );

    this.verifyButtonText(AfterLogicApi.i18n('AUTHENTICATION_PLUGIN/VERIFY_IN_PROGRESS'));

    this.setAutoReloadTimer();
};

ValidatePasswordPopup.prototype.onCancelClick = function ()
{
	this.opened(false);
	this.closeCommand();
    this.password('');

    if (this.oVerifyPasswordViewModel)
    {
        this.oVerifyPasswordViewModel.loading(false);
    }
};

ValidatePasswordPopup.prototype.onEscHandler = function ()
{
	this.onCancelClick();
};

ValidatePasswordPopup.prototype.onEnterHandler = function ()
{
    this.onVerifyClick();
};