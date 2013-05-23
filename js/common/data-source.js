/*
 * Classes:
 *  CDataType(type, caching, cacheLimit, cacheByParts, requestParams, getRequest)
 *  CDataSource(dataTypes, actionUrl, errorHandler, loadHandler, takeDataHandler, requestHandler)
 *  CCache(dataTypes)
 *  CHistoryStorage(settingsStorage)
 */

function CDataType(type, caching, cacheLimit, cacheByParts, requestParams, getRequest)
{
	this.type = type; //int
	this.caching = caching; //bool
	this.cacheLimit = cacheLimit; //int
	this.cacheByParts = cacheByParts; //bool
	this.requestParams = requestParams; //obj
	/*
	ex. for messages list: {
			idFolder: "id_folder",
			sortField: "sort_field",
			sortOrder: "sort_order",
			page: "page"
		}
	*/
	this.getRequest = getRequest; //string; ex. for messages list: 'messages'
}

function CDataSource(dataTypes, actionUrl, errorHandler, loadHandler, takeDataHandler, requestHandler)
{
	this.cache = new CCache(dataTypes);
	this.netLoader = new CNetLoader(actionUrl, loadHandler, errorHandler);

	this._actionUrl = actionUrl;

	this._onError = errorHandler;
	this._onGet = takeDataHandler;
	this._onRequest = requestHandler;

	this._dataTypes = [];
	for (var key = 0; key < dataTypes.length; key++) {
		this._dataTypes[dataTypes[key].type] = dataTypes[key];
	}

	this.lastFromCache = false;
	this.needInfo = true;
	this.waitMessagesBodies = false;
}

