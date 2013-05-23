/*
 * Handlers:
 *  MailAllHandler(toAddr, ccAddr, bccAddr)
 *  MailToHandler(toAddr)
 *  MailAllHandlerWithDropDown(addr)
 *  SendConfirmationHandler(toAddr, subject)
 *  SendAppointmentConfirmationHandler(oAppointment, bAccepted, sAction)
 *  SaveIcs(oIcs)
 *  SaveVcf(oVcf)
 *  ViewMessageInNewWindow(msg)
 *  ViewMessageInNewTab(msg)
 *  EditMessageFromDrafts(msg)
 *  OpenContextMenuHandler(sId, oController, oMsgList, iX, iY)
 *  OpenAllSelectedMessagesHandler()
 *  DblClickHandler()
 *  ClickMessageHandler(id)
 *  GetPageMessagesHandler(page)
 *  SortMessagesHandler()
 *  ResizeMessagesTab(number)
 *  GetMessageListHandler(redrawIndex, redrawElement, oKeyMsgList, background)
 *  GetMessageHandler(iMsgId, sMsgUid, iFldId, sFldFullName, aMsgParts, iCharset, bSeen)
 *  MoveToFolderHandler(id)
 *  RequestMessagesOperationHandler(type, idArray, sizeArray, idToFolder, toFolderFullName)
 *  SetCounterValueHandler()
 *  SetStateTextHandler(text)
 *  SetCheckingFolderHandler(folder, count)
 *  SetRetrievingMessageHandler(number)
 *  SetDeletingMessageHandler(number)
 *  SetUpdatedFolders(foldersArray, showInfo)
 *  EndCheckMailHandler(error)
 *  CheckEndCheckMailHandler()
 *  GetAutoFillingContactsHandler()
 *  SelectSuggestionHandler()
 *  PlaceViewMessageHandler()
 *  ShowPicturesHandler(safety)
 *  SetMessageSafetyHandler(msg)
 *  SetSenderSafetyHandler(fromAddr)
 *  MarkMsgAsRepliedHandler(msg)
 *  ClearSentAndDraftsHandler()
 *  ClearDraftsAndSetMessageId(id, uid)
 *  NewMessageClickHandler(ev, toAddr)
 *  ConfirmBeforeUnload()
 */

function MailAllHandler(toAddr, ccAddr, bccAddr)
{
	var historyObj = {
			ScreenId: SCREEN_NEW_MESSAGE,
			fromDrafts: false,
			ForReply: false,
			fromContacts: true,
			ToField: toAddr,
			CcField: ccAddr,
			BccField: bccAddr
		};
	SetHistoryHandler(historyObj);
}

function MailToHandler(toAddr)
{
	MailAllHandler(toAddr, '', '');
}

var addrDropDown;
function MailAllHandlerWithDropDown(addr)
{
//	if (UseCustomContacts) {
		if (addr && addr.length > 0) {
			if (addrDropDown) {
				addrDropDown.InitAddr(addr);
				addrDropDown.hide();
			} else {
				addrDropDown = new CToAddressDropDown(addr);
			}
			addrDropDown.show();
		}
//	} else {
//		MailToHandler(addr);
//	}
}

function SendConfirmationHandler(toAddr, subject)
{
	var xml = '<confirmation>' + GetCData(toAddr) + '</confirmation><subject>' + GetCData(subject) + '</subject>';
	RequestHandler('send', 'confirmation', xml);
}

function SendAppointmentConfirmationHandler(oAppointment, bAccepted, sAction)
{
	var
		sTypeAttr = ' type="' + oAppointment.sType + '"',
		sFileAttr = ' file="' + oAppointment.sFile + '"',
		sAcceptedAttr = ' accepted="' + (bAccepted ? '1' : '0') + '"',
		sActionAttr = ' action="' + sAction + '"',
		sCalIdNode = '<calendar_id>' + GetCData(oAppointment.sCalId) + '</calendar_id>',
		sXml = '<appointment' + sTypeAttr + sFileAttr + sAcceptedAttr + sActionAttr + '>' + 
			sCalIdNode + '</appointment>'
	;
	RequestHandler('process', 'appointment', sXml);
}

