/*
 * Classes:
 *  CViewContactScreenPart()
 *  CContactTab(container, imgDiv, tab, hr)
 *  CToAddressDropDown(Addr)
 */

function CViewContactScreenPart()
{
	this.Contact = null;
	this._contactEmail = '';
	this._contactHEmail = '';
	this._contactBEmail = '';
	this._contactOtherEmail = '';
	this._contactHWeb = '';
	this._contactBWeb = '';

	this._mainTbl = null;
	this._fullnameObj = null;
	this._fullnameCont = null;

	this._titleObj = null;
	this._titleCont = null;
	this._firstnameObj = null;
	this._firstnameCont = null;
	this._lastNameObj = null;
	this._lastNameCont = null;
	this._nicknameObj = null;
	this._nicknameCont = null;

	this._defaultEmailObj = null;
	this._defaultEmailCont = null;
	this._birthdayObj = null;
	this._birthdayCont = null;

	this._personalTbl = null;
	this._hEmailObj = null;
	this._hEmailCont = null;
	this._hStreetObj = null;
	this._hStreetCont = null;
	this._hCityTitle = null;
	this._hCityObj = null;
	this._hFaxTitle = null;
	this._hFaxObj = null;
	this._hCityFaxCont = null;
	this._hStateTitle = null;
	this._hStateObj = null;
	this._hPhoneTitle = null;
	this._hPhoneObj = null;
	this._hStatePhoneCont = null;
	this._hZipTitle = null;
	this._hZipObj = null;
	this._hMobileTitle = null;
	this._hMobileObj = null;
	this._hZipMobileCont = null;
	this._hCountryObj = null;
	this._hCountryCont = null;
	this._hWebObj = null;
	this._hWebCont = null;

	this._businessTbl = null;
	this._bEmailObj = null;
	this._bEmailCont = null;
	this._bCompanyTitle = null;
	this._bCompanyObj = null;
	this._bJobTitleTitle = null;
	this._bJobTitleObj = null;
	this._bCompanyJobTitleCont = null;
	this._bDepartmentTitle = null;
	this._bDepartmentObj = null;
	this._bOfficeTitle = null;
	this._bOfficeObj = null;
	this._bDepartmentOfficeCont = null;
	this._bStreetObj = null;
	this._bStreetCont = null;
	this._bCityTitle = null;
	this._bCityObj = null;
	this._bFaxTitle = null;
	this._bFaxObj = null;
	this._bCityFaxCont = null;
	this._bStateTitle = null;
	this._bStateObj = null;
	this._bPhoneTitle = null;
	this._bPhoneObj = null;
	this._bStatePhoneCont = null;
	this._bZipTitle = null;
	this._bZipObj = null;
	this._bCountryTitle = null;
	this._bCountryObj = null;
	this._bZipCountryCont = null;
	this._bMobileTitle = null;
	this._bMobileObj = null;
	this._bMobileCont = null;
	this._bWebObj = null;
	this._bWebCont = null;

	this._otherTbl = null;
	this._otherEmailObj = null;
	this._otherEmailCont = null;
	this._notesObj = null;
	this._notesCont = null;

	this._groupsTbl = null;
	this._groupsObj = null;

	this._editTbl = null;

	this._mailSearchCont = null;

	this._sectionClassName = 'wm_contacts_view';
	this._titleClassName = 'wm_contacts_view_title';
	this._nameClassName = 'wm_contacts_name';
	this._emailClassName = 'wm_contacts_email';
	this._sectionNameClassName = 'wm_contacts_section_name';

	this.show = function ()
	{
		this._mainTbl.className = '';
		this.fill();
	};

	this.hide = function ()
	{
		this._mainTbl.className = 'wm_hide';
	};

	this.UpdateContact = function (data)
	{
		this.Contact = data;
	};

	this.fill = function ()
	{
		this.FillDefSection(this.Contact);
		this.FillHomeSection(this.Contact);
		this.FillBusinessSection(this.Contact);
		this.FillOtherSection(this.Contact);
		this.FillGroupSection(this.Contact);

		if (this.Contact.bReadonly) {
			this._editTbl.className = 'wm_hide';
		}
		else {
			this._editTbl.className = this._sectionClassName;
		}

		// toggle MailTo and ShowContactEmails buttons on empty emails
		$(this._mailSearchCont).toggleClass('wm_hide', !(this.Contact.bEmail != '' || this.Contact.hEmail != '' || this.Contact.otherEmail));

//		this.InitContactView();
	};

	this.FillGroupSection = function (cont)
	{
		var a;
		var emptySection = true;
		CleanNode(this._groupsObj);
		var groups = cont.groups;
		var iCount = groups.length;
		var span;
		for (var i = 0; i < iCount; i++) {
			a = CreateChild(this._groupsObj, 'a', [['href', '#']]);
			a.onclick = function () { return false; };
			a.innerHTML = groups[i].name;
			a.id = groups[i].sGroupId;
			a.onclick = function () {
				SetHistoryHandler(
					{
						ScreenId: SCREEN_CONTACTS,
						Entity: PART_VIEW_GROUP,
						sGroupId: this.id
					}
				);
				return false;
			};
			emptySection = false;
			span = CreateChild(this._groupsObj, 'span');
			span.innerHTML = ',&nbsp;';
		}
		if (iCount > 0) span.innerHTML = '';
		if (emptySection) this._groupsTbl.className = 'wm_hide';
		else this._groupsTbl.className = this._sectionClassName;
	};

	this.build = function (container)
	{
		var tbl = CreateChild(container, 'table');
		this._mainTbl = tbl;
		tbl.style.width = '100%';
		var tr = tbl.insertRow(0);
		var td = tr.insertCell(0);
		/*rtl*/
		td.style.textAlign = window.LEFT;
		this.buildContactView(td);

		td = tr.insertCell(1);
		td.style.textAlign = window.RIGHT;
		td.style.verticalAlign = 'top';
		this.buildMailSearch(td);
	};

	this.buildGroupSection = function (container)
	{
		var tbl = CreateChild(container, 'table');
		this._groupsTbl = tbl;
		tbl.className = 'wm_hide';
		var tr = tbl.insertRow(0);
		var td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title wm_contacts_section_name';
		td.innerHTML = Lang.Groups;
		WebMail.langChanger.register('innerHTML', td, 'Groups', '');
		td = tr.insertCell(1);
		td.className = 'wm_contacts_groups';
		this._groupsObj = td;
	};

	this.buildContactView = function (container)
	{
		this.buildDefSection(container);
		this.buildHomeSection(container);
		this.buildBusinessSection(container);
		this.buildOtherSection(container);
		this.buildGroupSection(container);

		var obj = this;
		this._defaultEmailObj.onclick = function () { MailAllHandlerWithDropDown(obj._contactEmail); return false; };
		this._hEmailObj.onclick = function () { MailAllHandlerWithDropDown(obj._contactHEmail); return false; };
		this._bEmailObj.onclick = function () { MailAllHandlerWithDropDown(obj._contactBEmail); return false; };
		this._otherEmailObj.onclick = function () { MailAllHandlerWithDropDown(obj._contactOtherEmail); return false; };
		this._hWebObj.onclick = function () { OpenURL(obj._contactHWeb); return false; };
		this._bWebObj.onclick = function () { OpenURL(obj._contactBWeb); return false; };

		var tbl = CreateChild(container, 'table');
		this._editTbl = tbl;
		tbl.className = 'wm_hide';
		var tr = tbl.insertRow(0);
		var td = tr.insertCell(0);
		var a = CreateChild(td, 'a', [['href', '#']]);
		a.onclick = function () {
			SetHistoryHandler(
				{
					ScreenId: SCREEN_CONTACTS,
					Entity: PART_EDIT_CONTACT,
					sContactId: obj.Contact.sContactId
				}
			);
			return false;
		};
		a.innerHTML = Lang.EditContact;
		WebMail.langChanger.register('innerHTML', a, 'EditContact', '');
	};

	this.buildMailSearch = function (container)
	{
		var obj = this;
		/*rtl*/

		this._mailSearchCont = CreateChild(container, 'div');
		var div = CreateChild(this._mailSearchCont, 'span');
		//div.style.margin = '0 20px 10px';
		div.className = 'wm_button_link wm_control';
		div.onclick = function () { MailAllHandlerWithDropDown(obj._contactEmail); return false; };

		var divCh = CreateChild(div, 'span');
		divCh.innerHTML = Lang.ContactMail;
		WebMail.langChanger.register('innerHTML', divCh, 'ContactMail', '');

/*
		var div = CreateChild(this._mailSearchCont, 'div', [['style', 'float: ' + window.RIGHT + ';']]);
		div.style.margin = '0 20px 10px';
		div.className = 'wm_button_link wm_control';
		div.onclick = function () { MailAllHandlerWithDropDown(obj._contactEmail); return false; };

		var divCh = CreateChild(div, 'div');
		divCh.innerHTML = Lang.ContactMail;
		WebMail.langChanger.register('innerHTML', divCh, 'ContactMail', '');
*/
		div = CreateChild(this._mailSearchCont, 'div', [['style', 'width: 0; height: 0; padding: 0; overflow: hidden; clear: both;']]);

		div = CreateChild(this._mailSearchCont, 'div');
		div.style.margin = '10px 20px 0';
		var a = CreateChild(div, 'a');
		a.href = '#';
		a.onclick = function () { ViewAllContactMailsHandler(obj.Contact); return false; };
		a.innerHTML = Lang.ContactViewAllMails;
		WebMail.langChanger.register('innerHTML', a, 'ContactViewAllMails', '');
//		a.className = (UseCustomContacts) ? 'wm_hide' : '';
	};

//	this.InitContactView = function ()
//	{
//		if (UseCustomContacts) {
//			this._fullnameCont.className = 'wm_hide';
//		} else if (UseCustomContacts1) {
//			this._titleCont.className = 'wm_hide';
//			this._firstnameCont.className = 'wm_hide';
//			this._lastNameCont.className = 'wm_hide';
//			this._nicknameCont.className = 'wm_hide';
//
//			this._bWebCont.className = 'wm_hide';
//		} else {
//			this._titleCont.className = 'wm_hide';
//			this._firstnameCont.className = 'wm_hide';
//			this._lastNameCont.className = 'wm_hide';
//			this._nicknameCont.className = 'wm_hide';
//			this._bMobileCont.className = 'wm_hide';
//		}
//	};
}

