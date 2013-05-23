/*
 * Classes:
 *  CEditContactScreenPart(parent)
 */

function CEditContactScreenPart(parent)
{
	this._parent = parent;
	this._primaryEmail = PRIMARY_DEFAULT_EMAIL;
	this.shown = false;
	this.DefEmail = '';
	
	this.Contact = new CContact();
	this.groups = null;
	this._groupsObjs = Array();
	
	this._mainTbl = null;
	this._moreInfo = null;
	this._buttonsTbl = null;
	this._showMoreInfo = null;
	this._isMoreInfo = false;

	this._notSpecified = null;
	this._defaultEmailSel = null;
	this._defaultEmailObj = null;

	this._hEmailObj = null;
	this._fullnameObj = null;
	this._titleObj = null;
	this._firstnameObj = null;
	this._lastNameObj = null;
	this._nicknameObj = null;
	this._notesObj = null;
//	this._useFriendlyNmObj = null;
	this._hStreetObj = null;
	this._hCityObj = null;
	this._hStateObj = null;
	this._hZipObj = null;
	this._hCountryObj = null;
	this._hPhoneObj = null;
	this._hFaxObj = null;
	this._hMobileObj = null;
	this._hWebObj = null;
	this._bEmailObj = null;
	this._bCompanyObj = null;
	this._bStreetObj = null;
	this._bCityObj = null;
	this._bStateObj = null;
	this._bZipObj = null;
	this._bCountryObj = null;
	this._bJobTitleObj = null;
	this._bDepartmentObj = null;
	this._bOfficeObj = null;
	this._bPhoneObj = null;
	this._bFaxObj = null;
	this._bPMobileObj = null;
	this._bWebObj = null;
	this._dayObj = null;
	this._yearObj = null;
	this._monthObj = null;
	this._otherEmailObj = null;
	this._groupsObj = null;
	
	this._tabs = Array();

	this.isCreateContact = false;
	this.isSaveContact = false;
}