function SaveIcs(oIcs, sCalId)
{
	var
		sFileAttr = ' file="' + oIcs.sFile + '"',
		sCalIdNode = '<calendar_id>' + GetCData(sCalId) + '</calendar_id>',
		sXml = '<ics' + sFileAttr + '>' + 
			sCalIdNode + '</ics>'
	;
	RequestHandler('save', 'ics', sXml);
}

function SaveVcf(oVcf)
{
	var
		sFileAttr = ' file="' + oVcf.sFile + '"',
		sXml = '<vcf' + sFileAttr + '></vcf>'
	;
	RequestHandler('save', 'vcf', sXml);
}

function ViewMessageInNewWindow(msg, sOpenMode, iType, sText)
{
    msg.setMode([PART_MESSAGE_HEADERS, PART_MESSAGE_HTML, PART_MESSAGE_MODIFIED_PLAIN_TEXT, PART_MESSAGE_ATTACHMENTS, PART_MESSAGE_FULL_HEADERS]);
	var params = '?open_mode=' + sOpenMode;
	if (sOpenMode === 'reply') {
		params += '&reply_type=' + iType;
		params += '&reply_text=' + sText;
	}
	params += '&msg_id=' + msg.id;
	params += '&msg_uid=' + encodeURIComponent(msg.uid);
	params += '&folder_id=' + msg.idFolder;
	params += '&folder_full_name=' + encodeURIComponent(msg.folderFullName);
	params += '&charset=' + msg.charset;
	params += '&mode=' + msg.parts;
	params += '&size=' + msg.size;
	var sWindowName = msg.id + msg.uid + msg.idFolder + msg.folderFullName;
	WindowOpener.open(MiniWebMailUrl + params, sWindowName);
}

function ViewMessageInNewTab(msg)
{
	if (WebMail.Settings.allowReplyMessage) {
		msg.setMode([PART_MESSAGE_HEADERS, PART_MESSAGE_HTML, PART_MESSAGE_REPLY_HTML, PART_MESSAGE_ATTACHMENTS, PART_MESSAGE_FULL_HEADERS]);
	}
	else {
		msg.setMode([PART_MESSAGE_HEADERS, PART_MESSAGE_HTML, PART_MESSAGE_ATTACHMENTS, PART_MESSAGE_FULL_HEADERS]);
	}
	var form = CreateChild(document.body, 'form', [['action', WebMailUrl], ['method', 'post'], ['class', 'wm_hide'],
		['enctype', 'multipart/form-data'], ['target', '_blank'], ['id', 'view_message_form']]);
	CreateChild(form, 'input', [['type', 'hidden'], ['value', '5'], ['name', 'start']]);
	CreateChild(form, 'input', [['type', 'hidden'], ['value', msg.id], ['name', 'msg_id']]);
	CreateChild(form, 'input', [['type', 'hidden'], ['value', msg.uid], ['name', 'msg_uid']]);
	CreateChild(form, 'input', [['type', 'hidden'], ['value', msg.idFolder], ['name', 'folder_id']]);
	CreateChild(form, 'input', [['type', 'hidden'], ['value', msg.folderFullName], ['name', 'folder_full_name']]);
	CreateChild(form, 'input', [['type', 'hidden'], ['value', msg.charset], ['name', 'charset']]);
	CreateChild(form, 'input', [['type', 'hidden'], ['value', msg.parts], ['name', 'mode']]);
	CreateChild(form, 'input', [['type', 'hidden'], ['value', msg.size], ['name', 'size']]);
	form.submit();
}

function EditMessageFromDrafts(msg)
{
	SetHistoryHandler(
		{
			ScreenId: SCREEN_NEW_MESSAGE,
			fromDrafts: true,
			MsgId: msg.id,
			MsgUid: msg.uid,
			MsgFolderId: msg.idFolder,
			MsgFolderFullName: msg.folderFullName,
			MsgCharset: msg.charset,
			MsgParts: [PART_MESSAGE_HEADERS, PART_MESSAGE_HTML, PART_MESSAGE_UNMODIFIED_PLAIN_TEXT, PART_MESSAGE_ATTACHMENTS, PART_MESSAGE_FULL_HEADERS]
		}
	);
}

