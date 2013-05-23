/*
 * Prototypes:
 *  ViewContactPrototype
 * Classes:
 *  CContactCard()
 *  CMessageCharsetSelector(_parent)
 *  CAddToAddressBookImg(_parent)
 *  CPreviewPaneMessageHeaders(bInNewWindow)
 */

var ViewContactPrototype = {
	FillDefSection: function (cont)
	{
		if (cont.name.length > 0) {
			this._fullnameObj.innerHTML = cont.name;
			this._fullnameCont.className = '';
		}
		else this._fullnameCont.className = 'wm_hide';
		
		this._contactEmail = (cont.useFriendlyName && cont.name.length > 0)
            ? '"' + HtmlDecode(cont.name) + '" <' + HtmlDecode(cont.email) + '>'
            : HtmlDecode(cont.email);
		
		if (cont.email.length > 0) {
			this._defaultEmailObj.innerHTML = cont.email;
			this._defaultEmailCont.className = '';
		}
		else {
            this._defaultEmailCont.className = 'wm_hide';
        }
		var birthDay = GetBirthDay(cont.day, cont.month, cont.year);
		if (birthDay.length > 0) {
			this._birthdayObj.innerHTML = birthDay;
			this._birthdayCont.className = '';
		}
		else this._birthdayCont.className = 'wm_hide';
//		if (cont.title.length > 0) {
//			this._titleObj.innerHTML = cont.title;
//			this._titleCont.className = '';
//		}
//		else this._titleCont.className = 'wm_hide';
		if (cont.firstName.length > 0) {
			this._firstnameObj.innerHTML = cont.firstName;
			this._firstnameCont.className = '';
		}
		else this._firstnameCont.className = 'wm_hide';
		if (cont.lastName.length > 0) {
			this._lastNameObj.innerHTML = cont.lastName;
			this._lastNameCont.className = '';
		}
		else this._lastNameCont.className = 'wm_hide';
		if (cont.nickName.length > 0) {
			this._nicknameObj.innerHTML = cont.nickName;
			this._nicknameCont.className = '';
		}
		else this._nicknameCont.className = 'wm_hide';
	},

	FillHomeSection: function (cont)
	{
		var emptySection = true;
		if (cont.hEmail.length > 0 && cont.primaryEmail != PRIMARY_HOME_EMAIL) {
			this._contactHEmail = (cont.useFriendlyName && cont.name.length > 0)
				? '"' + cont.name + '" <' + HtmlDecode(cont.hEmail) + '>'
				: HtmlDecode(cont.hEmail);
			this._hEmailObj.innerHTML = cont.hEmail;
			this._hEmailCont.className = '';
			emptySection = false;
		}
		else this._hEmailCont.className = 'wm_hide';
		if (cont.hStreet.length > 0) {
			this._hStreetObj.innerHTML = cont.hStreet;
			this._hStreetCont.className = '';
			emptySection = false;
		}
		else this._hStreetCont.className = 'wm_hide';
		if (cont.hCity.length > 0 || cont.hFax.length > 0) {
			this._hCityObj.innerHTML = cont.hCity;
			this._hCityTitle.innerHTML = (cont.hCity.length > 0) ? Lang.City + ':' : '';
			this._hFaxObj.innerHTML = cont.hFax;
			this._hFaxTitle.innerHTML = (cont.hFax.length > 0) ? Lang.Fax + ':' : '';
			this._hCityFaxCont.className = '';
			emptySection = false;
		}
		else this._hCityFaxCont.className = 'wm_hide';
		if (cont.hState.length > 0 || cont.hPhone.length > 0) {
			this._hStateObj.innerHTML = cont.hState;
			this._hStateTitle.innerHTML = (cont.hState.length > 0) ? Lang.StateProvince + ':' : '';
			this._hPhoneObj.innerHTML = cont.hPhone;
			this._hPhoneTitle.innerHTML = (cont.hPhone.length > 0) ? Lang.Phone + ':' : '';
			this._hStatePhoneCont.className = '';
			emptySection = false;
		}
		else this._hStatePhoneCont.className = 'wm_hide';
		if (cont.hZip.length > 0 || cont.hMobile.length > 0) {
			this._hZipObj.innerHTML = cont.hZip;
			this._hZipTitle.innerHTML = (cont.hZip.length > 0) ? Lang.ZipCode + ':' : '';
			this._hMobileObj.innerHTML = cont.hMobile;
			this._hMobileTitle.innerHTML = (cont.hMobile.length > 0) ? Lang.Mobile + ':' : '';
			this._hZipMobileCont.className = '';
			emptySection = false;
		}
		else this._hZipMobileCont.className = 'wm_hide';
		if (cont.hCountry.length > 0) {
			this._hCountryObj.innerHTML = cont.hCountry;
			this._hCountryCont.className = '';
			emptySection = false;
		}
		else this._hCountryCont.className = 'wm_hide';
		if (cont.hWeb.length > 0) {
			this._contactHWeb = cont.hWeb;
			this._hWebObj.innerHTML = cont.hWeb;
			this._hWebCont.className = '';
			emptySection = false;
		}
		else this._hWebCont.className = 'wm_hide';
		if (emptySection) this._personalTbl.className = 'wm_hide';
		else this._personalTbl.className = this._sectionClassName;
	},

	FillBusinessSection: function (cont)
	{
		var emptySection = true;
		if (cont.bEmail.length > 0 && cont.primaryEmail != PRIMARY_BUS_EMAIL) {
			this._contactBEmail = (cont.useFriendlyName && cont.name.length > 0)
				? '"' + cont.name + '" <' + HtmlDecode(cont.bEmail) + '>'
				: HtmlDecode(cont.bEmail);
			this._bEmailObj.innerHTML = cont.bEmail;
			this._bEmailCont.className = '';
			emptySection = false;
		}
		else this._bEmailCont.className = 'wm_hide';
		if (cont.bCompany.length > 0 || cont.bJobTitle.length > 0) {
			this._bCompanyObj.innerHTML = cont.bCompany;
			this._bCompanyTitle.innerHTML = (cont.bCompany.length > 0) ? Lang.Company + ':' : '';
			this._bJobTitleObj.innerHTML = cont.bJobTitle;
			this._bJobTitleTitle.innerHTML = (cont.bJobTitle.length > 0) ? Lang.JobTitle + ':' : '';
			this._bCompanyJobTitleCont.className = '';
			emptySection = false;
		}
		else this._bCompanyJobTitleCont.className = 'wm_hide';
		if (cont.bDepartment.length > 0 || cont.bOffice.length > 0) {
			this._bDepartmentObj.innerHTML = cont.bDepartment;
			this._bDepartmentTitle.innerHTML = (cont.bDepartment.length > 0) ? Lang.Department + ':' : '';
			this._bOfficeObj.innerHTML = cont.bOffice;
			this._bOfficeTitle.innerHTML = (cont.bOffice.length > 0) ? Lang.Office + ':' : '';
			this._bDepartmentOfficeCont.className = '';
			emptySection = false;
		}
		else this._bDepartmentOfficeCont.className = 'wm_hide';
		if (cont.bStreet.length > 0) {
			this._bStreetObj.innerHTML = cont.bStreet;
			this._bStreetCont.className = '';
			emptySection = false;
		}
		else this._bStreetCont.className = 'wm_hide';
		if (cont.bCity.length > 0 || cont.bFax.length > 0) {
			this._bCityObj.innerHTML = cont.bCity;
			this._bCityTitle.innerHTML = (cont.bCity.length > 0) ? Lang.City + ':' : '';
			this._bFaxObj.innerHTML = cont.bFax;
			this._bFaxTitle.innerHTML = (cont.bFax.length > 0) ? Lang.Fax + ':' : '';
			this._bCityFaxCont.className = '';
			emptySection = false;
		}
		else this._bCityFaxCont.className = 'wm_hide';
		if (cont.bState.length > 0 || cont.bPhone.length > 0) {
			this._bStateObj.innerHTML = cont.bState;
			this._bStateTitle.innerHTML = (cont.bState.length > 0) ? Lang.StateProvince + ':' : '';
			this._bPhoneObj.innerHTML = cont.bPhone;
			this._bPhoneTitle.innerHTML = (cont.bPhone.length > 0) ? Lang.Phone + ':' : '';
			this._bStatePhoneCont.className = '';
			emptySection = false;
		}
		else this._bStatePhoneCont.className = 'wm_hide';
		if (cont.bZip.length > 0 || cont.bCountry.length > 0) {
			this._bZipObj.innerHTML = cont.bZip;
			this._bZipTitle.innerHTML = (cont.bZip.length > 0) ? Lang.ZipCode + ':' : '';
			this._bCountryObj.innerHTML = cont.bCountry;
			this._bCountryTitle.innerHTML = (cont.bCountry.length > 0) ? Lang.CountryRegion + ':' : '';
			this._bZipCountryCont.className = '';
			emptySection = false;
		}
		else this._bZipCountryCont.className = 'wm_hide';
		if (cont.bMobile.length > 0) {
			this._bMobileObj.innerHTML = cont.bMobile;
			this._bMobileTitle.innerHTML = Lang.Mobile + ':';
			this._bMobileCont.className = '';
			emptySection = false;
		}
		else this._bMobileCont.className = 'wm_hide';
		if (cont.bWeb.length > 0) {
			this._contactBWeb = cont.bWeb;
			this._bWebObj.innerHTML = cont.bWeb;
			this._bWebCont.className = '';
			emptySection = false;
		}
		else this._bWebCont.className = 'wm_hide';
		if (emptySection) this._businessTbl.className = 'wm_hide';
		else this._businessTbl.className = this._sectionClassName;
	},

	FillOtherSection: function (cont)
	{
		var emptySection = true;
		if (cont.otherEmail.length > 0 && cont.primaryEmail != PRIMARY_OTHER_EMAIL) {
			this._contactOtherEmail = (cont.useFriendlyName && cont.name.length > 0)
				? '"' + cont.name + '" <' + HtmlDecode(cont.otherEmail) + '>'
				: HtmlDecode(cont.otherEmail);
			this._otherEmailObj.innerHTML = cont.otherEmail;
			this._otherEmailCont.className = '';
			emptySection = false;
		}
		else this._otherEmailCont.className = 'wm_hide';
		if (cont.notes.length > 0) {
			this._notesObj.innerHTML = cont.notes;
			this._notesCont.className = '';
			emptySection = false;
		}
		else this._notesCont.className = 'wm_hide';
		if (emptySection) this._otherTbl.className = 'wm_hide';
		else this._otherTbl.className = this._sectionClassName;
	},

	buildDefSection: function (container)
	{
		var tbl = CreateChild(container, 'table');
		tbl.className = this._sectionClassName;
		tbl.style.marginTop = '0';

		var rowIndex = 0;
		var tr = tbl.insertRow(rowIndex++);
		var td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.ContactDisplayName + ':';
		WebMail.langChanger.register('innerHTML', td, 'ContactDisplayName', ':');
		td = tr.insertCell(1);
		td.className = this._nameClassName;
		this._fullnameObj = td;
		this._fullnameCont = tr;

//		tr = tbl.insertRow(rowIndex++);
//		td = tr.insertCell(0);
//		td.className = this._titleClassName;
//		td.innerHTML = Lang.ContactTitle + ':';
//		WebMail.langChanger.register('innerHTML', td, 'ContactTitle', ':');
//		td = tr.insertCell(1);
//		td.className = this._nameClassName;
//		this._titleObj = td;
//		this._titleCont = tr;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.ContactFirstName + ':';
		WebMail.langChanger.register('innerHTML', td, 'ContactFirstName', ':');
		td = tr.insertCell(1);
		td.className = this._nameClassName;
		this._firstnameObj = td;
		this._firstnameCont = tr;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.ContactSurName + ':';
		WebMail.langChanger.register('innerHTML', td, 'ContactSurName', ':');
		td = tr.insertCell(1);
		td.className = this._nameClassName;
		this._lastNameObj = td;
		this._lastNameCont = tr;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.ContactNickName + ':';
		WebMail.langChanger.register('innerHTML', td, 'ContactNickName', ':');
		td = tr.insertCell(1);
		td.className = this._nameClassName;
		this._nicknameObj = td;
		this._nicknameCont = tr;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.Email + ':';
		WebMail.langChanger.register('innerHTML', td, 'Email', ':');
		td = tr.insertCell(1);
		td.className = this._emailClassName;
		var a = CreateChild(td, 'a', [['href', '#']]);
		this._defaultEmailObj = a;
		this._defaultEmailCont = tr;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.Birthday + ':';
		WebMail.langChanger.register('innerHTML', td, 'Birthday', ':');
		td = tr.insertCell(1);
		this._birthdayObj = td;
		this._birthdayCont = tr;
	},

	buildHomeSection: function (container)
	{
		var tbl = CreateChild(container, 'table');
		this._personalTbl = tbl;
		tbl.className = 'wm_hide';
		var rowIndex = 0;
		var tr = tbl.insertRow(rowIndex++);
		var td = tr.insertCell(0);
		td.className = this._sectionNameClassName;
		td.colSpan = 4;
		td.innerHTML = Lang.Home;
		WebMail.langChanger.register('innerHTML', td, 'Home', '');

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.PersonalEmail + ':';
		WebMail.langChanger.register('innerHTML', td, 'PersonalEmail', ':');
		td = tr.insertCell(1);
		td.colSpan = 3;
		var a = CreateChild(td, 'a', [['href', '#']]);
		a.onclick = function () {return false;};
		this._hEmailObj = a;
		this._hEmailCont = tr;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.StreetAddress + ':';
		WebMail.langChanger.register('innerHTML', td, 'StreetAddress', ':');
		td = tr.insertCell(1);
		td.colSpan = 3;
		this._hStreetObj = td;
		this._hStreetCont = tr;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.City + ':';
		this._hCityTitle = td;
		td = tr.insertCell(1);
		this._hCityObj = td;
		td = tr.insertCell(2);
		td.className = this._titleClassName;
		td.innerHTML = Lang.Fax + ':';
		this._hFaxTitle = td;
		td = tr.insertCell(3);
		this._hFaxObj = td;
		this._hCityFaxCont = tr;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.StateProvince + ':';
		this._hStateTitle = td;
		td = tr.insertCell(1);
		this._hStateObj = td;
		td = tr.insertCell(2);
		td.className = this._titleClassName;
		td.innerHTML = Lang.Phone + ':';
		this._hPhoneTitle = td;
		td = tr.insertCell(3);
		this._hPhoneObj = td;
		this._hStatePhoneCont = tr;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.ZipCode + ':';
		this._hZipTitle = td;
		td = tr.insertCell(1);
		this._hZipObj = td;
		td = tr.insertCell(2);
		td.className = this._titleClassName;
		td.innerHTML = Lang.Mobile + ':';
		this._hMobileTitle = td;
		td = tr.insertCell(3);
		this._hMobileObj = td;
		this._hZipMobileCont = tr;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.CountryRegion + ':';
		WebMail.langChanger.register('innerHTML', td, 'CountryRegion', ':');
		td = tr.insertCell(1);
		td.colSpan = 3;
		this._hCountryObj = td;
		this._hCountryCont = tr;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.WebPage + ':';
		WebMail.langChanger.register('innerHTML', td, 'WebPage', ':');
		td = tr.insertCell(1);
		td.colSpan = 3;
		a = CreateChild(td, 'a', [['href', '#']]);
		this._hWebObj = a;
		this._hWebCont = tr;
	},

	buildBusinessSection: function (container)
	{
		var tbl = CreateChild(container, 'table');
		this._businessTbl = tbl;
		tbl.className = 'wm_hide';
		var rowIndex = 0;
		var tr = tbl.insertRow(rowIndex++);
		var td = tr.insertCell(0);
		td.className = this._sectionNameClassName;
		td.colSpan = 4;
		td.innerHTML = Lang.Business;
		WebMail.langChanger.register('innerHTML', td, 'Business', '');

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.BusinessEmail + ':';
		WebMail.langChanger.register('innerHTML', td, 'BusinessEmail', ':');
		td = tr.insertCell(1);
		td.colSpan = 3;
		var a = CreateChild(td, 'a', [['href', '#']]);
		a.onclick = function () {return false;};
		this._bEmailObj = a;
		this._bEmailCont = tr;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.Company + ':';
		this._bCompanyTitle = td;
		td = tr.insertCell(1);
		this._bCompanyObj = td;
		td = tr.insertCell(2);
		td.className = this._titleClassName;
		td.innerHTML = Lang.JobTitle + ':';
		this._bJobTitleTitle = td;
		td = tr.insertCell(3);
		this._bJobTitleObj = td;
		this._bCompanyJobTitleCont = tr;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.Department + ':';
		this._bDepartmentTitle = td;
		td = tr.insertCell(1);
		this._bDepartmentObj = td;
		td = tr.insertCell(2);
		td.className = this._titleClassName;
		td.innerHTML = Lang.Office + ':';
		this._bOfficeTitle = td;
		td = tr.insertCell(3);
		this._bOfficeObj = td;
		this._bDepartmentOfficeCont = tr;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.StreetAddress + ':';
		WebMail.langChanger.register('innerHTML', td, 'StreetAddress', ':');
		td = tr.insertCell(1);
		td.colSpan = 3;
		this._bStreetObj = td;
		this._bStreetCont = tr;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.City + ':';
		this._bCityTitle = td;
		td = tr.insertCell(1);
		this._bCityObj = td;
		td = tr.insertCell(2);
		td.className = this._titleClassName;
		td.innerHTML = Lang.Fax + ':';
		this._bFaxTitle = td;
		td = tr.insertCell(3);
		this._bFaxObj = td;
		this._bCityFaxCont = tr;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.StateProvince + ':';
		this._bStateTitle = td;
		td = tr.insertCell(1);
		this._bStateObj = td;
		td = tr.insertCell(2);
		td.className = this._titleClassName;
		td.innerHTML = Lang.Phone + ':';
		this._bPhoneTitle = td;
		td = tr.insertCell(3);
		this._bPhoneObj = td;
		this._bStatePhoneCont = tr;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.ZipCode + ':';
		this._bZipTitle = td;
		td = tr.insertCell(1);
		this._bZipObj = td;
		td = tr.insertCell(2);
		td.className = this._titleClassName;
		td.innerHTML = Lang.CountryRegion + ':';
		this._bCountryTitle = td;
		td = tr.insertCell(3);
		this._bCountryObj = td;
		this._bZipCountryCont = tr;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.Mobile + ':';
		WebMail.langChanger.register('innerHTML', td, 'Mobile', ':');
		this._bMobileTitle = td;
		td = tr.insertCell(1);
		td.colSpan = 3;
		this._bMobileObj = td;
		this._bMobileCont = tr;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.WebPage + ':';
		WebMail.langChanger.register('innerHTML', td, 'WebPage', ':');
		td = tr.insertCell(1);
		td.colSpan = 3;
		a = CreateChild(td, 'a', [['href', '#']]);
		this._bWebObj = a;
		this._bWebCont = tr;
	},

	buildOtherSection: function (container)
	{
		var tbl = CreateChild(container, 'table');
		this._otherTbl = tbl;
		tbl.className = 'wm_hide';
		var rowIndex = 0;
		var tr = tbl.insertRow(rowIndex++);
		var td = tr.insertCell(0);
		td.className = this._sectionNameClassName;
		td.colSpan = 2;
		td.innerHTML = Lang.Other;
		WebMail.langChanger.register('innerHTML', td, 'Other', '');

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.OtherEmail + ':';
		WebMail.langChanger.register('innerHTML', td, 'OtherEmail', ':');
		td = tr.insertCell(1);
		var a = CreateChild(td, 'a', [['href', '#']]);
		a.onclick = function () {return false;};
		this._otherEmailObj = a;
		this._otherEmailCont = tr;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = this._titleClassName;
		td.innerHTML = Lang.Notes + ':';
		WebMail.langChanger.register('innerHTML', td, 'Notes', ':');
		td = tr.insertCell(1);
		this._notesObj = td;
		this._notesCont = tr;
	}
};

