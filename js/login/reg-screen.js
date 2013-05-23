/*
 * Classes:
 *  CRegScreen(submitHandler)
 * Functions:
 *  RegErrorHandler()
 *  RegHandler()
 *  TryRegHandler()
 *  Init()
 */

var WebMail;
var infoObj, infoMessage;
var Browser;
var NetLoader;
var RegScreen;

var ScriptLoader = new CScriptLoader();

function CRegScreen(submitHandler)
{
	this.tip = new CTip();
	this.onSubmit = submitHandler;
	this._langsIsShown = false;
	this._langChanger = null;

	this.RegForm = document.getElementById('reg_form');

	this._name = document.getElementById('reg_name');
	this._login = document.getElementById('reg_login');
	this._domain = document.getElementById('reg_domain');
	this._signMe = document.getElementById('sign_me');
	this._pass1 = document.getElementById('reg_pass_1');
	this._pass2 = document.getElementById('reg_pass_2');

	this._question_1 = document.getElementById('reg_question_1');
	this._question_2 = document.getElementById('reg_question_2');

	this._answer_1 = document.getElementById('reg_answer_1');
	this._answer_2 = document.getElementById('reg_answer_2');

	this._timezone = document.getElementById('reg_timezone');
	this._lang = document.getElementById('reg_lang');
	this._captcha = document.getElementById('captcha');

	this._body = document.getElementById('mbody');

	this._container = $('#registration_screen');
	this._regErrorCont = $('#reg_error');
	this._regErrorMess = $('#reg_error_message');

	this._captchaImg = document.getElementById('captcha_img');
	this._captchaReloadLink = document.getElementById('lang_CaptchaReloadLink');
	this._language = document.getElementById('language');
	this._langs_collection = document.getElementById('langs_collection');
	this._langs_selected = document.getElementById('langs_selected');

	this.Init();
}

