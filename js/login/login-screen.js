/*
 * Functions:
 *  LoadWebMailScript()
 *  LoginErrorHandler()
 *  LoginHandler()
 *  TryLoginHandler()
 * Classes:
 *  CLoginScreen(submitHandler)
 *  CLoginDemoLangClass()
 * Functions:
 *  Init()
 */

var
	LoginScreen,
	LoginDemoLangClass,
	InfoObj,
	Browser,
	NetLoader
;

var
	LOGIN_TYPE_DROPDOWN = 1,
	LOGIN_TYPE_DOMAIN = 2,
	LOGIN_TYPE_LOGIN = 3
;

var
	WebMailScripts = [],
	ScriptToLoadIndex = 0,
	ScriptLoader = new CScriptLoader()
;

function LoadWebMailScript()
{
	if (ScriptToLoadIndex >= WebMailScripts.length) {
		return;
	}
	ScriptLoader.load([WebMailScripts[ScriptToLoadIndex]], LoadWebMailScript);
	ScriptToLoadIndex++;
}

function LoginErrorHandler()
{
	InfoObj.hide();
	LoginScreen.showError(this.errorDesc);
	LoginScreen.reloadCaptcha();
}

function OnParsingError(errorCode)
{
	var errorDesc = Lang.ErrorParsing + '<br/>Error code ' + errorCode + '.<br/>';
	LoginScreen.showError(errorDesc);
	LoginScreen.reloadCaptcha();
}


function LoginHandler()
{
	InfoObj.hide();
	var xmlDoc = this.responseXML;
	if (!xmlDoc || !(xmlDoc.documentElement) || typeof xmlDoc != 'object' || typeof xmlDoc.documentElement != 'object') {
		OnParsingError(1);
		return;
	}
	var rootElement = xmlDoc.documentElement;
	if (!rootElement || rootElement.tagName != 'webmail') {
		OnParsingError(2);
		return;
	}

	var errorNode = XmlHelper.getFirstChildNodeByName(rootElement, 'error');
	if (errorNode) {
		var errorDesc = errorNode.childNodes[0].nodeValue;
		LoginScreen.showError((errorDesc && errorDesc.length > 0) ? errorDesc : Lang.ErrorWithoutDesc);
		var captchaNode = XmlHelper.getFirstChildNodeByName(rootElement, 'captcha');
		var captchaValue = XmlHelper.getFirstChildValue(captchaNode, '');
		if (captchaValue == '1') {
			LoginScreen.showCaptcha();
		}
		LoginScreen.reloadCaptcha();
		return;
	}

	var loginNode = XmlHelper.getFirstChildNodeByName(rootElement, 'login');
	if (loginNode === null) {
		LoginScreen.showError(Lang.ErrorEmptyXmlPacket);
		LoginScreen.reloadCaptcha();
		return;
	}

	var id = XmlHelper.getIntAttributeByName(loginNode, 'id_acct', -1);
	var hashNode = XmlHelper.getFirstChildNodeByName(loginNode, 'hash');
	var hash = XmlHelper.getFirstChildValue(hashNode, '');
	var dIos = true === window.DisableIos;

	if (id != -1 && hash != '') {
		Cookies.create('awm_autologin_data', hash, 14);
		Cookies.create('awm_autologin_id', id, 14);
	}
	if (!dIos && Browser.ios && !window.IsLite && !Cookies.readBool('awm_skip_profile')) {
		document.location = 'ios.php';
	}
	else {
		document.location = WebMailUrl + '?check=1';
	}
	var infoDiv = document.getElementById('demo_info');
	if (infoDiv) {
		infoDiv.className = 'wm_hide';
	}
}

function TryLoginHandler()
{
	var errorCont = document.getElementById('info');
	if (errorCont != null) {
		errorCont.className = 'wm_hide';
	}
	InfoObj.show(Lang.Loading);
	InfoObj.resize();
	NetLoader.loadXmlDoc('xml=' + encodeURIComponent(this.xml), 'login', '');
}

