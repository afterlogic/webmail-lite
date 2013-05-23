/*
 * Functions:
 *  CreateToolBarItemClick(type)
 *  CreateReplyClick(type)
 *  CreateMoveActionFunc(id, fullName)
 *  CreateFolderClickFunc(id, fullName, obj, element)
 * Prototypes:
 *  MessageListPrototype
 */

function CreateToolBarItemClick(type)
{
	return function () {
		RequestMessagesOperationHandler(type, [], []);
	};
}

function CreateReplyClick(type)
{
	return function () {
		WebMail.replyClick(type);
	};
}

function CreateMoveActionFunc(id, fullName) {
	return function() {
		RequestMessagesOperationHandler(TOOLBAR_MOVE_TO_FOLDER, [], [], id, fullName);
	};
}

function CreateFolderClickFunc(id, fullName, obj, element) {
	return function() {
		obj.FolderClick(id, fullName, element);
		return false;
	};
}

var MessageListPrototype = {

	placeData: function(data) {
		switch (data.type) {
			case TYPE_FOLDER_LIST:
				this.PlaceFolderList(data);
				break;
			case TYPE_MESSAGE_LIST:
				this._placeMessageList(data);
				break;
			case TYPE_MESSAGE:
				var currentMessageId = data.getIdForList(this.id);
				var currentLineObj = this.oSelection.GetViewedLine();
				var currentLineId = (currentLineObj == null) ? null : currentLineObj.id;
				if (currentLineId == null || currentLineId == currentMessageId) {
					this._msgObj = data;
					this._messageId = data.id;
					this._messageUid = data.uid;
					this._msgCharset = data.charset;
					this._fillByMessage();
				}
				break;
			case TYPE_MESSAGES_OPERATION:
				this._placeMessagesOperation(data);
				break;
			case TYPE_UPDATE:
				switch (data.value) {
					case 'save_vcf':
						this._vcfControl.onUpdate(data.sContactUid)
						break;
					case 'save_ics':
						this._icsControl.onUpdate(data.sEventUid)
						break;
					case 'process_appointment':
						this._appointmentConfirmationControl.onUpdate(data.sEventUid)
						break;
				}
		}
	},

	GetCurrMessageHistoryObject: function() {
		var historyObj = this.getCurrFolderHistoryObject();
		historyObj.MsgId = this._messageId;
		historyObj.MsgUid = this._messageUid;
		historyObj.MsgFolderId = this.oKeyMsgList.iFolderId;
		historyObj.MsgFolderFullName = this.oKeyMsgList.sFolderFullName;
		historyObj.MsgCharset = this._msgCharset;
		historyObj.MsgSize = 0;
		historyObj.MsgParts = [PART_MESSAGE_HEADERS, PART_MESSAGE_HTML, PART_MESSAGE_ATTACHMENTS, PART_MESSAGE_FULL_HEADERS];
		return historyObj;
	},

	resizeBody: function(mode) {
		if (this.isBuilded) {
			this.resizeScreen(mode);
			if (!Browser.ie && mode == RESIZE_MODE_ALL) {
				this.resizeScreen(mode);
			}
		}
	},

	ChangeSkin: function() {
		this._foldersPane.ChangeSkin();
		this.CleanFolderList();
		this.FillByFolders();
		this.RedrawPages(this.oKeyMsgList.iPage);
		this._fillByMessages();
	},

	RedrawFolderControls: function(redrawElement, id, fullName) {
		if (redrawElement) {
			if (this._currFolder) this._currFolder.className = 'wm_folder';
			redrawElement.className = 'wm_select_folder';
			this._currFolder = redrawElement;
			if (!this.oKeyMsgList.isEqualFolder(id, fullName)) {
				this.cleanMessageBody(true);
			}
		}
		if (id && fullName) {
			this.ChangeFolder(id, fullName);
		}
		if (id == -1 && fullName == '') {
			if (this._currFolder) this._currFolder.className = 'wm_folder';
			this.ChangeFolder(id, fullName);
		}
	},

	cleanMessageBody: function(showInfo) {
		this._msgObj = null;
		this._previewPaneMessageHeaders.clean();
		this._oAttachmentsPane.hide();
		if (this.oMsgList !== null && this.oMsgList.messagesCount > 0 && showInfo) {
			this._msgViewer.clean('<div class="wm_inbox_info_message">' + Lang.InfoNoMessageSelected +
			'<br /><div class="wm_view_message_info">' + Lang.InfoSingleDoubleClick + '</div></div>');
		}
		else {
			//TODO empty message is not good
			this._msgViewer.clean();
		}
		this._picturesControl.hide();
		this._sensivityControl.hide();
		this._readConfirmationControl.hide();
		if (this._appointmentConfirmationControl) {
			this._appointmentConfirmationControl.hide();
		}
		if (this._icsControl) {
			this._icsControl.hide();
		}
		if (this._vcfControl) {
			this._vcfControl.hide();
		}
		this._voiceMessageViewPane.hide();
		this._replyPane.hide();
		if (this.id == SCREEN_MESSAGE_LIST_CENTRAL_PANE) {
			this._messagePaneContainer.className = '';
			this.resizeScreen(RESIZE_MODE_MSG_PANE);
		}
		else {
			this._messagePaneContainer.className = 'wm_message_container';
			this.resizeScreen(RESIZE_MODE_ALL);
		}
		this._resetReplyTools(null);
	},

	showPictures: function(iSafety) {
		var oMsg = this._msgObj;
		if (oMsg === null) {
			return;
		}
		if (oMsg.safety != iSafety) {
			oMsg.showPictures();
			oMsg.safety = iSafety;
			this._msgViewer.fill(oMsg);
		}
		this._fillMessageInfo(oMsg);
		this.resizeScreen(RESIZE_MODE_MSG_HEIGHT);
	},

	_fillMessageInfo: function(msg, hidePicturesControl) {
		if (hidePicturesControl) {
			this._picturesControl.hide();
		}
		else {
			this._picturesControl.SetSafety(msg.safety);
			switch (msg.safety) {
				case SAFETY_NOTHING:
					this._picturesControl.show();
					var fromParts = GetEmailParts(HtmlDecode(msg.fromAddr));
					this._picturesControl.SetFromAddr(fromParts.email);
					break;
				case SAFETY_MESSAGE:
					this._picturesControl.show();
					break;
				case SAFETY_FULL:
					this._picturesControl.hide();
					break;
			}
		}
		if (this._sensivityControl) {
			this._sensivityControl.show(msg.sensivity);
		}
		if (this._readConfirmationControl) {
			if (msg.mailConfirmationValue && msg.mailConfirmationValue.length > 0) {
				this._readConfirmationControl.show();
			}
			else {
				this._readConfirmationControl.hide();
			}
		}

		if (this._appointmentConfirmationControl) {
			this._appointmentConfirmationControl.show(msg);
		}
		if (this._icsControl) {
			this._icsControl.show(msg);
		}
		if (this._vcfControl) {
			this._vcfControl.show(msg);
		}
	},

	_fillToolBarByMessage: function (msg)
	{
		if (msg !== null && msg.saveLink != '#') {
			var createSaveLinkFunc = function (link) {
				return function () {document.location = link;};
			};
			var saveFunc = createSaveLinkFunc(msg.saveLink);
			this._saveButton.changeHandler(saveFunc);
			this._saveButton.enable();
		}
		else {
			this._saveButton.disable();
		}

		if (msg !== null && msg.printLink != '#') {
			var createPrintLinkFunc = function (link) {
				return function () {return PopupPrintMessage(link);};
			};
			var printFunc = createPrintLinkFunc(msg.printLink);
			this._printButton.changeHandler(printFunc);
			this._printButton.enable();
			ContextMenu.enablePrint(printFunc);
		}
		else {
			this._printButton.disable();
			ContextMenu.disablePrint();
		}
	},

	_fillPreviewPaneMessageHeaders: function (oMsg)
	{
		var oContact = oMsg.fromContact;
		var sContactId = oContact.sContactId;
		if (sContactId.length > 0) {
			var stringDataKey = WebMail.DataSource.getStringDataKey(TYPE_CONTACT, {sContactId: sContactId});
			if (WebMail.DataSource.cache.existsData(TYPE_CONTACT, stringDataKey)) {
				oContact = WebMail.DataSource.cache.getData(TYPE_CONTACT, stringDataKey);
			}
		}
		this._previewPaneMessageHeaders.fill(oMsg, oContact);
		this._oAttachmentsPane.show(oMsg.attachments);
	},

	_fillReplyPane: function (msg) {
		var
			bAllowReplyPaneInLayout = (WebMail.Settings.bReplyPaneInBothLayouts || this.id === SCREEN_MESSAGE_LIST_CENTRAL_PANE),
			msgInSentFolder = this.isSent(msg.idFolder, msg.folderFullName),
			msgInDraftsFolder = this.isDrafts(msg.idFolder, msg.folderFullName)
		;
		if (bAllowReplyPaneInLayout && WebMail.Settings.allowReplyMessage && !msgInSentFolder && !msgInDraftsFolder) {
			this._replyPane.show(msg);
		}
	},

	_fillByMessage: function()
	{
		var msg = this._msgObj;
		if (msg.isVoice) {
			if (this.id == SCREEN_MESSAGE_LIST_CENTRAL_PANE) {
				this._messagePaneContainer.className = 'wm_voice_message';
			}
			else {
				this._messagePaneContainer.className = 'wm_message_container wm_voice_message';
			}
			this._previewPaneMessageHeaders.hide();
			this._oAttachmentsPane.hide();
			this._msgViewer.hide();
			this._voiceMessageViewPane.show(msg, this.id);
			this._resetReplyTools(null);
			this._fillMessageInfo(msg, true);
		}
		else {
			if (this.id == SCREEN_MESSAGE_LIST_CENTRAL_PANE) {
				this._messagePaneContainer.className = '';
			}
			else {
				this._messagePaneContainer.className = 'wm_message_container';
			}
			this._voiceMessageViewPane.hide();
			this._resetReplyTools(msg);
			this._fillToolBarByMessage(msg);
			this._fillPreviewPaneMessageHeaders(msg);
			var htmlMode = this._msgViewer.fill(msg);
			this._fillMessageInfo(msg, (!htmlMode));
			this._fillReplyPane(msg);
		}

		if (this.id == SCREEN_MESSAGE_LIST_CENTRAL_PANE) {
			this.resizeScreen(RESIZE_MODE_MSG_PANE);
		}
		else {
			this.resizeScreen(RESIZE_MODE_ALL);
		}
		if (null != this._pageSwitcher) this._pageSwitcher.Replace();
	},

	setVoiceMessageReadFlag: function (bReadFlag)
	{
		this._voiceMessageViewPane.setReadFlag(bReadFlag);
	},

	setVoiceMessageDuration: function (fMilliseconds)
	{
		this._voiceMessageViewPane.setDuration(fMilliseconds);
	},

	setVoiceMessagePosition: function (fPos)
	{
		this._voiceMessageViewPane.setPosition(fPos);
	},

	stopVoiceMessage: function (fPos)
	{
		this._voiceMessageViewPane.stop(fPos);
	},

	switchToHtmlPlain: function() {
		this._msgViewer.switchToHtmlPlain();
	},

	getCurrFolderHistoryObject: function(oMsg) {
		var oHistoryArgs = {
			ScreenId: this.id,
			oKeyMsgList: this.oKeyMsgList,
			RedrawType: REDRAW_NOTHING,
			RedrawObj: null,
			MsgId: null,
			MsgUid: null,
			MsgFolderId: null,
			MsgFolderFullName: null,
			MsgCharset: null,
			MsgParts: null
		};
		if (oMsg != undefined) {
			oHistoryArgs.MsgId = oMsg.id;
			oHistoryArgs.MsgUid = oMsg.uid;
			oHistoryArgs.MsgFolderId = oMsg.idFolder;
			oHistoryArgs.MsgFolderFullName = oMsg.folderFullName;
			oHistoryArgs.MsgCharset = oMsg.charset;
			oHistoryArgs.MsgSize = oMsg.size;
		}
		return oHistoryArgs;
	},

	ClearSearch: function() {
		var
			historyObj = this.getCurrFolderHistoryObject(),
			oFld = historyObj.oKeyMsgList.getFolder()
		;
		if (oFld.id === -1) {
			oFld = WebMail.getCurrentInboxFolder();
		}
		historyObj.oKeyMsgList = historyObj.oKeyMsgList.getNewBySearch(oFld.id,  oFld.fullName, 1, '', 0);
		SetHistoryHandler(historyObj);
	},

	stopSearch: function() {
		WebMail.DataSource.netLoader.abortRequests('get', 'messages');
		WebMail.hideInfo();

		var
			args = this.getCurrFolderHistoryObject(),
			oFld = args.oKeyMsgList.getFolder()
		;
		if (oFld.id === -1) {
			oFld = WebMail.getCurrentInboxFolder();
		}
		args.oKeyMsgList = args.oKeyMsgList.getNewBySearch(oFld.id,  oFld.fullName, 1, '', 0);
		GetMessageListHandler(args.RedrawType, args.RedrawObj, args.oKeyMsgList);
	},

	GetSortField: function(idFolder, folderFullName) {
		var isSentOrDrafts = (this.isDrafts(idFolder, folderFullName)
			|| this.isSent(idFolder, folderFullName));
		if (this.oKeyMsgList.iSortField == SORT_FIELD_FROM && isSentOrDrafts) {
			return SORT_FIELD_TO;
		}
		if (this.oKeyMsgList.iSortField == SORT_FIELD_TO && isSentOrDrafts) {
			return SORT_FIELD_FROM;
		}
		return this.oKeyMsgList.iSortField;
	},

	PlaceFolderList: function(data) {
		this._foldersObj = data;
		if (!this.isBuilded) return;
		if (this.shown || this.oKeyMsgList.iFolderId === -1 && this.oKeyMsgList.sFolderFullName == '') {
			this.CleanFolderList();
			this._foldersParam = Array();
			this.FillByFolders();
		}
		if (this.shown) {
			GetMessageListHandler(REDRAW_NOTHING, null, this.oKeyMsgList);
			this.resizeBody(RESIZE_MODE_ALL);
		}
	},

	RevertMessageList: function ()
	{
		if (this.oMsgList !== null) {
			this._placeMessageList(this.oMsgList);
		}
	},

	_placeMessageList: function (data)
	{
		var
			bEqualMsgsList = false
		;

		if (!this.shown) {
			return;
		}
		if (this._foldersObj.bAllFoldersInDm) {
			this.enableCheckMailTool();
			HeaderInfo.setNewMessagesCount();
		}
		var isSearchInAllFolders = (data.idFolder == -1 && data.folderFullName == ''
			&& data.lookFor.length > 0);
		var isMessageListForSelectedFolder = (this.oKeyMsgList.isEqualFolder(data.idFolder, data.folderFullName));
		if (!isMessageListForSelectedFolder && !isSearchInAllFolders) {
			return;
		}
		this.oMsgList = data;
		bEqualMsgsList = this.oKeyMsgList.isEqualMsgList(data);
		this.oKeyMsgList.update(data);

		this.PlaceSearchData(data._searchFields, data.lookFor);

		var oFldParams = this._foldersParam[this.oKeyMsgList.iFolderId + this.oKeyMsgList.sFolderFullName];
		if (oFldParams) {
			oFldParams.setPage(data.page);
			oFldParams.changeMsgsCounts(data.messagesCount, data.newMsgsCount, data.lookFor.length > 0);
			this.RedrawFolderControls(oFldParams.eContainer, data.idFolder, data.folderFullName);
		}
		this.WriteMsgsCountInFolder(data.messagesCount);
		this._useOrFreeSort(data.messagesCount);

		this._fillByMessages(bEqualMsgsList);
		this.RepairToolBar();

		setTimeout((function (aMessagesBodies, aFoldersParam) {
			return function () {
				PreFetch.getMessagesBodies(aMessagesBodies, aFoldersParam);
			}
		}(data.messagesBodies, this._foldersParam)), 500);
	},

	enableCheckMailTool: function ()
	{
		var
			oCurrAccount = null,
			iDiff = 0
		;
		if (!this._checkMailTool.enabled()) {
			this._checkMailTool.enable();
			WebMail.bHiddenCheckmail = false;
			WebMail.bCheckmail = false;
			oCurrAccount = WebMail.Accounts.getCurrentAccount();
			if (oCurrAccount.imapQuotaLimit > 0)
			{
				iDiff = oCurrAccount.imapQuotaLimit - oCurrAccount.imapQuota;
				if (iDiff <= 0) {
					WebMail.showError(Lang.WarningMailboxIsFull);
				}
				else if (Math.round((iDiff / oCurrAccount.imapQuotaLimit) * 100) < 5) {
					WebMail.showError(Lang.WarningMailboxAlmostFull);
				}
			}
		}
	},

	startCheckMail: function(bHidden)
	{
		if (this._checkMailTool.enabled()) {
			this._checkMailTool.disable();
			WebMail.bHiddenCheckmail = bHidden;
			WebMail.bCheckmail = true;
			var oFolders = this._foldersObj;
			if (oFolders === undefined) {
				var oListScreen = WebMail.getCurrentListScreen();
				if (oListScreen) {
					oFolders = oListScreen._foldersObj;
				}
			}
			if (oFolders && oFolders.bAllFoldersInDm) {
				WebMail.DataSource.cache.clearMessageList(-1, '');
				GetFolderListFromServer();
			}
			else {
				WebMail.CheckMail.start(bHidden);
			}
		}
	},

	endCheckMail: function() {
		WebMail.CheckMail.end();
		HeaderInfo.setNewMessagesCount();
		this.enableCheckMailTool();
	},

	enableToolsByOperation: function(operationType, deleteLikePop3) {
		switch (operationType) {
			case TOOLBAR_DELETE:
			case TOOLBAR_NO_MOVE_DELETE:
				this.enableDeleteTools(deleteLikePop3);
				break;
			case TOOLBAR_NOT_SPAM:
				this._notSpamTool.enable();
				break;
			case TOOLBAR_IS_SPAM:
				this._isSpamTool.enable();
				break;
		}
	},

	RepairSpamTools: function (showSpam, showNotSpam)
	{
		this._isSpamTool.hide();
		this._notSpamTool.hide();

		if (!WebMail.allowSpamTools(true)) return;

		if (showSpam) {
			this._isSpamTool.show();
		}
		if (showNotSpam) {
			this._notSpamTool.show();
		}
	},

	RepairEmptyTools: function ()
	{
		var allowTrash = WebMail.allowTrashTools();
		var allowSpam = WebMail.allowSpamTools(false);
		if (allowTrash) {
			this._emptyTrashButton.show();
		}
		else {
			this._emptyTrashButton.hide();
		}
		if (allowSpam) {
			this._emptySpamButton.show();
		}
		else {
			this._emptySpamButton.hide();
		}
		if (allowTrash || allowSpam) {
			this._deleteArrow.show();
		}
		else {
			this._deleteArrow.hide();
		}
	},

	_placeMessagesOperation: function(data) {
		this.enableToolsByOperation(data.operationInt, this.DeleteLikePop3());
		if (this.shown) {
			var fId = this.oKeyMsgList.iFolderId;var fName = this.oKeyMsgList.sFolderFullName;
			var trash = WebMail.getCurrentTrashFolder();
			if (trash != null && data.operationInt == TOOLBAR_PURGE && this.DeleteLikePop3()) {
				fId = trash.id;fName = trash.fullName;
			}
			var spam = WebMail.getCurrentSpamFolder();
			if (spam != null && data.operationInt == TOOLBAR_EMPTY_SPAM) {
				fId = spam.id;fName = spam.fullName;
			}
			var oFldParams = this._foldersParam[fId + fName];
			if (!oFldParams && this.oKeyMsgList.sLookFor.length == 0) {
				var dict = data.messages;
				var keys = dict.keys();
				if (keys.length == 1) {
					var folder = dict.getVal(keys[0]);
					fId = folder.idFolder;fName = folder.folderFullName;
					oFldParams = this._foldersParam[fId + fName];
				}
			}
			if (oFldParams) {
				switch (data.operationInt) {
					case TOOLBAR_MOVE_TO_FOLDER:
					case TOOLBAR_IS_SPAM:
					case TOOLBAR_NOT_SPAM:
						oFldParams.remove();
						var paramIndex = data.idToFolder + data.toFolderFullName;
						if (spam != null && data.operationInt == TOOLBAR_IS_SPAM) {
							paramIndex = spam.id + spam.fullName;
						}
						else if (data.operationInt == TOOLBAR_NOT_SPAM) {
							var inbox = WebMail.getCurrentInboxFolder();
							paramIndex = inbox.id + inbox.fullName;
						}
						if (this._foldersParam[paramIndex]) {
							this._foldersParam[paramIndex].append();
						}
						if (null != this._pageSwitcher) {
							var page = this._pageSwitcher.GetLastPage(this._removeCount);
							if (page < this.oKeyMsgList.iPage) {
								this.oKeyMsgList.updatePage(page);
							}
						}
						break;
					case TOOLBAR_DELETE:
					case TOOLBAR_NO_MOVE_DELETE:
						oFldParams.remove();
						if (this.DeleteLikePop3()) {
							if (trash != null && this._foldersParam[trash.id + trash.fullName]) {
								this._foldersParam[trash.id + trash.fullName].append();
							}
							else if (this.needToRefreshFolders) {
								GetHandler(TYPE_FOLDER_LIST, {idAcct: WebMail.iAcctId, sync: GET_FOLDERS_NOT_SYNC}, [], '');
							}
						}
						if (null != this._pageSwitcher) {
							var page = this._pageSwitcher.GetLastPage(this._removeCount);
							if (page < this.oKeyMsgList.iPage) {
								this.oKeyMsgList.updatePage(page);
							}
						}
						break;
					case TOOLBAR_PURGE:
						if (this.DeleteLikePop3()) {
							oFldParams.changeMsgsCounts(0, 0, false);
						}
					case TOOLBAR_EMPTY_SPAM:
						oFldParams.changeMsgsCounts(0, 0, false);
						break;
					case TOOLBAR_FLAG:
					case TOOLBAR_UNFLAG:
						WebMail.DataSource.cache.clearMessageList(fId, fName, true);
						break;
				}
				WebMail.DataSource.cache.setMessagesCount(fId, fName, oFldParams.iMsgsCount, oFldParams.iNewMsgsCount);
			}
			else if (this.oKeyMsgList.sLookFor.length > 0) {
				WebMail.DataSource.cache.clearMessageList(fId, fName);
				GetHandler(TYPE_FOLDER_LIST, {idAcct: WebMail.iAcctId, sync: GET_FOLDERS_NOT_SYNC}, [], '');
			}

			if (data.operationInt == TOOLBAR_PURGE && this.DeleteLikePop3() && this.isTrash()) {
				this.CleanInboxLines(Lang.InfoEmptyFolder);
				this.WriteMsgsCountInFolder(0);
			}
			else if (data.operationInt == TOOLBAR_EMPTY_SPAM && this.isSpam()) {
				this.CleanInboxLines(Lang.InfoEmptyFolder);
				this.WriteMsgsCountInFolder(0);
			}
			else if (data.operationInt == TOOLBAR_DELETE || data.operationInt == TOOLBAR_NO_MOVE_DELETE ||
					data.operationInt == TOOLBAR_IS_SPAM || data.operationInt == TOOLBAR_NOT_SPAM ||
					data.operationInt == TOOLBAR_MOVE_TO_FOLDER) {
				GetMessageListHandler(REDRAW_NOTHING, null, this.oKeyMsgList);
			}
			else {
				if (data.operationField != '') {
					var dict = data.messages;
					var keys = dict.keys();
					var idArray = [];
					for (var i in keys) {
						if (typeof(keys[i]) !== 'string') {
							continue;
						}
						var folder = dict.getVal(keys[i]);
						for (var j in folder.idArray) {
							if (typeof(folder.idArray[i]) !== 'string') {
								continue;
							}
							var msg = folder.idArray[j];
							var msgH = new CMessageHeaders();
							msgH.id = msg.id;
							msgH.uid = msg.uid;
							msgH.charset = msg.charset;
							msgH.size = msg.size;
							msgH.idFolder = folder.idFolder;
							msgH.folderFullName = folder.folderFullName;
							idArray.push(msgH.getIdForList(this.id));
						}
					}
					this.oSelection.SetParams(idArray, data.operationField, data.operationValue, data.isAllMess);
				}
			}
		}
	},

	show: function(historyArgs) {
		this.shown = true;
		this._mainContainer.className = 'wm_background';
		this._toolBar.show();
		this.resizeBody(RESIZE_MODE_ALL);
		if (null != historyArgs) {
			this.restoreFromHistory(historyArgs);
		}
		if (this._foldersObj === null && -1 != WebMail.iAcctId || this.needToRefreshFolders) {
			GetHandler(TYPE_FOLDER_LIST, {idAcct: WebMail.iAcctId, sync: GET_FOLDERS_NOT_SYNC}, [], '');
			this.needToRefreshFolders = false;
		}
		else {
			this.SetCurrSearchFolder(this.oKeyMsgList.iFolderId, this.oKeyMsgList.sFolderFullName);
		}
	},

	ChangeFromFieldInFolder: function(id, fullName) {
		var oFldParams = this._foldersParam[id + fullName];
		if (oFldParams) {
			if (oFldParams.bSentDraftsType) {
				this._inboxTable.changeField(IH_FROM, IH_TO, InboxHeaders[IH_TO]);
			}
			else {
				this._inboxTable.changeField(IH_TO, IH_FROM, InboxHeaders[IH_FROM]);
			}
		}
	},

	FolderClick: function(id, fullName, newFolder) {
		var oFldParams = this._foldersParam[id + fullName];
		if (oFldParams) {
			var oArgs = this.getCurrFolderHistoryObject();
			oArgs.oKeyMsgList = oArgs.oKeyMsgList.getNewByFolder(id,  fullName, oFldParams.iPage, this.GetSortField(id, fullName), '', 0, MESSAGE_LIST_FILTER_NONE);
			oArgs.RedrawType = REDRAW_FOLDER;
			oArgs.RedrawObj = newFolder;
			SetHistoryHandler(oArgs);
		}
	},

	_showSearchingMessage: function(idFolder, folderFullName, lookForString) {
		var oFldParams = this._foldersParam[idFolder + folderFullName];
		var searchResultsMessage;
		if (oFldParams) {
			searchResultsMessage = this._getSearchResultsMessage(idFolder, oFldParams.sName, oFldParams.iType, lookForString);
		}
		else {
			searchResultsMessage = this._getSearchResultsMessage(-1, '', '', lookForString);
		}
		this.CleanInboxLines(searchResultsMessage, Lang.Searching);
		this.cleanMessageBody(false);
	},

	restoreFromHistory: function(args) {
		if (null != args) {
			if (args.AcctChanged) {
				this.oKeyMsgList.reset(args.idAcct);
				this.oMsgList = null;
				this.needToRefreshFolders = false;
				this.CleanFolderList();
				this.CleanInboxLines(Lang.InfoMessagesLoad);
				this.cleanMessageBody(true);
				var stringDataKey = WebMail.DataSource.getStringDataKey(TYPE_FOLDER_LIST, {idAcct: args.idAcct});
				if (WebMail.DataSource.cache.existsData(TYPE_FOLDER_LIST, stringDataKey)) {
					WebMail.DataSource.needInfo = false;
					RequestHandler('update', 'id_acct', '<param name="id_acct" value="' + WebMail.iAcctId + '"/>');
					GetHandler(TYPE_FOLDER_LIST, {idAcct: args.idAcct, sync: GET_FOLDERS_NOT_SYNC}, [], '');
				} else {
					GetHandler(TYPE_ACCOUNT_BASE, {idAcct: args.idAcct, ChangeAcct: 1}, [], '');
				}
			}
			else {
				var needMsg = null != args.MsgId && null != args.MsgUid && null != args.MsgFolderId &&
					null != args.MsgFolderFullName && null != args.MsgCharset && null != args.MsgParts && null != args.MsgSize;
				if (undefined === args.oKeyMsgList && (this.oKeyMsgList.iFolderId !== -1 || this.oKeyMsgList.sLookFor.length !== 0)
					|| this.needToRefreshMessages) {
					if (!needMsg) {
						this.cleanMessageBody(false);
						this.CleanInboxLines(Lang.InfoMessagesLoad);
					}
					if (this.oKeyMsgList.sLookFor.length !== 0) {
						this._showSearchingMessage(this.oKeyMsgList.iFolderId, this.oKeyMsgList.sFolderFullName, this.oKeyMsgList.sLookFor);
					}
					GetMessageListHandler(REDRAW_NOTHING, null, this.oKeyMsgList);
					this.needToRefreshMessages = false;
				}
				else if (args.ForcedRequest || null === this.oMsgList || !this.oKeyMsgList.isEqual(args.oKeyMsgList)) {
					if (undefined !== args.oKeyMsgList && args.oKeyMsgList !== null && (args.oKeyMsgList.iFolderId !== -1 || args.oKeyMsgList.sLookFor.length !== 0)) {
						var paramIndex = args.oKeyMsgList.iFolderId + args.oKeyMsgList.sFolderFullName;
						var oFldParams = this._foldersParam[paramIndex];
						if (oFldParams) {
							this.ChangeCurrFolder(args.oKeyMsgList.iFolderId, args.oKeyMsgList.sFolderFullName, args.RedrawObj,
								oFldParams.iMsgsCount, oFldParams.iSyncType, oFldParams.iType);
						}
						if (!needMsg) {
							this.cleanMessageBody(false);
							this.CleanInboxLines(Lang.InfoMessagesLoad);
						}
						if (args.oKeyMsgList.sLookFor.length != 0) {
							this._showSearchingMessage(args.oKeyMsgList.iFolderId, args.oKeyMsgList.sFolderFullName, args.oKeyMsgList.sLookFor);
						}
						this.oKeyMsgList.copy(args.oKeyMsgList);
						GetMessageListHandler(args.RedrawType, args.RedrawObj, args.oKeyMsgList);
					}
					else {
						this.RedrawPages(this.oKeyMsgList.iPage);
					}
				}
				else {
					this.RedrawPages(this.oKeyMsgList.iPage);
				}
				if (needMsg && this.oMsgList !== null && this.oMsgList.hasMessage(args.MsgId, args.MsgUid)) {
					GetMessageHandler(args.MsgId, args.MsgUid, args.MsgFolderId,
						args.MsgFolderFullName, args.MsgParts, args.MsgCharset, args.msgSeen);
				}
			}
		}
	},

	incMessageCountInDrafts: function (iCount)
	{
		var
			oDrafts = WebMail.getCurrentDraftsFolder(),
			oFldParams = this._foldersParam[oDrafts.id + oDrafts.fullName]
		;
		if (oFldParams) {
			oFldParams.addToAppend(iCount, 0);
		}
	},

	markMessageAsRead: function (sMsgId, iMsgSize, bMarkOnServer)
	{
		var iReadedCount = this.oSelection.SetParams([sMsgId], 'read', true, false);
		if (iReadedCount > 0) {
			var oFldParams = this._foldersParam[this.oKeyMsgList.iFolderId + this.oKeyMsgList.sFolderFullName];
			if (oFldParams) {
				oFldParams.read(iReadedCount);
				WebMail.DataSource.cache.setMessagesCount(this.oKeyMsgList.iFolderId, this.oKeyMsgList.sFolderFullName,
					oFldParams.iMsgsCount, oFldParams.iNewMsgsCount);
			}
			if (bMarkOnServer) {
				WebMail.DataSource.needInfo = false;
				RequestMessagesOperationHandler(TOOLBAR_MARK_READ, [sMsgId], [iMsgSize]);
			}
		}
	},

	hide: function() {
		this.shown = false;
		this._mainContainer.className = 'wm_hide';
		this._toolBar.hide();
		this.hideSearchForm();
		if (null != this._pageSwitcher) {
			this._pageSwitcher.hide();
		}
		this._voiceMessageViewPane.hide();
	},

	clickBody: function(ev) {
		this.msgBodyFocus = this._msgViewer.overMsgBody;
		this.CheckVisibilitySearchForm(ev);
	},

	onKeyDown: function(key, ev) {
		switch (key) {
			case Keys.space:
				var scrolled = this._msgViewer.scrollDown();
				if (scrolled) return;
				this._inboxTable.onKeyDown(Keys.down, ev);
				break;
			case Keys.n:
				if (ev.shiftKey || ev.ctrlKey || ev.altKey) return;
				SetHistoryHandler({ScreenId: SCREEN_NEW_MESSAGE});
				break;
			case Keys.r:
				if (ev.shiftKey || ev.ctrlKey || ev.altKey) return;
				WebMail.replyClick(TOOLBAR_REPLY);
				break;
			case Keys.s:
				if (ev.altKey) {
					this.focusSearchForm();
				}
				break;
			default:
				if ((key == Keys.shift || key == Keys.ctrl) && ev.shiftKey && ev.ctrlKey) {
					this._msgViewer.changeRTL();
				}
				var msgBodyFocus = (Browser.mozilla || Browser.opera) ? this.msgBodyFocus : this._msgViewer.focusMsgBody;
				if (msgBodyFocus && (key == Keys.up || key == Keys.down || key == Keys.home ||
					key == Keys.end || key == Keys.pageUp || key == Keys.pageDown)) {
					break;
				}
				this._inboxTable.onKeyDown(key, ev);
				break;
		}
	},

	_resizeInboxContainerWidth: function(width) {
		this._inboxWidth = width;
		this._inboxContainer.style.width = width + 'px';
	},

	isInbox: function() {
		var inbox = WebMail.getCurrentInboxFolder();
		if (inbox === null) return false;
		return (this.oKeyMsgList.isEqualFolder(inbox.id, inbox.fullName));
	},

	isSent: function(id, fullName) {
		var sent = WebMail.getCurrentSentFolder();
		if (sent == null) return false;
		if (id != undefined && fullName != undefined) {
			return (sent.id == id && sent.fullName == fullName);
		}
		return (this.oKeyMsgList.isEqualFolder(sent.id, sent.fullName));
	},

	isDrafts: function(id, fullName) {
		var drafts = WebMail.getCurrentDraftsFolder();
		if (drafts == null) return false;
		if (id != undefined && fullName != undefined) {
			return (drafts.id == id && drafts.fullName == fullName);
		}
		return (this.oKeyMsgList.isEqualFolder(drafts.id, drafts.fullName));
	},

	isTrash: function(id, fullName) {
		var trash = WebMail.getCurrentTrashFolder();
		if (trash == null) return false;
		if (id != undefined && fullName != undefined) {
			return (trash.id == id && trash.fullName == fullName);
		}
		return (this.oKeyMsgList.isEqualFolder(trash.id, trash.fullName));
	},

	DeleteLikePop3: function() {
		return ((WebMail.Accounts.currMailProtocol != IMAP4_PROTOCOL) || WebMail.Settings.useImapTrash);
	},

	DeleteLikeImap: function() {
		return (WebMail.Accounts.currMailProtocol == IMAP4_PROTOCOL && !WebMail.Settings.useImapTrash);
	},

	isSpam: function() {
		var spam = WebMail.getCurrentSpamFolder();
		if (spam == null) return false;
		return (this.oKeyMsgList.isEqualFolder(spam.id, spam.fullName));
	},

	CleanFolderList: function() {
		this._foldersPane.CleanList();
		this.CleanMoveMenu();
		this.CleanSearchFolders(this._foldersObj.hasFolderInDm);
	},

	allowSearchInAllFolders: function ()
	{
		return (!this._foldersObj.hasFolderInDm);
	},

	GerFolderForSearch: function ()
	{
		if (WebMail.Accounts.currMailProtocol == POP3_PROTOCOL && this.isDirectMode) {
			return this._foldersObj.getFirstFolderInDb();
		}
		else {
			return {id: this.oKeyMsgList.iFolderId, fullName: this.oKeyMsgList.sFolderFullName};
		}
	},

	CleanInboxLines: function(msg1, msg2) {
		this._inboxTable.cleanLines(msg1, msg2);
		if (null != this._pageSwitcher) {
			this._pageSwitcher.hide();
		}
	},

	setNoMessagesFoundMessage: function() {
		this._inboxTable.setNoMessagesFoundMessage();
		if (null != this._pageSwitcher) {
			this._pageSwitcher.hide();
		}
	},

	setSearchErrorMessage: function() {
		this._inboxTable.setSearchErrorMessage();
		if (null != this._pageSwitcher) {
			this._pageSwitcher.hide();
		}
	},

	setRetrievingErrorMessage: function() {
		this._inboxTable.setRetrievingErrorMessage();
		if (null != this._pageSwitcher) {
			this._pageSwitcher.hide();
		}
	},

	RedrawControls: function(redrawIndex, redrawElement, oKeyMsgList) {
		switch (redrawIndex - 0) {
			case REDRAW_FOLDER:
				this.RedrawFolderControls(redrawElement);
				if (WebMail.listScreenId == SCREEN_MESSAGE_LIST_TOP_PANE) {
					this._inboxTable.setSort(oKeyMsgList.iSortField, oKeyMsgList.iSortOrder);
				}
				if (WebMail.listScreenId == SCREEN_MESSAGE_LIST_CENTRAL_PANE) {
					this._messageListPane.setSort(oKeyMsgList.iSortField, oKeyMsgList.iSortOrder);
				}
				this.RedrawPages(oKeyMsgList.iPage);
				break;
			case REDRAW_HEADER:
				if (WebMail.listScreenId == SCREEN_MESSAGE_LIST_TOP_PANE) {
					this._inboxTable.setSort(oKeyMsgList.iSortField, oKeyMsgList.iSortOrder);
				}
				if (WebMail.listScreenId == SCREEN_MESSAGE_LIST_CENTRAL_PANE) {
					this._messageListPane.setSort(oKeyMsgList.iSortField, oKeyMsgList.iSortOrder);
				}
				break;
			case REDRAW_PAGE:
				this.RedrawPages(oKeyMsgList.iPage);
				break;
		}
	},

	ChangeDefOrder: function(defOrder) {
		if ((defOrder % 2) === SORT_ORDER_ASC) {
			this.oKeyMsgList.updateSort(defOrder - SORT_ORDER_ASC, SORT_ORDER_ASC);
		}
		else {
			this.oKeyMsgList.updateSort(defOrder, SORT_ORDER_DESC);
		}
	},

	GetDefOrder: function() {
		return this.oKeyMsgList.iSortField + this.oKeyMsgList.iSortOrder;
	},

	ChangeFolder: function (id, fullName)
	{
		var oOldFldParams = this._foldersParam[this.oKeyMsgList.iFolderId + this.oKeyMsgList.sFolderFullName];
		if (oOldFldParams) {
			oOldFldParams.showMoveItem();
		}
		var oNewFldParams = this._foldersParam[id + fullName];
		if (oNewFldParams) {
			oNewFldParams.hideMoveItem();
			this.oKeyMsgList.updateCount(oNewFldParams.iMsgsCount);
		}
		this.oKeyMsgList.updateFolder(id, fullName);
		this.ChangeFromFieldInFolder(id, fullName);
		if (!this.oKeyMsgList.isEqualFolder(id, fullName)) {
			this.cleanMessageBody(true);
		}
		this.SetCurrSearchFolder(this.oKeyMsgList.iFolderId, this.oKeyMsgList.sFolderFullName, this.oKeyMsgList.sType);
	},

	ChangeCurrFolder: function(id, fullName, div, count, syncType) {
		if (count == 0) {
			if (this.oKeyMsgList.sLookFor.length > 0) {
				this.setNoMessagesFoundMessage();
			}
			else {
				this.CleanInboxLines(Lang.InfoEmptyFolder);
			}
		}
		if (div) {
			this.RedrawFolderControls(div, id, fullName);
		}
		else {
			this.ChangeFolder(id, fullName);
		}
		this.WriteMsgsCountInFolder(count);
		this.RepairToolBar();

		this.isDirectMode = (syncType == SYNC_TYPE_DIRECT_MODE);
		this._useOrFreeSort(count);
	},

	_useOrFreeSort: function(iMsgsCountInFolder) {
		var oAcct = WebMail.Accounts.getCurrentAccount();
		if (this.isDirectMode && !oAcct.bAllowSorting) {
			var bEveryplace = (iMsgsCountInFolder === 0);
			this._inboxTable.freeSort(bEveryplace);
		}
		else {
			if (iMsgsCountInFolder > 0) {
				var bSortByFlags = !this.isDirectMode;
				this._inboxTable.useSort(bSortByFlags);
			}
			else {
				this._inboxTable.freeSort(true);
			}
		}
	},

	GetCurrFolder: function() {
		if (this.oKeyMsgList.iFolderId != -1) {
			return {id: this.oKeyMsgList.iFolderId, fullName: this.oKeyMsgList.sFolderFullName};
		}
		else {
			return WebMail.getCurrentInboxFolder();
		}
	},

	CleanMoveMenu: function() {
		CleanNode(this._moveMenu);
		this._inboxMoveItem = null;
	},

	addToMoveMenu: function(iFolderId, sFolderFullName, sFolderName, bIsInboxFolder) {
		var eItem = CreateChild(this._moveMenu, 'div');
		eItem.onmouseover = function() {this.className = 'wm_menu_item_over';};
		eItem.onmouseout = function() {this.className = 'wm_menu_item';};
		eItem.onclick = CreateMoveActionFunc(iFolderId, sFolderFullName);
		eItem.className = 'wm_menu_item';
		eItem.innerHTML = sFolderName;
		if (bIsInboxFolder) {
			this._inboxMoveItem = eItem;
		}
		return eItem;
	},

	SetCurrSearchFolder: function (id, fullName)
	{
		var newValue = id + STR_SEPARATOR + fullName;
		this._searchIn.value = newValue;
		if (this._searchIn.value != newValue) {
			this.hideSearchForm();
		}
		else {
			this.showSearchForm();
		}
	},

	addToSearchFolders: function(name, id, fullName) {
		var option = CreateChild(this._searchIn, 'option');
		option.innerHTML = '&nbsp;' + name;
		option.value = id + STR_SEPARATOR + fullName;
	},

	getSearchParameters: function() {
		var aSearch = this._searchIn.value.split(STR_SEPARATOR);
		var iFolderId = aSearch[0] - 0;
		var sFolderFullName = aSearch[1];
		var iSearchMode = (this._quickSearch.checked) ? 0 : 1;
		var sLookFor = this.SearchFormObj.getStringValue();
		return {iFolderId: iFolderId, sFolderFullName: sFolderFullName, iSearchMode: iSearchMode, sLookFor: sLookFor};
	},

	requestSearchResults: function ()
	{
		var oSearch = this.getSearchParameters();

		var redrawType = REDRAW_NOTHING;
		var redrawObj = null;
		var folder = {id: oSearch.iFolderId, fullName: oSearch.sFolderFullName};
		if (oSearch.sLookFor.length === 0 && folder.id === -1) {
			folder = this.GetCurrFolder();
			var oFldParams = this._foldersParam[folder.id + folder.fullName];
			if (oFldParams) {
				redrawType = REDRAW_FOLDER;
				redrawObj = oFldParams.eContainer;
			}
		}

		var oArgs = this.getCurrFolderHistoryObject();
		oArgs.oKeyMsgList = oArgs.oKeyMsgList.getNewBySearch(folder.id,  folder.fullName, 1, oSearch.sLookFor, oSearch.iSearchMode);
		oArgs.RedrawType = redrawType;
		oArgs.RedrawObj = redrawObj;
		SetHistoryHandler(oArgs);
	},

	PlaceSearchData: function(searchFields, lookFor) {
		this._quickSearch.checked = (searchFields == 0);
		this._slowSearch.checked = (searchFields == 1);
		this.SearchFormObj.setStringValue(lookFor);
	},

	focusSearchForm: function() {
		this.SearchFormObj.focusSmallForm();
	},

	hideSearchFolders: function() {
		if (null != this.SearchFormObj && this.SearchFormObj.isShown == POPUP_HIDDEN) {
			this._searchIn.className = 'wm_hide';
		}
	},

	CheckVisibilitySearchForm: function(ev) {
		if (null != this.SearchFormObj) {
			this.SearchFormObj.checkVisibility(ev, Browser.mozilla);
		}
	},

	CleanSearchFolders: function(hasFolderInDm) {
		CleanNode(this._searchIn);
		if (!hasFolderInDm && window.UseDb) {
			this.addToSearchFolders(Lang.AllMailFolders, -1, '');
		}
		this.hideSearchFolders();
	},

	showSearchForm: function() {
		if (null != this.SearchFormObj) {
			this.SearchFormObj.show();
		}
	},

	hideSearchForm: function() {
		if (null != this.SearchFormObj) {
			this.SearchFormObj.hide();
		}
	},

/**
 * Messages operation functions
 */
	Pop3DeleteToolEnabled: function() {
		var enabled = (this._pop3DeleteTool.enabled == false) ? false : true;
		return enabled;
	},

	AlreadyPop3Deleted: function(idArray) {
		if (isEqualArray(this._pop3DeleteTool.idArray, idArray)) {
			return true;
		}
		return false;
	},

	disablePop3DeleteTool: function(idArray) {
		this._pop3DeleteTool.enabled = false;
		this._pop3DeleteTool.className = "wm_tb wm_toolbar_item_disabled";
		this._pop3DeleteTool.idArray = idArray;
	},

	ClearDeleteTools: function() {
		if (this._imap4DeleteTool) {
			this._imap4DeleteTool.enable();
			if (this._imap4DeleteTool.idArray) {
				this._imap4DeleteTool.idArray = [];
			}
		}
		if (this._pop3DeleteTool) {
			this._pop3DeleteTool.enabled = true;
			this._pop3DeleteTool.className = "wm_tb";
			if (this._pop3DeleteTool.idArray) {
				this._pop3DeleteTool.idArray = [];
			}
		}
	},

	ImapDeleteToolEnabled: function() {
		var enabled = (this._imap4DeleteTool.enabled == false) ? false : true;
		return enabled;
	},

	AlreadyImapDeleted: function(idArray) {
		if (isEqualArray(this._imap4DeleteTool.idArray, idArray)) {
			return true;
		}
		return false;
	},

	disableImapDeleteTool: function(idArray) {
		this._imap4DeleteTool.disable();
		this._imap4DeleteTool.idArray = idArray;
	},

	SpamToolEnabled: function(type) {
		var tool = (type == TOOLBAR_IS_SPAM) ? this._isSpamTool : this._notSpamTool;
		var enabled = (tool.enabled == false) ? false : true;
		return enabled;
	},

	AlreadyMarkedSpam: function(type, idArray) {
		var tool = (type == TOOLBAR_IS_SPAM) ? this._isSpamTool : this._notSpamTool;
		if (isEqualArray(tool.idArray, idArray)) {
			return true;
		}
		tool.disable();
		tool.idArray = idArray;
		return false;
	},

	isMessagesOperationEnable: function (type, msgIdArray, toFolder)
	{
		var operationForAllMsgs = (type == TOOLBAR_MARK_ALL_READ || type == TOOLBAR_MARK_ALL_UNREAD
			|| type == TOOLBAR_PURGE || type == TOOLBAR_EMPTY_SPAM);
		if (!operationForAllMsgs && msgIdArray.length == 0) {
			Dialog.alert(Lang.WarningMarkListItem);
			return false;
		}
		switch (type) {
			case TOOLBAR_DELETE:
				if (this.DeleteLikePop3()) {
					if (!this.Pop3DeleteToolEnabled()) return false;
					if (this.AlreadyPop3Deleted(msgIdArray)) return false;
				}
				if (this.DeleteLikeImap()) {
					if (!this.ImapDeleteToolEnabled()) return false;
					if (this.AlreadyImapDeleted(msgIdArray)) return false;
				}
				break;
			case TOOLBAR_NO_MOVE_DELETE:
				if (this.DeleteLikePop3()) {
					this.disablePop3DeleteTool(msgIdArray);
				}
				if (this.DeleteLikeImap()) {
					this.disableImapDeleteTool(msgIdArray);
				}
				break;
			case TOOLBAR_IS_SPAM:
			case TOOLBAR_NOT_SPAM:
				if (!this.SpamToolEnabled(type)) {return false};
				if (this.AlreadyMarkedSpam(type, msgIdArray)) {
					return false;
				}
				break;
			case TOOLBAR_MOVE_TO_FOLDER:
				if (this.oKeyMsgList.isEqualFolder(toFolder.id, toFolder.fullName)
						|| (toFolder.id == -1 && toFolder.fullName == '')) {
					return false;
				}
				break;
		}
		return true;
	},

	confirmMessagesOperation: function (type, msgIdArray, toFolder, msgsData)
	{
		switch (type) {
			case TOOLBAR_DELETE:
				if (this.DeleteLikePop3()) {
					if ((!WebMail.allowTrashTools() || this.isTrash()) && !this.isSpam()) {
						Dialog.confirm(Lang.ConfirmAreYouSure, (function (obj, msgIdArray, type, toFolder, msgsData) {
							return function ()
							{
								obj.disablePop3DeleteTool(msgIdArray);
								SendMessagesOperationHandler(type, toFolder, msgsData);
							};
						})(this, msgIdArray, type, toFolder, msgsData));
					}
					else {
						this.disablePop3DeleteTool(msgIdArray);
						SendMessagesOperationHandler(type, toFolder, msgsData);
					}
				}
				if (this.DeleteLikeImap()) {
					Dialog.confirm(Lang.ConfirmDirectModeAreYouSure, (function (obj, msgIdArray, type, toFolder, msgsData) {
						return function ()
						{
							obj.disableImapDeleteTool(msgIdArray);
							SendMessagesOperationHandler(type, toFolder, msgsData);
						};
					})(this, msgIdArray, type, toFolder, msgsData));
				}
				break;
			case TOOLBAR_PURGE:
			case TOOLBAR_EMPTY_SPAM:
				Dialog.confirm(Lang.ConfirmMessagesPermanentlyDeleted, (function (type, toFolder, msgsData) {
					return function ()
					{
						SendMessagesOperationHandler(type, toFolder, msgsData);
					};
				})(type, toFolder, msgsData));
				break;
			default:
				SendMessagesOperationHandler(type, toFolder, msgsData);
				break;
		}
	},

	GetMessagesOperationToFolder: function (type, idToFolder, toFolderFullName)
	{
		switch (type) {
			case TOOLBAR_MOVE_TO_FOLDER:
				return {id: idToFolder, fullName: toFolderFullName};
			case TOOLBAR_IS_SPAM:
				var spam = WebMail.getCurrentSpamFolder();
				if (spam != null) {
					return spam;
				}
				break;
			case TOOLBAR_NOT_SPAM:
				return WebMail.getCurrentInboxFolder();
			case TOOLBAR_DELETE:
				var trash = WebMail.getCurrentTrashFolder();
				if (trash != null && this.DeleteLikePop3() && !this.isSpam() && !this.isTrash()) {
					return trash;
				}
				break;
		}
		return {id: -1, fullName: ''};
	},

	GetMessagesOperationMessagesData: function (msgIdArray, msgSizeArray)
	{
		if (msgIdArray.length == 0) {
			return this.oSelection.GetCheckedLines();
		}
		else {
			return {idArray: msgIdArray, SizeArray: msgSizeArray, Unreaded: 0};
		}
	},

	_getMessagesOperationWorkFolder: function (type)
	{
		var trash = WebMail.getCurrentTrashFolder();
		if (trash != null && type == TOOLBAR_PURGE && this.DeleteLikePop3()) {
			return trash;
		}
		else {
			return {id: this.oKeyMsgList.iFolderId, fullName: this.oKeyMsgList.sFolderFullName};
		}
	},

	_getMessagesOperationXmlHeader: function (toFolder, workFolder, sDigDosId)
	{
		var xmlHeader = this.oKeyMsgList.getLookForNode();
		xmlHeader += '<folder id="' + workFolder.id + '">';
		xmlHeader += '<full_name>' + GetCData(workFolder.fullName) + '</full_name></folder>';
		xmlHeader += '<to_folder id="' + toFolder.id + '">';
		xmlHeader += '<full_name>' + GetCData(toFolder.fullName) + '</full_name></to_folder>';
		if (WebMail.Settings.bAllowDigDos) {
			xmlHeader += '<digdos>' + GetCData(sDigDosId) + '</digdos>';
		}
		return xmlHeader;
	},

	DisplayMessagesOperationInMessageList: function (type, msgIdArray)
	{
		switch (type) {
			case TOOLBAR_MARK_ALL_READ:
				this.oSelection.SetParams(msgIdArray, 'read', true, true);
				break;
			case TOOLBAR_MARK_ALL_UNREAD:
				this.oSelection.SetParams(msgIdArray, 'read', false, true);
				break;
			case TOOLBAR_MARK_READ:
				this.oSelection.SetParams(msgIdArray, 'read', true, false);
				break;
			case TOOLBAR_MARK_UNREAD:
				this.oSelection.SetParams(msgIdArray, 'read', false, false);
				break;
			case TOOLBAR_FLAG:
				this.oSelection.SetParams(msgIdArray, 'flagged', true, false);
				break;
			case TOOLBAR_UNFLAG:
				this.oSelection.SetParams(msgIdArray, 'flagged', false, false);
				break;
			case TOOLBAR_MOVE_TO_FOLDER:
			case TOOLBAR_IS_SPAM:
			case TOOLBAR_NOT_SPAM:
			case TOOLBAR_DELETE:
			case TOOLBAR_NO_MOVE_DELETE:
				this.oSelection.hideLines(msgIdArray);
				this._inboxTable.resizeFromField();
				this._inboxTable.addMessageToList(Lang.InfoMessagesLoad);
				break;
		}
	},

	DisplayMessagesOperationInFolderList: function (type, toFolder, msgsData)
	{
		var count = msgsData.idArray.length;
		var oToFldParams = this._foldersParam[toFolder.id + toFolder.fullName];
		if (oToFldParams) {
			oToFldParams.addToAppend(count, msgsData.Unreaded);
		}
		var oFldParams = this._foldersParam[this.oKeyMsgList.iFolderId + this.oKeyMsgList.sFolderFullName];
		if (!oFldParams) return;
		switch (type) {
			case TOOLBAR_MARK_ALL_READ:
				oFldParams.addAllToRead();
				break;
			case TOOLBAR_MARK_ALL_UNREAD:
				oFldParams.addAllToUnread();
				break;
			case TOOLBAR_MARK_READ:
				oFldParams.addToRead(msgsData.Unreaded);
				break;
			case TOOLBAR_MARK_UNREAD:
				oFldParams.addToUnread(count - msgsData.Unreaded);
				break;
			case TOOLBAR_MOVE_TO_FOLDER:
			case TOOLBAR_IS_SPAM:
			case TOOLBAR_NOT_SPAM:
			case TOOLBAR_DELETE:
			case TOOLBAR_NO_MOVE_DELETE:
				oFldParams.addToRemove(count, msgsData.Unreaded);
				this._removeCount = count;
				break;
		}
	},

	PerformMessagesOperation: function(type, toFolder, msgsData)
	{
		var
			workFolder = this._getMessagesOperationWorkFolder(type),
			bDigDosId = (WebMail.Settings.bAllowDigDos) ? DigDosDialog.isSelected() : false,
			sDigDosId = (WebMail.Settings.bAllowDigDos) ? DigDosDialog.getSelectedId() : '',
			xmlHeader = this._getMessagesOperationXmlHeader(toFolder, workFolder, sDigDosId),
			xmlMessages = '',
			viewMessageForOperation = false,
			sAction = 'operation_messages',
			sRequest = OperationTypes[type]
		;
		if (type === TOOLBAR_MOVE_TO_FOLDER && bDigDosId) {
			sAction = 'custom_set';
			sRequest = 'dos';
		}

		for (var i in msgsData.idArray) {
			if (typeof(msgsData.idArray[i]) !== 'string') {
				continue;
			}
			var msg = new CMessage();
			msg.getFromIdForList(msgsData.idArray[i], msgsData.SizeArray[i]);
			xmlMessages += msg.getInShortXML();
			if (null != this._msgObj && this._msgObj.isEqual(msg)) {
				viewMessageForOperation = true; //message in preview pane is message for operation
			}
		}
		var xml = '<messages>' + xmlHeader + xmlMessages + '</messages>';

		switch (type) {
			case TOOLBAR_MARK_ALL_READ:
			case TOOLBAR_MARK_ALL_UNREAD:
			case TOOLBAR_MARK_READ:
			case TOOLBAR_MARK_UNREAD:
			case TOOLBAR_FLAG:
			case TOOLBAR_UNFLAG:
				WebMail.DataSource.needInfo = false;
				break;
		}

		WebMail.DataSource.request({action: sAction, request: sRequest},
			xml);
		var viewMessageRemoved = (type == TOOLBAR_MOVE_TO_FOLDER || type == TOOLBAR_IS_SPAM
			|| type == TOOLBAR_NOT_SPAM || type == TOOLBAR_DELETE || type == TOOLBAR_NO_MOVE_DELETE)
			&& viewMessageForOperation;
		var allMessagesRemoved = (type == TOOLBAR_PURGE || type == TOOLBAR_EMPTY_SPAM);
		if (viewMessageRemoved || allMessagesRemoved) {
			//message in preview pane will remove
			this.cleanMessageBody(true);
		}
	},
/*
 * End of messages operation functions
 */

	GetNewMsgsCount: function ()
	{
		var oInbox = WebMail.getCurrentInboxFolder();
		if (oInbox !== null) {
			var oFldParams = this._foldersParam[oInbox.id + oInbox.fullName];
			if (oFldParams) {
				return oFldParams.iNewMsgsCount;
			}
		}
		return 0;
	},

	FillByFolders: function() {
		this._dragNDrop.CleanDropObjects();
		var
			aFolders = this._foldersObj.folders,
			oInbox = WebMail.getCurrentInboxFolder(),
			oAccount = WebMail.Accounts.getCurrentAccount(),
			iIndex = 0,
			iLen = aFolders.length,
			oFolder = null,
			sName = '', bNotDirectMode = false, bNotPop3 = false, eMoveItem = {},
			bInboxFolder = false, sParamIndex = '', oFldParams = null, eDiv = null,
			self = this, fClickHandler = function () {},
			oSearchFolder = WebMail.getFolderByName(WebMail.sSearchFolderName, false)
		;
		for (; iIndex < iLen; iIndex++) {
			oFolder = aFolders[iIndex];

			if (oAccount.bIgnoreSubscribeStatus || !oFolder.listHide) {
				if (this.oKeyMsgList.iFolderId == -1) {
					if (oSearchFolder !== null) {
						this.ChangeFolder(oSearchFolder.id, oSearchFolder.fullName);
						this.cleanMessageBody(true);
					}
					else if (oFolder.type == FOLDER_TYPE_INBOX) {
						this.ChangeFolder(oFolder.id, oFolder.fullName);
						this.cleanMessageBody(true);
					}
				}

				sName = oFolder.getNameByType();
				bNotDirectMode = (oFolder.syncType != SYNC_TYPE_DIRECT_MODE);
				bNotPop3 = (WebMail.Accounts.currMailProtocol != POP3_PROTOCOL);
				eMoveItem = {};
				if (oInbox !== null && (bNotDirectMode || bNotPop3) && oFolder.noselect === false) {
					bInboxFolder = (oFolder.id == oInbox.id && oFolder.fullName == oInbox.fullName);
					eMoveItem = this.addToMoveMenu(oFolder.id, oFolder.fullName, oFolder.strIndent + sName, bInboxFolder);
					this.addToSearchFolders(oFolder.strIndent + sName, oFolder.id, oFolder.fullName,  oFolder.type);
				}

				sParamIndex = oFolder.id + oFolder.fullName;
				oFldParams = this._foldersParam[sParamIndex];
				if (!oFldParams) {
					oFldParams = new CFolderParams(oFolder.id, oFolder.fullName,
						oFolder.sentDraftsType, oFolder.type, oFolder.syncType,
						oFolder.msgCount, oFolder.newMsgCount, sName, oFolder.intIndent,
						oFolder.noselect, eMoveItem);
				}

				eDiv = CreateChild(this._foldersPane.list, 'div', [['class', 'wm_folder']]);
				fClickHandler = CreateFolderClickFunc(oFolder.id, oFolder.fullName, self, eDiv);
				oFldParams.setContainer(eDiv, fClickHandler);
				if (this.oKeyMsgList.isEqualFolder(oFolder.id, oFolder.fullName)) {
					this.ChangeCurrFolder(oFolder.id, oFolder.fullName, eDiv, oFldParams.iMsgsCount, oFolder.syncType, oFolder.type);
				}
				eDiv.id = oFolder.id + STR_SEPARATOR + oFolder.fullName;
				if (oFolder.type == FOLDER_TYPE_INBOX) {
					this._dragNDrop.SetInboxId(eDiv.id);
				}
				if (oFolder.noselect === false) {
					this._dragNDrop.addDropObject(eDiv);
				}
				this._foldersParam[sParamIndex] = oFldParams;
			}
		}
		this.RepairToolBar();
		this.hideSearchFolders();
	}, //FillByFolders

	_getSearchResultsMessage: function(idFolder, folderName, folderType, lookForString) {
		if (idFolder == -1) {
			return Lang.SearchResultsInAllFolders.replace(/#s/g, lookForString);
		}
		else {
			var normalName = CFolder.prototype.getNameByType(folderType, folderName);
			return Lang.SearchResultsInFolder.replace(/#s/g, lookForString).replace(/#f/g, normalName);
		}
	},

	_fillByMessages: function(bEqualMsgsList) {
		var msgsArray = new Array();
		var msgsObj = this.oMsgList;
		if (msgsObj != null) {
			msgsArray = msgsObj.list;
		}
		if (msgsArray.length == 0) {
			if (this.oKeyMsgList.sLookFor.length > 0) {
				if (msgsObj.bError) {
					this.setSearchErrorMessage();
				}
				else {
					this.setNoMessagesFoundMessage();
				}
			}
			else {
				if (msgsObj.bError) {
					this.setRetrievingErrorMessage();
				}
				else {
					this.CleanInboxLines(Lang.InfoEmptyFolder);
				}
			}
		}
		else {
			if (WebMail.listScreenId === SCREEN_MESSAGE_LIST_TOP_PANE) {
				this._inboxTable.setSort(this.oKeyMsgList.iSortField, this.oKeyMsgList.iSortOrder);
			}
			if (WebMail.listScreenId === SCREEN_MESSAGE_LIST_CENTRAL_PANE) {
				this._messageListPane.setSort(this.oKeyMsgList.iSortField, this.oKeyMsgList.iSortOrder);
			}
			var doFlag = !(WebMail.Accounts.currMailProtocol == POP3_PROTOCOL
				&& WebMail.Accounts.isInboxDirectMode == true && this.isInbox());
			this._inboxController.SetDoFlag(doFlag);
			var additionalMessage = '';
			if (msgsObj.lookFor.length > 0) {
				additionalMessage = this._getSearchResultsMessage(msgsObj.idFolder,
					msgsObj.folderName, msgsObj.folderType, msgsObj.lookFor)
				}

			var addClearLink = true;
			/*
			if (additionalMessage == '') {
				if (this.isSpam()) {
					additionalMessage = 'SPAM';
					addClearLink = false;
				}
				else if (this.isTrash()) {
					additionalMessage = 'TRASH';
					addClearLink = false;
				}
			}*/

			var oCurrLine = this._inboxTable.fill(msgsArray, this.id, additionalMessage, addClearLink, false, bEqualMsgsList);
			if (oCurrLine === null) {
				this.cleanMessageBody(true);
			}
			else {
				var aParts = [PART_MESSAGE_HEADERS, PART_MESSAGE_HTML, PART_MESSAGE_ATTACHMENTS, PART_MESSAGE_FULL_HEADERS];
				if (this.isDrafts()) {
					aParts.push(PART_MESSAGE_UNMODIFIED_PLAIN_TEXT);
				}
				GetMessageHandler(oCurrLine.MsgId, oCurrLine.MsgUid, oCurrLine.MsgFolderId,
						oCurrLine.MsgFolderFullName, aParts, oCurrLine.charset, oCurrLine.read);
			}
			this.RedrawPages(this.oKeyMsgList.iPage);
			if (this.id == SCREEN_MESSAGE_LIST_TOP_PANE) {
				this._repairReplyTools();
				this._resizeInboxWidth();
			}
			else {
				WebMail.resizeBody(RESIZE_MODE_ALL);
			}
		}
	}, //_fillByMessages

	RedrawPages: function(page) {
		if (this.shown && this.oMsgList && this._pageSwitcher) {
			var perPage = WebMail.Settings.msgsPerPage;
			var count = this.oMsgList.messagesCount;
			this._pageSwitcher.show(page, perPage, count, 'GetPageMessagesHandler(', ');');
			this._pageSwitcher.Replace();
		}
	},

	buildInboxTable: function() {
		this._inboxController = new CMessageListTableController('ClickMessageHandler');
		var inboxTable;
		if (this.id == SCREEN_MESSAGE_LIST_CENTRAL_PANE) {
			inboxTable = new CMessageListDisplay(this.oSelection, this._dragNDrop, this._inboxController);
		}
		else {
			inboxTable = new CVariableTable(SortMessagesHandler, this.oSelection, this._dragNDrop, this._inboxController);
		}
		inboxTable.addColumn(IH_CHECK, InboxHeaders[IH_CHECK]);
		if (window.AddPriorityHeader) {
			inboxTable.addColumn(IH_PRIORITY, InboxHeaders[IH_PRIORITY]);
		}
		if (window.AddSensivityHeader) {
			inboxTable.addColumn(IH_SENSIVITY, InboxHeaders[IH_SENSIVITY]);
		}
		inboxTable.addColumn(IH_ATTACHMENTS, InboxHeaders[IH_ATTACHMENTS]);
		inboxTable.addColumn(IH_FLAGGED, InboxHeaders[IH_FLAGGED]);
		if (this.id == SCREEN_MESSAGE_LIST_CENTRAL_PANE) {
			inboxTable.addColumn(IH_FROM, InboxHeaders[IH_FROM]);
			inboxTable.build(this._messageListPane.MainContainer);
		}
		else {
			this._fromColumn = inboxTable.addColumn(IH_FROM, InboxHeaders[IH_FROM]);
			this._dateColumn = inboxTable.addColumn(IH_DATE, InboxHeaders[IH_DATE]);
			this._sizeColumn = inboxTable.addColumn(IH_SIZE, InboxHeaders[IH_SIZE]);
			this._subjectColumn = inboxTable.addColumn(IH_SUBJECT, InboxHeaders[IH_SUBJECT]);
			inboxTable.build(this._inboxContainer);
		}
		this._inboxTable = inboxTable;
	},

	_repairReplyTools: function ()
	{
		if (WebMail.Settings.allowReplyMessage && !this.isDrafts() && !this.isSent()) {
			this._replyTool.className = 'wm_tb';
		}
		else {
			this._replyTool.className = 'wm_hide';
		}
		if (WebMail.Settings.allowForwardMessage && !this.isDrafts()) {
			this._forwardTool.show();
		}
		else {
			this._forwardTool.hide();
		}
	},

	_resetReplyTools: function(oMsg) {
		if (oMsg == null || oMsg.sensivity != SENSIVITY_NOTHING || oMsg.noForward) {
			this._forwardTool.disable();
		}
		else {
			this._forwardTool.enable();
		}
		if (oMsg == null || oMsg.noReply) {
			this._replyButton.disable();
			this._replyPopupMenu.disable = true;
		}
		else {
			this._replyButton.enable();
			this._replyPopupMenu.disable = false;
		}
		if (oMsg == null || oMsg.noReplyAll) {
			this._replyAllButton.disable();
		}
		else {
			this._replyAllButton.enable();
		}
		if (oMsg == null) {
			this._printButton.disable();
			this._saveButton.disable();
		}
	},

	buildAdvancedSearchForm: function() {
		var obj = this;
		var div = CreateChild(document.body, 'div', [['id', 'search_form_' + Math.random()]]);
		this._bigSearchForm = div;
		div.className = 'wm_hide';
		var frm = CreateChild(div, 'form');
		frm.onsubmit = function() {return false;};
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
		this._toolBar.CreateSearchButton(td, function() {obj.requestSearchResults();});
		lookForBigInp.onkeypress = function(ev) {
			if (isEnter(ev)) {
				obj.requestSearchResults();
			}
		};
		tr = tbl.insertRow(1);
		td = tr.insertCell(0);
		td.className = 'wm_search_title';
		td.innerHTML = Lang.SearchIn;
		WebMail.langChanger.register('innerHTML', td, 'SearchIn', '');
		td = tr.insertCell(1);
		td.className = 'wm_search_value';
		this._searchIn = CreateChild(td, 'select');
		if (null != this.SearchFormObj) this.SearchFormObj.setSearchIn(this._searchIn);
		tr = tbl.insertRow(2);
		td = tr.insertCell(0);
		td.className = 'wm_search_value';
		td.colSpan = 2;
		var nobr = CreateChild(td, 'nobr');
		var inp = CreateChild(nobr, 'input', [['type', 'radio'], ['name', 'qsmode' + this.id], ['id', 'qmode' + this.id]]);
		this._quickSearch = inp;
		inp.className = 'wm_checkbox';
		inp.checked = true;
		var lbl = CreateChild(nobr, 'label', [['for', 'qmode' + this.id]]);
		lbl.innerHTML = Lang.QuickSearch;
		WebMail.langChanger.register('innerHTML', lbl, 'QuickSearch', '');
		tr = tbl.insertRow(3);
		td = tr.insertCell(0);
		td.className = 'wm_search_value';
		td.colSpan = 2;
		nobr = CreateChild(td, 'nobr');
		inp = CreateChild(nobr, 'input', [['type', 'radio'], ['name', 'qsmode' + this.id], ['id', 'smode' + this.id]]);
		this._slowSearch = inp;
		inp.className = 'wm_checkbox';
		inp.checked = false;
		lbl = CreateChild(nobr, 'label', [['for', 'smode' + this.id]]);
		lbl.innerHTML = Lang.SlowSearch;
		WebMail.langChanger.register('innerHTML', lbl, 'SlowSearch', '');
		return lookForBigInp;
	}, // buildAdvancedSearchForm

	FillSpaceInfo: function (container)
	{
		this._spaceInfoObj.fill(container);
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