function CContactCard()
{
	this._contact = null;
	this._contactEmail = '';
	this._contactHEmail = '';
	this._contactBEmail = '';
	this._contactOtherEmail = '';
	this._contactHWeb = '';
	this._contactBWeb = '';

	this._mainCont = null;

	this._fullnameObj = null;
	this._fullnameCont = null;
	this._defaultEmailObj = null;
	this._defaultEmailCont = null;
	this._birthdayObj = null;
	this._birthdayCont = null;

	this._builded = false;
	this._pageX = 0;
	this._pageY = 0;

	this._sectionClassName = '';
	this._titleClassName = 'wm_line_title';
	this._nameClassName = '';
	this._emailClassName = '';
	this._sectionNameClassName = 'wm_section_name';

	this._showTimeOut = Math.NaN;
	this._shown = false;

	this.SetContact = function (contact)
	{
		this._contact = contact;
	};

	this.go = function ()
	{
		SetHistoryHandler(
			{
				ScreenId: SCREEN_CONTACTS,
				Entity: PART_VIEW_CONTACT,
				sContactId: ContactCard._contact.sContactId,
				idAcct: WebMail.iAcctId
			}
		);
		return false;
	};

	this.show = function (e)
	{
		ContactCard.ClearTimeOut();
		if (ContactCard._shown) {
			return;
		}
		ContactCard._build();

		e = e ? e : window.event;
		ContactCard._pageX = e.clientX;
		ContactCard._pageY = e.clientY;
		if (Browser.mozilla) {
			ContactCard._pageX = e.pageX;
			ContactCard._pageY = e.pageY;
		}
		if (Browser.opera) {
			ContactCard._pageX += document.documentElement.scrollLeft - document.documentElement.clientLeft;
			ContactCard._pageY += document.documentElement.scrollTop - document.documentElement.clientTop;
		}
		ContactCard._showTimeOut = setTimeout('ContactCard._show()', 200);
	};

	this._show = function ()
	{
		ContactCard._mainCont.className = 'wm_contact_card';
		ContactCard.FillDefSection(ContactCard._contact);
		ContactCard.FillHomeSection(ContactCard._contact);
		ContactCard.FillBusinessSection(ContactCard._contact);
		ContactCard.FillOtherSection(ContactCard._contact);

		ContactCard._mainCont.style.left = ContactCard._pageX + 10 + 'px';
		ContactCard._mainCont.style.top = ContactCard._pageY + 10 + 'px';
		ContactCard._mainCont.style.backgroundPosition = '0 ' + (ContactCard._mainCont.offsetHeight - 50) + 'px';
		ContactCard._shown = true;
	};

	this.hide = function ()
	{
		ContactCard.ClearTimeOut();
		ContactCard._showTimeOut = setTimeout('ContactCard._hide()', 400);
	};

	this.ClearTimeOut = function ()
	{
		if (!isNaN(ContactCard._showTimeOut)) {
			clearTimeout(ContactCard._showTimeOut);
			ContactCard._showTimeOut = Math.NaN;
		}
	};

	this._hide = function ()
	{
		ContactCard.ClearTimeOut();
		ContactCard._mainCont.className = 'wm_hide';
		ContactCard._shown = false;
	};

	this._mailTo = function (email)
	{
		if (ContactCard._contact != null) {
			MailToHandler(email);
		}
		ContactCard._hide();
	};

	this._openUrl = function (url)
	{
		if (ContactCard._contact != null) {
			OpenURL(url);
		}
		ContactCard._hide();
	};

	this._build = function ()
	{
		if (this._builded) {
			return;
		}

		this._mainCont = CreateChild(document.body, 'table', [['class', 'wm_contact_card']]);
		this._mainCont.onmouseover = ContactCard.show;
		this._mainCont.onmouseout = ContactCard.hide;
		var tr = this._mainCont.insertRow(0);
		var td = tr.insertCell(0);
		td.style.textAlign = 'left';
		var a = CreateChild(td, 'a', [['href', '#']]);
		a.onclick = function () {
			ContactCard._hide();
			ContactCard._mailTo(ContactCard._contactEmail);
			return false;
		};
		a.innerHTML = Lang.ContactMail;
		WebMail.langChanger.register('innerHTML', a, 'ContactMail', '');

		td = tr.insertCell(1);
		td.style.textAlign = 'right';
		a = CreateChild(td, 'a', [['href', '#']]);
		a.onclick = function () {
			if (ContactCard._contact != null) {
				ContactCard._hide();
				ViewAllContactMailsHandler(ContactCard._contact);
			}
			return false;
		};
		a.innerHTML = Lang.ContactViewAllMails;
//		a.className = (UseCustomContacts) ? 'wm_hide' : '';
		WebMail.langChanger.register('innerHTML', a, 'ContactViewAllMails', '');

		tr = this._mainCont.insertRow(1);
		td = tr.insertCell(0);
		td.colSpan = 2;
		td.className = 'wm_view_sections';
		this.buildDefSection(td);
		this.buildHomeSection(td);
		this.buildBusinessSection(td);
		this.buildOtherSection(td);

		this._defaultEmailObj.onclick = function () {
			ContactCard._mailTo(ContactCard._contactEmail);
			return false;
		};
		this._hEmailObj.onclick = function () {
			ContactCard._mailTo(ContactCard._contactHEmail);
			return false;
		};
		this._bEmailObj.onclick = function () {
			ContactCard._mailTo(ContactCard._contactBEmail);
			return false;
		};
		this._otherEmailObj.onclick = function () {
			ContactCard._mailTo(ContactCard._contactOtherEmail);
			return false;
		};
		this._hWebObj.onclick = function () {
			ContactCard._openUrl(ContactCard._contactHWeb);
			return false;
		};
		this._bWebObj.onclick = function () {
			ContactCard._openUrl(ContactCard._contactBWeb);
			return false;
		};
		this._builded = true;
	};
}
CContactCard.prototype = ViewContactPrototype;
var ContactCard = new CContactCard();

