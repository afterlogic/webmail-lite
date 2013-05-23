/*
 * Classes:
 *	CMessageReplyPane(eParent, bInNewWindow)
 */

function CMessageReplyPane(eParent, bInNewWindow)
{
	this._bBuilded = false;
	this._bInNewWindow = bInNewWindow;
	this._bSaving = false;
	this._bSending = false;
	this._bShown = false;

	this._eMainContainer = null;
	this._eParent = eParent;
	this._eSaveButton = null;
	this._eTextDisplay = null;

	this._iMaxHeight = 20;
	this._iMaxRows = 0;

	this._oMsg = null;
	this._oWaitParams = null;
	
	this._sTextValue = '';
}

CMessageReplyPane.prototype = {
	show: function (oMsg)
	{
		if (oMsg.noReplyAll) {
			return;
		}
		if (!this._bBuilded) {
			this._build();
		}
		this._oMsg = oMsg;
		if (this._oWaitParams != null) {
			var oWaitMsg = this._oWaitParams.oMsg;
			if (oMsg.isEqual(oWaitMsg)) {
				this._sendMessage(this._oWaitParams.iMode);
				this._oWaitParams = null;
			}
		}
		else if (!this._bShown) {
			this._eMainContainer.className = 'wm_reply_pane';
			this._eMainContainer.style.height = 'auto';
			if (WebMail.allowSaveMessageToDrafts()) {
				this._eSaveButton.className = 'wm_save_button wm_control';
			}
			else {
				this._eSaveButton.className = 'wm_hide';
			}
			this._clear();
		}
		this._bShown = true;
	},

	hide: function ()
	{
		if (!this._bBuilded) return;
		this._eMainContainer.className = 'wm_hide';
		this._bShown = false;
	},
	
	setMessageId: function (iMsgId, sMsgUid)
	{
		if (!this._bBuilded) return;
		
		if (this._bInNewWindow) {
			if (window.opener) {
				window.opener.IncMessageCountInDrafts(1);
			}
		}
		else {
			IncMessageCountInDrafts(1);
		}
		
		this.resetFlags(SAVE_MODE);
	},
	
	resetFlags: function (iMode)
	{
		if (!this._bBuilded) return;
		switch (iMode) {
			case SEND_MODE:
				this._bSending = false;
				break;
			case SAVE_MODE:
				this._bSaving = false;
				break;
			default:
				this._bSaving = false;
				this._bSending = false;
		}
	},
	
	resizeWidth: function (iWidth)
	{
		if (!this._bBuilded) return;
		if (this._eTextDisplay.offsetWidth == 0 || this._eTextDisplay.cols == 0) return;
		
		iWidth = iWidth - 22;
		var iSymbolWidth = this._eTextDisplay.offsetWidth / this._eTextDisplay.cols;
		var iNewCols = Math.round(iWidth / iSymbolWidth);

		var iStep = this._getStep(iNewCols, iWidth, iSymbolWidth);
		var bPositiveStep = (iStep > 0);
		var iCounter = 0;
		while (bPositiveStep == (iStep > 0) && iStep != 0 && iCounter < 10) {
			iNewCols -= iStep;
			iStep = this._getStep(iNewCols, iWidth, iSymbolWidth);
			iCounter++;
		}
		if (this._eTextDisplay.offsetWidth > iWidth) {
			this._eTextDisplay.cols = iNewCols - 1;
		}
	},
	
	setMaxHeight: function (iMaxHeight)
	{
		if (!this._bBuilded) return;
		iMaxHeight = iMaxHeight - 50;
		if (this._iMaxHeight != iMaxHeight) {
			this._iMaxHeight = iMaxHeight;
			this._iMaxRows = 0;
		}
	},
	
	resizeHeight: function (sToAdd)
	{
		if (!this._bBuilded) return;
		
		if (typeof(strToAdd) != 'string') sToAdd = '';
		var sValue = this._eTextDisplay.value + sToAdd;
		
		var aParagraphs = sValue.replace(/\r\n/g, '\n').replace(/\r/g, '\n').split('\n');
		var iLinesCount = aParagraphs.length;
		
		for (var i = 0; i < aParagraphs.length; i++) {
			var aWords = aParagraphs[i].split(' ');
			var k = 0;
			for (var j = 0; j < aWords.length; j++) {
				k += aWords[j].length + 1;
				if (k > this._eTextDisplay.cols) {
					k = 0;
					iLinesCount++;
				}
			}
		}

		var iRowsCount = iLinesCount + 1;
		if (this._iMaxRows != 0 && iRowsCount > this._iMaxRows) {
			iRowsCount = this._iMaxRows;
		}
		if (this._eTextDisplay.rows != iRowsCount) {
			this._eTextDisplay.rows = iRowsCount;
			while (iRowsCount > 2 && this._eTextDisplay.offsetHeight > this._iMaxHeight) {
				iRowsCount--;
				this._eTextDisplay.rows = iRowsCount;
				this._iMaxRows = iRowsCount;
			}
			WebMail.resizeBody(RESIZE_MODE_MSG_HEIGHT);
		}
	},

	getHeight: function ()
	{
		if (!this._bBuilded) return 0;
		return this._eMainContainer.offsetHeight;
	},
	
	setStyle: function (bFocused)
	{
		var sValue = this._eTextDisplay.value;
		var bEmptyValue = (sValue == '');
		var bQuickValue = (sValue == Lang.QuickReply && this._sTextValue == '');
		if (!bFocused && (bEmptyValue || bQuickValue)) {
			this._eTextDisplay.className = 'wm_reply_text wm_blured_text';
			this._eTextDisplay.value = Lang.QuickReply;
			this._sTextValue = '';
		}
		else {
			this._eTextDisplay.className = 'wm_reply_text wm_focused_text ';
			if (bQuickValue) {
				this._eTextDisplay.value = '';
			}
			else {
				this._sTextValue = this._eTextDisplay.value;
			}
		}
	},
	
	switchToFullForm: function (iReplyAction)
	{
		WebMail.replyMessageClick(iReplyAction, this._oMsg, this._getValue());
	},
	
	sendOrRequestForSend: function (iMode)
	{
		if (this._oMsg == null) return;
		
		switch (iMode) {
		    case SEND_MODE:
				if (this._bSending) return;
			    this._bSending = true;
			    break;
			case SAVE_MODE:
				if (this._bSaving) return;
				if (!WebMail.allowSaveMessageToDrafts()) return;
			    this._bSaving = true;
			    break;
			default:
				return;
		}
		
		if (this._oMsg.isReplyHtml || this._oMsg.isReplyPlain) {
			this._sendMessage(iMode);
		}
		else {
			var sInfo = (iMode === SEND_MODE) ? Lang.Sending : Lang.Saving;
			WebMail.showInfo(sInfo);
			this.requestForSend(iMode);
		}
	},

	requestForSend: function (iMode) {
		var oWaitMsg = (this._oWaitParams !== null) ? this._oWaitParams.oMsg : null;
		if (oWaitMsg !== null && this._oMsg.isEqual(oWaitMsg)) {
			this._oWaitParams.iMode = iMode;
		}
		else if (!this._oMsg.isReplyHtml && !this._oMsg.isReplyPlain) {
			this._oWaitParams = { iMode: iMode, oMsg: this._oMsg };
			WebMail.DataSource.needInfo = false;
			WebMail.requestMessageReplyPart(this._oMsg, false);
		}
	},

	slideAndShow: function (bClear)
	{
		if (this._bBuilded && this._eMainContainer.className === 'wm_hide') {
			if (bClear) {
				this._clear();
			}
			this._eMainContainer.className = 'wm_reply_pane';
			Slider.slideIt('reply_pane', 'show');
		}
	},

	endSlide: function (sDir)
	{
		if (sDir == 'show') {
			this._eMainContainer.style.height = 'auto';
			PreCacheSentAndDraftsFolders(this._iMode === SEND_MODE);
		}
		else {
			this._eMainContainer.className = 'wm_hide';
			this.sendOrRequestForSend(this._iMode);
		}
	},

	slideAndHide: function (iMode)
	{
		this._iMode = iMode;
		Slider.slideIt('reply_pane', 'hide');
	},

	_getStep: function (iNewCols, iWidth, iSymbolWidth)
	{
		this._eTextDisplay.cols = iNewCols;
		var iNewWidth = this._eTextDisplay.offsetWidth;
		var iDif = iNewWidth - iWidth;
		var iStep = (iDif == 0) ? 0 : Math.round((iDif / 2) / iSymbolWidth);
		return iStep;
	},

	_getValue: function ()
	{
		var sValue = this._eTextDisplay.value;
		if (sValue == Lang.QuickReply) sValue = this._sTextValue;
		return sValue;
	},

	_sendMessage: function (iMode)
	{
		if (this._oMsg === null) return;
		if (iMode !== SEND_MODE && iMode !== SAVE_MODE) return;
		
		var oNewMsg = this._getReplyMessage(this._oMsg, TOOLBAR_REPLYALL, this._getValue());

		var sXml = oNewMsg.getInXml();
		switch (iMode) {
		    case SEND_MODE:
				if (this._bInNewWindow) {
					if (window.opener) {
						window.opener.WebMail.DataSource.cache.clearAllContactsGroupsList();
					}
				}
				else {
					WebMail.DataSource.cache.clearAllContactsGroupsList();
				}
		        RequestHandler('send', 'message', sXml);
			    break;
			case SAVE_MODE:
			    RequestHandler('save', 'message', sXml);
			    break;
		}

		if (this._bInNewWindow) {
			if (window.opener) {
				window.opener.MarkMsgAsRepliedHandler(oNewMsg);
			}
		}
		else {
			MarkMsgAsRepliedHandler(oNewMsg);
		}
	},
	
	_clear: function ()
	{
		this._sTextValue = '';
		this._eTextDisplay.value = '';
		this._eTextDisplay.rows = 2;
		this._eTextDisplay.blur();
		this.setStyle(false);
		this.resetFlags();
	},

	_build: function ()
	{
		this._eMainContainer = CreateChild(this._eParent, 'div', [['class', 'wm_reply_pane'],
			['id', 'reply_pane']]);
		
		var eTbl = CreateChild(this._eMainContainer, 'table');
		var eTr = eTbl.insertRow(0);
		var eTd = eTr.insertCell(0);
		eTd.colSpan = 2;
		this._eTextDisplay = CreateChild(eTd, 'textarea', [['cols', '50'], ['rows', '2']]);
		var obj = this;
		this._eTextDisplay.onfocus = function () {
			obj.setStyle(true);
			obj.requestForSend(-1);
		};
		this._eTextDisplay.onblur = function () {
			obj.setStyle(false);
		};
		this._eTextDisplay.onkeydown = function (ev)
		{
            var iKey = Keys.getCodeFromEvent(ev);
            if (iKey == Keys.enter) {
				obj.resizeHeight('\r\n');
            }
		};
		
		this._eTextDisplay.onkeyup = function (ev)
		{
            ev = ev ? ev : window.event;
            var iKey = Keys.getCodeFromEvent(ev);
            if (iKey != Keys.enter) {
				obj.resizeHeight();
			}
            if (iKey == Keys.enter && ev.ctrlKey) {
                obj.slideAndHide(SEND_MODE);
            }
		};
		
		eTr = eTbl.insertRow(1);
		eTd = eTr.insertCell(0);
		var fSend = function () { obj.slideAndHide(SEND_MODE); };
		var fSave = function () { obj.slideAndHide(SAVE_MODE); };
		ButtonsBuilder.addForQuickReply(eTd, 'SendMessage', fSend);
		this._eSaveButton = ButtonsBuilder.addForQuickReply(eTd, 'SaveMessage', fSave);

		eTd = eTr.insertCell(1);
		eTd.style.textAlign = 'right';
		var eLink = CreateChild(eTd, 'a', [['href', '#'], ['class', 'wm_reply_full_view_link']]);
		eLink.innerHTML = Lang.SwitchToFullForm;
		WebMail.langChanger.register('innerHTML', eLink, 'SwitchToFullForm', '');
		eLink.onclick = function () {
			obj.switchToFullForm(TOOLBAR_REPLYALL);
			return false;
		};
		this._bBuilded = true;
	}
};

CMessageReplyPane.prototype._getFromAddrByAcctId = CNewMessageScreen.prototype._getFromAddrByAcctId;
CMessageReplyPane.prototype._getReplyMessage = CNewMessageScreen.prototype._getReplyMessage;

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}