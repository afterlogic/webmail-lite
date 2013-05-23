/*
 * Classes:
 *  CAccounts()
 *  CMessage()
 *  COperationMessages()
 *  CMessageHeaders()
 *  CKeyMessages()
 *  CMessages()
 *  CMessagesBodies()
 *  CFolder(level, listHide)
 *  CFolderList()
 *  CUpdate()
 *  CIdentities()
 *  CIdentity()
 */

function CAccounts(account)
{
	this.type = TYPE_ACCOUNT_LIST;
	this.items = [];
	if (account != undefined) {
		this.items.push(account);
		this.addedId = -1;
		this.editableId = account.id;
		this.count = 1;
		this.currId = account.id;
		this.currMailProtocol = account.mailProtocol;
	}
	else {
		this.addedId = -1;
		this.editableId = null;
		this.count = 0;
		this.currId = null;
		this.currMailProtocol = POP3_PROTOCOL;
	}
	this.isInboxDirectMode = true;
}

CAccounts.prototype = {
	getStringDataKeys: function()
	{
		return '';
	},

	getAccountById: function (id)
	{
		for (var i = this.items.length - 1; i >= 0; i--) {
			if (this.items[i].id == id) {
				return this.items[i];
			}
		}
		return null;
	},

	getCurrentAccount: function ()
	{
		return this.getAccountById(this.currId);
	},

	getEditableAccount: function ()
	{
		return this.getAccountById(this.editableId);
	},

	updateFilters: function (filters)
	{
		for (var i = this.items.length - 1; i >= 0; i--) {
			if (this.items[i].id == filters.id) {
				this.items[i].filters = filters.items;
				break;
			}
		}
	},

	deleteCurrFilters: function ()
	{
		for (var i = this.items.length - 1; i >= 0; i--) {
			if (this.items[i].id == this.currId) {
				this.items[i].filters = null;
				break;
			}
		}
	},

	getEditableFilters: function ()
	{
		var account = this.getEditableAccount();
		if (account == null) return null;
		return account.filters;
	},

	hasAccount: function (id)
	{
		var account = this.getAccountById(id);
		return (account != null);
	},

	setAccountImapQuota: function (acctImapQuota)
	{
		for (var i = this.items.length - 1; i >= 0; i--) {
			if (this.items[i].id == acctImapQuota.id) {
				this.items[i].setImapQuota(acctImapQuota);
				break;
			}
		}
	},

	getAccountIdByFullEmail: function (fullEmail)
	{
		var emailParts = GetEmailParts(fullEmail);
		var email = emailParts.email;
		for (var i = this.items.length - 1; i >= 0; i--) {
			if (this.items[i].email == email) {
				return this.items[i].id;
			}
		}
		return this.currId;
	},

	setAccountDefOrder: function (id, defOrder)
	{
		for (var i = this.items.length - 1; i >= 0; i--) {
			if (this.items[i].id == id){
				this.items[i].defOrder = defOrder;
			}
		}
	},

	changeCurrAccount: function (id)
	{
		for (var i = this.items.length - 1; i >= 0; i--) {
			var account = this.items[i];
			if (account.id == id) {
				this.currId = id;
				this.currMailProtocol = account.mailProtocol;
				this._setInboxSyncType(account.folderList);
				break;
			}
		}
	},

	changeEditableAccount: function (id)
	{
		var oAccount = this.getAccountById(id);
		if (oAccount !== null) {
			this.editableId = id;
			return oAccount;
		}
		else {
			return this.getEditableAccount();
		}
	},

	_setInboxSyncType: function (folderList)
	{
		if (folderList !== null && folderList.idAcct === this.currId) {
			this.isInboxDirectMode = (folderList.inboxSyncType === SYNC_TYPE_DIRECT_MODE);
		}
	},

	setFolderList: function (folderList)
	{
		for (var i = this.items.length - 1; i >= 0; i--) {
			var account = this.items[i];
			if (account.id == folderList.idAcct) {
				account.folderList = folderList;
				this._setInboxSyncType(folderList);
				break;
			}
		}
	},

	getCurrentFolderList: function ()
	{
		var account = this.getCurrentAccount();
		return account.folderList;
	},

	setAllowForwardedFlag: function (iFolderId, sFolderFullName, bAllowForwardedFlag)
	{
		var oFolderList = this.getCurrentFolderList();
		if (oFolderList !== null) {
			oFolderList.setAllowForwardedFlag(iFolderId, sFolderFullName, bAllowForwardedFlag);
		}
	},

	getAllowForwardedFlag: function (iFolderId, sFolderFullName)
	{
		var oFolderList = this.getCurrentFolderList();
		if (oFolderList !== null) {
			return oFolderList.getAllowForwardedFlag(iFolderId, sFolderFullName);
		}
		else {
			return false;
		}
	},

	aplyNewAccountProperties: function (newAcctProp)
	{
		for (var i = this.items.length - 1; i >= 0; i--) {
			if (this.items[i].id == newAcctProp.id) {
				this.items[i].copy(newAcctProp);
			}
		}
	},

	getFromXml: function(rootElement)
	{
		this.addedId = XmlHelper.getIntAttributeByName(rootElement, 'last_id', this.editableId);
		this.editableId = this.addedId;
		this.currId = XmlHelper.getIntAttributeByName(rootElement, 'curr_id', this.currId);
		var hasCurrAccount = false;
		var hasEditableAccount = false;
		var bHasCurrEditableAcct = false;
		var accountsParts = rootElement.childNodes;
		for (var i = 0; i < accountsParts.length; i++) {
			if (accountsParts[i].tagName == 'account') {
		        var account = new CAccountProperties();
		        account.getFromXml(accountsParts[i]);
				if (account.id === this.currId) {
					this.currMailProtocol = account.mailProtocol;
					hasCurrAccount = true;
				}
				if (account.id === this.editableId) {
					hasEditableAccount = true;
				}
				if (WebMail.Accounts !== null && account.id === WebMail.Accounts.editableId) {
					bHasCurrEditableAcct = true;
				}
				this.items.push(account);
			}
		}
		if (!hasCurrAccount && this.items.length > 0) {
			var currAccount = this.items[0];
			this.currId = currAccount.id;
			this.currMailProtocol = currAccount.mailProtocol;
		}
		if (!hasEditableAccount) {
			if (bHasCurrEditableAcct) {
				this.editableId = WebMail.Accounts.editableId;
			}
			else {
				this.editableId = this.currId;
			}
		}
		this.count = this.items.length;
	}
};

// for message
function CMessage(iMsgId, sMsgUid, iFldId, sFldFullName, iCharset)
{
	this.type = TYPE_MESSAGE;
	this.parts = 0;
	//	0 - Common Headers
	//	1 - htmlBody
	//	2 - plainBody
	//	3 - fullHeaders
	//	4 - attachments
	//	5 - replyHtml;
	//	6 - replyPlain;
	//	7 - forwardHtml;
	//	8 - forwardPlain;
	this.idFolder = (iFldId === undefined) ? -1: iFldId;
	this.folderFullName = (sFldFullName === undefined) ? '' : sFldFullName;
	this.size = 0;

	this.id = (iMsgId === undefined) ? -1 : iMsgId;
	this.uid = (sMsgUid === undefined) ? '' : sMsgUid;
	if (WebMail.Settings.richDefEditor) {
		this.hasHtml = true;
		this.hasPlain = false;
	}
	else {
		this.hasHtml = false;
		this.hasPlain = true;
	}
	this.isReplyHtml = false;
	this.isReplyPlain = false;
	this.isForwardHtml = false;
	this.isForwardPlain = false;
	this.importance = PRIORITY_NORMAL;
	this.sensivity = SENSIVITY_NOTHING;
	this.charset = (iCharset === undefined) ? AUTOSELECT_CHARSET : iCharset;
	this.hasCharset = true;
	this.isRtl = false;
	this.safety = SAFETY_MESSAGE;
	this.downloaded = false;
	this.isVoice = false;
	this.voiceAttachment = null;

	// Common Headers
	this.fromAddr = '';
	this.fromDisplayName = '';
	this.fromContact = null;
	this.fromAcctId = -1;
	this.iFromIdentityId = -1;
	this.toAddr = '';
	this.shortToAddr = '';
	this.ccAddr = '';
	this.bccAddr = '';
	this.sendersGroups = Array();//for auto-filling To, CC, BCC fields
	this.replyToAddr = '';//if it's equal with from, set empty value
	this.subject = '';
	this.date = '';
	this.fullDate = '';
	this.time = '';
	this.mailConfirmation = false;
	this.mailConfirmationValue = null;
	this.bSaveMail = true;

	// Body
	this.htmlBody = '';
	this.plainBody = '';
	this.clearPlainBody = '';

	// Body for reply/forward
	this.replyHtml = '';
	this.replyPlain = '';
	this.forwardHtml = '';
	this.forwardPlain = '';

	// Full headers
	this.fullHeaders = '';

	// attachments - array of objects with fields fileName, size[, id, download, view] (for getting) [, tempName, mimeType] (for sending)
	this.attachments = [];

	this.saveLink = '#';
	this.printLink = '#';

	this.replyMsg = null;

	this.noReply = false;
	this.noReplyAll = false;
	this.noForward = false;
	this.xFid = '';

	this.oAppointment = null;
	this.oIcs = null;
	this.oVcf = null;
}

