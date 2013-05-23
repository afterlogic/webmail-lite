/*
 * Functions:
 *  AddEvent
 * Objects:
 *  HtmlEditorField
 */

function AddEvent(obj, eventType, eventFunction, capture)
{
	if (obj.addEventListener) {
		if (typeof(capture) != 'boolean') capture = false;
		obj.addEventListener(eventType, eventFunction, capture);
		return true;
	}
	else if (obj.attachEvent) {
		return obj.attachEvent('on' + eventType, eventFunction);
	}
	return false;
}

var Fonts = ['Arial', 'Arial Black', 'Courier New', 'Tahoma', 'Times New Roman', 'Verdana'];

var HtmlEditorField = {
	editor: null,
	area: null,
	htmlMode: true,
	focused: false,

	_defaulFontName: 'Tahoma',
	_defaulFontSize: 2,

	_mainTbl: null,
	_sEditorClassName: 'wm_html_editor',
	_header: null,
	_iframesContainer: null,
	_colorPalette: null,
	_colorTable: null,

	_btnFontColor: null,
	_btnBgColor: null,
	_btnInsertLink: null,
	_btnInsertImage: null,
	_fontFaceSel: null,
	_fontSizeSel: null,

	_loaded: false,
	_designMode: false,
	_designModeStart: false,

	_colorMode: -1,
	_colorChoosing: 0,
	_currentColor: null,

	_range: null,
	_shown: false,

	_plainEditor: null,
	_htmlSwitcher: null,
	_waitHtml: null,

	_width: 0,
	_height: 0,

	_disabler: null,
	_disable: false,

	_builded: false,

	_tabindex: -1,

	_imgUploaderCont: null,
	_imgUploaderForm: null,
	_imgUploaderFile: null,

	_bActive: null,
	_oSignaturePane: null,

	setPlainEditor: function (plainEditor, htmlSwitcher, tabindex, useInsertImage)
	{
		this._plainEditor = plainEditor;
		this._htmlSwitcher = htmlSwitcher;
		this.replace();
		var obj = this;
		this._htmlSwitcher.onclick = function () {
			obj.onclickSwitcher();
			return false;
		};
		this._tabindex = tabindex;
		this._btnInsertImage.className = (useInsertImage && WebMail.Settings.allowInsertImage)
			? 'wm_toolbar_item' : 'wm_hide';
	},

	onclickSwitcher: function ()
	{
		if (!this._bActive && this._oSignaturePane !== null) {
			this._oSignaturePane.setUseSignature(true);
		}
		this.switchHtmlMode();
	},

	onfocus: function ()
	{
		this.focused = true;
		if (!this._bActive && this._oSignaturePane !== null) {
			this._oSignaturePane.setUseSignature(true);
		}
	},

	makeActive: function ()
	{
		this._bActive = true;
		if (this.htmlMode) {
			if ($.trim(TextFormatter.removeAllTags(this.getHtml(true))) === Lang.SignatureEnteringHere) {
				this.setHtml('');
			}
			this._sEditorClassName = 'wm_html_editor';
			this._mainTbl.className = this._sEditorClassName;
			this.focus();
		}
		else {
			if (this._plainEditor.value === Lang.SignatureEnteringHere) {
				this._plainEditor.value = '';
			}
			this._plainEditor.className = 'wm_plain_editor_text wm_plain_editor_active';
			this._plainEditor.onfocus = function () { };
			this._plainEditor.focus();
		}
	},

	makeInactive: function (oSignaturePane)
	{
		this._bActive = false;
		this._oSignaturePane = oSignaturePane;
		if (this.htmlMode) {
			if (TextFormatter.removeAllTags(this.getHtml(true)) === '') {
				this.setHtml('<span style="color: #AAAAAA;">' + Lang.SignatureEnteringHere + '</span>');
			}
			this._sEditorClassName = 'wm_html_editor wm_html_editor_inactive';
			this._mainTbl.className = this._sEditorClassName;
		}
		else {
			if (this._plainEditor.value === '') {
				this._plainEditor.value = Lang.SignatureEnteringHere;
			}
			this._plainEditor.className = 'wm_plain_editor_text wm_plain_editor_inactive';
			this._plainEditor.onfocus = function () {
				oSignaturePane.setUseSignature(true);
			};
		}
	},

	getHtmlForSwitch: function ()
	{
		if (this._designMode) {
			var sHtml = this.getHtml(true);
			if ((Browser.ie || Browser.opera) && sHtml.length > 0) {
				sHtml = sHtml.replaceStr('<style> p { margin-top: 0px; margin-bottom: 0px; } </style>', '');
				sHtml = sHtml.replaceStr('<style> .misspel { background: url(skins/redline.gif) repeat-x bottom; display: inline; } </style>', '');
			}
			return sHtml;
		}
		else {
			return this._waitHtml;
		}
	},

	switchHtmlMode: function ()
	{
		if (!this._builded) return;
		if (this.htmlMode) {
			Dialog.confirm(Lang.ConfirmHtmlToPlain, (function (obj) {
				return function () {
					var sHtml = obj.getHtmlForSwitch();
					sHtml = TextFormatter.htmlToPlain(sHtml);
					obj.setPlain(sHtml);
				}
			})(this));
		}
		else {
			var sPlain = TextFormatter.plainToHtml(this.getPlain());
			this.setHtml(sPlain);
		}
	},

	loadEditArea: function ()
	{
		this._loaded = true;
		this.designModeOn();
	},

	disable: function ()
	{
		if (!(this._designMode && this._loaded && this._shown)) return;

		if (this._disabler == null) {
			this._disabler = CreateChild(document.body, 'div');
		}
		this._disabler.className = '';
		this._resizeDisabler();
		this._disable = true;
	},

	enable: function ()
	{
		if (!(this._designMode && this._loaded && this._shown)) return;

		if (this._disabler != null) {
			this._disabler.className = 'wm_hide';
		}
		this._disable = false;
	},

	_resizeDisabler: function ()
	{
		if (this._disabler != null) {
			var bounds = GetBounds(this.editor);
			$(this._disabler).css({
				'position': 'absolute',
				'left': bounds.Left,
				'top': bounds.Top - 1,
				'width': bounds.Width,
				'height': bounds.Height,
				'background-color': '#fff'
			});
		}
	},

	_switchOnRtl: function ()
	{
		if (window.RTL && Browser.ie && this.area != null &&
				this.area.document != null && this.area.document.body != null) {
			if (Browser.version >= 7) {
				this.area.document.body.dir = 'rtl';
			}
			else {
				this.area.document.dir = 'rtl';
			}
		}
	},

	designModeOn: function ()
	{
		if (this._loaded && this._shown) {
			var doc = this.getDocument();
			try {
				doc.designMode = 'on';
				if (doc.designMode.toLowerCase() == 'on')	{
					this._designMode = true;
				}
			}
			catch (err) {}

			if (Browser.ie9 && doc.body === null) {
				this._designMode = false;
			}

			if (this._designMode && this._designModeStart) {
				this._setWaitHtml();
			}
			else {
				this._designModeStart = true;
				setTimeout('DesignModeOnHandler();', 20);
			}
			this.assignChangesHandler();
		}
	},

	assignChangesHandler: function ()
	{
		if (this._designMode) {
			var doc = this.getDocument();
			$(doc).one('keypress', SetNewMessageScreenChangesHandler);
		}
	},

	getDocument: function ()
	{
		var oDoc = null;
		if (this.area !== null) {
			oDoc = (Browser.ie) ? this.area.document : this.area.contentDocument;
		}
		return oDoc;
	},

	getWindow: function ()
	{
		return (Browser.ie) ? this.area.window : this.area.contentWindow;
	},

	_show: function ()
	{
		if (!this._builded)  return;

		this._colorMode = -1;
		this._mainTbl.className = this._sEditorClassName;
		if (this.editor == null) {
			var url = (window.RTL) ? EditAreaUrl + '?rtl=1' : EditAreaUrl;
			var editor = CreateChild(this._iframesContainer, 'iframe',
				[
					['src', url],
					['frameborder', '0px'],
					['id', 'EditorFrame'],
					['class', 'wm_editor'],
					['style', 'width: 100px;']
				]);
			this.editor = editor;
			this.area = (Browser.ie) ? frames('EditorFrame') : editor;
		}

		this._shown = true;
		if (!this._disable && this._disabler != null) {
			this._disabler.className = 'wm_hide';
		}
	},

	hide: function ()
	{
		if (!this._builded)  return;

		if (this._shown) {
			this._mainTbl.focus();
			this.editor.tabIndex = -1;
		}
		this._shown = false;
		this._mainTbl.className = 'wm_hide';
		this._colorPalette.className = 'wm_hide';
		this._imgUploaderCont.className = 'wm_hide';
	},

	replace: function ()
	{
		if (!this._builded) return;

		if (this._plainEditor != null) {
			var plainEditor = $(this._plainEditor);
			$(this._mainTbl).css({
				'position': 'absolute',
				'top': plainEditor.offset().top -1,
				'left': (window.RTL) ?  'auto' : plainEditor.offset().left - 1,
				'right': (window.RTL) ?  $(window).width()- 1 - plainEditor.outerWidth() - plainEditor.offset().left : 'auto'
			});
		}

		this._resizeDisabler();
	},

	resizeWidth: function (width)
	{
		if (!this._builded) return;

		this._width = width;
		if (this._plainEditor != null) {
			this._plainEditor.style.width = (width - 8) + 'px';
		}
		this._mainTbl.style.width = (width + 3) + 'px';
		if (this.editor != null) {
			this.editor.style.width = (width + 1) + 'px';
		}
	},

	resizeHeight: function (height)
	{
		if (!this._builded) return;

		this._height = height;
		if (this._plainEditor != null) {
			this._plainEditor.style.height = (height - 8) + 'px';
		}
		this._mainTbl.style.height = (height + 2) + 'px';
		if (this.editor != null) {
			var offsetHeight = this._header.offsetHeight;
			if (offsetHeight && (height - offsetHeight) > 0) {
				this.editor.style.height = (height - offsetHeight) + 'px';
			}
		}
	},

	resize: function (width, height)
	{
		this.resizeWidth(width);
		this.resizeHeight(height);
		this.replace();
	},

    _showPlainEditor: function ()
    {
		this._header.className = 'wm_hide';
		this.htmlMode = false;
		this._htmlSwitcher.innerHTML = Lang.SwitchToHTMLMode;
		this.hide();
    },

	getPlain: function ()
	{
		if (this._builded && this._plainEditor != null) {
			return this._plainEditor.value;
		}
		return '';
	},

	setPlain: function (txt)
	{
		if (!this._builded) return;

		this._plainEditor.value = txt;
		this._showPlainEditor();
		SetCounterValueHandler();
	},

	_setWaitHtml: function ()
	{
		if (this._waitHtml != null) {
			this.setHtml(this._waitHtml);
		}
	},

	fillFontSelects: function ()
	{
		var fontName = this._comValue('FontName');
		switch (fontName) {
			case false:
			case null:
			case '':
				fontName = this._defaulFontName;
				break;
			default:
				fontName = fontName.replace(/'/g, '');
				break;
		}
		var fontSize = this._comValue('FontSize');
		switch (fontSize) {
			case '10px':
				fontSize = '1';
				break;
			case '13px':
				fontSize = '2';
				break;
			case '16px':
				fontSize = '3';
				break;
			case '18px':
				fontSize = '4';
				break;
			case '24px':
				fontSize = '5';
				break;
			case '32px':
				fontSize = '6';
				break;
			case '48px':
				fontSize = '7';
				break;
			case null:
			case '':
				fontSize = this._defaulFontSize;
				break;
			default:
				fontSize = parseInt(fontSize, 10);
				if (fontSize > 7) {
					fontSize = 7;
				}
				else if (fontSize < 1) {
					fontSize = 1;
				}
				break;
		}
		if (fontName && fontSize) {
			this._fontFaceSel.value = fontName;
			this._fontSizeSel.value = fontSize;
		}
	},

	_setDefaultFont: function ()
	{
		var doc = null;
		doc = this.getDocument();
		if (doc != null) {
			doc.body.style.fontFamily = this._defaulFontName;
			this._fontFaceSel.value = this._defaulFontName;
			if (!Browser.opera) {
				switch (this._defaulFontSize) {
				case '1':
					doc.body.style.fontSize = '10px';
					break;
				default:
				case '2':
					doc.body.style.fontSize = '13px';
					break;
				case '3':
					doc.body.style.fontSize = '16px';
					break;
				case '4':
					doc.body.style.fontSize = '18px';
					break;
				case '5':
					doc.body.style.fontSize = '24px';
					break;
				case '6':
					doc.body.style.fontSize = '32px';
					break;
				case '7':
					doc.body.style.fontSize = '48px';
					break;
				}
				this._fontSizeSel.value = this._defaulFontSize;
			}
		}
	},

	_blur: function ()
	{
		if (Browser.ie || Browser.opera) {
			this.area.blur();
		}
		else {
			this.editor.contentWindow.blur();
		}
	},

	focus: function ()
	{
		if (this._disable) return;
		HtmlEditorField.onfocus();
		if (!this._designMode) return;
		if (Browser.ie || Browser.opera) {
			this.area.focus();
		}
		else {
			this.editor.contentWindow.focus();
		}
	},

	_setFontCheckers: function ()
	{
		var obj = this;
		if (this.editor.contentWindow && this.editor.contentWindow.addEventListener) {
			this.editor.contentWindow.addEventListener('mousedown', function () {
				HtmlEditorField.onfocus();
			}, false);
			this.editor.contentWindow.addEventListener('mouseup', function () {
				obj.fillFontSelects();
				SetCounterValueHandler();
			}, false);
			this.editor.contentWindow.addEventListener('keyup', function (ev) {
				var
					key = Keys.getCodeFromEvent(ev),
					bUpdate = (key === Keys.f5 || (ev.ctrlKey && key === Keys.r) || key === Keys.ctrl)
				;
				if (!bUpdate) {
					obj.fillFontSelects();
					SetCounterValueHandler();
					if (key === Keys.enter) {
						obj.breakQuotes(ev);
					}
				}
			}, false);
		}
		else if (Browser.ie) {
			this.area.document.onmousedown = function () {
				HtmlEditorField.onfocus();
			};
			this.area.document.onmouseup = function () {
				obj.fillFontSelects();
				SetCounterValueHandler();
			};
			this.area.document.onkeyup = function (ev) {
				var
					key = Keys.getCodeFromEvent(ev),
					bUpdate = (key === Keys.f5 || (ev.ctrlKey && key === Keys.r) || key === Keys.ctrl)
				;
				if (!bUpdate) {
					obj.fillFontSelects();
					SetCounterValueHandler();
					obj.breakQuotes(ev);
				}
			};
		}


		this._plainEditor.onmouseup = function () {
			SetCounterValueHandler();
		};
		this._plainEditor.onkeyup = function () {
			SetCounterValueHandler();
		};
	},

    _showHtmlEditor: function ()
    {
		this._mainTbl.className = this._sEditorClassName;
		this._header.className = 'wm_html_editor_toolbar';
		this.editor.tabIndex = this._tabindex;
		this.htmlMode = true;
		this._htmlSwitcher.innerHTML = Lang.SwitchToPlainMode;
    },

	setHtml: function (txt)
	{
		if (!this._builded) return;

		this._show();

		if (this._designMode) {
			var styles = '';
			if (Browser.ie) {
				styles = '<style> .misspel { background: url(skins/redline.gif) repeat-x bottom; display: inline; } </style>';
				styles += '<style> p { margin-top: 0px; margin-bottom: 0px; } </style>';
				this.area.document.open();
				this.area.document.writeln(styles + txt);
				this.area.document.close();
				this._switchOnRtl();
			}
			else {
				this.area.contentDocument.body.innerHTML = styles + txt;
			}
			this._setDefaultFont();
			this._setFontCheckers();
			this._waitHtml = null;
			this._showHtmlEditor();
			this.resize(this._width, this._height);
			SetCounterValueHandler();
		}
		else {
			this._waitHtml = txt;
			if (this._loaded) {
				this.designModeOn();
			}
		}
	},

	getHtml: function ()
	{
		var
			value = '',
			doc = this.getDocument()
		;
		if (this._builded && this._designMode) {
			value = doc.body.innerHTML;
			if (Browser.ie) {
				value = value.replace(/<\/p>/gi, '<br />').replace(/<p>/gi, '');
			}
			else {
				/*value = value.replace(/<\/pre>/gi, '<br />').replace(/<pre[^>]*>/gi, '');
				value = value.replace(/<\/code>/gi, '<br />').replace(/<code[^>]*>/gi, '');*/
			}
		}
		if (Browser.ie) {
			value = '<FONT size=' + this._defaulFontSize + ' face=' + this._defaulFontName + '>'
				+ value + '</FONT>';
		}
		else if (Browser.ie9) {
			value = '<font size="' + this._defaulFontSize + '" face="' + this._defaulFontName + '">'
				+ value + '</font>';
		}
		else {
			value = '<font face="' + this._defaulFontName + '" size="' + this._defaulFontSize + '">'
				+ value + '</font>';
		}
		return value;
	},

	breakQuotes: function (ev)
	{
		var
			win = this.getWindow(),
			sel = win.getSelection ? win.getSelection() : win.document.selection,
			eFocused = sel.focusNode,
			eBlock = this.getLastBlockQuote(eFocused)
		;
		if (eFocused && eBlock) {
			this.breakBlocks(eFocused, eBlock, sel.focusOffset);
		}
	},

	setCursorPosition: function (eStart, iStartOffset)
	{
		var
			win = this.getWindow(),
			sel = win.getSelection ? win.getSelection() : win.document.selection,
			doc = this.getDocument(),
			range = null
		;
		sel.removeAllRanges();
		range = doc.createRange();
		range.setStart(eStart, iStartOffset);
		range.setEnd(eStart, iStartOffset);
		sel.addRange(range);
	},

	cloneNode: function (eNode)
	{
		var
			$clonedNode = null,
			sTagName = ''
		;
		try {
			$clonedNode = $(eNode).clone();
		}
		catch (er) {
			sTagName = eNode.tagName;
			$clonedNode = $('<' + sTagName + '></' + sTagName + '>');
		}
		return $clonedNode
	},

	breakBlocks: function (eFocused, eBlock, iFocusOffset)
	{
		var
			eCurrent = eFocused,
			eCurChild = null,
			aChildren = [],
			iIndex = 0,
			iLen = 0,
			eChild = null,
			bBeforeCurrent = true,
			$firstParent = null,
			$secondParent = null,
			$first = null,
			$second = null,
			bLast = false,
			bContinue = true,
			$span = null
		;

		while (bContinue && eCurrent.parentNode) {

			$first = $firstParent;
			$second = $secondParent;

			$firstParent = this.cloneNode(eCurrent).empty();
			$secondParent = this.cloneNode(eCurrent).empty();

			aChildren = $(eCurrent).contents();
			iLen = aChildren.length;
			bBeforeCurrent = true;
			if (eCurChild === null) {
				eCurChild = aChildren[iFocusOffset];
			}
			if (iLen === 0) {
				$firstParent = null;
			}
			for (iIndex = 0; iIndex < iLen; iIndex++) {
				eChild = aChildren[iIndex];
				if (eChild === eCurChild) {
					if ($first === null) {
						if (!(iIndex === iFocusOffset && eChild.tagName === 'BR')) {
							$(eChild).appendTo($secondParent);
						}
					}
					else {
						if ($first.html().length > 0) {
							$first.appendTo($firstParent);
						}
						$second.appendTo($secondParent);
					}
					bBeforeCurrent = false;
				}
				else if (bBeforeCurrent) {
					$(eChild).appendTo($firstParent);
				}
				else {
					$(eChild).appendTo($secondParent);
				}
			}

			bLast = (eBlock === eCurrent);
			if (bLast) {
				bContinue = false;
			}

			eCurChild = eCurrent;
			eCurrent = eCurrent.parentNode;
		}

		if ($firstParent !== null && $secondParent !== null) {
			$firstParent.insertBefore($(eBlock));
			$span = $('<span>&nbsp;</span>').insertBefore($(eBlock));
			$('<br>').insertBefore($(eBlock));
			$secondParent.insertBefore($(eBlock));

			$(eBlock).remove();
			this.setCursorPosition($span[0], 0)
		}
	},

	getLastBlockQuote: function (eFocused)
	{
		var
			eCurrent = eFocused,
			eBlock = null
		;

		while (eCurrent && eCurrent.parentNode) {
			if (eCurrent.tagName === 'BLOCKQUOTE') {
				eBlock = eCurrent;
			}
			eCurrent = eCurrent.parentNode;
		}

		return eBlock;
	},

	_comValue: function (cmd)
	{
		if (this._designMode) {
			if (typeof this.area.document != 'undefined') {
				return this.area.document.queryCommandValue(cmd);
			}
			else if (typeof this.area.contentDocument != 'undefined') {
				return this.area.contentDocument.queryCommandValue(cmd, false, null);
			}
		}
		return '';
	},

	_execCom: function (cmd, param)
	{
		if (this._designMode) {
			if (!Browser.opera) {
				this.focus();
			}
			if (Browser.ie) {
				if (param) {
					this.area.document.execCommand(cmd, false, param);
				}
				else {
					this.area.document.execCommand(cmd);
				}
			}
			else {
				if (param) {
					this.area.contentDocument.execCommand(cmd, false, param);
				}
				else {
					this.area.contentDocument.execCommand(cmd, false, null);
				}
			}
			if (!Browser.opera) {
				this.focus();
			}
		}
	},

	createLink: function ()
	{
		if (Browser.ie) {
			this._execCom('CreateLink');
		}
		else if (this._designMode) {
			var bounds, top;
			bounds = GetBounds(this._btnInsertLink);
			top = bounds.Top + bounds.Height;
			HtmlEditorField.onfocus();
			window.open('linkcreator.html', 'ha_fullscreen',
				'toolbar=no,menubar=no,personalbar=no,width=380,height=100,left=' + bounds.Left + ',top=' + top +
				'scrollbars=no,resizable=no,modal=yes,status=no');
		}
	},

	createLinkFromWindow: function (url)
	{
		this._execCom('createlink', url);
	},

	unlink: function ()
	{
		if (Browser.ie) {
			this._execCom('Unlink');
		}
		else if (this._designMode) {
			this._execCom('unlink');
		}
	},

	insertImage: function ()
	{
		if (!WebMail.Settings.allowInsertImage) return;
		this._imgUploaderCont.className = 'wm_image_uploader_cont';
		this._rebuildUploadForm();
		var bounds = GetBounds(this._btnInsertImage);
		var iuStyle = this._imgUploaderCont.style;
        iuStyle.top = bounds.Top + bounds.Height + 'px';
        if (window.RTL) {
            iuStyle.right = GetWidth() - (bounds.Left + bounds.Width) + 'px';
        }
        else {
            iuStyle.left = bounds.Left + 'px';
        }
	},

	insertImageFromWindow: function (url)
	{
		if (!WebMail.Settings.allowInsertImage) return;
		this._imgUploaderCont.className = 'wm_hide';
		if (Browser.ie) {
			this._execCom('InsertImage', url);
		}
		else if (this._designMode) {
			this._execCom('insertimage', url);
		}
	},

	insertOrderedList: function ()
	{
		this._execCom('InsertOrderedList');
	},

	insertUnorderedList: function ()
	{
		this._execCom('InsertUnorderedList');
	},

	insertHorizontalRule: function ()
	{
		this._execCom('InsertHorizontalRule');
	},

	fontName: function (name)
	{
		this._fontFaceSel.value = name;
		this._execCom('FontName', name);
	},

	fontSize: function (size)
	{
		this._fontSizeSel.value = size;
		this._execCom('FontSize', size);
	},

	bold: function ()
	{
		this._execCom('Bold');
	},

	italic: function ()
	{
		this._execCom('Italic');
	},

	underline: function ()
	{
		this._execCom('Underline');
	},

	justifyLeft: function ()
	{
		this._execCom('JustifyLeft');
	},

	justifyCenter: function ()
	{
		this._execCom('JustifyCenter');
	},

	justifyRight: function ()
	{
		this._execCom('JustifyRight');
	},

	justifyFull: function ()
	{
		this._execCom('JustifyFull');
	},

	chooseColor: function (mode)
	{
		if (this._designMode) {
			if (this._colorMode == mode) {
				this._colorPalette.className = 'wm_hide';
				this._colorChoosing = 0;
				this._colorMode = -1;
			}
			else {
				this._colorMode = mode;
				var bounds = GetBounds((mode == 0) ? this._btnFontColor : this._btnBgColor);
				this._colorPalette.style.left = bounds.Left + 'px';
				this._colorPalette.style.top = bounds.Top + bounds.Height + 'px';
				this._colorPalette.className = 'wm_color_palette';

				this._colorPalette.style.height = 'auto';
				this._colorPalette.style.width = 'auto';
				if (Browser.ie) {
					this._range = this.area.document.selection.createRange();
					this._colorPalette.style.height = this._colorTable.offsetHeight + 8 + 'px';
					this._colorPalette.style.width = this._colorTable.offsetWidth + 8 + 'px';
				}
				else {
					this._colorPalette.style.height = this._colorTable.offsetHeight + 8 + 'px';
					this._colorPalette.style.width = this._colorTable.offsetWidth + 'px';
				}
				this._colorChoosing = 2;
			}
			if (Browser.ie9) {
				this.storeSelectionPosition();
			}
		}
	},

	selectFontColor: function (color)
	{
		var sCmd;
		if (this._designMode) {
			HtmlEditorField.onfocus();
			if (this._colorMode === 0) {
				sCmd = 'ForeColor';
			}
			else {
				if (Browser.ie || Browser.ie9) {
					sCmd = 'BackColor';
				}
				else {
					sCmd = 'hilitecolor';
				}
			}
			if (Browser.ie) {
				this._range.select();
				this._range.execCommand(sCmd, false, color);
			}
			else {
				if (Browser.ie9) {
					this.restoreSelectionPosition();
				}
				this._execCom(sCmd, color);
			}
			this.area.focus();
			this._colorPalette.className = 'wm_hide';
			this._colorMode = -1;
		}
	},

	storeSelectionPosition: function ()
	{
		var win, selection;
		if (Browser.ie) {
			this._range = this.area.document.selection.createRange();
			this._bookmark = this._range.getBookmark();
		}
		else {
			win = this.getWindow();
			this.focus();
			selection = win.getSelection();
			this._selectionPosition = null;
			if (selection.anchorNode) {
				if (selection.focusOffset > selection.anchorOffset) {
					this._selectionPosition = {
						startNode: selection.anchorNode,
						startOffset: selection.anchorOffset,
						endNode: selection.focusNode,
						endOffset: selection.focusOffset
					}
				}
				else {
					this._selectionPosition = {
						startNode: selection.focusNode,
						startOffset: selection.focusOffset,
						endNode: selection.anchorNode,
						endOffset: selection.anchorOffset
					}
				}
			}
		}
	},

	restoreSelectionPosition: function ()
	{
		var
			win = this.getWindow(),
			doc = this.getDocument(),
			range,
			selection
		;
		if (Browser.ie){
			if (this._selectionPosition) {
				range = doc.body.createTextRange();
				range.collapse(true);
				range.select();
			}
			else {
				range = doc.body.createTextRange();
				range.moveToBookmark(this._bookmark);
				range.select ();
			}
		}
		else {
			selection = win.getSelection();
			selection.removeAllRanges();
			if (this._selectionPosition) {
				range = doc.createRange();
				range.setStart(this._selectionPosition.startNode, this._selectionPosition.startOffset);
				range.setEnd(this._selectionPosition.endNode, this._selectionPosition.endOffset);
				selection.addRange(range);
			}
		}
	},

	changeLang: function ()
	{
		if (!this._builded) return;
		for (var key in this._buttons) {
			var but = this._buttons[key];
			if (typeof(but) === 'function') continue;
			if (but.imgDiv) {
				but.imgDiv.title = Lang[but.langField];
			}
		}
	},

	_buttons: {
		'link': {x: 0 * X_ICON_SHIFT, y: 0 * X_ICON_SHIFT, langField: 'InsertLink'},
		'unlink': {x: 1 * X_ICON_SHIFT, y: 0 * X_ICON_SHIFT, langField: 'RemoveLink'},
		'number': {x: 2 * X_ICON_SHIFT, y: 0 * X_ICON_SHIFT, langField: 'Numbering'},
		'list': {x: 3 * X_ICON_SHIFT, y: 0 * X_ICON_SHIFT, langField: 'Bullets'},
		'hrule': {x: 4 * X_ICON_SHIFT, y: 0 * X_ICON_SHIFT, langField: 'HorizontalLine'},
		'bld': {x: 5 * X_ICON_SHIFT, y: 0 * X_ICON_SHIFT, langField: 'Bold'},
		'itl': {x: 6 * X_ICON_SHIFT, y: 0 * X_ICON_SHIFT, langField: 'Italic'},
		'undrln': {x: 7 * X_ICON_SHIFT, y: 0 * X_ICON_SHIFT, langField: 'Underline'},
		'lft': {x: 8 * X_ICON_SHIFT, y: 0 * X_ICON_SHIFT, langField: 'AlignLeft'},
		'cnt': {x: 9 * X_ICON_SHIFT, y: 0 * X_ICON_SHIFT, langField: 'Center'},
		'rt': {x: 10 * X_ICON_SHIFT, y: 0 * X_ICON_SHIFT, langField: 'AlignRight'},
		'full': {x: 11 * X_ICON_SHIFT, y: 0 * X_ICON_SHIFT, langField: 'Justify'},
		'font_color': {x: 0 * X_ICON_SHIFT, y: 1 * X_ICON_SHIFT, langField: 'FontColor'},
		'bg_color': {x: 1 * X_ICON_SHIFT, y: 1 * X_ICON_SHIFT, langField: 'Background'},
		'insert_image': {x: 10 * X_ICON_SHIFT, y: 1 * X_ICON_SHIFT, langField: 'InsertImage'}
	},

	_addToolBarItem: function (parent, imgIndex)
	{
		var child = CreateChild(parent, 'a', [['href', 'javascript:void(0);']]);
		var cdiv = CreateChild(child, 'span');

		cdiv.className = 'wm_toolbar_item';
		cdiv.onmouseover = function () {
			this.className = 'wm_toolbar_item_over';
		};
		cdiv.onmouseout = function () {
			this.className = 'wm_toolbar_item';
		};
		var desc = this._buttons[imgIndex];
		var imgDiv = CreateChild(cdiv, 'span', [['title', Lang[desc.langField]],
			['style', 'background-position: -' + desc.x + 'px -' + desc.y + 'px']]);
		this._buttons[imgIndex].imgDiv = imgDiv;

		return cdiv;
	},

	_addToolBarSeparate: function (parent)
	{
		var child = CreateChild(parent, 'span');
		child.className = 'wm_toolbar_separate';
		return child;
	},

	clickBody: function ()
	{
		if (!this._builded) return;

		switch (this._colorChoosing) {
			case 2:
				this._colorChoosing = 1;
				break;
			case 1:
				this._colorChoosing = 0;
				this._colorPalette.className = 'wm_hide';
				this._colorMode = -1;
				break;
		}
	},

	setCurrentColor: function (color)
	{
		this._currentColor.style.backgroundColor = color;
	},

	_rebuildUploadForm: function ()
	{
		if (!WebMail.Settings.allowInsertImage) return;
		var form = this._imgUploaderForm;
		CleanNode(form);
		var inp = CreateChild(form, 'input', [['type', 'hidden'], ['name', 'inline_image'], ['value', '1']]);
		var tbl = CreateChild(form, 'table');
		var tr = tbl.insertRow(0);
		var td = tr.insertCell(0);
		var span = CreateChild(td, 'span');
		span.innerHTML = Lang.ImagePath + ': ';
		CreateChild(td, 'br');

		inp = CreateChild(td, 'input', [['type', 'file'], ['class', 'wm_file'], ['name', 'qqfile']]);
		this._imgUploaderFile = inp;

		td = tr.insertCell(1);
		inp = CreateChild(td, 'input', [['type', 'submit'], ['class', 'wm_button'], ['value', Lang.ImageUpload]]);
		CreateChild(td, 'br');
		inp = CreateChild(td, 'input', [['type', 'button'], ['class', 'wm_button'], ['value', Lang.Cancel]]);
		var obj = this;
		inp.onclick = function () {
			obj._imgUploaderCont.className = 'wm_hide';
		};
	},

	_buildUploadForm: function ()
	{
		CreateChild(document.body, 'iframe name="UploadFrame"', [['src', EmptyHtmlUrl], ['id', 'UploadFrame'], ['class', 'wm_hide']]);
		this._imgUploaderCont = CreateChild(document.body, 'div', [['class', 'wm_hide']]);
		this._imgUploaderForm = CreateChild(this._imgUploaderCont, 'form', [['action', ImageUploaderUrl], ['method', 'post'], ['enctype', 'multipart/form-data'], ['target', 'UploadFrame'], ['id', 'ImageUploadForm']]);
		var obj = this;
		this._imgUploaderForm.onsubmit = function () {
			if (!WebMail.Settings.allowInsertImage) return false;
			if (obj._imgUploaderFile.value.length == 0) return false;
			var ext = GetExtension(obj._imgUploaderFile.value);
			switch (ext) {
				case 'jpg':
				case 'jpeg':
				case 'jpe':
				case 'png':
				case 'bmp':
				case 'gif':
				case 'tif':
				case 'tiff':
					break;
				default:
					Dialog.alert(Lang.WarningImageUpload);
					return false;
			}
			return true;
		};
	},

	_buildColorPalette: function ()
	{
		var div = CreateChild(document.body, 'div');
		div.className = 'wm_hide';
		this._colorPalette = div;
		var tbl = CreateChild(div, 'table');
		this._colorTable = tbl;
		var rowIndex = 0;
		var colors = ['#000000', '#333333', '#666666', '#999999', '#CCCCCC', '#FFFFFF', '#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#00FFFF', '#FF00FF'];
		var colorIndex = 0;
		var symbols = ['00', '33', '66', '99', 'CC', 'FF'];
		var obj = this;
		for (var jStart = 0; jStart < 6; jStart += 3) {
			for (var i = 0; i < 6; i++) {
				var tr = tbl.insertRow(rowIndex++);
				var cellIndex = 0;
				var td;
				if (rowIndex == 1) {
					td = tr.insertCell(cellIndex++);
					td.rowSpan = 12;
					td.className = 'wm_current_color_td';
					this._currentColor = CreateChild(td, 'div');
					this._currentColor.className = 'wm_current_color';
				}
				td = tr.insertCell(cellIndex++);
				td.className = 'wm_palette_color';
				td = tr.insertCell(cellIndex++);
				td.bgColor = colors[colorIndex++];
				td.className = 'wm_palette_color';
				td.onmouseover = function () {
					obj.setCurrentColor(this.bgColor);
				};
				td.onclick = function () {
					obj.selectFontColor(this.bgColor);
				};
				td = tr.insertCell(cellIndex++);
				td.className = 'wm_palette_color';
				for (var j = jStart; j < jStart + 3; j++) {
					for (var k = 0; k < 6; k++) {
						td = tr.insertCell(cellIndex++);
						td.bgColor = '#' + symbols[j] + symbols[k] + symbols[i];
						td.className = 'wm_palette_color';
						td.onmouseover = function () {
							obj.setCurrentColor(this.bgColor);
						};
						td.onclick = function () {
							obj.selectFontColor(this.bgColor);
						};
					}
				}
			}
		}
	}, //_buildColorPalette

	build: function ()
	{
		if (this._builded) return;
		var tbl = CreateChild(document.body, 'table');
		this._mainTbl = tbl;
		tbl.className = 'wm_hide';
		var tr = tbl.insertRow(0);
		this._header = tr;
		tr.className = 'wm_hide';
		var td = tr.insertCell(0);
		this._btnInsertLink = this._addToolBarItem(td, 'link');
		var obj = this;
		this._btnInsertLink.onclick = function () {
			obj.createLink();
		};
		var div = this._addToolBarItem(td, 'unlink');
		div.onclick = function () {
			obj.unlink();
		};
		this._btnInsertImage = this._addToolBarItem(td, 'insert_image');
		this._btnInsertImage.onclick = function () {
			obj.insertImage();
		};
		div = this._addToolBarItem(td, 'number');
		div.onclick = function () {
			obj.insertOrderedList();
		};
		div = this._addToolBarItem(td, 'list');
		div.onclick = function () {
			obj.insertUnorderedList();
		};
		div = this._addToolBarItem(td, 'hrule');
		div.onclick = function () {
			obj.insertHorizontalRule();
		};
		div = this._addToolBarSeparate(td);

		div = CreateChild(td, 'div');
		div.className = 'wm_toolbar_item';
		var fontFaceSel = CreateChild(div, 'select');
		fontFaceSel.className = 'wm_input wm_html_editor_select';
		for (var i = 0; i < Fonts.length; i++) {
			var opt = CreateChild(fontFaceSel, 'option', [['value', Fonts[i]]]);
			opt.innerHTML = Fonts[i];
			if (Fonts[i] == this._defaulFontName) {
				opt.selected = true;
			}
		}
		fontFaceSel.onchange = function () {
			obj.fontName(this.value);
		};
		this._fontFaceSel = fontFaceSel;
		div.style.margin = '0px';

		div = CreateChild(td, 'div');
		div.className = 'wm_toolbar_item';
		var fontSizeSel = CreateChild(div, 'select');
		fontSizeSel.className = 'wm_input wm_html_editor_select';
		for (i = 1; i < 8; i++) {
			opt = CreateChild(fontSizeSel, 'option', [['value', i]]);
			opt.innerHTML = i;
			if (i == this._defaulFontSize) {
				opt.selected = true;
			}
		}
		fontSizeSel.onchange = function () {
			obj.fontSize(this.value);
		};
		this._fontSizeSel = fontSizeSel;
		div.style.margin = '0px';

		div = this._addToolBarSeparate(td);
		div = this._addToolBarItem(td, 'bld');
		div.onclick = function () {
			obj.bold();
		};
		div = this._addToolBarItem(td, 'itl');
		div.onclick = function () {
			obj.italic();
		};
		div = this._addToolBarItem(td, 'undrln');
		div.onclick = function () {
			obj.underline();
		};
		div = this._addToolBarItem(td, 'lft');
		div.onclick = function () {
			obj.justifyLeft();
		};
		div = this._addToolBarItem(td, 'cnt');
		div.onclick = function () {
			obj.justifyCenter();
		};
		div = this._addToolBarItem(td, 'rt');
		div.onclick = function () {
			obj.justifyRight();
		};
		div = this._addToolBarItem(td, 'full');
		div.onclick = function () {
			obj.justifyFull();
		};
		this._btnFontColor = this._addToolBarItem(td, 'font_color');
		this._btnFontColor.onclick = function () {
			obj.chooseColor(0);
		};
		this._btnBgColor = this._addToolBarItem(td, 'bg_color');
		this._btnBgColor.onclick = function () {
			obj.chooseColor(1);
		};

		tr = tbl.insertRow(1);
		td = tr.insertCell(0);
		td.className = 'wm_html_editor_cell';
		td.colSpan = 1;
		this._iframesContainer = td;

		this._buildColorPalette();
		this._buildUploadForm();
		this._builded = true;
	}, //build

	updateEditorHandlers : function (eventFunction, eventsList)
	{
		var doc = this.getDocument();
		for (var i = 0; i < eventsList.length; i++)
		{
			$addHandler(doc, eventsList[i],  eventFunction);
		}
	}
};

/* html editor handlers */
function EditAreaLoadHandler() {
	HtmlEditorField.loadEditArea();
}

function CreateLinkHandler(url) {
	HtmlEditorField.createLinkFromWindow(url);
}

function InsertImageHandler(url) {
	HtmlEditorField.insertImageFromWindow(url);
}

function DesignModeOnHandler() {
	HtmlEditorField.designModeOn();
}
/*-- html editor handlers */

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}