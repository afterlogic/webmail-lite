/**
 * @constructor
 */
function GooglePickerPopup()
{
	this.allowGoogle = AfterLogicApi.getAppDataItem('SocialGoogle');
	this.googleClientId = AfterLogicApi.getAppDataItem('SocialGoogleId');

	this.picker = null;
	this.pickerCreated = false;
	this.googleApiLoaded = false;
	this.pickerApiLoaded = false;
	this.fCallback = null;
	this.timeOut = null;
}

/**
 * @param {Function} fCallback
 */
GooglePickerPopup.prototype.onShow = function (fCallback)
{
	var 
		oauthToken = null
	;
	
	if (AfterLogicApi.isFunc(fCallback))
	{
		this.fCallback = fCallback;
	}
	if (this.pickerApiLoaded && this.googleApiLoaded)
	{
		oauthToken = window.gapi.auth.getToken();
		if (oauthToken && !_.isEmpty(oauthToken) && !oauthToken.error) 
		{
			this.createPicker();
		}
		else
		{
			this.doGoogleAuth();
		}
	}
};

/**
 * Executes after applying bindings.
 */
GooglePickerPopup.prototype.onApplyBindings = function ()
{
	if (this.allowGoogle)
	{
		AfterLogicApi.loadScript('https://apis.google.com/js/api.js?onload=onGoogleApiLoad',
			_.bind(this.onGoogleApiLoad, this), null, 'onGoogleApiLoad');
	}
};

/**
 * @return {string}
 */
GooglePickerPopup.prototype.popupTemplate = function ()
{
	return 'Plugin_GooglePickerPopup';
};

GooglePickerPopup.prototype.onEscHandler = function ()
{
	this.onCancelClick();
};

GooglePickerPopup.prototype.onCancelClick = function ()
{
	this.closeCommand();
	if (this.pickerApiLoaded && this.picker)
	{
		this.picker.setVisible(false);
	}
};

GooglePickerPopup.prototype.onGoogleApiLoad = function ()
{
	this.googleApiLoaded = true;

	window.gapi.load('auth', {
		'callback': _.bind(this.doGoogleAuth, this)
	});

	window.gapi.load('picker', {
		'callback': _.bind(this.onPickerApiLoad, this)
	});
};

GooglePickerPopup.prototype.doGoogleAuth = function () 
{
	var oauthToken = window.gapi.auth.getToken();
	if (!oauthToken || _.isEmpty(oauthToken) || oauthToken && oauthToken.error)
	{
		window.gapi.auth.init(_.bind(this.checkGoogleAuth, this, true));
	 }
};

GooglePickerPopup.prototype.checkGoogleAuth = function (bImmediate)
{
	this.checkTimer = window.setTimeout(_.bind(this.ckeckPickerCreate, this), 5000);
	window.gapi.auth.authorize({
			'client_id': this.googleClientId,
			'scope': ['https://www.googleapis.com/auth/drive'],
			'immediate': !!bImmediate
		},
		_.bind(this.handleGoogleAuthResult, this));
};

GooglePickerPopup.prototype.handleGoogleAuthResult = function (authResult) 
{
	if (authResult) 
	{
		if (!authResult.error)
		{
			this.createPicker();
		}
		else if (authResult.error === 'immediate_failed')
		{
			window.clearTimeout(this.checkTimer);
			this.checkGoogleAuth(false);
		}
	}
};

GooglePickerPopup.prototype.onPickerApiLoad = function ()
{
	this.pickerApiLoaded = true;
};

GooglePickerPopup.prototype.ckeckPickerCreate = function ()
{
	window.clearTimeout(this.checkTimer);
	if (!this.pickerCreated)
	{
		this.closeCommand();
		AfterLogicApi.showError('COMPOSE/ERROR_GOOGLE_PICKER_POPUP');
	}
};

GooglePickerPopup.prototype.createPicker = function () 
{
	var 
		docsView = null,
		oauthToken = window.gapi.auth.getToken(),
		self = this
	;
	
	if (this.pickerApiLoaded && oauthToken && !oauthToken.error) 
	{
		var 
			oUser = AfterLogicApi.getAppDataItem('User'),
			sLang = oUser ? oUser.DefaultLanguageShort : 'en'
		;
		docsView = new window.google.picker.DocsView()
			.setIncludeFolders(true);
		
		this.picker = new window.google.picker.PickerBuilder()
			.addView(docsView)
			.setOAuthToken(oauthToken.access_token)
			.setAppId(self.googleClientId)
			.setOrigin(window.location.protocol + '//' +  window.location.host)
			.setCallback(_.bind(self.pickerCallback, self, oauthToken.access_token))
			.enableFeature(window.google.picker.Feature.NAV_HIDDEN)
			.enableFeature(window.google.picker.Feature.MULTISELECT_ENABLED)
			.setLocale(sLang)
			.build();

		this.picker.setVisible(true);
		this.picker.Ab.style.zIndex = 2000;

		this.pickerCreated = true;
	}
};

GooglePickerPopup.prototype.pickerCallback = function (sAccessToken, data)
{
	if (data[window.google.picker.Response.ACTION] === window.google.picker.Action.PICKED)
	{
		if (this.fCallback)
		{
			this.fCallback(data[window.google.picker.Response.DOCUMENTS], sAccessToken);
		}
		
		this.closeCommand();
	}
	else if (data[window.google.picker.Response.ACTION] === window.google.picker.Action.CANCEL)
	{
		this.closeCommand();
	}
};

