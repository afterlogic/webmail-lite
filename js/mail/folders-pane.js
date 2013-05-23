/*
 * Classes:
 *	CFoldersPane(htmlParent, dragNDrop, VResizer, resizingObjectsContainer, withToolBar)
 *	CFolderParams(id, sFullName, bSentDraftsType, iType, iSyncType, iMsgsCount, iNewMsgsCount, sName,
 *  	iIndent, bNoselect, eMoveItem)
 */

function CFoldersPane(htmlParent, dragNDrop, VResizer, resizingObjectsContainer, withToolBar)
{
	this._displayed = (WebMail.Settings.hideFolders) ? false : true;
	this._displayWidth = WebMail.Settings.vertResizerPos;
	this._width = WebMail.Settings.vertResizerPos;
	this._minHiddenWidth = 5;
	this._minDisplayedWidth = 125;
	
	this._mainContainer = null;
	this._toolBar = null;
	this._withToolBar = withToolBar;
	
	this._hideContainer = null;
	this._hideControl = null;
	
	this.list = null;
	
	this._manageLinkContainer = null;

	this._contentPreDiv = null;
	this._progressBarPane = null;
	this.ProgressBarContainer = null;
	this._contentBeforeDiv = null;
	
	this._vertResizerObj = null;
	this._build(htmlParent, dragNDrop, VResizer, resizingObjectsContainer);
}

