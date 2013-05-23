/*
 * Classes:
 *  CPopupAutoFilling(requestHandler, selectHandler)
 *  CPopupContacts(requestHandler, selectHandler)
 */

function CPopupAutoFilling(requestHandler, selectHandler)
{
	this._suggestInput = null;

	this._requestHandler = requestHandler;
	this._selectHandler = selectHandler;

	this._popup = null;
	this._shown = false;

	this._keyword = '';
	this._requestKeyword = '';
	this._pickPos = -1;
	this._lines = Array();

	this._timeOut = null;
	
	this.fKeyUp = (function (self) {
		return function (ev) {
			self.onKeyUp(ev);
		}
	})(this);
	this.fBlur = (function (self) {
		return function () {
			self._requestKeyword = '';
		};
	})(this)

	this.build();
}

CPopupAutoFilling.prototype =
{
	show: function ()
	{
		this._popup.className = 'wm_auto_filling_cont';
		this._shown = true;
		var self = this;
		$(document).one('click', function(){
			self.hide();
		});
		this.Replace();
	},
	
	hide: function ()
	{
		this._keyword = '';
		this._popup.className = 'wm_hide';
		this._shown = false;
	},
	
	SetSuggestInput: function (suggestInput)
	{
		this.hide();
		if (this._suggestInput != null) {
			$(this._suggestInput).unbind('keyup', this.fKeyUp);
			$(this._suggestInput).unbind('blur', this.fBlur);
		}
		
		if (this._suggestInput !== suggestInput) {
			this._requestKeyword = '';
		}
		
		this._suggestInput = suggestInput;
		suggestInput.setAttribute('autocomplete', 'off');  
		$(this._suggestInput).bind('keyup', this.fKeyUp);
		$(this._suggestInput).bind('blur', this.fBlur);
		
		this.moveCaretToEnd();
	},
	
	moveCaretToEnd: function ()
	{
		this._suggestInput.focus();
		if (this._suggestInput.createTextRange) {
			//ie6-8
			var textRange = this._suggestInput.createTextRange();
			textRange.collapse(false);
			textRange.select();
		}
		if (this._suggestInput.setSelectionRange)
		{
			// ff, opera, ie9
			// Double the length because Opera is inconsistent about whether a carriage return is one character or two. Sigh.
			var len = this._suggestInput.value.length * 2;
			this._suggestInput.setSelectionRange(len, len);
		}
	},
	
	Replace: function ()
	{
		if (!this._suggestInput) {
			return;
		}
		
		var
			suggestInput = $(this._suggestInput),
			position = suggestInput.offset(),
			popup = $(this._popup)
		;
		popup.css({
			'top': suggestInput.outerHeight(true) + position.top,
			'left': position.left,
			'width': suggestInput.innerWidth(),
			'height': 'auto'
		});
		/* set popup width and height in absolute value for hiding select under popup in ie6 */

		var iMaxHeight = $(window).height() / 2;
		if (popup.height() > iMaxHeight) {
			popup.css('height', Math.round(iMaxHeight));
		}
	},

	fill: function (aItems, sKeyword, sLastPhrase)
	{
		if (sKeyword === this._requestKeyword) {
			this._keyword = sKeyword;
			this._requestKeyword = '';
			sLastPhrase = sLastPhrase || '';
			this._fill(aItems, false, sLastPhrase);
		}
	},

	_fill: function (aItems, bCloseIcon, sLastPhrase)
	{
		CleanNode(this._popup);
		MakeOpaqueOnSelect(this._popup);
		
		if (bCloseIcon) {
			var eCloseIcon = CreateChild(this._popup, 'div', [['class', 'wm_popular_contacts_image wm_control']]);
			eCloseIcon.onclick = (function (obj) {
				return function () {
					obj.hide();
				};
			})(this);
		}

		this._pickPos = -1;
		this._lines = [];
		
		this._pickPos = -1;
		this._lines = [];

		var
			iRowIndex = 0
		;
		for (var i = 0, c = aItems.length; i < c; i++) {
			if (null !== (new RegExp(aItems[i].clearEmail, 'gi')).exec(this._suggestInput.value)) {
				continue;
			}
            var div = CreateChild(this._popup, 'div');
            var innerHtml = '';
            if (aItems[i].isGroup) {
                innerHtml += '<span class="wm_inbox_lines_group">&nbsp;</span>';
            }
            div.innerHTML = innerHtml + aItems[i].displayText;
            
            div.ContactGroup = aItems[i];
            div.Number = iRowIndex;
			div.onmouseover = (function (obj) {
				return function () {
					obj.PickLine(this.Number);
				};
			})(this);
			div.onmouseout = (function (obj) {
				return function () {
					this.className = '';
					if (obj._pickPos == this.Number) {
						obj._pickPos = -1;
					}
				};
			})(this);
			div.onclick = (function (obj) {
				return function () {
					obj.SelectLine(this);
				};
			})(this);
            this._lines[iRowIndex] = div;
			iRowIndex++;
		}
		
		if (sLastPhrase.length > 0) {
			var eLastPhrase = CreateChild(this._popup, 'div', [['class', 'wm_secondary_info']]);
			eLastPhrase.innerHTML = sLastPhrase;
		}
		
        if (iRowIndex > 0) {
            this.show();
        }
	},
	
	GetKeyword: function ()
	{
		var arr = this._suggestInput.value.replace(/;/g, ',').split(',');
		return Trim(arr[arr.length - 1]);
	},
	
	SetSuggestions: function (sSuggestion)
	{
		var
			iLastKeyLen = this.GetKeyword().length,
			iEndPos = this._suggestInput.value.length - iLastKeyLen
		;
		if (iEndPos > 0) {
			if (iLastKeyLen > 0) {
				this._suggestInput.value = this._suggestInput.value.slice(0, iEndPos);
				this._suggestInput.value += sSuggestion;
			}
		}
		else {
			this._suggestInput.value = sSuggestion;
		}
	},
	
	SelectLine: function (obj)
	{
		this.hide();
		this.SetSuggestions(obj.ContactGroup.replaceText) ;
		this._pickPos = -1;
		this._suggestInput.focus();
		this.moveCaretToEnd(); 
		this._selectHandler.call(obj);
	},
	
	PickLine: function (posInt)
	{
		if (this._pickPos != -1) {
			this._lines[this._pickPos].className = '';
		}
		this._pickPos = posInt;
		if (this._pickPos != -1) {
			this._lines[this._pickPos].className = 'wm_auto_filling_chosen';
		}
	},
	
	onKeyUp: function (ev)
	{
		var key = Keys.getCodeFromEvent(ev);
		switch (key) {
			case Keys.enter:
				if (this._pickPos != -1) {
					var td = this._lines[this._pickPos];
					this.SelectLine(td);
				}
				break;
			case Keys.up:
				if (this._pickPos > -1) {
					this.PickLine(this._pickPos - 1);
				}
				break;
			case Keys.down:
				if (this._pickPos < (this._lines.length - 1)) {
					this.PickLine(this._pickPos + 1);
				}
				break;
			default:
				var keyword = this.GetKeyword();
				if (this.CheckRequestKeyword(keyword)) {
					if (this._timeOut != null) {
						clearTimeout(this._timeOut);
					}
					var obj = this;
					this._timeOut = setTimeout ( function () {
						obj.RequestKeyword(); 
					}, 500 );
				}
				else if (keyword.length == 0) {
					this.hide();
				}
				break;
		}
	},
	
	CheckRequestKeyword: function (keyword)
	{
		if (keyword.length > 0 && this._keyword != keyword) {
			if (this._requestKeyword.length > 0) {
				var reg = new RegExp(this._requestKeyword.PrepareForRegExp(), 'gi');
				var res = reg.exec(keyword);
				if (res != null && res.index == 0) {
					return false;
				}
				else {
					return true;
				}
			}
			return true;
		}
		else {
			return false;
		}
	},
	
	RequestKeyword: function ()
	{
		var keyword = this.GetKeyword();
		if (this.CheckRequestKeyword(keyword)) {
			this._requestKeyword = keyword;
			this._requestHandler.call({Keyword: keyword});
		}
	},

	build: function ()
	{
		this._popup = CreateChild(document.body, 'div');
		this._popup.style.position = 'absolute';
		this.hide();
	}
};