function OpenContextMenuHandler(sId, oController, oMsgList, iX, iY)
{
	var
		oMsgsData = oMsgList.oSelection.GetCheckedLines(),
		bInArray = ($.inArray(sId, oMsgsData.idArray) !== -1),
		oDigdosFolder = WebMail.getFolderByName(WebMail.Settings.sDigDosName, true),
		oListScreen = WebMail.getCurrentListScreen(),
		bDigdos = (oDigdosFolder !== null && oListScreen !== null 
			&& oListScreen.oKeyMsgList.iFolderId === oDigdosFolder.id
			&& oListScreen.oKeyMsgList.sFolderFullName === oDigdosFolder.fullName)
	;
	if (!bInArray) {
		oMsgList.oSelection.CheckLine(sId, true);
		oController.ClickLine(sId, oMsgList);
		oMsgsData = oMsgList.oSelection.GetCheckedLines();
	}

	var bManyItems = (oMsgsData.idArray.length > 1);
	if (bManyItems) {
		ContextMenu.openManyItemsMenu(iX, iY, bDigdos);
	}
	else {
		var bUnreaded = ((oMsgsData.Unreaded > 0) && bInArray);
		ContextMenu.openOneItemMenu(iX, iY, bDigdos, bUnreaded);
	}
}

function OpenAllSelectedMessagesHandler()
{
	var oListScreen = WebMail.getCurrentListScreen();
	if (oListScreen == null) return;

	var oMsgsData = oListScreen.oSelection.GetCheckedLines();
	var iCount = oMsgsData.idArray.length;
	for (var i = 0; i < iCount; i++) {
		DblClickHandler.call({id: oMsgsData.idArray[i]});
	}
}

function DblClickHandler()
{
	var screen = WebMail.getCurrentListScreen();
	if (screen == null) return;

	var line = screen.oSelection.GetLineById(this.id);
	if (screen.Id === SCREEN_MESSAGE_LIST_CENTRAL_PANE && line.isVoice) {
		return;
	}
	var msg = new CMessage();
	msg.getFromIdForList(this.id, line.MsgSize);

	if (screen.isDrafts()) {
		EditMessageFromDrafts(msg);
	}
	else {
		ViewMessageInNewWindow(msg, 'view');
	}
}

function ClickMessageHandler(sMsgId)
{
	var oScreen = WebMail.getCurrentListScreen();
	if (oScreen === null) return;

	var oLine = oScreen.oSelection.GetLineById(sMsgId);
	var oMsg = new CMessage();
	oMsg.getFromIdForList(sMsgId, oLine.MsgSize);
	var bRead = true;
	if (oLine.isVoice) {
		var oTopListScreen = WebMail.Screens[SCREEN_MESSAGE_LIST_TOP_PANE];
		if (oTopListScreen) oTopListScreen.setVoiceMessageReadFlag(oLine.read);
		var oCentralListScreen = WebMail.Screens[SCREEN_MESSAGE_LIST_CENTRAL_PANE];
		if (oCentralListScreen) oCentralListScreen.setVoiceMessageReadFlag(oLine.read);
		bRead = oLine.read;
	}
	if (null == oScreen._msgObj || oMsg.id != oScreen._msgObj.id || oMsg.uid != oScreen._msgObj.uid ||
	  oMsg.idFolder != oScreen._msgObj.idFolder || oMsg.folderFullName != oScreen._msgObj.folderFullName ||
	  oMsg.charset != oScreen._msgObj.charset) {
		oScreen.cleanMessageBody(false);
		var aParts = [PART_MESSAGE_HEADERS, PART_MESSAGE_HTML, PART_MESSAGE_ATTACHMENTS, PART_MESSAGE_FULL_HEADERS];
		if (oScreen.isDrafts()) {
			aParts.push(PART_MESSAGE_UNMODIFIED_PLAIN_TEXT);
		}
		var oArgs = oScreen.getCurrFolderHistoryObject(oMsg);
		oArgs.MsgParts = aParts;
		oArgs.msgSeen = bRead;
		
		var oCheckedArgs = WebMail.CheckHistoryObject(oArgs, true);
		if (oCheckedArgs != null) {
			SetHistoryHandler(oCheckedArgs);
		}
		else if (null == oScreen._msgObj) {
			GetMessageHandler(oArgs.MsgId, oArgs.MsgUid, oArgs.MsgFolderId, oArgs.MsgFolderFullName,
				oArgs.MsgParts, oArgs.MsgCharset, oArgs.msgSeen);
		}
	}
}