CFoldersPane.prototype = 
{
	show: function ()
	{
		var obj = this;
		this._width = this._displayWidth;
		this._displayed = true;
		Cookies.create('wm_hide_folders', 0);
		this._hideControl.className = 'wm_folders_hide wm_control_img';
		this._hideControl.title = Lang.HideFolders;
		this.list.className = 'wm_folders';
		this._manageLinkContainer.className = 'wm_manage_folders';
		this._hideControl.onclick = function() {
			obj.hide();
		};
		this._vertResizerObj.busy(this._width);
		WebMail.resizeBody(RESIZE_MODE_FOLDERS);
	},
	
	hide: function ()
	{
		var obj = this;
		this._width = Validator.correctNumber(this._hideControl.offsetWidth, this._minHiddenWidth);
		this._displayed = false;
		Cookies.create('wm_hide_folders', 1);
		this._hideControl.className = 'wm_folders_show wm_control_img';
		this._hideControl.title = Lang.ShowFolders;
		this.list.className = 'wm_hide';
		this._manageLinkContainer.className = 'wm_hide';
		this._hideControl.onclick = function() { obj.show(); };
		this._vertResizerObj.free();
		WebMail.resizeBody(RESIZE_MODE_FOLDERS);
	},
	
	CleanList: function ()
	{
		CleanNode(this.list);
	},
	
	resizeHeight: function (height)
	{
		if (Validator.isPositiveNumber(height) && height >=100) {
			this._foldersHeight = height;
			this._calculateBorders();
			var allHeight = this._foldersHeight - this._vertBordersWidth;
			this._mainContainer.style.height = allHeight + 'px';
			var toolBarHeight = 0;
			var progressBarHeight = 0;
			if (this._withToolBar) {
				if (WebMail.Settings.allowComposeMessage) {
					toolBarHeight = this._toolBar.getHeight();
				}
				else {
					toolBarHeight = this._contentBeforeDiv.offsetHeight;
				}
				progressBarHeight = this._progressBarPane.offsetHeight;
			}
			else {
				toolBarHeight = this._hideContainer.offsetHeight;
			}
			this.list.style.height = (allHeight - toolBarHeight - this._manageLinkContainer.offsetHeight -
				this._contentPreDiv.offsetHeight - progressBarHeight) + 'px';
			this._vertResizerObj.updateVerticalSize(height);
		}
	},
	
	getHeight: function ()
	{
		return this._mainContainer.offsetHeight;
	},
	
	GetWidth: function ()
	{
		return this._width;
	},
	
	setResizerMinRightPos: function (minRightPos)
	{
		this._vertResizerObj.updateMinRightWidth(minRightPos);
	},
	
	_calculateWidth: function (maxWidth, leftMargin)
	{
		var foldersImgWidth = (this._withToolBar) ? this._minDisplayedWidth : this._hideControl.offsetWidth;
		if (this._displayed || this._withToolBar) {
			var leftPos = this._vertResizerObj.leftPosition;
			var minWidth = Validator.correctNumber(foldersImgWidth, this._minDisplayedWidth);
			var width = Validator.correctNumber(leftPos, minWidth, maxWidth);
			this._vertResizerObj.leftPosition = width;
			return width - leftMargin;
		}
		else {
			return Validator.correctNumber(foldersImgWidth, this._minHiddenWidth, maxWidth);
		}
	},
	
	_calculateBorders: function ()
	{
		if (this._horBordersWidth == undefined) {
			var borders = GetBorders(this.list);
			this._horBordersWidth = borders.Left + borders.Right;
			this._vertBordersWidth = borders.Top + borders.Bottom;
		}
	},
	
	resizeWidth: function (maxWidth, leftMargin)
	{
		if (leftMargin == undefined) leftMargin = 0;
		this._width = this._calculateWidth(maxWidth, leftMargin);
		this._calculateBorders();
		
		var innerWidth = this._width - this._horBordersWidth;
		this._mainContainer.style.width = this._width + 'px';
		this.list.style.width = innerWidth + 'px';
		if (!this._withToolBar) {
			this._hideContainer.style.width = innerWidth + 'px';
		}
		if (!this._withToolBar && this._displayed) {
			this._displayWidth = this._width;
			this._vertResizerObj.leftPosition = this._width;
		}
		Cookies.create('wm_vert_resizer', this._vertResizerObj.leftPosition);
		return this._width;
	},
	
	ChangeSkin: function ()
	{
		if (this._withToolBar) return;
		this._hideControl.className = (this._displayed) ? 'wm_folders_hide wm_control_img' : 'wm_folders_show wm_control_img';
	},
	
	_buildToolBar: function ()
	{
		this._toolBar = new CToolBar(this._mainContainer, TOOLBAR_VIEW_NEW_MESSAGE);
		this._toolBar.addItem(TOOLBAR_NEW_MESSAGE, NewMessageClickHandler, true);
	},

	_build: function (htmlParent, dragNDrop, VResizer, resizingObjectsContainer)
	{
		var resizerWidth = 4;
		this._vertResizerObj = new CVerticalResizer(VResizer, resizingObjectsContainer, resizerWidth, this._minDisplayedWidth, 551, 
			this._displayWidth, 'WebMail.resizeBody(RESIZE_MODE_FOLDERS);');

		this._mainContainer = htmlParent;//CreateChild(htmlParent, 'div');
		this._mainContainer.className = (this._withToolBar) ? 'wm_folders_part wm_folders_basic_view' : 'wm_folders_part';

		if (this._withToolBar) {
			if (WebMail.Settings.allowComposeMessage) {
				this._buildToolBar();
			}
			else {
				this._contentBeforeDiv = CreateChild(this._mainContainer, 'div', [['class', 'wm_folders_bottom_corners']]);
				CreateChild(this._contentBeforeDiv, 'div', [['class', 'wm_manage_folders_corner1 top']]);
				CreateChild(this._contentBeforeDiv, 'div', [['class', 'wm_manage_folders_corner2 top']]);
				CreateChild(this._contentBeforeDiv, 'div', [['class', 'wm_manage_folders_corner3 top']]);
			}
		}
		else {
			this._hideContainer = CreateChild(this._mainContainer, 'div', [['class', 'wm_folders_hide_show']]);
			this._hideControl = CreateChild(this._hideContainer, 'span', [['class', 'wm_folders_hide wm_control_img'], ['title', Lang.HideFolders]]);
			this._hideControl.innerHTML = '&nbsp;';
		}

		this._contentPreDiv = CreateChild(this._mainContainer, 'div', [['class', 'wm_folders_top_corners']]);

		var contentDiv = CreateChild(this._mainContainer, 'div', [['class', 'wm_folders_content']]);
		
		this.list = CreateChild(contentDiv, 'div');
		this.list.className = 'wm_folders_list';
		dragNDrop.SetDropContainer(this.list);

		this._manageLinkContainer = CreateChild(contentDiv, 'div', [['align', 'center'], ['class', 'wm_manage_folders']]);
		/*var linkParent = this._manageLinkContainer;
		if (this._withToolBar) {
			linkParent = CreateChild(this._manageLinkContainer, 'div', [['class', 'wm_manage_folders_link_container']]);
		}*/
		var a = CreateChild(this._manageLinkContainer, 'a', [['href', '#']]);
		a.innerHTML = Lang.ManageFolders;
		WebMail.langChanger.register('innerHTML', a, 'ManageFolders', '');
		a.onclick = function () {
			SetHistoryHandler(
				{
					ScreenId: SCREEN_USER_SETTINGS,
					iEntity: PART_MANAGE_FOLDERS,
					iEditableAcctId: WebMail.Accounts.currId,
					bNewMode: false
				}
			);
			return false;
		};

		if (this._withToolBar) {
			CreateChild(this._contentPreDiv, 'div', [['class', 'wm_manage_folders_corner1']]);
			CreateChild(this._contentPreDiv, 'div', [['class', 'wm_manage_folders_corner2']]);
			CreateChild(this._contentPreDiv, 'div', [['class', 'wm_manage_folders_corner3']]);
			
			this._progressBarPane = CreateChild(this._mainContainer, 'div', [['class', 'wm_page_switcher_bar']]);
			this.ProgressBarContainer = CreateChild(this._progressBarPane, 'div', 
				[['class', 'wm_page_switcher_container']]);
			CreateChild(this._progressBarPane, 'div', [['class', 'wm_page_switcher_corner3']]);
			CreateChild(this._progressBarPane, 'div', [['class', 'wm_page_switcher_corner2']]);
			CreateChild(this._progressBarPane, 'div', [['class', 'wm_page_switcher_corner1']]);

		}

		var obj = this;
		if (this._withToolBar || this._displayed) {
			if (!this._withToolBar) {
				this._hideControl.onclick = function() {
					obj.hide();
				};
			}
		}
		else {
			this._width = Validator.correctNumber(this._hideControl.offsetWidth, this._minHiddenWidth);
			this._hideControl.className = 'wm_folders_show wm_control_img';
			this._hideControl.title = Lang.ShowFolders;
			this.list.className = 'wm_hide';
			this._manageLinkContainer.className = 'wm_hide';
			this._hideControl.onclick = function() {
				obj.show();
			};
			this._vertResizerObj.free();
		}
	}
};

