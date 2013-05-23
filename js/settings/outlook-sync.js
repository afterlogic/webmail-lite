/*
 * Classes:
 *  COutlookSyncSettingsPane(oParent)
 */

function COutlookSyncSettingsPane(oParent)
{
	this._oParent = oParent;

	this._mainForm = null;

	this._settings = null;
	this._newSettings = null;

	this.hasChanges = false;
	this._shown = false;
	
	this._loginObj = null;
	this._PasswordObj = null;
	this._davUrlObj = null;

}

COutlookSyncSettingsPane.prototype = {
	show: function()
	{
		this.hasChanges = false;
		this._mainForm.className = '';
		this._shown = true;
		if (this._settings == null || this._settings.bEmpty) {
			GetHandler(TYPE_OUTLOOK_SYNC, { }, [], '');
		}
        else {
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
		this._settings = settings || null;
		this.fill();
	},

	UpdateSettings: function ()
	{
		this._settings = this._newSettings;
		this.fill();
	},

	fill: function ()
	{
		if (!this._shown) return;

        this.hasChanges = false;
        var outlookSync = this._settings;
        this._loginObj.innerHTML = outlookSync.login;
        this._davUrlObj.innerHTML = outlookSync.davUrl;
		this._PasswordObj.innerHTML = Lang.ActiveSyncPasswordValue;

        this._oParent.resizeBody();
	},

	saveChanges: function ()
	{
		var newSettings = new COutlookSyncData();
        newSettings.copy(this._settings);

		var xml = newSettings.getInXml();
		RequestHandler('update', 'outlook_sync', xml);

		this._newSettings = newSettings;
		this.hasChanges = false;
	},

	build: function(container)
	{
		var
			tr, td, div, alink, img,
			rowIndex = 0
		;

		this._mainForm = CreateChild(container, 'form');
		this._mainForm.onsubmit = function () { return false; };
		this._mainForm.className = 'wm_hide';
		var tbl = CreateChild(this._mainForm, 'table');
		tbl.className = 'wm_settings_common';
		
		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.colSpan = 3;
		div = CreateChild(td, 'div', [['class', 'syncTextTitleClass']]);
		div.innerHTML = Lang.OutlookSyncHintDesc;
		WebMail.langChanger.register('innerHTML', td, 'OutlookSyncHintDesc', '');
		
		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = Lang.OutlookSyncServerURL + ':';
		WebMail.langChanger.register('innerHTML', td, 'OutlookSyncServerURL', ':');
		td = tr.insertCell(1);
		td.colSpan = 2;
		this._davUrlObj = td;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = Lang.LANG_Login + ':';
		WebMail.langChanger.register('innerHTML', td, 'LANG_Login', ':');
		td = tr.insertCell(1);
		td.colSpan = 2;
		this._loginObj = td;
		
		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = Lang.LANG_Password + ':';
		WebMail.langChanger.register('innerHTML', td, 'LANG_Password', ':');
		td = tr.insertCell(1);
		td.colSpan = 2;
		this._PasswordObj = td;

		tr = tbl.insertRow(rowIndex++);
		tr.className = '';
		td = tr.insertCell(0);
		td.className = '';
		td.colSpan = 3;
		td.innerHTML = '<br />';
		
	}//build
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}