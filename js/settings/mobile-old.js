/*
 * Classes:
 *  CMobileSyncSettingsPane(oParent)
 */

function CMobileSyncSettingsPane(oParent)
{
	this._oParent = oParent;

	this._mainForm = null;

	this._settings = null;
	this._newSettings = null;

	this.hasChanges = false;
	this._shown = false;

	this._loginObj = null;
	this._davUrlObj = null;
	this._principalUrlObj = null;
	this._calDavUrlObj = null;
}

CMobileSyncSettingsPane.prototype = {
	show: function()
	{
		this.hasChanges = false;
		this._mainForm.className = '';
		this._shown = true;
		if (this._settings == null || this._settings.bEmpty) {
			GetHandler(TYPE_MOBILE_SYNC, { }, [], '');
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
        var mobileSync = this._settings;
        this._loginObj.innerHTML = mobileSync.login;
        this._davUrlObj.innerHTML = mobileSync.davUrl;
        this._principalUrlObj.innerHTML = mobileSync.principalUrl;

        this._oParent.resizeBody();
	},

	saveChanges: function ()
	{
		var newSettings = new CMobileSyncData();
        newSettings.copy(this._settings);

		var xml = newSettings.getInXml();
		RequestHandler('update', 'mobile_sync', xml);

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
		td.className = 'wm_settings_title';
		td.innerHTML = Lang.MobileSyncDavServerURL + ':';
		WebMail.langChanger.register('innerHTML', td, 'MobileSyncDavServerURL', ':');
		td = tr.insertCell(1);
		td.colSpan = 2;
		this._davUrlObj = td;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		tr.className = 'wm_hide'; // TODO
		td.innerHTML = Lang.MobileSyncPrincipalURL + ':';
		WebMail.langChanger.register('innerHTML', td, 'MobileSyncPrincipalURL', ':');
		td = tr.insertCell(1);
		td.colSpan = 2;
		this._principalUrlObj = td;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = Lang.MobileSyncLoginTitle + ':';
		WebMail.langChanger.register('innerHTML', td, 'MobileSyncLoginTitle', ':');
		td = tr.insertCell(1);
		td.colSpan = 2;
		this._loginObj = td;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = '';
		td = tr.insertCell(1);
		td.colSpan = 2;
		div = CreateChild(td, 'div', [['class', 'syncTextTitleClass']]);
		div.innerHTML = Lang.MobileSyncHintDesc;
		WebMail.langChanger.register('innerHTML', td, 'MobileSyncHintDesc', '');

		/* ios */
		tr = tbl.insertRow(rowIndex++);
		tr.className = Browser.ios ? '' : 'wm_hide';
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		img = CreateChild(td, 'img', [['src', 'skins/apple-icon.png']]);
		td = tr.insertCell(1);
		td.colSpan = 2;
		alink = CreateChild(td, 'a', [['class', 'wm_apple_delivery_settings'], ['href', 'ios.php?profile']]);
		alink.innerHTML = Lang.MobileGetIOSSettings;
		WebMail.langChanger.register('innerHTML', alink, 'MobileGetIOSSettings', '');
		/* ios */

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