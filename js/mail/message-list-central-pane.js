/*
 * Classes:
 *	CMessageListCentralPane(container, PopupMenus)
 */

function CMessageListCentralPane(container, PopupMenus)
{
	this._sortDescriptions = [
		{ Field: SORT_FIELD_DATE, FieldLang: 'SortFieldDate', Order: SORT_ORDER_ASC, OrderLang: 'SortOrderAscending', bEnableInDm: true },
		{ Field: SORT_FIELD_DATE, FieldLang: 'SortFieldDate', Order: SORT_ORDER_DESC, OrderLang: 'SortOrderDescending', bEnableInDm: true },
		{ Field: SORT_FIELD_FROM, FieldLang: 'SortFieldFrom', Order: SORT_ORDER_ASC, OrderLang: 'SortOrderAscending', bEnableInDm: true },
		{ Field: SORT_FIELD_FROM, FieldLang: 'SortFieldFrom', Order: SORT_ORDER_DESC, OrderLang: 'SortOrderDescending', bEnableInDm: true },
		{ Field: SORT_FIELD_SIZE, FieldLang: 'SortFieldSize', Order: SORT_ORDER_ASC, OrderLang: 'SortOrderAscending', bEnableInDm: true },
		{ Field: SORT_FIELD_SIZE, FieldLang: 'SortFieldSize', Order: SORT_ORDER_DESC, OrderLang: 'SortOrderDescending', bEnableInDm: true },
		{ Field: SORT_FIELD_SUBJECT, FieldLang: 'SortFieldSubject', Order: SORT_ORDER_ASC, OrderLang: 'SortOrderAscending', bEnableInDm: true },
		{ Field: SORT_FIELD_SUBJECT, FieldLang: 'SortFieldSubject', Order: SORT_ORDER_DESC, OrderLang: 'SortOrderDescending', bEnableInDm: true },
		{ Field: SORT_FIELD_FLAG, FieldLang: 'SortFieldFlag', Order: SORT_ORDER_ASC, OrderLang: 'SortOrderAscending', bEnableInDm: false },
		{ Field: SORT_FIELD_FLAG, FieldLang: 'SortFieldFlag', Order: SORT_ORDER_DESC, OrderLang: 'SortOrderDescending', bEnableInDm: false },
		{ Field: SORT_FIELD_ATTACH, FieldLang: 'SortFieldAttachments', Order: SORT_ORDER_ASC, OrderLang: 'SortOrderAscending', bEnableInDm: false },
		{ Field: SORT_FIELD_ATTACH, FieldLang: 'SortFieldAttachments', Order: SORT_ORDER_DESC, OrderLang: 'SortOrderDescending', bEnableInDm: false }
	];
	//this._sortDescriptions.length=13 in ie7
	this._sortDescriptionsLength = 12;
	this._eSortInscription = null;
	this._oSortPopupMenu = null;
		
	this.pageSwitcherBar = null;
	this._messageListDisplay = null;
	
	this._toolBar = null;
	this._checkMailTool = null;
	this._markTool = null;
	this._moveMenu = null;
	this._inboxMoveItem = null;
	this._pop3DeleteTool = null;
	this._imap4DeleteTool = null;
	this._isSpamTool = null;
	this._notSpamTool = null;
	
	this.SearchFormObj = null;
	this._bigSearchForm = null;
	this._searchIn = null;
	this._quickSearch = null;
	this._slowSearch = null;
	
	this._additionalBar = null;

	this.MainContainer = CreateChild(container, 'div');
	var borders = GetBorders(this.MainContainer);
	this._horBordersWidth = borders.Left + borders.Right;
	this._vertBordersWidth = borders.Top + borders.Bottom;
	this._buildToolBar(PopupMenus);
	this._buildAdditionalBar();
	
	this.eLastLogin = null;
}

