/*
* Classes:
*  CServerBasedData()
*  CServerAttachmentList()
*  CServerAttachment()
*  CAttachmentsSelectionPane()
*/
var EnumAttachTypes = {
	Resume: 'resume',
	Letter: 'letter'
};

function CServerBasedData()
{
	this.type = TYPE_SERVER_BASED_DATA;

	this.sRecipient = '';
	this.sSubject = '';
	this.sBody = '';
	this.bHtmlBody = true;
	this.oAttachment = null;
}

CServerBasedData.prototype = {
	getStringDataKeys: function ()
	{
		return '';
	},

	getFromXml: function(eRoot)
	{
		var
			eRecipient = XmlHelper.getFirstChildNodeByName(eRoot, 'recipient'),
			eSubject = XmlHelper.getFirstChildNodeByName(eRoot, 'subject'),
			eBody = XmlHelper.getFirstChildNodeByName(eRoot, 'body'),
			eAttachment = XmlHelper.getFirstChildNodeByName(eRoot, 'attachment')
		;

		this.sRecipient = XmlHelper.getFirstChildValue(eRecipient, this.sRecipient);
		this.sSubject = XmlHelper.getFirstChildValue(eSubject, this.sSubject);
		this.sBody = XmlHelper.getFirstChildValue(eBody, this.sBody);
		this.bHtmlBody = XmlHelper.getBoolAttributeByName(eBody, 'html', this.bHtmlBody);
		this.oAttachment = this._readAttachment(eAttachment);
	}
}

CServerBasedData.prototype._readAttachment = CMessage.prototype._readAttachment;

function CServerAttachmentList()
{
	this.type = TYPE_SERVER_ATTACHMENT_LIST;

	this.aList = [];
}

CServerAttachmentList.prototype = {
	getStringDataKeys: function ()
	{
		return '';
	},

	getFromXml: function(eRoot)
	{
		var
			aAttachments = eRoot.childNodes,
			iLen = aAttachments.length,
			iIndex = 0,
			eAttachment = null,
			oAttachment = null
		;

		for (; iIndex < iLen; iIndex++) {
			eAttachment = aAttachments[iIndex];
			if (eAttachment.tagName === 'attachment') {
				oAttachment = this.getAttachment(eAttachment);
				this.aList.push(oAttachment);
			}
		}
	},

	getAttachment: function (eAttachment)
	{
		var
			iId = XmlHelper.getIntAttributeByName(eAttachment, 'id', -1),
			sTypeCD = XmlHelper.getAttributeByName(eAttachment, 'typecd', EnumAttachTypes.Resume),
			sName = XmlHelper.getFirstChildValue(eAttachment, '')
		;
		return {iId: iId, sTypeCD: sTypeCD, sName: sName};
	}
}

function CServerAttachment()
{
	this.type = TYPE_SERVER_ATTACHMENT;

	this.oAttachment = null;
}

CServerAttachment.prototype = {
	getStringDataKeys: function() {
		return '';
	},

	getFromXml: function(eRoot) {
		var
			sFormat = XmlHelper.getAttributeByName(eRoot, 'format', ''),
			eBody = XmlHelper.getFirstChildNodeByName(eRoot, 'body'),
			sBody = XmlHelper.getFirstChildValue(eBody, '')
		;
		
		if (sFormat === 'attach' || sFormat === 'both') {
			this.oAttachment = this._readAttachment(eRoot);
			this.oAttachment.id = this.oAttachment.tempName;
		}
		else {
			this.oAttachment = {};
		}
		
		if (sFormat === 'body' || sFormat === 'both') {
			this.oAttachment.sBody = HtmlDecode(sBody);
		}
		
		this.oAttachment.sFormat = sFormat;
	}
}

CServerAttachment.prototype._readAttachment = CMessage.prototype._readAttachment;

