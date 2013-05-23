/*
 * Functions:
 *  fnum(num, digits)
 *  getSettingsParametr()
 *  String.prototype.parseJSON(filter)
 * Classes:
 *  CCalendarSettingsPane(oParent)
 */

var setcache = null;

function fnum(num, digits)
{
	num = String(num);
	while (num.length < digits) {
		num = '0' + num;
	}
	return(num);
}

function getSettingsParametr()
{
	var
		i, setval, setparams,
		scache = {},
		res = '{}',
		netLoader = new CNetLoader(),
		req = netLoader.getTransport(),
		sNoCache = '&nocache=' + Math.random(),
		sToken = '&token=' + GetCsrfToken(),
		url = CalendarProcessingUrl + '?action=get_settings' + sNoCache + sToken
	;

	WebMail.showInfo(Lang.InfoLoading);
	if (req != null) {
		req.open('GET', url, false);
		req.send(null);
		res = req.responseText;
	}
	WebMail.hideInfo();
	setparams = res.parseJSON();
	if (setparams == false) return null;
	for (i in setparams) {
		setval = setparams[i];
		if (typeof(setval) == 'function') continue;
		scache[i] = setval;
	}
	return scache;
}

/*
 * Based on json.js (2007-07-03)
 * Modified by AfterLogic Corporation
 */
