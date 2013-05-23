/*
 * Classes:
 *  CMessageListCentralPaneScreen()
 */

function CMessageListCentralPaneScreen(sLookFor)
{
	this.id = SCREEN_MESSAGE_LIST_CENTRAL_PANE;
	this.isBuilded = false;
	this.bodyAutoOverflow = false;
	this.shown = false;
	
	this._spaceInfoObj = new CSpaceInfo();
	
	this.isDirectMode = false;

	this.oKeyMsgList = new CKeyMessages(sLookFor);
	this._removeCount = 0;

	this._mainContainer = null;
	this._mailContDiv = null;

	this._toolBar = null;

	this._foldersObj = null;
	this.needToRefreshFolders = false;
	this.needToRefreshMessages = false;
	this._foldersParam = Array();
	this._currFolder = null;

	this._vResizerCont = null;

	this._pageSwitcher = null;

	this._inboxContainer = null;
	this._inboxTable = null;
	this._messageListPane = null;
	this.oSelection = new CSelection();
	this._dragNDrop = new CDragNDrop('Messages');
	this._dragNDrop.SetSelection(this.oSelection);
	this._inboxWidth = 361;

	this.oMsgList = null;

	this._msgViewer = null;
	this._voiceMessageViewPane = null;
	this.msgBodyFocus = false;
	this._replyPane = null;

	this._picturesControl = new CMessagePicturesController(false);
	this._sensivityControl = new CMessageSensivityController();

	this.SendConfirmation = function () {
		var msg = this._msgObj;
		if (msg && msg.mailConfirmationValue && msg.mailConfirmationValue.length) {
			SendConfirmationHandler(msg.mailConfirmationValue, msg.subject);
		}
		this.resizeScreen();
	};

	this._readConfirmationControl = new CMessageReadConfirmationController(this.SendConfirmation, this);
	this._appointmentConfirmationControl = new CAppointmentConfirmationController();
	this._icsControl = new CIcsController();
	this._vcfControl = new CVcfController();

	this._messageId = -1;
	this._messageUid = '';
	this._msgCharset = AUTOSELECT_CHARSET;
	this._msgObj = null;
	this.forEditParams = [];
	this.fromDraftsParams = [];
	
	//manage folders + hide folders
	this._foldersExternalHeight = 22 + 20;
	this._foldersHeight = 100;
	
	this._msgResizerCont = null;
	this._msgResizerObj = null;
	
	this._inboxHeight = 100;
	this._inboxHeadersHeight = 21;
	//border + inbox headers
	this._minUpper = 1 + this._inboxHeadersHeight;

	this._replyTool = null;
	this._replyButton = null;
	this._replyAllButton = null;
	this._replyPopupMenu = null;
	this._forwardTool = null;

	this._previewPaneMessageHeaders = null;
	this._messagePadding = 16;
	this._minLower = 200;

	this._minListWidth = 460;
	this._minMessageWidth = 400;

	this._mailContMargins = null;

	this._msgFrameHorWidth = 6;
	this._msgFrameVertWidth = 10;
	this._messagePaneContainer = null;
	
	this._oAttachmentsPane = null;
	
	this.resizeScreen = function (mode)
	{
		var isAuto = false;
		var helpWidth = this._getHelpWidth();
		switch (mode) {
			case RESIZE_MODE_ALL:
				isAuto = this._resizeFoldersAndMessageListWidth(helpWidth, isAuto);
				isAuto = this._resizeMessageAndMessageListWidth(helpWidth, isAuto);
				isAuto = this._resizeHeight(isAuto);
				break;
			case RESIZE_MODE_FOLDERS:
				isAuto = this._resizeFoldersAndMessageListWidth(helpWidth, isAuto);
				break;
			case RESIZE_MODE_MSG_WIDTH:
				isAuto = this._resizeMessageAndMessageListWidth(helpWidth, isAuto);
				isAuto = this._resizeHeight(isAuto);
				break;
			case RESIZE_MODE_MSG_PANE:
				if (this._messageWidth !== undefined) {
					isAuto = this._resizeMessageWidth(this._messageWidth - this._msgFrameHorWidth);
					isAuto = this._resizeHeight(isAuto);
				}
				break;
			case RESIZE_MODE_MSG_HEIGHT:
				isAuto = this._resizeHeight(isAuto);
				break;
		}
        if (null != this._pageSwitcher) this._pageSwitcher.Replace();
		SetBodyAutoOverflow(isAuto);
		this._dragNDrop.resize();
	};

	this._getHelpWidth = function ()
	{
		var fldResizerWidth = this._vResizerCont.offsetWidth;
		fldResizerWidth = (fldResizerWidth > 0) ? (fldResizerWidth + 2) : 8;
		var msgResizerWidth = this._msgResizerCont.offsetWidth;
		msgResizerWidth = (msgResizerWidth > 0) ? (msgResizerWidth + 2) : 8;

		var marginWidth = (this._mailContMargins != null)
			? (this._mailContMargins.Left + this._mailContMargins.Right)
			: 0;
		var externalWidth = fldResizerWidth + msgResizerWidth + marginWidth;

		var screenWidth = GetWidth();
		return { screen: screenWidth, margin: marginWidth, fldResizer: fldResizerWidth,
			msgResizer: msgResizerWidth, external: externalWidth };
	};

	this._resizeFoldersAndMessageListWidth = function (helpWidth, isAuto)
	{
		var maxFoldersWidth = helpWidth.screen - (this._minListWidth + helpWidth.external + this._messageWidth) + (helpWidth.margin / 2);
		var fldWidth = this._foldersPane.resizeWidth(maxFoldersWidth, (helpWidth.margin / 2));

		var calculatedListWidth = helpWidth.screen - (fldWidth + helpWidth.external + this._messageWidth);
		var listWidth = Validator.correctNumber(calculatedListWidth, this._minListWidth);
		if (calculatedListWidth !== listWidth) {
			isAuto = true;
		}

		var msgMinLeftPos = fldWidth + this._minListWidth + helpWidth.external - helpWidth.margin / 2;
		this._msgResizerObj.updateMinLeftWidth(msgMinLeftPos);
		this._resizeInboxContainerWidth(listWidth);
		this._resizeInboxWidth();
		return isAuto;
	};

	this._resizeMessageAndMessageListWidth = function (helpWidth, isAuto)
	{
		var msgLeftBound = this._msgResizerObj.leftPosition + helpWidth.msgResizer;
		var msgWidth = Validator.correctNumber((helpWidth.screen - msgLeftBound), this._minMessageWidth);

		var fldWidth = this._foldersPane.GetWidth();

		var listWidth = helpWidth.screen - (fldWidth + helpWidth.external + msgWidth);
		var correctListWidth = Validator.correctNumber(listWidth, this._minListWidth);
		var difference = correctListWidth - listWidth;
		msgWidth = Validator.correctNumber(msgWidth - difference, this._minMessageWidth);
		this._messageWidth = msgWidth;
		var allWidth = fldWidth + correctListWidth + msgWidth + helpWidth.fldResizer + helpWidth.msgResizer;
		if ((allWidth + helpWidth.margin) > helpWidth.screen) {
			this._mailContDiv.style.width = allWidth + 'px';
			isAuto = true;
		}
		else {
			this._mailContDiv.style.width = 'auto';
		}

		var fldMinRightPos = msgWidth + this._minListWidth + helpWidth.external;
		this._foldersPane.setResizerMinRightPos(fldMinRightPos);
		this._picturesControl.resizeWidth(msgWidth - this._msgFrameHorWidth);
		this._readConfirmationControl.resizeWidth(msgWidth - this._msgFrameHorWidth);
		this._appointmentConfirmationControl.resizeWidth(msgWidth - this._msgFrameHorWidth);
		this._icsControl.resizeWidth(msgWidth - this._msgFrameHorWidth);
		this._vcfControl.resizeWidth(msgWidth - this._msgFrameHorWidth);
		this._sensivityControl.resizeWidth(msgWidth - this._msgFrameHorWidth);
		this._resizeMessageWidth(msgWidth - this._msgFrameHorWidth);
		this._resizeInboxContainerWidth(correctListWidth);
		this._resizeInboxWidth();
		if (isAuto == false) {
			this._msgResizerObj.leftPosition = helpWidth.screen - (msgWidth + helpWidth.msgResizer);
			Cookies.create('wm_msg_resizer', this._msgResizerObj.leftPosition);
		}
		return isAuto;
	},

	this._resizeHeight = function (isAuto)
	{
		var screenHeight = GetHeight();
		if (screenHeight < MIN_SCREEN_HEIGHT) {
			screenHeight = MIN_SCREEN_HEIGHT;
			isAuto = true;
		}
		var externalHeight = WebMail.getHeaderHeight();
		var marginHeight = (this._mailContMargins != null)
			? (this._mailContMargins.Top + this._mailContMargins.Bottom)
			: 0;
		var innerHeight = screenHeight - externalHeight - marginHeight;
		this._inboxHeight = innerHeight;
		this._mailContDiv.style.height = innerHeight + 'px';

		this._foldersPane.resizeHeight(innerHeight);
		this._messageListPane.resizeHeight(innerHeight);
		this._msgResizerObj.updateVerticalSize(innerHeight);

		var iMsgHeight = this._resizeMessageHeight(innerHeight);
		var iDiffVoiceViewerBorders = 4;
		this._voiceMessageViewPane.resizeHeight(iMsgHeight + iDiffVoiceViewerBorders);

		return isAuto;
	},
	
	this._resizeMessageWidth = function(width)
	{
		this._previewPaneMessageHeaders.resize(width);
		this._msgViewer.resizeWidth(width);
		this._messagePaneContainer.style.width = width + this._msgFrameHorWidth + 'px';
		this._replyPane.resizeWidth(width);
	};
	
    this._createResizer = function (parent)
    {
		var container = CreateChild(parent, 'div', [['class', 'wm_vresizer_transparent_part'],
			['style', 'display: inline; width: 6px; float: left; margin: 0 1px 0 -1px;']]);
		var resizer = CreateChild(container, 'div');
		resizer.className = 'wm_vresizer';
		var div = CreateChild(container, 'div');
		div.className = 'wm_vresizer_width';
        return {container: container, resizer: resizer};
    };
	
	this.build = function (container, PopupMenus)
	{
		var layout3PaneDiv = CreateChild(container, 'div');
		layout3PaneDiv.id = 'layout_3pane';
		layout3PaneDiv.className = 'wm_hide';
		this._mainContainer = layout3PaneDiv;
		
		this._mailContDiv = CreateChild(layout3PaneDiv, 'div', [['class', 'wm_mail_container wm_central_list_pane']]);
		this._mailContMargins = GetMargins(this._mailContDiv);

		var messagePaneContainer, msgResizer, fldResizer, foldersHtmlParent;
		if (window.RTL) {
			messagePaneContainer = CreateChild(this._mailContDiv, 'div', [['class', 'wm_message_container_parent_td'],
				['style', 'display: inline; float: left;']]);
			msgResizer = this._createResizer(this._mailContDiv);
			this._inboxContainer = CreateChild(this._mailContDiv, 'div', [['class', 'wm_message_list'],
				['style', 'display: inline; float: left;']]);
			fldResizer = this._createResizer(this._mailContDiv);
			foldersHtmlParent = CreateChild(this._mailContDiv, 'div', [['style', 'display: inline; float: left;']]);
		}
		else {
			foldersHtmlParent = CreateChild(this._mailContDiv, 'div', [['style', 'display: inline; float: left;']]);
			fldResizer = this._createResizer(this._mailContDiv);
			this._inboxContainer = CreateChild(this._mailContDiv, 'div', [['class', 'wm_message_list'],
				['style', 'display: inline; float: left;']]);
			msgResizer = this._createResizer(this._mailContDiv);
			messagePaneContainer = CreateChild(this._mailContDiv, 'div', [['class', 'wm_message_container_parent_td'],
				['style', 'display: inline; float: left;']]);
		}

		this._messageListPane = new CMessageListCentralPane(this._inboxContainer, PopupMenus);
		this.buildInboxTable();
		this._messageListPane.build(this._inboxTable);

		this._vResizerCont = fldResizer.container;
		this._msgResizerCont = msgResizer.container;
		this._messagePaneContainer = messagePaneContainer;
		this._buildMessageContainer(messagePaneContainer, PopupMenus);

		this._foldersPane = new CFoldersPane(foldersHtmlParent, this._dragNDrop, fldResizer.resizer, this._mainContainer, true);
		this.FillSpaceInfo(this._foldersPane.ProgressBarContainer);

		var msgResizerWidth = 4;
		var minLeftWidth = 620;
		var minRightWidth = this._minMessageWidth + 2;
		this._msgResizerObj = new CVerticalResizer(msgResizer.resizer, layout3PaneDiv, msgResizerWidth, minLeftWidth,
			minRightWidth, WebMail.Settings.msgResizerPos, 'WebMail.resizeBody(RESIZE_MODE_MSG_WIDTH);', 0, [
				WebMail, function (sType) {
					this.resizeProcess(sType);
				}
			]);

		this._pageSwitcher = new CPageSwitcher(this._messageListPane.pageSwitcherBar, true);
		
		this.isBuilded = true;
	};//build
	
	this._buildToolBar = function (container, PopupMenus)
	{
		var toolBar = new CToolBar(container, TOOLBAR_VIEW_WITH_CURVE);
		this._toolBar = toolBar;

		function CreateReplyClickFromReplyPane(obj, replyAction)
		{
			return function () {
				obj._replyPane.switchToFullForm(replyAction);
			};
		}
		var replyFunc = CreateReplyClickFromReplyPane(this, TOOLBAR_REPLY);
		var replyAllFunc = CreateReplyClickFromReplyPane(this, TOOLBAR_REPLYALL);
		//reply tool (reply, reply all); absent in drafts and sent items
		var replyParts = toolBar.addReplyItem(PopupMenus, false, replyFunc, replyAllFunc);
		this._replyTool = replyParts.replyReplace;
		this._replyButton = replyParts.replyButton;
		this._replyAllButton = replyParts.replyAllButton;
		this._replyPopupMenu = replyParts.replyPopupMenu;
		
		//forward tool; absent in drafts and sent items
		this._forwardTool = toolBar.addItem(TOOLBAR_FORWARD, CreateReplyClick(TOOLBAR_FORWARD), false);

		this._printButton = toolBar.addItem(TOOLBAR_PRINT_MESSAGE, null, true);
		this._saveButton = toolBar.addItem(TOOLBAR_SAVE_MESSAGE, null, true);
	};

	this._buildMessageContainer = function(mainTd, PopupMenus)
	{
		var parentdiv = CreateChild(mainTd, 'div');
		this._buildToolBar(parentdiv, PopupMenus);

		var bordersHeight = 0;
		var divTopCorners = CreateChild(parentdiv, 'div');
		var div = CreateChild(divTopCorners, 'div', [['class', 'wm_message_pane_corner1']]);
		bordersHeight += GetHeightStyle(div);
		div = CreateChild(divTopCorners, 'div', [['class', 'wm_message_pane_corner2']]);
		bordersHeight += GetHeightStyle(div);
		div = CreateChild(divTopCorners, 'div', [['class', 'wm_message_pane_corner3']]);
		bordersHeight += GetHeightStyle(div);
		div = CreateChild(divTopCorners, 'div', [['class', 'wm_message_pane_corner4']]);
		bordersHeight += GetHeightStyle(div);
		div = CreateChild(divTopCorners, 'div', [['class', 'wm_message_pane_corner5']]); //!!!
		bordersHeight += GetHeightStyle(div);

		var divMain = CreateChild(parentdiv, 'div', [['class', 'wm_message_pane_border']]);
		var borders = GetBorders(divMain);
		this._msgFrameHorWidth = borders.Left + borders.Right;

		this._picturesControl.build(divMain);
		this._sensivityControl.build(divMain);
		this._readConfirmationControl.build(divMain);
		this._previewPaneMessageHeaders = new CPreviewPaneMessageHeaders(false);
		this._previewPaneMessageHeaders.build(divMain);
		this._appointmentConfirmationControl.build(divMain);
		this._icsControl.build(divMain);
		this._vcfControl.build(divMain);
		this._oAttachmentsPane = new CAttachmentsPane(divMain);
		this._msgViewer = new CMessageViewPane(false);
		this._msgViewer.build(divMain, 0);
		this._msgViewer.setSwitcher(this._previewPaneMessageHeaders.SwitcherCont, 'wm_message_right', this._previewPaneMessageHeaders.SwitcherObj);

		this._replyPane = new CMessageReplyPane(divMain, false);
		
		this._voiceMessageViewPane = new CVoiceMessageViewPane(divMain, false);

		var divBottomCorners = CreateChild(parentdiv, 'div');
		div = CreateChild(divBottomCorners, 'div', [['class', 'wm_message_pane_corner5 bottom']]); //!!!
		bordersHeight += GetHeightStyle(div);
		div = CreateChild(divBottomCorners, 'div', [['class', 'wm_message_pane_corner4 bottom']]); //!!!
		bordersHeight += GetHeightStyle(div);
		div = CreateChild(divBottomCorners, 'div', [['class', 'wm_message_pane_corner3 bottom']]); //!!!
		bordersHeight += GetHeightStyle(div);
		div = CreateChild(divBottomCorners, 'div', [['class', 'wm_message_pane_corner2 bottom']]); //!!!
		bordersHeight += GetHeightStyle(div);
		div = CreateChild(divBottomCorners, 'div', [['class', 'wm_message_pane_corner1 bottom']]); //!!!
		bordersHeight += GetHeightStyle(div);
		this._msgFrameVertWidth = bordersHeight;
	};
}

