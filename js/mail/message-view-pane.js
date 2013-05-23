/*
 * Functions:
 *  CreateAttachViewClick(sLink)
 *  CreateAttachDownloadClick(sLink)
 * Classes:
 *  CAttachmentsPane(eParent)
 *  CMessageViewPane(bShowAttachments)
 *  CVoiceMessageViewPane(eContainer)
 * Functions:
 *  LoadVoiceHandler(fLoadedSeconds, fTotalSeconds)
 *  SetPositionVoiceHandler(fPos)
 *  StopVoiceHandler(fPos)
 */

function CreateAttachViewClick(sLink)
{
	return function () {
		var sAttrs = 'toolbar=yes,status=no,scrollbars=yes,resizable=yes,width=760,height=480';
		var oShown = window.open(sLink, 'Popup', sAttrs);
		oShown.focus();
		return false;
	};
}

function CreateAttachDownloadClick(sLink)
{
	return function () {
		document.location = sLink;
		return false;
	};
}

function CAttachmentsPane(eParent)
{
	this.bShown = false;

	this._bBuilded = false;

	this._eAttachList = null;
	this._eContainer = null;
	
	if (eParent !== undefined) {
		this._build(eParent);
	}
}

CAttachmentsPane.prototype = {
	show: function (aAttachments)
	{
		if (!this._bBuilded || aAttachments.length === 0) {
			return;
		}
		CleanNode(this._eAttachList);
		var bHasAttachments = this._fill(aAttachments);
		if (bHasAttachments) {
			this._eContainer.className = 'wm_attachments_container';
			this._eContainer.style.border = '';
			this.bShown = true;
		}
		else {
			this.hide();
		}
	},

	hide: function ()
	{
		if (!this._bBuilded) {
			return;
		}
		this._eContainer.className = 'wm_hide';
		this.bShown = false;
	},

	setMaxHeight: function (iMaxHeight)
	{
		this._iMaxHeight = iMaxHeight;
		this._eContainer.style.height = 'auto';
		if (this._eContainer.offsetHeight > iMaxHeight) {
			this._eContainer.style.height = iMaxHeight + 'px';
		}
	},

	setHeight: function (iHeight)
	{
		this._eContainer.style.border = '0';
		this._eContainer.style.height = iHeight + 'px';
	},

	getHeight: function ()
	{
		if (!this._bBuilded) {
			return 0;
		}
		return this._eContainer.offsetHeight;
	},

	_fill: function (aAttachments)
	{
		var bHasAttachments = false;
		for (var i = 0; i < aAttachments.length; i++) {
			var oAtt = aAttachments[i];
			if (oAtt.inline) {
				continue;
			}
			bHasAttachments = true;
			
			var eLi = CreateChild(this._eAttachList, 'li', [['class', ' wm_upload_success']]);
			
			var oFileParams = GetFileParams(oAtt.fileName);
			var iSize = GetFriendlySize(oAtt.size);
			var sTitle = Lang.ClickToDownload + ' ' + oAtt.fileName + ' (' + iSize + ')';
			var fDownload = CreateAttachDownloadClick(oAtt.download);

			var eIcon = CreateChild(eLi, 'span', [['class', 'wm_upload_icon'], ['title', sTitle],
				['style', 'background-position: -' + oFileParams.iX + 'px -' + oFileParams.iY + 'px;']]);
			eIcon.onclick = fDownload;

			var eName = CreateChild(eLi, 'span', [['class', 'wm_upload_file'], ['title', sTitle]]);
			eName.innerHTML = oFileParams.sShortName + '<span class="wm_upload_size">' + iSize + '</span>';
			eName.onclick = fDownload;

			if (oFileParams.bView) {
				CreateChild(eLi, 'br');
				var eView = CreateChild(eLi, 'a', [['class', 'wm_upload_view'], ['href', '#']]);
				eView.onfocus = function () {this.blur();};
				eView.onclick = CreateAttachViewClick(oAtt.view);
				eView.innerHTML = Lang.View;
			}
		}
		return bHasAttachments;
	},

	_build: function (eParent)
	{
		this._eContainer = CreateChild(eParent, 'div', [['class', 'wm_hide']]);
		this._eAttachList = CreateChild(this._eContainer, 'ul', [['class', 'wm_upload_list']]);
		this._bBuilded = true;
	}
};