CRegScreen.prototype = {
	Init: function ()
	{
		var obj = this;

		this._body.onclick = function (event) {
			obj.showLangs(event);
		};

		this.AjaxInit();

		if (this._captchaImg) {
			this._captchaImg.onclick = function () {
				obj.ReloadCaptcha();
			};

			if (this._captchaReloadLink) {
				this._captchaReloadLink.onclick = function () {
					obj.ReloadCaptcha();
					return false;
				};
			}
		}

		return true;
	},

	ReloadCaptcha: function () {
		if (this._captchaImg) {
			this._captchaImg.src = this._captchaImg.src + '&c' + Math.round(Math.random() * 1000);
			if (this._captcha) {
				this._captcha.value= '';
			}
		}
	},

	showLangs: function (e) {
		if (this._langs_collection) {
			e = e ? e : window.event;
			var tgt = window.event ? window.event.srcElement : e.target;
			if (tgt && tgt.parentNode && tgt.parentNode.id == 'langs_selected') {
				if (this._langsIsShown) {
					this._langs_collection.style.display = "none";
					this._langsIsShown = false;
				} else {
					this._langs_collection.style.display = "block";
					this._langsIsShown = true;
				}
			} else {
				this._langs_collection.style.display = "none";
				this._langsIsShown = false;
			}
		}
		return false;
	},

	ChangeLang: function (object) {
		var obj, isRtl;
		if (null == this._language) {
			return;
		}
		obj = this;
		if (object && object.name && object.name.length > 4 && object.name.substr(0, 4) == 'lng_') {
			isRtl = isRtlLanguage(object.name.substr(4));
			if (window.RTL && !isRtl || !window.RTL && isRtl) {
				document.location = LoginUrl + '?lang=' + object.name.substr(4);
			} else {
				this._language.value = object.name.substr(4);
				document.getElementById('langs_selected').innerHTML = '<span>' + object.innerHTML + '</span><font>&nbsp;</font><span class="wm_login_lang_switcher">&nbsp;</span>';
				Cookies.create('awm_defLang', object.name.substr(4), 635);
				ScriptLoader.load([LanguageUrl + '?v=' + WmVersion + '&lang=' + object.name.substr(4)], function () {
					obj._langChanger.go();
				});
			}
		}
	},

	InitLangs: function () {
		var langObj = document.getElementById('lang_RegTitle');
		this._langChanger.register('innerHTML', langObj, 'RegRegistrationTitle', '');

		langObj = document.getElementById('lang_RegName');
		this._langChanger.register('innerHTML', langObj, 'RegName', ':');

		langObj = document.getElementById('lang_RegEmail');
		this._langChanger.register('innerHTML', langObj, 'RegEmail', ':');

		langObj = document.getElementById('lang_RegEmailDesc');
		this._langChanger.register('innerHTML', langObj, 'RegEmailDesc', '');

		langObj = document.getElementById('lang_SignMe');
		this._langChanger.register('innerHTML', langObj, 'RegSignMe', '');

		langObj = document.getElementById('lang_RegSignMeDesc');
		this._langChanger.register('innerHTML', langObj, 'RegSignMeDesc', '');

		langObj = document.getElementById('lang_RegPass1');
		this._langChanger.register('innerHTML', langObj, 'RegPass1', ':');

		langObj = document.getElementById('lang_RegPass2');
		this._langChanger.register('innerHTML', langObj, 'RegPass2', ':');

		langObj = document.getElementById('lang_RegQuestionDesc');
		this._langChanger.register('innerHTML', langObj, 'RegQuestionDesc', '');

		langObj = document.getElementById('lang_RegQuestion1');
		this._langChanger.register('innerHTML', langObj, 'RegQuestion1', ':');

		langObj = document.getElementById('lang_RegAnswer1');
		this._langChanger.register('innerHTML', langObj, 'RegAnswer1', ':');

		langObj = document.getElementById('lang_RegQuestion2');
		this._langChanger.register('innerHTML', langObj, 'RegQuestion2', ':');

		langObj = document.getElementById('lang_RegAnswer2');
		this._langChanger.register('innerHTML', langObj, 'RegAnswer2', ':');

		langObj = document.getElementById('lang_RegTimeZone');
		this._langChanger.register('innerHTML', langObj, 'RegTimeZone', ':');

		langObj = document.getElementById('lang_RegLang');
		this._langChanger.register('innerHTML', langObj, 'RegLang', ':');

		langObj = document.getElementById('lang_RegCaptcha');
		this._langChanger.register('innerHTML', langObj, 'RegCaptcha', ':');

		langObj = document.getElementById('lang_CaptchaReloadLink');
		this._langChanger.register('innerHTML', langObj, 'CaptchaReloadLink', '');

		langObj = document.getElementById('submitId');
		this._langChanger.register('value', langObj, 'RegSubmitButtonValue', '');

		langObj = document.getElementById('return_id');
		this._langChanger.register('innerHTML', langObj, 'RegReturnLink', '');
	},

	CheckRegForm: function ()
	{
		this.tip.hide('');
		var vLogin = Trim(this._login.value);
		if (Validator.isEmpty(vLogin)) {
			this.tip.show(Lang.WarningEmailBlank, this._login, 'reg_login');
			return false;
		}

		var vEmail = vLogin + '@' + Trim(this._domain.value);
		if (!Validator.isCorrectEmail(vEmail)) {
			this.tip.show(Lang.WarningCorrectEmail, this._login, 'reg_login');
			return false;
		}

		var vPass1 = Trim(this._pass1.value);
		var vPass2 = Trim(this._pass2.value);
		if (Validator.isEmpty(vPass1)) {
			this.tip.show(Lang.WarningPassBlank, this._pass1, 'reg_pass_1');
			return false;
		}
		if (Validator.isEmpty(vPass2)) {
			this.tip.show(Lang.WarningPassBlank, this._pass2, 'reg_pass_2');
			return false;
		}
		if (vPass1 != vPass2) {
			this.tip.show(Lang.WarningPassNotMatch, this._pass2, 'reg_pass_2');
			return false;
		}
		var vQuestion1 = Trim(this._question_1.value);
		if (Validator.isEmpty(vQuestion1)) {
			this.tip.show(Lang.WarningFieldBlank, this._question_1, 'reg_question_1');
			return false;
		}
		var vQuestion2 = Trim(this._question_2.value);
		if (Validator.isEmpty(vQuestion2)) {
			this.tip.show(Lang.WarningFieldBlank, this._question_2, 'reg_question_2');
			return false;
		}
		var vAnswer1 = Trim(this._answer_1.value);
		if (Validator.isEmpty(vAnswer1)) {
			this.tip.show(Lang.WarningFieldBlank, this._answer_1, 'reg_answer_1');
			return false;
		}
		var vAnswer2 = Trim(this._answer_2.value);
		if (Validator.isEmpty(vAnswer2)) {
			this.tip.show(Lang.WarningFieldBlank, this._answer_2, 'reg_answer_2');
			return false;
		}
		if (this._captcha) {
			var vCapcha = Trim(this._captcha.value);
			if (Validator.isEmpty(vCapcha)) {
				this.tip.show(Lang.WarningFieldBlank, this._captcha, 'captcha');
				return false;
			}
		}

		return true;
	},

	SendRegForm: function ()
	{
		if (!this.CheckRegForm()) {
			return;
		}

		var xml = '<param name="action" value="registration" /><param name="request" value="" />';

		xml += '<param name="token" value="' + GetCsrfToken() + '"/>';

		xml += '<param name="name">' + GetCData(this._name.value) + '</param>';
		xml += '<param name="login">' + GetCData(this._login.value) + '</param>';
		xml += '<param name="domain">' + GetCData(this._domain.value) + '</param>';
		xml += '<param name="pass">' + GetCData(this._pass1.value) + '</param>';

		xml += '<param name="question_1">' + GetCData(this._question_1.value) + '</param>';
		xml += '<param name="answer_1">' + GetCData(this._answer_1.value) + '</param>';
		xml += '<param name="question_2">' + GetCData(this._question_2.value) + '</param>';
		xml += '<param name="answer_2">' + GetCData(this._answer_2.value) + '</param>';

		xml += '<param name="timezone">' + GetCData(this._timezone.value) + '</param>';
		xml += '<param name="lang">' + GetCData(this._lang.value) + '</param>';
		if (this._captcha) {
			xml += '<param name="captcha">' + GetCData(this._captcha.value) + '</param>';
		}

		var signMe = this._signMe.checked ? '1' : '0';
		xml += '<param name="sign_me" value="' + signMe + '"/>';

		xml = '<?xml version="1.0" encoding="utf-8"?><webmail>' + xml + '</webmail>';
		this.onSubmit.call({ xml: xml });
	},

	AjaxInit: function ()
	{
		var submit, obj, ahrefs, name, i;
		obj = this;

		this.RegForm.onsubmit = function () {
			return false;
		};

		submit = document.getElementById('submitId');
		submit.onclick = function () {
			obj.SendRegForm();
		};
		if (NeedToSubmit) {
			this.SendRegForm();
		}

		this._langChanger = new CLanguageChanger();
		this.InitLangs();

		if (this._langs_collection) {
			ahrefs = this._langs_collection.getElementsByTagName('A');
			name = '';
			for (i = 0; i < ahrefs.length; i++) {
				name = ahrefs[i].getAttribute('name');
				if (name.length > 4 && name.substr(0, 4) == 'lng_') {
					ahrefs[i].onclick = function () {
						obj.ChangeLang(this);
						return false;
					};
				}
			}
		}
	},

	showError: function (errorDesc)
	{
		this._regErrorMess.text(errorDesc);
		var obj = this._regErrorCont;

		obj.removeClass('wm_hide')
			.show()
			.css({
				'left': ($(window).width() - obj.width()) / 2,
				'right': 'auto'
			})
			.find('.wm_close_info_image')
			.click(function () {
				obj.hide();
			});
	},

	hideError: function ()
	{
		this._regErrorCont.className = 'wm_hide';
	},

	show: function ()
	{
		this._container.removeClass('wm_hide');
	},

	hide: function ()
	{
		this._container.addClass('wm_hide');
	}
};