CMessageListCentralPaneScreen.prototype.shieldShowType = function (bShow)
{
	if (this._msgViewer.shieldShowType)
	{
		this._msgViewer.shieldShowType(bShow);
	}
};

CMessageListCentralPaneScreen.prototype.enableDeleteTools = function ()
{
	this._messageListPane.enableDeleteTools(this.DeleteLikePop3());
};

CMessageListCentralPaneScreen.prototype._resizeMessageHeight = function (iHeight)
{
	var iExternalHeight = this._toolBar.getHeight();
	iExternalHeight += this._previewPaneMessageHeaders.getHeight();
	iExternalHeight += this._picturesControl.getHeight();
	iExternalHeight += this._readConfirmationControl.getHeight();
	iExternalHeight += this._appointmentConfirmationControl.getHeight();
	iExternalHeight += this._icsControl.getHeight();
	iExternalHeight += this._vcfControl.getHeight();
	iExternalHeight += this._sensivityControl.getHeight();
	iExternalHeight += this._msgFrameVertWidth;

	var iAllMsgHeight = iHeight - iExternalHeight;
	var iMaxReplyPaneHeight = Math.round(iAllMsgHeight * 0.4);
	this._replyPane.setMaxHeight(iMaxReplyPaneHeight);

	var iMsgHeight = iAllMsgHeight - this._replyPane.getHeight();
	if (this._msgViewer.bEmpty && this._oAttachmentsPane.bShown) {
		this._oAttachmentsPane.setHeight(iMsgHeight)
		this._msgViewer.hide();
	}
	else {
		this._oAttachmentsPane.setMaxHeight(iMaxReplyPaneHeight)
		var iAttachmentsHeight = this._oAttachmentsPane.getHeight();
		this._msgViewer.resizeHeight(iMsgHeight - iAttachmentsHeight);
	}
	return iAllMsgHeight;
};

