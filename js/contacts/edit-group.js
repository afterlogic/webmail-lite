/*
 * Classes:
 *  CEditGroupScreenPart(parent)
 */

function CEditGroupScreenPart(parent)
{
	this._parent = parent;
	
	this._mainContainer = null;
	this._groupContactsCont = null;
	this._buttonsTbl = null;
	this._GroupOrganizationTab = null;
	this._contacts = Array();
	this._isEditName = false;

	this._groupNameObj = null;

	this._groupNameSpan = null;
	this._groupNameA = null;
	this._saveButton = null;
	this._createButton = null;
	this._mailButton = null;
	
	this.isCreateGroup = false;
	this._createdGroupName = '';
	this.isSaveGroup = false;
	
	this._tabs = Array();
	this._groupOrganizationObj = null;
	this._emailObj = null;
	this._companyObj = null;
	this._streetObj = null;
	this._cityObj = null;
	this._faxObj = null;
	this._stateObj = null;
	this._phoneObj = null;
	this._zipObj = null;
	this._countryObj = null;
	this._webObj = null;
}

CEditGroupScreenPart.prototype = {
	show: function ()
	{
	    var obj = this;
		this._mainContainer.className = '';
		this._buttonsTbl.className = 'wm_contacts_view';
		this._groupNameObj.onkeypress = function (ev) {if (isEnter(ev)) obj.saveChanges();};
		this._groupNameObj.onblur = function () { };
	},
	
	
	hide: function ()
	{
		this._mainContainer.className = 'wm_hide';
		this._groupContactsCont.className = 'wm_hide';
		this._buttonsTbl.className = 'wm_hide';
		this._GroupOrganizationTab.className = 'wm_hide';
		this._tabs[0].hide();
	},
	
	CheckGroupUpdate: function ()
	{
		if (this.isCreateGroup) {
			WebMail.showReport(Lang.ReportGroupSuccessfulyAdded1 + ' "' + this._createdGroupName + '" ' + Lang.ReportGroupSuccessfulyAdded2);
			this.isCreateGroup = false;
		}
		else if (this.isSaveGroup) {
			WebMail.showReport(Lang.ReportGroupUpdatedSuccessfuly);
			this.isSaveGroup = false;
		}
	},
	
	EditName: function ()
	{
		var obj = this;
		this._groupNameObj.value = HtmlDecodeWithQuotes(this._groupNameSpan.innerHTML);
		this._groupNameObj.onkeypress = function (ev) {if (isEnter(ev)) obj.SaveName();};
		this._groupNameObj.onblur = function () {obj.SaveName();};
		this._groupNameObj.className = 'wm_input wm_group_name_input';
		this._groupNameObj.focus();
		this._groupNameSpan.className = 'wm_hide';
		this._groupNameA.className = 'wm_hide';
		this._isEditName = true;
	},
	
	SaveName: function ()
	{
		this._groupNameSpan.innerHTML = HtmlEncodeWithQuotes(this._groupNameObj.value);
		this.CloseNameEditor();
	},
	
	CloseNameEditor: function ()
	{
		this._groupNameObj.onkeypress = function () { };
		this._groupNameObj.onblur = function () { };
		this._groupNameObj.className = 'wm_hide';
		this._groupNameSpan.className = '';
		this._groupNameA.className = '';
		this._isEditName = false;
	},
	
	MailSelected: function (bAll)
	{
		var
			iCount = this._contacts.length,
			aSelected = [],
			iIndex = 0,
			oContact = null
		;
		for (; iIndex < iCount; iIndex++) {
			oContact = this._contacts[iIndex];
			if ((bAll || oContact.Inp.checked) && oContact.email.length > 0) {
				if (oContact.name.length > 0) {
					aSelected.push('"' + oContact.name + '" <' + oContact.email + '>');
				}
				else {
					aSelected.push(oContact.email);
				}
			}
		}
		if (aSelected.length == 0) {
			if (!bAll) {
				Dialog.alert(Lang.AlertNoContactsSelected);
			}
			return;
		}
		MailAllHandlerWithDropDown(aSelected.join(', '));
	},
	
	fill: function (group)
	{
		var obj = this;
		this.show();
		this.Group = group;
		if (group.sGroupId.length === 0) {
			this._groupNameObj.value = '';
			this._groupNameObj.className = 'wm_input wm_group_name_input';
			this._groupNameSpan.className = 'wm_hide';
			this._groupNameA.className = 'wm_hide';
			this._saveButton.className = 'wm_hide';
			this._createButton.className = 'wm_button';
			this._mailButton.className = 'wm_hide';
		}
		else {
			this._groupNameSpan.innerHTML = group.name;
			this._groupNameObj.className = 'wm_hide';
			this._groupNameSpan.className = '';
			this._groupNameA.className = '';
			this._saveButton.className = 'wm_button';
			this._createButton.className = 'wm_hide';
			this._mailButton.className = 'wm_button_link wm_control';
		}

		this._groupOrganizationObj.checked = group.isOrganization;
		if (group.isOrganization) {
			this._tabs[0].show();
		}
		else {
			this._tabs[0].hide();
		}
		this._emailObj.value = HtmlDecode(group.email);
		this._companyObj.value = HtmlDecode(group.company);
		this._streetObj.value = HtmlDecode(group.street);
		this._cityObj.value = HtmlDecode(group.city);
		this._faxObj.value = HtmlDecode(group.fax);
		this._stateObj.value = HtmlDecode(group.state);
		this._phoneObj.value = HtmlDecode(group.phone);
		this._zipObj.value = HtmlDecode(group.zip);
		this._countryObj.value = HtmlDecode(group.country);
		this._webObj.value = HtmlDecode(group.web);
		var iCount = group.contacts.length;
		this._contacts = Array();
		if (iCount > 0) {
			this._groupContactsCont.className = '';
			CleanNode(this._groupContactsCont);
			var tbl = CreateChild(this._groupContactsCont, 'table');
			tbl.className = 'wm_contacts_in_group_lines';
			var rowIndex = 0;
			
			var tr = tbl.insertRow(rowIndex++);
			tr.className = 'wm_contacts_headers';
			var td = tr.insertCell(0);
			td.style.width = '12px';
			var inp = CreateChild(td, 'input', [['type', 'checkbox'], ['class', 'wm_checkbox']]);
			inp.onclick = function () {obj.CheckAllLines(this.checked);};
			td = tr.insertCell(1);
			td.style.width = '100px';
			td.innerHTML = Lang.Name;
			td = tr.insertCell(2);
			td.style.width = '164px';
			td.innerHTML = Lang.Email;
			for (var i=0; i<iCount; i++) {
				tr = tbl.insertRow(rowIndex++);
				tr.className = 'wm_inbox_read_item';
				td = tr.insertCell(0);
				inp = CreateChild(td, 'input', [['type', 'checkbox'], ['class', 'wm_checkbox'], ['id', group.contacts[i].sContactId]]);
				inp.onclick = function () {obj.CheckLine(this.id, this.checked);};
				td = tr.insertCell(1);
				if (typeof group.contacts[i].getFullName === 'function'){
					td.innerHTML = group.contacts[i].getFullName();
				}
				else {
					td.innerHTML = group.contacts[i].name;
				}
				td = tr.insertCell(2);
				td.innerHTML = group.contacts[i].email;
				this._contacts[i] = {sContactId: group.contacts[i].sContactId, name: group.contacts[i].name,
					email: group.contacts[i].email, Tr: tr, Inp: inp, deleted: false};
			}

			var contactsTableWidth = tbl.offsetWidth;
			if (contactsTableWidth < 300) contactsTableWidth = 300;
			tbl = CreateChild(this._groupContactsCont, 'table');
			tbl.className = 'wm_contacts_in_group_actions';
			tbl.style.width = contactsTableWidth + 'px';
			rowIndex = 0;
			tr = tbl.insertRow(rowIndex++);
			td = tr.insertCell(0);
			td.colSpan = 2;
			var a = CreateChild(td, 'a', [['href', '#']]);
			a.onclick = function () {obj.MailSelected(false);return false;};
			a.innerHTML = Lang.MailSelected;
			td = tr.insertCell(1);
			/*rtl*/
			td.style.textAlign = window.RIGHT;
			a = CreateChild(td, 'a', [['href', '#']]);
			a.onclick = function () {obj.deleteSelected();return false;};
			a.innerHTML = Lang.RemoveFromGroup;
		}
		else {
			this._groupContactsCont.className = 'wm_hide';
		}
	},
	
	CheckLine: function (id, checked)
	{
		var iCount = this._contacts.length;
		for (var i=0; i<iCount; i++) {
			var cont = this._contacts[i];
			if (cont.id == id && !cont.deleted)
				if (checked) {
					cont.Tr.className = 'wm_inbox_read_item_select';
				}
				else {
					cont.Tr.className = 'wm_inbox_read_item';
				}
		}
	},
	
	CheckAllLines: function (checked)
	{
		var iCount = this._contacts.length;
		for (var i=0; i<iCount; i++) {
			var cont = this._contacts[i];
			cont.Inp.checked = checked;
			if (checked) {
				cont.Tr.className = 'wm_inbox_read_item_select';
			}
			else {
				cont.Tr.className = 'wm_inbox_read_item';
			}
		}
	},
	
	deleteSelected: function ()
	{
		var iCount = this._contacts.length;
		var delCount = 0;
		var deleted = false;
		for (var i=0; i<iCount; i++) {
			var cont = this._contacts[i];
			if (cont.Inp.checked) {
				if (!cont.deleted) deleted = true;
				cont.Tr.className = 'wm_hide';
				cont.deleted = true;
				delCount++;
			}
		}
		if (!deleted) {
			Dialog.alert(Lang.AlertNoContactsSelected);
		}
		if (delCount == iCount) {
			this._groupContactsCont.className = 'wm_hide';
		}
	},

	_setInputKeyPress: function (inp)
	{
	    var obj = this;
		inp.onkeypress = function (ev) {if (isEnter(ev)) obj.saveChanges();};
	},
	
	_getNewGroup: function ()
	{
		var
			sGroupId = this.Group.sGroupId,
			sName = (sGroupId.length === 0 || this._isEditName)
				? Trim(this._groupNameObj.value) : Trim(HtmlDecodeWithQuotes(this._groupNameSpan.innerHTML)),
			oGroup = new CGroup(),
			iCount = this._contacts.length,
			iIndex = 0,
			oCont = null
		;
		
		oGroup.sGroupId = sGroupId;
		oGroup.isOrganization = this._groupOrganizationObj.checked;
		oGroup.name = sName;
		oGroup.email = this._emailObj.value;
		oGroup.company = this._companyObj.value;
		oGroup.street = this._streetObj.value;
		oGroup.city = this._cityObj.value;
		oGroup.fax = this._faxObj.value;
		oGroup.state = this._stateObj.value;
		oGroup.phone = this._phoneObj.value;
		oGroup.zip = this._zipObj.value;
		oGroup.country = this._countryObj.value;
		oGroup.web = this._webObj.value;
		for (; iIndex < iCount; iIndex++) {
			oCont = this._contacts[iIndex];
			if (oCont.deleted == false) {
				oGroup.contacts.push({sContactId: oCont.sContactId, name: oCont.name, email: oCont.email});
			}
			else {
				oGroup.deletedContacts.push({sContactId: oCont.sContactId, name: oCont.name, email: oCont.email});
			}
		}
		
		return oGroup;
	},
	
	_validateGroup: function (oGroup)
	{
		var self = this;
		
		if (Validator.isEmpty(oGroup.name)) {
			Dialog.alert(Lang.WarningGroupNotComplete);
		}
		
		if (oGroup.contacts.length === 0) {
			Dialog.confirm(Lang.WarningRemovingAllContactsFromGroup,
				function () {
					self.removeGroup(oGroup);
				},
				function () {
					self.revertGroupContacts();
				}
			);
		}
		else {
			this.sendGroup(oGroup);
		}
	},
	
	removeGroup: function (oGroup)
	{
		var
			sLineId = this._parent.oSelection.getLineId(oGroup.sGroupId) //TODO
		;
		
		this._parent.deleteContacts([sLineId]);
	},
	
	revertGroupContacts: function ()
	{
		var
			iCount = this._contacts.length,
			iIndex = 0,
			oCont = null
		;
		
		this._groupContactsCont.className = '';
		
		for (; iIndex < iCount; iIndex++) {
			oCont = this._contacts[iIndex];
			oCont.Tr.className = 'wm_inbox_read_item';
			oCont.deleted = false;
		}
	},
	
	saveChanges: function ()
	{
		var
			oGroup = this._getNewGroup()
		;
		
		this._validateGroup(oGroup);
	},
	
	sendGroup: function (oGroup)
	{
		var
			sDataKey = '',
			sXml = oGroup.getInXml(this._parent.getXmlParams()),
			iIndex = 0
		;
		if (oGroup.sGroupId.length === 0) {
			WebMail.DataSource.cache.clearAllContactsGroupsList();
			for (; iIndex < oGroup.contacts.length; iIndex++) {
				sDataKey = WebMail.DataSource.getStringDataKey(TYPE_CONTACT, {sContactId: oGroup.contacts[iIndex].sContactId});
				WebMail.DataSource.cache.removeData(TYPE_CONTACT, sDataKey);
			}
			RequestHandler('new', 'group', sXml);
			this.isCreateGroup = true;
			this._createdGroupName = oGroup.name;
		}
		else {
			sDataKey = WebMail.DataSource.getStringDataKey(oGroup.type, {sGroupId: oGroup.sGroupId});
			WebMail.DataSource.cache.replaceData(oGroup.type, sDataKey, oGroup);
			WebMail.DataSource.cache.renameRemoveGroupInContacts(oGroup.sGroupId, oGroup.name, oGroup.contacts);
			if (this.Group.name != oGroup.name) {
				WebMail.DataSource.cache.clearAllContactsGroupsList();
			}
			RequestHandler('update', 'group', sXml);
			this.isSaveGroup = true;
		}
		this._parent._groupsOutOfDate = true;
	},
	
	build: function (container)
	{
		var obj = this;
		
		var mailTbl = CreateChild(container, 'table');
		mailTbl.style.width = '100%';
		mailTbl.className = 'wm_hide';
		this._mainContainer = mailTbl;
		var mainTr = mailTbl.insertRow(0);
		var mainTd = mainTr.insertCell(0);
		mainTd.style.textAlign = 'left';

		var tbl = CreateChild(mainTd, 'table');
		tbl.className = 'wm_contacts_view';
		tbl.style.marginTop = '0';
		var tr = tbl.insertRow(0);
		var td = tr.insertCell(0);
		td.innerHTML = Lang.GroupName + ':';
		WebMail.langChanger.register('innerHTML', td, 'GroupName', ':');
		td = tr.insertCell(1);
		td.className = 'wm_contacts_name';
		var inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input wm_group_name_input'], ['maxlength', '85']]);
		this._groupNameObj = inp;
		var a, span;
		/*rtl*/
		if (window.RTL) {
		    a = CreateChild(td, 'a', [['href', '#']]);
		    span = CreateChild(td, 'span');
		    span.innerHTML = '&nbsp;';
		}
		span = CreateChild(td, 'span');
		span.className = 'wm_hide';
		this._groupNameSpan = span;
		if (!window.RTL) {
			span = CreateChild(td, 'span');
			span.innerHTML = '&nbsp;';
			a = CreateChild(td, 'a', [['href', '#']]);
		}
		a.onclick = function () {obj.EditName();return false;};
		a.innerHTML = Lang.Rename;
		WebMail.langChanger.register('innerHTML', a, 'Rename', '');
		a.className = 'wm_hide';
		this._groupNameA = a;
		
		tr = tbl.insertRow(1);
		td = tr.insertCell(0);
		td.colSpan = 2;
		inp = CreateChild(td, 'input', [['type', 'checkbox'], ['class', 'wm_checkbox'], ['id', 'group-organization']]);
		var lbl = CreateChild(td, 'label', [['for', 'group-organization']]);
		lbl.innerHTML = Lang.TreatAsOrganization;
		WebMail.langChanger.register('innerHTML', lbl, 'TreatAsOrganization', '');
		inp.onclick = function () {
			if (this.checked) {
				obj._tabs[0].show();
			}
			else {
				obj._tabs[0].hide();
			}
			obj._parent.resizeBody();
		};
		this._groupOrganizationObj = inp;
//		if (UseCustomContacts || UseCustomContacts1)
//		{
			tr.className = 'wm_hide';
//		}
		
		mainTd = mainTr.insertCell(1);
        mainTd.style.textAlign = window.RIGHT;
		mainTd.style.verticalAlign = 'top';
		
		var div;
		/*rtl*/

		div = CreateChild(mainTd, 'span');
		//div.style.margin = '0 20px 10px';
		div.className = 'wm_button_link wm_control';
		div.onclick = function () {obj.MailSelected(true);};

		var divCh = CreateChild(div, 'span');
		divCh.innerHTML = Lang.MailGroup;
		WebMail.langChanger.register('innerHTML', divCh, 'MailGroup', '');
/*
	    div = CreateChild(mainTd, 'div', [['style', 'float: ' + window.RIGHT + ';']]);
		div.style.margin = '0 20px 10px';
		div.className = 'wm_button_link wm_control';
		div.onclick = function () { obj.MailSelected(true); };

		var divCh = CreateChild(div, 'div');
		divCh.innerHTML = Lang.MailGroup;
		WebMail.langChanger.register('innerHTML', divCh, 'MailGroup', '');
*/
		this._mailButton = div;
		div = CreateChild(container, 'div', [['style', 'width: 0; height: 0; padding: 0; overflow: hidden; clear: both;']]);

		this.buildGroupOrganization(container);
		
		/*------Group contacts------*/
		
		div = CreateChild(container, 'div');
		this._groupContactsCont = div;
		div.className = 'wm_hide';

		/*------New contacts------*/
		tbl = CreateChild(container, 'table');
		this._buttonsTbl = tbl;
		tbl.className = 'wm_hide';
		tbl.style.width = '90%';
		tr = tbl.insertRow(0);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_save_button';
		inp = CreateChild(td, 'input', [['type', 'button'], ['class', 'wm_button'], ['value', Lang.Save]]);
		WebMail.langChanger.register('value', inp, 'Save', '');
		inp.onclick = function () {obj.saveChanges();};
		this._saveButton = inp;
		inp = CreateChild(td, 'input', [['type', 'button'], ['class', 'wm_button'], ['value', Lang.CreateGroup]]);
		WebMail.langChanger.register('value', inp, 'CreateGroup', '');
		inp.onclick = function () {obj.saveChanges();};
		this._createButton = inp;
	},
	
	TextAreaLimit: function (ev)
	{
		return TextAreaLimit(ev, this, 85);
	},
	
	buildGroupOrganization: function (container)
	{
		var obj = this;
		
		var tabTbl = CreateChild(container, 'table');
		tabTbl.style.marginTop = '20px';
		this._GroupOrganizationTab = tabTbl;
		tabTbl.onclick = function () {
			obj._tabs[0].ChangeTabMode();
			obj._parent.resizeBody();
		};
		tabTbl.className = 'wm_contacts_tab';
		var tr = tabTbl.insertRow(0);
		var td = tr.insertCell(0);
		var span = CreateChild(td, 'span');
		span.className = 'wm_contacts_tab_name';
		span.innerHTML = Lang.Organization;
		WebMail.langChanger.register('innerHTML', span, 'Organization', '');
		var imgDiv = CreateChild(td, 'div');
		imgDiv.className = 'wm_contacts_tab_open_mode';

		var tbl = CreateChild(container, 'table');
		tbl.className = 'wm_contacts_view';
		tr = tbl.insertRow(0);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.style.width = '20%';
		td.innerHTML = Lang.Email + ':';
		WebMail.langChanger.register('innerHTML', td, 'Email', ':');
		td = tr.insertCell(1);
		td.style.width = '80%';
		td.colSpan = 4;
		var inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '45'], ['maxlength', '255']]);
		this._setInputKeyPress(inp);
		this._emailObj = inp;

		tr = tbl.insertRow(1);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.Company + ':';
		WebMail.langChanger.register('innerHTML', td, 'Company', ':');
		td = tr.insertCell(1);
		td.colSpan = 4;
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '65']]);
		this._setInputKeyPress(inp);
		this._companyObj = inp;

		tr = tbl.insertRow(2);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.StreetAddress + ':';
		WebMail.langChanger.register('innerHTML', td, 'StreetAddress', ':');
		td = tr.insertCell(1);
		td.colSpan = 4;
		var txt = CreateChild(td, 'textarea', [['class', 'wm_input'], ['cols', '35'], ['rows', '2']]);
		txt.onkeydown = this.TextAreaLimit;
		this._streetObj = txt;

		tr = tbl.insertRow(3);
		td = tr.insertCell(0);
		td.style.width = '20%';
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.City + ':';
		WebMail.langChanger.register('innerHTML', td, 'City', ':');
		td = tr.insertCell(1);
		td.style.width = '30%';
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '65']]);
		this._setInputKeyPress(inp);
		this._cityObj = inp;
		td = tr.insertCell(2);
		td.style.width = '5%';
		td = tr.insertCell(3);
		td.style.width = '15%';
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.Fax + ':';
		WebMail.langChanger.register('innerHTML', td, 'Fax', ':');
		td = tr.insertCell(4);
		td.style.width = '30%';
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '50']]);
		this._setInputKeyPress(inp);
		this._faxObj = inp;

		tr = tbl.insertRow(4);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.StateProvince + ':';
		WebMail.langChanger.register('innerHTML', td, 'StateProvince', ':');
		td = tr.insertCell(1);
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '65']]);
		this._setInputKeyPress(inp);
		this._stateObj = inp;
		td = tr.insertCell(2);
		td = tr.insertCell(3);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.Phone + ':';
		WebMail.langChanger.register('innerHTML', td, 'Phone', ':');
		td = tr.insertCell(4);
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '50']]);
		this._setInputKeyPress(inp);
		this._phoneObj = inp;

		tr = tbl.insertRow(5);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.ZipCode + ':';
		WebMail.langChanger.register('innerHTML', td, 'ZipCode', ':');
		td = tr.insertCell(1);
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '10']]);
		this._setInputKeyPress(inp);
		this._zipObj = inp;
		td = tr.insertCell(2);
		td = tr.insertCell(3);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.CountryRegion + ':';
		WebMail.langChanger.register('innerHTML', td, 'CountryRegion', ':');
		td = tr.insertCell(4);
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '18'], ['maxlength', '65']]);
		this._setInputKeyPress(inp);
		this._countryObj = inp;

		tr = tbl.insertRow(6);
		td = tr.insertCell(0);
		td.className = 'wm_contacts_view_title';
		td.innerHTML = Lang.WebPage + ':';
		WebMail.langChanger.register('innerHTML', td, 'WebPage', ':');
		td = tr.insertCell(1);
		td.colSpan = 4;
		inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['size', '45'], ['maxlength', '85']]);
		this._setInputKeyPress(inp);
		this._webObj = inp;
		inp = CreateChild(td, 'input', [['type', 'button'], ['class', 'wm_go_button'], ['value', Lang.Go]]);
		WebMail.langChanger.register('value', inp, 'Go', '');
		inp.onclick = function () {OpenURL(obj._webObj.value);};
		
		var hr = CreateChild(container, 'hr', [['style', 'background-color: #e1e1e1; color: #e1e1e1; border: 0; height: 1px; padding: 0; margin: 0 15px; width: 94%']]);
		this._tabs[0] = new CContactTab(tbl, imgDiv, tabTbl, hr);
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}