function CAddToAddressBookImg(parent)
{
	this._fromName = '';
	this._fromEmail = '';
	this._img = null;
	this._parent = parent;

	this.show = function (name, email)
	{
		this._fromName = name;
		this._fromEmail = email;
		this._img.className = 'wm_add_address_book_img';
	};

	this.hide = function ()
	{
		this._img.className = 'wm_hide';
	};

	this.getWidth = function ()
	{
		return this._parent.clientWidth;
	};

	this._build = function ()
	{
		var img, obj;
		img = CreateChild(this._parent, 'span', [['class', 'wm_hide'], ['style', 'margin: 0 0 0 1px'], ['title', Lang.AddToAddressBook]]);
		img.innerHTML = '&nbsp;';
		WebMail.langChanger.register('title', img, 'AddToAddressBook', '');
		obj = this;
		img.onclick = function () {
			if (obj._fromName.length == 0 && obj._fromEmail.length == 0) {
				return;
			}
			SetHistoryHandler(
				{
					ScreenId: SCREEN_CONTACTS,
					Entity: PART_NEW_CONTACT,
					name: obj._fromName,
					email: obj._fromEmail
				}
			);
		};
		this._img = img;
	};

	this._build();
}

function CMessageCharsetSelector(_parent)
{
	this._hasCharset = true;
	this._charset = AUTOSELECT_CHARSET;
	this._size = 0;

	this.show = function ()
	{
		this.fill(this._hasCharset, this._charset, this._size);
	};

	this.hide = function ()
	{
		_parent.innerHTML = '';
	};

	this.GetWidth = function ()
	{
		return _parent.clientWidth;
	};

	this.fill = function (hasCharset, charset, size)
	{
		this._hasCharset = hasCharset;
		this._charset = charset;
		this._size = size;
		_parent.innerHTML = '';
		if (this._hasCharset && this._charset == AUTOSELECT_CHARSET) {
			return false;
		}

		var font = CreateChild(_parent, 'font');
		font.innerHTML = Lang.Charset + ':';

		var sel = CreateChild(_parent, 'select');
		sel.onchange = function () {
			if (WebMail.ScreenId == WebMail.listScreenId) {
				var screen = WebMail.Screens[WebMail.ScreenId];
				if (screen) {
					var historyObj = screen.GetCurrMessageHistoryObject();
					historyObj.MsgCharset = this.value;
					historyObj.MsgSize = size;
					SetHistoryHandler(historyObj);
				}
			}
		};
		for (var i = 0; i < Charsets.length; i++) {
			var value = (Charsets[i].value == 0) ? AUTOSELECT_CHARSET : Charsets[i].value;
			var opt = CreateChild(sel, 'option', [['value', value]]);
			opt.innerHTML = Charsets[i].name;
			opt.selected = (charset == value);
		}
		sel.blur();
		return true;
	};
}