CMessageListCentralPaneScreen.prototype.enableTools = function ()
{
	this._messageListPane.enableTools(this.DeleteLikePop3());
	this._replyPane.resetFlags();
};

CMessageListCentralPaneScreen.prototype.setMessageId = function (msgId, msgUid)
{
	this._replyPane.setMessageId(msgId, msgUid);
};

CMessageListCentralPaneScreen.prototype.slideAndShowReplyPane = function (bClear)
{
	this._replyPane.slideAndShow(bClear);
};

CMessageListCentralPaneScreen.prototype.endSlideReplyPane = function (sDir)
{
	this._replyPane.endSlide(sDir);
};

CMessageListCentralPaneScreen.prototype.ResetReplyPaneFlags = function (mode)
{
	this._replyPane.resetFlags(mode);
};

CMessageListCentralPaneScreen.prototype.endCheckMail = MessageListPrototype.endCheckMail;

CMessageListCentralPaneScreen.prototype.enableCheckMailTool = function ()
{
	this._messageListPane.enableCheckMailTool();
};

CMessageListCentralPaneScreen.prototype.startCheckMail = function(bHidden)
{
	this._messageListPane.startCheckMail(bHidden);
};

CMessageListCentralPaneScreen.prototype.RepairEmptyTools = function()
{
	this._messageListPane.RepairEmptyTools();
};