function GetPageMessagesHandler(iPage)
{
	var oListScreen = WebMail.getCurrentListScreen();
	if (oListScreen == null) return;
	
	var oArgs = oListScreen.getCurrFolderHistoryObject();
	oArgs.oKeyMsgList = oArgs.oKeyMsgList.getNewByPage(iPage);
	oArgs.RedrawType = REDRAW_PAGE;
	oArgs.RedrawObj = null;
	SetHistoryHandler(oArgs);
}

function SortMessagesHandler()
{
	var oScreen = WebMail.getCurrentListScreen();
	if (oScreen === null) return;
	var oArgs = oScreen.getCurrFolderHistoryObject();
	oArgs.oKeyMsgList = oArgs.oKeyMsgList.getNewBySort(this.sortField, this.sortOrder);
	oArgs.RedrawType = REDRAW_HEADER;
	oArgs.RedrawObj = null;
	SetHistoryHandler(oArgs);
}

function FilterMessagesHandler(iFilter)
{
	var oScreen = WebMail.getCurrentListScreen();
	if (oScreen === null) return;
	var oArgs = oScreen.getCurrFolderHistoryObject();
	oArgs.oKeyMsgList = oArgs.oKeyMsgList.getNewByFilter(iFilter);
	if (iFilter === MESSAGE_LIST_FILTER_UNSEEN) {
		WebMail.DataSource.cache.clearMessageList(oArgs.oKeyMsgList.iFolderId,
			oArgs.oKeyMsgList.sFolderFullName, false, true);
	}
	SetHistoryHandler(oArgs);
}

function ResizeMessagesTab(number)
{
	var screen = WebMail.getCurrentListScreen(SCREEN_MESSAGE_LIST_TOP_PANE);
	if (screen == null) return;

	screen._inboxTable.resizeColumnsWidth(number);
}

function StopSearchHandler()
{
	var oScreen = WebMail.getCurrentListScreen();
	if (oScreen) {
		oScreen.stopSearch();
	}
}

function ClearSearchHandler()
{
	var
		oListScreen = WebMail.getCurrentListScreen(),
		oContactsScreen = WebMail.Screens[SCREEN_CONTACTS]
	;
	
	if (oListScreen)
	{
		oListScreen.ClearSearch();
	}
	if (WebMail.ScreenId === SCREEN_CONTACTS && oContactsScreen) 
	{
		oContactsScreen.clearSearch();
	}
}

function RetryRetrievingMessagesHandler()
{
	var oScreen = WebMail.getCurrentListScreen();
	if (oScreen) {
		GetMessageListHandler(REDRAW_NOTHING, null, oScreen.oKeyMsgList);
	}
}

function GetMessageListHandler(redrawIndex, redrawElement, oKeyMsgList, background)
{
	var screen = WebMail.Screens[WebMail.listScreenId];
	if (screen && !background) {
		screen.RedrawControls(redrawIndex, redrawElement, oKeyMsgList);
	}
	var sXml = oKeyMsgList.getInXml();
	GetHandler(TYPE_MESSAGE_LIST, {idAcct: WebMail.iAcctId, page: oKeyMsgList.iPage,
		sortField: oKeyMsgList.iSortField, sortOrder: oKeyMsgList.iSortOrder,
		idFolder: oKeyMsgList.iFolderId, folderFullName: oKeyMsgList.sFolderFullName,
		lookFor: oKeyMsgList.sLookFor, SearchFields: oKeyMsgList.iSearchMode, iFilter: oKeyMsgList.iFilter}, [], sXml, background );
}

