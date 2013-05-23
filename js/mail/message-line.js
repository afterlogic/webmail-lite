/*
 * Classes:
 *  CMessageListTableController(clickHandler)
 *  CMessageLine(msg, tr, doFlag)
 */

function CMessageListTableController(clickHandler)
{
	this.type = TYPE_MESSAGE;
	this._clickHandler = clickHandler;
	this._dblClickHandler = DblClickHandler;
	this._doFlag = true;
	this.resizeHandler = 'ResizeMessagesTab';
	this.listContanerClass = 'wm_inbox';
	
	this.SetDoFlag = function (doFlag)
	{
		this._doFlag = doFlag;
	};
	
	this.CreateLine = function (msgHeaders, tr, screenId)
	{
		tr.id = msgHeaders.getIdForList(screenId);
		return new CMessageLine(msgHeaders, tr, this._doFlag);
	};
	
	this.ClickLine = function (id, obj)
	{
		if (obj.LastClickLineId != id) {
			obj.LastClickLineId = id;
			if (null != obj._timer) {
				clearTimeout(obj._timer);
			}
			obj._timer = setTimeout(this._clickHandler + "('" + EncodeStringForEval(id) + "')", 200);
		}
	};
	
	this.del = function ()
	{
		RequestMessagesOperationHandler(TOOLBAR_DELETE, [], []);
	};
	
	this.DblClickLine = function (tr, obj)
	{
		if (null != obj._dragNDrop) {
			obj._dragNDrop.endDrag();
		}
		if (null != obj._timer) {
			clearTimeout(obj._timer);
		}
		this._dblClickHandler.call(tr);
	};

	this.SetEventsHandlers = function (obj, tr)
	{
		var objController = this;
		$(tr).bind('mousedown', function (e) {
			e.preventDefault();
			// right button click
			if (e.button == 2) {
				return;
			}
			if (null != obj._dragNDrop) {
				obj._dragNDrop.RequestDrag(e, this);
			}
			var clickElem = (Browser.mozilla) ? e.target : e.srcElement;
			var clickTagName = clickElem ? clickElem.tagName : 'NOTHING';
			// wait for flag message
			if (objController._doFlag && clickTagName == 'DIV' && clickElem.id.substr(0,8) == 'flag_img') {
				return;
			}
			// wait for check message with ctrl key
			if (clickTagName == 'INPUT') {
				return;
			}
			if (e.ctrlKey) {
				return;
			}
			// wait for check message with shift key
			if (e.shiftKey) {
				return;
			}
			// wait for multidrag
			if (!obj.oSelection.SingleForDrag(this.id)) {
				return;
			}
			// view message
			var tdElem = clickElem;
			while (tdElem && tdElem.tagName != 'TD') {
				tdElem = tdElem.parentNode;
			}
			if (tdElem.name != 'not_view') {
				obj.oSelection.CheckLine(this.id);
				objController.ClickLine(this.id, obj);
			}
		});
		tr.onclick = function(e) {
			if (null != obj._dragNDrop) {
				obj._dragNDrop.endDrag();
			}
			e = e ? e : window.event;
			var clickElem = (Browser.mozilla) ? e.target : e.srcElement;
			var clickTagName = (clickElem) ? clickElem.tagName : 'NOTHING';
			// flag message
			if (objController._doFlag && clickTagName == 'DIV' && clickElem.id.substr(0,8) == 'flag_img') {
				obj.oSelection.FlagLine(this.id);
			}
			// check message with ctrl key
			else if (clickTagName == 'INPUT' || e.ctrlKey) {
				obj.oSelection.CheckCtrlLine(this.id);
			}
			// check message with shift key
			else if (e.shiftKey) {
				obj.oSelection.CheckShiftLine(this.id);
			}
			// view message
			else {
				var tdElem = clickElem;
				while (tdElem && tdElem.tagName != 'TD') {
					tdElem = tdElem.parentNode;
				}
				if (tdElem.name != 'not_view') {
					obj.oSelection.CheckLine(this.id);
					objController.ClickLine(this.id, obj);
				}
			}
		};
		tr.ondblclick = function (e) {
			var clickElem, clickTagName;
			e = e ? e : window.event;
			clickElem = (Browser.mozilla) ? e.target : e.srcElement;
			clickTagName = (clickElem) ? clickElem.tagName : 'NOTHING';
			if (clickTagName != 'INPUT') {
				objController.DblClickLine(this, obj);
			}
		};
		$(tr).bind('contextmenu', function (event) {
			OpenContextMenuHandler(this.id, objController, obj, event.pageX, event.pageY);
			event.preventDefault();
		});
	};
}

