/*
 * Classes:
 *  CToolButton(eContainer, oDesc, fHandler, bShow)
 *  CToolPopupButton(eContainer, oDesc, fHandler, bShow)
 *  CToolBar(parent, viewMode)
 */

function CToolButton(eContainer, oDesc, fHandler, bShow)
{
	this.eCont = eContainer;
	this.eIcon = null;
	
	this._bEnabled = true;
	this._bHover = (oDesc.hover === undefined) ? true : oDesc.hover;
	this._bShown = false;
	this._fHandler = function () {};
	this._sClass = 'wm_toolbar_item';
	this._sOverClass = 'wm_toolbar_item_over';

	this._build(eContainer, oDesc);
	this.changeHandler(fHandler);
	if (bShow) {
		this.show();
	}
	else {
		this.hide();
	}
}

CToolButton.prototype = {
	show: function ()
	{
		this._bShown = true;
		this._setView();
	},

	hide: function ()
	{
		this._bShown = false;
		this._setView();
	},
	
	disable: function ()
	{
		this._bEnabled = false;
		this.eCont.onclick = function () {};
		this._setView();
	},
	
	enable: function ()
	{
		this._bEnabled = true;
		this.eCont.onclick = this._fHandler;
		this._setView();
	},
	
	enabled: function ()
	{
		return this._bShown && this._bEnabled;
	},
	
	_setView: function ()
	{
		if (this.enabled()) {
			var sClass = this._sClass;
			var sOverClass = this._sOverClass;
			if (this._bHover) {
				this.eCont.onmouseover = function () {
					this.className = sOverClass;
				};
				this.eCont.onmouseout = function () {
					this.className = sClass;
				};
			}
			this.eCont.className = sClass;
		}
		else {
			if (this._bHover) {
				this.eCont.onmouseover = function () { };
				this.eCont.onmouseout = function () { };
			}
			if (this._bShown) {
				this.eCont.className = this._sClass + ' wm_toolbar_item_disabled';
			}
			else {
				this.eCont.className = 'wm_hide';
			}
		}
	},
	
	changeHandler: function (fHandler)
	{
		if (fHandler !== null) {
			this._fHandler = fHandler;
			this.eCont.onclick = fHandler;
		}
	},
	
	changeClassName: function (sClass, sOverClass)
	{
		this._sClass = sClass;
		this._sOverClass = sOverClass;
		if (this._bShown) {
			this.show();
		}
	},
	
	_build: function (eContainer, oDesc)
	{
		if (oDesc.x !== undefined || oDesc.y !== undefined) {
			var eIcon = CreateChild(eContainer, 'span', [['class', 'wm_toolbar_icon'],
				['style', 'background-position: -' + oDesc.x * X_ICON_SHIFT + 'px -' + oDesc.y * Y_ICON_SHIFT + 'px']]);
			eIcon.innerHTML = '&nbsp;';
			var sTitleLangField = (oDesc.titleLangField) ? oDesc.titleLangField : oDesc.langField;
			if (sTitleLangField) {
				eIcon.title = Lang[sTitleLangField];
				WebMail.langChanger.register('title', eIcon, sTitleLangField, '');
			}
			if (oDesc.iconClassName) {
				eIcon.className = oDesc.iconClassName;
			}
			this.eIcon = eIcon;
		}
		
		if (oDesc.langField) {
			var eName = CreateChild(eContainer, 'span');
			eName.innerHTML = Lang[oDesc.langField];
			WebMail.langChanger.register('innerHTML', eName, oDesc.langField, '');
		}
		
		var sClass = (oDesc.className === undefined) ? this._sClass : oDesc.className;
		var sOverClass = (oDesc.classNameOver === undefined) ? this._sOverClass : oDesc.classNameOver;
		this.changeClassName(sClass, sOverClass);
	}
};

function CToolPopupButton(eContainer, oDesc, fHandler, bShow)
{
	CToolButton.apply(this, [eContainer, oDesc, fHandler, bShow]);
}

$.extend(CToolPopupButton.prototype, CToolButton.prototype);

