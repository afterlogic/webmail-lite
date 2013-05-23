/*
 * Classes:
 *  CCommonSettingsPane(oParent)
 */

function CCommonSettingsPane(oParent)
{
	this._oParent = oParent;
	
	this._mainForm = null;
	
	this._settings = null;
	this._newSettings = null;
	
	this.hasChanges = false;
	this._shown = false;
	
	this._messPerPageObj = null;
	this._messPerPageCont = null;
	this._contactsPerPageObj = null;
	this._contactsPerPageCont = null;
	this._autoCheckMailObj = null;
	this._autoCheckMailCont = null;
	this._autoCheckMailBuilded = false;
	this._skinObj = null;
	this._skinBuilded = false;
	this._skinCont = null;
	this._timeOffsetObj = null;
	this._timeOffsetBuilded = false;
	this._timeOffsetCont = null;
	this._12timeFormatObj = null;
	this._24timeFormatObj = null;
	this._timeFormatCont = null;
	this._languageObj = null;
	this._languageBuilded = false;
	this._languageCont = null;
	this._dateFormatObj = null;
	this._dateFormatBuilded = false;
	this._dateFormatCont = null;
	
	this._editorObj = null;
	this._editorCont = null;
	this._editorBuilded = false;

	this._layoutSideObj = null;
	this._layoutBottomObj = null;
	this._layoutCont = null;
}