function CLoginScreen(submitHandler)
{
	this.tip = new CTip();
    this.onSubmit = submitHandler;
	this.fadeEffect = new CFadeEffect('LoginScreen.fadeEffect');

	this._langsIsShown = false;
	this._langChanger = null;

	this._container = document.getElementById('login_screen');
	this._loginError = new CReport('LoginScreen.hideError();', 10000, 'wm_information wm_error_information', true);
	this._loginError.setFade(this.fadeEffect);
	this._loginError.build(document.body);

	this._loginForm = document.getElementById('login_form');
	this._email = document.getElementById('email');
	this._emailCont = document.getElementById('email_cont');
	this._incLogin = document.getElementById('inc_login');
	this._loginCont = document.getElementById('login_cont');
	this._domain = document.getElementById('sDomainValue');
	this._domainCont = document.getElementById('domain_cont');
	this._password = document.getElementById('password');

	this.bAdvancedMode = false;
	this._incoming = document.getElementById('incoming');
	if (this._incoming) {
		this._allowAdvanced = true;
		this._incProtocol = document.getElementById('inc_protocol');
		this._outgoing = document.getElementById('outgoing');
		this._authentication = document.getElementById('authentication');
		this._incServer = document.getElementById('inc_server');
		this._incPort = document.getElementById('inc_port');
		this._outServer = document.getElementById('out_server');
		this._outPort = document.getElementById('out_port');
		this._smtpAuth = document.getElementById('smtp_auth');
		this._loginModeSwitcher = document.getElementById('login_mode_switcher');
	}
	else {
		this._allowAdvanced = false;
	}

	this._signMe = document.getElementById('sign_me');
	this._language = document.getElementById('language');
	this._body = document.getElementById('mbody');
	this._captchaContent = document.getElementById('captcha_content');
	this._captcha = document.getElementById('captcha');
	this._captchaImg = document.getElementById('captcha_img');
	this._captchaReloadLink = document.getElementById('lang_CaptchaReloadLink');
	this._langsCollection = document.getElementById("langs_collection");
	this._regLink = document.getElementById("reg_link_id");
	this._focusField = null;

	this._init();
	this._makeView();
}