CDataSource.prototype = {
	getDataTypeGetRequest: function (intDataType)
	{
		return this._dataTypes[intDataType].getRequest;
	},

	getStringDataKey: function (intDataType, objDataKeys)
	{
		var dataType = this._dataTypes[intDataType];
		var arDataKeys = [];
		for (var key in objDataKeys) {
			if (typeof(objDataKeys[key]) === 'function') continue;
			if (key == 'sync') continue;
			arDataKeys.push(objDataKeys[key]);
		}
		if (dataType.caching) {
			return dataType.getRequest + STR_SEPARATOR + arDataKeys.join(STR_SEPARATOR);
		}
        else {
			return dataType.getRequest;
		}
	},

	getStringDataKeyFromObj: function (iType, oData)
	{
		var oType = this._dataTypes[iType];
		if (oType.caching) {
			return oType.getRequest + STR_SEPARATOR + oData.getStringDataKeys();
		}
        else {
			return oType.getRequest;
		}
	},

	get: function (intDataType, objDataKeys, arDataParts, xml, background)
	{
		var cache = this.cache;
		var dataType = this._dataTypes[intDataType];
		var cacheByParts = dataType.cacheByParts;

		var mode = 0;
		if (cacheByParts) {
			for (var key in arDataParts) {
				if (typeof(arDataParts[key]) === 'number') {
					mode = (1 << arDataParts[key]) | mode;
				}
			}
		}

		var dataSize = null;
		if (intDataType == TYPE_MESSAGE && typeof(objDataKeys.size) != 'undefined') {
			dataSize = objDataKeys.size;
			delete objDataKeys.size;
		}

		var stringDataKeys = this.getStringDataKey(intDataType, objDataKeys);

		var data = null;
		if (dataType.caching && cache.existsData(intDataType, stringDataKeys)) {// there is in the cache!
			data = cache.getData(intDataType, stringDataKeys);
			if (cacheByParts) {
				mode = (mode | data.parts) ^ data.parts;
			}
		}

		if ((data == null) || (cacheByParts && (mode != 0))) {
			var arParams = [];
			arParams['action'] = 'get';
			arParams['request'] = dataType.getRequest;
			if (cacheByParts) {
				arParams['mode'] = mode;
			}
			if (background) {
				arParams['background'] = '1';
			}
			var objRequestParams = dataType.requestParams;
			for (var Param in objRequestParams) {
				if (typeof(objRequestParams[Param]) === 'function') continue;
				arParams[objRequestParams[Param]] = objDataKeys[Param];
			}
			if (null !== dataSize) {
				arParams['size'] = dataSize;
			}
			if (intDataType == TYPE_BASE) {
				arParams['flash_version'] = FlashVersion();
				arParams['js_timeoffset'] = (-(new Date()).getTimezoneOffset());
			}

			var xmlParams = this._getXml(arParams, xml);
			if (intDataType != TYPE_MESSAGES_BODIES && this.needInfo && !background) {
				this._onRequest.call({action: arParams['action'], request: arParams['request']});
			}
			if (intDataType == TYPE_MESSAGES_BODIES) {
				this.waitMessagesBodies = true;
			}

			this.netLoader.loadXmlDoc('xml=' + encodeURIComponent(xmlParams), arParams['action'],
				arParams['request'], background);
			this.lastFromCache = false;
		}
		else if (!background) {
			this._onGet.call({data: data});
			this.lastFromCache = true;
		}
	},

	set: function (messageParams, field, value, isAllMess)
	{
		this.cache.setData(TYPE_MESSAGE_LIST, messageParams, field, value, isAllMess);
	},

	request: function (objParams, xml, bBackground)
	{
		var xmlParams = this._getXml(objParams, xml);
		if (this.needInfo && !bBackground) {
			this._onRequest.call({action: objParams.action, request: objParams.request});
		}
		this.netLoader.loadXmlDoc('xml=' + encodeURIComponent(xmlParams), objParams.action,
			objParams.request, bBackground);
	},

	_getXml: function(arParams, xml)
	{
		var strResult = '';

		strResult += '<param name="token" value="' + GetCsrfToken() + '"/>';

		for (var paramName in arParams) {
			if (typeof(arParams[paramName]) === 'function') continue;
			strResult += '<param name="' + HtmlEncodeWithQuotes(paramName) + '" value="' + HtmlEncodeWithQuotes(arParams[paramName]) + '"/>';
		}
		strResult += xml;
		var bSendOrSaveMessage = ((arParams['action'] === 'send' || arParams['action'] === 'save')
			&& arParams['request'] === 'message');
		if (bSendOrSaveMessage) {
			strResult += Statistics.getXml();
		}
		return '<?xml version="1.0" encoding="utf-8"?><webmail>' + strResult + '</webmail>';
	},

	_onParsingError: function (action, request)
	{
		this._onError.call({errorDesc: Lang.ErrorDataTransferFailed, action: action, request: request});
	},

	parseXml: function(oXmlDoc, sAction, sRequest)
	{
		if (!oXmlDoc || !(oXmlDoc.documentElement) || typeof(oXmlDoc) !== 'object' || typeof(oXmlDoc.documentElement) !== 'object') {
			this._onParsingError(sAction, sRequest);
			return;
		}
		var oRootNode = oXmlDoc.documentElement;
		if (!oRootNode || oRootNode.tagName !== 'webmail') {
			this._onParsingError(sAction, sRequest);
			return;
		}
		var sComplex = oRootNode.getAttribute('complex');

		var bBackground = oRootNode.getAttribute('background');
		bBackground = (bBackground === '1');
		var aNodes = oRootNode.childNodes;
		if (aNodes.length === 0 && sComplex !== 'messages_bodies') {
			if (!bBackground) {
				this._onParsingError(sAction, sRequest);
			}
			return;
		}

		var bShowError = (sComplex != 'messages_bodies' && !bBackground);
		var aData = this._getDataArray(aNodes, bShowError, sAction, sRequest);

		var bTrialEnd = oRootNode.getAttribute('trialend');
		bTrialEnd = (bTrialEnd === '1');
		this._placeData(aData, bBackground, sComplex, bTrialEnd);
	},

	_placeData: function (aData, bBackground, sComplex, bTrialEnd)
	{
		if (bBackground && aData['messages']) {
			WebMail.RequestFoldersMessageList();
		}
		if (bBackground || sComplex === 'folders_base') {
			return;
		}
		if (sComplex === 'messages_bodies') {
			this.waitMessagesBodies = false;
			this._onGet.call({data: {type: TYPE_MESSAGES_BODIES}});
			return;
		}

		if (sComplex === 'base' && bTrialEnd) {
			WebMail.showTrial();
		}

		if (this.needInfo) {
			WebMail.hideInfo();
		}
		this.needInfo = true;
		if (sComplex === 'account_base') {
			this._onGet.call({data: aData['folders_list']});
			if (aData['update']) {
				this._onGet.call({data: aData['update']});
			}
			return;
		}
		if (aData['message']) {
			// to miss contact object for WebMail placing
			this._onGet.call({data: aData['message']});
			return;
		}
		if (aData['contacts_groups']) {
			// to miss contact object for WebMail placing
			if (aData['groups']) {
				this._onGet.call({data: aData['groups']});
			}
			this._onGet.call({data: aData['contacts_groups']});
			return;
		}
		if (sComplex === 'base') {
			// to miss message list object for WebMail placing
			if (aData['identities']) {
				this._onGet.call({data: aData['identities']});
			}
			this._onGet.call({data: aData['settings_list']});
			if (aData['update']) {
				this._onGet.call({data: aData['update']});
			}
			if (aData['server-based-data']) {
				this._onGet.call({data: aData['server-based-data']});
			}
			if (aData['dossiers']) {
				this._onGet.call({data: aData['dossiers']});
			}
			return;
		}
		for (var sIndex in aData) {
			if (typeof(aData[sIndex]) === 'function') continue;
			this._onGet.call({data: aData[sIndex]});
		}
	},

	_getDataArray: function (aNodes, bShowError, sAction, sRequest)
	{
		var aData = [];
		for (var i = 0; i < aNodes.length; i++) {
			var oNode = aNodes[i];
			var oData = this._getDataObj(oNode, bShowError, sAction, sRequest);
			if (oData == null) {
				continue;
			}

			var iType = oData.type;
			var oType = this._dataTypes[iType];
			if (typeof(oType) === 'object') {
				oData.getFromXml(oNode);
				if (oData.type === TYPE_MESSAGE_LIST) {
					var oListScreen = WebMail.getCurrentListScreen();
					if (oListScreen && oData.isEqual(oListScreen.oMsgList)) {
						oData = oListScreen.oSelection.copyFlags(oData, oListScreen.id);
					}
				}
				if (oType.caching) {
					var sDataKeys = this.getStringDataKeyFromObj(iType, oData);
					if (this.cache.existsData(iType, sDataKeys)) {
						if (oType.cacheByParts) {
							oData = this.cache.getData(iType, sDataKeys);
							oData.getFromXml(oNode);
						}
						this.cache.replaceData(iType, sDataKeys, oData);
					}
                    else {
						var bError = (oData.type === TYPE_MESSAGE_LIST && oData.bError);
						if (!bError) {
							this.cache.addData(iType, sDataKeys, oData);
						}
					}
				}
				if (oData.type == TYPE_MESSAGE) {
					this.set([[oData.id], oData.idFolder, oData.folderFullName], 'read', true);
				}
			}
            else {
				oData.getFromXml(oNode);
			}
			var sTagName = oNode.tagName;
			if (sTagName == 'accounts') {
				this._onGet.call({data: oData});
			}
			else {
				if (aData[sTagName]) {
					sTagName += Math.random();
				}
				aData[sTagName] = oData;
			}
		}
		return aData;
	},

	_getDataObj: function (objectXml, showError, action, request)
	{
		var oData = null;
		
		switch (objectXml.tagName) {
			case 'settings_list':
				return new CSettingsList();
			case 'update':
				return new CUpdate();
			case 'accounts':
				return new CAccounts();
			case 'identities':
				return new CIdentities();
			case 'message':
				return new CMessage();
			case 'messages':
				return new CMessages();
			case 'operation_messages':
				return new COperationMessages();
			case 'folders_list':
				return new CFolderList();
			case 'settings':
				return new CSettings();
			case 'account':
				return new CAccountProperties();
			case 'filters':
				return new CFilters();
			case 'autoresponder':
				return new CAutoresponderData();
			case 'forward':
				return new CForwardData();
			case 'mobile_sync':
				return new CMobileSyncData();
			case 'outlook_sync':
				return new COutlookSyncData();
			case 'global_contacts':
				oData = new CContacts();
				oData.type = TYPE_GLOBAL_CONTACTS;
				return oData;
			case 'multiple_contacts':
				oData = new CContacts();
				oData.type = TYPE_MULTIPLE_CONTACTS;
				return oData;
			case 'contacts_groups':
				return new CContacts();
			case 'global_contact':
				oData = new CContact();
				oData.type = TYPE_GLOBAL_CONTACT;
				return oData;
			case 'contact':
				return new CContact();
			case 'groups':
				return new CGroups();
			case 'group':
				return new CGroup();
			case 'notification_settings':
				return new CCustomForwardingData();
			case 'attachment':
				if (typeof CServerAttachment === 'function') {
					return new CServerAttachment();
				}
				else {
					return null;
				}
			case 'server-attachment-list':
				if (typeof CServerAttachmentList === 'function') {
					return new CServerAttachmentList();
				}
				else {
					return null;
				}
			case 'server-based-data':
				if (typeof CServerBasedData === 'function') {
					return new CServerBasedData();
				}
				else {
					return null;
				}
			case 'dossiers':
				if (typeof CDossiersData === 'function') {
					return new CDossiersData();
				}
				else {
					return null;
				}
			case 'information':
				var info = objectXml.childNodes[0].nodeValue;
				if (info && info.length > 0) {
					WebMail.showReport(info, 10000);
				}
				return null;
			case 'error':
				var attr = objectXml.getAttribute('code');
				if (attr) {
					document.location = LoginUrl + '?error=' + attr;
				}
				else if (showError) {
					var errorDesc = objectXml.childNodes[0].nodeValue;
					if (!errorDesc || errorDesc.length == 0) {
						errorDesc = Lang.ErrorWithoutDesc;
					}
					if (window.console && window.console.log) {
						window.console.log(errorDesc);
					}
					this._onError.call({errorDesc: errorDesc, action: action, request: request});
				}
				return null;
			case 'session_error':
				document.location = LoginUrl + '?error=1';
				return null;
			default:
				return null;
		}
		return null;
	}
};

