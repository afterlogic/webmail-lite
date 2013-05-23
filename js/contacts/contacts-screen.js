/*
 * Classes:
 *  CContactsScreen()
 */

function CContactsScreen()
{
	this.id = SCREEN_CONTACTS;
	this.isBuilded = false;
	this.bodyAutoOverflow = true;
	this.contacts = null;
	this.Contact = null;
	this.groups = null;
	this._groupsOutOfDate = true;
	this._groupsDeleted = false;
	this.HistoryArgs = null;

	this._mainDiv = null;
	this._leftDiv = null;
	this._searchByFirstCharPane = null;

	this._bigSearchForm = null;
	this._searchIn = null;
	this.SearchFormObj = null;

	this._contactsToMenu = null;

	this._page = 1;
	this._pageSwitcher = null;
	this._sortOrder = SORT_ORDER_DESC;
	this._sortField = SORT_FIELD_EMAIL;
	this.sSearchGroupId = '';
	this._lookFor = '';
	this._lookFirstChar = '';
	this.sContactForEditId = '';

	this._contactsController = null;
	this._contactsTable = null;
	this.oSelection = new CSelection(FillSelectedContactsHandler);

	this._minListHeight = 150;//counted variable, depends on (contacts + groups) count on page
	this._listWidthPercent = 40;

	this._toolBar = null;
	this._lowToolBar = null;
	this._contactsCount = null;

	this._cardMinWidth = null;

	var obj = this;
	this._newContactObj = new CEditContactScreenPart(obj);
	this._viewContactObj = new CViewContactScreenPart();
	this._newGroupObj = new CEditGroupScreenPart(obj);
	this._importContactsObj = new CImportContactsScreenPart();
	this._selectedContactsObj = new CSelectedContactsScreenPart(obj);

	this._addContactsCount = 0;
	this._addGroupName = '';

	this._emptyCard = true;

	this.eGlobalPersonal = null;
	this.bGlobal = false;
	this.iContactType = TYPE_CONTACT;
	this.iContactsType = TYPE_CONTACTS;
	this.iSearchContactsType = WebMail.Settings.bShowMultipleContacts ? TYPE_MULTIPLE_CONTACTS : TYPE_CONTACTS;
}