CToolPopupButton.prototype._build = function (eContainer, oDesc)
{
	if (oDesc.x !== undefined || oDesc.y !== undefined) {
		var eIcon = CreateChild(eContainer, 'span', [['class', 'wm_toolbar_icon'],
			['style', 'background-position: -' + oDesc.x * X_ICON_SHIFT + 'px -' + oDesc.y * Y_ICON_SHIFT + 'px']]);
		eIcon.innerHTML = '&nbsp;';
		var sTitleLangField = (oDesc.titleLangField) ? oDesc.titleLangField : oDesc.langField;
		if (sTitleLangField) {
			eIcon.title = Lang[sTitleLangField];
			WebMail.langChanger.register('title', eIcon, sTitleLangField, '');
		}
		if (oDesc.iconClassName) {
			eIcon.className = oDesc.iconClassName;
		}
		this.eIcon = eIcon;

	}

	if (oDesc.langField) {
		var eName = CreateChild(eContainer, 'span', [['class', 'wm_toolbar_text']]);
		eName.innerHTML = Lang[oDesc.langField];
		WebMail.langChanger.register('innerHTML', eName, oDesc.langField, '');
	}

	var sClass = (oDesc.className === undefined) ? this._sClass : oDesc.className;
	var sOverClass = (oDesc.classNameOver === undefined) ? this._sOverClass : oDesc.classNameOver;
	this.changeClassName(sClass, sOverClass);
};