CMessageListCentralPaneScreen.prototype.RepairToolBar = function ()
{
	if (!this.isBuilded) return;

	var showNotSpam = this.isSpam();
	var showSpam = (!this.isSent() && !this.isDrafts() && !showNotSpam);
	this._messageListPane.RepairSpamTools(showSpam, showNotSpam);
	
	this._messageListPane.repairDeleteTools(this.DeleteLikePop3());
	
	if (WebMail.Accounts.currMailProtocol == POP3_PROTOCOL) {
		if (WebMail.Accounts.isInboxDirectMode) {
			if (this.isInbox()) {
				this._messageListPane.hideMarkTool();
				this._messageListPane.showInboxMoveItem();
			}
			else {
				this._messageListPane.showMarkTool();
				this._messageListPane.hideInboxMoveItem();
			}
			this._dragNDrop.SetMoveToInbox(false);
		}
		else {
			this._messageListPane.showMarkTool();
			this._messageListPane.showInboxMoveItem();
			this._dragNDrop.SetMoveToInbox(true);
		}
	}
	else {
		this._messageListPane.showMarkTool();
	}
	this._repairReplyTools();
	if (this.oKeyMsgList.iFolderId === -1 && this.oKeyMsgList.sFolderFullName.length === 0 && this.oKeyMsgList.sLookFor.length > 0) {
		this._messageListPane.disableInSearch(true);
	}
	else {
		this._messageListPane.disableInSearch(false);
	}
};