CEditContactScreenPart.prototype = {
	show: function ()
	{
		this._mainTbl.className = 'wm_contacts_view';
		this._moreInfo.className = (this._isMoreInfo) ? '' : 'wm_hide';
		this._buttonsTbl.className = 'wm_contacts_view';
		this._fullnameObj.focus();
		this.shown = true;
	},
	
	FillDefaultEmailSel: function ()
	{
		var emails = Array(); var titles = Array();
		emails[0] = HtmlEncodeWithQuotes(this._hEmailObj.value);		titles[0] = Lang.Personal;
		emails[1] = HtmlEncodeWithQuotes(this._bEmailObj.value);		titles[1] = Lang.Business;
		emails[2] = HtmlEncodeWithQuotes(this._otherEmailObj.value);	titles[2] = Lang.Other;
		if (emails[0].length == 0 && emails[1].length == 0 && emails[2].length == 0) {
			this._notSpecified.className = '';
			this._defaultEmailSel.className = 'wm_hide';
		}
		else {
			this._notSpecified.className = 'wm_hide';
			var sel = this._defaultEmailSel;
			CleanNode(sel);
			sel.className = '';
			var opts = Array();
			var existsEmail = -1;
			for (var i=0; i<=2; i++) {
				if (emails[i].length != 0) {
					opts[i] = CreateChild(sel, 'option', [['value', i]]);
					opts[i].innerHTML = titles[i] + ': ' + emails[i];
					if (existsEmail == -1) existsEmail = i;
				}
			}
			if (opts[this._primaryEmail]) {
				opts[this._primaryEmail].selected = true;
			} else if (existsEmail != -1) {
				opts[existsEmail].selected = true;
				this._primaryEmail = existsEmail;
			}
		}
	},
	
	showMoreInfo: function ()
	{
		var dEmail = this._defaultEmailObj.value;
		switch (this._primaryEmail) {
			case PRIMARY_HOME_EMAIL:
				this._hEmailObj.value = dEmail; break;
			case PRIMARY_BUS_EMAIL:
				this._bEmailObj.value = dEmail; break;
			case PRIMARY_OTHER_EMAIL:
				this._otherEmailObj.value = dEmail; break;
		}
		this.FillDefaultEmailSel();
		this._moreInfo.className = (this.shown) ? '' : 'wm_hide';
		
		this._defaultEmailObj.className = 'wm_hide';
		this._showMoreInfo.className = 'wm_hide';
		this._isMoreInfo = true;
		this._parent.resizeBody();
	},
	
	hide: function ()
	{
		this._mainTbl.className = 'wm_hide';
		this._moreInfo.className = 'wm_hide';
		this._buttonsTbl.className = 'wm_hide';
		this.shown = false;
	},
	
	hideMoreInfo: function ()
	{
		var emails = Array();
		emails[0] = this._hEmailObj.value;
		emails[1] = this._bEmailObj.value;
		emails[2] = this._otherEmailObj.value;
		if (emails[this._primaryEmail].length == 0) {
			if (emails[0].length != 0) {
				this._primaryEmail = 0;
			} else if (emails[1].length != 0) {
				this._primaryEmail = 1;
			} else if (emails[2].length != 0) {
				this._primaryEmail = 2;
			}
		}
		this._defaultEmailObj.value = emails[this._primaryEmail];
		this._notSpecified.className = 'wm_hide';
		this._defaultEmailSel.className = 'wm_hide';
		this._moreInfo.className = 'wm_hide';
		this._defaultEmailObj.className = 'wm_input';
		this._showMoreInfo.className = '';
		this._isMoreInfo = false;
		this._parent.resizeBody();
	},
	
	ChangeTabMode: function (index)
	{
		this._tabs[index].ChangeTabMode();
		this._parent.resizeBody();
	},
	
	CheckContactUpdate: function ()
	{
		if (this.isCreateContact) {
			WebMail.showReport(Lang.ReportContactSuccessfulyAdded);
			this.isCreateContact = false;
		}
		else if (this.isSaveContact) {
			WebMail.showReport(Lang.ReportContactUpdatedSuccessfuly);
			this.isSaveContact = false;
		}
	},
	
	fillGroups: function (oGroups)
	{
		this.groups = oGroups;
		this._groupsObjs = [];
		CleanNode(this._groupsObj);
		var eGroupInfo = CreateChild(this._groupsObj, 'div', [['style', 'padding: 0 0 6px 0;'],
			['class', 'wm_secondary_info']]);
		eGroupInfo.innerHTML = Lang.InfoGroupsOfContact;
		var aGroups = oGroups.items;
		var iCount = aGroups.length;
		for (var i = 0; i < iCount; i++) {
			var oGroup = aGroups[i];
			var eGroupContainer = CreateChild(this._groupsObj, 'div', [['style', 'margin: 0 0 6px 0;']]);
			var eGroupControl = CreateChild(eGroupContainer, 'input', [['type', 'checkbox'], 
				['class', 'wm_checkbox'], ['id', 'check_group_' + oGroup.sGroupId]]);
			eGroupControl.name = oGroup.name;
			var eGroupLabel = CreateChild(eGroupContainer, 'label', [['for', 'check_group_' + oGroup.sGroupId]]);
			eGroupLabel.innerHTML = oGroup.name;
			this._groupsObjs[i] = eGroupControl;
		}
	},
	
	fill: function (cont)
	{
		this.Contact = cont;
		this._primaryEmail = cont.primaryEmail;
		switch (cont.primaryEmail) {
			case PRIMARY_BUS_EMAIL:
				this._defaultEmailObj.value = HtmlDecode(cont.bEmail);
				break;
			case PRIMARY_OTHER_EMAIL:
				this._defaultEmailObj.value = HtmlDecode(cont.otherEmail);
				break;
			case PRIMARY_HOME_EMAIL:
				this._defaultEmailObj.value = HtmlDecode(cont.hEmail);
				break;
		}
//		this._useFriendlyNmObj.checked = cont.useFriendlyName;
		this._fullnameObj.value = HtmlDecode(cont.name);
		this._titleObj.value = HtmlDecode(cont.title);
		this._firstnameObj.value = HtmlDecode(cont.firstName);
		this._lastNameObj.value = HtmlDecode(cont.lastName);
		this._nicknameObj.value = HtmlDecode(cont.nickName);
		this.FillDay(cont.day);
		this.FillMonth(cont.month);
		this.FillYear(cont.year);
		this._hEmailObj.value = HtmlDecode(cont.hEmail);
		this._hStreetObj.value = HtmlDecode(cont.hStreet);
		this._hCityObj.value = HtmlDecode(cont.hCity);
		this._hStateObj.value = HtmlDecode(cont.hState);
		this._hZipObj.value = HtmlDecode(cont.hZip);
		this._hCountryObj.value = HtmlDecode(cont.hCountry);
		this._hFaxObj.value = HtmlDecode(cont.hFax);
		this._hPhoneObj.value = HtmlDecode(cont.hPhone);
		this._hMobileObj.value = HtmlDecode(cont.hMobile);
		this._hWebObj.value = HtmlDecode(cont.hWeb);

		this._bEmailObj.value = HtmlDecode(cont.bEmail);
		this._bCompanyObj.value = HtmlDecode(cont.bCompany);
		this._bJobTitleObj.value = HtmlDecode(cont.bJobTitle);
		this._bDepartmentObj.value = HtmlDecode(cont.bDepartment);
		this._bOfficeObj.value = HtmlDecode(cont.bOffice);
		this._bStreetObj.value = HtmlDecode(cont.bStreet);
		this._bCityObj.value = HtmlDecode(cont.bCity);
		this._bStateObj.value = HtmlDecode(cont.bState);
		this._bZipObj.value = HtmlDecode(cont.bZip);
		this._bCountryObj.value = HtmlDecode(cont.bCountry);
		this._bFaxObj.value = HtmlDecode(cont.bFax);
		this._bPhoneObj.value = HtmlDecode(cont.bPhone);
//		this._bMobileObj.value = HtmlDecode(cont.bMobile);
		this._bWebObj.value = HtmlDecode(cont.bWeb);

		this._otherEmailObj.value = HtmlDecode(cont.otherEmail);
		this._notesObj.value = HtmlDecode(cont.notes);

		var iCount = this._groupsObjs.length;
		if (iCount > 0) {
			this._tabs[3].show();
		}
		else {
			this._tabs[3].hide();
		}
		for (var i = 0; i < iCount; i++) {
			var sGroupId = this._groupsObjs[i].id.substring(12);
			var checked = false;
			var jCount = cont.groups.length;
			for (var j = 0; j < jCount; j++) {
				if (cont.groups[j].sGroupId === sGroupId)
					checked = true;
			}
			this._groupsObjs[i].checked = checked;
		}

		if (cont.onlyMainData) {
			this.hideMoreInfo();
		}
		else {
			this.showMoreInfo();
		}
		
		this._tabs[0].Close();
		this._tabs[1].Close();
		this._tabs[2].Close();
		this._tabs[3].Close();
		if (cont.hasHomeData || !cont.hasBusinessData && !cont.hasOtherData) {
			this._tabs[0].open();
		}
		if (cont.hasBusinessData) {
			this._tabs[1].open();
		}
		if (cont.hasOtherData) {
			this._tabs[2].open();
		}
	},
	
	CancelChanges: function ()
	{
		var sContactId = this.Contact.sContactId;
		if (sContactId.length > 0) {
			SetHistoryHandler(
				{
					ScreenId: SCREEN_CONTACTS,
					Entity: PART_VIEW_CONTACT,
					sContactId: sContactId
				}
			);
		}
		else {
			SetHistoryHandler(
				{
					ScreenId: SCREEN_CONTACTS,
					Entity: PART_CONTACTS
				}
			);
		}
	},
	
	_setInputKeyPress: function (inp)
	{
	    var obj = this;
        inp.onkeypress = function (ev) { if (isEnter(ev)) {obj.saveChanges(); this.blur();} };
    },
	
	saveChanges: function ()
	{
		/* validation */
		var fullNameValue = this._fullnameObj.value;
		var titleValue = this._titleObj.value;
		var firstNameValue = this._firstnameObj.value;
		var lastNameValue = this._lastNameObj.value;
		var nickNameValue = this._nicknameObj.value;
		this.DefEmail = this._defaultEmailObj.value;
		var hEmailValue = this._hEmailObj.value;
		var bEmailValue = this._bEmailObj.value;
		var oEmailValue = this._otherEmailObj.value;
		switch (this._primaryEmail) {
			case PRIMARY_BUS_EMAIL:
				if (this._isMoreInfo) {
					this.DefEmail = bEmailValue;
				} else {
					bEmailValue = this.DefEmail;
				}
				break;
			case PRIMARY_OTHER_EMAIL:
				if (this._isMoreInfo) {
					this.DefEmail = oEmailValue;
				} else {
					oEmailValue = this.DefEmail;
				}
				break;
			case PRIMARY_HOME_EMAIL:
				if (this._isMoreInfo) {
					this.DefEmail = hEmailValue;
				} else {
					hEmailValue = this.DefEmail;
				}
				break;
		}
		if (this.Contact.sContactId.length > 0) this.DefEmail = '';
		if (Validator.isEmpty(fullNameValue) && Validator.isEmpty(hEmailValue) &&
				Validator.isEmpty(bEmailValue) && Validator.isEmpty(oEmailValue)) {
			Dialog.alert(Lang.WarningContactNotComplete);
			return;
		}
		if (Validator.hasEmailForbiddenSymbols(hEmailValue) || Validator.hasEmailForbiddenSymbols(bEmailValue) ||
				Validator.hasEmailForbiddenSymbols(oEmailValue)) {
			Dialog.alert(Lang.WarningCorrectEmail);
			return;
		}
		
		var hWebValue = Validator.correctWebPage(this._hWebObj.value);
		var bWebValue = Validator.correctWebPage(this._bWebObj.value);
		
		/* saving */
		var contact = new CContact();
		contact.sContactId = this.Contact.sContactId;
		contact.sEtag = this.Contact.sEtag;
		contact.primaryEmail = this._primaryEmail;
//		contact.useFriendlyName = this._useFriendlyNmObj.checked;
		
		contact.name = fullNameValue;
		contact.title = titleValue;
		contact.firstName = firstNameValue;
		contact.lastName = lastNameValue;
		contact.nickName = nickNameValue;

		contact.day = this._dayObj.value - 0;
		contact.month = this._monthObj.value - 0;
		contact.year = this._yearObj.value - 0;
		
		contact.hEmail = hEmailValue;
		contact.hStreet = this._hStreetObj.value;
		contact.hCity = this._hCityObj.value;
		contact.hState = this._hStateObj.value;
		contact.hZip = this._hZipObj.value;
		contact.hCountry = this._hCountryObj.value;
		contact.hFax = this._hFaxObj.value;
		contact.hPhone = this._hPhoneObj.value;
		contact.hMobile = this._hMobileObj.value;
		contact.hWeb = hWebValue;

		contact.bEmail = bEmailValue;
		contact.bCompany = this._bCompanyObj.value;
		contact.bJobTitle = this._bJobTitleObj.value;
		contact.bDepartment = this._bDepartmentObj.value;
		contact.bOffice = this._bOfficeObj.value;
		contact.bStreet = this._bStreetObj.value;
		contact.bCity = this._bCityObj.value;
		contact.bState = this._bStateObj.value;
		contact.bZip = this._bZipObj.value;
		contact.bCountry = this._bCountryObj.value;
		contact.bFax = this._bFaxObj.value;
		contact.bPhone = this._bPhoneObj.value;
//		contact.bMobile = this._bMobileObj.value;
		contact.bWeb = bWebValue;

		contact.otherEmail = oEmailValue;
		contact.notes = this._notesObj.value;

		var groupsCount = this._groupsObjs.length;
		for (var groupIndex = 0; groupIndex < groupsCount; groupIndex++) {
			var groupObj = this._groupsObjs[groupIndex];
			if (groupObj.checked) {
				contact.groups.push({sGroupId: groupObj.id.substring(12), name: groupObj.name});
			}
		}

		var xml = contact.getInXml(this._parent.getXmlParams());
		var stringDataKey;
		if (contact.sContactId.length === 0) {
			WebMail.DataSource.cache.clearAllContactsGroupsList();
			for (var i = 0; i < contact.groups; i++) {
				stringDataKey = WebMail.DataSource.getStringDataKey(TYPE_GROUP, {sGroupId: contact.groups[i].sGroupId});
				WebMail.DataSource.cache.removeData(TYPE_GROUP, stringDataKey);
			}
			RequestHandler('new', 'contact', xml);
			this.isCreateContact = true;
		}
		else {
			stringDataKey = WebMail.DataSource.getStringDataKey(contact.type, {sContactId: contact.sContactId});
			WebMail.DataSource.cache.replaceData(contact.type, stringDataKey, contact);
			switch (contact.primaryEmail) {
				case PRIMARY_HOME_EMAIL: contact.email = contact.hEmail; break;
				case PRIMARY_BUS_EMAIL: contact.email = contact.bEmail; break;
				case PRIMARY_OTHER_EMAIL: contact.email = contact.otherEmail; break;
			}
			WebMail.DataSource.cache.addRemoveRenameContactInGroups(contact);
			switch (this.Contact.primaryEmail) {
				case PRIMARY_HOME_EMAIL: this.Contact.email = this.Contact.hEmail; break;
				case PRIMARY_BUS_EMAIL: this.Contact.email = this.Contact.bEmail; break;
				case PRIMARY_OTHER_EMAIL: this.Contact.email = this.Contact.otherEmail; break;
			}
			if (this.Contact.primaryEmail != contact.primaryEmail || this.Contact.email != contact.email ||
				this.Contact.name != contact.name) {
				WebMail.DataSource.cache.clearAllContactsGroupsList();
			}
			RequestHandler('update', 'contact', xml);
			this.isSaveContact = true;
		}
	},
	
	FillMonth: function (month)
	{
		var obj = this;
		var sel = this._monthObj;
		CleanNode(sel);
		var opt;
		var iCount = Lang.Monthes.length;
		for (var i=0; i<iCount; i++) {
			opt = CreateChild(sel, 'option', [['value', i]]);
			opt.innerHTML = Lang.Monthes[i];
			if (month == i) opt.selected = true;
		}
		sel.onchange = function () {
			obj.FillDay(obj._dayObj.value);
		};
	},
	
	FillDay: function (day)
	{
		var daysInMonth = [31, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
		var year = this._yearObj.value;
		if (year == 0 || (year % 4) == 0 && (year % 100) != 0 || (year % 400) == 0) 
			daysInMonth[2] = 29;
		var month = this._monthObj.value;
		var sel = this._dayObj;
		CleanNode(sel);
		var opt = CreateChild(sel, 'option', [['value', 0]]);
		opt.innerHTML = Lang.Day;
		for (var i=1; i<=daysInMonth[month]; i++) {
			opt = CreateChild(sel, 'option', [['value', i]]);
			opt.innerHTML = i;
			if (day == i) opt.selected = true;
		}
		if (day > daysInMonth[month]) {
			opt.selected = true;
		}
	},
	
	FillYear: function (year)
	{
		var obj = this;
		var sel = this._yearObj;
		CleanNode(sel);
		var opt = CreateChild(sel, 'option', [['value', 0]]);
		opt.innerHTML = Lang.Year;
		var now = new Date();
		var firstYear = now.getFullYear();
		var lastYear = firstYear - 100;
		for (var i=firstYear; i>=lastYear; i--) {
			opt = CreateChild(sel, 'option', [['value', i]]);
			opt.innerHTML = i;
			if (year == i) opt.selected = true;
		}
		sel.onchange = function () {
			if (obj._monthObj.value == '2')
				obj.FillDay(obj._dayObj.value);
		};
	},
	
	TextAreaLimit: function (ev)
	{
		return TextAreaLimit(ev, this, 85);
	},
	
	build: function (container)
	{
		var span, imgDiv, tabTbl, tr, td;
		var obj = this;
		var tbl = CreateChild(container, 'table');
		this._mainTbl = tbl;
		tbl.style.marginTop = '0';
		tbl.className = 'wm_hide';
		var rowIndex = 0;

		tr = tbl.insertRow(rowIndex++);
//		tr.className = (UseCustomContacts) ? 'wm_hide' : '';
//		tr.className = 'wm_hide';
		td = tr.insertCell(0);
		td.style.width = '25%';
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.ContactDisplayName + ':';
		WebMail.langChanger.register('innerHTML', td, 'ContactDisplayName', ':');
		td = tr.insertCell(1);
		td.style.width = '75%';
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['maxlength', '85']]);
		this._setInputKeyPress(inp);
		this._fullnameObj = inp;

        tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.DefaultEmail + ':';
		WebMail.langChanger.register('innerHTML', td, 'DefaultEmail', ':');
		td = tr.insertCell(1);
		span = CreateChild(td, 'span');
		span.innerHTML = Lang.NotSpecifiedYet;
		WebMail.langChanger.register('innerHTML', span, 'NotSpecifiedYet', ':');
		span.className = 'wm_hide';
		this._notSpecified = span;
		var sel = CreateChild(td, 'select');
		sel.className = 'wm_hide';
		sel.onchange = function () { obj._primaryEmail = this.value - 0; };
		sel.style.width = '200px';
		this._defaultEmailSel = sel;
		var inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['maxlength', '255']]);
		this._setInputKeyPress(inp);
		this._defaultEmailObj = inp;

		tr = tbl.insertRow(rowIndex++);