CMessage.prototype = {
	getStringDataKeys: function ()
	{
		var arDataKeys = [ this.id, this.charset, this.uid, this.idFolder, this.folderFullName ];
		return arDataKeys.join(STR_SEPARATOR);
	},

	isEqual: function (msg)
	{
		if (msg == null) return false;
		var equal = (this.id == msg.id && this.charset == msg.charset && this.uid == msg.uid
			&& this.idFolder == msg.idFolder && this.folderFullName == msg.folderFullName);
		return equal;
	},

	isCorrectData: function (msgId, msgUid, msgFolderId, msgFolderFullName, charset)
	{
		charset = (charset == undefined) ? this.charset : charset;
		var correct = (this.id == msgId && this.charset == charset && this.uid == msgUid
			&& this.idFolder == msgFolderId && this.folderFullName == msgFolderFullName);
		return correct;
	},

	getVoiceAttachment: function ()
	{
		var
			oVoiceAttachment = {},
			iIndex = 0,
			iLen = this.attachments.length,
			oAttachment = null
		;
		if (this.voiceAttachment === null) {
			for (; iIndex < iLen; iIndex++) {
				oAttachment = this.attachments[iIndex];
				if (oAttachment.sVoice !== undefined) {
					 this.voiceAttachment = oAttachment;
				}
			}
		}
		if (this.voiceAttachment !== null) {
			oVoiceAttachment = this.voiceAttachment;
		}
		return oVoiceAttachment;
	},

	getFromIdForList: function (id, size)
	{
		var identifiers = id.split(STR_SEPARATOR);
		this.id = identifiers[0];
		this.uid = identifiers[1];
		this.idFolder = identifiers[2];
		this.folderFullName = identifiers[3];
		this.charset = identifiers[4];
		this.setSize(size);
	},

	getIdForList: function(id)
	{
		var identifiers = [this.id, this.uid, this.idFolder, this.folderFullName, this.charset, id];
		return identifiers.join(STR_SEPARATOR);
	},

	setSize: function(size)
	{
		if (size != undefined) {
			this.size = size;
		}
	},

	setMode: function(modeArray)
	{
        var mode = 0;
        for (var key = 0; key < modeArray.length; key++) {
            mode = (1 << modeArray[key]) | mode;
        }
        this.parts = mode;
	},

	getShortDate: function ()
	{
		return this.date + ((this.time && this.date != this.time) ? (', ' + this.time) : '');
	},

	setData: function (oServerBasedData)
	{
		if (null === oServerBasedData) {
			return;
		}

		this.hasHtml = oServerBasedData.bHtmlBody;
		this.hasPlain = !oServerBasedData.bHtmlBody;
		if (oServerBasedData.bHtmlBody) {
			this.htmlBody = oServerBasedData.sBody;
		}
		else {
			this.plainBody = oServerBasedData.sBody;
		}

		this.toAddr = HtmlDecode(oServerBasedData.sRecipient);
		this.subject = HtmlDecode(oServerBasedData.sSubject);

		if (null !== oServerBasedData.oAttachment) {
			this.attachments.push(oServerBasedData.oAttachment);
		}
	},

	prepareForEditing: function (msg)
	{
		this.safety = msg.safety;
		this.idFolder = msg.idFolder;
		this.folderFullName = msg.folderFullName;

		this.id = msg.id;
		this.uid = HtmlDecode(msg.uid);
		this.hasHtml = msg.hasHtml;
		this.hasPlain = msg.hasPlain;
		this.importance = msg.importance;
		this.sensivity = msg.sensivity;

		this.fromAddr = HtmlDecode(msg.fromAddr);
		this.toAddr = HtmlDecode(msg.toAddr);
		this.ccAddr = HtmlDecode(msg.ccAddr);
		this.bccAddr = HtmlDecode(msg.bccAddr);
		this.subject = HtmlDecode(msg.subject);
		this.date = HtmlDecode(msg.date);

		this.htmlBody = msg.htmlBody;
		this.plainBody = msg.clearPlainBody;

		this.attachments = msg.attachments;
		this.size = msg.size;
	},

	_getStringRecipients: function (aSourceRecipients, aRecipientsToExclude)
	{
		for (var j = 0; j < aRecipientsToExclude.length; j++) {
			aRecipientsToExclude[j] = GetEmailParts(aRecipientsToExclude[j]);
		}
		var aResRecipients = Array();
		for (var i = 0; i < aSourceRecipients.length; i++) {
			var bPush = true;
			for (j = 0; j < aRecipientsToExclude.length; j++) {
				if (aSourceRecipients[i].email === aRecipientsToExclude[j].email) {
					bPush = false;
				}
			}
			if (bPush) {
				aResRecipients.push(Trim(aSourceRecipients[i].FullEmail));
			}
		}
		return aResRecipients.join(', ');
	},

	_getArrayRecipients: function (sRecipients)
	{
		if (null === sRecipients)  return [];

		var
			aRecipients = Array(),
			sWorkingRecipients = Trim(HtmlDecode(sRecipients)) + " ",

			emailStartPos = 0,
			emailEndPos = 0,

			isInQuotes = false,
			chQuote = '"',
			isInAngleBrackets = false,
			isInBrackets = false,

			currentPos = 0,

			sWorkingRecipientsLen = sWorkingRecipients.length,

			currentChar = '',
			str = '',
			oRecipient = null,
			inList = false,
			jCount = 0,
			j = 0
		;

		while (currentPos < sWorkingRecipientsLen) {
			currentChar = sWorkingRecipients.substring(currentPos, currentPos+1);
			switch (currentChar) {
				case '\'':
				case '"':
					if (isInQuotes) {
						if (chQuote == currentChar) {
							isInQuotes = false;
						}
					}
					else {
						if (!isInAngleBrackets && !isInBrackets) {
							chQuote = currentChar;
							isInQuotes = true;
						}
					}
				break;
				case '<':
					if (!isInQuotes && !isInAngleBrackets && !isInBrackets) {
						isInAngleBrackets = true;
					}
				break;
				case '>':
					if (isInAngleBrackets) {
						isInAngleBrackets = false;
					}
				break;
				case '(':
					if (!isInQuotes && !isInAngleBrackets && !isInBrackets) {
						isInBrackets = true;
					}
				break;
				case ')':
					if (isInBrackets) {
						isInBrackets = false;
					}
				break;
				default:
				    if (currentChar != ',' && currentChar != ';' && currentPos != (sWorkingRecipientsLen-1)) break;
					if (!isInAngleBrackets && !isInBrackets && !isInQuotes) {
						emailEndPos = (currentPos != (sWorkingRecipientsLen-1)) ? currentPos : sWorkingRecipientsLen;
						str = sWorkingRecipients.substring(emailStartPos, emailEndPos);
						if (Trim(str).length > 0) {
							oRecipient = GetEmailParts(str);
							inList = false;
							jCount = aRecipients.length;
							for (j = 0; j < jCount; j++) {
								if (aRecipients[j].email == oRecipient.email) inList = true;
							}
							if (!inList) {
								aRecipients.push(oRecipient);
							}
						}
						emailStartPos = currentPos + 1;
					}
				break;
			}
			currentPos++;
		}
		return aRecipients;
	},

	_prepareCommonReplyParts: function (msg)
	{
		this.hasHtml = msg.isReplyHtml;
		this.hasPlain = msg.isReplyPlain;
		this.htmlBody = (msg.sensivity != SENSIVITY_NOTHING) ? '' : msg.replyHtml;
		this.plainBody = (msg.sensivity != SENSIVITY_NOTHING) ? '' : '\r\n\r\n' + msg.replyPlain;
		this.subject = (msg.sensivity != SENSIVITY_NOTHING) ? '' : ReplySubjectAdd(Lang.Re, HtmlDecode(msg.subject));
        if (!msg.hasPlain && msg.hasHtml) {
			this.attachments = [];
			var iCount = msg.attachments.length;
			var j = 0;
			for (var i=0; i<iCount; i++) {
				if (msg.attachments[i].inline) {
					this.attachments[j] = msg.attachments[i];
					j++;
				}
			}
		}
	},

	_getReplyToAddr: function (oMsg)
	{
		var
			aReplyToRecipients = this._getArrayRecipients(oMsg.replyToAddr),
			sReplyToEmail = (aReplyToRecipients.length > 0) ? aReplyToRecipients[0].email : '',
			aFromRecipients = this._getArrayRecipients(oMsg.fromAddr),
			sFromEmail = (aFromRecipients.length > 0) ? aFromRecipients[0].email : ''
		;
		if (sReplyToEmail === '' || sReplyToEmail.toLowerCase() === sFromEmail.toLowerCase()) {
			if (aFromRecipients.length > 0) {
				return aFromRecipients[0].FullEmail;
			}
		}
		if (aReplyToRecipients.length > 0) {
			return aReplyToRecipients[0].FullEmail;
		}
		return '';
	},

	prepareForReply: function (msg, replyAction, fromAddr)
	{
		replyAction = replyAction - 0;
		this.replyMsg = {action: replyAction, id: msg.id, uid: msg.uid,
		    idFolder: msg.idFolder, folderFullName: msg.folderFullName};
		this.safety = msg.safety;
		this.fromAddr = '';
		this.date = '';
		this.size = msg.size;
		this.xFid = msg.xFid;
		switch (replyAction) {
			case TOOLBAR_REPLY:
				this.toAddr = this._getReplyToAddr(msg);
				this.ccAddr = '';
				this.bccAddr = '';
				this._prepareCommonReplyParts(msg);
			break;
			case TOOLBAR_REPLYALL:
				this.toAddr = this._getReplyToAddr(msg);
				var aCcRecipients = this._getArrayRecipients(msg.toAddr + ',' + msg.ccAddr);
				this.ccAddr = this._getStringRecipients(aCcRecipients, [fromAddr, this.toAddr]);
				var aBccRecipients = this._getArrayRecipients(msg.bccAddr);
				this.bccAddr = this._getStringRecipients(aBccRecipients, [fromAddr, this.toAddr]);
				this._prepareCommonReplyParts(msg);
			break;
			case TOOLBAR_FORWARD:
				this.hasHtml = msg.isForwardHtml;
				this.hasPlain = msg.isForwardPlain;
				this.htmlBody = msg.forwardHtml;
				this.plainBody = msg.forwardPlain;
				this.toAddr = '';
				this.ccAddr = '';
				this.bccAddr = '';
				this.subject = ReplySubjectAdd(Lang.Fwd, HtmlDecode(msg.subject));
				this.attachments = msg.attachments;
			break;
		}
	},

	getInShortXML: function ()
	{
		var xml = '<message id="' + this.id + '" charset="' + this.charset + '"';
		xml += ' size="' + this.size + '">';
		xml += '<uid>' + GetCData(HtmlDecode(this.uid)) + '</uid>';
		xml += '<folder id="' + this.idFolder + '">';
		xml += '<full_name>' + GetCData(this.folderFullName) + '</full_name></folder>';
		xml += '</message>';
		return xml;
	},

	_getAttachmentXmlNodes: function (oAttachment)
	{
		var sNodes = '';
		sNodes += '<temp_name>' + GetCData(oAttachment.tempName) + '</temp_name>';
		sNodes += '<name>' + GetCData(oAttachment.fileName) + '</name>';
		sNodes += '<mime_type>' + GetCData(oAttachment.mimeType) + '</mime_type>';
		return sNodes;
	},

	getVoiceAttachmentXml: function (oAttachment)
	{
		return '<attachment>' + this._getAttachmentXmlNodes(oAttachment) + '</attachment>';
	},

	getAttachmentXml: function (oAttachment)
	{
		var sAttrs = '';
		sAttrs += ' size="' + oAttachment.size + '"';
		sAttrs += (oAttachment.inline) ? ' inline="1"' : ' inline="0"';
		return '<attachment' + sAttrs + '>' + this._getAttachmentXmlNodes(oAttachment) + '</attachment>';
	},

	getInXml: function(iMode)
	{
		var strHeaders = '<from>' + GetCData(this.fromAddr) + '</from>';
		strHeaders += '<to>' + GetCData(this.toAddr) + '</to>';
		strHeaders += '<cc>' + GetCData(this.ccAddr) + '</cc>';
		strHeaders += '<bcc>' + GetCData(this.bccAddr) + '</bcc>';
		strHeaders += '<subject>' + GetCData(this.subject) + '</subject>';
		if (this.mailConfirmation) {
			strHeaders += '<mailconfirmation>' + GetCData(this.fromAddr) + '</mailconfirmation>';
		}

		var strGroups = '';
		var iCount = this.sendersGroups.length;
		for (var i = 0; i < iCount; i++) {
			strGroups += '<group id="' + HtmlEncodeWithQuotes(this.sendersGroups[i]) + '" />';
		}
		strHeaders += '<groups>' + strGroups + '</groups>';
		strHeaders = '<headers>' + strHeaders + '</headers>';

		var strBody = (this.hasHtml)
			? '<body is_html="1">' + GetCData(this.htmlBody, true) + '</body>'
			: '<body is_html="0">' + GetCData(this.plainBody, true) + '</body>';

		var strAttachments = '';
		for (var j = 0; j < this.attachments.length; j++) {
			strAttachments += this.getAttachmentXml(this.attachments[j]);
		}
		strAttachments = '<attachments>' + strAttachments + '</attachments>';

		var uid = '<uid>' + GetCData(HtmlDecode(this.uid)) + '</uid>';
		var attrs = ' id="' + this.id + '"';
		attrs += ' x-fid="' + this.xFid + '"';
		attrs += ' from_acct_id="' + this.fromAcctId + '"';
		attrs += ' from_identity_id="' + this.iFromIdentityId + '"';
		attrs += ' sensivity="' + this.sensivity + '"';
		attrs += ' size="' + this.size + '"';
		attrs += ' priority="' + this.importance + '"';
		attrs += ' save_mail="' + (this.bSaveMail ? '1' : '0') + '"';
		attrs += ' autosave="' + (iMode == AUTO_SAVE_MODE ? '1' : '0') + '"';

		var replyMsg = '';
		if (null != this.replyMsg) {
		    var action = (this.replyMsg.action == TOOLBAR_FORWARD) ? 'forward' : 'reply';
		    replyMsg = '<reply_message action="' + action + '" id="' + this.replyMsg.id + '">';
		    replyMsg += '<uid>' + GetCData(this.replyMsg.uid) + '</uid>';
		    replyMsg += '<folder id="' + this.replyMsg.idFolder + '">';
		    replyMsg += '<full_name>' + GetCData(this.replyMsg.folderFullName);
			replyMsg += '</full_name></folder></reply_message>';
		}

		var strResult = '<message' + attrs + '>' + uid + strHeaders + strBody + strAttachments
			+ replyMsg + '</message>';
		return strResult;
	},//getInXml

	showPictures: function ()
	{
		if (this.hasHtml) {
			this.htmlBody = this.htmlBody.replaceStr('wmx_background', 'background');
			this.htmlBody = this.htmlBody.replaceStr('wmx_src', 'src');
			this.htmlBody = this.htmlBody.replaceStr('wmx_url(', 'url(');
		}
		if (this.isReplyHtml) {
			this.replyHtml = this.replyHtml.replaceStr('wmx_background', 'background');
			this.replyHtml = this.replyHtml.replaceStr('wmx_src', 'src');
			this.replyHtml = this.replyHtml.replaceStr('wmx_url(', 'url(');
			this.forwardHtml = this.replyHtml;
		}
	},

	_readSafety: function (rootElement)
	{
		var safety = XmlHelper.getIntAttributeByName(rootElement, 'safety', SAFETY_NOTHING);
		var needShowPic = false;
		if (this.parts == 0) {
		    this.safety = safety;
		}
		else {
		    needShowPic = (safety == SAFETY_NOTHING && this.safety > SAFETY_NOTHING);
		}
		return needShowPic;
	},

	_applySensivity: function ()
	{
		if (this.sensivity != SENSIVITY_NOTHING) {
			this.replyHtml = '';
			this.replyPlain = '';
			this.forwardHtml = '';
			this.forwardPlain = '';
			this.parts = this.parts | (1 << PART_MESSAGE_REPLY_HTML) | (1 << PART_MESSAGE_REPLY_PLAIN);
			this.parts = this.parts | (1 << PART_MESSAGE_FORWARD_HTML) | (1 << PART_MESSAGE_FORWARD_PLAIN);
		}
	},

	_readAttachments: function (rootElement)
	{
		this.attachments = [];
		var
			eAttachments = XmlHelper.getFirstChildNodeByName(rootElement, 'attachments'),
			iIndex = 0,
			iLen = 0,
			eAttachment,
			oAttachment
		;
		if (eAttachments == null) return;
		for (iLen = eAttachments.childNodes.length; iIndex < iLen; iIndex++) {
			eAttachment = eAttachments.childNodes[iIndex];
			oAttachment = this._readAttachment(eAttachment);
			if (oAttachment.sVoice !== undefined) {
				 this.voiceAttachment = oAttachment;
			}
			else {
				this.attachments.push(oAttachment);
			}
		}
	},

	_readAttachment: function (attachNode)
	{
		var downloadNode = XmlHelper.getFirstChildNodeByName(attachNode, 'download');
		var sDownload = XmlHelper.getFirstChildValue(downloadNode, '#');
		sDownload = HtmlDecode(sDownload);

		var voiceNode = XmlHelper.getFirstChildNodeByName(attachNode, 'voice');
		var sVoice = XmlHelper.getFirstChildValue(voiceNode, '#');
		sVoice = HtmlDecode(sVoice);

		var fileNameNode = XmlHelper.getFirstChildNodeByName(attachNode, 'filename');
		var sFileName = XmlHelper.getFirstChildValue(fileNameNode, '');

		var mimeTypeNode = XmlHelper.getFirstChildNodeByName(attachNode, 'mime_type');
		var sMimeType = XmlHelper.getFirstChildValue(mimeTypeNode, '');

		var tempNameNode = XmlHelper.getFirstChildNodeByName(attachNode, 'tempname');
		var sTempName = XmlHelper.getFirstChildValue(tempNameNode, '');

		if (sVoice != '#') {
			var iDuration = XmlHelper.getIntAttributeByName(attachNode, 'duration', 0);

			return {download: sDownload, sVoice: sVoice, iDuration: iDuration,
				sTranscription: '', tempName: sTempName, mimeType: sMimeType, fileName: sFileName};
		}
		else {
			var id = XmlHelper.getIntAttributeByName(attachNode, 'id', -1);
			var iSize = XmlHelper.getIntAttributeByName(attachNode, 'size', 0);
			var bInline = XmlHelper.getBoolAttributeByName(attachNode, 'inline', false);

			var viewNode = XmlHelper.getFirstChildNodeByName(attachNode, 'view');
			var sView = XmlHelper.getFirstChildValue(viewNode, '#');
			sView = HtmlDecode(sView);

			return {id: id, inline: bInline, fileName: sFileName, size: iSize,
				download: sDownload, view: sView, tempName: sTempName, mimeType: sMimeType};
		}
	},

	_readCommon: function (rootElement)
	{
		this.charset = XmlHelper.getIntAttributeByName(rootElement, 'charset', this.charset);
		this.id = XmlHelper.getIntAttributeByName(rootElement, 'id', this.id);
		this.importance = XmlHelper.getIntAttributeByName(rootElement, 'priority', this.importance);
		this.noForward = XmlHelper.getBoolAttributeByName(rootElement, 'no_forward', this.noForward);
		this.noReply = XmlHelper.getBoolAttributeByName(rootElement, 'no_reply', this.noReply);
		this.noReplyAll = XmlHelper.getBoolAttributeByName(rootElement, 'no_reply_all', this.noReplyAll);
		this.size = XmlHelper.getIntAttributeByName(rootElement, 'size', this.size);
		this.sensivity = XmlHelper.getIntAttributeByName(rootElement, 'sensivity', this.sensivity);
		this.isVoice = XmlHelper.getBoolAttributeByName(rootElement, 'voice', this.isVoice);

		var folderNode = XmlHelper.getFirstChildNodeByName(rootElement, 'folder');
		this.idFolder = XmlHelper.getIntAttributeByName(folderNode, 'id', this.idFolder);
		this.folderFullName = XmlHelper.getFirstChildValue(folderNode, this.folderFullName);

		var uidNode = XmlHelper.getFirstChildNodeByName(rootElement, 'uid');
		this.uid = XmlHelper.getFirstChildValue(uidNode, this.uid);
		if (this.uid == '-1') this.uid = '';
	},

	_readCommonHeaders: function (rootElement)
	{
		var toNode = XmlHelper.getFirstChildNodeByName(rootElement, 'to');
		this.toAddr = XmlHelper.getFirstChildValue(toNode, this.toAddr);

		var ccNode = XmlHelper.getFirstChildNodeByName(rootElement, 'cc');
		this.ccAddr = XmlHelper.getFirstChildValue(ccNode, this.ccAddr);

		var bccNode = XmlHelper.getFirstChildNodeByName(rootElement, 'bcc');
		this.bccAddr = XmlHelper.getFirstChildValue(bccNode, this.bccAddr);

		var replyToNode = XmlHelper.getFirstChildNodeByName(rootElement, 'reply_to');
		this.replyToAddr = XmlHelper.getFirstChildValue(replyToNode, this.replyToAddr);

		var subjectNode = XmlHelper.getFirstChildNodeByName(rootElement, 'subject');
		this.subject = XmlHelper.getFirstChildValue(subjectNode, this.subject);

		var dateNode = XmlHelper.getFirstChildNodeByName(rootElement, 'short_date');
		this.date = XmlHelper.getFirstChildValue(dateNode, this.date);

		var fullDateNode = XmlHelper.getFirstChildNodeByName(rootElement, 'full_date');
		this.fullDate = XmlHelper.getFirstChildValue(fullDateNode, this.fullDate);
	},

	_readCalendars: function (eRoot)
	{
		var
			eCalendars = XmlHelper.getFirstChildNodeByName(eRoot, 'calendars'),
			aeCalendars = eCalendars.childNodes,
			iLen = aeCalendars.length,
			iIndex = 0,
			eCalendar = null,
			eId = null,
			sId = '',
			eName = null,
			sName = '',
			aoCalendars = []
		;

		for (; iIndex < iLen; iIndex++) {
			eCalendar = aeCalendars[iIndex];

			eId = XmlHelper.getFirstChildNodeByName(eCalendar, 'id');
			sId = XmlHelper.getFirstChildValue(eId, '');
			eName = XmlHelper.getFirstChildNodeByName(eCalendar, 'name');
			sName = XmlHelper.getFirstChildValue(eName, '');

			aoCalendars.push({sName: sName, sId: sId});
		}

		return aoCalendars;
	},

	_readIcs: function (eRoot)
	{
		var
			eIcs = XmlHelper.getFirstChildNodeByName(eRoot, 'ics'),
			oIcs = null,
			sAppType, sType, aType,
			aTypes = [EnumAppointmentType.Cancel, EnumAppointmentType.Reply,
				EnumAppointmentType.Request, EnumAppointmentType.Save],
			sConfig = '',
			aConfigs = [EnumAppointmentConfig.Accepted, EnumAppointmentConfig.Declined,
				EnumAppointmentConfig.Tentative, EnumAppointmentConfig.NeedAction]
		;
		
		if (eIcs !== null) {
			oIcs = this._readCommonIcsAppointmentPart(eIcs);
			
			sAppType = XmlHelper.getAttributeByName(eIcs, 'type', '');
			aType = sAppType.split('-');
			
			sType = aType.shift();
			if (-1 === $.inArray(sType, aTypes)) {
				sType = EnumAppointmentType.Save;
			}
			
			sConfig = aType.join('-');
			if (-1 === $.inArray(sConfig, aConfigs)) {
				sConfig = EnumAppointmentConfig.NeedAction;
			}

			oIcs.sType = sType;
			oIcs.sConfig = sConfig;
			
			if (sType === EnumAppointmentType.Save) {
				this.oIcs = oIcs;
			}
			else {
				this.oAppointment = oIcs;
			}
		}
	},
	
	_readCommonIcsAppointmentPart: function (eAppointment)
	{
		var
			sUid = XmlHelper.getAttributeByName(eAppointment, 'uid', ''),
			sFile = XmlHelper.getAttributeByName(eAppointment, 'file', ''),
			aCalendars = this._readCalendars(eAppointment),
			eCalId = XmlHelper.getFirstChildNodeByName(eAppointment, 'calendar_id'),
			sCalId = XmlHelper.getFirstChildValue(eCalId, ''),
			eLocation = XmlHelper.getFirstChildNodeByName(eAppointment, 'location'),
			sLocation = XmlHelper.getFirstChildValue(eLocation, ''),
			eDescription = XmlHelper.getFirstChildNodeByName(eAppointment, 'description'),
			sDescription = HtmlDecode(XmlHelper.getFirstChildValue(eDescription, '')),
			eWhen = XmlHelper.getFirstChildNodeByName(eAppointment, 'when'),
			sWhen = XmlHelper.getFirstChildValue(eWhen, '')
		;

		WebMail.registerCalendarEvent(sUid);

		return {sUid: sUid, sFile: sFile, aCalendars: aCalendars,
			sCalId: sCalId, sLocation: sLocation, sDescription: sDescription, sWhen: sWhen};
	},

	_readVcf: function (eRoot)
	{
		var
			eVcf = XmlHelper.getFirstChildNodeByName(eRoot, 'vcf'),
			sUid = '',
			sFile = '',
			bExists = false,
			eName = null,
			sName = '',
			eEmail = null,
			sEmail = ''
		;
		
		if (eVcf !== null) {
			sUid = XmlHelper.getAttributeByName(eVcf, 'uid', '');
			sFile = XmlHelper.getAttributeByName(eVcf, 'file', '');
			bExists = XmlHelper.getBoolAttributeByName(eVcf, 'exists', false);
			eName = XmlHelper.getFirstChildNodeByName(eVcf, 'name');
			sName = HtmlDecode(XmlHelper.getFirstChildValue(eName, ''));
			eEmail = XmlHelper.getFirstChildNodeByName(eVcf, 'email');
			sEmail = XmlHelper.getFirstChildValue(eEmail, '');
			
			this.oVcf = {sUid: sUid, sFile: sFile, sName: sName, sEmail: sEmail, bExists: bExists};
		}
	},
	
	getFromXml: function(rootElement)
	{
		this._readCommon(rootElement);
		this.downloaded = XmlHelper.getBoolAttributeByName(rootElement, 'downloaded', this.downloaded);
		this.hasCharset = XmlHelper.getBoolAttributeByName(rootElement, 'has_charset', this.hasCharset);
		this.hasHtml = XmlHelper.getBoolAttributeByName(rootElement, 'html', this.hasHtml);
		this.hasPlain = XmlHelper.getBoolAttributeByName(rootElement, 'plain', this.hasPlain);
		this.isRtl = XmlHelper.getBoolAttributeByName(rootElement, 'rtl', this.isRtl);
		var needShowPic = this._readSafety(rootElement); //!before mode reading
		var parts = XmlHelper.getIntAttributeByName(rootElement, 'mode', this.parts);
		this.parts = this.parts | parts;
		this._applySensivity();

		var headersNode = XmlHelper.getFirstChildNodeByName(rootElement, 'headers');
		var fromNode = XmlHelper.getFirstChildNodeByName(headersNode, 'from');
			var sContactId = XmlHelper.getAttributeByName(fromNode, 'contact_id', '');
			this.fromContact = {sContactId: sContactId};
			var shortNode = XmlHelper.getFirstChildNodeByName(fromNode, 'short');
			this.fromDisplayName = XmlHelper.getFirstChildValue(shortNode, this.fromDisplayName);
			var fullNode = XmlHelper.getFirstChildNodeByName(fromNode, 'full');
			this.fromAddr = XmlHelper.getFirstChildValue(fullNode, this.fromAddr);
		var shortToNode = XmlHelper.getFirstChildNodeByName(headersNode, 'short_to');
		this.shortToAddr = XmlHelper.getFirstChildValue(shortToNode, this.shortToAddr);
		var mailConfirmationNode = XmlHelper.getFirstChildNodeByName(headersNode, 'mailconfirmation');
		this.mailConfirmationValue = HtmlDecode(XmlHelper.getFirstChildValue(mailConfirmationNode, this.mailConfirmationValue));
		var timeNode = XmlHelper.getFirstChildNodeByName(headersNode, 'time');
		this.time = XmlHelper.getFirstChildValue(timeNode, this.time);
		this._readCommonHeaders(headersNode);

		this._readIcs(rootElement);
		this._readVcf(rootElement);

		var htmlBodyNode = XmlHelper.getFirstChildNodeByName(rootElement, 'html_part');
		this.htmlBody = XmlHelper.getFirstChildValue(htmlBodyNode, this.htmlBody);
		if (this.htmlBody.length > 0) this.hasHtml = true;

		var plainBodyNode = XmlHelper.getFirstChildNodeByName(rootElement, 'modified_plain_text');
		this.plainBody = XmlHelper.getFirstChildValue(plainBodyNode, this.plainBody);
		this.clearPlainBody = this.plainBody;
		if (this.plainBody.length > 0) this.hasPlain = true;

		var clearPlainBodyNode = XmlHelper.getFirstChildNodeByName(rootElement, 'unmodified_plain_text');
		this.clearPlainBody = XmlHelper.getFirstChildValue(clearPlainBodyNode, this.clearPlainBody);

		var replyHtmlNode = XmlHelper.getFirstChildNodeByName(rootElement, 'reply_html');
		this.replyHtml = XmlHelper.getFirstChildValue(replyHtmlNode, this.replyHtml);
		if (this.replyHtml.length > 0) {
			this.forwardHtml = this.replyHtml;
			this.isReplyHtml = true;
			this.isForwardHtml = true;
			this.parts = (1 << PART_MESSAGE_FORWARD_HTML) | this.parts;
		}

		var replyPlainNode = XmlHelper.getFirstChildNodeByName(rootElement, 'reply_plain');
		this.replyPlain = XmlHelper.getFirstChildValue(replyPlainNode, this.replyPlain);
		if (this.replyPlain.length > 0) {
			this.forwardPlain = this.replyPlain;
			this.isReplyPlain = true;
			this.isForwardPlain = true;
			this.parts = (1 << PART_MESSAGE_FORWARD_PLAIN) | this.parts;
		}

		var forwardHtmlNode = XmlHelper.getFirstChildNodeByName(rootElement, 'forward_html');
		this.forwardHtml = XmlHelper.getFirstChildValue(forwardHtmlNode, this.forwardHtml);
		if (this.forwardHtml.length > 0) {
			this.replyHtml = this.forwardHtml;
			this.isForwardHtml = true;
			this.isReplyHtml = true;
			this.parts = (1 << PART_MESSAGE_REPLY_HTML) | this.parts;
		}

		var forwardPlainNode = XmlHelper.getFirstChildNodeByName(rootElement, 'forward_plain');
		this.forwardPlain = XmlHelper.getFirstChildValue(forwardPlainNode, this.forwardPlain);
		if (this.forwardPlain.length > 0) {
			this.replyPlain = this.forwardPlain;
			this.isForwardPlain = true;
			this.isReplyPlain = true;
			this.parts = (1 << PART_MESSAGE_REPLY_PLAIN) | this.parts;
		}

		var fullHeadersNode = XmlHelper.getFirstChildNodeByName(rootElement, 'full_headers');
		this.fullHeaders = XmlHelper.getFirstChildValue(fullHeadersNode, this.fullHeaders);

		var saveLinkNode = XmlHelper.getFirstChildNodeByName(rootElement, 'save_link');
		this.saveLink = XmlHelper.getFirstChildValue(saveLinkNode, this.saveLink);
		this.saveLink = HtmlDecode(this.saveLink);

		var printLinkNode = XmlHelper.getFirstChildNodeByName(rootElement, 'print_link');
		this.printLink = XmlHelper.getFirstChildValue(printLinkNode, this.printLink);
		this.printLink = HtmlDecode(this.printLink);

		this._readAttachments(rootElement);

		if (needShowPic) {
		    this.showPictures();
		}
	}
};