function GetMessageHandler(iMsgId, sMsgUid, iFldId, sFldFullName, aMsgParts, iCharset, bSeen)
{
	var oScreen = WebMail.Screens[WebMail.listScreenId];
	if (oScreen === null) return;

	var msg = new CMessage(iMsgId, sMsgUid, iFldId, sFldFullName, iCharset);
	var sMsgId = msg.getIdForList(oScreen.id);

	oScreen.oSelection.CheckLine(sMsgId);
	if (oScreen._inboxTable != null) {
		oScreen._inboxTable.LastClickLineId = sMsgId;
	}

	var oLine = oScreen.oSelection.GetLineById(sMsgId);
	var iMsgSize = 0;
	if (oLine) {
		iMsgSize = oLine.MsgSize;
	}

	var sXml = '<param name="uid">' + GetCData(HtmlDecode(sMsgUid)) + '</param>';
	sXml += '<param name="seen" value="' + (bSeen ? '1' : '0') + '" />';
	if (oLine && oLine.isVoice != undefined) {
		sXml += '<param name="voice" value="' + (oLine.isVoice ? '1' : '0') + '" />';
	}
	sXml += '<folder id="' + iFldId + '"><full_name>' + GetCData(sFldFullName) + '</full_name></folder>';
	WebMail.DataSource.get(TYPE_MESSAGE, {id: iMsgId, charset: iCharset, uid: sMsgUid,
		idFolder: iFldId, folderFullName: sFldFullName, size: iMsgSize}, aMsgParts, sXml);

	if (bSeen) {
		oScreen.markMessageAsRead(sMsgId, iMsgSize, WebMail.DataSource.lastFromCache);
	}
}

function MoveToFolderHandler(id)
{
	var screenId = WebMail.ScreenId;
	var screen = WebMail.Screens[screenId];
	if (screen && screenId == WebMail.listScreenId) {
		var folderParams = id.split(STR_SEPARATOR);
		if (2 == folderParams.length) {
			RequestMessagesOperationHandler(TOOLBAR_MOVE_TO_FOLDER, [], [], folderParams[0], folderParams[1]);
		}
	}
}

function RequestMessagesOperationHandler(type, msgIdArray, msgSizeArray, idToFolder, toFolderFullName) {
	var listScreen = WebMail.Screens[WebMail.listScreenId];
	if (listScreen && type != -1) {
		//data preparing
		var toFolder = listScreen.GetMessagesOperationToFolder(type, idToFolder, toFolderFullName);
		var msgsData = listScreen.GetMessagesOperationMessagesData(msgIdArray, msgSizeArray);

		//verification
		if (!listScreen.isMessagesOperationEnable(type, msgsData.idArray, toFolder)) {
			return;
		}
		listScreen.confirmMessagesOperation(type, msgIdArray, toFolder, msgsData);
	}
}

function SendMessagesOperationHandler(type, toFolder, msgsData) {
	var listScreen = WebMail.Screens[WebMail.listScreenId];
	if (listScreen && type != -1) {
		//display operation results
		listScreen.DisplayMessagesOperationInFolderList(type, toFolder, msgsData);
		listScreen.DisplayMessagesOperationInMessageList(type, msgsData.idArray);

		//operation
		switch (type) {
			case TOOLBAR_DELETE:
			case TOOLBAR_PURGE:
			case TOOLBAR_EMPTY_SPAM:
			case TOOLBAR_IS_SPAM:
			case TOOLBAR_NOT_SPAM:
			case TOOLBAR_MOVE_TO_FOLDER:
				setTimeout(function () {
					listScreen.PerformMessagesOperation(type, toFolder, msgsData);
				}, 200);
				break;
			default:
				listScreen.PerformMessagesOperation(type, toFolder, msgsData);
				break;
		}
	}
}