CMessageListTableController.prototype = {
	setOnScroll: function (eCont, oTable)
	{
		eCont.onscroll = function ()
		{
			if (!oTable.bFilled) {
				return;
			}
			var iContHeight = eCont.offsetHeight;
			var iScrollHeight = eCont.scrollHeight;
			var iScrollTop = eCont.scrollTop;
			var iScrollBottom = iScrollHeight - iContHeight - iScrollTop;
			var iPercentBottom = (iScrollBottom / iScrollHeight) * 100;
			if (iPercentBottom < 10) {
				if (WebMail.requestMessageListNextPage(false)) {
					oTable.addMessageToList(Lang.InfoMessagesLoad);
					oTable.bFilled = false;
				}
			}
		}
	},

	onFill: function (eCont, oTable)
	{
		if (eCont.offsetHeight === eCont.scrollHeight) {
			if (WebMail.requestMessageListNextPage(false)) {
				oTable.addMessageToList(Lang.InfoMessagesLoad);
				oTable.bFilled = false;
			}
		}
	}
}

function CMessageLine(msg, tr, doFlag)
{
	DontSelectContent(tr);
	
	tr.size = msg.size;
	this.fromDisplay = null;

	this.Hidden = false;
	this._className = '';
	this.flagged = msg.flagged;
	this.replied = msg.replied;
	this.forwarded = msg.forwarded;
	this.deleted = msg.deleted;
	this.read = msg.read;
	this.Checked = false;
	this.gray = msg.gray;
	this.noReply = msg.noReply;
	this.noReplyAll = msg.noReplyAll;
	this.noForward = msg.noForward;
	this.sensivity = msg.sensivity;
	this.isVoice = msg.isVoice;
	this._duration = msg.duration;
	this.charset = msg.charset;

	this.MsgFromAddr = msg.fromAddr;
	this.MsgToAddr = msg.toAddr;
	this.MsgDate = msg.date;
	this.MsgFullDate = msg.fullDate;
	this.MsgSize = msg.size;
	this.MsgSubject = msg.subject;
	this.MsgId = msg.id;
	this.MsgUid = msg.uid;
	this.MsgFolderId = msg.idFolder;
	this.MsgFolderFullName = msg.folderFullName;

	this.Node = tr;
	this.id = tr.id;
	this.SetClassName();
	this.ApplyClassName();
	
	this.fCheck = new CCheckBoxCell();
	
	var content = '';
	if (msg.hasAttachments) {
		content = 'wm_inbox_lines_attachment';
	}
	if (msg.isVoice) {
		content = 'wm_inbox_lines_voice';
	}
	this.fHasAttachments = new CImageCell('', '', content);

	switch (msg.importance) {
		case PRIORITY_HIGH:
			content = 'wm_inbox_lines_priority_high';
			break;
		case PRIORITY_LOW:
			content = 'wm_inbox_lines_priority_low';
			break;
		default:
			content = '';
			break;
	}
	this.fImportance = new CImageCell('', '', content);

	content = (msg.sensivity) ? 'wm_inbox_lines_sensivity' : '';
	this.fSensivity = new CImageCell('', '', content);

	var className = doFlag ? 'wm_control_img' : '';
	content = this.flagged ? 'wm_inbox_lines_flag' : 'wm_inbox_lines_unflag';
	this.fFlagged = new CImageCell(className, 'flag_img' + Math.random(), content);

	if (WebMail.listScreenId == SCREEN_MESSAGE_LIST_CENTRAL_PANE) {
		this.fFromAddr = new CTextCell(this._getFromToContent(true));
	}
	else {
		this.fFromAddr = new CTextCell(this.MsgFromAddr);
	}
	if (WebMail.listScreenId == SCREEN_MESSAGE_LIST_CENTRAL_PANE) {
		this.fToAddr = new CTextCell(this._getFromToContent(false));
	}
	else {
		this.fToAddr = new CTextCell(this.MsgToAddr);
	}
	this.fDate = new CTextCell(this.MsgDate, this.MsgFullDate);
	this.fSize = new CTextCell(GetFriendlySize(this.MsgSize));
	this.fSubject = new CTextCell(this._getSubjectContent());
}