function CCache(dataTypes)
{
	this._dataTypes = [];
	this._dictionaries = [];
	this._addDataTypes(dataTypes);
}

CCache.prototype = {
	_addDataTypes: function(dataTypes)
	{
		for (var index = 0; index < dataTypes.length; index++) {
			var dataType = dataTypes[index];
			this._dataTypes[dataType.type] = dataType;
			this._dictionaries[dataType.type] = new CDictionary();
		}
	},

	existsData: function(dataType, key)
	{
		if (typeof this._dataTypes[dataType] == 'object' && typeof this._dictionaries[dataType] == 'object') {
			return this._dictionaries[dataType].exists(key);
		}
        else {
			return false;
		}
	},

	addData: function(dataType, key, value)
	{
		if (this._dictionaries[dataType].count >= this._dataTypes[dataType].cacheLimit) {
			var keys = this._dictionaries[dataType].keys();
			this._dictionaries[dataType].remove(keys[0]);
		}
		this._dictionaries[dataType].add(key, value);
	},

	replaceFromContactFromMessages: function(sAddedContactId, defEmail)
	{
		var dict = this._dictionaries[TYPE_MESSAGE];
		var keys = dict.keys();
		for (var i = 0; i < keys.length; i++) {
			var msg = dict.getVal(keys[i]);
			if (msg.fromAddr.indexOf(defEmail) != -1) {
				msg.fromContact = {sContactId: sAddedContactId};
				dict.setVal(keys[i], msg);
			}
		}
	},

	removeFromContactFromMessages: function(sContactIdForRemove)
	{
		var
			dict = this._dictionaries[TYPE_MESSAGE],
			keys = dict.keys()
		;
		
		for (var i = 0; i < keys.length; i++) {
			var msg = dict.getVal(keys[i]);
			if (msg.fromContact.sContactId === sContactIdForRemove) {
				msg.fromContact = {sContactId: ''};
				dict.setVal(keys[i], msg);
			}
			if (msg.oVcf && msg.oVcf.sUid === sContactIdForRemove) {
				msg.oVcf.bExists = false;
				dict.setVal(keys[i], msg);
			}
		}
	},

	setMessageSpecialAttach: function(msgId, msgUid, idFolder, folderFullName, oSpecialAttach, sAttachType)
	{
		var dict = this._dictionaries[TYPE_MESSAGE];
		var keys = dict.keys();
		for (var i = 0; i < keys.length; i++) {
			var msg = dict.getVal(keys[i]);
			if (msg.isCorrectData(msgId, msgUid, idFolder, folderFullName)) {
				msg[sAttachType] = oSpecialAttach;
				dict.setVal(keys[i], msg);
				break;
			}
		}
	},

	setMessageSafety: function(msgId, msgUid, idFolder, folderFullName, safety, isAll)
	{
		var dict = this._dictionaries[TYPE_MESSAGE];
		var keys = dict.keys();
		for (var i = 0; i < keys.length; i++) {
			var msg = dict.getVal(keys[i]);
			if (isAll || msg.isCorrectData(msgId, msgUid, idFolder, folderFullName)) {
				msg.showPictures();
				msg.safety = safety;
				dict.setVal(keys[i], msg);
				if (!isAll) {
					break;
				}
			}
		}
	},

	setSenderSafety: function(fromAddr, safety)
	{
		var fromParts = GetEmailParts(HtmlDecode(fromAddr));
		var fromEmail = fromParts.email;
		var dict = this._dictionaries[TYPE_MESSAGE];
		var keys = dict.keys();
		for (var i = 0; i < keys.length; i++) {
			var msg = dict.getVal(keys[i]);
			var fParts = GetEmailParts(HtmlDecode(msg.fromAddr));
			if (fromEmail == fParts.email) {
				msg.showPictures();
				msg.safety = safety;
				dict.setVal(keys[i], msg);
			}
		}
	},

	setMessagesCount: function(idFolder, folderFullName, count, countNew)
	{
		var dict = this._dictionaries[TYPE_MESSAGE_LIST];
		var keys = dict.keys();
		for (var i = 0; i < keys.length; i++) {
			var messages = dict.getVal(keys[i]);
			if (messages.idFolder == idFolder && messages.folderFullName == folderFullName && messages.lookFor.length == 0) {
				if (messages.messagesCount != count) {
					dict.remove(keys[i]);
				}
                else {
					messages.newMsgsCount = countNew;
					dict.setVal(keys[i], messages);
				}
			}
		}
	},

	setFolderMessagesCount: function(idFolder, folderFullName, count, countNew, idAcct)
	{
		var dict = this._dictionaries[TYPE_FOLDER_LIST];
		var keys = dict.keys();
		for (var i = 0; i < keys.length; i++) {
			var folderList = dict.getVal(keys[i]);
			if (folderList.idAcct == idAcct) {
				folderList.setMessagesCount(idFolder, folderFullName, count, countNew);
			}
		}
	},

	clearMessageList: function(idFolder, folderFullName, byFlag, bUnseenFilter)
	{
		var dict = this._dictionaries[TYPE_MESSAGE_LIST];
		if (idFolder == '-1' && folderFullName == '') {
			dict.removeAll();
		}
		else {
			var keys = dict.keys();
			for (var i = 0; i < keys.length; i++) {
				var messages = dict.getVal(keys[i]);
				if (byFlag && messages.sortField != SORT_FIELD_FLAG) {
					continue;
				}
				var bThisFolder = (messages.idFolder == idFolder && messages.folderFullName == folderFullName ||
					messages.idFolder == '-1' && messages.folderFullName == '');
				if (bThisFolder) {
					if (bUnseenFilter) {
						if (messages.iFilter === MESSAGE_LIST_FILTER_UNSEEN) {
							dict.remove(keys[i]);
						}
					}
					else {
						dict.remove(keys[i]);
					}
				}
			}
		}
	},

	clearAllMessages: function()
	{
		this._dictionaries[TYPE_MESSAGE_LIST].removeAll();
		this._dictionaries[TYPE_MESSAGE].removeAll();
	},

	clearMessage: function(id, uid, idFolder, folderFullName, charset)
	{
		var deleted = false;
		var dict = this._dictionaries[TYPE_MESSAGE];
		var keys = dict.keys();
		for (var i = 0; i < keys.length; i++) {
			var msg = dict.getVal(keys[i]);
			if (msg.isCorrectData(id, uid, idFolder, folderFullName) && msg.charset != charset) {
				dict.remove(keys[i]);
				deleted = true;
			}
		}
		return deleted;
	},

	renameRemoveGroupInContacts: function (sGroupId, groupName, groupContacts)
	{
		var dict = this._dictionaries[TYPE_CONTACT];
		var keys = dict.keys();
		for (var i = 0; i < keys.length; i++) {
			var oldContact = dict.getVal(keys[i]);
			var newContact = null;
			for (var j = 0; j < groupContacts; j++) {
				if (oldContact.sContactId == groupContacts[j].sContactId) {
					newContact = groupContacts[j];
					break;
				}
			}
			var groupsCount = oldContact.groups.length;
			for (var groupIndex = 0; groupIndex < groupsCount; groupIndex++) {
				if (oldContact.groups[groupIndex].sGroupId === sGroupId) {
					if (newContact != null) {
						oldContact.groups[groupIndex].name = groupName;
					}
					else {
						var groups1 = oldContact.groups.slice(0, groupIndex);
						var groups2 = oldContact.groups.slice(groupIndex + 1, groupsCount);
						oldContact.groups = groups1.concat(groups2);
					}
					break;
				}
			}
			dict.setVal(keys[i], oldContact);
		}
	},

	removeGroup: function (sGroupId)
	{
		var
			oDict = this._dictionaries[TYPE_GROUP],
			aKeys = oDict.keys(),
			iIndex = 0,
			iLen = aKeys.length,
			oGroup = null
		;

		for (; iIndex < iLen; iIndex++) {
			oGroup = oDict.getVal(aKeys[iIndex]);
			if (oGroup.sGroupId === sGroupId) {
				oDict.remove(aKeys[iIndex]);
			}
		}

		this.removeFromGroupInContacts(sGroupId);
	},

	removeFromGroupInContacts: function (sGroupId)
	{
		var dict = this._dictionaries[TYPE_CONTACT];
		var keys = dict.keys();
		for (var i = 0; i < keys.length; i++) {
			var oldContact = dict.getVal(keys[i]);
			var groupsCount = oldContact.groups.length;
			for (var groupIndex = 0; groupIndex < groupsCount; groupIndex++) {
				if (oldContact.groups[groupIndex].sGroupId === sGroupId) {
					var groups1 = oldContact.groups.slice(0, groupIndex);
					var groups2 = oldContact.groups.slice(groupIndex + 1, groupsCount);
					oldContact.groups = groups1.concat(groups2);
					break;
				}
			}
			dict.setVal(keys[i], oldContact);
		}
	},

	removeContact: function (sContactId)
	{
		var
			oDict = this._dictionaries[TYPE_CONTACT],
			aKeys = oDict.keys(),
			iIndex = 0,
			iLen = aKeys.length,
			oContact = null
		;

		for (; iIndex < iLen; iIndex++) {
			oContact = oDict.getVal(aKeys[iIndex]);
			if (oContact.sContactId === sContactId) {
				oDict.remove(aKeys[iIndex]);
			}
		}

		this.removeFromContactFromMessages(sContactId);
		this.removeFromContactInGroups(sContactId);
	},

	removeFromContactInGroups: function (sContactId)
	{
		var dict = this._dictionaries[TYPE_GROUP];
		var keys = dict.keys();
		for (var i = 0; i < keys.length; i++) {
			var oldGroup = dict.getVal(keys[i]);
			var contactCount = oldGroup.contacts.length;
			for (var contactIndex = 0; contactIndex < contactCount; contactIndex++) {
				if (oldGroup.contacts[contactIndex].sContactId === sContactId) {
					var contacts1 = oldGroup.contacts.slice(0, contactIndex);
					var contacts2 = oldGroup.contacts.slice(contactIndex + 1, contactCount);
					oldGroup.contacts = contacts1.concat(contacts2);
					break;
				}
			}
			dict.setVal(keys[i], oldGroup);
		}
	},

	addGroupToContacts: function (sGroupId, groupName, groupContacts)
	{
		var dict = this._dictionaries[TYPE_CONTACT];
		var keys = dict.keys();
		for (var i = 0; i < keys.length; i++) {
			var oldContact = dict.getVal(keys[i]);
			var newContact = null;
			for (var j = 0; j < groupContacts.length; j++) {
				if (oldContact.sContactId === groupContacts[j].sContactId) {
					newContact = groupContacts[j];
					break;
				}
			}
			if (newContact != null) {
				var groupsCount = oldContact.groups.length;
				var finded = false;
				for (var groupIndex = 0; groupIndex < groupsCount; groupIndex++) {
					if (oldContact.groups[groupIndex].sGroupId === sGroupId) {
						finded = true;
						break;
					}
				}
				if (!finded) {
					oldContact.groups.push({sGroupId: sGroupId, name: groupName});
				}
			}
			dict.setVal(keys[i], oldContact);
		}
	},

	clearAllContactsGroupsList: function ()
	{
		this._dictionaries[TYPE_CONTACTS].removeAll();
		this._dictionaries[TYPE_GLOBAL_CONTACTS].removeAll();
	},

	addContactsToGroup: function (key, groupContacts)
	{
		var dict = this._dictionaries[TYPE_GROUP];
		var group = dict.getVal(key);
		if (typeof(group) == 'undefined') return;
		for (var j = 0; j < groupContacts.length; j++) {
			var contactsCount = group.contacts.length;
			var finded = false;
			for (var contactIndex = 0; contactIndex < contactsCount; contactIndex++) {
				if (group.contacts[contactIndex].sContactId === groupContacts[j].sContactId) {
					finded = true;
					break;
				}
			}
			if (!finded) {
				group.contacts.push(groupContacts[j]);
			}
		}
		dict.setVal(key, group);
	},

	addRemoveRenameContactInGroups: function (contact)
	{
		var dict = this._dictionaries[TYPE_GROUP];
		var keys = dict.keys();
		for (var i = 0; i < keys.length; i++) {
			var group = dict.getVal(keys[i]);

			var contactHasGroup = false;
			var groupCount = contact.groups.length;
			for (var groupIndex = 0; groupIndex < groupCount; groupIndex++) {
				if (group.sGroupId == contact.groups[groupIndex].sGroupId) {
					contactHasGroup = true;
					break;
				}
			}

			var contactsCount = group.contacts.length;
			var finded = false;
			for (var contactIndex = 0; contactIndex < contactsCount; contactIndex++) {
				if (group.contacts[contactIndex].sContactId === contact.sContactId) {
					if (contactHasGroup) {
						group.contacts[contactIndex] = {sContactId: contact.sContactId, name: contact.name, email: contact.email};
					}
					else {
						var contacts1 = group.contacts.slice(0, contactIndex);
						var contacts2 = group.contacts.slice(contactIndex + 1, contactsCount);
						group.contacts = contacts1.concat(contacts2);
					}
					finded = true;
					break;
				}
			}
			if (!finded && contactHasGroup) {
				group.contacts.push({sContactId: contact.sContactId, name: contact.name, email: contact.email});
			}

			dict.setVal(keys[i], group);
		}
	},

	setData: function (type, messageParams, field, value, isAllMess)
	{
		var idFolder = messageParams[1];
		var folderFullName = messageParams[2];
		var dict = this._dictionaries[type];
		var keys = dict.keys();
		for (var i = 0; i < keys.length; i++) {
			var messages = dict.getVal(keys[i]);
			if (messages.idFolder == idFolder && messages.folderFullName == folderFullName ||
				(messages.idFolder == '-1' && messages.folderFullName == '' && !isAllMess)) {
				var idArray = messageParams[0];
				for (var j in messages.list) {
					var data = messages.list[j];
					if (typeof(data) === 'function') continue;
					if (data) {
						if (isAllMess) {
							data[field] = value;
							messages.list[j] = data;
						}
						else {
							for (var k = 0; k < idArray.length; k++) {
								if (data.isCorrectData(idArray[k].id, idArray[k].uid, idFolder, folderFullName)) {
									data[field] = value;
									messages.list[j] = data;
								}
							}
						}
					}
				}
				dict.setVal(keys[i], messages);
			}
		}
	},

	getData: function(dataType, key)
	{
		return this._dictionaries[dataType].getVal(key);
	},

	replaceData: function(dataType, key, value)
	{
		this._dictionaries[dataType].setVal(key, value);
	},

	removeData: function(dataType, key)
	{
		this._dictionaries[dataType].remove(key);
	}
};