function COperationMessages()
{
	this.type = TYPE_MESSAGES_OPERATION;
	this.operationType = '';
	this.operationField = '';
	this.operationValue = true;
	this.operationInt = -1;
	this.isAllMess = false;
	this.idFolder = -1;
	this.folderFullName = '';
	this.idToFolder = -1;
	this.toFolderFullName = '';
	this.messages = new CDictionary();
	this.getMessageAfterDelete = false;//???
	this.isMoveError = false;
}

COperationMessages.prototype = {
	getInXml: function ()
	{
		var getmsg = (this.getMessageAfterDelete) ? 1 : 0;
		var nodes = '<messages getmsg="' + getmsg + '">';
		nodes += '<look_for fields="0">' + GetCData('') + '</look_for>';
		nodes += '<to_folder id="' + this.idToFolder + '">';
		nodes += '<full_name>' + GetCData(this.toFolderFullName) + '</full_name></to_folder>';
		nodes += '<folder id="' + this.idFolder + '">';
		nodes += '<full_name>' + GetCData(this.folderFullName) + '</full_name></folder>';
		var keys = this.messages.keys();
		var iCount = keys.length;
		for (var i = 0; i < iCount; i++) {
			var msgsInFolder = this.messages.getVal(keys[i]);
			var msgsCount = msgsInFolder.idArray.length;
			for (var msgIndex = 0; msgIndex < msgsCount; msgIndex++) {
				var msg = msgsInFolder.idArray[msgIndex];
				nodes += '<message id="' + msg.id + '" charset="';
				nodes += msg.charset + '" size="' + msg.size + '">';
				nodes += '<uid>' + GetCData(msg.uid) + '</uid>';
				nodes += '<folder id="' + msgsInFolder.idFolder + '"><full_name>';
				nodes += GetCData(msgsInFolder.folderFullName) + '</full_name></folder>';
				nodes += '</message>';
			}
		}
		nodes += '</messages>';
		return nodes;
	},

	getFromXml: function (rootElement)
	{
		this.operationType = XmlHelper.getAttributeByName(rootElement, 'type', this.operationType);
		this._getOperation();
		this.isAllMess = (this.operationInt == TOOLBAR_MARK_ALL_READ || this.operationInt == TOOLBAR_MARK_ALL_UNREAD);

		var toFolderFullNameNode = XmlHelper.getFirstChildNodeByName(rootElement, 'to_folder');
		this.idToFolder = XmlHelper.getIntAttributeByName(toFolderFullNameNode, 'id', this.idToFolder);
		this.toFolderFullName = XmlHelper.getFirstChildValue(toFolderFullNameNode, this.toFolderFullName);

		var folderFullNameNode = XmlHelper.getFirstChildNodeByName(rootElement, 'folder');
		this.idFolder = XmlHelper.getIntAttributeByName(folderFullNameNode, 'id', this.idFolder);
		this.folderFullName = XmlHelper.getFirstChildValue(folderFullNameNode, this.folderFullName);

		var messagesNode = XmlHelper.getFirstChildNodeByName(rootElement, 'messages');
		this.getMessageAfterDelete = XmlHelper.getBoolAttributeByName(messagesNode, 'getmsg', this.getMessageAfterDelete);
		this.isMoveError = XmlHelper.getBoolAttributeByName(messagesNode, 'no_move', this.isMoveError);
		var messagesChildNodes = messagesNode.childNodes;
		for (var i = 0; i < messagesChildNodes.length; i++) {
			var msgNode = messagesChildNodes[i];
			var id = XmlHelper.getIntAttributeByName(msgNode, 'id', -1);
			var charset = XmlHelper.getIntAttributeByName(msgNode, 'charset', AUTOSELECT_CHARSET);
			var size = XmlHelper.getIntAttributeByName(msgNode, 'size', 0);
			var uidNode = XmlHelper.getFirstChildNodeByName(msgNode, 'uid');
			var uid = XmlHelper.getFirstChildValue(uidNode, '');
			var folderNode = XmlHelper.getFirstChildNodeByName(msgNode, 'folder');
			var idFolder = XmlHelper.getIntAttributeByName(folderNode, 'id', -1);
			var folderFullName = XmlHelper.getFirstChildValue(folderNode, '');

			var idArray = Array();
			if (this.messages.exists(idFolder + folderFullName)) {
				var folder = this.messages.getVal(idFolder + folderFullName);
				idArray = folder.idArray;
			}
			idArray.push({id: id, uid: uid, charset: charset, size: size});
			this.messages.setVal(idFolder + folderFullName,
				{idArray: idArray, idFolder: idFolder, folderFullName: folderFullName});
		}
	},

	_getOperation: function ()
	{
		switch (this.operationType) {
			case OperationTypes[TOOLBAR_DELETE]:
				this.operationField = 'deleted';
				this.operationValue = true;
				this.operationInt = TOOLBAR_DELETE;
			break;
			case OperationTypes[TOOLBAR_NO_MOVE_DELETE]:
				this.operationField = 'deleted';
				this.operationValue = true;
				this.operationInt = TOOLBAR_NO_MOVE_DELETE;
			break;
			case OperationTypes[TOOLBAR_UNDELETE]:
				this.operationField = 'deleted';
				this.operationValue = false;
				this.operationInt = TOOLBAR_UNDELETE;
			break;
			case OperationTypes[TOOLBAR_PURGE]:
				this.operationInt = TOOLBAR_PURGE;
			break;
			case OperationTypes[TOOLBAR_EMPTY_SPAM]:
				this.operationInt = TOOLBAR_EMPTY_SPAM;
			break;
			case OperationTypes[TOOLBAR_MARK_READ]:
				this.operationField = 'read';
				this.operationValue = true;
				this.operationInt = TOOLBAR_MARK_READ;
			break;
			case OperationTypes[TOOLBAR_MARK_UNREAD]:
				this.operationField = 'read';
				this.operationValue = false;
				this.operationInt = TOOLBAR_MARK_UNREAD;
			break;
			case OperationTypes[TOOLBAR_FLAG]:
				this.operationField = 'flagged';
				this.operationValue = true;
				this.operationInt = TOOLBAR_FLAG;
			break;
			case OperationTypes[TOOLBAR_UNFLAG]:
				this.operationField = 'flagged';
				this.operationValue = false;
				this.operationInt = TOOLBAR_UNFLAG;
			break;
			case OperationTypes[TOOLBAR_MARK_ALL_READ]:
				this.operationField = 'read';
				this.operationValue = true;
				this.operationInt = TOOLBAR_MARK_ALL_READ;
			break;
			case OperationTypes[TOOLBAR_MARK_ALL_UNREAD]:
				this.operationField = 'read';
				this.operationValue = false;
				this.operationInt = TOOLBAR_MARK_ALL_UNREAD;
			break;
			case OperationTypes[TOOLBAR_IS_SPAM]:
				this.operationInt = TOOLBAR_IS_SPAM;
			break;
			case OperationTypes[TOOLBAR_NOT_SPAM]:
				this.operationInt = TOOLBAR_NOT_SPAM;
			break;
			case OperationTypes[TOOLBAR_MOVE_TO_FOLDER]:
			case '':
				this.operationInt = TOOLBAR_MOVE_TO_FOLDER;
			break;
		}
	}
};