//		tr.className = (!UseCustomContacts) ? 'wm_hide' : '';
		tr.className = 'wm_hide';
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.ContactTitle + ':';
		WebMail.langChanger.register('innerHTML', td, 'ContactTitle', ':');
		td = tr.insertCell(1);
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['maxlength', '85']]);
		this._setInputKeyPress(inp);
		this._titleObj = inp;

		tr = tbl.insertRow(rowIndex++);
//		tr.className = (!UseCustomContacts) ? 'wm_hide' : '';
//		tr.className = 'wm_hide';
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.ContactFirstName + ':';
		WebMail.langChanger.register('innerHTML', td, 'ContactFirstName', ':');
		td = tr.insertCell(1);
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['maxlength', '85']]);
		this._setInputKeyPress(inp);
		this._firstnameObj = inp;

		tr = tbl.insertRow(rowIndex++);
//		tr.className = (!UseCustomContacts) ? 'wm_hide' : '';
//		tr.className = 'wm_hide';
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.ContactSurName + ':';
		WebMail.langChanger.register('innerHTML', td, 'ContactSurName', ':');
		td = tr.insertCell(1);
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['maxlength', '85']]);
		this._setInputKeyPress(inp);
		this._lastNameObj = inp;

		tr = tbl.insertRow(rowIndex++);