function DeleteMessageFromDrafts(oMsg)
{
	var oScreen = WebMail.Screens[WebMail.listScreenId];
	if (oScreen) {
		var oDrafts = WebMail.getCurrentDraftsFolder();
		var oTrash = WebMail.getCurrentTrashFolder();
		var xmlHeader = oScreen._getMessagesOperationXmlHeader(oTrash, oDrafts);
		oMsg.idFolder = oDrafts.id;
		oMsg.folderFullName = oDrafts.fullName;
		var xml = '<messages>' + xmlHeader + oMsg.getInShortXML() + '</messages>';
		WebMail.DataSource.request({action: 'operation_messages', request: OperationTypes[TOOLBAR_DELETE]}, xml, true);
	}
}

function SetCounterValueHandler()
{
	if (WebMail && WebMail.Screens) {
		var screen = WebMail.Screens[SCREEN_NEW_MESSAGE];
		if (screen) {
			screen.SetCounterValue();
		}
	}
}

/* check mail handlers */
function SetStateTextHandler(text) {
	WebMail.CheckMail.SetText(text);
}

function SetCheckingFolderHandler(folder, count) {
	WebMail.CheckMail.SetFolder(folder, count);
}

function SetRetrievingMessageHandler(number) {
	WebMail.CheckMail.SetMsgNumber(number);
}

function SetDeletingMessageHandler(number) {
	WebMail.CheckMail.DeleteMsg(number);
}

function SetUpdatedFolders(foldersArray, showInfo)
{
	showInfo = (typeof showInfo != 'undefined') ? showInfo : true;
    WebMail.foldersToUpdate = foldersArray;
	if (showInfo && foldersArray.length == 0) {
		WebMail.showReport(Lang.InfoNoNewMessages);
	}
}

function GetFolderListFromServer()
{
	var stringDataKey = WebMail.DataSource.getStringDataKey(TYPE_FOLDER_LIST, {idAcct: WebMail.Accounts.currId});
	WebMail.DataSource.cache.removeData(TYPE_FOLDER_LIST, stringDataKey);
    GetHandler(TYPE_FOLDER_LIST, {idAcct: WebMail.Accounts.currId, sync: GET_FOLDERS_NOT_SYNC}, [], '');
}

function EndCheckMailHandler(error) {
	var screenId = WebMail.listScreenId;
	var screen = WebMail.Screens[screenId];
	if (screen) {
		screen.endCheckMail();
		if (WebMail.foldersToUpdate.length > 0) {
		    GetFolderListFromServer();
		}
	}
	if (error.length > 0 && !WebMail.CheckMail.hidden) {
		if (error == 'session_error') {
			document.location = LoginUrl + '?error=1';
		}
		else {
			ErrorHandler.call({errorDesc: error});
		}
	}
}

function CheckEndCheckMailHandler() {
	if (!WebMail.CheckMail.started) {
		return;
	}
	var screenId = WebMail.listScreenId;
	var screen = WebMail.Screens[screenId];
	if (screen && WebMail.CheckMail.started) {
	    WebMail.foldersToUpdate = [{id: -1, fullName: ''}];
		screen.endCheckMail();
		GetFolderListFromServer();
        if (!WebMail.CheckMail.hidden) {
            ErrorHandler.call({errorDesc: Lang.ErrorCheckMail});
        }
	}
}
/*-- check mail handlers */

/* auto filling handlers */
function GetAutoFillingContactsHandler()
{
	var oContactsGroups = new CContacts('', this.Keyword, '', CONTACTS_SEARCH_TYPE_FREQUENCY);
	GetHandler(TYPE_CONTACTS, 
	{
		page: 1,
		sortField: SORT_FIELD_USE_FREQ,
		sortOrder: SORT_ORDER_ASC,
		sGroupId: '',
		lookFor: this.Keyword,
		lookFirstChar: '',
		searchType: CONTACTS_SEARCH_TYPE_FREQUENCY
	}, [], oContactsGroups.getInXml());
}

function SelectSuggestionHandler()
{
	if (this.ContactGroup.isGroup) {
		var screen = WebMail.Screens[SCREEN_NEW_MESSAGE];
		if (screen) {
			screen.addSenderGroup(this.ContactGroup.sGroupId);
		}
	}
}