CMessageListCentralPane.prototype = {
	resizeHeight: function (height) {
		var innerHeight = height - this._vertBordersWidth;
		this.MainContainer.style.height = innerHeight + 'px';
		var toolBarHeight = this._toolBar.getHeight();
		var lowToolBarHeight = this.pageSwitcherBar.offsetHeight;
		var additionalBarHeight = this._additionalBar.offsetHeight;
		this._messageListDisplay.setLinesHeight(innerHeight - toolBarHeight - lowToolBarHeight - additionalBarHeight);
	},
	
	resizeWidth: function (width)
    {
		this._messageListDisplay.resize(width - this._horBordersWidth);
	},
	
	repairDeleteTools: function (deleteLikePop3)
	{
		if (deleteLikePop3) {
			this._pop3DeleteTool.className = 'wm_tb';
			this._imap4DeleteTool.hide();
		}
		else {
			this._pop3DeleteTool.className = 'wm_hide';
			this._imap4DeleteTool.show();
		}
	},
	
	enableDeleteTools: function (deleteLikePop3)
	{
		if (deleteLikePop3) {
			this._pop3DeleteTool.enabled = true;
			this._pop3DeleteTool.className = "wm_tb";
		}
		else {
			this._imap4DeleteTool.enable();
		}
	},
	
	enableTools: function (deleteLikePop3)
	{
		this.enableCheckMailTool();
		this.enableDeleteTools(deleteLikePop3);
		this._isSpamTool.enable();
		this._notSpamTool.enable();
	},

	showMarkTool: function ()
	{
		this._markTool.className = 'wm_tb';
	},
	
	hideMarkTool: function ()
	{
		this._markTool.className = 'wm_hide';
	},
	
	disableInSearch: function (disable)
	{
		this._toolBar.disableInSearch(disable);
	},
	
	
	showInboxMoveItem: function ()
	{
		if (this._inboxMoveItem == null) return;
		this._inboxMoveItem.className = 'wm_menu_item';
	},
	
	hideInboxMoveItem: function ()
	{
		if (this._inboxMoveItem == null) return;
		this._inboxMoveItem.className = 'wm_hide';
	},
	
	_buildToolBar: function (PopupMenus)
	{
		this._toolBar = new CToolBar(this.MainContainer, TOOLBAR_VIEW_WITH_CURVE);
		//check mail tool
		var obj = this;
		this._checkMailTool = this._toolBar.addItem(TOOLBAR_CHECK_MAIL, function () {
			obj.startCheckMail(false);
		}, true);

		//mark tool; absent in inbox in direct mode in pop3
		this._markTool = this._toolBar.addMarkItem(PopupMenus, false);
		
		//move to folder tool; absent in inbox in direct mode in pop3
		var div = CreateChild(document.body, 'div');
		this._moveMenu = div;
		div.className = 'wm_hide';
		this._toolBar.addMoveItem(TOOLBAR_MOVE_TO_FOLDER, PopupMenus, div, false);
		
		//delete tools
		var deleteParts = this._toolBar.addPop3DeleteItem(PopupMenus, false);
		this._pop3DeleteTool = deleteParts.DeleteTool;
		this._emptySpamButton = deleteParts.EmptySpamButton;
		this._emptyTrashButton = deleteParts.EmptyTrashButton;
		this._deleteArrow = deleteParts.DeleteArrow;
		var deleteFunc = CreateToolBarItemClick(TOOLBAR_DELETE);
		this._imap4DeleteTool = this._toolBar.addItem(TOOLBAR_DELETE, deleteFunc, false);
		
		// spam tools
		this._isSpamTool = this._toolBar.addItem(TOOLBAR_IS_SPAM, function () {RequestMessagesOperationHandler(TOOLBAR_IS_SPAM, [], []);}, false);
		this._notSpamTool = this._toolBar.addItem(TOOLBAR_NOT_SPAM, function () {RequestMessagesOperationHandler(TOOLBAR_NOT_SPAM, [], []);}, false);
	},
	
	requestSearchResults: function ()
	{
		var screen = WebMail.Screens[WebMail.listScreenId];
		if (screen) {
			screen.requestSearchResults();
		}
	},

	useSort: function (bSortByFlags)
	{
		this._oSortPopupMenu.show();
		this._eSortInscription.className = 'wm_arranged_by_title';
		var sClassName = (bSortByFlags) ? '' : 'wm_hide';
		for (var iSortDescIdx = 0; iSortDescIdx < this._sortDescriptionsLength; iSortDescIdx++) {
			var oSortDescription = this._sortDescriptions[iSortDescIdx];
			if (oSortDescription.eLine && !oSortDescription.bEnableInDm) {
				oSortDescription.eLine.className = sClassName;
			}
		}
	},
	
	freeSort: function (pEveryplace)
	{
		var iSortDescIdx, oSortDescription;
		if (pEveryplace) {
			this._oSortPopupMenu.hide();
			this._eSortInscription.className = 'wm_hide';
		}
		else {
			this._oSortPopupMenu.show();
			this._eSortInscription.className = 'wm_arranged_by_title';
			for (iSortDescIdx = 0; iSortDescIdx < this._sortDescriptionsLength; iSortDescIdx++) {
				oSortDescription = this._sortDescriptions[iSortDescIdx];
				if (oSortDescription.eLine && oSortDescription.Field === SORT_FIELD_DATE) {
					oSortDescription.eLine.className = '';
				}
				else {
					oSortDescription.eLine.className = 'wm_hide';
				}
			}
		}
	},

	_fillSortMenu: function ()
	{
		var createSortFunc = function (field, order) {
			return function () {
				SortMessagesHandler.call({ sortField: field, sortOrder: order });
			};
		};
		for (var sortDescIndex = 0; sortDescIndex < this._sortDescriptionsLength; sortDescIndex++) {
			var sortDescription = this._sortDescriptions[sortDescIndex];
			var item = CreateChild(this._sortMenu, 'div');
			item.onclick = createSortFunc(sortDescription.Field, sortDescription.Order);

			var span = CreateChild(item, 'span');
			span.innerHTML = Lang[sortDescription.FieldLang] + ', ';
		    WebMail.langChanger.register('innerHTML', span, sortDescription.FieldLang, ', ');

			span = CreateChild(item, 'span');
			span.innerHTML = Lang[sortDescription.OrderLang];
		    WebMail.langChanger.register('innerHTML', span, sortDescription.OrderLang, '');

			sortDescription.eLine = item;
			this._sortDescriptions[sortDescIndex] = sortDescription;
		}
	},
	
	setSort: function (sortField, sortOrder)
	{
		for (var sortDescIndex = 0; sortDescIndex < this._sortDescriptionsLength; sortDescIndex++) {
			var sortDescription = this._sortDescriptions[sortDescIndex];
			if (sortDescription.Field == sortField && sortDescription.Order == sortOrder) {
				this._sortTitle.innerHTML = Lang[sortDescription.FieldLang] + ', ' + Lang[sortDescription.OrderLang];
			}
		}
	},
	
	_buildSortPopup: function (container)
	{
		this._sortMenu = CreateChild(document.body, 'div');
		this._sortMenu.className = 'wm_hide';
		
		this._eSortInscription = CreateChild(container, 'span', [['class', 'wm_arranged_by_title']]);
		this._eSortInscription.innerHTML = Lang.ArrangedBy  + ': ';
	    WebMail.langChanger.register('innerHTML', this._eSortInscription, 'ArrangedBy', ': ');
		
		var sortReplace = CreateChild(container, 'span', [['class', 'wm_sort_popup_control']]);
		
		var sortControl;
		if (window.RTL) {
			sortControl = CreateChild(sortReplace, 'span');
		}
		
		this._sortTitle = CreateChild(sortReplace, 'span', [['class', 'wm_toolbar_item']]);
		this._fillSortMenu();
		
		if (!window.RTL) {
			sortControl = CreateChild(sortReplace, 'span');
		}
		sortControl.className = 'wm_toolbar_item';
		sortControl.innerHTML = '<span class="wm_control_icon"> </span>';
		
		this._oSortPopupMenu = new CPopupMenu(this._sortMenu, sortControl, 'wm_sort_popup_menu', sortReplace, this._sortTitle,
			'wm_sort_popup_control', 'wm_sort_popup_control', 'wm_toolbar_item', 'wm_toolbar_item');
		WebMail.PopupMenus.addItem(this._oSortPopupMenu);
	},
	
	_buildAdditionalBar: function ()
	{
		this._additionalBar = CreateChild(this.MainContainer, 'div', [['class', 'wm_additional_bar wm_central_pane_view']]);
		//vasil
		CreateChild(this._additionalBar, 'div', [['class', 'wm_additional_bar_corner1']]);
		CreateChild(this._additionalBar, 'div', [['class', 'wm_additional_bar_corner2']]);
		CreateChild(this._additionalBar, 'div', [['class', 'wm_additional_bar_corner3']]);
		var additionalBarCont = CreateChild(this._additionalBar, 'div', [['class', 'wm_additional_bar_container']]);

		var lookForBigInp = this.buildAdvancedSearchForm();
		var searchParts = this._toolBar.addSearchItems(additionalBarCont, true);
		this.SearchFormObj = new CSearchForm(this._bigSearchForm, searchParts.SmallForm, searchParts.DownButton.eCont,
			searchParts.UpButton.eCont, lookForBigInp, searchParts.lookFor, true);
		if (null != this._searchIn) {
			this.SearchFormObj.setSearchIn(this._searchIn);
		}
		var obj = this;
		searchParts.lookFor.onkeypress = function (ev) {
			if (isEnter(ev)) {
				obj.requestSearchResults();
			}
		};
		searchParts.ActionImg.onclick = function () {
			obj.requestSearchResults();
		};

		// var div = CreateChild(this._additionalBar, 'div', [['class', 'wm_list_checkbox_container']]);
		var div = CreateChild(additionalBarCont, 'span', [['class', 'wm_list_checkbox_container']]);
		this._checkbox = CreateChild(div, 'input', [['type', 'checkbox']]);
		// this._buildSortPopup(this._additionalBar);
		this._buildSortPopup(additionalBarCont);

		// div = CreateChild(this._additionalBar, 'div', [['class', 'wm_additional_bar_right_border']]);
		// div = CreateChild(additionalBarCont, 'div', [['class', 'wm_additional_bar_right_border']]);

		CreateChild(additionalBarCont, 'div', [['class', 'clear']]);
	},
	
	build: function (messageListDisplay) {
		this._messageListDisplay = messageListDisplay;
		
		this._messageListDisplay.oSelection.SetCheckBox(this._checkbox);

		this.pageSwitcherBar = CreateChild(this.MainContainer, 'div', [['class', 'wm_page_switcher_bar']]);

		var ePgContainer = CreateChild(this.pageSwitcherBar, 'div', [['class', 'wm_page_switcher_container']]);
		var eLastLogin = CreateChild(ePgContainer, 'div', [['style', 'margin: 12px']]);
		if (WebMail.Settings.sLastLogin.length > 0)	{
			eLastLogin.innerHTML = Lang.LastLoginTitle + ': ' + WebMail.Settings.sLastLogin;
			setTimeout ( function () {
				$(eLastLogin).fadeOut(6000);
			}, 300000 );
		}
		CreateChild(this.pageSwitcherBar, 'div', [['class', 'wm_page_switcher_corner3']]);
		CreateChild(this.pageSwitcherBar, 'div', [['class', 'wm_page_switcher_corner2']]);
		CreateChild(this.pageSwitcherBar, 'div', [['class', 'wm_page_switcher_corner1']]);
	}
};