//		tr.className = (!UseCustomContacts) ? 'wm_hide' : '';
//		tr.className = 'wm_hide';
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.ContactNickName + ':';
		WebMail.langChanger.register('innerHTML', td, 'ContactNickName', ':');
		td = tr.insertCell(1);
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['maxlength', '85']]);
		this._setInputKeyPress(inp);
		this._nicknameObj = inp;

//		tr = tbl.insertRow(rowIndex++);
////		tr.className =(UseCustomContacts || UseCustomContacts1) ? 'wm_hide' : '';
//		td = tr.insertCell(0);
//		td = tr.insertCell(1);
//		this._useFriendlyNmObj = CreateChild(td, 'input', [['type', 'checkbox'], ['class', 'wm_checkbox'], ['id', 'use_friendly_nm_contacts']]);
//		var lbl = CreateChild(td, 'label', [['for', 'use_friendly_nm_contacts']]);
//		lbl.innerHTML = Lang.UseFriendlyName1;
//		WebMail.langChanger.register('innerHTML', lbl, 'UseFriendlyName1', '');
//		lbl = CreateChild(td, 'label');
//		lbl.innerHTML = Lang.UseFriendlyName2;
//		lbl.className = 'wm_secondary_info wm_inline_info';
//		WebMail.langChanger.register('innerHTML', lbl, 'UseFriendlyName2', '');
		
		var div = CreateChild(container, 'div');
		div.className = 'wm_hide';
		this._moreInfo = div;
		tbl = CreateChild(div, 'table');
		tbl.className = 'wm_contacts_view';
		tbl.style.width = '90%';
		tbl.style.marginBottom = '20px';
		tr = tbl.insertRow(0);
		td = tr.insertCell(0);
		td.className = 'wm_hide_section';
		var a = CreateChild(td, 'a', [['href', '#']]);
		a.innerHTML = Lang.HideAddFields;
		WebMail.langChanger.register('innerHTML', a, 'HideAddFields', '');
		a.onclick = function () { obj.hideMoreInfo(); return false; };

		/*------Personal------*/
		
		tabTbl = CreateChild(div, 'table');
		tabTbl.onclick = function () { obj.ChangeTabMode(0); };
		tabTbl.className = 'wm_contacts_tab';
		tr = tabTbl.insertRow(0);
		td = tr.insertCell(0);
		span = CreateChild(td, 'span');
		span.className = 'wm_contacts_tab_name';
		span.innerHTML = Lang.Home;
		WebMail.langChanger.register('innerHTML', span, 'Home', '');
		imgDiv = CreateChild(td, 'div');
		imgDiv.className = 'wm_contacts_tab_open_mode';
		
		rowIndex = 0;
		tbl = CreateChild(div, 'table');
		this._tabs[0] = new CContactTab(tbl, imgDiv, tabTbl);
		tbl.className = 'wm_contacts_view wm_contacts_tab_view';
		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.style.width = '20%';
		td.innerHTML = Lang.PersonalEmail + ':';
		WebMail.langChanger.register('innerHTML', td, 'PersonalEmail', ':');
		td = tr.insertCell(1);
		td.style.width = '80%';
		td.colSpan = 4;
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '45'], ['maxlength', '255']]);
		this._setInputKeyPress(inp);
		inp.onchange = function () { obj.FillDefaultEmailSel(); };
		this._hEmailObj = inp;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.StreetAddress + ':';
		WebMail.langChanger.register('innerHTML', td, 'StreetAddress', ':');
		td = tr.insertCell(1);
		td.colSpan = 4;
		var txt = CreateChild(td, 'textarea', [['class', 'wm_input'], ['cols', '35'], ['rows', '2']]);
		txt.onkeydown = this.TextAreaLimit;
		this._hStreetObj = txt;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.style.width = '20%';
		td.innerHTML = Lang.City + ':';
		WebMail.langChanger.register('innerHTML', td, 'City', ':');
		td = tr.insertCell(1);
		td.style.width = '30%';
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '65']]);
		this._setInputKeyPress(inp);
		this._hCityObj = inp;
		td = tr.insertCell(2);
		td.style.width = '10%';
		td = tr.insertCell(3);
		td.className = 'wm_contacts_view_title';
		td.style.width = '10%';
		td.innerHTML = Lang.Fax + ':';
		WebMail.langChanger.register('innerHTML', td, 'Fax', ':');
		td = tr.insertCell(4);
		td.style.width = '30%';
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '50']]);
		this._setInputKeyPress(inp);
		this._hFaxObj = inp;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.StateProvince + ':';
		WebMail.langChanger.register('innerHTML', td, 'StateProvince', ':');
		td = tr.insertCell(1);
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '65']]);
		this._setInputKeyPress(inp);
		this._hStateObj = inp;
		td = tr.insertCell(2);
		td = tr.insertCell(3);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.Phone + ':';
		WebMail.langChanger.register('innerHTML', td, 'Phone', ':');
		td = tr.insertCell(4);
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '50']]);
		this._setInputKeyPress(inp);
		this._hPhoneObj = inp;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.ZipCode + ':';
		WebMail.langChanger.register('innerHTML', td, 'ZipCode', ':');
		td = tr.insertCell(1);
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '10']]);
		this._setInputKeyPress(inp);
		this._hZipObj = inp;
		td = tr.insertCell(2);
		td = tr.insertCell(3);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.Mobile + ':';
		WebMail.langChanger.register('innerHTML', td, 'Mobile', ':');
		td = tr.insertCell(4);
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '50']]);
		this._setInputKeyPress(inp);
		this._hMobileObj = inp;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.CountryRegion + ':';
		WebMail.langChanger.register('innerHTML', td, 'CountryRegion', ':');
		td = tr.insertCell(1);
		td.colSpan = 4;
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '65']]);
		this._setInputKeyPress(inp);
		this._hCountryObj = inp;

		tr = tbl.insertRow(rowIndex++);