function RegErrorHandler()
{
	infoObj.hide();
	RegScreen.showError(this.errorDesc);
	RegScreen.ReloadCaptcha();
}

function RegHandler()
{
	var XmlDoc, RootElement, ErrorTag, errorDesc, Objects, iCount, i, j, attr, hashParts, jCount, part;
	infoObj.hide();
	XmlDoc = this.responseXML;
	if (XmlDoc && XmlDoc.documentElement && typeof(XmlDoc) == 'object' && typeof(XmlDoc.documentElement) == 'object') {
		RootElement = XmlDoc.documentElement;
		if (RootElement && RootElement.tagName == 'webmail') {
			ErrorTag = RootElement.getElementsByTagName('error')[0];
			if (ErrorTag) {
				errorDesc = ErrorTag.childNodes[0].nodeValue;
				RegScreen.showError((errorDesc && errorDesc.length > 0) ? errorDesc : Lang.ErrorWithoutDesc);
				RegScreen.ReloadCaptcha();
			} else {
				Objects = RootElement.childNodes;
				if (Objects.length == 0) {
					RegScreen.showError(Lang.ErrorEmptyXmlPacket);
					RegScreen.ReloadCaptcha();
				} else {
					iCount = Objects.length;
					for (i = iCount - 1; i >= 0; i--) {
						if (Objects[i].tagName == 'registration') {
							var hash = '';
							var id = -1;
							attr = Objects[i].getAttribute('id_acct'); if (attr) id = attr - 0;
							hashParts = Objects[i].childNodes;
							jCount = hashParts.length;
							for (j = jCount - 1; j >= 0; j--) {
								part = hashParts[j].childNodes;
								if (part.length > 0 && hashParts[j].tagName == 'hash') {
									hash = part[0].nodeValue;
								}
							}
							if (id != -1 && hash != '') {
								Cookies.create('awm_autologin_data', hash, 14);
								Cookies.create('awm_autologin_id', id, 14);
							}

							document.location  = WebMailUrl + '?check=1';
						}
					}
				}
			}//if (ErrorTag)
		}
		else {
			LoginScreen.showError(Lang.ErrorParsing + '<br/>Error code 2.');
			LoginScreen.ReloadCaptcha();
		}//if (RootElement)
	}
	else {
		LoginScreen.showError(Lang.ErrorParsing + '<br/>Error code 1.');
		LoginScreen.ReloadCaptcha();
	}//if (XmlDoc)
}

function TryRegHandler()
{
	infoMessage.innerHTML = Lang.Loading;
	infoObj.show();
	infoObj.resize();
	NetLoader.loadXmlDoc('xml=' + encodeURIComponent(this.xml), 'registration', '');
}

function Init()
{
	var transport, infoElem;
	Browser = new CBrowser();

	NetLoader = new CNetLoader(ActionUrl, RegHandler, RegErrorHandler);
	transport = NetLoader.getTransport();

	if (transport) {
	    infoElem = document.getElementById('info');
	    infoMessage = document.getElementById('info_message');
	    infoObj = new CInformation(infoElem, 'wm_information wm_status_information');

		RegScreen = new CRegScreen(TryRegHandler);
	}
}