function CHistoryStorage(historyStorageObjectName)
{
	this._historyStorageObjectName = historyStorageObjectName;
	this._maxLimitSteps = 50;
	this._dictionary = new CDictionary();
	this._inStep = false;
	this._queue = [];
	this._keysInStep = [];
	this._prevKey = '';

	this._historyKey = null;
	this._historyObjectName = null;
	this._form = null;

	this._initialize();
}

CHistoryStorage.prototype = {
	_initialize: function()
	{
		CreateChild(document.body, 'iframe name="HistoryStorageIframe"', [['id', 'HistoryStorageIframe'], ['src', EmptyHtmlUrl], ['class', 'wm_hide']]);
		var frm = CreateChild(document.body, 'form', [['action', HistoryStorageUrl], ['target', 'HistoryStorageIframe'], ['method', 'post'], ['id', 'HistoryForm'], ['name', 'HistoryForm'], ['class', 'wm_hide']]);
		this._historyKey = CreateChild(frm, 'input', [['type', 'text'], ['name', 'HistoryKey']]);
		this._historyObjectName = CreateChild(frm, 'input', [['type', 'text'], ['name', 'HistoryStorageObjectName']]);
		this._form = frm;
	},

	ProcessHistory: function (historyKey) {
		this._inStep = false;
		if (this._keysInStep[historyKey]) {
			delete this._keysInStep[historyKey];
		}
		else {
			this.restoreFromHistory(historyKey);
		}
	},

	restoreFromHistory: function (historyKey) {
		if (this._dictionary.exists(historyKey)) {
			var historyObject = this._dictionary.getVal(historyKey);
			eval('window.' + historyObject.functionName + '(historyObject.args)');
		}

		if (this._queue.length > 0) {
			var key = this._queue.shift();
			if (this._dictionary.exists(key)) {
				this._doStep(key);
			}
		}
	},

	addStep: function(objectData){

		var newKey = this._generateHistoryKey();
		if (this._dictionary.count >= this._maxLimitSteps) {
			//remove first step because steps count is more then limit
			var keys = this._dictionary.keys();
			this._dictionary.remove(keys[0]);
		}
		//add new step
		this._dictionary.add(newKey, objectData);

		if (this._inStep) {
			//move step key to queue because previouse step still not finished
			this._queue.push(newKey);
		}
        else {
			//realize step
			this._doStep(newKey);
		}
	},

	_generateHistoryKey: function () {
		var key = String(new Date()) + ' ' + Math.random();
		return key.replace(/[\s\+\-\.]/g, '_').replace(/[^a-zA-Z0-9_]/g, '');
	},

	_doStep: function (newKey) {
		if (!Browser.opera) {
			if (this._keysInStep[this._prevKey]) {
				delete this._keysInStep[this._prevKey];
			}
			this._historyKey.value = newKey;
			this._historyObjectName.value = this._historyStorageObjectName;
			this._form.action = HistoryStorageUrl + '?param=' + Math.random();
			this._form.submit();
			this._keysInStep[newKey] = true;
			this._prevKey = newKey;
		}
		this.restoreFromHistory(newKey);
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}