CViewContactScreenPart.prototype = ViewContactPrototype;

function CContactTab(container, imgDiv, tab, hr)
{
	this._container = container;
	this._controlImg = imgDiv;
	this.isHidden = false;
	this._tab = tab;
    this._hr = (hr) ? hr : null;
}

CContactTab.prototype =
{
	show: function ()
	{
		this._tab.className = 'wm_contacts_tab';
		if (this.isHidden) {
			this.Close();
		}
		else {
			this.open();
		}
	},

	hide: function ()
	{
		this._tab.className = 'wm_hide';
		this._container.className = 'wm_hide';
		if (this._hr != null) this._hr.className = 'wm_hide';
	},

	ChangeTabMode: function ()
	{
		if (this.isHidden) {
			this.open();
		}
		else {
			this.Close();
		}
	},

	open: function ()
	{
		this._container.className = 'wm_contacts_view wm_contacts_tab_view';
		this._controlImg.className = 'wm_contacts_tab_close_mode';
		if (this._hr != null) this._hr.className = '';
		this.isHidden = false;
	},

	Close: function ()
	{
		this._container.className = 'wm_hide';
		this._controlImg.className = 'wm_contacts_tab_open_mode';
		if (this._hr != null) this._hr.className = 'wm_hide';
		this.isHidden = true;
	}
};

