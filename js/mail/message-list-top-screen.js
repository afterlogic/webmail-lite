/*
 * Classes:
 *  CMessageListTopPaneScreen()
 *  CSpaceInfo()
 */

function CMessageListTopPaneScreen(sLookFor)
{
	this.id = SCREEN_MESSAGE_LIST_TOP_PANE;
	this.isBuilded = false;
	this.bodyAutoOverflow = false;
	this.shown = false;
	
	this._spaceInfoObj = new CSpaceInfo();

	this.isDirectMode = false;

	this.oKeyMsgList = new CKeyMessages(sLookFor);
	this._removeCount = 0;

	this._mainContainer = null;

	this.SearchFormObj = null;
	this._bigSearchForm = null;
	this._searchIn = null;
	this._quickSearch = null;
	this._slowSearch = null;

	this._toolBar = null;
	this._checkMailTool = null;
	this._pop3DeleteTool = null;
	this._imap4DeleteTool = null;
	this._markTool = null;
	this._moveMenu = null;
	this._inboxMoveItem = null;
	this._isSpamTool = null;
	this._notSpamTool = null;

	this._foldersObj = null;
	this.needToRefreshFolders = false;
	this.needToRefreshMessages = false;
	this._foldersParam = Array();
	this._currFolder = null;

	this._vResizerCont = null;

	this._pageSwitcher = null;
	
	this._inboxContainer = null;
	this._inboxTable = null;

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
	
	this._messagesInFolder = null;

	this._lowToolBar = null;

	//manage folders + hide folders
	this._foldersExternalHeight = 22 + 20;
	this._foldersHeight = 100;
	
	this._hResizerCont = null;
	this._hResizerHeight = 4;
	this._horizResizerObj = null;
	
	this._inboxHeight = 100;
	this._inboxHeadersHeight = 21;
	//border + inbox headers
	this._minUpper = 1 + this._inboxHeadersHeight;

	this._replyTool = null;
	this._replyButton = null;
	this._replyAllButton = null;
	this._replyPopupMenu = null;
	this._forwardTool = null;

	this._messagePaneContainer = null;
	this._previewPaneMessageHeaders = null;
	this._messagePadding = 16;
	this._minLower = 200;
	
	this._oAttachmentsPane = null;

	this.resizeScreen = function (mode)
	{
		if (mode == RESIZE_MODE_MSG_HEIGHT) {
			Cookies.create('wm_horiz_resizer', this._horizResizerObj._topPosition);
		}
		var isAuto = false;
		var height = GetHeight();
		var externalHeight = WebMail.getHeaderHeight() + this._toolBar.getHeight() +  this._lowToolBar.offsetHeight;
		var innerHeight = height - externalHeight;
		if (innerHeight < 300) {
			innerHeight = 300;
			isAuto = true;
		}

		if (mode == RESIZE_MODE_ALL || mode == RESIZE_MODE_MSG_HEIGHT) {
			this._inboxHeight = this._horizResizerObj._topPosition - this._minUpper;
			this._foldersPane.resizeHeight(innerHeight);
			this.resizeInboxHeight(innerHeight);
		}
		
		if (mode != RESIZE_MODE_MSG_HEIGHT) {
			var resizerWidth = this._vResizerCont.offsetWidth;
			resizerWidth = (resizerWidth > 0) ? (resizerWidth + 2) : 6;
			var screenwidth = GetWidth();
			var minListWidth = 550;
			
			var maxFoldersWidth = screenwidth - minListWidth - resizerWidth;
			var foldersWidth = this._foldersPane.resizeWidth(maxFoldersWidth);
			
			var listWidth = screenwidth - foldersWidth - resizerWidth;
			this._resizeInboxContainerWidth(listWidth);
			this._horizResizerObj.updateHorizontalSize(listWidth);
			
			if (this._inboxWidth > listWidth) isAuto = true;
		}

		this._resizeInboxWidth();
		if (mode != RESIZE_MODE_MSG_HEIGHT) {
			this._resizeMessageWidth();
		} else {
			this._msgViewer.resizeWidth(this._inboxWidth - this._messageHorBordersWidth);
		}
		if (mode == RESIZE_MODE_ALL || mode == RESIZE_MODE_MSG_HEIGHT) {
			this.resizeInboxHeight(innerHeight);
		}
		if (null != this._pageSwitcher) this._pageSwitcher.Replace();
		SetBodyAutoOverflow(isAuto);
		this._dragNDrop.resize();
	};
	
	this._resizeMessageWidth = function()
	{
		this._previewPaneMessageHeaders.resize(this._inboxWidth - this._messageHorBordersWidth);
		this._msgViewer.resizeWidth(this._inboxWidth - this._messageHorBordersWidth);
		this._replyPane.resizeWidth(this._inboxWidth - this._messageHorBordersWidth);
	};
	
	this._getMessageExternalHeight = function()
	{
		var inboxHeight = this._inboxTable.getHeight();
		this._hResizerHeight = this._hResizerCont.offsetHeight;
		var messageHeadersHeight = this._previewPaneMessageHeaders.getHeight()
			+ this._picturesControl.getHeight() + this._readConfirmationControl.getHeight()
			+ this._appointmentConfirmationControl.getHeight() 
			+ this._icsControl.getHeight() 
			+ this._vcfControl.getHeight() 
			+ this._sensivityControl.getHeight();
		return inboxHeight + this._inboxTable.VertBordersWidth + this._messageVertBordersWidth
			+ this._hResizerHeight + messageHeadersHeight;
	};
	
	this.resizeInboxHeight = function(height)
	{
		if (Validator.isPositiveNumber(height) && height >=100) {
			var messExternalHeight = this._getMessageExternalHeight();
			var messInnerHeight = height - messExternalHeight;
			if (messInnerHeight < 100) {
				this._inboxHeight -= 100 - messInnerHeight;
				messInnerHeight = 100;
			}
			if (this._inboxHeight < 100) {
				this._inboxHeight = 100;
			}
			this._inboxTable.setLinesHeight(this._inboxHeight);
			this._horizResizerObj._topPosition = this._inboxHeight + this._minUpper;

			var iMaxReplyPaneHeight = Math.round(messInnerHeight * 0.4);
			this._replyPane.setMaxHeight(iMaxReplyPaneHeight);
			var iMsgHeight = messInnerHeight - this._replyPane.getHeight();
			this._msgViewer.resizeHeight(iMsgHeight);

			var iVoiceMessageBorder = 1;
			this._voiceMessageViewPane.resizeHeight(messInnerHeight - iVoiceMessageBorder);
		}
	};

	this.build = function(container, PopupMenus)
	{
		this._toolBar = new CToolBar(container);
		this._buildToolBar(PopupMenus);

		var div = CreateChild(container, 'div');
		this._mainContainer = div;
		div.className = 'wm_hide';
		var tbl = CreateChild(div, 'table');
		tbl.className = 'wm_mail_container';
		var tr = tbl.insertRow(0);
		var foldersHtmlParent = tr.insertCell(0);
		foldersHtmlParent.rowSpan = 3;

		var td = tr.insertCell(1);
		td.rowSpan = 3;
		td.className = 'wm_vresizer_part';
		this._vResizerCont = td;
		var VResizer = CreateChild(td, 'div');
		VResizer.className = 'wm_vresizer';
		div = CreateChild(td, 'div');
		div.className = 'wm_vresizer_width';

		this._inboxContainer = tr.insertCell(2);
		this.buildInboxTable();

		tr = tbl.insertRow(1);
		td = tr.insertCell(0);
		td.className = 'wm_hresizer_part';
		this._hResizerCont = td;
		div = CreateChild(td, 'div');
		div.className = 'wm_hresizer_height';
		var HResizer = CreateChild(td, 'div');
		HResizer.className = 'wm_hresizer';
		div = CreateChild(td, 'div');
		div.className = 'wm_hresizer_height';

		tr = tbl.insertRow(2);
		td = tr.insertCell(0);
		td.className = 'wm_message_container_parent_td';
		this.buildMessageContainer(td);

		tr = tbl.insertRow(3);
		this._lowToolBar = tr.insertCell(0);
		this._lowToolBar.colSpan = 3;
		this._lowToolBar.className = 'wm_lowtoolbar';
		this._messagesInFolder = CreateChild(this._lowToolBar, 'span');
		this._messagesInFolder.className = 'wm_lowtoolbar_messages';
		this.WriteMsgsCountInFolder(0);

		this.FillSpaceInfo(this._lowToolBar);

		this._foldersPane = new CFoldersPane(foldersHtmlParent, this._dragNDrop, VResizer, this._mainContainer, false);
		this._horizResizerObj = new CHorizontalResizer(HResizer, this._mainContainer, 2, this._minUpper + 100, this._minLower, WebMail.Settings.horizResizerPos, 'WebMail.resizeBody(RESIZE_MODE_MSG_HEIGHT);', [
			WebMail, function (sType) {
				this.resizeProcess(sType);
			}
		]);

		this._pageSwitcher = new CPageSwitcher(this._inboxTable.getLines(), false);
		
		this.isBuilded = true;
	};//build
	
	this._buildToolBar = function (PopupMenus)
	{
		var obj = this;
		var toolbar = this._toolBar;
		//new message tool
		if (WebMail.Settings.allowComposeMessage) {
			toolbar.addItem(TOOLBAR_NEW_MESSAGE, NewMessageClickHandler, true);
		}
		//check mail tool
		this._checkMailTool = toolbar.addItem(TOOLBAR_CHECK_MAIL, function () {
		    obj.startCheckMail(false);
		}, true);

		function CreateReplyClickFromReplyPane(obj, replyAction)
		{
			return function () {
				obj._replyPane.switchToFullForm(replyAction);
			};
		}
		
		var replyFunc = (WebMail.Settings.bReplyPaneInBothLayouts) 
			? CreateReplyClickFromReplyPane(this, TOOLBAR_REPLY)
			: CreateReplyClick(TOOLBAR_REPLY);
		
		var replyAllFunc = (WebMail.Settings.bReplyPaneInBothLayouts) 
			? CreateReplyClickFromReplyPane(this, TOOLBAR_REPLYALL)
			: CreateReplyClick(TOOLBAR_REPLYALL);
		
		//reply tool (reply, reply all); absent in drafts
		var replyParts = toolbar.addReplyItem(PopupMenus, false, replyFunc, replyAllFunc);
		this._replyTool = replyParts.replyReplace;
		this._replyButton = replyParts.replyButton;
		this._replyAllButton = replyParts.replyAllButton;
		this._replyPopupMenu = replyParts.replyPopupMenu;
		//forward tool; absent in drafts
		this._forwardTool = toolbar.addItem(TOOLBAR_FORWARD, CreateReplyClick(TOOLBAR_FORWARD), false);
		this._printButton = toolbar.addItem(TOOLBAR_PRINT_MESSAGE, null, true);
		this._saveButton = toolbar.addItem(TOOLBAR_SAVE_MESSAGE, null, true);
		//mark tool; absent in inbox in direct mode in pop3
		this._markTool = toolbar.addMarkItem(PopupMenus, false);
		//move to folder tool; absent in inbox in direct mode in pop3
		var div = CreateChild(document.body, 'div');
		this._moveMenu = div;
		div.className = 'wm_hide';
		toolbar.addMoveItem(TOOLBAR_MOVE_TO_FOLDER, PopupMenus, div, false);
		//delete tools
		var deleteParts = toolbar.addPop3DeleteItem(PopupMenus, false);
		this._pop3DeleteTool = deleteParts.DeleteTool;
		this._emptySpamButton = deleteParts.EmptySpamButton;
		this._emptyTrashButton = deleteParts.EmptyTrashButton;
		this._deleteArrow = deleteParts.DeleteArrow;
		var deleteFunc = CreateToolBarItemClick(TOOLBAR_DELETE);
		this._imap4DeleteTool = toolbar.addItem(TOOLBAR_DELETE, deleteFunc, false);
		// spam tools
		this._isSpamTool = toolbar.addItem(TOOLBAR_IS_SPAM, function () {RequestMessagesOperationHandler(TOOLBAR_IS_SPAM, [], []);}, false);
		this._notSpamTool = toolbar.addItem(TOOLBAR_NOT_SPAM, function () {RequestMessagesOperationHandler(TOOLBAR_NOT_SPAM, [], []);}, false);
		
		var lookForBigInp = this.buildAdvancedSearchForm();
		var searchParts = toolbar.addSearchItems();
		this.SearchFormObj = new CSearchForm(this._bigSearchForm, searchParts.SmallForm,
			searchParts.DownButton.eCont, searchParts.UpButton.eCont,
			lookForBigInp, searchParts.lookFor);
		if (null != this._searchIn) {
			this.SearchFormObj.setSearchIn(this._searchIn);
		}
		searchParts.lookFor.onkeypress = function (ev) {
			if (isEnter(ev)) {
				obj.requestSearchResults();
			}
		};
		searchParts.ActionImg.onclick = function () {
			obj.requestSearchResults();
		};

		toolbar.addClearDiv();
		toolbar.hide();
	};

	this.buildMessageContainer = function(mainTd)
	{
		var div = CreateChild(mainTd, 'div');
		div.className = 'wm_message_container';
		this._messagePaneContainer = div;
		if (this._messageHorBordersWidth == undefined) {
			var borders = GetBorders(div);
			this._messageHorBordersWidth = borders.Left + borders.Right;
			this._messageVertBordersWidth = borders.Top + borders.Bottom;
		}
		
		var tbl = CreateChild(div, 'table');
		var tr = tbl.insertRow(0);
		var td = tr.insertCell(0);

		this._picturesControl.build(td);
		this._sensivityControl.build(td);
		this._readConfirmationControl.build(td);
		this._previewPaneMessageHeaders = new CPreviewPaneMessageHeaders(false);
		this._previewPaneMessageHeaders.build(td);
		this._appointmentConfirmationControl.build(td);
		this._icsControl.build(td);
		this._vcfControl.build(td);
		this._oAttachmentsPane = new CAttachmentsPane();
		tr = tbl.insertRow(1);
		td = tr.insertCell(0);
		this._msgViewer = new CMessageViewPane(true);
		this._msgViewer.build(td, 0);
		this._msgViewer.setSwitcher(this._previewPaneMessageHeaders.SwitcherCont, 'wm_message_right', this._previewPaneMessageHeaders.SwitcherObj);

		this._replyPane = new CMessageReplyPane(td, false);

		this._voiceMessageViewPane = new CVoiceMessageViewPane(td, false);
	};
	
	this.shieldShowType = function (bShow)
	{
		if (this._msgViewer.shieldShowType)
		{
			this._msgViewer.shieldShowType(bShow);
		}
	};
	
	this.enableDeleteTools = function ()
	{
		if (this.DeleteLikePop3()) {
			this._pop3DeleteTool.enabled = true;
			this._pop3DeleteTool.className = "wm_tb";
		}
		if (this.DeleteLikeImap()) {
			this._imap4DeleteTool.enable();
		}
	};

	this.enableTools = function ()
	{
		this.enableCheckMailTool();
		this.enableDeleteTools();
		this._notSpamTool.enable();
		this._isSpamTool.enable();
		this._replyPane.resetFlags();
	};

	this.ChangeHeaderFieldsLang = function()
	{
		if (this._fromColumn != undefined) this._fromColumn.SetContent();
		if (this._dateColumn != undefined) this._dateColumn.SetContent();
		if (this._sizeColumn != undefined) this._sizeColumn.SetContent();
		if (this._subjectColumn != undefined) this._subjectColumn.SetContent();
	};
	
	this._resizeInboxWidth = function ()
	{
		var offsetWidth = this._inboxContainer.offsetWidth;
		if (offsetWidth) {
			var width = this._inboxWidth - this._inboxTable.HorBordersWidth;
			if (offsetWidth > width) {
				this._inboxTable.resize(width);
			} else {
				this._inboxTable.resize(offsetWidth);
			}
		}
	};
	
	this.RepairToolBar = function ()
	{
		if (this.isBuilded) {
			var showNotSpam = this.isSpam();
			var showSpam = (!this.isSent() && !this.isDrafts() && !showNotSpam);
			this.RepairSpamTools(showSpam, showNotSpam);

			if (this.DeleteLikePop3()) {
				this._pop3DeleteTool.className = 'wm_tb';
				this._imap4DeleteTool.hide();
			}
			else {
				this._pop3DeleteTool.className = 'wm_hide';
				this._imap4DeleteTool.show();
			}
			
			if (WebMail.Accounts.currMailProtocol == POP3_PROTOCOL) {
				if (WebMail.Accounts.isInboxDirectMode) {
					if (this.isInbox()) {
						this._markTool.className = 'wm_hide';
						if (null != this._inboxMoveItem) this._inboxMoveItem.className = 'wm_menu_item';
					}
					else {
						this._markTool.className = 'wm_tb';
						if (null != this._inboxMoveItem) this._inboxMoveItem.className = 'wm_hide';
					}
					this._dragNDrop.SetMoveToInbox(false);
				}
				else {
					this._markTool.className = 'wm_tb';
					if (null != this._inboxMoveItem) this._inboxMoveItem.className = 'wm_menu_item';
					this._dragNDrop.SetMoveToInbox(true);
				}
			}
			else {
				this._markTool.className = 'wm_tb';
			}
			this._repairReplyTools();
			if (this.oKeyMsgList.iFolderId === -1 && this.oKeyMsgList.sFolderFullName.length === 0 && this.oKeyMsgList.sLookFor.length > 0) {
				this._toolBar.disableInSearch(true);
			}
			else {
				this._toolBar.disableInSearch(false);
			}
		}
	}; // RepairToolBar

	this.WriteMsgsCountInFolder = function (count)
	{
		this._messagesInFolder.innerHTML = (this.oKeyMsgList.sLookFor.length > 0) ?
			count + ' ' + Lang.Messages :
			count + ' ' + Lang.MessagesInFolder;
	}
}