function CMessageViewPane(bShowAttachments)
{
	this.bEmpty = false;

	this._bAttachmentsLeft = true;
	this._bHtmlMode = false;
	this._bJustFilled = false;
	this._bNeedPlain = false;
	this._bShowAttachments = bShowAttachments;

	this._eSwitcherCont = {};
	this._eSwitcherObj = {};

	this._sSwitcherClass = '';

	this._eMainCont = null;
	this._eAttachments = null;
	this._eResizer = null;
	this._bHasAttachments = false;
	this._bUseIframe = false;
	this._eMessageCont = null;
	this._eMessageText = null;
	this._eMessageShield = null;
	this._eReplyPane = null;
	this._oResizer = null;
	this._iAttachWidth = 102;
	this._iMinAttachWidth = 10;
	this._iMinMessWidth = 40;
	this._iResizerWidth = 2;
	this._iColPadding = 0;
	this._iWidth = 300;

	this._iMsgId = -1;
	this._sMsgUid = '';
	this._iFldId = -1;
	this._sFldFullName = '';
	this._sMsgCharset = -1;
	this.isRtl = false;
	this.overMsgBody = false;
	this.focusMsgBody = false;
}

CMessageViewPane.prototype = {
	getReplyPaneContainer: function ()
	{
		return this._eReplyPane;
	},

	scrollDown: function ()
	{
		var iScrollTop1 = GetScrollY(this._eMessageCont);
		this._eMessageCont.scrollTop = iScrollTop1 + this._eMessageCont.offsetHeight - 15;
		var iScrollTop2 = GetScrollY(this._eMessageCont);
		if (iScrollTop1 == iScrollTop2) return false;
		return true;
	},
	
	switchToHtmlPlain: function ()
	{
		var iPart = PART_MESSAGE_MODIFIED_PLAIN_TEXT;
		this._bNeedPlain = true;
		if (!this._bHtmlMode) {
			this._bNeedPlain = false;
			iPart = PART_MESSAGE_HTML;
		}
		GetMessageHandler(this._iMsgId, this._sMsgUid, this._iFldId, this._sFldFullName,
			[iPart], this._sMsgCharset, true);
	},
	
	switchToHtmlPlainInNewWindow: function ()
	{
		this._bNeedPlain = false;
		if (this._bHtmlMode) {
			this._bNeedPlain = true;
		}
	},

	resize: function (iWidth, iHeight)
	{
		this.resizeWidth(iWidth);
		this.resizeHeight(iHeight);
	},

	_resizeAttachmentsLeft: function (iWidth)
	{
		var iAttachWidth;
		if (this._oResizer != null) {
			iAttachWidth = this._oResizer._leftShear;
		}
		else {
			iAttachWidth = this._iAttachWidth;
		}

		var iMaxAttachWidth = iWidth - this._iMinMessWidth - this._iResizerWidth - this._iColPadding;
		if (iAttachWidth > iMaxAttachWidth) iAttachWidth = iMaxAttachWidth;

		this._eAttachments.style.left = '0px';
		this._eAttachments.style.width = iAttachWidth + 'px';

		this._eResizer.style.left = iAttachWidth + 'px';
		this._eResizer.style.width = this._iResizerWidth + 'px';
		
		this._eMessageCont.style.left = (iAttachWidth + this._iResizerWidth) + 'px';
		this._setMessageContWidth(iWidth - iAttachWidth - this._iResizerWidth);
	},
	
	_resizeAttachmentsRight: function (iWidth)
	{
		var iMessWidth;
		var bSetResizerLeftPosition = false;
		if (!this._bJustFilled && this._iWidth === iWidth && this._oResizer !== null) {
			iMessWidth = this._oResizer._leftShear;
			if (iMessWidth > (iWidth - this._iMinAttachWidth)) {
				iMessWidth = iWidth - this._iMinAttachWidth;
				bSetResizerLeftPosition = true;
			}
		}
		else {
			iMessWidth = iWidth - this._iAttachWidth;
			bSetResizerLeftPosition = true;
		}
		this._bJustFilled = false;
		if (iMessWidth < this._iMinMessWidth) {
			iMessWidth = this._iMinMessWidth;
			bSetResizerLeftPosition = true;
		}
		if (bSetResizerLeftPosition && this._oResizer != null) {
			this._oResizer.leftPosition = iMessWidth;
			this._oResizer._leftShear = iMessWidth;
		}

		this._eMessageCont.style.left = '0px';
		this._setMessageContWidth(iMessWidth - this._iResizerWidth);
		
		this._iAttachWidth = iWidth - iMessWidth;
		this._eAttachments.style.left = (iMessWidth + this._iResizerWidth) + 'px';
		this._eAttachments.style.width = this._iAttachWidth + 'px';

		this._eResizer.style.left = iMessWidth + 'px';
		this._eResizer.style.width = this._iResizerWidth + 'px';
	},

	_setMessageContWidth: function (iWidth)
	{
		var oMsgCont = $(this._eMessageCont);
		var iPaddings = parseInt(oMsgCont.css('padding-left'), 10) + parseInt(oMsgCont.css('padding-right'), 10);
		if (this._bUseIframe) {
			this._eMessageCont.style.width = (iWidth - this._iColPadding) + 'px';
			this._eMessageText.style.width = (iWidth - this._iColPadding - iPaddings) + 'px';
//			this._eMessageShield && this._eMessageShield.width(iWidth - this._iColPadding - iPaddings);
		}
		else {
			this._eMessageCont.style.width = (iWidth - this._iColPadding - iPaddings) + 'px';
		}
	},

	resizeWidth: function (iWidth)
	{
		this._iWidth = iWidth;
		this._eMainCont.style.width = iWidth - this._iColPadding + 'px';

		if (this._bHasAttachments) {
			if (this._bAttachmentsLeft) {
				this._resizeAttachmentsLeft(iWidth);
			}
			else {
				this._resizeAttachmentsRight(iWidth);
			}
		}
		else {
			this._eMessageCont.style.left = '0px';
			this._setMessageContWidth(iWidth);
		}
		var iClientWidth = this._eMessageCont.clientWidth;
		var oMsgCont = $(this._eMessageCont);
		var iPaddings = parseInt(oMsgCont.css('padding-left')) + parseInt(oMsgCont.css('padding-right'));
		if (!this._bUseIframe && iClientWidth > (iPaddings)) {
			iClientWidth = iClientWidth - iPaddings;
			this._eMessageText.style.width = iClientWidth + 'px';
//			this._eMessageShield && this._eMessageShield.width(iClientWidth);
		}
		
//		this._oResizer.refresh();
		
		return iClientWidth;
	},
	
	resizeHeight: function (iHeight)
	{
		this._eMainCont.style.height = iHeight + 'px';
		this._eAttachments.style.height = iHeight + 'px';
		this._eResizer.style.height = iHeight + 'px';
		var iReplyPaneHeight = this._eReplyPane.offsetHeight;
		var oMsgCont = $(this._eMessageCont);
		var iPaddings = parseInt(oMsgCont.css('padding-top')) + parseInt(oMsgCont.css('padding-bottom'));
		if (this._bUseIframe) {
			this._eMessageCont.style.height = (iHeight - iReplyPaneHeight) + 'px';
			this._eMessageText.style.height = (iHeight - iReplyPaneHeight - iPaddings) + 'px';
//			this._eMessageShield && this._eMessageShield.height(iHeight - iReplyPaneHeight - iPaddings);
		}
		else {
			this._eMessageCont.style.height = (iHeight - iPaddings) + 'px';
			this._eMessageText.style.height = (iHeight - iReplyPaneHeight - iPaddings) + 'px';
		}
	},

	hide: function ()
	{
		this._eMainCont.className = 'wm_hide';
	},
	
	fill: function (oMsg)
	{
		var bMessageChanged = (this._iMsgId !== oMsg.id || this._sMsgUid !== oMsg.uid 
			|| this._iFldId !== oMsg.idFolder || this._sFldFullName !== oMsg.folderFullName 
			|| this._sMsgCharset !== oMsg.charset);
		
		this._eMainCont.className = '';
		this._iMsgId = oMsg.id;
		this._sMsgUid = oMsg.uid;
		this._iFldId = oMsg.idFolder;
		this._sFldFullName = oMsg.folderFullName;
		this._sMsgCharset = oMsg.charset;
		this.isRtl = oMsg.isRtl;
		this._updateDirClass();
		
		CleanNode(this._eAttachments);
		if (oMsg.attachments.length == 0 || !this._bShowAttachments) {
			this._bHasAttachments = false;
		}
		else {
			this._bHasAttachments = false;
			for (var i = 0; i < oMsg.attachments.length; i++) {
				var oAttachment = oMsg.attachments[i];
				if (oAttachment.inline) {
					continue;
				}
				this._bHasAttachments = true;
				
				var sFileName = oAttachment.fileName;
				var eAttach = CreateChild(this._eAttachments, 'div', [['style', 'float: left;']]);
				var sSize = GetFriendlySize(oAttachment.size);
				var oFileParams = GetFileParams(sFileName);
				var sTitle = Lang.ClickToDownload + ' ' + sFileName + ' (' + sSize + ')';
				if (sFileName.length > 16) {
					sFileName = sFileName.substring(0, 15) + '&#8230;';
				}
				var eDownloadLink = CreateChild(eAttach, 'a',
					[['href', oAttachment.download], ['class', 'wm_attach_download_a']]);
				eDownloadLink.onfocus = function () {this.blur();};
				var sInnerHtml = '<div style="background-position: -' + oFileParams.iX + 'px';
				sInnerHtml += ' -' + oFileParams.iY + 'px;" title="' + sTitle + '"></div>';
				sInnerHtml += '<span title="' + sTitle + '">' + sFileName + '</span>';
				eDownloadLink.innerHTML = sInnerHtml;
				if (oFileParams.bView && oAttachment.view != '#') {
					CreateChild(eAttach, 'br');
					var eViewLink = CreateChild(eAttach, 'a',
						[['href', ''], ['class', 'wm_attach_view_a']]);
					eViewLink.innerHTML = Lang.View;
					eViewLink.onclick = CreateAttachViewClick(oAttachment.view);
				}
			}
			CreateChild(this._eAttachments, 'div', [['class', 'clear']]);
		}
		if (this._bHasAttachments) {
			this._eAttachments.className = 'wm_message_attachments';
			this._eResizer.className = 'wm_vresizer_mess';
		}
		else {
			this._eAttachments.className = 'wm_hide';
			this._eResizer.className = 'wm_hide';
		}

		this._clearMessageCont();

		var sSwitcherText = '';
		if (oMsg.hasHtml && !this._bNeedPlain) {
			sSwitcherText = Lang.SwitchToPlain;
			this._bHtmlMode = true;
		}
		else {
			sSwitcherText = Lang.SwitchToHTML;
			this._bHtmlMode = false;
		}
		var bBothBodies = (oMsg.hasHtml && oMsg.hasPlain);
		this._eSwitcherObj.innerHTML = bBothBodies ? sSwitcherText : '';
		this._eSwitcherCont.className = this._sSwitcherClass;

		var sBody = '';
		if (this._bHtmlMode && oMsg.hasHtml) {
			sBody = oMsg.htmlBody;
		}
		else if (!this._bHtmlMode && oMsg.hasPlain) {
			sBody = oMsg.plainBody;
		}
		this._writeMessageCont(sBody);
		this.bEmpty = false;
		if (sBody.length < 10) {
			var sTrimmedBody = Trim(sBody.replace(/<br>/, '').replace(/<br\/>/, '').replace(/<br \/>/, '').replace(/\n/, ''));
			this.bEmpty = (sTrimmedBody.length === 0);
		}
		this._bNeedPlain = false;
		
		if (bMessageChanged) {
			this._eMessageCont.scrollTop = 0;
		}
		this._bJustFilled = true;
//		this._oResizer.initStartState();

		return this._bHtmlMode;
	},

	changeRTL: function ()
	{
		if (!window.RTL) return;
		this.isRtl = !this.isRtl;
		this._updateDirClass();
	},
	
	_exportToIframeWithInterval: function (oBody, fReadyCallback)
	{
		var 
			iIntervalId = 0,
			iIndex = 0
		;
		
		iIntervalId = window.setInterval(function () {
			if (10 < iIndex)
			{
				window.clearInterval(iIntervalId);
				return false;
			}
			
			if (oBody)
			{
				fReadyCallback(oBody);
				window.clearInterval(iIntervalId);
			}
			
			iIndex++;
			return true;
			
		}, 100);
	},

	_writeMessageCont: function (html)
	{
		if (this._bUseIframe) {
			window.setTimeout((function (self) {
				return function () {
					self._exportToIframeWithInterval(self._eMessageText.contentWindow.document.body, 
						function (oBody) {
							$(oBody).html(html);
						}
					);
				};
			}(this)), 100);
		}
		else {
			this._eMessageText.innerHTML = html;
		}
	},

	_clearMessageCont: function ()
	{
		if (this._bUseIframe) {
			CleanNode(this._eMessageText.contentWindow.document.body);
		}
		else {
			CleanNode(this._eMessageText);
		}
	},

	_updateDirClass: function()
	{
		var sClassName = (this.isRtl) ? 'wm_message_body_rtl' : 'wm_message_body_ltr';
		if (this._bUseIframe) {
			var sStyle = 'font: normal 12px Arial;';
			sStyle += this.isRtl ? ' direction: rtl; text-align: right !important;' : ' direction: ltr; text-align: left !important;';
			
			this._exportToIframeWithInterval(this._eMessageText.contentWindow.document.body,
				function (oBody) {
					$(oBody).attr('style', sStyle);
				}
			);
		}
		else {
			this._eMessageText.className = sClassName;
		}
	},
	
	clean: function (sInfo)
	{
		this._eMainCont.className = '';
		this._eAttachments.innerHTML = '';
		this._eAttachments.className = 'wm_hide';
		this._eResizer.className = 'wm_hide';
//		this._oResizer.refresh();
		this._bHasAttachments = false;
		if (typeof(sInfo) != 'string') {
			sInfo = '';
		}
		this._clearMessageCont();
		this._writeMessageCont(sInfo);
		this._eSwitcherCont.className = 'wm_hide';
		this._bHtmlMode = false;
		this._bNeedPlain = false;

		this.isRtl = (window.RTL) ? window.RTL : false;
		this._updateDirClass();
	},
	
	setSwitcher: function (eCont, sClass, eObj)
	{
		if (eCont !== undefined) {
			this._eSwitcherCont = eCont;
			this._sSwitcherClass = sClass;
			this._eSwitcherObj = eObj;
		}
	},

	_buildAttachments: function ()
	{
		this._eAttachments = CreateChild(this._eMainCont, 'div');
		this._eAttachments.style.position = 'absolute';
		this._eAttachments.style.top = '0px';
		this._eAttachments.className = 'wm_hide';
		this._bHasAttachments = false;
	},

	_buildResizer: function ()
	{
		this._eResizer = $('<div></div>').css({'position': 'absolute', 'top': '0'}).addClass('wm_hide').appendTo(this._eMainCont)[0];
	},
	
	shieldShowType: function (bShow)
	{
		if (this._eMessageShield) {
			if (bShow) {
				this._eMessageShield.show();
			} else {
				this._eMessageShield.hide();
			}
		}
	},
	
	_buildMessage: function ()
	{
		this._eMessageCont = CreateChild(this._eMainCont, 'div', [['class', 'wm_message'],
			['style', 'position: absolute; top: 0px;']]);
		var eMessage = this._eMessageCont;
		if (this._bUseIframe) {
			this._eMessageText = CreateChild(this._eMessageCont, 'iframe', [
				['frameborder', '0'],
				['style', 'border-width: 0px;margin:0;padding:0; width: 100%']]);
			
			this._eMessageShield = $('<div />')
				.addClass('wm_iframe_mask')
				.hide()
				.appendTo(this._eMessageCont)
			;
		}
		else {
			this._eMessageText = CreateChild(this._eMessageCont, 'div');
			eMessage = this._eMessageText;
		}

		var obj = this;
		eMessage.onmouseover = function () {obj.overMsgBody = true;};
		eMessage.onmouseout = function () {obj.overMsgBody = false;};
		eMessage.onfocus = function () {obj.focusMsgBody = true;};
		eMessage.onblur = function () {obj.focusMsgBody = false;};

		this._eReplyPane = CreateChild(this._eMessageCont, 'div');
	},
	
	build: function (eContainer, iColPadding)
	{
		this._iColPadding = iColPadding;
		
		var eMainCont = CreateChild(eContainer, 'div');
		this._eMainCont = eMainCont;
		eMainCont.style.position = 'relative';
		eMainCont.className = 'wm_view_message_container';

		var iMinLeftWidth = this._iMinAttachWidth;
		var iMinRightWidth = this._iMinMessWidth;
		if (this._bAttachmentsLeft) {
			this._buildAttachments();
			this._buildResizer();
			this._buildMessage();
		}
		else {
			this._buildMessage();
			this._buildResizer();
			this._buildAttachments();
			iMinLeftWidth = this._iMinMessWidth;
			iMinRightWidth = this._iMinAttachWidth;
		}

//		this._oResizer = new CResizer(this._eResizer, eMainCont, this._eAttachments, this._eMessageCont, 20, 200,
//		[
//			WebMail, function (sType) {
//				this.resizeProcess(sType);
//			}
//		]);
		
		this._oResizer = new CVerticalResizer(this._eResizer, eMainCont, this._iResizerWidth,
			iMinLeftWidth, iMinRightWidth, this._iAttachWidth,
			'WebMail.resizeBody(RESIZE_MODE_MSG_PANE);', 1, [
				WebMail, function (sType) {
					this.resizeProcess(sType);
				}
			]);
	}
};