//		tr.className = (UseCustomContacts) ? 'wm_hide' : '';
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.WebPage + ':';
		WebMail.langChanger.register('innerHTML', td, 'WebPage', ':');
		td = tr.insertCell(1);
		td.colSpan = 4;
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '45'], ['maxlength', '85']]);
		this._setInputKeyPress(inp);
		this._hWebObj = inp;
		inp = CreateChild(td, 'input', [['type', 'button'], ['class', 'wm_go_button'], ['value', Lang.Go]]);
		WebMail.langChanger.register('value', inp, 'Go', '');
		inp.onclick = function () { OpenURL(obj._hWebObj.value); };

		/*------Business------*/
		
		tabTbl = CreateChild(div, 'table');
		tabTbl.onclick = function () { obj.ChangeTabMode(1); };
//		tabTbl.className = (UseCustomContacts1) ? 'wm_hide' : 'wm_contacts_tab';
		tabTbl.className = 'wm_contacts_tab';
		tr = tabTbl.insertRow(0);
		td = tr.insertCell(0);
		span = CreateChild(td, 'span');
		span.className = 'wm_contacts_tab_name';
		span.innerHTML = Lang.Business;
		WebMail.langChanger.register('innerHTML', span, 'Business', '');
		imgDiv = CreateChild(td, 'div');
		imgDiv.className = 'wm_contacts_tab_open_mode';
		
		rowIndex = 0;
		tbl = CreateChild(div, 'table');
		this._tabs[1] = new CContactTab(tbl, imgDiv, tabTbl);
		tbl.className = 'wm_hide';
		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.style.width = '20%';
		td.innerHTML = Lang.BusinessEmail + ':';
		WebMail.langChanger.register('innerHTML', td, 'BusinessEmail', ':');
		td = tr.insertCell(1);
		td.style.width = '80%';
		td.colSpan = 4;
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '45'], ['maxlength', '255']]);
		this._setInputKeyPress(inp);
		inp.onchange = function () { obj.FillDefaultEmailSel(); };
		this._bEmailObj = inp;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.style.width = '20%';
		td.innerHTML = Lang.Company + ':';
		WebMail.langChanger.register('innerHTML', td, 'Company', ':');
		td = tr.insertCell(1);
		td.style.width = '30%';
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '65']]);
		this._setInputKeyPress(inp);
		this._bCompanyObj = inp;
		td = tr.insertCell(2);
		td.style.width = '5%';
		td = tr.insertCell(3);
		td.className = 'wm_contacts_view_title';
		td.style.width = '15%';
		td.innerHTML = Lang.JobTitle + ':';
		WebMail.langChanger.register('innerHTML', td, 'JobTitle', ':');
		td = tr.insertCell(4);
		td.style.width = '30%';
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '30']]);
		this._setInputKeyPress(inp);
		this._bJobTitleObj = inp;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.Department + ':';
		WebMail.langChanger.register('innerHTML', td, 'Department', ':');
		td = tr.insertCell(1);
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '65']]);
		this._setInputKeyPress(inp);
		this._bDepartmentObj = inp;
		td = tr.insertCell(2);
		td = tr.insertCell(3);
		td.className = 'wm_contacts_view_title';