CCommonSettingsPane.prototype = {
	show: function()
	{
		this.hasChanges = false;
		this._mainForm.className = (window.UseDb || window.UseLdapSettings) ? '' : 'wm_hide';
		this._shown = true;
		if (this._settings == null) {
			GetHandler(TYPE_USER_SETTINGS, { }, [], '');
		} else {
			this.fill();
		}
	},
	
	hide: function()
	{
		if (this.hasChanges) {
			Dialog.confirm(
				Lang.ConfirmSaveSettings,
				(function (obj) {
					return function () {
						obj.saveChanges();
					};
				})(this)
			);
		}
		this._mainForm.className = 'wm_hide';
		this.hasChanges = false;
		this._shown = false;
	},
	
	SetSettings: function (settings)
	{
		this._settings = settings;
		this.fill();
	},
	
	GetNewSettings: function ()
	{
		this._settings = this._newSettings;
		this.fill();
		return this._settings;
	},
	
	fill: function ()
	{
		if (this._shown) {
			var i, opt;
			var settings = this._settings;
			this.hasChanges = false;
			if (settings.msgsPerPage != null) {
				this._messPerPageObj.value = settings.msgsPerPage;
				this._messPerPageCont.className = '';
			}
			else {
				this._messPerPageCont.className = 'wm_hide';
			}
			
			if (WebMail.Settings.allowContacts && settings.contactsPerPage != null) {
				this._contactsPerPageObj.value = settings.contactsPerPage;
				this._contactsPerPageCont.className = '';
			}
			else {
				this._contactsPerPageObj.value = 20;
				this._contactsPerPageCont.className = 'wm_hide';
			}
			if (settings.autoCheckMailInterval != null) {
				if (!this._autoCheckMailBuilded) {
				    opt = CreateChild(this._autoCheckMailObj, 'option', [['value', 0]]);
				    opt.innerHTML = Lang.AutoCheckMailIntervalDisableName;
					WebMail.langChanger.register('innerHTML', opt, 'AutoCheckMailIntervalDisableName', '');

					opt = CreateChild(this._autoCheckMailObj, 'option', [['value', 1]]);
				    opt.innerHTML = Lang.AutoCheckMailInterval1Minute;
					WebMail.langChanger.register('innerHTML', opt, 'AutoCheckMailInterval1Minute', '');

					opt = CreateChild(this._autoCheckMailObj, 'option', [['value', 3]]);
				    opt.innerHTML = Lang.AutoCheckMailInterval3Minutes;
					WebMail.langChanger.register('innerHTML', opt, 'AutoCheckMailInterval3Minutes', '');

					opt = CreateChild(this._autoCheckMailObj, 'option', [['value', 5]]);
				    opt.innerHTML = Lang.AutoCheckMailInterval5Minutes;
					WebMail.langChanger.register('innerHTML', opt, 'AutoCheckMailInterval5Minutes', '');

					opt = CreateChild(this._autoCheckMailObj, 'option', [['value', 10]]);
				    opt.innerHTML = '10 ' + Lang.AutoCheckMailIntervalMinutes;
					WebMail.langChanger.register('innerHTML', opt, 'AutoCheckMailIntervalMinutes', '', '10 ');
					
					opt = CreateChild(this._autoCheckMailObj, 'option', [['value', 15]]);
				    opt.innerHTML = '15 ' + Lang.AutoCheckMailIntervalMinutes;
					WebMail.langChanger.register('innerHTML', opt, 'AutoCheckMailIntervalMinutes', '', '15 ');

					opt = CreateChild(this._autoCheckMailObj, 'option', [['value', 20]]);
				    opt.innerHTML = '20 ' + Lang.AutoCheckMailIntervalMinutes;
					WebMail.langChanger.register('innerHTML', opt, 'AutoCheckMailIntervalMinutes', '', '20 ');

					opt = CreateChild(this._autoCheckMailObj, 'option', [['value', 30]]);
				    opt.innerHTML = '30 ' + Lang.AutoCheckMailIntervalMinutes;
					WebMail.langChanger.register('innerHTML', opt, 'AutoCheckMailIntervalMinutes', '', '30 ');

                    this._autoCheckMailBuilded = true;
				}

				this._autoCheckMailObj.value = settings.autoCheckMailInterval;
				this._autoCheckMailCont.className = '';
			}
			else {
				this._messPerPageCont.className = 'wm_hide';
			}
			if (settings.defEditor != null) {
				if (!this._editorBuilded) {
					opt = CreateChild(this._editorObj, 'option', [['value', 0]]);
					opt.innerHTML = Lang.DefEditorPlainText;
						WebMail.langChanger.register('innerHTML', opt, 'DefEditorPlainText', '');

					opt = CreateChild(this._editorObj, 'option', [['value', 1]]);
					opt.innerHTML = Lang.DefEditorRichText;
						WebMail.langChanger.register('innerHTML', opt, 'DefEditorRichText', '');

					this._editorObj.value = settings.defEditor;
					this._editorCont.className = '';

					this._editorBuilded = true;
				}
			}
			else {
				this._editorCont.className = 'wm_hide';
			}
			//
			if (settings.timeOffset != null) {
			    if (!this._timeOffsetBuilded) {
				    for (i = 0; i < TimeOffsets.length; i++) {
					    opt = CreateChild(this._timeOffsetObj, 'option', [['value', TimeOffsets[i].value]]);
					    opt.innerHTML = TimeOffsets[i].name;
					    if (TimeOffsets[i].value == '0') {
					        WebMail.langChanger.register('innerHTML', opt, 'TimeDefault', '');
					    }
				    }
                    this._timeOffsetBuilded = true;
				}
				this._timeOffsetObj.value = settings.timeOffset;
				this._timeOffsetCont.className = '';
			}
			else {
				this._timeOffsetCont.className = 'wm_hide';
			}
			if (settings.timeFormat!= null) {
			    if (settings.timeFormat == 0) {
				    this._12timeFormatObj.checked = false;
				    this._24timeFormatObj.checked = true;
				}
				else {
				    this._12timeFormatObj.checked = true;
				    this._24timeFormatObj.checked = false;
				}
				this._timeFormatCont.className = '';
				if (setcache != null) setcache['timeformat'] = settings.timeFormat;
			}
			else {
				this._timeFormatCont.className = 'wm_hide';
			}
			if (settings.layoutSide != null) {
				this._layoutSideObj.checked = settings.layoutSide;
				this._layoutBottomObj.checked = !settings.layoutSide;
				this._layoutCont.className = '';
			}
			else {
				this._layoutCont.className = 'wm_hide';
			}
			var skins = settings.skins;
			if (settings.defSkin != null) {
			    if (!this._skinBuilded) {
				    for (i = 0; i < skins.length; i++) {
					    opt = CreateChild(this._skinObj, 'option', [['value', skins[i]]]);
					    opt.innerHTML = skins[i];
				    }
                    this._skinBuilded = true;
				}
				this._skinObj.value = settings.defSkin;
				this._skinCont.className = '';
			}
			else {
				this._skinCont.className = 'wm_hide';
			}
			var langs = settings.langs;
			if (settings.defLang != null) {
			    if (!this._languageBuilded) {
                    var langName;
				    for (i = 0; i < langs.length; i++) {
						if (typeof(langs[i]) === 'function') continue;
                        langName = Lang['Language' + langs[i].replace(/\-/g, '')];
					    opt = CreateChild(this._languageObj, 'option', [['value', langs[i]]]);
					    opt.innerHTML = (typeof(langName) == 'undefined') ? langs[i] : langName;
				    }
                    this._languageBuilded = true;
				}
				this._languageObj.value = settings.defLang;
				this._languageCont.className = '';
			}
			else {
				this._languageCont.className = 'wm_hide';
			}
			
			var dateFormats = settings.dateFormats;
			if (settings.defDateFormat != null) {
			    if (!this._dateFormatBuilded) {
				    for (i = 0; i < dateFormats.length; i++) {
						if (typeof(dateFormats[i]) === 'function') continue;
					    opt = CreateChild(this._dateFormatObj, 'option', [['value', dateFormats[i]]]);
					    opt.innerHTML = dateFormats[i];
				    }
                    this._dateFormatBuilded = true;
				}
				this._dateFormatObj.value = settings.defDateFormat;
				this._dateFormatCont.className = '';
			}
			else {
				this._dateFormatCont.className = 'wm_hide';
			}

			if (this._oParent) {
				this._oParent.resizeBody();
			}
		}
	},//fill
	
	_setInputKeyPress: function (inp)
	{
	    var obj = this;
		inp.onkeypress = function (ev) { if (isEnter(ev)) obj.saveChanges(); };
	},
	
	saveChanges: function ()
	{
		var messPerPageValue = Trim(this._messPerPageObj.value);
		if (Validator.isEmpty(messPerPageValue) || !Validator.isPositiveNumber(messPerPageValue)) {
			Dialog.alert(Lang.WarningMessagesPerPage);
			return;
		}
		var contPerPageValue = Trim(this._contactsPerPageObj.value);
		if (Validator.isEmpty(contPerPageValue) || !Validator.isPositiveNumber(contPerPageValue)) {
			Dialog.alert(Lang.WarningContactsPerPage);
			return;
		}
		var autoCheckMailInterval = Trim(this._autoCheckMailObj.value);

		var settings = this._settings;
		var newSettings = new CSettings();
		if (settings.msgsPerPage != null) {
			newSettings.msgsPerPage = messPerPageValue - 0;
		}
		if (WebMail.Settings.allowContacts && settings.contactsPerPage != null) {
			newSettings.contactsPerPage = contPerPageValue - 0;
		}
		if (settings.autoCheckMailInterval != null) {
			newSettings.autoCheckMailInterval = autoCheckMailInterval - 0;
		}
		if (settings.defEditor != null) {
			newSettings.defEditor = this._editorObj.value - 0;
		}
		if (settings.timeOffset != null) {
			newSettings.timeOffset = this._timeOffsetObj.value - 0;
		}
		if (settings.timeFormat != null) {
			newSettings.timeFormat = this._12timeFormatObj.checked ? 1 : 0;
		}
		if (settings.layoutSide != null) {
			newSettings.layoutSide = this._layoutSideObj.checked;
		}
		if (settings.defSkin != null) {
			newSettings.skins = settings.skins;
			newSettings.defSkin = this._skinObj.value;
		}
		if (settings.defLang != null) {
			newSettings.langs = settings.langs;
			newSettings.defLang = this._languageObj.value;
		}
		if (settings.defDateFormat != null) {
			newSettings.dateFormats = settings.dateFormats;
			newSettings.defDateFormat = this._dateFormatObj.value;
		}

		var xml = newSettings.getInXml();
		RequestHandler('update', 'settings', xml);
		this._newSettings = newSettings;
		this.hasChanges = false;
	},//saveChanges

	_buildLayout: function (tbl, rowIndex)
	{
		var tr = tbl.insertRow(rowIndex);
		tr.className = 'wm_hide';
		var td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = Lang.Layout;
		WebMail.langChanger.register('innerHTML', td, 'Layout', '');
		td = tr.insertCell(1);
		td.colSpan = 2;

		var inp = CreateChild(td, 'input', [['id', 'layout_1'], ['class', 'wm_checkbox'], ['type', 'radio'], ['name', 'layout']]);
		var lbl = CreateChild(td, 'label', [['for', 'layout_1']]);
		CreateChild(lbl, 'span', [['class', 'wm_settings_layout_icon_side']]);
		this._layoutSideObj = inp;
		var obj = this;
		this._layoutSideObj.onchange = function () { obj.hasChanges = true; };

		inp = CreateChild(td, 'input', [['id', 'layout_0'], ['class', 'wm_checkbox'], ['type', 'radio'], ['name', 'layout']]);
		lbl = CreateChild(td, 'label', [['for', 'layout_0']]);
		CreateChild(lbl, 'span', [['class', 'wm_settings_layout_icon_bottom']]);
		this._layoutBottomObj = inp;
		this._layoutBottomObj.onchange = function () { obj.hasChanges = true; };
		this._layoutCont = tr;
	},

	build: function(container)
	{
		var obj = this;
		this._mainForm = CreateChild(container, 'form');
		this._mainForm.onsubmit = function () { return false; };
		this._mainForm.className = 'wm_hide';
		var tbl = CreateChild(this._mainForm, 'table');
		tbl.className = 'wm_settings_common';

		var rowIndex = 0;
		
		var tr = tbl.insertRow(rowIndex++);
		tr.className = 'wm_hide';
		var td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = Lang.Skin;
		WebMail.langChanger.register('innerHTML', td, 'Skin', '');
		td = tr.insertCell(1);
		td.colSpan = 2;
		var sel = CreateChild(td, 'select');
		this._skinObj = sel;
		this._skinObj.onchange = function () { obj.hasChanges = true; };
		this._skinCont = tr;
		
		this._buildLayout(tbl, rowIndex++);
		
		tr = tbl.insertRow(rowIndex++);
		tr.className = 'wm_hide';
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = Lang.DefLanguage;
		WebMail.langChanger.register('innerHTML', td, 'DefLanguage', '');
		td = tr.insertCell(1);
		td.colSpan = 2;
		sel = CreateChild(td, 'select');
		this._languageObj = sel;
		this._languageObj.onchange = function () { obj.hasChanges = true; };
		this._languageCont = tr;

		tr = tbl.insertRow(rowIndex++);
		tr.className = 'wm_hide';
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = Lang.MsgsPerPage;
		WebMail.langChanger.register('innerHTML', td, 'MsgsPerPage', '');
		td = tr.insertCell(1);
		td.colSpan = 2;
		var inp = CreateChild(td, 'input', [['class', 'wm_input'], ['type', 'text'], ['size', '2'], ['maxlength', '2']]);
		this._setInputKeyPress(inp);
		this._messPerPageObj = inp;
		this._messPerPageObj.onchange = function () { obj.hasChanges = true; };
		this._messPerPageCont = tr;
		
		tr = tbl.insertRow(rowIndex++);
		tr.className = 'wm_hide';
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = Lang.ContactsPerPage;
		WebMail.langChanger.register('innerHTML', td, 'ContactsPerPage', '');
		td = tr.insertCell(1);
		td.colSpan = 2;
		inp = CreateChild(td, 'input', [['class', 'wm_input'], ['type', 'text'], ['size', '2'], ['maxlength', '2']]);
		this._setInputKeyPress(inp);
		this._contactsPerPageObj = inp;
		this._contactsPerPageObj.onchange = function () { obj.hasChanges = true; };
		this._contactsPerPageCont = tr;

		tr = tbl.insertRow(rowIndex++);
		tr.className = 'wm_hide';
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = Lang.AutoCheckMailIntervalLabel;
		WebMail.langChanger.register('innerHTML', td, 'AutoCheckMailIntervalLabel', '');
		td = tr.insertCell(1);
		td.colSpan = 2;
		sel = CreateChild(td, 'select');
		this._autoCheckMailObj = sel;
		this._autoCheckMailObj.onchange = function () { obj.hasChanges = true; };
		this._autoCheckMailCont = tr;

		tr = tbl.insertRow(rowIndex++);
		tr.className = 'wm_hide';
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = Lang.DefEditor;
		WebMail.langChanger.register('innerHTML', td, 'DefEditor', '');
		td = tr.insertCell(1);
		td.colSpan = 2;
		sel = CreateChild(td, 'select');
		this._editorObj = sel;
		this._editorObj.onchange = function () { obj.hasChanges = true; };
		this._editorCont = tr;
		
		tr = tbl.insertRow(rowIndex++);
		tr.className = 'wm_hide';
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = Lang.DefTimeOffset;
		WebMail.langChanger.register('innerHTML', td, 'DefTimeOffset', '');
		td = tr.insertCell(1);
		td.colSpan = 2;
		sel = CreateChild(td, 'select');
		this._timeOffsetObj = sel;
		this._timeOffsetObj.onchange = function () { obj.hasChanges = true; };
		this._timeOffsetCont = tr;

		tr = tbl.insertRow(rowIndex++);
		tr.className = 'wm_hide';
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = Lang.DefTimeFormat;
		WebMail.langChanger.register('innerHTML', td, 'DefTimeFormat', '');
		td = tr.insertCell(1);
		td.colSpan = 2;
		inp = CreateChild(td, 'input', [['id', 'def_TimeFormat_0'], ['class', 'wm_checkbox'], ['type', 'radio'], ['name', 'def_TimeFormat']]);
		var lbl = CreateChild(td, 'label', [['for', 'def_TimeFormat_0']]);
		lbl.innerHTML = '1PM&nbsp;';
		this._12timeFormatObj = inp;
		this._12timeFormatObj.onchange = function () { obj.hasChanges = true; };
		inp = CreateChild(td, 'input', [['id', 'def_TimeFormat_1'], ['class', 'wm_checkbox'], ['type', 'radio'], ['name', 'def_TimeFormat']]);
		lbl = CreateChild(td, 'label', [['for', 'def_TimeFormat_1']]);
		lbl.innerHTML = '13:00';
		this._24timeFormatObj = inp;
		this._24timeFormatObj.onchange = function () { obj.hasChanges = true; };
		this._timeFormatCont = tr;

		tr = tbl.insertRow(rowIndex++);
		tr.className = 'wm_hide';
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = Lang.SettingsDateFormat;
		WebMail.langChanger.register('innerHTML', td, 'SettingsDateFormat', '');
		td = tr.insertCell(1);
		td.colSpan = 2;
		sel = CreateChild(td, 'select');
		this._dateFormatObj = sel;
		this._dateFormatObj.onchange = function () { obj.hasChanges = true; };
		this._dateFormatCont = tr;
		
		var eButtonsPane = CreateChild(this._mainForm, 'div', [['class', 'wm_settings_buttons']]);
		inp = CreateChild(eButtonsPane, 'input', [['class', 'wm_button'], ['type', 'button'], ['value', Lang.Save]]);
		WebMail.langChanger.register('value', inp, 'Save', '');
		inp.onclick = function () {
			obj.saveChanges();
		};
	}//build
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}