CLoginScreen.prototype = {
	_init: function ()
	{
		var obj = this;

		/* email */
		this._email.onfocus = function () {
			obj.emailFocus();
		};

		this._email.onmouseup = function (e) {
			if ('email' !== obj._focusField) {
				if (e != undefined) {
					e.preventDefault();
				}
				obj._focusField = 'email';
			}
		};

		this._email.onkeypress = function (ev) {
			if (!isEnter(ev)) {
				obj.tip.hide('email');
			}
		};

		/* login */
		if (this._incLogin != null) {
			this._incLogin.onfocus = function () {
				obj.loginFocus();
			};

			this._incLogin.onmouseup = function (e) {
				if ('login' !== obj._focusField) {
					if (e != undefined) {
						e.preventDefault();
					}
					obj._focusField = 'login';
				}
			};

			this._incLogin.onkeypress = function (ev) {
				if (!isEnter(ev)) {
					obj.tip.hide('login');
				}
			};
		}

		/* password */
		this._password.onfocus = function () {
			obj.passwordFocus();
		};

		this._password.onmouseup = function (e) {
			if ('password' !== obj._focusField) {
				if (e != undefined) {
					e.preventDefault();
				}
				obj._focusField = 'password';
			}
		};

		this._password.onkeypress = function (ev) {
			if (!isEnter(ev)) {
				obj.tip.hide('password');
			}
		};

		this._body.onclick = function (event) {
			obj.showLangs(event);
		};

		this._ajaxInit();

		if (this._captchaImg) {
			this._captchaImg.onclick = function () {
				obj.reloadCaptcha();
			};

			if (this._captchaReloadLink) {
				this._captchaReloadLink.onclick = function () {
					obj.reloadCaptcha();
					return false;
				};
			}
		}

		if (this._allowAdvanced) {
			this._initAdvancedFields();
		}
	},

	_initAdvancedFields: function ()
	{
		var obj = this;

		/* incoming mail */
		this._incServer.onkeypress = function (ev) {
			if (!isEnter(ev)) {
				obj.tip.hide('inc_server');
			}
		};
		this._incPort.onkeypress = function (ev) {
			if (!isEnter(ev)) {
				obj.tip.hide('inc_port');
			}
		};
		this._incProtocol.onchange = function () {
			obj._incPort.value = (this.value == IMAP4_PROTOCOL) ? IMAP4_PORT : POP3_PORT;
		};
		/* ougoing mail */
		this._outServer.onkeypress = function (ev) {
			if (!isEnter(ev)) {
				obj.tip.hide('out_server');
			}
		};
		this._outPort.onkeypress = function (ev) {
			if (!isEnter(ev)) {
				obj.tip.hide('out_port');
			}
		};
	},

	showCaptcha: function ()
	{
		if (this._captchaContent) {
			this._captchaContent.className = '';
		}
	},

	reloadCaptcha: function ()
	{
		if (this._captchaImg) {
			this._captchaImg.src = this._captchaImg.src + '&c' + Math.round(Math.random() * 1000);
			if (this._captcha) {
				this._captcha.value= '';
			}
		}
		else if (typeof(Recaptcha) !== 'undefined') {
			Recaptcha.reload();
		}
	},

	showLangs: function (e)
	{
		if (this._langsCollection) {
			e = e ? e : window.event;
			var tgt = window.event ? window.event.srcElement : e.target;
			if (tgt && tgt.parentNode && tgt.parentNode.id == 'langs_selected') {
				if (this._langsIsShown) {
					this._langsCollection.style.display = 'none';
					this._langsIsShown = false;
				}
				else {
					this._langsCollection.style.display = 'block';
					this._langsIsShown = true;
				}
			}
			else {
				this._langsCollection.style.display = 'none';
				this._langsIsShown = false;
			}
		}
		return false;
	},

	changeLang: function (object)
	{
		if (null == this._language) return;
		var newLangName = this._language.value;
		if (object && object.name && object.name.length > 4 && object.name.substr(0, 4) == 'lng_') {
			newLangName = object.name.substr(4);
		}
		if (newLangName == this._language.value) return;

		var isRtl = isRtlLanguage(newLangName);
		if (window.RTL != isRtl) {
			document.location = LoginUrl + '?lang=' + newLangName;
		}
		else {
			this._language.value = newLangName;
			document.getElementById('langs_selected').innerHTML = '<span>' + object.innerHTML
				+ '</span><font>&nbsp;</font><span class="wm_login_lang_switcher">&nbsp;</span>';
			Cookies.create('awm_defLang', newLangName, 635);
			var obj = this;
			ScriptLoader.load([LanguageUrl + '?v=' + WmVersion + '&lang=' + newLangName], function()
			{
				obj._langChanger.go();
                if (obj._loginModeSwitcher) {
					obj._loginModeSwitcher.innerHTML = obj.bAdvancedMode ? Lang.StandardLogin : Lang.AdvancedLogin;
				}
			});
		}
	},

	_initLangs: function () {
		var langObj = document.getElementById('lang_LoginInfo');
		this._langChanger.register('innerHTML', langObj, 'LANG_LoginInfo', '');

		langObj = document.getElementById('lang_Email');
		this._langChanger.register('innerHTML', langObj, 'LANG_Email', ':');

		langObj = document.getElementById('lang_Login');
		this._langChanger.register('innerHTML', langObj, 'LANG_Login', ':');

		langObj = document.getElementById('lang_Password');
		this._langChanger.register('innerHTML', langObj, 'LANG_Password', ':');

		langObj = document.getElementById('lang_Captcha');
		this._langChanger.register('innerHTML', langObj, 'Captcha', ':');

		langObj = document.getElementById('lang_CaptchaReloadLink');
		this._langChanger.register('innerHTML', langObj, 'CaptchaReloadLink', '');

		langObj = document.getElementById('lang_IncServer');
		this._langChanger.register('innerHTML', langObj, 'LANG_IncServer', ':');

		langObj = document.getElementById('lang_IncPort');
		this._langChanger.register('innerHTML', langObj, 'LANG_IncPort', ':');

		langObj = document.getElementById('lang_OutServer');
		this._langChanger.register('innerHTML', langObj, 'LANG_OutServer', ':');

		langObj = document.getElementById('lang_OutPort');
		this._langChanger.register('innerHTML', langObj, 'LANG_OutPort', ':');

		langObj = document.getElementById('lang_UseSmtpAuth');
		this._langChanger.register('innerHTML', langObj, 'LANG_UseSmtpAuth', '');

		langObj = document.getElementById('lang_SignMe');
		this._langChanger.register('innerHTML', langObj, 'LANG_SignMe', '');

		langObj = document.getElementById('submit');
		this._langChanger.register('value', langObj, 'LANG_Enter', '');

		langObj = document.getElementById('reset_link_id');
		this._langChanger.register('innerHTML', langObj, 'IndexResetLink', '');

		langObj = document.getElementById('reg_link_id');
		this._langChanger.register('innerHTML', langObj, 'IndexRegLink', '');
	},

	_makeView: function ()
	{
		if (this._allowAdvanced)
		{
			this._loginModeSwitcher.innerHTML = this.bAdvancedMode ? Lang.StandardLogin : Lang.AdvancedLogin;
			this._incProtocol.className = this.bAdvancedMode ? 'wm_advanced_input': 'wm_hide';
		}

		if (this.bAdvancedMode) {
			this.visibilityEmailField(true, 224);
			this.visibilityLoginField(true, 2);
			this.visibilityDomainField(false);
		}
		else {
			switch (LoginFormType) {
				case LOGIN_TYPE_DROPDOWN:
				case LOGIN_TYPE_DOMAIN:
					this.visibilityEmailField(true, 120);
					this.visibilityLoginField(false, -1);
					this.visibilityDomainField(true);
					break;
				case LOGIN_TYPE_LOGIN:
					this.visibilityEmailField(false, 224);
					this.visibilityLoginField(true, 2);
					this.visibilityDomainField(false);
					break;
				default:
					this.visibilityEmailField(true, 224);
					this.visibilityLoginField(false, -1);
					this.visibilityDomainField(false);
					break;
			}
		}

		if (LOGIN_TYPE_LOGIN === LoginFormType)
		{
			this._incLogin.focus();
		}
		else
		{
			this._email.focus();
		}
	},

	/**
	 * @param {boolean} bShow
	 * @param {number=} iNewWidthValue
	 */
	visibilityEmailField: function (bShow, iNewWidthValue) {
		this._emailCont.className = bShow ? '' : 'wm_hide';
		this._email.style.width = iNewWidthValue + 'px';
	},

	/**
	 * @param {boolean} bShow
	 * @param {number=} iNewTabIndex
	 */
	visibilityLoginField: function (bShow, iNewTabIndex) {
		if (this._incLogin === null) {
			return;
		}
		this._loginCont.className = bShow ? '' : 'wm_hide';
		this._incLogin.tabIndex = iNewTabIndex;
	},

	/**
	 * @param {boolean} bShow
	 */
	visibilityDomainField: function (bShow) {
		this._domainCont.className = bShow ? '' : 'wm_hide';
	},

	emailFocus: function ()
	{
		this._email.className = 'wm_input_focus';
		this._email.select();
	},

	loginFocus: function ()
	{
		if (this._incLogin === null) {
			return;
		}
		this._incLogin.className = 'wm_input_focus';
		if (this._incLogin.value.length == 0 && this._email.value.length != 0) {
			this._incLogin.value = this._email.value;
		}
		this._incLogin.select();
	},

	passwordFocus: function ()
	{
		this._password.className = 'wm_input_focus wm_password_input';
		this._password.select();
	},

	_checkLoginForm: function (loginData)
	{
		this.tip.hide('');
		/* email */
		if (LOGIN_TYPE_LOGIN !== LoginFormType)
		{
			if (Validator.isEmpty(loginData.email)) {
				this.tip.show(Lang.WarningEmailBlank, this._email, 'email');
				return false;
			}
			if (!Validator.isCorrectEmail(loginData.email)) {
				this.tip.show(Lang.WarningCorrectEmail, this._email, 'email');
				return false;
			}
		}

		/* login */
		if ((this.bAdvancedMode || LOGIN_TYPE_LOGIN === LoginFormType) && Validator.isEmpty(loginData.incLogin)) {
			this.tip.show(Lang.WarningLoginBlank, this._incLogin, 'login');
			return false;
		}
		/* password */
		if (Validator.isEmpty(loginData.pass)) {
			this.tip.show(Lang.WarningPassBlank, this._password, 'password');
			return false;
		}
		/* advanced */
		if (this.bAdvancedMode) {
			return this._checkAdvancedFields(loginData);
		}
		return true;
	},

	_checkAdvancedFields: function (loginData)
	{
		/* incoming mail */
		if (Validator.isEmpty(loginData.incServer)) {
			this.tip.show(Lang.WarningIncServerBlank, this._incPort, 'inc_server', this._incServer);
			return false;
		}
		if (!Validator.isCorrectServerName(loginData.incServer)) {
			this.tip.show(Lang.WarningCorrectIncServer, this._incPort, 'inc_server', this._incServer);
			return false;
		}
		if (Validator.isEmpty(loginData.incPort)) {
			this.tip.show(Lang.WarningIncPortBlank, this._incPort, 'inc_port');
			return false;
		}
		else if (!Validator.isPort(loginData.incPort)) {
			this.tip.show(Lang.WarningIncPortNumber + '<br />' + Lang.DefaultIncPortNumber, this._incPort, 'inc_port');
			return false;
		}
		/* outgoing mail */
		if (Validator.isEmpty(loginData.outServer)) {
			this.tip.show(Lang.WarningOutServerBlank, this._outPort, 'out_server', this._outServer);
			return false;
		}
		if (!Validator.isCorrectServerName(loginData.outServer)) {
			this.tip.show(Lang.WarningCorrectSMTPServer, this._outPort, 'out_server', this._outServer);
			return false;
		}
		if (Validator.isEmpty(loginData.outPort)) {
			this.tip.show(Lang.WarningOutPortBlank, this._outPort, 'out_port');
			return false;
		}
		if (!Validator.isPort(loginData.outPort)) {
			this.tip.show(Lang.WarningOutPortNumber + '<br />' + Lang.DefaultOutPortNumber, this._outPort, 'out_port');
			return false;
		}
		return true;
	},

	sendLoginForm: function ()
	{
		var loginData = {};
		loginData.email = Trim(this._email.value);
		if (!this.bAdvancedMode) {
			switch (LoginFormType) {
				case LOGIN_TYPE_DROPDOWN:
				case LOGIN_TYPE_DOMAIN:
					var domainValue = this._domain.value;
					loginData.email += '@' + domainValue;
				case LOGIN_TYPE_LOGIN:
					loginData.incLogin = Trim(this._incLogin.value);
					break;
			}
		}
		loginData.pass = Trim(this._password.value);
		loginData.signMe = this._signMe.checked ? '1' : '0';
		if (this.bAdvancedMode) {
			loginData.incLogin = Trim(this._incLogin.value);
			loginData.incServer = Trim(this._incServer.value);
			loginData.incPort = Trim(this._incPort.value);
			loginData.incProtocol = Trim(this._incProtocol.value);
			loginData.outServer = Trim(this._outServer.value);
			loginData.outPort = Trim(this._outPort.value);
			loginData.outAuth = this._smtpAuth.checked ? '1' : '0';
		}
		if (!this._checkLoginForm(loginData)) {
			return;
		}
		this.hideError();
		var xml = '<param name="action" value="login" /><param name="request" value="" />';
		xml += '<param name="token" value="' + GetCsrfToken() + '"/>';

		if (LoginFormType !== LOGIN_TYPE_LOGIN) {
			xml += '<param name="email">' + GetCData(loginData.email) + '</param>';
		}

		xml += '<param name="mail_inc_pass">' + GetCData(loginData.pass) + '</param>';
		xml += '<param name="sign_me" value="' + loginData.signMe + '"/>';
		xml += '<param name="advanced" value="' + (this.bAdvancedMode ? '1' : '0') + '"/>';

		if (this.bAdvancedMode || LoginFormType === LOGIN_TYPE_LOGIN) {
			xml += '<param name="mail_inc_login">' + GetCData(loginData.incLogin) + '</param>';
		}

		if (this.bAdvancedMode) {
			xml += '<param name="mail_inc_host">' + GetCData(loginData.incServer) + '</param>';
			xml += '<param name="mail_inc_port" value="' + loginData.incPort + '"/>';
			xml += '<param name="mail_protocol" value="' + loginData.incProtocol + '"/>';
			xml += '<param name="mail_out_host">' + GetCData(loginData.outServer) + '</param>';
			xml += '<param name="mail_out_port" value="' + loginData.outPort + '"/>';
			xml += '<param name="mail_out_auth" value="' + loginData.outAuth + '"/>';
		}

		if (this._domain != null) {
			xml += '<param name="domain_name">' + GetCData(this._domain.value) + '</param>';
		}

		var reCaptchaChallengeField = document.getElementById('recaptcha_challenge_field');
		var reCaptchaResponseField = document.getElementById('recaptcha_response_field');
		if (reCaptchaChallengeField && reCaptchaResponseField) {
			xml += '<param name="recaptcha_challenge_field" value="' + reCaptchaChallengeField.value + '" />';
			xml += '<param name="recaptcha_response_field" value="' + reCaptchaResponseField.value + '" />';
		}
		else if (this._captcha) {
			xml += '<param name="captcha" value="' + this._captcha.value + '"/>';
		}

		if (this._language == null) {
			xml += '<param name="language">' + GetCData('') + '</param>';
		}
		else {
			var lang = this._language.value;
			if (lang.length == 0) {
				var getRequestParams = ParseGetParams();
				if (getRequestParams.lang && getRequestParams.lang.length > 0) {
					lang = getRequestParams.lang;
				}
			}
			xml += '<param name="language">' + GetCData(lang) + '</param>';
		}
		xml = '<?xml version="1.0" encoding="utf-8"?><webmail>' + xml + '</webmail>';
		if (Browser.ie) {
			this._email.blur();
			this._password.blur();
			if (this.bAdvancedMode || LoginFormType === LOGIN_TYPE_LOGIN) {
				this._incLogin.blur();
			}
			if (this.bAdvancedMode) {
				this._incServer.blur();
				this._incPort.blur();
				this._outServer.blur();
				this._outPort.blur();
			}
		}
		this.onSubmit.call({xml: xml});
	},

	changeMode: function ()
	{
		if (!this._allowAdvanced) {
			return;
		}

		this.tip.hide('');
		this.bAdvancedMode = !this.bAdvancedMode;
		this._makeView();
	},

	_ajaxInit: function ()
	{
		var obj = this;

		if (this._allowAdvanced) {
			this._loginModeSwitcher.href = '#';
			this._loginModeSwitcher.onclick = function () {
				var sDir = (obj.bAdvancedMode) ? 'hide' : 'show';
				Slider.slideIt('advanced_fields', sDir);
				return false;
			};
		}

		this._loginForm.onsubmit = function () {
			return false;
		};

		var submit = document.getElementById('submit');
		submit.onclick = function () {
			obj.sendLoginForm();
		};
		if (NeedToSubmit) {
			this.sendLoginForm();
		}

		this._langChanger = new CLanguageChanger();
		this._initLangs();

		if (this._langsCollection) {
			var ahrefs = this._langsCollection.getElementsByTagName('A');
			var name = '';
			for (var i = 0; i < ahrefs.length; i++) {
				name = ahrefs[i].getAttribute('name');
				if (name.length > 4 && name.substr(0, 4) == 'lng_') {
					ahrefs[i].onclick = function () {
						obj.changeLang(this);
						return false;
					};
				}
			}
		}
	},

	showError: function (errorDesc)
	{
		this._loginError.show(errorDesc);
	},

	hideError: function ()
	{
		this._loginError.hide();
	},

	show: function ()
	{
		this._container.className = '';
		if (this._regLink) {
			this._regLink.className = 'wm_reg_link';
		}
	},

	hide: function ()
	{
		this._container.className = 'wm_hide';
		if (this._regLink) {
			this._regLink.className = 'wm_hide';
		}
	}
};