// for message in messages list
function CMessageHeaders()
{
	this.id = -1;
	this.uid = '';
	this.hasAttachments = false;
	this.importance = PRIORITY_NORMAL;
	this.sensivity = SENSIVITY_NOTHING;

	this.idFolder = -1;
	this.folderType = -1;
	this.folderFullName = '';
	this.folderName = '';
	this.charset = AUTOSELECT_CHARSET;

	this.read = false;
	this.replied = false;
	this.forwarded = false;
	this.flagged = false;
	this.deleted = false;
	this.gray = false;
	this.isVoice = false;

	this.fromAddr = '';
	this.toAddr = '';
	this.ccAddr = '';
	this.bccAddr = '';
	this.replyToAddr = '';
	this.size = '';
	this.subject = '';
	this.date = '';
	this.fullDate = '';

	this.noReply = false;
	this.noReplyAll = false;
	this.noForward = false;
}

CMessageHeaders.prototype = {
//public
	getInXml: function ()
	{
		var sAttrs = ' id="' + this.id + '" charset="' + this.charset + '"';
		sAttrs += ' size="' + this.size + '" voice="' + (this.isVoice ? '1' : '0') + '"';
		var sNodes = '<uid>' + GetCData(this.uid) + '</uid>';
		return '<message' + sAttrs + '>' + sNodes + '</message>';
	},

	getFromXml: function(rootElement)
	{
		this._readCommon(rootElement);
		this._readCommonHeaders(rootElement);

		this.hasAttachments = XmlHelper.getBoolAttributeByName(rootElement, 'has_attachments', this.hasAttachments);
		this._readFlags(rootElement);

		var fromNode = XmlHelper.getFirstChildNodeByName(rootElement, 'from');
		this.fromAddr = XmlHelper.getFirstChildValue(fromNode, this.fromAddr);
	},

	makeSearchResult: function (searchString)
	{
		this.fromAddr = this.fromAddr.replaceStr(searchString, HighlightMessageLine);
		this.subject = this.subject.replaceStr(searchString, HighlightMessageLine);
	},

//private
	_readFlags: function (rootElement)
	{
		var flags = XmlHelper.getIntAttributeByName(rootElement, 'flags', 0);
		if (flags & 1) this.read = true;
		if (flags & 2) this.replied = true;
		if (flags & 4) this.flagged = true;
		if (flags & 8) this.deleted = true;
		if (flags & 256) this.forwarded = true;
		if (flags & 512) this.gray = true;
	}
};