CContactsScreen.prototype = {
	placeData: function(data)
	{
		switch (data.type)
		{
			case TYPE_CONTACTS:
			case TYPE_GLOBAL_CONTACTS:
			case TYPE_MULTIPLE_CONTACTS:
				var
					bGlobal = (this.bGlobal || data.type === TYPE_GLOBAL_CONTACTS),
					bPersonal = (!this.bGlobal || data.type === TYPE_CONTACTS)
				;
				if (bGlobal || bPersonal || data._lookFor.length > 0)
				{
					if (this.HistoryArgs.Entity == PART_CONTACTS || null != this.contacts) {
						this.showEmpty();
					}
					this._newContactObj.CheckContactUpdate();
					this.contacts = data;
	//				if (!this.bGlobal && (this._groupsOutOfDate || this._groupsDeleted)) {
	//					GetHandler(TYPE_GROUPS, {}, [], '');
	//				}
					this.fill();
					this.SearchFormObj.setStringValue(data.lookFor);
					if (!this._groupsDeleted && this.HistoryArgs.Entity == PART_VIEW_GROUP) {
						this.restoreFromHistory(this.HistoryArgs);
					}
					if (this.HistoryArgs.Entity == PART_EDIT_CONTACT) {
						SetHistoryHandler(
							{
								ScreenId: SCREEN_CONTACTS,
								Entity: PART_VIEW_CONTACT,
								sContactId: this.HistoryArgs.sContactId
							}
						);
					}
					if (data.sAddedContactId.length > 0 && this._newContactObj.DefEmail.length > 0) {
						WebMail.DataSource.cache.replaceFromContactFromMessages(data.sAddedContactId, this._newContactObj.DefEmail);
					}
				}
			break;
			case TYPE_CONTACT:
			case TYPE_GLOBAL_CONTACT:
				this.Contact = data;
				if (this.sContactForEditId.length === 0) {
					this._cardTitle.innerHTML = Lang.TitleViewContact;
					this._viewContactObj.UpdateContact(data);
					this.showViewContact();
					var sContactLineId = data.getIdForList();
					this.oSelection.CheckLine(sContactLineId);
				}
				else {
					this._cardTitle.innerHTML = Lang.TitleEditContact;
					this._newContactObj.fill(this.Contact);
					this.showNewContact();
					this.sContactForEditId = '';
				}
			break;
			case TYPE_GROUPS:
				this._newGroupObj.CheckGroupUpdate();
				this.groups = data;
				this._groupsOutOfDate = false;
				this._groupsDeleted = false;
				this._fillGroups();
				this._newContactObj.fillGroups(data);
			break;
			case TYPE_GROUP:
				this.Group = data;
				this.showNewGroup();
				this._cardTitle.innerHTML = Lang.TitleViewGroup;
				this._newGroupObj.fill(data);
			break;
			case TYPE_UPDATE:
				if (data.value == 'group') {
					if (this._addContactsCount > 0) {
						WebMail.DataSource.cache.clearAllContactsGroupsList();
						WebMail.showReport(Lang.ReportContactAddedToGroup + ' "' + this._addGroupName + '".');
						this._addContactsCount = 0;
						this._addGroupName = '';

						SetHistoryHandler(
							{
								ScreenId: SCREEN_CONTACTS,
								Entity: PART_CONTACTS,
								page: this._page,
								sortField: this._sortField,
								sortOrder: this._sortOrder,
								SearchIn: this.sSearchGroupId,
								lookFor: this._lookFor,
								lookFirstChar: this._lookFirstChar
							}
						);
					}
				} else if (data.value == 'sync_contacts') {
					WebMail.DataSource.cache.clearAllContactsGroupsList();
					WebMail.showReport(Lang.ReportContactSyncDone);

					SetHistoryHandler({
						ScreenId: SCREEN_CONTACTS,
						Entity: PART_CONTACTS,
						page: this._page,
						sortField: this._sortField,
						sortOrder: this._sortOrder,
						SearchIn: this.sSearchGroupId,
						lookFor: this._lookFor,
						lookFirstChar: this._lookFirstChar
					});
				}
			break;
		}
	},

	clickBody: function(ev)
	{
		if (null != this.SearchFormObj) {
			this.SearchFormObj.checkVisibility();
		}
	},

	onKeyDown: function (key, ev)
	{
		switch (key) {
			case Keys.space:
				this._contactsTable.onKeyDown(Keys.down, ev);
				break;
			case Keys.n:
				if (ev.shiftKey || ev.ctrlKey || ev.altKey) return;
				this._mailContactsTo();
				break;
			case Keys.s:
				if (ev.altKey) {
					this.SearchFormObj.focusSmallForm();
				}
				break;
			default:
				this._contactsTable.onKeyDown(key, ev);
				break;
		}
	},

	resizeBody: function()
	{
		if (!Browser.ie || Browser.version >= 7) {
			var listBorderHeight = 1;
			var externalHeight = WebMail.getHeaderHeight() + this._toolBar.getHeight() + this._lowToolBar.offsetHeight;
			var height = GetHeight() - externalHeight;
			if (height < this._minListHeight) {
				height = this._minListHeight;
			}
			var tableHeight = this._contactsTable.getHeight();
			var cardHeight = this._eCardCont.offsetHeight;
			if (height < tableHeight) {
				height = tableHeight;
			}
			if (height < cardHeight) {
				height = cardHeight;
			}
			var letterSearchHeight = 0;
			if (WebMail.Settings.allowFirstCharacterSearch) {
				letterSearchHeight = this._searchByFirstCharPane.offsetHeight;
			}
			this._mainDiv.style.height = height + 'px';
			this._contactsTable.setLinesHeight(height - listBorderHeight - letterSearchHeight);
			this._contactsTable.resize(this._leftDiv.offsetWidth);
		}
		else {
			this._mainDiv.style.width = ((document.documentElement.clientWidth || document.body.clientWidth) < 850) && (!this._emptyCard) ? '850px' : '100%';
			var listWidth = this._leftDiv.offsetWidth;
			this._contactsTable.resize(listWidth);

			var width = GetWidth();
			if (width < 850) width = 850;
			this._eCardCont.style.width = width - listWidth - 4 + 'px';
		}
		this._pageSwitcher.Replace();
	},

	clearContactsAndGroups: function ()
	{
		WebMail.DataSource.cache.clearAllContactsGroupsList();
		this.contacts = null;
		this.groups = null;
	},

	show: function(historyArgs)
	{
		this._mainDiv.className = 'wm_contacts';
		this._lowToolBar.className = 'wm_lowtoolbar';
		this._toolBar.show();
		if (null != this.SearchFormObj) {
			this.SearchFormObj.show();
		}
//		if (!this.bGlobal && (this.groups == null || this._groupsOutOfDate)) {
//			GetHandler(TYPE_GROUPS, {}, [], '');
//		}
		if (null == historyArgs) {
			historyArgs = {Entity: PART_CONTACTS, page: 1, SearchIn: 0, lookFor: '',
				lookFirstChar: this._lookFirstChar, sortField: CH_NAME, sortOrder: SORT_ORDER_DESC};
		}
		this.restoreFromHistory(historyArgs);
		this.resizeBody();
	},

    _checkContacts: function ()
    {
        if (null == this.contacts) {
            var requestArgs = {
                page: this._page,
                sortField: this._sortField,
                sortOrder: this._sortOrder,
                sGroupId: '',
                lookFor: ''
            };
            this._requestContacts(requestArgs);
        }
    },

    _showCurrentContacts: function ()
    {
        var lastPage = this._pageSwitcher.GetLastPage(0, WebMail.Settings.contactsPerPage);
        var page = (lastPage < this._page) ? lastPage : this._page;
        var requestArgs = {
            page: page,
            sortField: this._sortField,
            sortOrder: this._sortOrder,
            sGroupId: this.sSearchGroupId,
            lookFor: this._lookFor,
			lookFirstChar: this._lookFirstChar
        };
        this._requestContacts(requestArgs);
    },

    _showDefaultContacts: function ()
    {
        var requestArgs = {
            page: 1,
            sortField: this._sortField,
            sortOrder: this._sortOrder,
            sGroupId: '',
            lookFor: '',
			lookFirstChar: ''
        };
        this._requestContacts(requestArgs);
    },

    _showRequestedContacts: function (historyArgs)
    {
        var requestArgs = {
            page: historyArgs.page,
            sortField: !isNaN(historyArgs.sortField) ? historyArgs.sortField : this._sortField,
            sortOrder: !isNaN(historyArgs.sortOrder) ? historyArgs.sortOrder : this._sortOrder,
            sGroupId: historyArgs.SearchIn,
            lookFor: (typeof(historyArgs.lookFor) == 'string') ? historyArgs.lookFor : '',
			lookFirstChar: (typeof(historyArgs.lookFirstChar) == 'string')
				? historyArgs.lookFirstChar
				: this._lookFirstChar,
			searchType: CONTACTS_SEARCH_TYPE_STANDARD
        };
        this._requestContacts(requestArgs);
    },

    _requestContacts: function (oArgs)
    {
        var
			oContactsGroups = new CContacts(oArgs.sGroupId, oArgs.lookFor, oArgs.lookFirstChar),
			sXml = oContactsGroups.getInXml(),
			iContactsType = (oArgs.lookFor.length > 0) ? this.iSearchContactsType : this.iContactsType
		;
		
		GetHandler(iContactsType, oArgs, [], sXml);
    },

	restoreFromHistory: function (historyArgs)
	{
		this.HistoryArgs = historyArgs;
		if (historyArgs.Entity != PART_CONTACTS) {
            if (this._pageSwitcher.pagesCount > 0) {
                this._pageSwitcher.show(0);
            }
			this._checkContacts();
        }
		switch (historyArgs.Entity) {
			case PART_CONTACTS:
				this.oSelection.clearCurrLine();
				if ('undefined' == typeof(historyArgs.page) || 'undefined' == historyArgs.page || null == historyArgs.page) {
					this._showDefaultContacts();
				}
                else {
					this._showRequestedContacts(historyArgs);
				}
			break;
			case PART_NEW_CONTACT:
				this.oSelection.clearCurrLine();
				this.oSelection.UncheckAll();
				var contact = new CContact();
				if (historyArgs.name) {
					contact.name = historyArgs.name;
				}
				if (historyArgs.email) {
					contact.hEmail = historyArgs.email;
				}
				this._newContactObj.fill(contact);
				this._cardTitle.innerHTML = Lang.TitleNewContact;
				this.showNewContact();
			break;
			case PART_EDIT_CONTACT:
				if (this.Contact.sContactId === historyArgs.sContactId) {
					this._newContactObj.fill(this.Contact);
					this._cardTitle.innerHTML = Lang.TitleEditContact;
					this.showNewContact();
				}
				else {
					this.sContactForEditId = historyArgs.sContactId;
					var iContactType = this.Contact.isGlobal ? TYPE_GLOBAL_CONTACT : this.iContactType;
					GetHandler(iContactType, {sContactId: historyArgs.sContactId}, [], '');
				}
			break;
			case PART_VIEW_CONTACT:
				if (historyArgs.sContactId.length > 0) {
					this._contactsController.CurrIsGroup = false;
					this._contactsController.sCurrContactId = historyArgs.sContactId;
					var iContactType = historyArgs.isGlobalContact ? TYPE_GLOBAL_CONTACT : this.iContactType;
					GetHandler(iContactType, {sContactId: historyArgs.sContactId}, [], '');
				}
			break;
			case PART_NEW_GROUP:
				this.oSelection.clearCurrLine();
				this.oSelection.UncheckAll();
				var group = new CGroup();
				if (null != historyArgs.contacts) group.contacts = historyArgs.contacts;
				this._cardTitle.innerHTML = Lang.TitleNewGroup;
				this.showNewGroup();
				this._newGroupObj.fill(group);
			break;
			case PART_VIEW_GROUP:
				if (historyArgs.sGroupId.length > 0) {
					GetHandler(TYPE_GROUP, {sGroupId: historyArgs.sGroupId}, [], '');
				}
			break;
			case PART_IMPORT_CONTACT:
				this.oSelection.clearCurrLine();
				this.oSelection.UncheckAll();
				this.showImportContacts();
			break;
		}
	},

	contactsImported: function ()
	{
		WebMail.DataSource.cache.clearAllContactsGroupsList();
		SetHistoryHandler(
			{
				ScreenId: SCREEN_CONTACTS,
				Entity: PART_CONTACTS,
				page: this._page,
				sortField: this._sortField,
				sortOrder: this._sortOrder,
				SearchIn: this.sSearchGroupId,
				lookFor: this._lookFor,
				lookFirstChar: this._lookFirstChar
			}
		);
	},

	showSelectedContacts: function ()
	{
		this._showPartOfScreen('showSelectedContacts');
	},

	showEmpty: function ()
	{
		this._showPartOfScreen('showEmpty');
	},

	showNewContact: function ()
	{
		this._showPartOfScreen('showNewContact');
	},

	showViewContact: function ()
	{
		this._showPartOfScreen('showViewContact');
	},

	showNewGroup: function ()
	{
		this._showPartOfScreen('showNewGroup');
	},

	showImportContacts: function ()
	{
		this._showPartOfScreen('showImportContacts');
	},

	_showPartOfScreen: function (type)
	{
		if ('showEmpty' == type){
			this._eCardCont.className = 'wm_hide';
			this._emptyCard = true;
		}
		else {
			this._eCardCont.className = 'wm_contacts_view_edit';
			this._emptyCard = false;
		}

		this._selectedContactsObj.hide();
		this._newContactObj.hide();
		this._viewContactObj.hide();
		this._newGroupObj.hide();
		this._importContactsObj.hide();

		switch (type) {
			case 'showSelectedContacts':
				this._cardTitle.innerHTML = Lang.TitleSelectedContacts;
				this._selectedContactsObj.show();
				break;
			case 'showNewContact':
				this._newContactObj.show();
				break;
			case 'showViewContact':
				this._viewContactObj.show();
				break;
			case 'showNewGroup':
				this._newGroupObj.show();
				break;
			case 'showImportContacts':
				this._cardTitle.innerHTML = Lang.TitleImportContacts;
				this._importContactsObj.show();
				break;
		}

		this.resizeBody();
	},

	hide: function()
	{
		this._mainDiv.className = 'wm_hide';
		this._lowToolBar.className = 'wm_hide';
		this._toolBar.hide();
		if (null != this.SearchFormObj) {
			this.SearchFormObj.hide();
		}
		this._pageSwitcher.hide();
	},

	getXmlParams: function ()
	{
		var params = '';
		params += '<param name="page" value="' + this._page + '"/>';
		params += '<param name="sort_field" value="' + this._sortField + '"/>';
		params += '<param name="sort_order" value="' + this._sortOrder + '"/>';
		return params;
	},

	requestSearchResultsByFirstChar: function (lookFor, searchIn, lookFirstChar)
	{
		this.requestSearchResults(lookFor, searchIn, lookFirstChar);
	},

	requestSearchResults: function (lookFor, searchIn, lookFirstChar)
	{
		var iPage = this._page;
		if (lookFor !== this._lookFor)
		{
			iPage = 1;
		}
		SetHistoryHandler(
			{
				ScreenId: SCREEN_CONTACTS,
				Entity: PART_CONTACTS,
				page: iPage,
				sortField: this._sortField,
				sortOrder: this._sortOrder,
				SearchIn: searchIn,
				lookFor: lookFor,
				lookFirstChar: lookFirstChar
			}
		);
	},

	requestSearchResultsBySubstring: function ()
	{
		var sSearchGroupId = this._searchIn.value;
		if (sSearchGroupId === STR_SEPARATOR + 'empty_string' + STR_SEPARATOR) {
			sSearchGroupId = '';
		}
		this.requestSearchResults(this.SearchFormObj.getStringValue(), sSearchGroupId,
			this._lookFirstChar);
	},

	deleteSelected: function ()
	{
		var
			bDelete = false,
			aContactsId = [],
			iCount = 0,
			iIndex = 0,
			aParams = []
		;

		if (this.oSelection != null) {
			aContactsId = this.oSelection.GetCheckedLines().idArray;
			iCount = aContactsId.length;
			if (iCount > 0) {
				for (; iIndex < iCount; iIndex++) {
					aParams = aContactsId[iIndex].split(STR_SEPARATOR);
					if (aParams.length == 4) {
						bDelete = true;
						break;
					}
				}
			}
		}

		if (bDelete) {
			Dialog.confirm(Lang.ConfirmAreYouSure, (function (self, aContactsId) {
				return function () {
					self.deleteContacts(aContactsId);
				}
			})(this, aContactsId));
		}
		else {
			Dialog.alert(Lang.AlertNoContactsGroupsSelected);
		}
	},

	deleteContacts: function (aContactsId)
	{
		var
			iCount = aContactsId.length,
			iIndex = 0,
			sId = '',
			bGroup = false,
			sContacts = '',
			sGroups = '',
			aParams = [],
			iLastPage = 0,
			sXml = '',
			bCurrentContact = false
		;

		if (iCount > 0) {
			for (; iIndex < iCount; iIndex++) {
				aParams = aContactsId[iIndex].split(STR_SEPARATOR);
				if (aParams.length === 4) {
					sId = aParams[0];
					bGroup = (aParams[1] === '1');
					if (bGroup) {
						if (sId === this.sSearchGroupId) {
							this.sSearchGroupId = '';
						}
						sGroups += '<group id="' + HtmlEncodeWithQuotes(sId) + '"/>';
						WebMail.DataSource.cache.removeGroup(sId);
						bCurrentContact = (this.HistoryArgs.sGroupId === sId &&
							this.HistoryArgs.Entity === PART_VIEW_GROUP);
						if (bCurrentContact) {
							this.HistoryArgs.sGroupId = '';
						}
					}
					else {
						sContacts += '<contact id="' + HtmlEncodeWithQuotes(sId) + '"/>';
						WebMail.DataSource.cache.removeContact(sId);
						bCurrentContact = (this.HistoryArgs.sContactId === sId &&
							(this.HistoryArgs.Entity === PART_EDIT_CONTACT
							|| this.HistoryArgs.Entity === PART_VIEW_CONTACT));
						if (bCurrentContact) {
							this.HistoryArgs.sContactId = '';
						}
					}
				}
			}
			if (sContacts.length !== 0 || sGroups.length !== 0) {
				if (sGroups.length !== 0) {
					this._groupsDeleted = true;
				}
				iLastPage = this._pageSwitcher.GetLastPage(iCount);
				if (this._page > iLastPage) {
					this._page = iLastPage;
				}
				sXml = this.getXmlParams();
				sXml += '<contacts>' + sContacts + '</contacts>';
				sXml += '<groups>' + sGroups + '</groups>';
				WebMail.DataSource.cache.clearAllContactsGroupsList();
				RequestHandler('delete', 'contacts', sXml);
			}
		}
	},

	addContactsToNewGroup: function (sAlertNoContactsSelected)
	{
		if (null === this.oSelection) {
			return;
		}
		var
			aContacts = [],
			aContactIds = this.oSelection.GetCheckedLines().idArray,
			iCount = aContactIds.length,
			i = 0,
			aParams = []
		;
		for (i = 0; i < iCount; i++) {
			aParams = aContactIds[i].split(STR_SEPARATOR);
			if (aParams.length === 4 && aParams[1] === '0') {
				aContacts.push({sContactId: aParams[0], name: aParams[2], email: aParams[3]});
			}
		}
		if (aContacts.length === 0) {
			Dialog.alert(sAlertNoContactsSelected);
			return;
		}
		SetHistoryHandler(
			{
				ScreenId: SCREEN_CONTACTS,
				Entity: PART_NEW_GROUP,
				contacts: aContacts
			}
		);
	},

	addContactsToGroup: function (sGroupId, sName)
	{
		if (null === this.oSelection) {
			return;
		}
		var sContacts = '';
		var aContactIds = this.oSelection.GetCheckedLines().idArray;
		var iCount = aContactIds.length;
		var iContactsCount = 0;
		var aContactsForCacheAdding = [];
		for (var i = 0; i < iCount; i++) {
			var params = aContactIds[i].split(STR_SEPARATOR);
			if (params.length === 4 && params[1] === '0') {
				sContacts += '<contact id="' + params[0] + '"/>';
				iContactsCount++;
				aContactsForCacheAdding[i] = {sContactId: params[0], name: params[2], email: params[3]};
			}
		}
		if (sContacts.length === 0) {
			Dialog.alert(Lang.AlertNoContactsSelected);
			return;
		}

		WebMail.DataSource.cache.addGroupToContacts(sGroupId, sName, aContactsForCacheAdding);
		var stringDataKey = WebMail.DataSource.getStringDataKey(TYPE_GROUP, {sGroupId: sGroupId});
		WebMail.DataSource.cache.addContactsToGroup(stringDataKey, aContactsForCacheAdding);

		var sParam = '<param name="id_group" value="' + HtmlEncodeWithQuotes(sGroupId) + '"/>';
		sParam += this.getXmlParams();
		var sXml = sParam + '<contacts>' + sContacts + '</contacts>';
		RequestHandler('add', 'contacts', sXml);

		this._addContactsCount = iContactsCount;
		this._addGroupName = sName;
	},

	_mailContactsTo: function ()
	{
		MailToHandler(this._getStrForMailContacts());
	},

	newMessageClick: function (ev)
	{
		NewMessageClickHandler(ev, this._getStrForMailContacts());
	},

	_getStrForMailContacts: function ()
	{
		var idArray = [];
		if (null != this.oSelection) {
			idArray = this.oSelection.GetCheckedLines().idArray;
		}
		var iCount = idArray.length;
		var emailArray = [];
		for (var i=0; i<iCount; i++) {
			var params = idArray[i].split(STR_SEPARATOR);
			if (params.length == 4 && params[3].length != 0) {
				emailArray.push(HtmlDecode(params[3]));
			}
		}

		return emailArray.join(', ');
	},

	_fillGroups: function ()
	{
		var
			eSel = this._searchIn,
			sVal = eSel.value,
			eOpt,
			self = this,
			eMenu = this._contactsToMenu,
			aGroups = this.groups.items,
			iCount = aGroups.length,
			eDiv,
			iIndex = 0
		;
		
		CleanNode(eSel);
		eOpt = CreateChild(eSel, 'option', [['value', STR_SEPARATOR + 'empty_string' + STR_SEPARATOR]]);
		eOpt.innerHTML = Lang.AllGroups;
		eOpt.selected = true;

		CleanNode(eMenu);
		if (iCount > 0 && this.$searchContactsType.val() === 'personal')
		{
			this.$searchInLine.show();
		}
		else
		{
			this.$searchInLine.hide();
		}
		
		for (; iIndex < iCount; iIndex++)
		{
			eDiv = CreateChild(eMenu, 'div');
			eDiv.className = 'wm_menu_item';
			eDiv.onmouseover = function () {this.className='wm_menu_item_over';};
			eDiv.onmouseout = function () {this.className='wm_menu_item';};
			eDiv.id = aGroups[iIndex].sGroupId;
			eDiv.innerHTML = aGroups[iIndex].name;
			eDiv.onclick = function () {self.addContactsToGroup(this.id, this.innerHTML);};

			eOpt = CreateChild(eSel, 'option', [['value', aGroups[iIndex].sGroupId]]);
			eOpt.innerHTML = aGroups[iIndex].name;
		}
		
		eDiv = CreateChild(eMenu, 'div');
		eDiv.className = 'wm_menu_item_spec';
		eDiv.onmouseover = function () {this.className='wm_menu_item_over_spec';};
		eDiv.onmouseout = function () {this.className='wm_menu_item_spec';};
		eDiv.innerHTML = '- ' + Lang.NewGroup + ' -';
		WebMail.langChanger.register('innerHTML', eDiv, 'NewGroup', ' -', '- ');
		eDiv.onclick = function () {self.addContactsToNewGroup(Lang.AlertNoContactsSelected);};
		
		eSel.value = sVal;
	},
	
	clearSearch: function ()
	{
		this.requestSearchResults('', '', '');
	},
	
	disableTools: function ()
	{
		this.oNewContactTool.disable();
		this.oNewGroupTool.disable();
		this.oAddContactsTool.oPopup.disableToShow();
//		this.oAddContactsTool.oArrowBtn.disable();
//		$(this.oAddContactsTool.eMove).addClass('wm_toolbar_item_disabled');
		this.oDeleteTool.disable();
		this.oImportTool.disable();
		this.oExportTool.disable();
	},

	enableTools: function ()
	{
		this.oNewContactTool.enable();
		this.oNewGroupTool.enable();
//		$(this.oAddContactsTool.eMove).removeClass('wm_toolbar_item_disabled');
		this.oAddContactsTool.oPopup.enableToShow();
//		this.oAddContactsTool.oArrowBtn.enable();
		this.oDeleteTool.enable();
		this.oImportTool.enable();
		this.oExportTool.enable();
	},

	fill: function ()
	{
		var
			bSearchResult = (this.contacts.lookFor.length > 0),
			sMessage = '',
			sGroupName = this.groups.getGroupNameById(this.contacts.sGroupId)
		;
		
		if (bSearchResult)
		{
			if (WebMail.Settings.bShowMultipleContacts && this.contacts.type === TYPE_MULTIPLE_CONTACTS)
			{
				sMessage = Lang.TopMultipleContactsSearchResults
					.replace('%SEARCHSTR%', this.contacts.lookFor);
			}
			else
			{
				if (sGroupName.length > 0)
				{
					sMessage = Lang.ContactsSearchResultsInGroup
						.replace('%SEARCHSTR%', this.contacts.lookFor)
						.replace('%GROUPNAME%', sGroupName);
				}
				else
				{
					sMessage = Lang.ContactsSearchResults
						.replace('%SEARCHSTR%', this.contacts.lookFor);
				}
			}
			if (WebMail.Settings.bShowMultipleContacts)
			{
				this.disableTools();
			}
			else
			{
				this.enableTools();
			}
		}
		else
		{
			this.enableTools();
		}
		
		this._sortField = this.contacts.sortField;
		this._sortOrder = this.contacts.sortOrder;
		this.sSearchGroupId = this.contacts.sGroupId;
		this._lookFor = this.contacts.lookFor;
		if (WebMail.Settings.allowFirstCharacterSearch) {
			this._lookFirstChar = this.contacts.lookFirstChar;
			this._buildLettersSearch(this._lookFirstChar);
		}

		if (this.contacts.count > 0) {
			this._contactsTable.useSort();
			this._contactsTable.setSort(this._sortField, this._sortOrder);
			this._contactsTable.fill(this.contacts.list, this.id, sMessage, bSearchResult);
		}
		else {
			this._contactsTable.freeSort(true);

			if (bSearchResult)
			{
				this._contactsTable.setNoMessagesFoundMessage(Lang.NoContactsFound);
			}
			else if (this.bGlobal)
			{
				this._contactsTable.cleanLines(Lang.InfoNoContactsGroups);
			}
			else
			{
				this._contactsTable.cleanLines(Lang.InfoNoContactsGroups +
				'<br /><div class="wm_view_message_info">' + Lang.InfoNewContactsGroups + '</div>');
			}
		}

		this._page = this.contacts.page;
		var beginHandler = "SetHistoryHandler( { ScreenId: SCREEN_CONTACTS, Entity: PART_CONTACTS, page: ";
		var endHandler = ", sortField: " + this._sortField + ", sortOrder: " + this._sortOrder
			+ ", SearchIn: '" + this.sSearchGroupId + "', lookFirstChar: '" + this._lookFirstChar + "'"
			+ ", lookFor: '" + this._lookFor.replace(/'/g, '\\\'') + "'} );";
		this._pageSwitcher.show(this._page, WebMail.Settings.contactsPerPage, this.contacts.count,
			beginHandler, endHandler);
		this._pageSwitcher.Replace();

		if (WebMail.Settings.bShowMultipleContacts && this.contacts.type === TYPE_MULTIPLE_CONTACTS)
		{
			this.clearContactsCount();
		}
		else
		{
			this._setContactsCount(this.contacts.contactsCount);
		}
		this.resizeBody();
	},

	fillSelectedContacts: function (contactsArray, sCurrContactId, currIsGroup)
	{
		var contCount = contactsArray.length;
		if (contCount == 0) {
			if (this._selectedContactsObj.shown) {
				this.showEmpty();
			}
			return;
		}
		if (contCount == 1 && currIsGroup == this._contactsController.CurrIsGroup &&
		 sCurrContactId === this._contactsController.sCurrContactId) {
			return;
		}
        else if (contCount > 1) {
			this._contactsController.sCurrContactId = null;
		}
		this.showSelectedContacts();
		this._selectedContactsObj.fill(contactsArray);
	},

	buildAdvancedSearchForm: function()
	{
		var obj = this;
		this._bigSearchForm = CreateChild(document.body, 'div');
		this._bigSearchForm.className = 'wm_hide';
		var frm = CreateChild(this._bigSearchForm, 'form');
		frm.onsubmit = function () {return false;};
		var tbl = CreateChild(frm, 'table');
		var tr = tbl.insertRow(0);
		var td = tr.insertCell(0);
		td.className = 'wm_search_title';
		td.innerHTML = Lang.LookFor;
		WebMail.langChanger.register('innerHTML', td, 'LookFor', '');
		td = tr.insertCell(1);
		td.className = 'wm_search_value';
		var lookForBigInp = CreateChild(td, 'input', [['type', 'text'], ['maxlength', '255']]);
		lookForBigInp.className = 'wm_search_input';
		this._toolBar.CreateSearchButton(td, function () {
			obj.requestSearchResultsBySubstring();
		});
		lookForBigInp.onkeypress = function (ev) {
			if (isEnter(ev)) {
				obj.requestSearchResultsBySubstring();
			}
		};
		
		tr = tbl.insertRow(1);
		td = tr.insertCell(0);
		td.className = 'wm_search_title';
		td.innerHTML = Lang.ContactsTypeTitle;
		WebMail.langChanger.register('innerHTML', td, 'ContactsTypeTitle', '');
		td = tr.insertCell(1);
		td.className = 'wm_search_value';
		this.$searchContactsType = $('<select></select>').appendTo(td);
		var $opt = $('<option value="all"></option>').appendTo(this.$searchContactsType);
		WebMail.langChanger.register$({sType: 'html', $elem: $opt, sField: 'ContactsTypeMultiple'});
		$opt = $('<option value="personal"></option>').appendTo(this.$searchContactsType);
		WebMail.langChanger.register$({sType: 'html', $elem: $opt, sField: 'ContactsTypePersonal'});
		$opt = $('<option value="global"></option>').appendTo(this.$searchContactsType);
		WebMail.langChanger.register$({sType: 'html', $elem: $opt, sField: 'ContactsTypeGlobal'});
		this.$searchContactsType.val(WebMail.Settings.bShowMultipleContacts ? 'all' : 'personal');
		this.$searchContactsType.bind('change', (function (self)
		{
			return function ()
			{
				switch (this.value)
				{
					case 'all':
						self.iSearchContactsType = TYPE_MULTIPLE_CONTACTS;
						self.$searchInLine.hide();
						break;
					case 'global':
						self.iSearchContactsType = TYPE_GLOBAL_CONTACTS;
						self.$searchInLine.hide();
						break;
					case 'personal':
						self.iSearchContactsType = TYPE_CONTACTS;
						if (self.groups.items.length > 0)
						{
							self.$searchInLine.show();
						}
						else
						{
							self.$searchInLine.hide();
						}
						break;
				}
			};
		})(this));
		if (!WebMail.Settings.bShowMultipleContacts)
		{
			$(tr).hide();
		}
		
		tr = tbl.insertRow(2);
		td = tr.insertCell(0);
		td.className = 'wm_search_title';
		td.innerHTML = Lang.SearchIn;
		WebMail.langChanger.register('innerHTML', td, 'SearchIn', '');
		td = tr.insertCell(1);
		td.className = 'wm_search_value';
		this._searchIn = CreateChild(td, 'select');
		this.$searchInLine = $(tr).hide();
		
		return lookForBigInp;
	},

	_buildToolBar: function(PopupMenus)
	{
		var obj = this;
		var toolBar = this._toolBar;

		toolBar.addItem(TOOLBAR_BACK_TO_LIST, BackToListHandler, true);
		if (WebMail.Settings.allowComposeMessage) {
			toolBar.addItem(TOOLBAR_NEW_MESSAGE, function (ev) {obj.newMessageClick(ev);}, true);
		}
		this.oNewContactTool = toolBar.addItem(TOOLBAR_NEW_CONTACT, function () {
			SetHistoryHandler(
				{
					ScreenId: SCREEN_CONTACTS,
					Entity: PART_NEW_CONTACT
				}
			);
		}, true);
		this.oNewGroupTool = toolBar.addItem(TOOLBAR_NEW_GROUP, function () {
			obj.addContactsToNewGroup(Lang.WarningCreatingGroupRequiresContacts);
		}, true);
		this._contactsToMenu = CreateChild(document.body, 'div');
		this._contactsToMenu.className = 'wm_hide';
		this.oAddContactsTool = toolBar.addMoveItem(TOOLBAR_ADD_CONTACTS_TO, PopupMenus, this._contactsToMenu, true);
		this.oDeleteTool = toolBar.addItem(TOOLBAR_DELETE, function () {obj.deleteSelected();}, true);
		this.oImportTool = toolBar.addItem(TOOLBAR_IMPORT_CONTACTS, function () {
			SetHistoryHandler(
				{
					ScreenId: SCREEN_CONTACTS,
					Entity: PART_IMPORT_CONTACT
				}
			);
		}, true);
		this.oExportTool = toolBar.addItem(TOOLBAR_EXPORT_CONTACTS, function () {
			document.location = ExportContactsUrl;
		}, true);

		var lookForBigInp = this.buildAdvancedSearchForm();
		var searchParts = toolBar.addSearchItems();
		this.SearchFormObj = new CSearchForm(this._bigSearchForm, searchParts.SmallForm,
			searchParts.DownButton.eCont, searchParts.UpButton.eCont,
			lookForBigInp, searchParts.lookFor);
		if (null != this._searchIn) {
			this.SearchFormObj.setSearchIn(this._searchIn);
		}
		searchParts.lookFor.onkeypress = function (ev) {
			if (isEnter(ev)) {
				obj.requestSearchResultsBySubstring();
			}
		};
		searchParts.ActionImg.onclick = function () {
			obj.requestSearchResultsBySubstring();
		};

		toolBar.addClearDiv();
		toolBar.hide();
	},

	clearContactsCount: function ()
	{
		this._contactsCount.innerHTML = '';
	},

	_setContactsCount: function (count)
	{
		this._contactsCount.innerHTML = count + '&nbsp;' + Lang.ContactsCount;
	},

	_buildLettersSearch: function (highlightChar)
	{
		CleanNode(this._searchByFirstCharPane);
		function CreateLetterClickFunc(obj, firstChar)
		{
			return function () {
				obj.requestSearchResultsByFirstChar(obj._lookFor, obj.sSearchGroupId, firstChar);
			};
		}
		var lettersArray = ['', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
			'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
		var ul = CreateChild(this._searchByFirstCharPane, 'ul');
		for (var i = 0; i < lettersArray.length; i++) {
			var letter = lettersArray[i];
			var li = CreateChild(ul, 'li');
			if (highlightChar == letter) {
				var bold = CreateChild(li, 'b');
				bold.innerHTML = (letter == '') ? Lang.SearchByFirstCharAll : letter;
			}
			else {
				var link = CreateChild(li, 'a', [['href', '#']]);
				link.innerHTML = (letter == '') ? Lang.SearchByFirstCharAll : letter;
				link.onclick = CreateLetterClickFunc(this, letter);
			}
		}
		CreateChild(this._searchByFirstCharPane, 'div', [['style', 'clear: both;']]);
	},

	switchToGlobal: function () {
		if (!this.bGlobal) {
			this.bGlobal = true;
			this.applyGlobalPersonal();
			this.changeGlobalPersonal();
		}
	},

	switchToPersonal: function () {
		if (this.bGlobal) {
			this.bGlobal = false;
			this.applyGlobalPersonal();
			this.changeGlobalPersonal();
		}
	},

	applyGlobalPersonal: function () {
		this.iContactType = (this.bGlobal) ? TYPE_GLOBAL_CONTACT : TYPE_CONTACT;
		this.iContactsType = (this.bGlobal) ? TYPE_GLOBAL_CONTACTS : TYPE_CONTACTS;

		if (this.bGlobal) {
			if (this.eGlobal) {
				this.eGlobal.className = 'current';
			}
			if (this.ePersonal) {
				this.ePersonal.className = '';
			}
			this.oNewContactTool.hide();
			this.oNewGroupTool.hide();
			this.oAddContactsTool.eMove.className = 'wm_hide';
			this.oDeleteTool.hide();
			this.oImportTool.hide();
			this.oExportTool.hide();
		}
		else {
			if (this.eGlobal) {
				this.eGlobal.className = '';
			}
			if (this.ePersonal) {
				this.ePersonal.className = 'current';
			}
			this.oNewContactTool.show();
			this.oNewGroupTool.show();
			this.oAddContactsTool.eMove.className = 'wm_toolbar_item';
			this.oDeleteTool.show();
			this.oImportTool.show();
			this.oExportTool.show();
		}
	},

	changeGlobalPersonal: function () {
		this.clearContactsCount();
		this._contactsTable.cleanLines(Lang.InfoLoadingContacts);
		this.showEmpty();
		SetHistoryHandler(
			{
				ScreenId: SCREEN_CONTACTS,
				Entity: PART_CONTACTS,
				page: 1,
				sortField: this._sortField,
				sortOrder: this._sortOrder,
				SearchIn: this.sSearchGroupId,
				lookFor: this._lookFor,
				lookFirstChar: this._lookFirstChar
			}
		);
	},

	_buildGlobalPersonalSwitcher: function (eParent) {
		var
			eContainer = CreateChild(eParent, 'div', [['class', 'wm_contact_list_tabs']]),
			eGlobalOuter = null,
			eGlobalInner = null,
			ePersonalOuter = null,
			ePersonalInner = null,
			self = this
		;
		if (!WebMail.Settings.bShowMultipleContacts)
		{
			if (WebMail.Settings.bShowGlobalContacts) {
				eGlobalOuter = CreateChild(eContainer, 'span');
				if (!WebMail.Settings.bShowPersonalContacts) {
					eGlobalOuter.className = 'current';
				}
				eGlobalInner = CreateChild(eGlobalOuter, 'span');
				eGlobalInner.innerHTML = Lang.ContactsTabGlobal;
				WebMail.langChanger.register('innerHTML', eGlobalInner, 'ContactsTabGlobal', '', '');
				this.eGlobal = eGlobalOuter;
				if (WebMail.Settings.bShowPersonalContacts) {
					eGlobalOuter.onclick = function () {
						self.switchToGlobal();
					};
				}
				else {
					this.bGlobal = true;
				}
			}
			if (WebMail.Settings.bShowPersonalContacts && WebMail.Settings.bShowGlobalContacts) {
				ePersonalOuter = CreateChild(eContainer, 'span', [['class', 'current']]);
				ePersonalInner = CreateChild(ePersonalOuter, 'span');
				ePersonalInner.innerHTML = Lang.ContactsTabPersonal;
				WebMail.langChanger.register('innerHTML', ePersonalInner, 'ContactsTabPersonal', '', '');
				ePersonalOuter.onclick = function () {
					self.switchToPersonal();
				};
				this.ePersonal = ePersonalOuter;
			}
		}
	},

	build: function(container, popupMenus)
	{
		this._toolBar = new CToolBar(container);
		this._buildToolBar(popupMenus);

		var mainDiv = CreateChild(container, 'div');
		mainDiv.className = 'wm_hide';
		this._mainDiv = mainDiv;
		var leftDiv = CreateChild(mainDiv, 'div');
		leftDiv.className = 'wm_contacts_list';
		this._leftDiv = leftDiv;

		if (WebMail.Settings.allowFirstCharacterSearch) {
			this._searchByFirstCharPane = CreateChild(leftDiv, 'div',
				[['class', 'wm_search_by_first_char_pane']]);
			this._buildLettersSearch('');
		}

		//contacts list
		this._contactsController = new CContactsTableController(this);
		var contactsTable = new CVariableTable(SortContactsHandler, this.oSelection, null, this._contactsController);
		contactsTable.addColumn(CH_CHECK, ContactsHeaders[CH_CHECK]);
		contactsTable.addColumn(CH_GROUP, ContactsHeaders[CH_GROUP]);
		contactsTable.addColumn(CH_NAME, ContactsHeaders[CH_NAME]);
		contactsTable.addColumn(CH_EMAIL, ContactsHeaders[CH_EMAIL]);
		contactsTable.build(leftDiv);
		this._contactsTable = contactsTable;
		this._contactsTable.cleanLines(Lang.InfoLoadingContacts);

		this._buildGlobalPersonalSwitcher(leftDiv);
		this.applyGlobalPersonal();

		this._pageSwitcher = new CPageSwitcher(contactsTable.getLines(), false);

		//contact's card on the left part of screen
		var eCardCont = CreateChild(mainDiv, 'div', [['class', 'wm_hide']]);
		this._eCardCont = eCardCont;

		CreateChild(eCardCont, 'div', [['class', 'wm_contacts_card_line1']]);
		CreateChild(eCardCont, 'div', [['class', 'wm_contacts_card_line2']]);
		CreateChild(eCardCont, 'div', [['class', 'wm_contacts_card_line3']]);
		CreateChild(eCardCont, 'div', [['class', 'wm_contacts_card_line4']]);
		CreateChild(eCardCont, 'div', [['class', 'wm_contacts_card_line5']]);

		var divContent = CreateChild(eCardCont, 'div', [['class', 'wm_contacts_card_content']]);
		var tableContent = CreateChild(divContent, 'table', [['style', 'width: 100%;']]);
		var trTitle = tableContent.insertRow(0);
		var tdTitle = trTitle.insertCell(0);
		tdTitle.style.padding = '0 20px 20px 20px';
		trTitle.style.fontSize = 'large';
		this._cardTitle = tdTitle;

		var trContent = tableContent.insertRow(1);
		var tdContent = trContent.insertCell(0);
		//----------//

		this._selectedContactsObj.build(tdContent);
		this._newContactObj.build(tdContent);
		this._viewContactObj.build(tdContent);
		this._newGroupObj.build(tdContent);
		this._importContactsObj.build(tdContent);

		CreateChild(eCardCont, 'div', [['class', 'wm_contacts_card_line5']]);
		CreateChild(eCardCont, 'div', [['class', 'wm_contacts_card_line4']]);
		CreateChild(eCardCont, 'div', [['class', 'wm_contacts_card_line3']]);
		CreateChild(eCardCont, 'div', [['class', 'wm_contacts_card_line2']]);
		CreateChild(eCardCont, 'div', [['class', 'wm_contacts_card_line1']]);

		var lowDiv = CreateChild(container, 'div');
		lowDiv.className = 'wm_hide';
		this._lowToolBar = lowDiv;
		this._contactsCount = CreateChild(lowDiv, 'span', [['class', 'wm_lowtoolbar_messages']]);
		this.clearContactsCount();

		this.isBuilded = true;
	}//build
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