CMessageListCentralPaneScreen.prototype.Pop3DeleteToolEnabled = function ()
{
	return this._messageListPane.Pop3DeleteToolEnabled();
};

CMessageListCentralPaneScreen.prototype.AlreadyPop3Deleted = function (idArray)
{
	return this._messageListPane.AlreadyPop3Deleted(idArray);
};

CMessageListCentralPaneScreen.prototype.disablePop3DeleteTool = function (idArray)
{
	return this._messageListPane.disablePop3DeleteTool(idArray);
};

CMessageListCentralPaneScreen.prototype.ClearDeleteTools = function ()
{
	return this._messageListPane.ClearDeleteTools();
};

CMessageListCentralPaneScreen.prototype.ImapDeleteToolEnabled = function ()
{
	return this._messageListPane.ImapDeleteToolEnabled();
};

CMessageListCentralPaneScreen.prototype.AlreadyImapDeleted = function (idArray)
{
	return this._messageListPane.AlreadyImapDeleted(idArray);
};

CMessageListCentralPaneScreen.prototype.disableImapDeleteTool = function (idArray)
{
	return this._messageListPane.disableImapDeleteTool(idArray);
};

CMessageListCentralPaneScreen.prototype.SpamToolEnabled = function (type)
{
	return this._messageListPane.SpamToolEnabled(type);
};
	