function CToolBar(parent, viewMode)
{
	this._viewMode = (viewMode != undefined) ? viewMode : TOOLBAR_VIEW_STANDARD;
	
	this.table = CreateChild(parent, 'div', [['class', 'wm_toolbar']]);
	$(this.table).disableSelection();
	this._container = CreateChild(this.table, 'span', [['wm_toolbar_content']]);
	if (this._viewMode != TOOLBAR_VIEW_STANDARD) {
		this._buildCurve();
	}
	
	// for safari and chrome to not display hidden menus
	document.body.style.cursor = 'auto';
	
	this._descriptions = [];
	this._descriptions[TOOLBAR_NEW_MESSAGE] = {langField: 'NewMessage', x: 0, y: 0};
	this._descriptions[TOOLBAR_CHECK_MAIL] = {langField: 'CheckMail', x: 1, y: 0};
	this._descriptions[TOOLBAR_REPLY] = {langField: 'Reply', x: 3, y: 0};
	this._descriptions[TOOLBAR_REPLYALL] = {langField: 'ReplyAll', x: 4, y: 0, className: 'wm_menu_item', classNameOver: 'wm_menu_item_over'};
	this._descriptions[TOOLBAR_FORWARD] = {langField: 'Forward', x: 5, y: 0};
	this._descriptions[TOOLBAR_MARK_READ] = {langField: 'MarkAsRead', x: 6, y: 0};
	this._descriptions[TOOLBAR_MOVE_TO_FOLDER] = {langField: 'MoveToFolder', x: 7, y: 0};
	this._descriptions[TOOLBAR_DELETE] = {langField: 'Delete', x: 8, y: 0};
	this._descriptions[TOOLBAR_UNDELETE] = {langField: 'Undelete', x: 9, y: 0, className: 'wm_menu_item wm_delete_menu', classNameOver: 'wm_menu_item_over wm_delete_menu'};
	this._descriptions[TOOLBAR_PURGE] = {langField: 'PurgeDeleted', x: 10, y: 0, className: 'wm_menu_item wm_delete_menu', classNameOver: 'wm_menu_item_over wm_delete_menu'};
	this._descriptions[TOOLBAR_EMPTY_TRASH] = {langField: 'EmptyTrash', x: 11, y: 0, className: 'wm_menu_item wm_delete_menu', classNameOver: 'wm_menu_item_over wm_delete_menu'};
	this._descriptions[TOOLBAR_IS_SPAM] = {langField: 'OperationSpam', x: 12, y: 0};
	this._descriptions[TOOLBAR_NOT_SPAM] = {langField: 'OperationNotSpam', x: 13, y: 0};
	this._descriptions[TOOLBAR_EMPTY_SPAM] = {langField: 'EmptySpam', x: 19, y: 0, className: 'wm_menu_item wm_delete_menu', classNameOver: 'wm_menu_item_over wm_delete_menu'};
	this._descriptions[TOOLBAR_SEARCH] = {x: 14, y: 0, iconClassName: 'wm_search_icon_standard', className: 'wm_toolbar_search_item', classNameOver: 'wm_toolbar_search_item_over'};
	this._descriptions[TOOLBAR_BIG_SEARCH] = {x: 15, y: 0};
	this._descriptions[TOOLBAR_SEARCH_ARROW_DOWN] = {x: 16, y: 0, iconClassName: 'wm_search_icon', className: 'wm_toolbar_search_item', classNameOver: 'wm_toolbar_search_item_over'};
	this._descriptions[TOOLBAR_SEARCH_ARROW_UP] = {x: 17, y: 0, iconClassName: 'wm_search_icon', className: 'wm_toolbar_search_item', classNameOver: 'wm_toolbar_search_item_over'};
	this._descriptions[TOOLBAR_ARROW] = {x: 18, y: 0, iconClassName: 'wm_control_icon', className: 'wm_toolbar_item wm_toolbar_add_item', classNameOver: 'wm_toolbar_item_over wm_toolbar_add_item'};
	this._descriptions[TOOLBAR_LIGHT_SEARCH_ARROW_DOWN] = {x: 0, y: 6, iconClassName: 'wm_search_icon', className: 'wm_toolbar_search_item', classNameOver: 'wm_toolbar_search_item_over'};
	this._descriptions[TOOLBAR_LIGHT_SEARCH_ARROW_UP] = {x: 1, y: 6, iconClassName: 'wm_search_icon', className: 'wm_toolbar_search_item', classNameOver: 'wm_toolbar_search_item_over'};

	this._descriptions[TOOLBAR_BACK_TO_LIST] = {langField: 'BackToList', x: 0, y: 1};
	this._descriptions[TOOLBAR_SEND_MESSAGE] = {langField: 'SendMessage', x: 1, y: 1};
	this._descriptions[TOOLBAR_SAVE_MESSAGE] = {langField: 'SaveMessage', x: 2, y: 1};
	this._descriptions[TOOLBAR_HIGH_IMPORTANCE] = {langField: 'High', x: 3, y: 1, className: 'wm_menu_item wm_importance_menu', classNameOver: 'wm_menu_item_over wm_importance_menu'};
	this._descriptions[TOOLBAR_NORMAL_IMPORTANCE] = {langField: 'Normal', x: 4, y: 1, className: 'wm_menu_item wm_importance_menu', classNameOver: 'wm_menu_item_over wm_importance_menu'};
	this._descriptions[TOOLBAR_LOW_IMPORTANCE] = {langField: 'Low', x: 5, y: 1, className: 'wm_menu_item wm_importance_menu', classNameOver: 'wm_menu_item_over wm_importance_menu'};
	this._descriptions[TOOLBAR_PRINT_MESSAGE] = {langField: 'Print', x: 6, y: 1};
	this._descriptions[TOOLBAR_IMPORTANCE] = {langField: 'Importance', x: 15, y: 1};
	this._descriptions[TOOLBAR_CANCEL] = {langField: 'Cancel', x: 6, y: 5};

	this._descriptions[TOOLBAR_SENSIVITY] = {langField: 'SensivityMenu', x: 7, y: 5};
	this._descriptions[TOOLBAR_SENSIVITY_NOTHING] = {langField: 'SensivityNothingMenu', x: 7, y: 5, className: 'wm_menu_item wm_importance_menu', classNameOver: 'wm_menu_item_over wm_importance_menu'};
	this._descriptions[TOOLBAR_SENSIVITY_CONFIDENTIAL] = {langField: 'SensivityConfidentialMenu', x: 7, y: 5, className: 'wm_menu_item wm_importance_menu', classNameOver: 'wm_menu_item_over wm_importance_menu'};
	this._descriptions[TOOLBAR_SENSIVITY_PRIVATE] = {langField: 'SensivityPrivateMenu', x: 7, y: 5, className: 'wm_menu_item wm_importance_menu', classNameOver: 'wm_menu_item_over wm_importance_menu'};
	this._descriptions[TOOLBAR_SENSIVITY_PERSONAL] = {langField: 'SensivityPersonalMenu', x: 7, y: 5, className: 'wm_menu_item wm_importance_menu', classNameOver: 'wm_menu_item_over wm_importance_menu'};

	this._descriptions[TOOLBAR_TEST] = {langField: 'TestButton', x: 0, y: 0, iconClassName: 'wm_hide'};

	var nextActiveX = 7;
	var nextInactiveX = 8;
	var prevActiveX = 9;
	var prevInactiveX = 10;
	if (window.RTL) {
		nextActiveX = 9;
		nextInactiveX = 10;
		prevActiveX = 7;
		prevInactiveX = 8;
	}
	this._descriptions[TOOLBAR_NEXT_ACTIVE] = {x: nextActiveX, y: 1, titleLangField: 'NextMsg', iconClassName: 'wm_navigate_icon'};
	this._descriptions[TOOLBAR_PREV_ACTIVE] = {x: prevActiveX, y: 1, titleLangField: 'PreviousMsg', iconClassName: 'wm_navigate_icon'};
	this._descriptions[TOOLBAR_NEW_CONTACT] = {langField: 'NewContact', x: 11, y: 1};
	this._descriptions[TOOLBAR_NEW_GROUP] = {langField: 'NewGroup', x: 12, y: 1};
	this._descriptions[TOOLBAR_ADD_CONTACTS_TO] = {langField: 'AddContactsTo', x: 13, y: 1};
	this._descriptions[TOOLBAR_IMPORT_CONTACTS] = {langField: 'ImportContacts', x: 14, y: 1};
	this._descriptions[TOOLBAR_EXPORT_CONTACTS] = {langField: 'ExportContacts', x: 14, y: 1};
	
	this._descriptions[TOOLBAR_FLAG] = {langField: 'MarkFlag', x: 0, y: 3, className: 'wm_menu_item', classNameOver: 'wm_menu_item_over'};
	this._descriptions[TOOLBAR_UNFLAG] = {langField: 'MarkUnflag', x: 1, y: 3, className: 'wm_menu_item', classNameOver: 'wm_menu_item_over'};
	this._descriptions[TOOLBAR_MARK_ALL_READ] = {langField: 'MarkAllRead', x: 2, y: 3, className: 'wm_menu_item', classNameOver: 'wm_menu_item_over'};
	this._descriptions[TOOLBAR_MARK_ALL_UNREAD] = {langField: 'MarkAllUnread', x: 3, y: 3, className: 'wm_menu_item', classNameOver: 'wm_menu_item_over'};
	this._descriptions[TOOLBAR_MARK_UNREAD] = {langField: 'MarkAsUnread', x: 4, y: 3, className: 'wm_menu_item', classNameOver: 'wm_menu_item_over'};
	
	//this._purgeTool = null;
	this._separatorAll = null;
	this._readAllTool = null;
	this._unreadAllTool = null;
}