CMessageHeaders.prototype.getStringDataKeys = CMessage.prototype.getStringDataKeys;
CMessageHeaders.prototype.getIdForList = CMessage.prototype.getIdForList;
CMessageHeaders.prototype.isCorrectData = CMessage.prototype.isCorrectData;
CMessageHeaders.prototype.setSize = CMessage.prototype.setSize;
CMessageHeaders.prototype.isEqual = CMessage.prototype.isEqual;
CMessageHeaders.prototype._readCommon = CMessage.prototype._readCommon;
CMessageHeaders.prototype._readCommonHeaders = CMessage.prototype._readCommonHeaders;

function CKeyMessages(oArgument)
{
	if (typeof(oArgument) === 'string') {
		this.iAcctId = WebMail.Accounts.currId;
		this.iFolderId = -1;
		this.sFolderFullName = '';
		this.iSortField = SORT_FIELD_DATE;
		this.iSortOrder = SORT_ORDER_DESC;
		this.iPage = 1;
		this.sLookFor = oArgument;
		this.iSearchMode = 0;
		this.iFilter = MESSAGE_LIST_FILTER_NONE;
		this.iMsgsCount = 0;
	}
	else {
		this.iAcctId = oArgument.iAcctId;
		this.iFolderId = oArgument.iFolderId;
		this.sFolderFullName = oArgument.sFolderFullName;
		this.iSortField = oArgument.iSortField;
		this.iSortOrder = oArgument.iSortOrder;
		this.iPage = oArgument.iPage;
		this.sLookFor = oArgument.sLookFor;
		this.iSearchMode = oArgument.iSearchMode;
		this.iFilter = oArgument.iFilter;
		this.iMsgsCount = oArgument.iMsgsCount;
	}
}