function CLoginDemoLangClass()
{
	this._currentLang = '';
	this._contOne = document.getElementById('langDemoTop');
	this._contTwo = document.getElementById('langDemoBottom');
}

CLoginDemoLangClass.prototype = {
	checkLang: function (name)
	{
		if (this._currentLang == name) {
			return;
		}

		var childsOne = (this._contOne) ? this._contOne.getElementsByTagName('a') : [];
		var childsTwo = (this._contTwo) ? this._contTwo.getElementsByTagName('a') : [];

		for (var i = 0; i < childsOne.length; i++) {
			this._uncheckNode(childsOne.item(i));
		}
		for (i = 0; i < childsTwo.length; i++) {
			this._uncheckNode(childsTwo.item(i));
		}
		for (i = 0; i < childsOne.length; i++) {
			this._initNode(childsOne.item(i), name);
		}
		for (i = 0; i < childsTwo.length; i++) {
			this._initNode(childsTwo.item(i), name);
		}
	},

	_initNode: function (aNode, name)
	{
		if (aNode && aNode.name == name){
			aNode.className = aNode.className + ' active';
			this._currentLang = aNode.name;
		}
	},

	_uncheckNode: function (aNode)
	{
		if (aNode) {
			aNode.className = aNode.className.replace(/ active/g, '');
		}
	}
};

function Init()
{
	var oTransport, eRegLink, oError;

	Browser = new CBrowser();
	NetLoader = new CNetLoader(ActionUrl, LoginHandler, LoginErrorHandler);
	LoginDemoLangClass = new CLoginDemoLangClass();
	oTransport = NetLoader.getTransport();

	if (oTransport) {
		InfoObj = new CReport('', 0,  'wm_information wm_status_information', false);
		InfoObj.build(document.body);

	    setTimeout('LoadWebMailScript();', 3000);

		LoginScreen = new CLoginScreen(TryLoginHandler);

		if (window.DemoLangInit) {
			window.DemoLangInit();
		}
	}
	else {
		eRegLink = document.getElementById('reg_link_id');
		if (eRegLink) {
			eRegLink.style.display = 'none';
		}

		oError = new CReport('', 10000, 'wm_information wm_error_information', false);
		oError.build(document.body);
		oError.show(Lang.LoginBrowserWarning);
	}
}

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}