//		td.className = (UseCustomContacts) ? 'wm_hide' : 'wm_contacts_view_title';
		td.innerHTML = Lang.Office + ':';
		WebMail.langChanger.register('innerHTML', td, 'Office', ':');
		td = tr.insertCell(4);
//		td.className = (UseCustomContacts) ? 'wm_hide' : '';
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '65']]);
		this._setInputKeyPress(inp);
		this._bOfficeObj = inp;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.StreetAddress + ':';
		WebMail.langChanger.register('innerHTML', td, 'StreetAddress', ':');
		td = tr.insertCell(1);
		td.colSpan = 4;
		txt = CreateChild(td, 'textarea', [['class', 'wm_input'], ['cols', '35'], ['rows', '2']]);
		txt.onkeydown = this.TextAreaLimit;
		this._bStreetObj = txt;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.City + ':';
		WebMail.langChanger.register('innerHTML', td, 'City', ':');
		td = tr.insertCell(1);
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '65']]);
		this._setInputKeyPress(inp);
		this._bCityObj = inp;
		td = tr.insertCell(2);
		td = tr.insertCell(3);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.Fax + ':';
		WebMail.langChanger.register('innerHTML', td, 'Fax', ':');
		td = tr.insertCell(4);
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '50']]);
		this._setInputKeyPress(inp);
		this._bFaxObj = inp;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.StateProvince + ':';
		WebMail.langChanger.register('innerHTML', td, 'StateProvince', ':');
		td = tr.insertCell(1);
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '65']]);
		this._setInputKeyPress(inp);
		this._bStateObj = inp;
		td = tr.insertCell(2);
		td = tr.insertCell(3);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.Phone + ':';
		WebMail.langChanger.register('innerHTML', td, 'Phone', ':');
		td = tr.insertCell(4);
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '50']]);
		this._setInputKeyPress(inp);
		this._bPhoneObj = inp;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.ZipCode + ':';
		WebMail.langChanger.register('innerHTML', td, 'ZipCode', ':');
		td = tr.insertCell(1);
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '10']]);
		this._setInputKeyPress(inp);
		this._bZipObj = inp;
		td = tr.insertCell(2);
		td = tr.insertCell(3);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.CountryRegion + ':';
		WebMail.langChanger.register('innerHTML', td, 'CountryRegion', ':');
		td = tr.insertCell(4);
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '65']]);
		this._setInputKeyPress(inp);
		this._bCountryObj = inp;

