/*
 * Classes:
 *  CMobileSyncSettingsPane(oParent)
 */

function CMobileSyncSettingsPane(oParent)
{
	this._oParent = oParent;

	this.$mainForm = null;

	this._settings = null;

	this._shown = false;

	this.$davCont = null;
	this.$davSyncServer = null;
	this.$davSyncLogin = null;
	this.$calendarsData = null;
	this.$calendarsHeading = null;
	this.$davSyncPersonalContacts = null;
	this.$davSyncCollectedAddresses = null;
	this.$davSyncGlobalAddressBook = null;

	this.$activeCont = null;
	this.$activeSyncServer = null;
	this.$activeSyncLogin = null;
}

CMobileSyncSettingsPane.prototype = {
	show: function()
	{
		this.$mainForm.show();
		this._shown = true;
		GetHandler(TYPE_MOBILE_SYNC, { }, [], '');
	},

	hide: function()
	{
		this.$mainForm.hide();
		this._shown = false;
	},

	SetSettings: function (settings)
	{
		this._settings = settings || null;
		this.fill();
	},

	fill: function ()
	{
        var mobileSync = this._settings;

		if (!this._shown) return;

		if (mobileSync.davEnable) {
			this.$davSyncServer.html(mobileSync.davUrl);
			this.$davSyncLogin.html(mobileSync.davLogin);
			this.fillCalendars(mobileSync.davCalendars);
			this.$davSyncPersonalContacts.html(mobileSync.davPersonalContacts);
			this.$davSyncCollectedAddresses.html(mobileSync.davCollectedAddresses);
			this.$davSyncGlobalAddressBook.html(mobileSync.davGlobalAddressBook);
			this.$davCont.show();
		}
		else {
			this.$davCont.hide();
		}

		if (mobileSync.activeEnable) {
			this.$activeSyncServer.html(mobileSync.activeServer);
			this.$activeSyncLogin.html(mobileSync.activeLogin);
			this.$activeCont.show();
		}
		else {
			this.$activeCont.hide();
		}


        this._oParent.resizeBody();
	},

	fillCalendars: function (aCalendars)
	{
		var
			iIndex = 0,
			iLen = aCalendars.length,
			oCalendar = null
		;

		if (iLen > 0 && WebMail.Settings.allowCalendar) {
			this.$calendarsData.empty();
			this.$calendarsData.show();
			this.$calendarsHeading.show();

			for (; iIndex < iLen; iIndex++) {
				oCalendar = aCalendars[iIndex];
				this.buildCalendarData(oCalendar);
			}
		}
		else {
			this.$calendarsData.hide();
			this.$calendarsHeading.hide();
		}
	},

	buildHeading: function ($tbl, sLangField, sHeaderTag)
	{
		var
			$tr = $('<tr></tr>').appendTo($tbl),
			$td = $('<td colspan="2"></td>').appendTo($tr),
			$sHeaderTag = sHeaderTag !== undefined ? sHeaderTag : 'h3',
			$heading = $('<'+$sHeaderTag+' class="wm_settings_description_title"></'+$sHeaderTag+'>').appendTo($td)
		;

		WebMail.langChanger.register$({ sType: 'html', $elem: $heading, sField: sLangField });
	},

	buildHint: function ($tbl, sLangField)
	{
		var
			$tr = $('<tr></tr>').appendTo($tbl),
			$td = $('<td colspan="2"></td>').appendTo($tr),
			$hint = $('<div class="syncTextTitleClass"></div>').appendTo($td)
		;

		WebMail.langChanger.register$({ sType: 'html', $elem: $hint, sField: sLangField });
	},

	buildData: function ($tbl, sTitleLangField, bShow, sDataLangField)
	{
		var
			$tr = $('<tr></tr>').appendTo($tbl),
			$titleTd = $('<td class="wm_settings_title" style="width: 20%"></td>').appendTo($tr),
			$dataTd = $('<td></td>').appendTo($tr)
		;

		WebMail.langChanger.register$({ sType: 'html', $elem: $titleTd, sField: sTitleLangField, sEnd: ':' });
		if (sDataLangField !== undefined) {
			WebMail.langChanger.register$({ sType: 'html', $elem: $dataTd, sField: sDataLangField });
		}

		if (!bShow) { $tr.hide(); }

		return $dataTd;
	},

	buildCalendarData: function (oCalendar)
	{
		var
			$tr = $('<tr></tr>').appendTo(this.$calendarsData),
			$titleTd = $('<td class="wm_settings_title" style="width: 20%"></td>').appendTo($tr),
			$dataTd = $('<td></td>').appendTo($tr)
		;

		$titleTd.html(oCalendar.Name + ':');
		$dataTd.html(oCalendar.Url);
	},

	buildIosLine: function ($tbl)
	{
		var
			$tr = $('<tr></tr>').appendTo($tbl),
			$titleTd = $('<td class="wm_settings_title" style="width: 20%"></td>').appendTo($tr),
			$dataTd = $('<td></td>').appendTo($tr),
			$link = $('<a href="ios.php?profile" class="wm_apple_delivery_settings"></a>').appendTo($dataTd)
		;
		$('<img src="skins/apple-icon.png" />').appendTo($titleTd);
		WebMail.langChanger.register$({ sType: 'html', $elem: $link, sField: 'MobileGetIOSSettings' });
	},

	build: function (eContainer)
	{
		var $tbl = null;

		this.$mainForm = $('<form onsubmit="return false;"></form>').hide().appendTo(eContainer);

		this.$davCont = $('<div></div>').appendTo(this.$mainForm);

		$tbl = $('<table class="wm_settings_common"></table>').appendTo(this.$davCont);
		this.buildHeading($tbl, 'DavSyncHeading');
		this.buildHint($tbl, 'DavSyncHint');
		this.$davSyncServer = this.buildData($tbl, 'DavSyncServer', true);

//		this.buildHeading($tbl, 'DavSyncHeadingLogin');
		this.buildHint($tbl, 'DavSyncHeadingLogin');
		this.$davSyncLogin = this.buildData($tbl, 'DavSyncLogin', true);
		if (!WebMail.bIsDemo)
		{
			this.buildData($tbl, 'DavSyncPasswordTitle', true, 'DavSyncPasswordValue');
		}
		else
		{
			this.buildData($tbl, 'DavSyncPasswordTitle', true, 'DavSyncDemoPasswordValue');
		}

		if (Browser.ios) {
			$tbl = $('<table class="wm_settings_common" style="margin-bottom: 30px;"></table>').appendTo(this.$davCont);
			this.buildIosLine($tbl);
		}

		this.$calendarsHeading = $('<table class="wm_settings_common"></table>').hide().appendTo(this.$davCont);
		this.buildHeading(this.$calendarsHeading, 'DavSyncSeparateUrlsHeading');
		this.buildHint(this.$calendarsHeading, 'DavSyncHintUrls');
		this.buildHeading(this.$calendarsHeading, 'DavSyncHeadingCalendar', 'h4');

		this.$calendarsData = $('<table class="wm_settings_common" style="margin-top: 0px;"></table>').hide().appendTo(this.$davCont);

		$tbl = $('<table class="wm_settings_common" style="margin-bottom: 30px;"></table>').appendTo(this.$davCont);
		if (!WebMail.Settings.allowContacts) {
			$tbl.hide();
		}
		this.buildHeading($tbl, 'DavSyncHeadingContacts', 'h4');
		this.$davSyncPersonalContacts = this.buildData($tbl, 'DavSyncPersonalContacts', WebMail.Settings.bShowPersonalContacts);
		this.$davSyncCollectedAddresses = this.buildData($tbl, 'DavSyncCollectedAddresses', WebMail.Settings.bShowPersonalContacts);
		this.$davSyncGlobalAddressBook = this.buildData($tbl, 'DavSyncGlobalAddressBook', WebMail.Settings.bShowGlobalContacts);

		this.$activeCont = $('<div></div>').appendTo(this.$mainForm);

		$tbl = $('<table class="wm_settings_common" style="margin-bottom: 30px;"></table>').appendTo(this.$activeCont);
		this.buildHeading($tbl, 'ActiveSyncHeading');
		this.buildHint($tbl, 'ActiveSyncHint');
		this.$activeSyncServer = this.buildData($tbl, 'ActiveSyncServer', true);
		this.$activeSyncLogin = this.buildData($tbl, 'ActiveSyncLogin', true);

		if (!WebMail.bIsDemo)
		{
			this.buildData($tbl, 'ActiveSyncPasswordTitle', true, 'ActiveSyncPasswordValue');
		}
		else
		{
			this.buildData($tbl, 'ActiveSyncPasswordTitle', true, 'ActiveSyncDemoPasswordValue');
		}
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}