function CVoiceMessageViewPane(eContainer, bInNewWindow)
{
	this._bBuilded = false;
	this._bMsgRead = false;
	this._bShown = false;
	this.bInNewWindow = bInNewWindow;
	this.bAllowFlashPlayer = (WebMail.Settings.iFlashVersion >= 10);

	this._eButton = null;
	this._eContainer = eContainer;
	this._eDownloadLink = null;
	this._eDuration = null;
	this._eFlashContainer = null;
	this._eFooter = null;
	this._eProgressCurrent = null;
	this._eProgressMarker = null;
	this._eReceived = null;
	this._eTranscription = null;
	this._eVoiceItem = null;

	this._fTotalSeconds = 0;
	this._iMsgSize = 0;
	this._sMsgId = '';
}

CVoiceMessageViewPane.prototype = {
	show: function (oMsg, iScreenId)
	{
		if (!this._bBuilded) {
			this._build();
		}
		if (!this._bBuilded) {
			return;
		}
		this._bShown = true;
		this._eFlashContainer.className = '';
		if (this.bAllowFlashPlayer) {
			this.showPlayer(oMsg, iScreenId);
		}
		else {
			this.showUpgradeFlashPlayerMessage();
		}
	},

	showUpgradeFlashPlayerMessage: function ()
	{
		this._eVoiceItem.className = 'wm_hide';
		this.removeFlash();
		this._eFlashContainer.innerHTML = '<div class="wm_upgrate_flash_player">' + Lang.VoiceMessageUpgradeFlashPlayer + '</div>';
	},

	showPlayer: function (oMsg, iScreenId)
	{
		var
			oVoiceAttachment = oMsg.getVoiceAttachment(),
			sVoiceLink = encodeURIComponent(oVoiceAttachment.sVoice),
			iWidth = 1,
			iHeight = 1,
			sPlayerLink = 'js/mail/wavplayer.swf?gui=full&amp;w=' + iWidth + '&amp;h=' + iHeight,
			sFlashHtml = ''
		;
		
		this._sMsgId = oMsg.getIdForList(iScreenId);
		this._iMsgSize = oMsg.size;

		this._eReceived.innerHTML = oMsg.getShortDate();
		if (oVoiceAttachment.sTranscription && oVoiceAttachment.sTranscription.length > 0) {
			this._eTranscription.innerHTML = oMsg.subject;
			this._eFooter.className = 'wm_voice_item_footer';
		}
		else {
			this._eFooter.className = 'wm_hide';
		}
		if (this._bMsgRead) {
			this._eVoiceItem.className = 'wm_voice_item wm_voice_item_read';
		}
		else {
			this._eVoiceItem.className = 'wm_voice_item wm_voice_item_active';
		}
		this._eDownloadLink.href = oVoiceAttachment.download;
		this.setDuration(oVoiceAttachment.iDuration);

		sFlashHtml += '<object';
		sFlashHtml += ' classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"';
		sFlashHtml += ' codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0"';
		sFlashHtml += ' width="' + iWidth + '"';
		sFlashHtml += ' height="' + iHeight + '"';
		sFlashHtml += ' id="voiceAttachmentFlash"';
		sFlashHtml += ' >';
		sFlashHtml += '<param name="movie" value="' + sPlayerLink + '">';
		sFlashHtml += '<param name="allowScriptAccess" value="always" />';
		sFlashHtml += '<param name="quality" value="high">';
		sFlashHtml += '<param name="salign" value="lt" />';
		sFlashHtml += '<param name="scale" value="noscale">';
		sFlashHtml += '<param name="menu" value="false" />';
		sFlashHtml += '<param name="FlashVars" value="sound=' + sVoiceLink + '">';
		sFlashHtml += '<param name="bgcolor" value="#ffffff">';
		sFlashHtml += '<embed src="' + sPlayerLink + '"';
		sFlashHtml += ' quality="high"';
		sFlashHtml += ' salign="lt"';
		sFlashHtml += ' width="' + iWidth + '"';
		sFlashHtml += ' height="' + iHeight + '"';
		sFlashHtml += ' align="middle"';
		sFlashHtml += ' scale="noscale"';
		sFlashHtml += ' menu="false"';
		sFlashHtml += ' bgcolor="#ffffff"';
		sFlashHtml += ' name="voiceAttachmentFlash"';
		sFlashHtml += ' swLiveConnect="true"';
		sFlashHtml += ' allowScriptAccess="always"';
		sFlashHtml += ' type="application/x-shockwave-flash"';
		sFlashHtml += ' pluginspage="http://www.macromedia.com/go/getflashplayer"';
		sFlashHtml += ' FlashVars="sound=' + sVoiceLink + '"';
		sFlashHtml += '></embed>';
		sFlashHtml += '</object>';

		this.removeFlash();
		this._eFlashContainer.innerHTML = sFlashHtml;
	},

	hide: function ()
	{
		if (!this._bShown) return;
		this._bShown = false;
		this._eVoiceItem.className = 'wm_hide';
		this._eFlashContainer.className = 'wm_hide';
		this.removeFlash();
		this._eContainer.style.height = 'auto';
		if (this.bAllowFlashPlayer) {
			this._stopPlaying(0);
		}
	},

	removeFlash: function ()
	{
		RemoveAllFlashes();
		this._eFlashContainer.innerHTML = '';
	},

	resizeHeight: function (iHeight)
	{
		if (!this._bShown) return;
		this._eContainer.style.height = iHeight + 'px';
	},

	setReadFlag: function (bMsgRead)
	{
		this._bMsgRead = bMsgRead;
	},
	
	setDuration: function (fMilliseconds)
	{
		var
			fSeconds = fMilliseconds/1000,
			iSeconds = Math.floor(fSeconds),
			iMinutes = Math.floor(iSeconds / 60),
			sDuration = ''
		;
		
		this._fTotalSeconds = fSeconds;
		iSeconds = iSeconds - iMinutes * 60;
		if (iMinutes < 10) {
			sDuration += '0';
		}
		sDuration += iMinutes + ':';
		if (iSeconds < 10) {
			sDuration += '0';
		}
		sDuration += iSeconds;
		this._eDuration.innerHTML = sDuration;
	},

	setPosition: function (fPosSeconds)
	{
		var iTotalPerc = 100;
		var iPosPerc = Math.round((iTotalPerc / this._fTotalSeconds) * fPosSeconds);
		if (iPosPerc < 0) {
			iPosPerc = 0;
		}
		if (iPosPerc > iTotalPerc) {
			iPosPerc = iTotalPerc;
		}
		this._eProgressCurrent.style.width = iPosPerc + '%';
		this._eProgressMarker.style.left = iPosPerc + '%';
	},

	play: function ()
	{
		JsWavPlayer.play();
		this._eButton.className = 'wm_voice_button wm_voice_button_pause';
		var obj = this;
		this._eButton.onclick = function () {
			obj.pause();
		};
	},

	pause: function ()
	{
		JsWavPlayer.pause();
		this._prepareButtonForPlay();
	},

	stop: function (fPosSeconds)
	{
		this._eVoiceItem.className = 'wm_voice_item wm_voice_item_read';
		this._stopPlaying(fPosSeconds);
	},

	_stopPlaying: function (fPos)
	{
		this.setPosition(fPos);
		this._prepareButtonForPlay();
		this._markMessageAsRead();
	},

	_markMessageAsRead: function ()
	{
		if (this._bMsgRead) return;
		if (this.bInNewWindow) {
			if (window.opener) {
				window.opener.MarkMessageAsRead(this._sMsgId, this._iMsgSize);
			}
		}
		else {
			MarkMessageAsRead(this._sMsgId, this._iMsgSize);
		}
		this._bMsgRead = true;
	},

	_prepareButtonForPlay: function ()
	{
		this._eButton.className = 'wm_voice_button wm_voice_button_play';
		var obj = this;
		this._eButton.onclick = function () {
			obj.play();
		};
	},

	_build: function ()
	{
		var eDiv = CreateChild(this._eContainer, 'div', [['class', 'wm_voice_item wm_voice_item_active']]);
		this._eVoiceItem = eDiv;
		CreateChild(eDiv, 'div', [['class', 'wm_voice_item_corner_lb']]);
		CreateChild(eDiv, 'div', [['class', 'wm_voice_item_corner_lt']]);
		CreateChild(eDiv, 'div', [['class', 'wm_voice_item_corner_rt']]);
		CreateChild(eDiv, 'div', [['class', 'wm_voice_item_corner_rb']]);
		
		var eHeaderDiv = CreateChild(eDiv, 'div', [['class', 'wm_voice_item_header']]);
		this._eReceived = CreateChild(eHeaderDiv, 'span', [['class', 'wm_voice_item_header_date']]);
		var eReceivedTitle = CreateChild(eHeaderDiv, 'span');
		eReceivedTitle.innerHTML = Lang.VoiceMessageReceived;
		WebMail.langChanger.register('innerHTML', eReceivedTitle, 'VoiceMessageReceived', '');

		var eBodyDiv = CreateChild(eDiv, 'div', [['class', 'wm_voice_item_content']]);
		this._eButton = CreateChild(eBodyDiv, 'span', [['class', 'wm_voice_button wm_voice_button_play']]);
		var obj = this;
		this._eButton.onclick = function () {obj.play();};
		
		var eProgress = CreateChild(eBodyDiv, 'span', [['class', 'wm_voice_progress_bar']]);
		this._eProgressCurrent = CreateChild(eProgress, 'span', [['class', 'wm_voice_progress_bar_current']]);
		this._eProgressMarker = CreateChild(eProgress, 'span', [['class', 'wm_voice_progress_bar_marker']]);
		
		this._eDuration = CreateChild(eBodyDiv, 'span', [['class', 'wm_voice_progress_bar_time']]);
		var eDownloadCont = CreateChild(eBodyDiv, 'span', [['class', 'wm_voice_dowload_link_cont']]);
		this._eDownloadLink = CreateChild(eDownloadCont, 'a', [['href', '#']]);
		this._eDownloadLink.innerHTML = Lang.VoiceMessageDownload;
		WebMail.langChanger.register('innerHTML', this._eDownloadLink, 'VoiceMessageDownload', '');

		this._eFooter = CreateChild(eDiv, 'div', [['class', 'wm_voice_item_footer']]);
		var eTranscriptionTitle = CreateChild(this._eFooter, 'span', [['class', 'wm_voice_item_footer_title']]);
		eTranscriptionTitle.innerHTML = Lang.VoiceMessageTranscription + ':';
		WebMail.langChanger.register('innerHTML', eTranscriptionTitle, 'VoiceMessageTranscription', ':');
		this._eTranscription = CreateChild(this._eFooter, 'span');

		this._eFlashContainer = CreateChild(this._eContainer, 'span');
		
		this._bBuilded = true;
	}
};

function LoadVoiceHandler(fLoadedSeconds, fTotalSeconds)
{
	var oScreen = WebMail.getCurrentListScreen();
	if (oScreen == null) return;
	oScreen.setVoiceMessageDuration(fTotalSeconds * 1000);
}

function SetPositionVoiceHandler(fPos)
{
	var oScreen = WebMail.getCurrentListScreen();
	if (oScreen == null) return;
	oScreen.setVoiceMessagePosition(fPos);
}

function StopVoiceHandler(fPos)
{
	var oScreen = WebMail.getCurrentListScreen();
	if (oScreen == null) return;
	oScreen.stopVoiceMessage(fPos);
}

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