CMessageListTopPaneScreen.prototype = MessageListPrototype;

CMessageListTopPaneScreen.prototype.setMessageId = function (msgId, msgUid)
{
	this._replyPane.setMessageId(msgId, msgUid);
};

CMessageListTopPaneScreen.prototype.slideAndShowReplyPane = function (bClear)
{
	this._replyPane.slideAndShow(bClear);
};

CMessageListTopPaneScreen.prototype.endSlideReplyPane = function (sDir)
{
	this._replyPane.endSlide(sDir);
};

CMessageListTopPaneScreen.prototype.ResetReplyPaneFlags = function (mode)
{
	this._replyPane.resetFlags(mode);
};


function CSpaceInfo()
{
	//public
	this.fill = function (container)
	{
		if (container != undefined) {
			this._container = container;
		}

		var account = WebMail.Accounts.getCurrentAccount();
		if (account && account.imapQuotaLimit === 0) {
			if (this._progressBarObj !== null) {
				this._progressBarObj.className = 'wm_hide';
			}
			return;
		}
		else {
			if (this._progressBarObj !== null) {
				this._progressBarObj.className = 'wm_lowtoolbar_space_info';
			}
		}

		this._build();
		if (!this._builded) return;

		var accountPercent = this._checkPercent(Math.round(account.imapQuota / account.imapQuotaLimit * 100));
		this._progressBarObj.title = Lang.YouUsing + ' ' + accountPercent + '% ' + Lang.OfYour + ' '
			+ GetFriendlySizeKB(account.imapQuotaLimit);
		this._accountUsedObj.style.width = accountPercent + 'px';
	};

	//private
	this._builded = false;
	this._container = null;
	this._progressBarObj = null;
	this._accountUsedObj = null;

	this._checkPercent = function (percent)
	{
		if (percent > 100) {
			percent = 100;
		}
		else if (percent < 0) {
			percent = 0;
		}
		return percent;
	};

	this._build = function ()
	{
		if (this._builded) return;
		if (this._container === null) return;

		this._progressBarObj = CreateChild(this._container, 'span');
		this._progressBarObj.className = 'wm_lowtoolbar_space_info';
		var div = CreateChild(this._progressBarObj, 'div');
		div.className = 'wm_progressbar';

		var usedDiv = CreateChild(div, 'div');
		usedDiv.className = 'wm_progressbar_used';
		this._accountUsedObj = usedDiv;

		this._builded = true;
	};
}

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