function SetNewMessageScreenChangesHandler()
{
	var oScreen = WebMail.Screens[SCREEN_NEW_MESSAGE];
	if (oScreen) {
		oScreen.isSavedOrSent = false;
		oScreen.setToolsEnable();
	}
}
/*-- auto filling handlers */

function PlaceViewMessageHandler()
{
    var getRequest = WebMail.DataSource.getDataTypeGetRequest(TYPE_MESSAGE);
    var stringDataKey = getRequest + STR_SEPARATOR + window.ViewMessage.getStringDataKeys();
    WebMail.DataSource.cache.addData(TYPE_MESSAGE, stringDataKey, window.ViewMessage);
}

function ShowPicturesHandler(safety)
{
	if (WebMail.ScreenId != SCREEN_MESSAGE_LIST_CENTRAL_PANE
		&& WebMail.ScreenId != SCREEN_MESSAGE_LIST_TOP_PANE) return;
	var screen = WebMail.Screens[WebMail.ScreenId];
	if (screen) {
		screen.showPictures(safety);
	}
}

function SetMessageSafetyHandler(msg)
{
	if (msg == undefined) {
		if (WebMail.ScreenId != SCREEN_MESSAGE_LIST_CENTRAL_PANE
			&& WebMail.ScreenId != SCREEN_MESSAGE_LIST_TOP_PANE) return;
		var screen = WebMail.Screens[WebMail.ScreenId];
		if (screen) {
			msg = screen._msgObj;
		}
		else return;
	}
	WebMail.DataSource.cache.setMessageSafety(msg.id, msg.uid, msg.idFolder, msg.folderFullName, SAFETY_MESSAGE);
}

function SetSenderSafetyHandler(fromAddr)
{
	var xml = '<param name="safety" value="1"/>';
	xml += '<param name="sender">' + GetCData(HtmlDecode(fromAddr)) + '</param>';
	RequestHandler('set', 'sender', xml);
	WebMail.DataSource.cache.setSenderSafety(fromAddr, SAFETY_FULL);
}

function MarkMsgAsRepliedHandler(oMsg)
{
	if (!oMsg.replyMsg) return;

	var iFolderSyncType = WebMail.GetFolderSyncType(oMsg.replyMsg.idFolder);
	var bAllowForwardedFlag = WebMail.Accounts.getAllowForwardedFlag(oMsg.replyMsg.idFolder, oMsg.replyMsg.folderFullName);
	var bNotSupportForwarded = (iFolderSyncType === SYNC_TYPE_DIRECT_MODE && !bAllowForwardedFlag);
	var bDontSetFlag = (oMsg.replyMsg.action === TOOLBAR_FORWARD && bNotSupportForwarded);
	if (!bDontSetFlag) {
		var aIds = [{id: oMsg.replyMsg.id, uid: oMsg.replyMsg.uid}];
		var aMsgData = [ aIds, oMsg.replyMsg.idFolder, oMsg.replyMsg.folderFullName ];
		var sOperationField = (oMsg.replyMsg.action === TOOLBAR_FORWARD) ? 'forwarded' : 'replied';
		WebMail.DataSource.set(aMsgData, sOperationField, true, false);
	}
}

function ClearSentAndDraftsHandler()
{
	var screen = WebMail.Screens[WebMail.listScreenId];
	if (screen) {
		var sent = WebMail.getCurrentSentFolder();
		if (sent != null) {
			WebMail.DataSource.cache.clearMessageList(sent.id, sent.fullName);
			if (screen.oKeyMsgList.isEqualFolder(sent.id, sent.fullName)) {
				screen.CleanInboxLines(Lang.InfoMessagesLoad);
				screen.cleanMessageBody(false);
				GetMessageListHandler(REDRAW_NOTHING, null, screen.oKeyMsgList, false);
			}
		}
		var drafts = WebMail.getCurrentDraftsFolder();
		if (drafts != null) {
			WebMail.DataSource.cache.clearMessageList(drafts.id, drafts.fullName);
			if (screen.oKeyMsgList.isEqualFolder(drafts.id, drafts.fullName)) {
				screen.CleanInboxLines(Lang.InfoMessagesLoad);
				screen.cleanMessageBody(false);
				GetMessageListHandler(REDRAW_NOTHING, null, screen.oKeyMsgList, false);
			}
		}
		if (typeof(screen.ResetReplyPaneFlags) == 'function') screen.ResetReplyPaneFlags(SEND_MODE);
	}
}