CKeyMessages.prototype = {
	getStringDataKeys: function()
	{
		var aDataKeys = [ this.iAcctId, this.iPage, this.iSortField, this.iSortOrder, this.iFolderId,
			this.sFolderFullName, this.sLookFor, this.iSearchMode, this.iFilter ];
		return aDataKeys.join(STR_SEPARATOR);
	},

	update: function (oMsgList)
	{
		if (this.isEqualFolder(oMsgList.idFolder, oMsgList.folderFullName)) {
			this.iSortField = oMsgList.sortField;
			this.iSortOrder = oMsgList.sortOrder;
			this.iPage = oMsgList.page;
			this.sLookFor = oMsgList.lookFor;
			this.iSearchMode = oMsgList._searchFields;
			this.iFilter = oMsgList.iFilter;
			this.iMsgsCount = oMsgList.messagesCount;
		}
	},

	copy: function (oKeyMsgList)
	{
		if (this.isEqualFolder(oKeyMsgList.iFolderId, oKeyMsgList.sFolderFullName)) {
			this.iSortField = oKeyMsgList.iSortField;
			this.iSortOrder = oKeyMsgList.iSortOrder;
			this.iPage = oKeyMsgList.iPage;
			this.sLookFor = oKeyMsgList.sLookFor;
			this.iSearchMode = oKeyMsgList.iSearchMode;
			this.iFilter = oKeyMsgList.iFilter;
			this.iMsgsCount = oKeyMsgList.iMsgsCount;
		}
	},

	reset: function (iAcctId)
	{
		this.iAcctId = iAcctId;
		this.iFolderId = -1;
		this.sFolderFullName = '';
		this.iPage = 1;
		this.sLookFor = '';
		this.iSearchMode = 0;
		this.iFilter = MESSAGE_LIST_FILTER_NONE;
	},

	updateSearch: function (iFolderId, sFolderFullName, iPage, sLookFor, iSearchMode)
	{
		this.iFolderId = iFolderId;
		this.sFolderFullName = sFolderFullName;
		this.iPage = iPage;
		this.sLookFor = sLookFor;
		this.iSearchMode = iSearchMode;
	},

	updateFolder: function (iFolderId, sFolderFullName, iPage, iSortField, sLookFor, iSearchMode, iFilter)
	{
		this.iFolderId = iFolderId;
		this.sFolderFullName = sFolderFullName;
		if (iPage !== undefined) {
			this.iPage = iPage;
			this.iSortField = iSortField;
			this.sLookFor = sLookFor;
			this.iSearchMode = iSearchMode;
			this.iFilter = iFilter;
		}
	},

	updatePage: function (iPage)
	{
		this.iPage = iPage;
	},

	updateSort: function (iSortField, iSortOrder)
	{
		this.iSortField = iSortField;
		this.iSortOrder = iSortOrder;
	},

	updateFilter: function (iFilter)
	{
		this.iFilter = iFilter;
	},

	updateCount: function (iMsgsCount)
	{
		this.iMsgsCount = iMsgsCount;
	},

	getNewBySearch: function (iFolderId, sFolderFullName, iPage, sLookFor, iSearchMode)
	{
		var oNewKeyMessageList = new CKeyMessages(this);
		oNewKeyMessageList.updateSearch(iFolderId, sFolderFullName, iPage, sLookFor, iSearchMode);
		return oNewKeyMessageList;
	},

	getNewByFolder: function (iFolderId, sFolderFullName, iPage, iSortField, sLookFor, iSearchMode, iFilter)
	{
		var oNewKeyMessageList = new CKeyMessages(this);
		oNewKeyMessageList.updateFolder(iFolderId, sFolderFullName, iPage, iSortField, sLookFor, iSearchMode, iFilter);
		return oNewKeyMessageList;
	},

	getNewByPage: function (iPage)
	{
		var oNewKeyMessageList = new CKeyMessages(this);
		oNewKeyMessageList.updatePage(iPage);
		return oNewKeyMessageList;
	},

	getNewBySort: function (iSortField, iSortOrder)
	{
		var oNewKeyMessageList = new CKeyMessages(this);
		oNewKeyMessageList.updateSort(iSortField, iSortOrder);
		return oNewKeyMessageList;
	},

	getNewByFilter: function (iFilter)
	{
		var oNewKeyMessageList = new CKeyMessages(this);
		oNewKeyMessageList.updateFilter(iFilter);
		return oNewKeyMessageList;
	},

	getNextPage: function ()
	{
		var iPagesCount = Math.ceil(this.iMsgsCount / WebMail.Settings.msgsPerPage);
		var iNextPage = this.iPage + 1;
		if (iNextPage > iPagesCount) {
			return 0;
		}
		return iNextPage;
	},

	getFolder: function ()
	{
		return {id: this.iFolderId, fullName: this.sFolderFullName};
	},

	getLookForNode: function ()
	{
		return '<look_for fields="' + this.iSearchMode + '">' + GetCData(this.sLookFor) + '</look_for>';
	},

	getInXml: function ()
	{
		var sXml = '<folder id="' + this.iFolderId + '"><full_name>' + GetCData(this.sFolderFullName) + '</full_name></folder>';
		sXml += this.getLookForNode();
		return sXml;
	},

	isNextPageMsgList: function (oMsgList)
	{
		return (this.iFolderId === oMsgList.idFolder
		&& this.sFolderFullName === oMsgList.folderFullName
		&& this.iSortField === oMsgList.sortField
		&& this.iSortOrder === oMsgList.sortOrder
		&& this.iPage === (oMsgList.page - 1)
		&& this.sLookFor === oMsgList.lookFor
		&& this.iSearchMode === oMsgList._searchFields
		&& this.iFilter === oMsgList.iFilter);
	},

	isEqualMsgList: function (oMsgList)
	{
		return (this.iFolderId === oMsgList.idFolder
		&& this.sFolderFullName === oMsgList.folderFullName
		&& this.iSortField === oMsgList.sortField
		&& this.iSortOrder === oMsgList.sortOrder
		&& this.iPage === oMsgList.page
		&& this.sLookFor === oMsgList.lookFor
		&& this.iSearchMode === oMsgList._searchFields
		&& this.iFilter === oMsgList.iFilter);
	},

	isEqual: function (oKeyMsgList)
	{
		if (oKeyMsgList === null) return false;
		return (this.iFolderId === oKeyMsgList.iFolderId
		&& this.sFolderFullName === oKeyMsgList.sFolderFullName
		&& this.iSortField === oKeyMsgList.iSortField
		&& this.iSortOrder === oKeyMsgList.iSortOrder
		&& this.iPage === oKeyMsgList.iPage
		&& this.sLookFor === oKeyMsgList.sLookFor
		&& this.iSearchMode === oKeyMsgList.iSearchMode
		&& this.iFilter === oKeyMsgList.iFilter);
	},

	isEqualFolder: function (iFolderId, sFolderFullName)
	{
		return (this.iFolderId === iFolderId
		&& this.sFolderFullName === sFolderFullName);
	}
}

function CMessages()
{
	this.type = TYPE_MESSAGE_LIST;
	this.bAllowForwardedFlag = false;
	this.idAcct = WebMail.iAcctId;
	this.idFolder = -1;
	this.iFilter = MESSAGE_LIST_FILTER_NONE;
	this.folderType = -1;
	this.folderFullName = '';
	this.folderName = '';
	this.sortField = SORT_ORDER_DESC;
	this.sortOrder = SORT_ORDER_DESC;
	this.page = 1;
	this.messagesCount = 0;
	this.newMsgsCount = 0;
	this.lookFor = '';
	this._searchFields = 0;
	this.list = [];
	this.messagesBodies = new CMessagesBodies();
	
	this.bError = false;
}

CMessages.prototype = {
	getStringDataKeys: function()
	{
		var arDataKeys = [ this.idAcct, this.page, this.sortField, this.sortOrder, this.idFolder,
			this.folderFullName, this.lookFor, this._searchFields, this.iFilter ];
		return arDataKeys.join(STR_SEPARATOR);
	},

	getFirstMessage: function ()
	{
		if (this.list.length === 0) {
			return null;
		}
		else {
			return this.list[0];
		}
	},

	getLastMessage: function ()
	{
		if (this.list.length === 0) {
			return null;
		}
		else {
			return this.list[this.list.length - 1];
		}
	},

	getNearMessages: function (id, uid)
	{
		for (var i = 0; i < this.list.length; i++) {
			var oMsgHeaders = this.list[i];
			if (oMsgHeaders.id === id && oMsgHeaders.uid === uid) {
				var bFirstMsg = (i === 0);
				var oPrevMsg = (bFirstMsg) ? null: this.list[i - 1];
				var bLastMsg = (i === (this.list.length - 1));
				var oNextMsg = (bLastMsg) ? null : this.list[i + 1];
				return {bFirstMsg: bFirstMsg, oPrevMsg: oPrevMsg, bLastMsg: bLastMsg, oNextMsg: oNextMsg};
				break;
			}
		}
		return null;
	},

	hasMessage: function (iId, sUid)
	{
		var
			iIndex = 0,
			iLen = this.list.length,
			oMsgHeaders = null
		;

		iId = iId - 0;
		for (iIndex = 0; iIndex < iLen; iIndex++) {
			oMsgHeaders = this.list[iIndex];
			if (oMsgHeaders.id === iId && oMsgHeaders.uid === sUid) {
				return true;
				break;
			}
		}

		return false;
	},

	getFromXml: function(rootElement)
	{
		this.bAllowForwardedFlag = XmlHelper.getBoolAttributeByName(rootElement, 'allow_forwarded_flag', this.bAllowForwardedFlag);
		this.idAcct = XmlHelper.getIntAttributeByName(rootElement, 'id_acct', this.idAcct);
		this.iFilter = XmlHelper.getIntAttributeByName(rootElement, 'filter', this.iFilter);
		this.page = XmlHelper.getIntAttributeByName(rootElement, 'page', this.page);
		this.sortField = XmlHelper.getIntAttributeByName(rootElement, 'sort_field', this.sortField);
		this.sortOrder = XmlHelper.getIntAttributeByName(rootElement, 'sort_order', this.sortOrder);
		this.messagesCount = XmlHelper.getIntAttributeByName(rootElement, 'count', this.messagesCount);
		this.newMsgsCount = XmlHelper.getIntAttributeByName(rootElement, 'count_new', this.newMsgsCount);
		
		this.bError = XmlHelper.getIntAttributeByName(rootElement, 'error', this.bError);

		var msgsCount = 0;
		for (var i = 0; i < rootElement.childNodes.length; i++) {
			var childNode = rootElement.childNodes[i];
			switch (childNode.tagName) {
				case 'folder':
					this.idFolder = XmlHelper.getIntAttributeByName(childNode, 'id', this.idFolder);
					this.folderType = XmlHelper.getIntAttributeByName(childNode, 'type', this.folderType);
					var nameNode = XmlHelper.getFirstChildNodeByName(childNode, 'name');
					this.folderName = XmlHelper.getFirstChildValue(nameNode, this.folderName);
					var fullNameNode = XmlHelper.getFirstChildNodeByName(childNode, 'full_name');
					this.folderFullName = XmlHelper.getFirstChildValue(fullNameNode, this.folderFullName);
					break;
				case 'look_for':
					this._searchFields = XmlHelper.getIntAttributeByName(childNode, 'fields', this._searchFields);
					this.lookFor = XmlHelper.getFirstChildValue(childNode, this.lookFor);
					break;
				case 'message':
					var msgHeaders = new CMessageHeaders();
					msgHeaders.getFromXml(childNode);
					if (this.lookFor != '') {
						msgHeaders.makeSearchResult(this.lookFor);
					}
					this.list[msgsCount++] = msgHeaders;
					this.messagesBodies.add(msgHeaders);
					break;
			}
		}
	},

	isEqual: function (oMsgList)
	{
		if (oMsgList === null) {
			return false;
		}
		return (oMsgList.idAcct === this.idAcct && oMsgList.page === this.page
			&& oMsgList.sortField === this.sortField && oMsgList.sortOrder === this.sortOrder
			&& oMsgList.idFolder === this.idFolder && oMsgList.folderFullName === this.folderFullName
			&& oMsgList.lookFor === this.lookFor && oMsgList._searchFields === this._searchFields
			&& oMsgList.iFilter === this.iFilter);
	}
};