//		tr = tbl.insertRow(rowIndex++);
//		tr.className = (!UseCustomContacts) ? 'wm_hide' : '';
//		td = tr.insertCell(0);
//		td.className = 'wm_contacts_view_title';
//		td.innerHTML = Lang.Mobile + ':';
//		WebMail.langChanger.register('innerHTML', td, 'Mobile', ':');
//		td = tr.insertCell(1);
//		td.colSpan = 4;
//		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '50']]);
//		this._setInputKeyPress(inp);
//		this._bMobileObj = inp;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.WebPage + ':';
		WebMail.langChanger.register('innerHTML', td, 'WebPage', ':');
		td = tr.insertCell(1);
		td.colSpan = 4;
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '45'], ['maxlength', '85']]);
		this._setInputKeyPress(inp);
		this._bWebObj = inp;
		inp = CreateChild(td, 'input', [['type', 'button'], ['class', 'wm_go_button'], ['value', Lang.Go]]);
		WebMail.langChanger.register('value', inp, 'Go', '');
		inp.onclick = function () { OpenURL(obj._bWebObj.value); };

		/*------Other------*/
		
		tabTbl = CreateChild(div, 'table');
		tabTbl.onclick = function () { obj.ChangeTabMode(2); };
		tabTbl.className = 'wm_contacts_tab';
		tr = tabTbl.insertRow(0);
		td = tr.insertCell(0);
		span = CreateChild(td, 'span');
		span.className = 'wm_contacts_tab_name';
		span.innerHTML = Lang.Other;
		WebMail.langChanger.register('innerHTML', span, 'Other', '');
		imgDiv = CreateChild(td, 'div');
		imgDiv.className = 'wm_contacts_tab_open_mode';
		
		tbl = CreateChild(div, 'table');
		this._tabs[2] = new CContactTab(tbl, imgDiv, tabTbl);
		tbl.className = 'wm_hide';
		rowIndex = 0;
		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.Birthday + ':';
		WebMail.langChanger.register('innerHTML', td, 'Birthday', ':');
		td = tr.insertCell(1);
		this._monthObj = CreateChild(td, 'select');
		this._dayObj = CreateChild(td, 'select');
		this._yearObj = CreateChild(td, 'select');
		this.FillMonth(0);
		this.FillDay(0);
		this.FillYear(0);
		
		tr = tbl.insertRow(rowIndex++);
//		tr.className = (UseCustomContacts || UseCustomContacts1) ? 'wm_hide' : '';
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.style.width = '20%';
		td.innerHTML = Lang.OtherEmail + ':';
		WebMail.langChanger.register('innerHTML', td, 'OtherEmail', ':');
		td = tr.insertCell(1);
		td.style.width = '80%';
		td.colSpan = 4;
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '45'], ['maxlength', '255']]);
		this._setInputKeyPress(inp);
		inp.onchange = function () { obj.FillDefaultEmailSel(); };
		this._otherEmailObj = inp;

		tr = tbl.insertRow(rowIndex++);