function CPreviewPaneMessageHeaders(bInNewWindow)
{
	this.bInNewWindow = bInNewWindow;
	this._container = null;
	this._contPadding = 12;
	this._spanMargin = 8;

	this._shortLines = [];
	this._fullLines = [];

	this._subjCont = null;
	this._openInNewWindowCont = null;
	this._fullHeadersSwitcherCont = null;
	this._showDetailsCont = null;
	this._hideDetailsCont = null;

	this._shortFromCont = null;
	this._shortAddToABObj = null;
	this._shortDateCont = null;

	this._fromCont = null;
	this.SwitcherCont = null;
	this.SwitcherObj = null;
	this._addToABObj = null;
	this._toCont = null;
	this._toLine = null;
	this._toLineClassName = '';
	this._dateCont = null;
	this._charsetSelector = null;
	this._charsetLine = null;
	this._ccCont = null;
	this._bccCont = null;
	this._copiesCont = null;

	this._shown = false;
	this._hasCopies = false;
	this._showFull = false;
}

CPreviewPaneMessageHeaders.prototype = {
	showShort: function (showDetailsSwitcher)
	{
		for (var shortIndex = 0; shortIndex < this._shortLines.length; shortIndex++) {
			this._shortLines[shortIndex].className = '';
		}
		for (var fullIndex = 0; fullIndex < this._fullLines.length; fullIndex++) {
			this._fullLines[fullIndex].className = 'wm_hide';
		}
		this._toLine.className = 'wm_hide';
		this._copiesCont.className = 'wm_hide';
		if (showDetailsSwitcher) {
			this._showDetailsCont.className = 'wm_message_right';
		}
		this._showFull = false;
	},

	showFull: function ()
	{
		for (var shortIndex = 0; shortIndex < this._shortLines.length; shortIndex++) {
			this._shortLines[shortIndex].className = 'wm_hide';
		}
		for (var fullIndex = 0; fullIndex < this._fullLines.length; fullIndex++) {
			this._fullLines[fullIndex].className = '';
		}
		this._toLine.className = this._toLineClassName;
		this._copiesCont.className = (this._hasCopies) ? '' : 'wm_hide';
		this._showFull = true;
	},

	SwitchDetails: function ()
	{
		if (this._showFull) {
			this.showShort(true);
		}
		else {
			this.showFull();
		}
		this.resize(this._width);
		if (this.bInNewWindow) {
			WebMail.resizeBody();
		}
		else if (WebMail.ScreenId == WebMail.listScreenId) {
			var screen = WebMail.Screens[WebMail.ScreenId];
			if (screen) screen.resizeScreen(RESIZE_MODE_MSG_HEIGHT);
		}
	},

	getHeight: function ()
	{
		return this._container.offsetHeight;
	},

	_fillSubject: function (msg)
	{
		var sSubj = (msg.subject == '')
			? '<span class="wm_no_subject">' + Lang.MessageNoSubject + '</span>'
			: msg.subject;
		if (msg.importance == PRIORITY_HIGH) {
			sSubj = '<span class="wm_importance_img"> </span>' + sSubj;
		}
		if (msg.oAppointment !== null) {
			sSubj = '<font style="font-weight: normal">' + Lang.AppointmentInvitation + ':</font>' + sSubj;
		}
		this._subjCont.innerHTML = sSubj;
	},

	_fillCharset: function (msg)
	{
		var shown = this._charsetSelector.fill(msg.hasCharset, msg.charset, msg.size);
		this._charsetLine.className = (shown) ? '' : 'wm_hide';
	},

	_fillFrom: function (msg, fromDisplay)
	{
		var
			sShortFrom = '<span class="wm_message_from">' + fromDisplay + '</span>',
			sCompleteFrom = '<font>' + Lang.From + ':</font>' + '<span class="wm_message_from">' + msg.fromAddr + '</span>'
		;
		this._fromCont.innerHTML = sCompleteFrom;
		this._shortFromCont.innerHTML = (msg.oAppointment !== null) ? sCompleteFrom : sShortFrom;
		if (WebMail.Settings.allowContacts && msg.fromAddr.length > 0) {
			//email parts for adding to contacts
			var fromParts = GetEmailParts(HtmlDecode(msg.fromAddr));
			this._addToABObj.show(fromParts.name, fromParts.email);
			this._shortAddToABObj.show(fromParts.name, fromParts.email);
		}
		else {
			this._addToABObj.hide();
			this._shortAddToABObj.hide();
		}
	},

	_fillFromWithContact: function (msg, fromDisplay, contact)
	{
		var font = null;

		ContactCard.SetContact(contact);

		this._shortFromCont.innerHTML = '';
		if (msg.oAppointment !== null) {
			font = CreateChild(this._shortFromCont, 'font');
			font.innerHTML = Lang.From + ':';
		}
		var a = CreateChild(this._shortFromCont, 'a', [['href', '#'], ['class', 'wm_message_from'],
			['style', 'text-decoration: underline;']]);
		a.onclick = ContactCard.go;
		if (contact.type == TYPE_CONTACT) {
			a.onmouseover = ContactCard.show;
			a.onmouseout = ContactCard.hide;
		}
		a.innerHTML = fromDisplay;
		this._shortAddToABObj.hide();

		this._fromCont.innerHTML = '';
		font = CreateChild(this._fromCont, 'font');
		font.innerHTML = Lang.From + ':';
		a = CreateChild(this._fromCont, 'a', [['href', '#'], ['class', 'wm_message_from'],
			['style', 'text-decoration: underline;']]);
		a.onclick = ContactCard.go;
		if (contact.type == TYPE_CONTACT) {
			a.onmouseover = ContactCard.show;
			a.onmouseout = ContactCard.hide;
		}
		a.innerHTML = msg.fromAddr;
		this._addToABObj.hide();
	},

	_fillShortToAndDate: function (msg)
	{
		var strTo = msg.toAddr;
		if (msg.ccAddr.length > 0) {
			strTo += ', ' + msg.ccAddr;
		}
		var title = strTo.replace(/"/g, '&quot;');

		var maxToLength = 65;
		var currPos, nextPos;
		if (strTo.length > maxToLength) {
			currPos = strTo.indexOf(',');
			nextPos = currPos;
			do {
				currPos = nextPos;
				nextPos = strTo.indexOf(',', currPos + 1);
			} while (nextPos <= maxToLength && nextPos != -1);
			if (nextPos > maxToLength || nextPos == -1) {
				strTo = strTo.substr(0, currPos) + '...';
			}
		}
		var innerHtml = '';

		if (msg.oAppointment === null && msg.toAddr) {
			innerHtml += Lang.MessageForAddr + ' <span class="wm_message_from" title="' + title + '">' + strTo + '</span>';
		}
		if (msg.date) {
			innerHtml += ' (' + msg.getShortDate() + ')';
		}
		this._shortDateCont.innerHTML = innerHtml;
	},

	_fillFullToAndDate: function (msg)
	{
		var toAddr = (msg.toAddr) ? msg.toAddr : '';
		if (toAddr == '') {
			this._toLineClassName = 'wm_hide';
		}
		else {
			this._toCont.innerHTML = '<font>' + Lang.To + ':</font>' + toAddr;
			this._toCont.title = toAddr.replace(/&lt;/g, '<').replace(/&gt;/g, '>');
			this._toLineClassName = '';
		}
		this._dateCont.innerHTML = '<font>' + Lang.Date + ':</font>' + ((msg.fullDate) ? msg.fullDate : '');
	},

	_fillCopies: function (msg)
	{
		this._hasCopies = false;
		if (msg.ccAddr) {
			this._ccCont.innerHTML = '<font>' + Lang.CC + ':</font>' + msg.ccAddr;
			this._hasCopies = true;
		}
		if (msg.bccAddr) {
			this._bccCont.innerHTML = '<font>' + Lang.BCC + ':</font>' + msg.bccAddr;
			this._hasCopies = true;
		}
	},

	hide: function ()
	{
		this._container.className = 'wm_hide';
	},

	fill: function (msg, contact)
	{
		this._container.className = 'wm_message_headers';
		if (!this.bInNewWindow) {
			this._openInNewWindowCont.className = 'wm_message_right wm_open_in_new_window_control';
			this._openInNewWindowCont.onclick = function () {
				ViewMessageInNewWindow(msg, 'view');
			};
		}
		else {
			this._fillFullHeaders(msg.fullHeaders);
			this._fullHeadersSwitcherCont.className = 'wm_message_right';
		}
		this._fillSubject(msg);
		this._fillCharset(msg);
		var fromDisplay = (msg.fromDisplayName.length > 0) ? msg.fromDisplayName : msg.fromAddr;
		if (contact == null || contact.sContactId.length === 0) {
			this._fillFrom(msg, fromDisplay);
		}
		else {
			this._fillFromWithContact(msg, fromDisplay, contact);
		}
		this._fillShortToAndDate(msg);
		this._fillFullToAndDate(msg);
		this._fillCopies(msg);
		this.showShort(true);
	},

	clean: function ()
	{
		this._container.className = 'wm_message_headers';
		this._subjCont.innerHTML = '';
		this._openInNewWindowCont.className = 'wm_hide';
		this._fullHeadersSwitcherCont.className = 'wm_hide';
		this.hideFullHeaders();
		this._showDetailsCont.className = 'wm_hide';

		this._shortFromCont.innerHTML = '';
		this._shortAddToABObj.hide();
		this._shortDateCont.innerHTML = '';

		this._fromCont.innerHTML = '';
		this.SwitcherCont.className = 'wm_hide';
		this._addToABObj.hide();
		this._toCont.innerHTML = '';
		this._dateCont.innerHTML = '';
		this._charsetSelector.hide();
		this._ccCont.innerHTML = '';
		this._bccCont.innerHTML = '';
		this._copiesCont.className = 'wm_hide';

		this.showShort(false);
	},

	_getSpanWidth: function (span)
	{
		var width = span.clientWidth;
		width += (width == 0) ? 0 : this._spanMargin;
		return width;
	},

	resize: function (inboxWidth)
	{
		this._width = inboxWidth;

		var subjWidth = inboxWidth - this._spanMargin - this._contPadding - 1;
		if (this.bInNewWindow) {
			var fullHeadersSwitcherWidth = this._getSpanWidth(this._fullHeadersSwitcherCont);
			subjWidth -= fullHeadersSwitcherWidth;
		}
		else {
			var openInNewWindowWidth = this._getSpanWidth(this._openInNewWindowCont);
			subjWidth -= openInNewWindowWidth;
		}
		subjWidth = Validator.correctNumber(subjWidth, 100);
		this._subjCont.style.width = subjWidth + 'px';

		if (this._showFull) {
			this.resizeFull(inboxWidth);
		}
		else {
			this.resizeShort(inboxWidth);
		}
	},

	resizeShort: function (inboxWidth)
	{
		var addrBookWidth = this._shortAddToABObj.getWidth();
		var showDetailsWidth = this._getSpanWidth(this._showDetailsCont);
		var maxFromDateWidth = inboxWidth - addrBookWidth - showDetailsWidth
			- (this._spanMargin / 2) - this._contPadding - 1;
		maxFromDateWidth = Validator.correctNumber(maxFromDateWidth, 100);
		var halfFromDateWidth = Math.floor(maxFromDateWidth/2);

		this._shortDateCont.style.width = 'auto';
		this._shortFromCont.style.width = 'auto';
		var dateWidth = this._getSpanWidth(this._shortDateCont);
		var fromWidth = this._getSpanWidth(this._shortFromCont);

		if (fromWidth < halfFromDateWidth) {
			this._shortDateCont.style.width = (maxFromDateWidth - fromWidth - this._spanMargin) + 'px';
		}
		else if (dateWidth < halfFromDateWidth) {
			this._shortFromCont.style.width = (maxFromDateWidth - dateWidth - this._spanMargin) + 'px';
		}
		else {
			this._shortDateCont.style.width = (halfFromDateWidth - this._spanMargin) + 'px';
			this._shortFromCont.style.width = (halfFromDateWidth - this._spanMargin) + 'px';
		}
	},

	resizeFull: function (inboxWidth)
	{
		var addrBookWidth = this._addToABObj.getWidth();
		var hideDetailsWidth = this._getSpanWidth(this._hideDetailsCont);
		var maxFromWidth = inboxWidth - addrBookWidth - hideDetailsWidth - 5 - this._contPadding;
		maxFromWidth = Validator.correctNumber(maxFromWidth, 100);
		this._fromCont.style.width = 'auto';
		var fromWidth = this._fromCont.clientWidth;
		if (fromWidth > maxFromWidth) {
			this._fromCont.style.width = maxFromWidth + 'px';
		}

		var switcherWidth = this._getSpanWidth(this.SwitcherCont);
		var maxDateWidth = inboxWidth - switcherWidth - 8 - this._contPadding;
		maxDateWidth = Validator.correctNumber(maxDateWidth, 100);
		this._dateCont.style.width = 'auto';
		var dateWidth = this._dateCont.clientWidth;
		if (dateWidth > maxDateWidth) {
			this._dateCont.style.width = maxDateWidth + 'px';
		}

		if (this._hasCopies) {
			if (this._ccCont.innerHTML == '') {
				this._bccCont.style.width = inboxWidth - this._spanMargin - this._contPadding + 'px';
			}
			else if (this._bccCont.innerHTML == '') {
				this._ccCont.style.width = inboxWidth - this._spanMargin - this._contPadding + 'px';
			}
			else {
				var halfWidth = Math.ceil(inboxWidth / 2) - this._spanMargin - (this._contPadding / 2);
				this._ccCont.style.width = 'auto';
				this._bccCont.style.width = 'auto';
				var ccWidth = this._ccCont.clientWidth;
				var bccWidth = this._bccCont.clientWidth;
				if ((ccWidth + bccWidth) > halfWidth * 2) {
					if (ccWidth > halfWidth) {
						if (bccWidth > halfWidth) {
							this._ccCont.style.width = halfWidth + 'px';
							this._bccCont.style.width = halfWidth + 'px';
						} else {
							this._ccCont.style.width = halfWidth * 2 - bccWidth + 'px';
						}
					} else if (bccWidth > halfWidth) {
						this._bccCont.style.width = halfWidth * 2 - ccWidth + 'px';
					}
				}
			}
		}
	},

	_addClearDiv: function (cont)
	{
		var styles = 'width: 0; height: 0; padding: 0; overflow: hidden; clear: both;';
		CreateChild(cont, 'div', [['style', styles]]);
	},

	_addSwitchDetailsCont: function (cont, show)
	{
		var switchDetailsCont = CreateChild(cont, 'span');
		var a = CreateChild(switchDetailsCont, 'a', [['href', '#']]);
		var obj = this;
		a.onclick = function () {
			obj.SwitchDetails();
			return false;
		};
		if (show) {
			switchDetailsCont.className = 'wm_hide';
			a.innerHTML = Lang.MessageShowDetails;
			WebMail.langChanger.register('innerHTML', a, 'MessageShowDetails', '');
			this._showDetailsCont = switchDetailsCont;
		}
		else {
			switchDetailsCont.className = 'wm_message_right';
			a.innerHTML = Lang.MessageHideDetails;
			WebMail.langChanger.register('innerHTML', a, 'MessageHideDetails', '');
			this._hideDetailsCont = switchDetailsCont;
		}
	},

	hideFullHeaders: function ()
	{
		this._fullHeadersSwitcherLink.innerHTML = Lang.ShowFullHeaders;
		this._headersCont.className = 'wm_hide';
		this._fullHeadersShown = false;
	},

	_fillFullHeaders: function (fullHeaders)
	{
		if (Browser.ie) {
			this._headersObj.innerText = fullHeaders;
		}
		else {
			this._headersObj.textContent = fullHeaders;
		}
	},

	_showFullHeaders: function ()
	{
		this._fullHeadersSwitcherLink.innerHTML = Lang.HideFullHeaders;
		var height = GetHeight();
		var width = GetWidth();
		var win_height = height * 3 / 5;
		var win_width = width * 3 / 5;
		this._headersCont.style.width = win_width + 'px';
		this._headersCont.style.height = win_height + 'px';
		this._headersCont.style.top = (height - win_height) / 2 + 'px';
		this._headersCont.style.left = (width - win_width) / 2 + 'px';
		this._headersDiv.style.width = win_width - 10 + 'px';
		this._headersDiv.style.height = win_height - 30 + 'px';
		this._headersCont.className = 'wm_headers';
		this._fullHeadersShown = true;
	},

	SwitchFullHeaders: function ()
	{
		if (this._fullHeadersShown) {
			this.hideFullHeaders();
		}
		else {
			this._showFullHeaders();
		}
	},

	_buildFullHeadersPane: function ()
	{
		this._headersCont = CreateChild(document.body, 'div', [['class', 'wm_hide']]);
		this._headersDiv = CreateChild(this._headersCont, 'div', [['class', 'wm_message_rfc822']]);
		this._headersObj = CreateChild(this._headersDiv, 'pre');
		var closeDiv = CreateChild(this._headersCont, 'div', [['class', 'wm_hide_headers']]);
		var a = CreateChild(closeDiv, 'a', [['href', '#']]);
		var obj = this;
		a.onclick = function () {
			obj.hideFullHeaders();
			return false;
		};
		a.innerHTML = Lang.Close;
		WebMail.langChanger.register('innerHTML', a, 'Close', '');
	},

	build: function (parent)
	{
		var cont = CreateChild(parent, 'div', [['class', 'wm_message_headers']]);
		cont.style.padding = (this._contPadding / 2) + 'px';
		cont.style.paddingTop = '4px';
		cont.style.paddingBottom = '4px';
		this._container = cont;

		var div = CreateChild(cont, 'div', [['style', 'overflow: visible']]);
		this._subjCont = CreateChild(div, 'span', [
			['style', 'font-size: 14px; font-weight: bold; white-space: normal;'],
			['class', 'wm_message_left']
		]);
		this._openInNewWindowCont = CreateChild(div, 'span', [['class', 'wm_hide']]);
		this._openInNewWindowCont.title = Lang.AltOpenInNewWindow;
		this._buildFullHeadersPane();
		this._fullHeadersSwitcherCont = CreateChild(div, 'span');
		this._fullHeadersSwitcherCont.className = (this.bInNewWindow)
			? 'wm_message_right'
			: 'wm_hide';
		var a = CreateChild(this._fullHeadersSwitcherCont, 'a', [['href', '#']]);
		var obj = this;
		a.onclick = function () {
			obj.SwitchFullHeaders();
			return false;
		};
		a.innerHTML = Lang.ShowFullHeaders;
		this._fullHeadersSwitcherLink = a;
		this._addClearDiv(cont);

		div = CreateChild(cont, 'div');
		this._shortFromCont = CreateChild(div, 'span', [['style', 'margin-right: 0;'],
			['class', 'wm_message_left wm_message_resized']]);
		var shortAddToABCont = CreateChild(div, 'span', [['style', 'margin-left: 0; margin-right: 0;'],
			['class', 'wm_message_left']]);
		this._shortAddToABObj = new CAddToAddressBookImg(shortAddToABCont);
		this._shortDateCont = CreateChild(div, 'span', [['class', 'wm_message_left wm_message_resized']]);
		this._addSwitchDetailsCont(div, true);
		this._shortLines.push(div);
		this._addClearDiv(cont);

		div = CreateChild(cont, 'div', [['class', 'wm_hide']]);
		this._fromCont = CreateChild(div, 'span', [['style', 'margin-right: 0;'],
			['class', 'wm_message_left wm_message_resized']]);
		var addToABCont = CreateChild(div, 'span', [['style', 'margin-left: 0; margin-right: 0;'],
			['class', 'wm_message_left']]);
		this._addToABObj = new CAddToAddressBookImg(addToABCont);
		this._addSwitchDetailsCont(div, false);
		this._fullLines.push(div);
		this._addClearDiv(cont);

		this._toLine = CreateChild(cont, 'div', [['class', 'wm_hide']]);
		this._toCont = CreateChild(this._toLine, 'span', [['class', 'wm_message_left']]);
		this._addClearDiv(cont);

		div = CreateChild(cont, 'div', [['class', 'wm_hide']]);
		this._ccCont = CreateChild(div, 'span', [['class', 'wm_message_left wm_message_resized']]);
		this._bccCont = CreateChild(div, 'span', [['class', 'wm_message_left'],
			['style', 'overflow: hidden;']]);
		this._copiesCont = div;
		this._addClearDiv(cont);

		div = CreateChild(cont, 'div', [['class', 'wm_hide']]);
		this._dateCont = CreateChild(div, 'span', [['class', 'wm_message_left wm_message_resized']]);
		this.SwitcherCont = CreateChild(div, 'span', [['class', 'wm_message_right']]);
		a = CreateChild(this.SwitcherCont, 'a', [['href', '#']]);
		a.innerHTML = Lang.SwitchToPlain;
		a.onclick = function () {
			if (obj.bInNewWindow) {
				WebMail.switchToHtmlPlain();
			}
			else if (WebMail.ScreenId == WebMail.listScreenId) {
				var screen = WebMail.Screens[WebMail.ScreenId];
				if (screen) screen.switchToHtmlPlain();
			}
			return false;
		};
		this.SwitcherObj = a;
		this._fullLines.push(div);
		this._addClearDiv(cont);

		// charset line
		this._charsetLine = CreateChild(cont, 'div', [['class', 'wm_hide']]);
		var charsetParent = CreateChild(this._charsetLine, 'span', [['class', 'wm_message_right']]);
		this._charsetSelector = new CMessageCharsetSelector(charsetParent);
		this._addClearDiv(cont);
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