function CMessagesBodies()
{
	this.type = TYPE_MESSAGES_BODIES;
	this.folders = [];

	this.add = function (oMsgHeaders)
	{
		if (oMsgHeaders.size > 76800 && WebMail.Accounts.currMailProtocol !== IMAP4_PROTOCOL) return;
		var sFldIndex = oMsgHeaders.idFolder + oMsgHeaders.folderFullName;
		if (this.folders[sFldIndex] === undefined) {
			this.folders[sFldIndex] = {id: oMsgHeaders.idFolder, fullName: oMsgHeaders.folderFullName, messages: []};
		}
		this.folders[sFldIndex].messages.push(oMsgHeaders);
	};

	this.getInJson = function (aFldParams)
	{
		var
			oData = {},
			sFldIndex = '',
			oFolder = null,
			oFldParams = null,
			bPop3Server = (WebMail.Accounts.currMailProtocol === POP3_PROTOCOL),
			aMessages = [],
			bFilled = false
		;

		for (sFldIndex in this.folders) {
			oFolder = this.folders[sFldIndex];
			if (typeof(oFolder) === 'function') continue;

			oFldParams = aFldParams[sFldIndex];
			if (bPop3Server && (!oFldParams || oFldParams.iSyncType == SYNC_TYPE_DIRECT_MODE ||
				oFldParams.iSyncType == SYNC_TYPE_NEW_HEADERS || oFldParams.iSyncType == SYNC_TYPE_ALL_HEADERS)) {
				continue;
			}

			aMessages = this._getMessagesArray(oFolder);
			if (aMessages.length > 0) {
				oData[oFolder.id] = [oFolder.fullName, aMessages];
				bFilled = true;
			}
		}

		return (bFilled) ? JSON.stringify(oData) : '';
	};

	this._getMessagesArray = function (oFolder)
	{
		var
			iMsgIndex = 0,
			iCount = oFolder.messages.length,
			oMsgHeaders = null,
			sCacheKey = '',
			aMessages = [],
			aMessage = []
		;

		for (; iMsgIndex < iCount; iMsgIndex++) {
			oMsgHeaders = oFolder.messages[iMsgIndex];
			sCacheKey = WebMail.DataSource.getStringDataKeyFromObj(TYPE_MESSAGE, oMsgHeaders);

			if (WebMail.DataSource.cache.existsData(TYPE_MESSAGE, sCacheKey)) {
				continue;
			}

			aMessage = [oMsgHeaders.id, oMsgHeaders.uid, oMsgHeaders.charset, oMsgHeaders.size,
				oMsgHeaders.isVoice];

			aMessages.push(aMessage);
		}

		return aMessages;
	};

	this.getInXml = function (aFldParams)
	{
		PreFetch.initMessagesBodies();
		var sXml = '';
		var bImapServer = (WebMail.Accounts.currMailProtocol === IMAP4_PROTOCOL);
		for (var sFldIndex in this.folders) {
			var oFolder = this.folders[sFldIndex];
			if (typeof(oFolder) === 'function') continue;

			var oFldParams = aFldParams[sFldIndex];
			if (!bImapServer && (!oFldParams || oFldParams.iSyncType == SYNC_TYPE_DIRECT_MODE ||
				oFldParams.iSyncType == SYNC_TYPE_NEW_HEADERS || oFldParams.iSyncType == SYNC_TYPE_ALL_HEADERS)) {
				continue;
			}
			var sNodes = '';
			var iCount = oFolder.messages.length;
			for (var iMsgIndex = 0; iMsgIndex < iCount; iMsgIndex++) {
				var oMsgHeaders = oFolder.messages[iMsgIndex];
				var sCacheKey = WebMail.DataSource.getStringDataKeyFromObj(TYPE_MESSAGE, oMsgHeaders);

				if (PreFetch.hasInCache(sCacheKey)) {
					continue;
				}
				if (WebMail.DataSource.cache.existsData(TYPE_MESSAGE, sCacheKey)) {
					continue;
				}

				if (PreFetch.enoughMessagesBodies(sCacheKey)) {
					break;
				}

				sNodes += oMsgHeaders.getInXml();
			}
			if (sNodes.length == 0) {
				continue;
			}
			sXml += '<folder id="' + oFolder.id + '">';
			sXml += '<full_name>' + GetCData(oFolder.fullName) + '</full_name>';
			sXml += sNodes + '</folder>';
		}
		return sXml;
	};
}

function CFolder(level, listHide)
{
	this.bAllowForwardedFlag = false;
	this.id = 0;
	this.idParent = 0;
	this.type = FOLDER_TYPE_DEFAULT;
	this.sentDraftsType = false;
	this.syncType = SYNC_TYPE_NO;
	this.hide = false;
	this.listHide = listHide;
	this.fldOrder = 0;
	this.msgCount = 0;
	this.newMsgCount = 0;
	this.noselect = false;
	this.invisible = false;
	this.size = 0;

	this.name = '';
	this.fullName = '';
	this.level = level;
	this.hasChilds = false;
	this.folders = [];
}

CFolder.prototype = {
	getNameByType: function(type, name)
	{
		if (type == undefined) type = this.type;
		if (name == undefined) name = this.name;
		var fDesc = FolderDescriptions[type];
		if (fDesc == undefined || fDesc.langField == undefined) {
			return name;
		}
		else {
			return Lang[fDesc.langField];
		}
	},

	isEqual: function (oFolder)
	{
		return (this.id === oFolder.id && this.fullName === oFolder.fullName);
	},

	getFromXml: function(rootElement, parentSentDraftsType)
	{
		this.id = XmlHelper.getIntAttributeByName(rootElement, 'id', this.id);
		this.idParent = XmlHelper.getIntAttributeByName(rootElement, 'id_parent', this.idParent);
		this.type = XmlHelper.getIntAttributeByName(rootElement, 'type', this.type);
		this.sentDraftsType = (parentSentDraftsType || this.type == FOLDER_TYPE_SENT || this.type == FOLDER_TYPE_DRAFTS);
		this.syncType = XmlHelper.getIntAttributeByName(rootElement, 'sync_type', this.syncType);
		this.hide = XmlHelper.getBoolAttributeByName(rootElement, 'hide', this.hide);
		this.listHide = (this.hide) ? this.hide : this.listHide;
		this.fldOrder = XmlHelper.getIntAttributeByName(rootElement, 'fld_order', this.fldOrder);
		this.msgCount = XmlHelper.getIntAttributeByName(rootElement, 'count', this.msgCount);
		this.newMsgCount = XmlHelper.getIntAttributeByName(rootElement, 'count_new', this.newMsgCount);
		this.noselect = XmlHelper.getBoolAttributeByName(rootElement, 'noselect', this.noselect);
		this.invisible = XmlHelper.getBoolAttributeByName(rootElement, 'invisible', this.invisible);
		this.size = XmlHelper.getIntAttributeByName(rootElement, 'size', this.size);

		var nameNode = XmlHelper.getFirstChildNodeByName(rootElement, 'name');
		this.name = XmlHelper.getFirstChildValue(nameNode, this.name);
		var fullNameNode = XmlHelper.getFirstChildNodeByName(rootElement, 'full_name');
		this.fullName = XmlHelper.getFirstChildValue(fullNameNode, this.fullName);

		var foldersNode = XmlHelper.getFirstChildNodeByName(rootElement, 'folders');
		var foldersChilds = (foldersNode != null) ? foldersNode.childNodes : [];
		for (var i = 0; i < foldersChilds.length; i++) {
			var fldNode = foldersChilds[i];
			if (fldNode.tagName == 'folder') {
				var folder = new CFolder(this.level + 1, this.listHide);
				folder.getFromXml(fldNode, this.sentDraftsType);
				this._addFolder(folder);
			}
		}

		if (this.type != FOLDER_TYPE_INBOX && this.type != FOLDER_TYPE_SENT &&
			this.type != FOLDER_TYPE_DRAFTS && this.type != FOLDER_TYPE_TRASH &&
			this.type != FOLDER_TYPE_SPAM && this.type != FOLDER_TYPE_QUARANTINE &&
			this.type != FOLDER_TYPE_SYSTEM){
			this.type = FOLDER_TYPE_DEFAULT;
		}
	},

	_addFolder: function (folder)
	{
		var childFolders = folder.folders;
		if (childFolders.length > 0) folder.hasChilds = true;
		delete folder.folders;
		if (!folder.invisible) {
			this.folders.push(folder)
		}
		this.folders = this.folders.concat(childFolders);
	}
};

function CFolderList()
{
	this.type = TYPE_FOLDER_LIST;
	this.folders = [];
	this.idAcct = -1;
	this.sync = GET_FOLDERS_NOT_SYNC;
	this.nameSpace = '';
	this.gmailfix = '';
	this.inboxSyncType = SYNC_TYPE_DIRECT_MODE;
	this.hasFolderInDm = false;
	this.bAllFoldersInDm = true;
	this.iLastSystemType = FOLDER_TYPE_DEFAULT;
	this.inbox = null;
	this.sent = null;
	this.drafts = null;
	this.trash = null;
	this.spam = null;
}

CFolderList.prototype = {
	getStringDataKeys: function()
	{
		return this.idAcct;
	},

	setAllowForwardedFlag: function (iFolderId, sFolderFullName, bAllowForwardedFlag)
	{
		for (var i = 0; i < this.folders.length; i++) {
			var oFolder = this.folders[i];
			if (oFolder.id === iFolderId && oFolder.fullName === sFolderFullName) {
				oFolder.bAllowForwardedFlag = bAllowForwardedFlag;
				this.folders[i] = oFolder;
				break;
			}
		}
	},

	getAllowForwardedFlag: function (iFolderId, sFolderFullName)
	{
		for (var i = 0; i < this.folders.length; i++) {
			var oFolder = this.folders[i];
			if (oFolder.id === iFolderId && oFolder.fullName === sFolderFullName) {
				return oFolder.bAllowForwardedFlag;
			}
		}
		return false;
	},

	getFromXml: function(rootElement)
	{
		this.idAcct = XmlHelper.getIntAttributeByName(rootElement, 'id_acct', this.idAcct);
		this.sync = XmlHelper.getIntAttributeByName(rootElement, 'sync', this.sync);
		this.nameSpace = XmlHelper.getAttributeByName(rootElement, 'namespace', this.nameSpace);
		this.gmailfix = XmlHelper.getAttributeByName(rootElement, 'gmailfix', this.gmailfix);

		var foldersXML = rootElement.childNodes;
		for (var i = 0; i < foldersXML.length; i++) {
			var folder = new CFolder(0, false);
			folder.getFromXml(foldersXML[i], false);
			this._addFolder(folder);
		}
		this._prepareData();
	},

	getNameSpaceFolderId: function()
	{
		if (this.nameSpace.length > 0) {
			var iCount = this.folders.length;
			for (var i = 0; i < iCount; i++) {
				var folder = this.folders[i];
				if (folder.fullName == this.nameSpace.substr(0, this.nameSpace.length - 1)) {
					return folder.id;
				}
			}
		}
		return -1;
	},

	getFirstFolderInDb: function ()
	{
		var iCount = this.folders.length;
		for (var i = 0; i < iCount; i++) {
			var folder = this.folders[i];
			if (folder.syncType != SYNC_TYPE_DIRECT_MODE) {
				return {id: folder.id, fullName: folder.fullName};
			}
		}
		return {id: -1, fullName: ''};
	},

	getFolderName: function (iId, sFullName)
	{
		var iCount = this.folders.length;
		for (var i = 0; i < iCount; i++) {
			var folder = this.folders[i];
			if (folder.id === iId && folder.fullName === sFullName) {
				var sLangField = FolderDescriptions[folder.type].langField;
				if (typeof(sLangField) === 'string') {
					return Lang[sLangField];
				}
				else {
					return folder.name;
				}
			}
		}
		return '';
	},

	getFolderMessagesCount: function (iId, sFullName)
	{
		var iCount = this.folders.length;
		for (var i = 0; i < iCount; i++) {
			var folder = this.folders[i];
			if (folder.id === iId && folder.fullName === sFullName) {
				return folder.msgCount;
			}
		}
		return 0;
	},

	getFolderByName: function (sName, bRegister)
	{
		var
			iIndex = 0,
			iLen = this.folders.length,
			oFolder = null,
			sFldName = ''
		;
		if (!bRegister) {
			sName = sName.toLowerCase();
		}
		for (; iIndex < iLen; iIndex++) {
			oFolder = this.folders[iIndex];
			sFldName = (bRegister) ? oFolder.name : oFolder.name.toLowerCase();
			if (sFldName === sName) {
				return oFolder;
			}
		}
		return null;
	},

	setMessagesCount: function (idFolder, folderFullName, count, countNew)
	{
		for (var i = this.folders.length - 1; i >= 0; i--) {
			var folder = this.folders[i];
			if (folder.id == idFolder && folder.fullName == folderFullName) {
				folder.msgCount = count;
				folder.newMsgCount = countNew;
			}
		}
	},

	_prepareData: function ()
	{
		var iCount = this.folders.length;
		var dmFoldersCount = 0;
		for (var i = 0; i < iCount; i++) {
			var folder = this.folders[i];
			var level = folder.level;
			if (this.nameSpace.length > 0 && this.folders[i].level > 0
				&& folder.fullName.substr(0, this.nameSpace.length) == this.nameSpace)
			{
				level = level - 1;
			}

			if (this.gmailfix.length > 0 && this.folders[i].level > 0
				&& folder.fullName.substr(0, this.gmailfix.length) == this.gmailfix)
			{
				level = level - 1;
			}

			this.folders[i].level = level;
			this.folders[i].intIndent = level * FOLDERS_TREES_INT_INDENT;
			var strIndent = '';
			for (var j = 0; j < level; j++) {
				strIndent += FOLDERS_TREES_STR_INDENT;
			}
			this.folders[i].strIndent = strIndent;
		    if (folder.syncType == SYNC_TYPE_DIRECT_MODE) {
				dmFoldersCount++;
			}
			if (folder.type !== FOLDER_TYPE_DEFAULT) {
				this.iLastSystemType = folder.type;
			}
			switch (folder.type) {
				case FOLDER_TYPE_INBOX:
					this.inbox = {id: folder.id, fullName: folder.fullName};
					this.inboxSyncType = folder.syncType;
					break;
				case FOLDER_TYPE_SENT:
					this.sent = {id: folder.id, fullName: folder.fullName};
					break;
				case FOLDER_TYPE_DRAFTS:
					this.drafts = {id: folder.id, fullName: folder.fullName};
					break;
				case FOLDER_TYPE_TRASH:
					this.trash = {id: folder.id, fullName: folder.fullName};
					break;
				case FOLDER_TYPE_SPAM:
					this.spam = {id: folder.id, fullName: folder.fullName};
					break;
			}
		}
		this.hasFolderInDm = (dmFoldersCount > 0);
		this.bAllFoldersInDm = (dmFoldersCount === iCount);
	}
};