CMessageListCentralPaneScreen.prototype.AlreadyMarkedSpam = function (type, idArray)
{
	return this._messageListPane.AlreadyMarkedSpam(type, idArray);
};

CMessageListCentralPaneScreen.prototype.enableToolsByOperation = function (operationType, deleteLikePop3)
{
	return this._messageListPane.enableToolsByOperation(operationType, deleteLikePop3);
};

CMessageListCentralPaneScreen.prototype.WriteMsgsCountInFolder = function (count) { };

CMessageListCentralPaneScreen.prototype.CleanMoveMenu = function ()
{
	return this._messageListPane.CleanMoveMenu();
};

CMessageListCentralPaneScreen.prototype.addToMoveMenu = function (idFolder, folderFullName, folderName, isInboxFolder)
{
	return this._messageListPane.addToMoveMenu(idFolder, folderFullName, folderName, isInboxFolder);
};

CMessageListCentralPaneScreen.prototype._resizeInboxWidth = function ()
{
	this._messageListPane.resizeWidth(this._inboxWidth);
};

CMessageListCentralPaneScreen.prototype._useOrFreeSort = function (iMsgsCountInFolder)
{
	var oAcct = WebMail.Accounts.getCurrentAccount();
	if (this.isDirectMode && !oAcct.bAllowSorting) {
		this._messageListPane.freeSort(false);
	}
	else {
		if (iMsgsCountInFolder > 0) {
			var bSortByFlags = !this.isDirectMode;
			this._messageListPane.useSort(bSortByFlags);
		}
		else {
			this._messageListPane.freeSort(true);
		}
	}
};

/* search */
CMessageListCentralPaneScreen.prototype.getSearchParameters = function ()
{
	return this._messageListPane.getSearchParameters();
}

CMessageListCentralPaneScreen.prototype.hideSearchFolders = function ()
{
	return this._messageListPane.hideSearchFolders();
};

CMessageListCentralPaneScreen.prototype.CheckVisibilitySearchForm = function (ev)
{
	return this._messageListPane.CheckVisibilitySearchForm(ev);
};

CMessageListCentralPaneScreen.prototype.CleanSearchFolders = function (hasFolderInDm)
{
	this._messageListPane.CleanSearchFolders(hasFolderInDm);
};

CMessageListCentralPaneScreen.prototype.showSearchForm = function ()
{
	this._messageListPane.showSearchForm();
};

CMessageListCentralPaneScreen.prototype.hideSearchForm = function ()
{
	this._messageListPane.hideSearchForm();
};

CMessageListCentralPaneScreen.prototype.PlaceSearchData = function (searchFields, lookFor)
{
	return this._messageListPane.PlaceSearchData(searchFields, lookFor);
};

CMessageListCentralPaneScreen.prototype.focusSearchForm = function ()
{
	return this._messageListPane.focusSearchForm();
};

CMessageListCentralPaneScreen.prototype.SetCurrSearchFolder = function (id, fullName)
{
	this._messageListPane.SetCurrSearchFolder(id, fullName);
};

CMessageListCentralPaneScreen.prototype.addToSearchFolders = function (name, id, fullName)
{
	return this._messageListPane.addToSearchFolders(name, id, fullName);
};
/* end search */