function CToAddressDropDown(Addr)
{
	this.TO_TYPE = 1;
	this.CC_TYPE = 2;
	this.BCC_TYPE = 3;

	this._main = null;
	this._isBuild = false;
	this._timeout = null;

	this._width = 50;

	this.InitAddr(Addr);
	this.buildAndShow();
}

CToAddressDropDown.prototype = {

	InitAddr: function (Addr)
	{
		this._addr = Addr;
	},

	show: function ()
	{
		this._main.style.top = (WebMail.mouseY - 10)+ 'px';
		this._main.style.left = (WebMail.mouseX - 50 + 10) + 'px';
		this._main.className = 'wm_addressfield_dropdown';

		this.ClearTimeout();
		this.TimeoutHide();
	},

	hide: function ()
	{
		this._main.className = 'wm_hide';
	},

	TimeoutHide: function ()
	{
		var obj = this;
		this._timeout = setTimeout( function() { obj.hide(); }, 1000);
	},

	ClearTimeout: function ()
	{
		if (this._timeout != null) {
			clearTimeout(this._timeout);
			this._timeout = null;
		}
	},

	buildAndShow: function ()
	{
		if (this._isBuild) {
			this.show();
			return;
		}

		var obj = this;
		this._main = CreateChild(document.body, 'div');
		this._main.className = 'wm_hide';
		this._main.style.position = 'absolute';
//		this._main.style.width = this._width + 'px';
		this._main.onmouseover = function () {
			obj.ClearTimeout();
		};
		this._main.onmouseout = function () {
			obj.TimeoutHide();
		};

		var a1 = CreateChild(this._main, 'a');
		a1.href = '#';
		a1.innerHTML = '<nobr>' +Lang.ContactDropDownTO + '</nobr>';
		a1.onclick = function() {
			obj.ReturnField(obj.TO_TYPE);
		};

		var a2 = CreateChild(this._main, 'a');
		a2.href = '#';
		a2.innerHTML = '<nobr>' + Lang.ContactDropDownCC + '</nobr>';
		a2.onclick = function() {
			obj.ReturnField(obj.CC_TYPE);
		};

		var a3 = CreateChild(this._main, 'a');
		a3.href = '#';
		a3.innerHTML = '<nobr>' + Lang.ContactDropDownBCC + '</nobr>';
		a3.onclick = function() {
			obj.ReturnField(obj.BCC_TYPE);
		};

/*
		var tbl = CreateChild(this._main, 'table');
		tbl.className = 'wm_addressfield_dropdown';
		tbl.style.width = this._width + 'px';
		var tr = tbl.insertRow(0);
		var td = tr.insertCell(0);
		td.innerHTML = Lang.ContactDropDownTO;
		td.onclick = function() {
			obj.ReturnField(obj.TO_TYPE);
		};

		tr = tbl.insertRow(1);
		td = tr.insertCell(0);
		td.innerHTML = Lang.ContactDropDownCC;
		td.onclick = function() {
			obj.ReturnField(obj.CC_TYPE);
		};

		tr = tbl.insertRow(2);
		td = tr.insertCell(0);
		td.innerHTML = Lang.ContactDropDownBCC;
		td.onclick = function() {
			obj.ReturnField(obj.BCC_TYPE);
		};
*/
		this._isBuild = true;
		this.show();
	},

	ReturnField: function (returnType)
	{
		var to, cc, bcc;
		to = cc = bcc = '';
		if (returnType == this.TO_TYPE) {
			to = this._addr;
		}
        else if (returnType == this.CC_TYPE) {
			cc = this._addr;
		}
        else if (returnType == this.BCC_TYPE) {
			bcc = this._addr;
		}
		this.hide();
		MailAllHandler(to, cc, bcc);
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}