function ClearDraftsAndSetMessageId(iId, sUid, bSetMessageId)
{
	var
		oListScreen = WebMail.Screens[WebMail.listScreenId],
		oDrafts = WebMail.getCurrentDraftsFolder()
	;
	
	if (oListScreen) {
		if (WebMail.ScreenId === SCREEN_MESSAGE_LIST_CENTRAL_PANE && bSetMessageId) {
			oListScreen.setMessageId(iId, sUid);
		}
		
		if (oDrafts != null) {
			WebMail.DataSource.cache.clearMessageList(oDrafts.id, oDrafts.fullName);
			
			if (WebMail.Accounts.currMailProtocol === POP3_PROTOCOL) {
				WebMail.DataSource.cache.clearMessage(iId, sUid, oDrafts.id, oDrafts.fullName, '');
			}
			
			if (oListScreen.oKeyMsgList.isEqualFolder(oDrafts.id, oDrafts.fullName)) {
				oListScreen.CleanInboxLines(Lang.InfoMessagesLoad);
				oListScreen.cleanMessageBody(false);
				GetMessageListHandler(REDRAW_NOTHING, null, oListScreen.oKeyMsgList, false);
			}
		}
	}
}

function PreCacheSentAndDraftsFolders(bPreCacheSentFolder)
{
	var oListScreen = WebMail.Screens[WebMail.listScreenId];
	if (oListScreen) {
		var oKeyMsgList = oListScreen.oKeyMsgList;
		var sent = WebMail.getCurrentSentFolder();
		if (sent !== null && bPreCacheSentFolder) {
			oKeyMsgList = oKeyMsgList.getNewByFolder(sent.id, sent.fullName);
			GetMessageListHandler(REDRAW_NOTHING, null, oKeyMsgList, true);
		}
		var drafts = WebMail.getCurrentDraftsFolder();
		if (drafts !== null) {
			oKeyMsgList = oKeyMsgList.getNewByFolder(drafts.id, drafts.fullName);
			GetMessageListHandler(REDRAW_NOTHING, null, oKeyMsgList, true);
		}
	}
}

function OpenNewMessageInNewWindow(newMsg)
{
	var params = '?open_mode=new';
	window.NewMsg = newMsg;
	WindowOpener.open(MiniWebMailUrl + params, 'new_message_window');
	BackToListHandler();
}

function BackToListHandler()
{
	SetHistoryHandler(
		{
			ScreenId: WebMail.listScreenId,
			idFolder: null
		}
	);
}

function NewMessageClickHandler(ev, toAddr)
{
	ev = ev ? ev : window.event;
	if (ev.shiftKey) {
		var newMsg = new CMessage();
		newMsg.toAddr = toAddr;
		OpenNewMessageInNewWindow(newMsg);
	}
	else {
		if (toAddr != undefined) {
			MailAllHandler(toAddr, '', '');
		}
		else {
			SetHistoryHandler({ScreenId: SCREEN_NEW_MESSAGE});
		}
	}
}

/* function must return a value of only in one branch */
function ConfirmBeforeUnload()
{
	if (WebMail) {
		WebMail.DataSource.netLoader.showErrors(false);
		if (WebMail.ScreenId == SCREEN_NEW_MESSAGE) {
			var screen = WebMail.Screens[SCREEN_NEW_MESSAGE];
			if (screen && screen.hasChanges()) {
				return Lang.ConfirmExitFromNewMessage;
			}
		}
	}
}

function LoadAttachmentHandler(attachment) {
	var screen = WebMail.Screens[SCREEN_NEW_MESSAGE];
	if (screen) {
		screen.loadAttachment(attachment);
	}
}

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