CMessageListCentralPaneScreen.prototype.placeData = MessageListPrototype.placeData;
CMessageListCentralPaneScreen.prototype.GetCurrMessageHistoryObject = MessageListPrototype.GetCurrMessageHistoryObject;
CMessageListCentralPaneScreen.prototype.resizeBody = MessageListPrototype.resizeBody;
CMessageListCentralPaneScreen.prototype.ChangeSkin = MessageListPrototype.ChangeSkin;
CMessageListCentralPaneScreen.prototype.RedrawFolderControls = MessageListPrototype.RedrawFolderControls;
CMessageListCentralPaneScreen.prototype.cleanMessageBody = MessageListPrototype.cleanMessageBody;
CMessageListCentralPaneScreen.prototype._fillMessageInfo = MessageListPrototype._fillMessageInfo;
CMessageListCentralPaneScreen.prototype._fillToolBarByMessage = MessageListPrototype._fillToolBarByMessage;
CMessageListCentralPaneScreen.prototype._fillPreviewPaneMessageHeaders = MessageListPrototype._fillPreviewPaneMessageHeaders;
CMessageListCentralPaneScreen.prototype._fillReplyPane = MessageListPrototype._fillReplyPane;
CMessageListCentralPaneScreen.prototype._fillByMessage = MessageListPrototype._fillByMessage;
CMessageListCentralPaneScreen.prototype.setVoiceMessageReadFlag = MessageListPrototype.setVoiceMessageReadFlag;
CMessageListCentralPaneScreen.prototype.setVoiceMessageDuration = MessageListPrototype.setVoiceMessageDuration;
CMessageListCentralPaneScreen.prototype.setVoiceMessagePosition = MessageListPrototype.setVoiceMessagePosition;
CMessageListCentralPaneScreen.prototype.stopVoiceMessage = MessageListPrototype.stopVoiceMessage;
CMessageListCentralPaneScreen.prototype.switchToHtmlPlain = MessageListPrototype.switchToHtmlPlain;
CMessageListCentralPaneScreen.prototype.getCurrFolderHistoryObject = MessageListPrototype.getCurrFolderHistoryObject;
CMessageListCentralPaneScreen.prototype.ClearSearch = MessageListPrototype.ClearSearch;
CMessageListCentralPaneScreen.prototype.stopSearch = MessageListPrototype.stopSearch;
CMessageListCentralPaneScreen.prototype.GetSortField = MessageListPrototype.GetSortField;
CMessageListCentralPaneScreen.prototype.PlaceFolderList = MessageListPrototype.PlaceFolderList;
CMessageListCentralPaneScreen.prototype.RevertMessageList = MessageListPrototype.RevertMessageList;
CMessageListCentralPaneScreen.prototype._placeMessageList = MessageListPrototype._placeMessageList;
CMessageListCentralPaneScreen.prototype._placeMessagesOperation = MessageListPrototype._placeMessagesOperation;
CMessageListCentralPaneScreen.prototype.show = MessageListPrototype.show;
CMessageListCentralPaneScreen.prototype.ChangeFromFieldInFolder = MessageListPrototype.ChangeFromFieldInFolder;
CMessageListCentralPaneScreen.prototype.FolderClick = MessageListPrototype.FolderClick;
CMessageListCentralPaneScreen.prototype._showSearchingMessage = MessageListPrototype._showSearchingMessage;
CMessageListCentralPaneScreen.prototype.restoreFromHistory = MessageListPrototype.restoreFromHistory;
CMessageListCentralPaneScreen.prototype.incMessageCountInDrafts = MessageListPrototype.incMessageCountInDrafts;
CMessageListCentralPaneScreen.prototype.markMessageAsRead = MessageListPrototype.markMessageAsRead;
CMessageListCentralPaneScreen.prototype.hide = MessageListPrototype.hide;
CMessageListCentralPaneScreen.prototype.clickBody = MessageListPrototype.clickBody;
CMessageListCentralPaneScreen.prototype.onKeyDown = MessageListPrototype.onKeyDown;
CMessageListCentralPaneScreen.prototype._resizeInboxContainerWidth = MessageListPrototype._resizeInboxContainerWidth;
CMessageListCentralPaneScreen.prototype.isInbox = MessageListPrototype.isInbox;
CMessageListCentralPaneScreen.prototype.isSent = MessageListPrototype.isSent;
CMessageListCentralPaneScreen.prototype.isDrafts = MessageListPrototype.isDrafts;
CMessageListCentralPaneScreen.prototype.isTrash = MessageListPrototype.isTrash;
CMessageListCentralPaneScreen.prototype.DeleteLikePop3 = MessageListPrototype.DeleteLikePop3;
CMessageListCentralPaneScreen.prototype.DeleteLikeImap = MessageListPrototype.DeleteLikeImap;
CMessageListCentralPaneScreen.prototype.isSpam = MessageListPrototype.isSpam;
CMessageListCentralPaneScreen.prototype.CleanFolderList = MessageListPrototype.CleanFolderList;
CMessageListCentralPaneScreen.prototype.allowSearchInAllFolders = MessageListPrototype.allowSearchInAllFolders;
CMessageListCentralPaneScreen.prototype.GerFolderForSearch = MessageListPrototype.GerFolderForSearch;
CMessageListCentralPaneScreen.prototype.CleanInboxLines = MessageListPrototype.CleanInboxLines;
CMessageListCentralPaneScreen.prototype.setNoMessagesFoundMessage = MessageListPrototype.setNoMessagesFoundMessage;
CMessageListCentralPaneScreen.prototype.setSearchErrorMessage = MessageListPrototype.setSearchErrorMessage;
CMessageListCentralPaneScreen.prototype.setRetrievingErrorMessage = MessageListPrototype.setRetrievingErrorMessage;
CMessageListCentralPaneScreen.prototype.RedrawControls = MessageListPrototype.RedrawControls;
CMessageListCentralPaneScreen.prototype.SetPageSwitcher = MessageListPrototype.SetPageSwitcher;
CMessageListCentralPaneScreen.prototype.ChangeDefOrder = MessageListPrototype.ChangeDefOrder;
CMessageListCentralPaneScreen.prototype.GetDefOrder = MessageListPrototype.GetDefOrder;
CMessageListCentralPaneScreen.prototype.ChangeFolder = MessageListPrototype.ChangeFolder;
CMessageListCentralPaneScreen.prototype.ChangeCurrFolder = MessageListPrototype.ChangeCurrFolder;
CMessageListCentralPaneScreen.prototype.requestSearchResults = MessageListPrototype.requestSearchResults;
CMessageListCentralPaneScreen.prototype.isMessagesOperationEnable = MessageListPrototype.isMessagesOperationEnable;
CMessageListCentralPaneScreen.prototype.confirmMessagesOperation = MessageListPrototype.confirmMessagesOperation;
CMessageListCentralPaneScreen.prototype.GetMessagesOperationToFolder = MessageListPrototype.GetMessagesOperationToFolder;
CMessageListCentralPaneScreen.prototype.GetMessagesOperationMessagesData = MessageListPrototype.GetMessagesOperationMessagesData;
CMessageListCentralPaneScreen.prototype._getMessagesOperationWorkFolder = MessageListPrototype._getMessagesOperationWorkFolder;
CMessageListCentralPaneScreen.prototype._getMessagesOperationXmlHeader = MessageListPrototype._getMessagesOperationXmlHeader;
CMessageListCentralPaneScreen.prototype.DisplayMessagesOperationInMessageList = MessageListPrototype.DisplayMessagesOperationInMessageList;
CMessageListCentralPaneScreen.prototype.DisplayMessagesOperationInFolderList = MessageListPrototype.DisplayMessagesOperationInFolderList;
CMessageListCentralPaneScreen.prototype.PerformMessagesOperation = MessageListPrototype.PerformMessagesOperation;
CMessageListCentralPaneScreen.prototype.GetNewMsgsCount = MessageListPrototype.GetNewMsgsCount;
CMessageListCentralPaneScreen.prototype.FillByFolders = MessageListPrototype.FillByFolders;
CMessageListCentralPaneScreen.prototype._getSearchResultsMessage = MessageListPrototype._getSearchResultsMessage;
CMessageListCentralPaneScreen.prototype.showPictures = MessageListPrototype.showPictures;
CMessageListCentralPaneScreen.prototype._fillByMessages = MessageListPrototype._fillByMessages;
CMessageListCentralPaneScreen.prototype._repairReplyTools = MessageListPrototype._repairReplyTools;
CMessageListCentralPaneScreen.prototype.RedrawPages = MessageListPrototype.RedrawPages;
CMessageListCentralPaneScreen.prototype.buildInboxTable = MessageListPrototype.buildInboxTable;
CMessageListCentralPaneScreen.prototype.buildFoldersPart = MessageListPrototype.buildFoldersPart;
CMessageListCentralPaneScreen.prototype._resetReplyTools = MessageListPrototype._resetReplyTools;
CMessageListCentralPaneScreen.prototype.FillSpaceInfo = MessageListPrototype.FillSpaceInfo;

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