function CPopupContacts(requestHandler, selectHandler, fOnInputValueChange)
{
	this._suggestInput = null;
	this._suggestControl = null;

	this._requestHandler = requestHandler;
	this._selectHandler = selectHandler;
	this._fOnInputValueChange = fOnInputValueChange;

	this._popup = null;
	this._shown = false;
	this._controlClick = false;

	this._pickPos = -1;
	this._lines = Array();

	this._timeOut = null;

	this.build();
}

CPopupContacts.prototype =
{
	ControlClick: function (suggestInput, suggestControl)
	{
		var self = this;
		
		if (this._shown && this._suggestInput == suggestInput) {
			this.hide();
			return;
		} else {
			this._controlClick = true;
			this._suggestInput = suggestInput;
			this._suggestControl = suggestControl;
			this._requestHandler.call({Keyword: ''});
			$(this._suggestInput).one('keydown', function () {
				self.hide();
			});
		}
	},

	show: function ()
	{
		this._popup.className = 'wm_popular_contacts_cont';
		this._shown = true;
		this.Replace();
	},
	
	hide: function ()
	{
		this._popup.style.width = 'auto'; // for opera
		this._popup.style.height = 'auto'; // for opera
		this._popup.className = 'wm_hide';
		this._shown = false;
		this._controlClick = false;
	},
	
	Replace: function ()
	{
		if (!this._shown) {
			return;
		}
		var
			position = $(this._suggestControl).offset(),
			popup = $(this._popup),
			width = 0
		;
		popup.css({
			'top': $(this._suggestControl).outerHeight(true) + position.top,
			'left': position.left,
			'width': 'auto',
			'height': 'auto'
		});
		/* set popup width and height in absolute value for hiding select under popup in ie6 */

		var iMaxHeight = $(window).height() / 2;
		var iMaxWidth = $(window).width() / 2;
		if (popup.height() > iMaxHeight) {
			popup.css('height', Math.round(iMaxHeight));
			width += popup.outerWidth() + popup.width() - this._popup.clientWidth;
		}
		if (width > iMaxWidth) {
			width = iMaxWidth;
		}
		if (width > 0) {
			popup.css('width', width);
		}

		if (window.RTL) {
			$(this._popup).css({
				'left': $(this._suggestControl).offset().left + $(this._suggestControl).outerWidth()
			});
		}
	},
	
	clickBody: function (ev)
	{
		if (this._shown && !this._controlClick) {
			ev = ev ? ev : window.event;
			var elem = (Browser.mozilla) ? ev.target : ev.srcElement;
			while (elem && elem.tagName != 'DIV' && elem.parentNode) {
				elem = elem.parentNode;
			}
			if (elem && elem.className != 'wm_popular_contacts_cont' && elem.parentNode) {
				elem = elem.parentNode;
			}
			if (elem && elem.className != 'wm_popular_contacts_cont') {
				this.hide();
			}
		}
		this._controlClick = false;
	},

	fill: function (aItems)
	{
		this._fill(aItems, true, '');
	},
	
	SetSuggestions: function (sSuggestion)
	{
		var sInputValue = $.trim(this._suggestInput.value).replace(/,$/, '');
		
		sSuggestion = $.trim(sSuggestion);
		if (sSuggestion.length > 0) {
			if (sInputValue.length > 0) {
				this._suggestInput.value = sInputValue + ', ' + sSuggestion;
			}
			else {
				this._suggestInput.value = sSuggestion;
			}
			this._fOnInputValueChange.call();
		}
	},
	
	SelectLine: function (obj)
	{
		this.SetSuggestions(obj.ContactGroup.replaceText);
		this.moveCaretToEnd();
		this._selectHandler.call(obj);
	},
	
	PickLine: function (posInt)
	{
		if (this._pickPos != -1) {
			this._lines[this._pickPos].className = '';
		}
		this._pickPos = posInt;
		if (this._pickPos != -1) {
			this._lines[this._pickPos].className = 'wm_auto_filling_chosen';
		}
	},
	
	build: function ()
	{
		this._popup = CreateChild(document.body, 'div');
		this._popup.style.position = 'absolute';
		this.hide();
	}
};

CPopupContacts.prototype._fill = CPopupAutoFilling.prototype._fill;
CPopupContacts.prototype.moveCaretToEnd = CPopupAutoFilling.prototype.moveCaretToEnd;

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