//		tr.className = (UseCustomContacts) ? 'wm_hide' : '';
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.Notes + ':';
		WebMail.langChanger.register('innerHTML', td, 'Notes', ':');
		td = tr.insertCell(1);
		td.colSpan = 4;
		txt = CreateChild(td, 'textarea', [['class', 'wm_input'], ['cols', '35'], ['rows', '2']]);
		txt.onkeydown = this.TextAreaLimit;
		this._notesObj = txt;

		/*------Groups------*/
		
		tabTbl = CreateChild(div, 'table');
		tabTbl.onclick = function () { obj.ChangeTabMode(3); };
		tabTbl.className = 'wm_contacts_tab';
		tr = tabTbl.insertRow(0);
		td = tr.insertCell(0);
		span = CreateChild(td, 'span');
		span.className = 'wm_contacts_tab_name';
		span.innerHTML = Lang.Groups;
		WebMail.langChanger.register('innerHTML', span, 'Groups', '');
		imgDiv = CreateChild(td, 'div');
		imgDiv.className = 'wm_contacts_tab_open_mode';
		
		tbl = CreateChild(div, 'table');
		this._tabs[3] = new CContactTab(tbl, imgDiv, tabTbl);
		tbl.className = 'wm_hide';
		tr = tbl.insertRow(0);
		td = tr.insertCell(0);
		this._groupsObj = td;

		/*------Buttons------*/

		tbl = CreateChild(container, 'table');
		this._buttonsTbl = tbl;
		tbl.className = 'wm_hide';
		tbl.style.width = '90%';
		tr = tbl.insertRow(0);
		td = tr.insertCell(0);
		/*rtl*/
		td.style.textAlign = window.RIGHT;
		a = CreateChild(td, 'a', [['href', '#']]);
		a.innerHTML = Lang.ShowAddFields;
		WebMail.langChanger.register('innerHTML', a, 'ShowAddFields', '');
		a.onclick = function () { obj.showMoreInfo(); return false; };
		this._showMoreInfo = a;
		tr = tbl.insertRow(1);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_save_button';
		inp = CreateChild(td, 'input', [['type', 'button'], ['class', 'wm_button'], ['value', Lang.Save]]);
		WebMail.langChanger.register('value', inp, 'Save', '');
		inp.onclick = function () { obj.saveChanges(); };
		CreateTextChild(td, ' ');
		inp = CreateChild(td, 'input', [['type', 'button'], ['class', 'wm_button'], ['value', Lang.Cancel]]);
		WebMail.langChanger.register('value', inp, 'Cancel', '');
		inp.onclick = function () { obj.CancelChanges(); };
	}
};

function CSelectedContactsScreenPart(parent)
{
	this._mainContainer = null;
	this._contactsContainer = null;
	this._parent = parent;
	this.shown = false;

	this._contacts = [];
}

CSelectedContactsScreenPart.prototype = 
{
	show: function ()
	{
		this._mainContainer.className = '';
		this.shown = true;
	},
	
	hide: function ()
	{
		this._mainContainer.className = 'wm_hide';
		this.shown = false;
	},
	
	fill: function (contactsArray)
	{
		CleanNode(this._contactsContainer);
		
		var tbl = CreateChild(this._contactsContainer, 'table');
		tbl.className = 'wm_contacts_in_group_lines';
		tbl.style.width = 'auto';
		tbl.style.marginBottom = '0';
		tbl.style.marginTop= '0';
		var rowIndex = 0;
		
		var tr = tbl.insertRow(rowIndex++);
		tr.className = 'wm_contacts_headers';
		var td = tr.insertCell(0);
		td.innerHTML = Lang.Email;
		td = tr.insertCell(1);
		td.innerHTML = Lang.ContactFieldTitle;

		var obj = this;
		this._contacts = [];
		for (var i = 0; i < contactsArray.length; i++) {
			if (contactsArray[i].length === 0) continue;
			tr = tbl.insertRow(rowIndex++);
			tr.className = 'wm_inbox_read_item';
			td = tr.insertCell(0);
			td.innerHTML = contactsArray[i];
			td = tr.insertCell(1);
			this._contacts[i] = { mail: contactsArray[i], type: 1 };
			var select = CreateChild(td, 'select', [['name', i]]);
			var option = CreateChild(select, 'option', [['value', '1']]);
			option.innerHTML = Lang.ContactDropDownTO;
			option = CreateChild(select, 'option', [['value', '2']]);
			option.innerHTML = Lang.ContactDropDownCC;
			option = CreateChild(select, 'option', [['value', '3']]);
			option.innerHTML = Lang.ContactDropDownBCC;
			select.onchange = function() {
				obj._contacts[this.name].type = this.value;
			};
		}
		
		var tblWidth = tbl.offsetWidth;
		if (tblWidth < 250) {
			tbl.style.width = '250px';
		}
	},
	
	MailSelected: function ()
	{
		var emailArray = [[], [], [], []];
		for (var iIndex = 0; iIndex < this._contacts.length; iIndex++) {
			var oContact = this._contacts[iIndex];
			if (oContact === undefined) continue;
			if (oContact.type > 0 && oContact.type < 4) {
				emailArray[oContact.type].push(HtmlDecode(oContact.mail));
			}
		}

		MailAllHandler(emailArray[1].join(', '), emailArray[2].join(', '), emailArray[3].join(', '));
	},

	build: function (container)
	{
		var tbl = CreateChild(container, 'table');
		tbl.style.width = '100%';
		tbl.className = 'wm_hide';
		this._mainContainer = tbl;
		var tr = tbl.insertRow(0);

		var td = tr.insertCell(0);
		td.style.textAlign = 'left';
		this._contactsContainer = td;

		td = tr.insertCell(1);
		td.style.textAlign = window.RIGHT;
		td.style.verticalAlign = 'top';

		var obj = this;
		var div = CreateChild(td, 'span');
		div.className = 'wm_button_link wm_control';
		div.onclick = function () { obj.MailSelected(); };

		var divCh = CreateChild(div, 'span');
		divCh.innerHTML = Lang.ContactsMailThem;
		WebMail.langChanger.register('innerHTML', divCh, 'ContactsMailThem', '');

		div = CreateChild(container, 'div', [['style', 'width: 0; height: 0; padding: 0; overflow: hidden; clear: both;']]);
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}