function CFolderParams(id, sFullName, bSentDraftsType, iType, iSyncType, iMsgsCount, iNewMsgsCount, sName,
		iIndent, bNoselect, eMoveItem) {
	this.bSearchResults = false;
	this.bSentDraftsType = bSentDraftsType;
	this._bNoselect = bNoselect;

	this.eContainer = null;
	this._eMoveItem = eMoveItem;

	this._fClickHandler = function () { };

	this.iMsgsCount = iMsgsCount;
	this.iNewMsgsCount = iNewMsgsCount;
	this.iPage = 1;
	this.iSyncType = iSyncType;
	this.iType = iType;
	this._id = id;
	this._iImgType = iType;
	this._iIndent = iIndent;
	this._iToAppendCount = 0;
	this._iToReadCount = 0;
	this._iToRemoveCount = 0;
	this._iToUnreadCount = 0;
	this._iUnreadedToAppendCount = 0;
	this._iUnreadedToRemoveCount = 0;

	this.sName = sName;
	this._sFullName = sFullName;
	this._sMoveClassName = eMoveItem.className;
	this._sTitle = '';
	
	this._changeImgType();
}

CFolderParams.prototype = {
	hideMoveItem: function ()
	{
		this._eMoveItem.className = 'wm_hide';
	},

	showMoveItem: function ()
	{
		this._eMoveItem.className = this._sMoveClassName;
	},

	setPage: function (iPage)
	{
		this.iPage = iPage;
	},
	
	setContainer: function (eContainer, fClickHandler)
	{
		this.eContainer = eContainer;
		this._fClickHandler = fClickHandler;
		if (this._sTitle.length > 0) {
			this.eContainer.title = this._sTitle;
		}
		this._setFolderNameText();
	},

	changeMsgsCounts: function (iCount, iNewCount, bSearchResults)
	{
		if (this.iMsgsCount !== iCount || this.iNewMsgsCount !== iNewCount) {
			WebMail.DataSource.cache.setFolderMessagesCount(this._id, this._sFullName, iCount, iNewCount, WebMail.iAcctId);
		}
		this.iMsgsCount = iCount;
		this.iNewMsgsCount = iNewCount;
		this.bSearchResults = bSearchResults;
		this._setFolderNameText();
	},
	
	addToAppend: function (iCount, iUnreaded)
	{
		this._iToAppendCount += iCount;
		this._iUnreadedToAppendCount += iUnreaded;
		this.append();
	},

	addToRemove: function (iCount, iUnreaded)
	{
		this._iToRemoveCount += iCount;
		this._iUnreadedToRemoveCount += iUnreaded;
		this.remove();
	},
	
	append: function ()
	{
		this.iMsgsCount += this._iToAppendCount;
		this.iNewMsgsCount += this._iUnreadedToAppendCount;
		WebMail.DataSource.cache.setFolderMessagesCount(this._id, this._sFullName, this.iMsgsCount, this.iNewMsgsCount, WebMail.iAcctId);
		this._iToAppendCount = 0;
		this._iUnreadedToAppendCount = 0;
		this._setFolderNameText();
	},
	
	remove: function ()
	{
		this.iMsgsCount += -this._iToRemoveCount;
		if (this.iMsgsCount < 0) {
			this.iMsgsCount = 0;
		}
		this.iNewMsgsCount += -this._iUnreadedToRemoveCount;
		if (this.iNewMsgsCount < 0) {
			this.iNewMsgsCount = 0;
		}
		WebMail.DataSource.cache.setFolderMessagesCount(this._id, this._sFullName, this.iMsgsCount, this.iNewMsgsCount, WebMail.iAcctId);
		this._iToRemoveCount = 0;
		this._iUnreadedToRemoveCount = 0;
		this._setFolderNameText();
	},
	
	addAllToRead: function ()
	{
		this._iToReadCount = this.iNewMsgsCount;
		this.read();
	},
	
	addAllToUnread: function ()
	{
		this._iToUnreadCount = this.iMsgsCount - this.iNewMsgsCount;
		this._unread();
	},
	
	addToRead: function (count)
	{
		this._iToReadCount += count;
		this.read();
	},
	
	addToUnread: function (count)
	{
		this._iToUnreadCount += count;
		this._unread();
	},
	
	read: function (count)
	{
		if (count) {
			this.iNewMsgsCount += -count;
		}
		else {
			this.iNewMsgsCount += -this._iToReadCount;
			this._iToReadCount = 0;
		}
		if (this.iNewMsgsCount < 0) {
			this.iNewMsgsCount = 0;
		}
		WebMail.DataSource.cache.setFolderMessagesCount(this._id, this._sFullName, this.iMsgsCount, this.iNewMsgsCount, WebMail.iAcctId);
		this._setFolderNameText();
	},
	
	_unread: function ()
	{
		this.iNewMsgsCount += this._iToUnreadCount;
		WebMail.DataSource.cache.setFolderMessagesCount(this._id, this._sFullName, this.iMsgsCount, this.iNewMsgsCount, WebMail.iAcctId);
		this._iToUnreadCount = 0;
		this._setFolderNameText();
	},

	_setFolderNameText: function ()
	{
		CleanNode(this.eContainer);
		var eSecondCont = CreateChild(this.eContainer, 'div');
		var eClickFolderLink = eSecondCont;
		if (this._bNoselect === false) {
			eClickFolderLink = CreateChild(eSecondCont, 'a');
			eClickFolderLink.href = '#';
			eClickFolderLink.onclick = this._fClickHandler;
		}
		
		var oFolderDesc = FolderDescriptions[this._iImgType];
		var eIcon = CreateChild(eClickFolderLink, 'span', [['class', 'wm_folder_img']]);
		eIcon.innerHTML = '&nbsp;';
		eIcon.style.backgroundPosition = '-' + oFolderDesc.x * X_ICON_SHIFT + 'px -' + oFolderDesc.y * Y_ICON_SHIFT + 'px';

		var eName = CreateChild(eClickFolderLink, 'span', [['class', 'wm_folder_name']]);
		var sInnerHtml = this.sName;
		var iShowMsgsCount = (this.iType === FOLDER_TYPE_DRAFTS) ? this.iMsgsCount : this.iNewMsgsCount;
		var sMsgsCountTitle = (this.iType === FOLDER_TYPE_DRAFTS) ? '' : ' title="' + Lang.NewMessages + '"';
		if (iShowMsgsCount > 0) {
			sInnerHtml += '&nbsp;<span' + sMsgsCountTitle + '>(' + iShowMsgsCount + ')</span>';
		}
		eName.innerHTML = sInnerHtml;

		if (window.RTL) {
			var iMarginRight = GetMarginRight(eName);
			if (!isNaN(iMarginRight)) {
				eSecondCont.style.paddingRight = this._iIndent + iMarginRight + 'px';
			}
		}
		else {
			var iMarginLeft = GetMarginLeft(eName);
			if (!isNaN(iMarginLeft)) {
				eSecondCont.style.paddingLeft = this._iIndent + iMarginLeft + 'px';
			}
		}

		if (this.iMsgsCount > 0) {
			if (this.iType === FOLDER_TYPE_TRASH) {
				this._createEmptyFolderElement(eSecondCont, 'EmptyTrash', TOOLBAR_PURGE);
			}
			else if (WebMail.allowSpamTools(false) && this.iType === FOLDER_TYPE_SPAM) {
				this._createEmptyFolderElement(eSecondCont, 'EmptySpam', TOOLBAR_EMPTY_SPAM);
			}
		}
	},

	_createEmptyFolderElement: function (eParent, sLangField, iOperation)
	{
		var eEmptyFolder = CreateChild(eParent, 'span', [['class', 'wm_clear_folder'], ['title', Lang[sLangField]]]);
		WebMail.langChanger.register('title', eEmptyFolder, sLangField, '');
		eEmptyFolder.onclick = CreateToolBarItemClick(iOperation);
		eEmptyFolder.innerHTML = '&nbsp;';
	},

	_changeImgType: function ()
	{
		if (WebMail.Accounts.currMailProtocol !== IMAP4_PROTOCOL) {
			return;
		}
		if (this.iSyncType === SYNC_TYPE_NO || this.iSyncType === SYNC_TYPE_DIRECT_MODE) {
			return;
		}
		switch (this.iType) {
			case FOLDER_TYPE_DEFAULT:
			case FOLDER_TYPE_SYSTEM:
				this._iImgType = FOLDER_TYPE_DEFAULT_SYNC;
				break;
			case FOLDER_TYPE_INBOX:
				this._iImgType = FOLDER_TYPE_INBOX_SYNC;
				break;
			case FOLDER_TYPE_SENT:
				this._iImgType = FOLDER_TYPE_SENT_SYNC;
				break;
			case FOLDER_TYPE_DRAFTS:
				this._iImgType = FOLDER_TYPE_DRAFTS_SYNC;
				break;
			case FOLDER_TYPE_TRASH:
				this._iImgType = FOLDER_TYPE_TRASH_SYNC;
				break;
		}
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}