String.prototype.parseJSON = function (filter) {
    var j;

    function walk(k, v) {
        var i;
        if (v && typeof v === 'object') {
            for (i in v) {
                if (v.hasOwnProperty(i)) {
                    v[i] = walk(i, v[i]);
                }
            }
        }
        return filter(k, v);
    }

    if (/^[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]+$/.test(this
            .replace(/\\./g, '@')
            .replace(/"[^"\\\n\r]*"/g, ''))) {

        j = eval('(' + this + ')');
        if (typeof filter === 'function') {
            j = walk('', j);
        }
        if (j['error'] == 'true') {
            WebMail.showError(j['description']);
            return false;
        }
        return j;
    }

    WebMail.showError(Lang.ErrorGeneral);
    return false;
};


function CCalendarSettingsPane(oParent)
{
	this._oParent = oParent;

	this._mainForm = null;
	this._eButtonsPane = null;

	this.hasChanges = false;

	this._showWeekends = null;
	this._showWeekendsCont = null;
	this._WorkdayStarts = null;
	this._WorkdayEnds = null;
	this._WorkdayCont = null;
	this._showWorkday = null;
	this._showWorkdayCont = null;
	this._tabCont = null;
	this._tab = Array();
	this._Country = null;
	this._CountryCont = null;
	this._autoAddInvitation = null;
	this._autoAddInvitationCont = null;
	this._CalncelBtn = null;
	this._SaveBtn = null;
	this._displayName = null;
	this._displayNameCont = null;
	this._weekStartsOn = null;
	this._weekStartsOnCont = null;
	this._weekStartsOnBuilded = false;

	this._tabs = [
		{ nameField: 'TabDay', value: '1',  id: 'set_tab_0'},
		{ nameField: 'TabWeek', value: '2', id: 'set_tab_1'},
		{ nameField: 'TabMonth', value: '3', id: 'set_tab_2'}
		];

	var d = new Date();

	var MonField = 'ShortMonthJanuary'; //month
	switch (d.getMonth()+1) {
		case 1: MonField = 'ShortMonthJanuary'; break;
		case 2: MonField = 'ShortMonthFebruary'; break;
		case 3: MonField = 'ShortMonthMarch'; break;
		case 4: MonField = 'ShortMonthApril'; break;
		case 5: MonField = 'ShortMonthMay'; break;
		case 6: MonField = 'ShortMonthJune'; break;
		case 7: MonField = 'ShortMonthJuly'; break;
		case 8: MonField = 'ShortMonthAugust'; break;
		case 9: MonField = 'ShortMonthSeptember'; break;
		case 10: MonField = 'ShortMonthOctober'; break;
		case 11: MonField = 'ShortMonthNovember'; break;
		case 12: MonField = 'ShortMonthDecember'; break;
	}

	this._dayFormat = [
		{name: fnum((d.getMonth()+1),2)+"/"+fnum(d.getDate(),2)+"/"+d.getFullYear(), value: '1', id: 'date_0'},
		{name: fnum(d.getDate(),2)+"/"+fnum((d.getMonth()+1),2)+"/"+d.getFullYear(), value: '2', id: 'date_1'},
		{name: d.getFullYear()+"-"+fnum((d.getMonth()+1),2)+"-"+fnum(d.getDate(),2), value: '3', id: 'date_2'},
		{name: null, nameField: MonField, nameBefore: '', nameAfter: ' ' + d.getDate() + ', ' + d.getFullYear(), value: '4', id: 'date_3'},
		{name: null, nameField: MonField, nameBefore: d.getDate() + ' ', nameAfter: ' ' + d.getFullYear(), value: '5', id: 'date_4'}
		];

	this._firstWeekDay = [
		{nameField: 'FullDaySaturday', value: '6', id: 'first_week_day_6'},
		{nameField: 'FullDaySunday', value: '0', id: 'first_week_day_0'},
		{nameField: 'FullDayMonday', value: '1', id: 'first_week_day_1'}
		];
}

CCalendarSettingsPane.prototype =
{
	show: function ()
	{
		this._mainForm.className = '';
		this._eButtonsPane.className = 'wm_settings_buttons';
		if (setcache == null) setcache = getSettingsParametr();
		this.fill();
	},

	hide: function ()
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
		this._eButtonsPane.className = 'wm_hide';
		this.hasChanges = false;
	},

	SetTimeFormat: function(WorkdayContainer, WorkdayValue, timeFormat)
	{
		for (var i = WorkdayContainer.options.length - 1; i >= 0; i--) {
			WorkdayContainer.options[i] = null;
		}
		for (i = 0; i < 24; i++) {
			var opt = document.createElement("option");
			opt.value = i;
			WorkdayContainer.appendChild(opt);
			if (timeFormat == 1) {
			    if (i < 12) {
			        opt.text = ((i == 0) ? 12 : i) + " AM";
			    }
			    else {
			        opt.text = ((i == 12) ? i : (i - 12)) + " PM";
			    }
			}
			else {
				opt.text = (i < 10) ? ("0" + i + ":00") : (i + ":00");
			}
		}
		setTimeout( function(){WorkdayContainer.options[WorkdayValue].selected=true;}, 1);
	},

	fill: function ()
	{
		if (setcache == null) return;
		this.hasChanges = false;

		if (setcache['showweekends'] != null) {
			if (setcache['showweekends'] == 1) this._showWeekends.checked = true;
			this._showWeekendsCont.className = '';
		}
		else this._showWeekendsCont.className = 'wm_hide';

		if (setcache['workdaystarts'] != null) {
			this.SetTimeFormat(this._WorkdayStarts, setcache['workdaystarts'], setcache['timeformat']);
			if (setcache['workdayends'] != null) {
				this.SetTimeFormat(this._WorkdayEnds, setcache['workdayends'], setcache['timeformat']);
			}
			this._WorkdayCont.className = '';
		}
		else this._WorkdayCont.className = 'wm_hide';

		if (setcache['showworkday'] != null) {
			if (setcache['showworkday'] == 1) this._showWorkday.checked = true;
			this._showWorkdayCont.className = '';
		}
		else this._showWorkdayCont.className = 'wm_hide';

		var i;
		if (setcache['weekstartson'] != null) {
			var sel = this._weekStartsOn;
			if (!this._weekStartsOnBuilded) {
				for(i = 0; i < this._firstWeekDay.length; i++) {
					var opt = CreateChild(sel, 'option', [['value', this._firstWeekDay[i].value]]);
					opt.innerHTML = Lang[this._firstWeekDay[i].nameField];
					WebMail.langChanger.register('innerHTML', opt, this._firstWeekDay[i].nameField, '', ' ');
				}
				this._weekStartsOnBuilded = true;
			}
			this._weekStartsOn.value = setcache['weekstartson'];
			this._weekStartsOnCont.className = '';
		}
		else this._weekStartsOnCont.className = 'wm_hide';

		if (setcache['defaulttab'] != null) {
			for (i = 0; i < this._tab.length; i++) {
				var tabObj = this._tab[i];
				if (setcache['defaulttab'] == tabObj.value) {
					tabObj.obj.checked = true;
				}
			}
			this._tabCont.className = "";
		}
		else this._tabCont.className = "wm_hide";

		/*if (setcache['autoaddinvitation'] != null) {
			if (setcache['autoaddinvitation'] == 1) this._autoAddInvitation.checked = true;
			this._autoAddInvitationCont.className = '';
		}
		else */this._autoAddInvitationCont.className = 'wm_hide';

		if (setcache['displayname'] != null) {
			this._displayName.value = setcache['displayname'];
			this._displayNameCont.className = '';
		}
		else this._displayNameCont.className = 'wm_hide';

		if (this._oParent) {
			this._oParent.resizeBody();
		}
	},//fill

	_setInputKeyPress: function (inp)
	{
	    var obj = this;
		inp.onkeypress = function (ev) { if (isEnter(ev)) obj.saveChanges(); };
	},

	saveChanges: function()
	{
		var _showWeekends = (this._showWeekends.checked == true) ? 1 : 0;

		var _showWorkday = (this._showWorkday.checked == true) ? 1 : 0;

		var _AllTimeZones = (this._AllTimeZones.checked == true) ? 1 : 0;

		var _autoAddInvitation = (this._autoAddInvitation.checked == true) ? 1 : 0;

		var _defTab = 1;
		for (var i = 0; i < this._tab.length; i++) {
			if (this._tab[i].obj.checked) {
				_defTab = this._tab[i].value;
			}
		}

		var _defTimeZone = 0;
		if (this.defTimeZone != null) {
			_defTimeZone = this.defTimeZone.value;
		}
		var _displayName = encodeURIComponent(Trim(this._displayName.value));

		var netLoader = new CNetLoader();
		var req = netLoader.getTransport();
		var sToken =  '&token=' + GetCsrfToken();
		var url = CalendarProcessingUrl +
				'?action=update_settings' +
				'&timeFormat=' + setcache['timeformat'] +
				'&dateFormat=' + setcache['dateformat'] +
				'&showWeekends=' + _showWeekends +
				'&workdayStarts=' + this._WorkdayStarts.value +
				'&WorkdayEnds=' + this._WorkdayEnds.value +
				'&showWorkday=' + _showWorkday +
				'&weekstartson=' + this._weekStartsOn.value +
				'&tab=' + _defTab +
				'&country=' + this._Country.value +
				'&TimeZone=' + _defTimeZone +
				'&AllTimeZones=' + _AllTimeZones +
				'&autoAddInvitation=' + _autoAddInvitation +
				'&displayName=' + _displayName +
				'&nocache=' + Math.random() + sToken;

		var res = '{}';
		if (req != null) {
			WebMail.showInfo(Lang.InfoSaving);
			req.open("GET", url, false);
			req.send(null);
			res = req.responseText;
		}

		var settingsFromDb;
		settingsFromDb = res.parseJSON();
		if (settingsFromDb == false) {
			WebMail.hideInfo();
			return;
		}
		for (i in settingsFromDb) {
			setval = settingsFromDb[i];
			if (typeof(setval) == 'function') continue;
			setcache[i] = settingsFromDb[i];
		}
		WebMail.hideInfo();
		WebMail.showReport(Lang.ReportSettingsUpdated);
		if (this.hasChanges) {
			var screen = WebMail.Screens[SCREEN_CALENDAR];
			if (screen) screen.needReload();
			this.hasChanges = false;
		}
	},

	build: function(container)
	{
		var inp, lbl;
		var obj = this;
		this._mainForm = CreateChild(container, 'form');
		this._mainForm.className = 'wm_hide';
		this._mainForm.onsubmit = function () { return false; };
		var tbl_ = CreateChild(this._mainForm, 'table');
		tbl_.className = 'wm_settings_common';

		var rowIndex = 0;
		var tr_ = tbl_.insertRow(rowIndex++);
		tr_.className = 'wm_hide';
		var td_ = tr_.insertCell(0);
		td_.className = 'wm_settings_title';
		td_.innerHTML = Lang.SettingsDisplayName;
		WebMail.langChanger.register('innerHTML', td_, 'SettingsDisplayName', '');
		td_ = tr_.insertCell(1);
		inp = CreateChild(td_, 'input', [['type', 'text'], ['name', 'DisplayName'], ['id', 'DisplayName'], ['value', ''], ['maxlength', '255']]);
		this._setInputKeyPress(inp);
		inp.onchange = function () { obj.hasChanges = true; };
		this._displayName = inp;
		this._displayNameCont = tr_;
		this._displayName.onblur = function() { obj._displayName.value = Trim(obj._displayName.value); };

		tr_ = tbl_.insertRow(rowIndex++);
		tr_.className = 'wm_hide';
		td_ = tr_.insertCell(0);
		td_ = tr_.insertCell(1);
		inp = CreateChild(td_, 'input', [['class', 'wm_checkbox'], ['type', 'checkbox'], ['name', 'showWeekends'], ['id', 'showWeekends'], ['value', '1']]);
		this._showWeekends = inp;
		this._showWeekends.onchange = function () { obj.hasChanges = true; };
		lbl = CreateChild(td_, 'label', [['for', 'showWeekends']]);
		lbl.innerHTML = Lang.SettingsShowWeekends;
		WebMail.langChanger.register('innerHTML', lbl, 'SettingsShowWeekends', '');
		this._showWeekendsCont = tr_;

		tr_ = tbl_.insertRow(rowIndex++);
		tr_.className = 'wm_hide';
		td_ = tr_.insertCell(0);
		td_.className = 'wm_settings_title';
		td_.innerHTML = Lang.SettingsWorkdayStarts;
		WebMail.langChanger.register('innerHTML', td_, 'SettingsWorkdayStarts', '');
		td_ = tr_.insertCell(1);
		sel1 = CreateChild(td_, 'select');
		sel1.style.width = "100px";
		this._WorkdayStarts = sel1;
		this._WorkdayStarts.onchange = function () { obj.hasChanges = true; };
		var span = CreateChild(td_, 'span');
		span.innerHTML = '&nbsp;&nbsp;' + Lang.SettingsWorkdayEnds + ' ';
		WebMail.langChanger.register('innerHTML', span, 'SettingsWorkdayEnds', ' ', '&nbsp;&nbsp;');
		sel2 = CreateChild(td_, 'select');
		sel2.style.width = "100px";
		this._WorkdayEnds = sel2;
		this._WorkdayEnds.onchange = function () { obj.hasChanges = true; };
		this._WorkdayCont = tr_;

		tr_ = tbl_.insertRow(rowIndex++);
		tr_.className = 'wm_hide';
		td_ = tr_.insertCell(0);
		td_ = tr_.insertCell(1);
		inp = CreateChild(td_, 'input', [['class', 'wm_checkbox'], ['type', 'checkbox'], ['name', 'showWorkday'], ['id', 'showWorkday'], ['value', '1']]);
		lbl = CreateChild(td_, 'label', [['for', 'showWorkday']]);
		lbl.innerHTML = Lang.SettingsShowWorkday;
		WebMail.langChanger.register('innerHTML', lbl, 'SettingsShowWorkday', '');
		inp.onchange = function () { obj.hasChanges = true; };
		this._showWorkday = inp;
		this._showWorkdayCont = tr_;

		tr_ = tbl_.insertRow(rowIndex++);
		tr_.className = 'wm_hide';
		td_ = tr_.insertCell(0);
		td_.className = 'wm_settings_title';
		td_.innerHTML = Lang.SettingsWeekStartsOn;
		WebMail.langChanger.register('innerHTML', td_, 'SettingsWeekStartsOn', '');
		td_ = tr_.insertCell(1);
		sel = CreateChild(td_, 'select');
		this._weekStartsOn = sel;
		this._weekStartsOn.onchange = function () { obj.hasChanges = true; };
		this._weekStartsOnCont = tr_;

		tr_ = tbl_.insertRow(rowIndex++);
		tr_.className = 'wm_hide';
		td_ = tr_.insertCell(0);
		td_.className = 'wm_settings_title';
		td_.innerHTML = Lang.SettingsDefaultTab;
		WebMail.langChanger.register('innerHTML', td_, 'SettingsDefaultTab', '');
		td_ = tr_.insertCell(1);
		this._tab = Array();
		/* rtl */
		for (var i=0; i<this._tabs.length; i++) {
			inp = CreateChild(td_, 'input', [['class', 'wm_checkbox'], ['type', 'radio'], ['name', 'defTab'], ['id', this._tabs[i].id], ['value', this._tabs[i].value]]);
			inp.onchange = function () { obj.hasChanges = true; };
			lbl = CreateChild(td_, 'label', [['for', this._tabs[i].id]]);
			lbl.innerHTML = Lang[this._tabs[i].nameField];
			WebMail.langChanger.register('innerHTML', lbl, this._tabs[i].nameField, '', '');
			this._tab.push({obj: inp, value: this._tabs[i].value});
		}
		this._tabCont = tr_;

		tr_ = tbl_.insertRow(rowIndex++);
		tr_.className = 'wm_hide';
		td_ = tr_.insertCell(0);
		td_.className = 'wm_settings_title';
		td_.innerHTML = Lang.SettingsCountry;
		WebMail.langChanger.register('innerHTML', td_, 'SettingsCountry', '');
		td_ = tr_.insertCell(1);
		sel = CreateChild(td_, 'select');
		sel.style.width = "300px";
		this._Country = sel;
		this._Country.onchange = function () {
			obj.hasChanges = true;
			/*reload timezones when change country*/
			var allZones = (obj._AllTimeZones.checked)?1:0;
			obj.LoadTimeZones(obj._UserTimeZoneTd, allZones);
		};
		this._CountryCont = tr_;

		tr_ = tbl_.insertRow(rowIndex++);
		tr_.className = 'wm_hide';
		td_ = tr_.insertCell(0);
		td_.className = 'wm_settings_title';
		td_.innerHTML = Lang.SettingsTimeZone;
		WebMail.langChanger.register('innerHTML', td_, 'SettingsTimeZone', '');
		td_ = tr_.insertCell(1);
		this._UserTimeZoneTd = td_;
		this._UserTimeZoneCont = tr_;

		tr_ = tbl_.insertRow(rowIndex++);
		tr_.className = 'wm_hide';
		td_ = tr_.insertCell(0);
		td_ = tr_.insertCell(1);
		inp = CreateChild(td_, 'input', [['class', 'wm_checkbox'], ['type', 'checkbox'], ['name', '_AllTimeZones'], ['id', 'AllTimeZones'], ['value', '0']]);
		lbl = CreateChild(td_, 'label', [['for', 'AllTimeZones']]);
		lbl.innerHTML = Lang.SettingsAllTimeZones;
		WebMail.langChanger.register('innerHTML', lbl, 'SettingsAllTimeZones', '');
		inp.onchange = function () { obj.hasChanges = true; };
		inp.onclick = function() {
			var allZones = (this.checked)?1:0;
			obj.LoadTimeZones(obj._UserTimeZoneTd, allZones);
		};
		this._AllTimeZones = inp;
		this._AllTimeZonesCont = tr_;

		tr_ = tbl_.insertRow(rowIndex++);
		tr_.className = 'wm_hide';
		td_ = tr_.insertCell(0);
		td_ = tr_.insertCell(1);
		inp = CreateChild(td_, 'input', [['class', 'wm_checkbox'], ['type', 'checkbox'], ['name', 'autoAddInvitation'], ['id', 'autoAddInvitation'], ['value', '0']]);
		lbl = CreateChild(td_, 'label', [['for', 'autoAddInvitation']]);
		lbl.innerHTML = Lang.SettingsAutoAddInvitation;
		WebMail.langChanger.register('innerHTML', lbl, 'SettingsAutoAddInvitation', '');
		inp.onchange = function () { obj.hasChanges = true; };
		this._autoAddInvitation = inp;
		this._autoAddInvitationCont = tr_;

		tr_ = tbl_.insertRow(rowIndex++);
		td_ = tr_.insertCell(0);
		td_ = tr_.insertCell(1);
		td_.innerHTML = ' ';

		var eButtonsPane = CreateChild(this._mainForm, 'div', [['class', 'wm_hide']]);
		inp = CreateChild(eButtonsPane, 'input', [['class', 'wm_button'], ['type', 'button'], ['value', Lang.ButtonSave]]);
		WebMail.langChanger.register('value', inp, 'ButtonSave', '');
		inp.onclick = function () {
			if (parseInt(obj._WorkdayStarts.value) >= parseInt(obj._WorkdayEnds.value)) {
				Dialog.alert(Lang.WarningWorkdayStartsEnds);
			} else {
				obj.saveChanges();
			}
		};
		this._SaveBtn = inp;

		this._eButtonsPane = eButtonsPane;
	}//build
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