CMessageLine.prototype = 
{
	getMessage: function ()
	{
		var oMsg = new CMessage();
		oMsg.id = this.MsgId;
		oMsg.uid = this.MsgUid;
		oMsg.idFolder = this.MsgFolderId;
		oMsg.folderFullName = this.MsgFolderFullName;
		oMsg.charset = this.charset;
		oMsg.size = this.MsgSize;
		oMsg.noReply = this.noReply;
		oMsg.noReplyAll = this.noReply;
		oMsg.noForward = this.noReply;
		oMsg.sensivity = this.sensivity;
		return oMsg;
	},

	_getFromToContent: function (useFrom)
	{
		var content = '';
		content += '<span class="wm_inbox_message_size">' + GetFriendlySize(this.MsgSize) + '</span>';
		content += '<span class="wm_inbox_message_date" title="' + this.MsgFullDate + '">' + this.MsgDate + '</span>';
		content += '<div class="wm_inbox_subject">' + this._getSubjectContent() + '</div>';
		content +=  (useFrom) 
			? '<div class="wm_inbox_from">' + this.MsgFromAddr + '</div>'
			: '<div class="wm_inbox_from">' + this.MsgToAddr + '</div>';
		return content;
	},
	
    _getSubjectContent: function ()
    {
		var subj = (this.MsgSubject == '')
			? '<span class="wm_no_subject">' + Lang.MessageNoSubject + '</span>'
			: this.MsgSubject;
		if (this.isVoice) {
			subj = Lang.VoiceMessageSubj;// + ' [' + this._duration + ']';
		}
	    return this._getReplyForwardContent() + subj;
    },
	
	_getReplyForwardContent: function ()
    {
	    if (this.replied && this.forwarded) {
	        return '<span class="wm_inbox_lines_rpl_fwd" title="' + Lang.RepliedForwardedMessageTitle 
				+ '"></span>';
	    }
	    if (this.replied) {
	        return '<span class="wm_inbox_lines_replied" title="' + Lang.RepliedMessageTitle 
				+ '"></span>';
	    }
	    if (this.forwarded) {
	        return '<span class="wm_inbox_lines_forwarded" title="' + Lang.ForwardedMessageTitle 
				+ '"></span>';
	    }
		return '';
    },
    
    view: function (viewed)
    {
		this._viewed = viewed;
		this.ApplyClassName();
    },
    
	Check: function()
	{
		this.Checked = true;
		this.fCheck.Node.checked = true;
		this.ApplyClassName();
	},

	Uncheck: function()
	{
		this.Checked = false;
		this.fCheck.Node.checked = false;
		this.ApplyClassName();
	},
	
	Flag: function ()
	{
		RequestMessagesOperationHandler(TOOLBAR_FLAG, [this.id], [this.MsgSize]);
	},
	
	Unflag: function ()
	{
		RequestMessagesOperationHandler(TOOLBAR_UNFLAG, [this.id], [this.MsgSize]);
	},

	SetFromDisplay: function (fromDisplay)
	{
		this.fromDisplay = fromDisplay;
	},

	hide: function ()
	{
		this.Hidden = true;
		var startHeight = this.Node.offsetHeight;
		var nodeChilds = this.Node.getElementsByTagName('TD');
		var td;
		for (var i = 0; i < nodeChilds.length; i++) {
			td = nodeChilds[i];
			td.style.height = '0px';
			td.style.paddingTop = '0px';
			td.style.paddingBottom = '0px';
			CleanNode(td);
		}
		td.style.height = startHeight + 'px';
		var createDecreaseTdFunc = function (td, height) {
			return function () {
				td.style.height = height + 'px';
			};
		};
		var stepsCount = 5;
		var heightStep = Math.round(startHeight / stepsCount);
		var timeStep = Math.round((200 / stepsCount) / heightStep);
		for (i = heightStep; i <= startHeight; i += heightStep) {
			var height = startHeight - i;
			if (height < heightStep) {
				var tr = this.Node;
				setTimeout(function () {tr.className = 'wm_hide';}, timeStep * i);
			}
			else {
				setTimeout(createDecreaseTdFunc(td, height), timeStep * i);
			}
		}
		this.Checked = false;
		this.fCheck.Node.checked = false;
	},
	
	SetClassName: function ()
	{
		if (this.deleted) {
			this._className = 'wm_inbox_deleted_item';
		}
		else if (this.read) {
			this._className = 'wm_inbox_read_item';
		}
		else {
			this._className = 'wm_inbox_item';
		}
	},
	
	ApplyClassName: function ()
	{
		if (this.Hidden) {
			this.Node.className = 'wm_hide';
		}
		else {
			var className = this._className;
			if (this._viewed) {
				className += '_view';
			}
			else if (this.Checked) {
				className += '_select';
			}
			else if (this.gray) {
				className += ' wm_inbox_grey_item';
			}
			this.Node.className = className;
		}
	},
	
	SetContainer: function (field, container)
	{
		if (WebMail.listScreenId == SCREEN_MESSAGE_LIST_CENTRAL_PANE) {
			field = 'f' + field;
		}
		if (field == 'fCheck' || field == 'fHasAttachments') {
			container.name = 'not_view';
		}
		this[field].SetContainer(container);
	},

	ApplyFlagImg: function ()
	{
		var content = this.flagged ? 'wm_inbox_lines_flag' : 'wm_inbox_lines_unflag';
		this.fFlagged.SetContent(content);
	},

	ApplyFromSubj: function ()
	{
		if (WebMail.listScreenId == SCREEN_MESSAGE_LIST_CENTRAL_PANE) {
			this.fFromAddr.SetContent(this._getFromToContent());
		}
		else {
			this.fFromAddr.SetContent(this.MsgFromAddr);
			this.fSubject.SetContent(this._getSubjectContent());
		}
	},
	
	ApplyRepliedForwarded: function ()
	{
		if (WebMail.listScreenId == SCREEN_MESSAGE_LIST_CENTRAL_PANE) {
			this.fFromAddr.SetContent(this._getFromToContent());
		}
		else {
			this.fSubject.SetContent(this._getSubjectContent());
		}
	},
	
	SetParams: function (field, value)
	{
		var readed = 0;
		switch (field) {
			case 'read':
				if (this.read == false && value == true) readed = 1;
				if (this.read == true && value == false) readed = -1;
				this.read = value;
				this.SetClassName();
				this.ApplyClassName();
				break;
			case 'deleted':
				this.deleted = value;
				this.SetClassName();
				this.ApplyClassName();
				break;
			case 'flagged':
				this.flagged = value;
				this.ApplyFlagImg();
				break;
			case 'replied':
				this.replied = value;
				this.ApplyRepliedForwarded();
				break;
			case 'forwarded':
				this.forwarded = value;
				this.ApplyRepliedForwarded();
				break;
			case 'gray':
				this.gray = value;
				this.ApplyClassName();
				break;
		}//switch field
		return readed;
	},
	
	isCorrectIdData: function (msg)
	{
		return (this.MsgId == msg.id && this.MsgUid == msg.uid && this.MsgFolderId == msg.idFolder &&
					this.MsgFolderFullName == msg.folderFullName);
	},
	
	ChangeFromSubjData: function (msg, newId)
	{
		if (newId) {
			this.id = newId;
			this.Node.id = newId;
		}
		this.Node.size = msg.size;
		this.MsgFromAddr = msg.fromAddr;
		this.MsgSubject = msg.subject;
		this.ApplyFromSubj();
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