function CAttachmentsSelectionPane()
{
	this.$Dialog = null;
	this.$TypeSelection = null;
	this.$AttachmentSelection = null;
	this.$AddToBodyCont = null;
	this.$AddToBodyCheck = null;
	this.$SelectTitle = null;
	this.sTypeCD = EnumAttachTypes.Resume;
	this.aResumeTypes = [
		{sValue: 'pdf', sLangField: 'DialogAttachTypePdfRecom'},
		{sValue: 'doc', sLangField: 'DialogAttachTypeDoc'},
		{sValue: 'rtf', sLangField: 'DialogAttachTypeRtf'},
		{sValue: 'html', sLangField: 'DialogAttachTypeHtml'},
		{sValue: 'txt', sLangField: 'DialogAttachTypeTxt'},
		{sValue: 'no', sLangField: 'DialogAttachTypeNo'}
	],
	this.aLetterTypes = [
		{sValue: 'text', sLangField: 'DialogAttachTypeTextInBody'},
		{sValue: 'pdf', sLangField: 'DialogAttachTypePdf'},
		{sValue: 'doc', sLangField: 'DialogAttachTypeDoc'},
		{sValue: 'rtf', sLangField: 'DialogAttachTypeRtf'},
		{sValue: 'html', sLangField: 'DialogAttachTypeHtml'},
		{sValue: 'txt', sLangField: 'DialogAttachTypeTxtAttach'}
	],
	this._build();
}