CMessageListCentralPane.prototype.CleanMoveMenu = MessageListPrototype.CleanMoveMenu;
CMessageListCentralPane.prototype.addToMoveMenu = MessageListPrototype.addToMoveMenu;

CMessageListCentralPane.prototype.enableCheckMailTool = MessageListPrototype.enableCheckMailTool;
CMessageListCentralPane.prototype.startCheckMail = MessageListPrototype.startCheckMail;
CMessageListCentralPane.prototype.Pop3DeleteToolEnabled = MessageListPrototype.Pop3DeleteToolEnabled;
CMessageListCentralPane.prototype.AlreadyPop3Deleted = MessageListPrototype.AlreadyPop3Deleted;
CMessageListCentralPane.prototype.disablePop3DeleteTool = MessageListPrototype.disablePop3DeleteTool;
CMessageListCentralPane.prototype.ClearDeleteTools = MessageListPrototype.ClearDeleteTools;
CMessageListCentralPane.prototype.ImapDeleteToolEnabled = MessageListPrototype.ImapDeleteToolEnabled;
CMessageListCentralPane.prototype.AlreadyImapDeleted = MessageListPrototype.AlreadyImapDeleted;
CMessageListCentralPane.prototype.disableImapDeleteTool = MessageListPrototype.disableImapDeleteTool;
CMessageListCentralPane.prototype.SpamToolEnabled = MessageListPrototype.SpamToolEnabled;
CMessageListCentralPane.prototype.AlreadyMarkedSpam = MessageListPrototype.AlreadyMarkedSpam;
CMessageListCentralPane.prototype.enableToolsByOperation = MessageListPrototype.enableToolsByOperation;
CMessageListCentralPane.prototype.RepairSpamTools = MessageListPrototype.RepairSpamTools;
CMessageListCentralPane.prototype.RepairEmptyTools = MessageListPrototype.RepairEmptyTools;

CMessageListCentralPane.prototype.buildAdvancedSearchForm = MessageListPrototype.buildAdvancedSearchForm;
CMessageListCentralPane.prototype.getSearchParameters = MessageListPrototype.getSearchParameters;
CMessageListCentralPane.prototype.hideSearchFolders = MessageListPrototype.hideSearchFolders;
CMessageListCentralPane.prototype.CheckVisibilitySearchForm = MessageListPrototype.CheckVisibilitySearchForm;
CMessageListCentralPane.prototype.CleanSearchFolders = MessageListPrototype.CleanSearchFolders;
CMessageListCentralPane.prototype.showSearchForm = MessageListPrototype.showSearchForm;
CMessageListCentralPane.prototype.hideSearchForm = MessageListPrototype.hideSearchForm;
CMessageListCentralPane.prototype.PlaceSearchData = MessageListPrototype.PlaceSearchData;
CMessageListCentralPane.prototype.focusSearchForm = MessageListPrototype.focusSearchForm;
CMessageListCentralPane.prototype.SetCurrSearchFolder = MessageListPrototype.SetCurrSearchFolder;
CMessageListCentralPane.prototype.addToSearchFolders = MessageListPrototype.addToSearchFolders;

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}