CFolderList.prototype._addFolder = CFolder.prototype._addFolder;

function CUpdate()
{
	this.type = TYPE_UPDATE;
	this.value = '';
	this.bSentMessageSaveError = false;
	this.saveMessage = null;
	this.accountProperties = null;
	this.sEventUid = '';
	this.sContactUid = '';
}

CUpdate.prototype = {
	getStringDataKeys: function()
	{
		return '';
	},

	getFromXml: function(eRoot)
	{
		this.value = XmlHelper.getAttributeByName(eRoot, 'value', this.value);
		switch (this.value) {
			case 'save_message':
				this._getSaveMessageFromXml(eRoot);
				break;
			case 'send_message':
				this._getSendMessageErrorFromXml(eRoot);
				break;
			case 'account_properties':
				this._getAccountPropertiesFromXml(eRoot);
				break;
			case 'save_vcf':
				this.sContactUid = XmlHelper.getAttributeByName(eRoot, 'uid', '');
				break;
			case 'save_ics':
			case 'process_appointment':
				this.sEventUid = XmlHelper.getAttributeByName(eRoot, 'uid', '');
				break;
		}
	},

	_getSaveMessageFromXml: function (eRoot)
	{
		var
			iId = XmlHelper.getIntAttributeByName(eRoot, 'id', -1),
			bAutosave = XmlHelper.getBoolAttributeByName(eRoot, 'autosave', false),
			eUid = XmlHelper.getFirstChildNodeByName(eRoot, 'uid'),
			sUid = XmlHelper.getFirstChildValue(eUid, '')
		;
		if (sUid == '-1') sUid = '';
		this.saveMessage = {id: iId, uid: sUid, bAutosave: bAutosave};
	},

	_getSendMessageErrorFromXml: function (eRoot)
	{
		this.bSentMessageSaveError = XmlHelper.getBoolAttributeByName(eRoot, 'save_in_sent_error', this.bSentMessageSaveError);
	},

	_getAccountPropertiesFromXml: function (eRoot)
	{
		var
			eAccount = XmlHelper.getFirstChildNodeByName(eRoot, 'account'),
			accountId = XmlHelper.getIntAttributeByName(eAccount, 'id', null),
			imapQuota = XmlHelper.getIntAttributeByName(eAccount, 'imap_quota', null),
			imapQuotaLimit = XmlHelper.getIntAttributeByName(eAccount, 'imap_quota_limit', null)
		;
		this.accountProperties = {id: accountId, imapQuota: imapQuota,
			imapQuotaLimit: imapQuotaLimit};
	}
};

function CIdentities()
{
	this.type = TYPE_IDENTITIES;

	this.aItems = [];
	this.iCount = 0;
	this.iCurrId = NON_EXISTENT_ID;
	this.iCurrAcctId = -1;
}

CIdentities.prototype = {
	getStringDataKeys: function ()
	{
		return '';
	},

	getIdentityById: function (iId, iAcctId)
	{
		for (var i = 0; i < this.iCount; i++) {
			if (this.aItems[i].iId === iId && this.aItems[i].iAcctId === iAcctId) {
				return this.aItems[i];
			}
		}
		return null;
	},

	isCurrentIdentity: function (oIdentity)
	{
		return (oIdentity.iId === this.iCurrId && oIdentity.iAcctId === this.iCurrAcctId);
	},

	getCurrentIdentity: function ()
	{
		if (!WebMail.Settings.bAllowIdentities) {
			return this.getIdentityById(NON_EXISTENT_ID, WebMail.Accounts.editableId);
		}
		return this.getIdentityById(this.iCurrId, this.iCurrAcctId);
	},

	changeCurrentIdentity: function (iId, iAcctId)
	{
		var oIdentity = this.getIdentityById(iId, iAcctId);
		if (oIdentity !== null) {
			this.iCurrId = iId;
			this.iCurrAcctId = iAcctId;
		}
	},

	clearCurrentId: function ()
	{
		this.iCurrId = NON_EXISTENT_ID;
		this.iCurrAcctId = -1;
	},

	removeById: function (iId)
	{
		for (var i = 0; i < this.iCount; i++) {
			if (this.aItems[i].iId === iId) {
				this.aItems.splice(i, 1);
				break;
			}
		}
		this.iCount = this.aItems.length;
	},

	applyNewAccountName: function (oAccount)
	{
		for (var i = 0; i < this.iCount; i++) {
			if (this.aItems[i].iId === NON_EXISTENT_ID && this.aItems[i].iAcctId === oAccount.id) {
				this.aItems[i].sName = oAccount.friendlyName;
			}
		}
	},

	addNewIdentity: function (oAccount)
	{
		var oIdentity = new CIdentity();
		oIdentity.iAcctId = oAccount.id;
		oIdentity.sEmail = oAccount.email;
		oIdentity.sName = oAccount.friendlyName;
		this.aItems.push(oIdentity);
		this.iCount = this.aItems.length;
	},

	getFromXml: function(nRoot)
	{
		var anIdentities = nRoot.childNodes;
		for (var i = 0; i < anIdentities.length; i++) {
			if (anIdentities[i].tagName === 'identity') {
		        var oIdentity = new CIdentity();
		        oIdentity.getFromXml(anIdentities[i]);
				this.aItems.push(oIdentity);
			}
		}
		this.iCount = this.aItems.length;
	},

	getFromArray: function (aIdentities)
	{
		for (var i = 0; i < aIdentities.length; i++) {
			var oIdentity = new CIdentity();
			oIdentity.copy(aIdentities[i]);
			this.aItems.push(oIdentity);
		}
		this.iCount = this.aItems.length;
	}
};

function CIdentity()
{
	this.type = TYPE_IDENTITY;

	this.bHtmlSignature = false;
	this.bUseSignature = false;
	this.iAcctId = WebMail.Accounts.editableId;
	this.iId = NON_EXISTENT_ID;
	this.sEmail = '';
	this.sName = '';
	this.sSignature = '';
}

CIdentity.prototype = {
	getStringDataKeys: function ()
	{
		return '';
	},

	getHtmlSignature: function ()
	{
		if (this.bHtmlSignature) {
			return HtmlDecode(this.sSignature);
		}
		else {
			return TextFormatter.plainToHtml(this.sSignature);
		}
	},

	getPlainSignature: function ()
	{
		if (this.bHtmlSignature) {
			return TextFormatter.htmlToPlain(HtmlDecode(this.sSignature));
		}
		else {
			return this.sSignature;
		}
	},

	getInXml: function ()
	{
		var sAttrs = ' html_signature="' + ((this.bHtmlSignature) ? '1' : '0') + '"';
		sAttrs += ' use_signature="' + ((this.bUseSignature) ? '1' : '0') + '"';
		sAttrs += ' id_acct="' + this.iAcctId + '"';
		sAttrs += ' id="' + this.iId + '"';
		var sNodes = '<email>' + GetCData(this.sEmail) + '</email>';
		sNodes += '<name>' + GetCData(this.sName) + '</name>';
		sNodes += '<signature>' + GetCData(this.sSignature) + '</signature>';
		return '<identity' + sAttrs + '>' + sNodes + '</identity>';
	},

	getFromXml: function(nRoot)
	{
		this.bHtmlSignature = XmlHelper.getBoolAttributeByName(nRoot, 'html_signature', this.bHtmlSignature);
		this.bUseSignature = XmlHelper.getBoolAttributeByName(nRoot, 'use_signature', this.bUseSignature);
		this.iAcctId = XmlHelper.getIntAttributeByName(nRoot, 'id_acct', this.iAcctId);
		this.iId = XmlHelper.getIntAttributeByName(nRoot, 'id', this.iId);

		var nEmail = XmlHelper.getFirstChildNodeByName(nRoot, 'email');
		this.sEmail = XmlHelper.getFirstChildValue(nEmail, this.sEmail);

		var nName = XmlHelper.getFirstChildNodeByName(nRoot, 'name');
		this.sName = XmlHelper.getFirstChildValue(nName, this.sName);

		var nSignature = XmlHelper.getFirstChildNodeByName(nRoot, 'signature');
		this.sSignature = XmlHelper.getFirstChildValue(nSignature, this.sSignature);
	},

	copy: function (oIdentity)
	{
		this.bHtmlSignature = oIdentity.bHtmlSignature;
		this.bUseSignature = oIdentity.bUseSignature;
		this.iAcctId = oIdentity.iAcctId;
		this.iId = oIdentity.iId;
		this.sEmail = oIdentity.sEmail;
		this.sName = oIdentity.sName;
		this.sSignature = oIdentity.sSignature;
	}
}

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
