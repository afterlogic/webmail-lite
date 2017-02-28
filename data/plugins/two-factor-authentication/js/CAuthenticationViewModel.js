/**
 * @constructor
 */
function CAuthenticationViewModel()
{
    this.enableTwoAuth = ko.observable(false);
    this.showButton = ko.observable(true);

    this.code = ko.observable('');
    this.QRcode = ko.observable('');
    this.pin = ko.observable('');
    this.enableButton = ko.observable(AfterLogicApi.i18n('AUTHENTICATION_PLUGIN/BUTTON_ENABLE'));
    this.saveButton = ko.observable((AfterLogicApi.i18n('AUTHENTICATION_PLUGIN/BUTTON_SAVE')));
    this.enableButtonDescription = ko.observable((AfterLogicApi.i18n('AUTHENTICATION_PLUGIN/SERVICES_TWO_AUTH_HINT')));
    this.status = ko.observable(false);

    this.getResponse = _.bind(this.getResponse, this);
    this.visible = ko.observable(true);
}

CAuthenticationViewModel.prototype.TemplateName = 'Plugin_AuthenticationTemplate';
CAuthenticationViewModel.prototype.TabName = 'two_factor_authentication';
CAuthenticationViewModel.prototype.TabTitle = AfterLogicApi.i18n('AUTHENTICATION_PLUGIN/TAB_NAME');

CAuthenticationViewModel.prototype.getResponse = function ()
{
    this.oResult = arguments[0].Result;

    if (this.oResult)
    {
        this.getSettings();
    }
};

/**
 * @param {object} oResult
 */
CAuthenticationViewModel.prototype.onSaveClick = function (oResult)
{
    AfterLogicApi.showPopup(ValidatePasswordPopup, ['', this.getResponse, this]);
};

/**
 * @param {string} sCode
 * @returns {string}
 *
 * Formats 16-char secret to XXXX XXXX XXXX XXXX
 */
CAuthenticationViewModel.prototype.splitCode = function (sCode)
{
    this.charsPerLine = 4;
    this.lineCount = Math.ceil(sCode.length / this.charsPerLine);
    this.lines = "";

    for (var i = 0; i < this.lineCount; i++)
    {
        this.lines += sCode.slice(i * this.charsPerLine, i * this.charsPerLine + this.charsPerLine) + " ";
    }

    return this.lines;
};

CAuthenticationViewModel.prototype.validatePin = function ()
{
    /**
     * @type {{Action: string, Pin: string, Code: string}}
     */
    var
        oParameters = {
            'Action': 'TwoFactorAuthenticationSave',
            'Pin': this.pin,
            'Code': this.code
        }
        ;

    this.saveButton(AfterLogicApi.i18n('AUTHENTICATION_PLUGIN/BUTTON_SAVING'));

    App.Ajax.send(oParameters, this.onResponseValidate, this);
};

/**
 * @param {object} oResult
 */
CAuthenticationViewModel.prototype.onResponseValidate = function (oResult)
{
    this.saveButton(AfterLogicApi.i18n('AUTHENTICATION_PLUGIN/BUTTON_SAVE'));

    if(oResult.Result == true)
    {
        this.pin('');
        this.code('');
        this.QRcode('');
        this.enableTwoAuth(false);
        this.showButton(true);
        this.enableButton(AfterLogicApi.i18n('AUTHENTICATION_PLUGIN/BUTTON_CONFIGURE'));

        AfterLogicApi.showReport(AfterLogicApi.i18n('AUTHENTICATION_PLUGIN/REPORT_SUCCESS'));
    } else {
        AfterLogicApi.showError(AfterLogicApi.i18n('AUTHENTICATION_PLUGIN/WRONG_CODE'));
    }
};

CAuthenticationViewModel.prototype.getSettings = function ()
{
    this.enableTwoAuth(!this.enableTwoAuth());

    /**
     * @type {{Action: string, Enable: bool}}
     */
    var
        oParameters = {
            'Action': 'TwoFactorAuthenticationSettings',
            'Enable': this.enableTwoAuth()
        }
        ;

    App.Ajax.send(oParameters, this.onSettingsResponse, this);
};

/**
 * @param oResult
 */
CAuthenticationViewModel.prototype.onSettingsResponse = function (oResult)
{
    if (oResult.Result.Enabled == false)
    {
        this.showButton(false);
    }

    if (oResult.Result.Code != null)
    {
        this.code(this.splitCode(oResult.Result.Code));
        this.QRcode(oResult.Result.QRcode);
        this.enableButton(AfterLogicApi.i18n('AUTHENTICATION_PLUGIN/BUTTON_DISABLE'));
    } else {
        this.enableButton(AfterLogicApi.i18n('AUTHENTICATION_PLUGIN/BUTTON_ENABLE'));
        this.enableButtonDescription(AfterLogicApi.i18n('AUTHENTICATION_PLUGIN/SERVICES_TWO_AUTH_HINT'));
    }
};

CAuthenticationViewModel.prototype.onRoute = function ()
{
    this.showButton(true);
    this.pin('');
    this.code('');
    this.QRcode('');
    this.enableTwoAuth(false);

    if (this.enableButton() == AfterLogicApi.i18n('AUTHENTICATION_PLUGIN/BUTTON_DISABLE'))
    {
        this.enableButton(AfterLogicApi.i18n('AUTHENTICATION_PLUGIN/BUTTON_ENABLE'));
    }

    /**
     * @type {{Action: string}}
     */
    App.Ajax.send({'Action': 'TwoFactorOnRouteAuthenticationSettings'}, this.onRouteSettingsResponse, this);
};

/**
 * @param oResult
 */
CAuthenticationViewModel.prototype.onRouteSettingsResponse = function (oResult)
{
    if (oResult.Result)
    {
        this.enableButton(AfterLogicApi.i18n('AUTHENTICATION_PLUGIN/BUTTON_CONFIGURE'));
        this.enableButtonDescription(AfterLogicApi.i18n('AUTHENTICATION_PLUGIN/SERVICES_TWO_AUTH_HINT_ENABLED'));
        this.status(oResult.Result);
    } else if (oResult.ErrorMessage) {
        AfterLogicApi.showError(oResult.ErrorMessage);
    }
};

AfterLogicApi.addSettingsTab(CAuthenticationViewModel);