CAttachmentsSelectionPane.prototype = {
	openDialog: function (aAttachments)
	{
		var
			self = this,
			aButtons = [
				{
					text: Lang.DialogAttachButton,
					click: function() {
						self.getAttachment();
					}
				},
				{
					text: Lang.Cancel,
					click: function() {
						self.closeDialog();
					}
				}
			],
			sTitle = (this.sTypeCD === EnumAttachTypes.Resume) ? Lang.DialogAttachHeaderResume : Lang.DialogAttachHeaderLetter,
			sSelect = (this.sTypeCD === EnumAttachTypes.Resume) ? Lang.DialogAttachName : Lang.DialogAttachSelectLetter
		;
		this._initPopup();
		this.$SelectTitle.html(sSelect);
		this._fillAttachments(aAttachments);
		if (this.sTypeCD === EnumAttachTypes.Resume) {
			this._fillTypeSelection(this.aResumeTypes);
			this.$AddToBodyCont.show();
		}
		else {
			this._fillTypeSelection(this.aLetterTypes);
			this.$AddToBodyCont.hide();
		}
		this.$Dialog
			.dialog('option', 'buttons', aButtons)
			.dialog({title: sTitle, width: 'auto', height: 'auto'})
			.dialog('open');
	},
	
	closeDialog: function ()
	{
		this.$Dialog.dialog('close');
	},
	
	getAttachment: function ()
	{
		var
			sId = this.$AttachmentSelection.val(),
			sType = this.$TypeSelection.val(),
			sFormat = 'attach',
			bAddToBody = this.$AddToBodyCheck.attr('checked') === 'checked'
		;
		if (this.sTypeCD === EnumAttachTypes.Resume) {
			if (sType === 'no') {
				if (bAddToBody) {
					sFormat = 'body';
					sType = 'text';
				}
				else {
					sFormat = '';
				}
			}
			else if (bAddToBody) {
				sFormat = 'both';
			}
		}
		else if (sType === 'text') {
			sFormat = 'body';
		}
		if (sFormat.length > 0) {
			RequestHandler('get', 'server-attachment',
				'<param name="id" value="' + sId + '"/><param name="type" value="' + sType + '"/><param name="format" value="' + sFormat + '"/>');
//			if (sFormat === 'attach' || sFormat === 'both') {
//				Uploader.addAttachment({ id: 'id', inline: false, fileName: 'sFileName', size: 123,
//							download: 'sDownload', view: 'sView', tempName: 'sTempName', mimeType: 'sMimeType' });
//			}
//			if (sFormat === 'body' || sFormat === 'both') {
//				var oSreen = WebMail.Screens[SCREEN_NEW_MESSAGE]
//				oSreen._addToBody('data.oAttachment.sBody');
//			}
//			this.closeDialog();
		}
		else {
			this.closeDialog();
		}
	},
	
	_build: function ()
	{
		this._buildButton(EnumAttachTypes.Resume);
		this._buildButton(EnumAttachTypes.Letter);
	},
	
	_buildButton: function (sTypeCD)
	{
		var
			$Uploader = null,
			$Button = null,
			sButtonLangField = (sTypeCD === EnumAttachTypes.Resume) ? 'DialogAttachResume' : 'DialogAttachLetter',
			self = this,
			$ButtonText = null
		;

		$Uploader = $('#' + sTypeCD + 'uploader-from-server');
		$('<span class="wm_dialog_attach_icon"></span>')
			.appendTo($Uploader);
		$Button = $('<span class="wm_dialog_attach_open_button"></span>')
			.css({
				'position': 'relative',
				'overflow-x': 'hidden',
				'overflow-y': 'hidden',
				'direction': 'ltr',
				'display': 'inline'
			})
			.hover(function () {$(this).addClass('hover');}, function () {$(this).removeClass('hover');})
			.appendTo($Uploader)
		;
		$ButtonText = $('<span></span>')
			.bind('click', function () {
				self.sTypeCD = sTypeCD;
				GetHandler(TYPE_SERVER_ATTACHMENT_LIST, {}, [], '');
//				self.openDialog([
//					{iId: '0', sTypeCD: 'resume', sName: 'resume 0'},
//					{iId: '1', sTypeCD: 'resume', sName: 'resume 1'},
//					{iId: '2', sTypeCD: 'letter', sName: 'letter 2'},
//					{iId: '3', sTypeCD: 'letter', sName: 'letter 3'},
//					{iId: '4', sTypeCD: 'resume', sName: 'resume 4'},
//					{iId: '5', sTypeCD: 'resume', sName: 'resume 5'},
//					{iId: '6', sTypeCD: 'letter', sName: 'letter 6'},
//					{iId: '7', sTypeCD: 'letter', sName: 'letter 7'},
//					{iId: '8', sTypeCD: 'resume', sName: 'resume 8'},
//					{iId: '9', sTypeCD: 'letter', sName: 'letter 9'}
//				]);
			})
			.appendTo($Button)
		;
		WebMail.langChanger.register$({ sType: 'html', $elem: $ButtonText, sField: sButtonLangField });
	},
	
	_fillAttachments: function (aAttachments)
	{
		var
			iIndex = 0,
			iLen = aAttachments.length,
			oAttachment
		;
		
		this.$AttachmentSelection.children().remove();
		
		for (; iIndex < iLen; iIndex++) {
			oAttachment = aAttachments[iIndex];
			if (this.sTypeCD !== oAttachment.sTypeCD) {
				continue;
			}
			$('<option></option>')
				.html(oAttachment.sName)
				.val(oAttachment.iId)
				.appendTo(this.$AttachmentSelection);
		}
	},
	
	_fillTypeSelection: function (aTypes)
	{
		var
			iIndex = 0,
			iLen = aTypes.length
		;
		
		this.$TypeSelection.children().remove();
		
		for (; iIndex < iLen; iIndex++) {
			$('<option></option>')
				.html(Lang[aTypes[iIndex].sLangField])
				.val(aTypes[iIndex].sValue)
				.appendTo(this.$TypeSelection);
		}
	},

	_initPopup: function ()
	{
		var
			$Table, $Row, $Cell
		;
		
		this.$Dialog = $('<div></div>').appendTo(document.body)
			.dialog({
				modal: true,
				autoOpen: false,
				minHeight: 100,
				minWidth: 140,
				position: 'center'
			});
			
		$Table = $('<table class="wm_dialog_attach"></table>').appendTo(this.$Dialog);
		$Row = $('<tr></tr>').appendTo($Table);
		this.$SelectTitle = $('<td></td>').html(Lang.DialogAttachName).appendTo($Row);
		this.$AttachmentSelection = $('<select></select>').appendTo($('<td></td>').appendTo($Row));
		
		$Row = $('<tr></tr>').appendTo($Table);
		$('<td></td>').html(Lang.DialogAttachType).appendTo($Row);
		this.$TypeSelection = $('<select></select>').appendTo($('<td></td>').appendTo($Row));
		
		this.$AddToBodyCont = $('<tr></tr>').appendTo($Table).hide();
		$('<td></td>').appendTo(this.$AddToBodyCont);
		$Cell = $('<td></td>').appendTo(this.$AddToBodyCont);
		this.$AddToBodyCheck = $('<input type="checkbox" id="add_to_body" />').appendTo($Cell);
		$('<label for="add_to_body"></label>').html(Lang.DialogAttachAddToBody).appendTo($Cell);
	}
}