CToolBar.prototype = {
	_buildCurve: function ()
	{
		var leftClassName = (this._viewMode == TOOLBAR_VIEW_WITH_CURVE) ? 'wm_toolbar_curve_left' : 'wm_toolbar_new_message_left';
		var rightClassName = (this._viewMode == TOOLBAR_VIEW_WITH_CURVE) ? 'wm_toolbar_curve_right' : 'wm_toolbar_new_message_right';
		
		CreateChild(this.table, 'div', [['class', 'wm_toolbar_curve_inner ' + leftClassName]]);
		CreateChild(this.table, 'div', [['class', 'wm_toolbar_curve_outer ' + leftClassName]]);
		CreateChild(this.table, 'div', [['class', 'wm_toolbar_curve_inner ' + rightClassName]]);
		CreateChild(this.table, 'div', [['class', 'wm_toolbar_curve_outer ' + rightClassName]]);
	},
	
	show: function ()
	{
		this.table.className = 'wm_toolbar';
	},

	hide: function ()
	{
		this.table.className = 'wm_hide';
	},

	getHeight: function ()
	{
		return this.table.offsetHeight;
	},

	addClearDiv: function () {
		CreateChild(this.table, 'div', [['class', 'clear']]);
	},
	
	addItem: function (itemId, clickHandler, show) {
		var div = CreateChild(this._container, 'span');
		return new CToolButton(div, this._descriptions[itemId], clickHandler, show);
	},
	
	addMarkItem: function (popupMenus, show) {
		var markMenu = CreateChild(document.body, 'div');
		markMenu.className = 'wm_hide';
		for (var i = TOOLBAR_MARK_UNREAD; i <= TOOLBAR_MARK_ALL_UNREAD; i++) {
			var div = CreateChild(markMenu, 'div');
			var markFunc = CreateToolBarItemClick(i);
			var button = new CToolButton(div, this._descriptions[i], markFunc, true);
			switch (i) {
				case TOOLBAR_UNFLAG:
					div = CreateChild(markMenu, 'div');
					div.className = 'wm_menu_separate';
					this._separatorAll = div;
					break;
				case TOOLBAR_MARK_ALL_READ:
					this._readAllTool = button;
					break;
				case TOOLBAR_MARK_ALL_UNREAD:
					this._unreadAllTool = button;
					break;
				}
		}

		var markReplace = CreateChild(this._container, 'span');
		markReplace.className = (show) ? 'wm_tb' : 'wm_hide';

		var markTitle = CreateChild(markReplace, 'span');
		markTitle.className = 'wm_toolbar_item';
		markFunc = CreateToolBarItemClick(TOOLBAR_MARK_READ);
		button = new CToolButton(markTitle, this._descriptions[TOOLBAR_MARK_READ], markFunc, true);
		
		var markControl = CreateChild(markReplace, 'span');
		markControl.className = 'wm_toolbar_item';
		var oArrowDesc = this._descriptions[TOOLBAR_ARROW];
		button = new CToolButton(markControl, oArrowDesc, null, true);

		var markPopupMenu = new CPopupMenu(markMenu, markControl, 'wm_popup_menu', markReplace, 
			markTitle, 'wm_tb', 'wm_tb_press', 'wm_toolbar_item', 'wm_toolbar_item_over',
			oArrowDesc.className, oArrowDesc.classNameOver);
		popupMenus.addItem(markPopupMenu);
		return markReplace;
	},
	
	addMoveItem: function (id, popupMenus, moveMenu, show) {
		var moveControl = CreateChild(this._container, 'span');
		moveControl.className = (show) ? 'wm_toolbar_item' : 'wm_hide';
		var oTitleBtn = new CToolButton(moveControl, this._descriptions[id], null, true);

		if (window.RTL) { 
			CreateTextChild(moveControl, ' '); 
		}
		var oArrowDesc = this._descriptions[TOOLBAR_ARROW];
		var oArrowBtn = new CToolButton(moveControl, oArrowDesc, null, true);

		var movePopupMenu = new CPopupMenu(moveMenu, moveControl, 'wm_popup_menu', moveControl, 
			moveControl, 'wm_toolbar_item', 'wm_toolbar_item_press', 'wm_toolbar_item', 'wm_toolbar_item_over',
			oArrowDesc.className, oArrowDesc.classNameOver);
		popupMenus.addItem(movePopupMenu);
		
		return {eMove: moveControl, oTitleBtn: oTitleBtn, oArrowBtn: oArrowBtn, oPopup: movePopupMenu};
	},
	
	addImportanceItem: function (popupMenus, importanceMenu) {
		var importanceControl = CreateChild(this._container, 'span');
		importanceControl.className = 'wm_toolbar_item';
		new CToolButton(importanceControl, this._descriptions[TOOLBAR_IMPORTANCE], null, true);
		var oArrowDesc = this._descriptions[TOOLBAR_ARROW];
		new CToolButton(importanceControl, oArrowDesc, null, true);
		var div = CreateChild(importanceMenu, 'div');
		var lowButton = new CToolButton(div, this._descriptions[TOOLBAR_LOW_IMPORTANCE], null, true);
		div = CreateChild(importanceMenu, 'div');
		var normalButton = new CToolButton(div, this._descriptions[TOOLBAR_NORMAL_IMPORTANCE], null, true);
		div = CreateChild(importanceMenu, 'div');
		var highButton = new CToolButton(div, this._descriptions[TOOLBAR_HIGH_IMPORTANCE], null, true);

		var importancePopupMenu = new CPopupMenu(importanceMenu, importanceControl, 'wm_popup_menu', importanceControl,
			importanceControl, 'wm_toolbar_item', 'wm_toolbar_item_press', 'wm_toolbar_item', 'wm_toolbar_item_over',
			oArrowDesc.className, oArrowDesc.classNameOver);
		popupMenus.addItem(importancePopupMenu);
		return {Low: lowButton, Normal: normalButton, High: highButton};
	},

	addSensivityItem: function (popupMenus, sensivityMenu) {
		var sensivityControl = CreateChild(this._container, 'span');
		sensivityControl.className = 'wm_toolbar_item';
		new CToolButton(sensivityControl, this._descriptions[TOOLBAR_SENSIVITY], null, true);
		var oArrowDesc = this._descriptions[TOOLBAR_ARROW];
		new CToolButton(sensivityControl, oArrowDesc, null, true);
		var div = CreateChild(sensivityMenu, 'div');
		var nothingButton = new CToolButton(div, this._descriptions[TOOLBAR_SENSIVITY_NOTHING], null, true);
		div = CreateChild(sensivityMenu, 'div');
		var confButton = new CToolButton(div, this._descriptions[TOOLBAR_SENSIVITY_CONFIDENTIAL], null, true);
		div = CreateChild(sensivityMenu, 'div');
		var privateButton = new CToolButton(div, this._descriptions[TOOLBAR_SENSIVITY_PRIVATE], null, true);
		div = CreateChild(sensivityMenu, 'div');
		var personalButton = new CToolButton(div, this._descriptions[TOOLBAR_SENSIVITY_PERSONAL], null, true);

		var sensivityPopupMenu = new CPopupMenu(sensivityMenu, sensivityControl, 'wm_popup_menu', sensivityControl,
			sensivityControl, 'wm_toolbar_item', 'wm_toolbar_item_press', 'wm_toolbar_item', 'wm_toolbar_item_over',
			oArrowDesc.className, oArrowDesc.classNameOver);
		popupMenus.addItem(sensivityPopupMenu);
		return {Nothing: nothingButton, Confidential: confButton, Private: privateButton, Personal: personalButton};
	},
	
	addReplyItem: function (popupMenus, show, replyFunc, replyAllFunc)
	{
		var replyMenu = CreateChild(document.body, 'div');
		replyMenu.className = 'wm_hide';
		var div = CreateChild(replyMenu, 'div');
		replyAllFunc = (replyAllFunc == undefined) ? CreateReplyClick(TOOLBAR_REPLYALL) : replyAllFunc;
		var replyAllButton = new CToolButton(div, this._descriptions[TOOLBAR_REPLYALL], replyAllFunc, true);

		var replyReplace = CreateChild(this._container, 'span');
		replyReplace.className = (show) ? 'wm_tb' : 'wm_hide';

		var replyTitle = CreateChild(replyReplace, 'span');
		replyFunc = (replyFunc == undefined) ? CreateReplyClick(TOOLBAR_REPLY) : replyFunc;
		var replyButton = new CToolButton(replyTitle, this._descriptions[TOOLBAR_REPLY], replyFunc, true);
		replyTitle.onclick = replyFunc;
		
		var replyControl = CreateChild(replyReplace, 'span');
		var oArrowDesc = this._descriptions[TOOLBAR_ARROW];
		new CToolButton(replyControl, oArrowDesc, null, true);

		var replyPopupMenu = new CPopupMenu(replyMenu, replyControl, 'wm_popup_menu', replyReplace, replyTitle,
			'wm_tb', 'wm_tb_press', 'wm_toolbar_item', 'wm_toolbar_item_over',
			oArrowDesc.className, oArrowDesc.classNameOver);
		popupMenus.addItem(replyPopupMenu);
		return {replyReplace: replyReplace, replyButton: replyButton, replyAllButton: replyAllButton, replyPopupMenu: replyPopupMenu};
	},
	
	addPop3DeleteItem: function (popupMenus, show)
	{
		var deleteMenu = CreateChild(document.body, 'div');
		deleteMenu.className = 'wm_hide';
		
		var div = CreateChild(deleteMenu, 'div');
		var deleteFunc = CreateToolBarItemClick(TOOLBAR_PURGE);
		var emptyTrashButton = new CToolButton(div, this._descriptions[TOOLBAR_EMPTY_TRASH], deleteFunc, true);
		
		var spamEmpty = CreateChild(deleteMenu, 'div');
		spamEmpty.className = 'wm_menu_item wm_delete_menu';
		var deleteSpamFunc = CreateToolBarItemClick(TOOLBAR_EMPTY_SPAM);
		var emptySpamButton = new CToolButton(spamEmpty, this._descriptions[TOOLBAR_EMPTY_SPAM], deleteSpamFunc, true);
		
		var deleteReplace = CreateChild(this._container, 'span');
		deleteReplace.className = (show) ? 'wm_tb' : 'wm_hide';
		
		var deleteTitle = CreateChild(deleteReplace, 'span');
		deleteFunc = CreateToolBarItemClick(TOOLBAR_DELETE);
		new CToolButton(deleteTitle, this._descriptions[TOOLBAR_DELETE], deleteFunc, true);
		
		var deleteControl = CreateChild(deleteReplace, 'span');
		var oArrowDesc = this._descriptions[TOOLBAR_ARROW];
		var deleteArrow = new CToolButton(deleteControl, oArrowDesc, null, true);

		var deletePopupMenu = new CPopupMenu(deleteMenu, deleteControl, 'wm_popup_menu', deleteReplace, deleteTitle, 
			'wm_tb', 'wm_tb_press', 'wm_toolbar_item', 'wm_toolbar_item_over',
			oArrowDesc.className, oArrowDesc.classNameOver);
		popupMenus.addItem(deletePopupMenu);
			
		return {DeleteTool: deleteReplace, EmptySpamButton: emptySpamButton,
			EmptyTrashButton: emptyTrashButton, DeleteArrow: deleteArrow};
	},
	
	CreateSearchButton: function (parent, handler)
	{
		var desc = this._descriptions[TOOLBAR_BIG_SEARCH];
		var span = CreateChild(parent, 'span', [['class', 'wm_search_icon_advanced wm_control'],
			['style', 'background-position: -' + desc.x * X_ICON_SHIFT + 'px -' + desc.y * Y_ICON_SHIFT + 'px']]);
		span.innerHTML = '&nbsp;';
		span.onclick = handler;
	},

	addSearchItems: function (container, centralPaneView) {
		if (container == undefined) container = this.table;
		
		var arrowDownId = (centralPaneView) ? TOOLBAR_LIGHT_SEARCH_ARROW_DOWN : TOOLBAR_SEARCH_ARROW_DOWN;
		var downControl = CreateChild(container, 'span');
		var downButton = new CToolButton(downControl, this._descriptions[arrowDownId], null, true);

		var arrowUpId = (centralPaneView) ? TOOLBAR_LIGHT_SEARCH_ARROW_UP : TOOLBAR_SEARCH_ARROW_UP;
		var upControl = CreateChild(container, 'span');
		var upButton = new CToolButton(upControl, this._descriptions[arrowUpId], null, true);

		var smallSearchForm = CreateChild(container, 'span');
		
		if (window.RTL) {
			downButton.eCont.style.marginRight = '0';
			upButton.eCont.style.marginRight = '0';
			smallSearchForm.style.marginLeft = '0';
		}
		else {
			downButton.eCont.style.marginLeft = '0';
			upButton.eCont.style.marginLeft = '0';
			smallSearchForm.style.marginRight = '0';
		}
		
		var lookFor = CreateChild(smallSearchForm, 'input', [['type', 'text'], ['class', 'wm_search_input'],
			['maxlength', '255']]);
		var actionButton = new CToolButton(smallSearchForm, this._descriptions[TOOLBAR_SEARCH], null, true);

		return {DownButton: downButton, UpButton: upButton, SmallForm: smallSearchForm, ActionImg: actionButton.eIcon,
			lookFor: lookFor};
	},
	
	disableInSearch: function (mode) {
		if (mode) {
			//this._purgeTool.hide();
			if (this._separatorAll != null) {
				this._separatorAll.className = 'wm_hide';
				this._readAllTool.hide();
				this._unreadAllTool.hide();
			}
		} else {
			//this._purgeTool.show();
			if (this._separatorAll != null) {
				this._separatorAll.className = 'wm_menu_separate';
				this._readAllTool.show();
				this._unreadAllTool.show();
			}
		}
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}