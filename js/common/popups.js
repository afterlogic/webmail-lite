/*
 * Classes:
 *  CPopupMenu(ePopup, eControl, sMenuClass, eMove, eTitle, sMoveClass, sMovePressClass, sTitleClass, sTitleOverClass)
 *  CPopupMenus()
 *  CSearchForm(bigSearchForm, smallSearchForm, downButton, upButton, bigLookFor, smallLookFor, centralPaneView)
 *  CFadeEffect(name)
 *  CInformation(cont, cls)
 *  CReport()
 * Prototypes:
 *  ReportPrototype
 */

var POPUP_SHOWED = 2;
var POPUP_READY = 1;
var POPUP_HIDDEN = 0;

function CPopupMenu(ePopup, eControl, sMenuClass, eMove, eTitle, sMoveClass, sMovePressClass,
	sTitleClass, sTitleOverClass, sControlClass, sControlOverClass)
{
	this.bShowDisable = false;
	this.disable = false;

	this.eControl = eControl;
	this.eMove = eMove;
	this.ePopup = ePopup;
	this.eTitle = eTitle;

	this.sControlClass = (sControlClass === undefined) ? sTitleClass : sControlClass;
	this.sControlOverClass = (sControlClass === undefined) ? sTitleOverClass : sControlOverClass;
	this.sMenuClass = sMenuClass;
	this.sMoveClass = sMoveClass;
	this.sMovePressClass = sMovePressClass;
	this.sTitleClass = sTitleClass;
	this.sTitleOverClass = sTitleOverClass;
}

CPopupMenu.prototype = {
	disableToShow: function ()
	{
		this.hidePopup();
		this.bShowDisable = true;
		this.eTitle.className = this.sTitleClass + ' wm_toolbar_item_disabled';
		$(this.eControl).find('span.wm_control_icon').css('cursor', 'default');
		this.eControl.onmouseover = function () {};
		this.eControl.onmouseout = function () {};
		this.eTitle.onmouseover = function () {};
		this.eTitle.onmouseout = function () {};
	},
	
	enableToShow: function ()
	{
		this.bShowDisable = false;
		this.eTitle.className = this.sTitleClass;
		$(this.eControl).find('span.wm_control_icon').css('cursor', 'pointer');
		this.hidePopup();
	},
	
	show: function ()
	{
		this.eControl.className = this.sControlClass;
		this.eMove.className = this.sMoveClass;
		this.eTitle.className = this.sTitleClass;
	},

	hide: function ()
	{
		this.eControl.className = 'wm_hide';
		this.eMove.className = 'wm_hide';
		this.ePopup.className = 'wm_hide';
		this.eTitle.className = 'wm_hide';
	},

	hidePopup: function ()
	{
		if (!this.bShowDisable)
		{
			this.ePopup.className = 'wm_hide';
			if (this.sMoveClass && this.sMoveClass != '' && this.eMove.className != 'wm_hide')
				this.eMove.className = this.sMoveClass;
			var
				obj = this,
				bDis = this.disable || this.bShowDisable
			;
			if (this.sTitleOverClass != '') {
				this.eControl.onmouseover = function () {
					obj.eTitle.className = obj.sTitleOverClass + (bDis ? ' wm_toolbar_item_disabled' : '');
					obj.eControl.className = obj.sControlOverClass;
				};
				this.eControl.onmouseout = function () {
					obj.eTitle.className = obj.sTitleClass + (bDis ? ' wm_toolbar_item_disabled' : '');
					obj.eControl.className = obj.sControlClass;
				};
				this.eTitle.onmouseover = function () {
					obj.eTitle.className = obj.sTitleOverClass + (bDis ? ' wm_toolbar_item_disabled' : '');
				};
				this.eTitle.onmouseout = function () {
					obj.eTitle.className = obj.sTitleClass + (bDis ? ' wm_toolbar_item_disabled' : '');
				};
			}
		}
	},

	showPopup: function ()
	{
		if (!this.bShowDisable)
		{
			this.ePopup.className = this.sMenuClass;
			if (this.sTitleClass && this.sTitleClass != '') {
				this.eControl.className = this.sTitleClass;
				this.eTitle.className = this.sTitleClass + (this.disable ? ' wm_toolbar_item_disabled' : '');
			}
			if (this.sMovePressClass && this.sMovePressClass != '')
				this.eMove.className = this.sMovePressClass;
			var borders = 1;
			if (this.sTitleOverClass != '') {
				this.eControl.onmouseover = function () {};
				this.eControl.onmouseout = function () {};
				this.eTitle.onmouseover = function () {};
				this.eTitle.onmouseout = function () {};
				borders = 2;
			}
			this._replace(borders);
			this._resize();
		}
	},

	_replace: function (borders)
	{
		var bounds = GetBounds(this.eMove);
		if (!window.RTL) {
			this.ePopup.style.left = bounds.Left + 'px';
		}
		this.ePopup.style.top = bounds.Top + bounds.Height + 'px';

		this.ePopup.style.width = 'auto';
		var pOffsetWidth = this.ePopup.offsetWidth;
		var cOffsetWidth = this.eControl.offsetWidth;
		var tOffsetWidth = (this.eControl == this.eTitle) ? 0 : this.eTitle.offsetWidth;
		this.ePopup.style.width = (pOffsetWidth < (cOffsetWidth + tOffsetWidth - borders)) ?
			(cOffsetWidth + tOffsetWidth - borders) + 'px' : (pOffsetWidth + borders) + 'px';

		/* rtl */
		if (window.RTL) {
			this.ePopup.style.left = (bounds.Left + bounds.Width - this.ePopup.offsetWidth) + 'px';
		}
	},

	_resize: function ()
	{
		this.ePopup.style.height = 'auto';
		var pOffsetHeight = this.ePopup.offsetHeight;
		var height = GetHeight();
		if (pOffsetHeight > height * 2 / 3) {
			this.ePopup.style.height = Math.round(height * 2 / 3) + 'px';
			this.ePopup.style.overflowY = 'auto';
		}
		else {
			this.ePopup.style.overflowY = 'hidden';
		}
	}
};

function CPopupMenus()
{
	this.aItems = [];
	this.iShown = POPUP_HIDDEN;
}

CPopupMenus.prototype = {
	getLength: function ()
	{
		return this.aItems.length;
	},

	addItem: function (ePopup)
	{
		this.aItems.push(ePopup);
		this.hideItem(this.getLength() - 1);
	},

	showItem: function (iItemIdx)
	{
		this.hideAllItems();
		var oItem = this.aItems[iItemIdx];
		oItem.showPopup();
		var obj = this;
		oItem.eControl.onclick = function () {
			obj.hideItem(iItemIdx);
		};
		this.iShown = POPUP_SHOWED;
	},

	hideItem: function (iItemIdx)
	{
		var oItem = this.aItems[iItemIdx];
		oItem.hidePopup();
		var obj = this;
		oItem.eControl.onclick = function () {
			obj.showItem(iItemIdx);
		};
	},

	hideAllItems: function ()
	{
		for (var i = this.getLength() - 1; i >= 0; i--) {
			this.hideItem(i);
		}
		this.iShown = POPUP_HIDDEN;
	},

	checkShownItems: function ()
	{
		if (this.iShown == POPUP_READY) {
			this.hideAllItems();
		}
		if (this.iShown == POPUP_SHOWED) {
			this.iShown = POPUP_READY;
		}
	}
};

function CSearchForm(bigSearchForm, smallSearchForm, downButton, upButton, bigLookFor,
	smallLookFor, centralPaneView, cancelControl)
{
	this.isShown = POPUP_HIDDEN;
	this.smallForm = smallSearchForm;
	this.downButton = downButton;
	this.upButton = upButton;

	this._form = bigSearchForm;
	this._bigLookFor = bigLookFor;
	this._bigClassName = (centralPaneView) ? 'wm_search_form wm_central_pane_view' : 'wm_search_form';
	this._smallLookFor = smallLookFor;
	this._cancelControl = cancelControl;
	this._searchIn = null;
	this._focused = false;
	this._isEmpty = true;
	this._sForAdd = '';

	this._init();
}

CSearchForm.prototype =
{
	setSearchIn: function (searchIn)
	{
		this._searchIn = searchIn;
	},

	setStringValue: function (string, sForAdd)
	{
		if (sForAdd === undefined) {
			sForAdd = this._sForAdd;
		}
		this._sForAdd = sForAdd
		if (string == '' && !this._focused) {
			string = Lang.SearchInputText + this._sForAdd;
			this._isEmpty = true;
		}
		if (!this._focused) {
			this._setStringToInput(string);
		}
	},

	getStringValue: function ()
	{
		var string = this.getStringFromInput();
		if (this._isEmpty == true) {
			string = '';
		}
		return string;
	},

	focusSmallForm: function ()
	{
		this._smallLookFor.focus();
	},

	focus: function ()
	{
		this._focused = true;
		var string = this.getStringValue();
		this._isEmpty = false;
		this._setStringToInput(string);
	},

	blur: function ()
	{
		this._focused = false;
		var string = this.getStringFromInput();
		this.setStringValue(string);
	},

	showBigForm: function ()
	{
		var bounds = GetBounds(this.smallForm);
		this._form.style.top = bounds.Top + 'px';
		if (window.RTL) {
		    this._form.style.left = bounds.Left + 'px';
		}
		else {
		    this._form.style.right = (GetWidth() - bounds.Left - bounds.Width) + 'px';
		}
		this._form.className = this._bigClassName;
		this.smallForm.className = 'wm_hide';
		this._hideDownButton();
		this._showUpButton();
		this.isShown = POPUP_SHOWED;
		this._bigLookFor.value = this._smallLookFor.value;
		if (null !== this._searchIn) {
			this._searchIn.className = '';
		}
	},

	show: function ()
	{
		this.isShown = POPUP_HIDDEN;
		this.smallForm.className = 'wm_toolbar_search_item';
		var searchFormObj = this;
		this.downButton.onclick = function () {
			searchFormObj.showBigForm();
		};
		this._showDownButton();
		this._form.className = 'wm_hide';
		if (null !== this._searchIn) {
			this._searchIn.className = 'wm_hide';
		}
		this._hideUpButton();
	},

	hide: function ()
	{
		this.smallForm.className = 'wm_hide';
		this._hideDownButton();
		this._form.className = 'wm_hide';
		if (null !== this._searchIn) {
			this._searchIn.className = 'wm_hide';
		}
		this._hideUpButton();
	},

	checkVisibility: function ()
	{
		if (this.isShown == POPUP_READY) {
			this.show();
		}
		if (this.isShown == POPUP_SHOWED) {
			this.isShown = POPUP_READY;
		}
	},

	_setStringToInput: function (string)
	{
		this._setStyle();
		this._bigLookFor.value = string;
		this._smallLookFor.value = string;
	},

	getStringFromInput: function ()
	{
		var string = (this.isShown != POPUP_HIDDEN) ? this._bigLookFor.value : this._smallLookFor.value;
		return string;
	},

	_setStyle: function ()
	{
		if (this._isEmpty) {
			this._bigLookFor.style.color = '#bbb';
			this._bigLookFor.style.fontStyle = 'italic';
			this._smallLookFor.style.color = '#bbb';
			this._smallLookFor.style.fontStyle = 'italic';
		}
		else {
			this._bigLookFor.style.color = 'Black';
			this._bigLookFor.style.fontStyle = 'normal';
			this._smallLookFor.style.color = 'Black';
			this._smallLookFor.style.fontStyle = 'normal';
		}
	},

	_showDownButton: function ()
	{
		var searchFormObj = this;
		this.downButton.onmouseover = function () {
			searchFormObj.downButton.className = 'wm_toolbar_search_item_over';
			searchFormObj.smallForm.className = 'wm_toolbar_search_item_over';
		};
		this.downButton.onmouseout = function () {
			searchFormObj.downButton.className = 'wm_toolbar_search_item';
			searchFormObj.smallForm.className = 'wm_toolbar_search_item';
		};
		this.downButton.className = 'wm_toolbar_search_item';
	},

	_hideDownButton: function ()
	{
		this.downButton.onmouseover = function () {};
		this.downButton.onmouseout = function () {};
		this.downButton.className = 'wm_hide';
	},

	_showUpButton: function ()
	{
		var searchFormObj = this;
		this.upButton.onmouseover = function () {
			searchFormObj.upButton.className = 'wm_toolbar_search_item_over';
		};
		this.upButton.onmouseout = function () {
			searchFormObj.upButton.className = 'wm_toolbar_search_item';
		};
		this.upButton.className = 'wm_toolbar_search_item';
	},

	_hideUpButton: function ()
	{
		this.upButton.onmouseover = function () {};
		this.upButton.onmouseout = function () {};
		this.upButton.className = 'wm_hide';
	},

	_init: function ()
	{
		this._form.onclick = function (e)
		{
			e = e || window.event;
			if (typeof(e.stopPropagation) == 'function') e.stopPropagation();
			if (typeof(e.cancelBubble) == 'boolean') e.cancelBubble = true;
		}
		var searchFormObj = this;
		this._bigLookFor.onfocus = function () {
			searchFormObj.focus();
		};
		this._bigLookFor.onblur = function () {
			searchFormObj.blur();
		};
		this._smallLookFor.onfocus = function () {
			searchFormObj.focus();
		};
		this._smallLookFor.onblur = function () {
			searchFormObj.blur();
		};
		this._bigLookFor.value = Lang.SearchInputText;
		this._smallLookFor.value = Lang.SearchInputText;
		this._isEmpty = true;
		this._setStyle();
	}
};

function CFadeEffect(name)
{
	this._name = name;
	this._elem = null;
	this._interval = 50;
	this._timer = null;
}

CFadeEffect.prototype =
{
	go: function (elem, delay)
	{
		this._elem = elem;
		clearTimeout(this._timer);
		this._timer = setTimeout(this._name + '.setOpacityAndRestartTimeout(1)', delay);
		return (delay + 10 * this._interval);
	},

	stop: function ()
	{
		this._setOpacity(1);
		clearTimeout(this._timer);
	},

	setOpacityAndRestartTimeout: function (opacity)
	{
		this._setOpacity(opacity);
		clearTimeout(this._timer);
		if (opacity > 0) {
			this._timer = setTimeout(this._name + '.setOpacityAndRestartTimeout(' + (opacity - 0.1) + ')', this._interval);
		}
	},

	_setOpacity: function (opacity)
	{
		if (this._elem == null) return;
		opacity = Math.round(opacity * 10) / 10;
		var elem = this._elem;
		if (Browser.ie || Browser.ie9) {
		var opacityIe = opacity * 100;
			var oAlpha = elem.filters['DXImageTransform.Microsoft.alpha'] || elem.filters.alpha;
			if (oAlpha) {
				oAlpha.opacity = opacityIe;
			}
			else {
				elem.style.filter += 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + opacityIe + ')';
			}
		}
		else {
			elem.style.opacity = opacity;       // CSS3 compliant (Moz 1.7+, safari 1.2+, opera 9)
			elem.style.MozOpacity = opacity;	// mozilla 1.6-, firefox 0.8
			elem.style.KhtmlOpacity = opacity;	// Konqueror 3.1, safari 1.1
		}
	}
};

/* for control placement and displaying of information block */
function CInformation(cont, cls)
{
	this._mainContainer = cont;
	this._containerClass = cls;
}

CInformation.prototype = {
	show: function ()
	{
		this._mainContainer.className = this._containerClass;
	},

	hide: function ()
	{
		this._mainContainer.className = 'wm_hide';
	},

	resize: function ()
	{
		var cont = this._mainContainer;
		cont.style.right = 'auto';
		cont.style.width = 'auto';
		var offsetWidth = cont.offsetWidth;
		var width = GetWidth();
		if (offsetWidth >  0.4 * width) {
			cont.style.width = '40%';
		}
		offsetWidth = cont.offsetWidth;
		cont.style.left = Math.round((width - offsetWidth) / 2) + 'px';
	}
};

function CReport(hideFunction, delay, className, hasCloseImage)
{
	this.shown = false;

	this._hideFunction = hideFunction;
	this._delay = delay;
	this._className = className;
	this._hasCloseImage = hasCloseImage;

	this._containerObj = null;
	this._messageObj = null;
	this._controlObj = null;
	this._fadeObj = null;
	this._hideTimer = null;
	this._visible = true;
}

CReport.prototype =
{
	setFade: function (fadeObj)
	{
		this._fadeObj = fadeObj;
	},

	show: function (msg, priorDelay)
	{
		this._messageObj.innerHTML = msg;
		if (this._visible) {
			this._controlObj.show();
			this._controlObj.resize();
		}
        var interval = (priorDelay) ? priorDelay : this._delay;
		if (interval > 0) {
			if (null !== this._fadeObj) {
				interval = this._fadeObj.go(this._containerObj, interval);
			}
			clearTimeout(this._hideTimer);
			this._hideTimer = setTimeout(this._hideFunction, interval);
		}
		this.shown = true;
	},

	hide: function ()
	{
		clearTimeout(this._hideTimer);
		this._controlObj.hide();
		if (null !== this._fadeObj) {
			this._fadeObj.stop();
		}
		this.shown = false;
	},

	unvisible: function ()
	{
		this._controlObj.hide();
		this._visible = false;
	},

	visible: function ()
	{
		if (this.shown) {
			this._controlObj.show();
			this._controlObj.resize();
		}
		this._visible = true;
	},

	resize: function ()
	{
		this._controlObj.resize();
	},

	build: function (parent)
	{
		if (parent == undefined) parent = document.body;
		var tbl = CreateChild(parent, 'table', [['class', 'wm_hide']]);

		var tr = tbl.insertRow(0);
			tr.style.position = 'relative';
			tr.style.zIndex = '20';

		var td = tr.insertCell(0);
			td.className = 'wm_shadow';
			td.style.width = '2px';
			td.style.fontSize = '1px';


		td = tr.insertCell(1);

		var infoDiv = CreateChild(td, 'div', [['class', 'wm_info_message'], ['id', 'info_message']]);
		var aDiv = CreateChild(td, 'div', [['class', 'a']]);
		aDiv.innerHTML = '&nbsp;';
		var bDiv = CreateChild(td, 'div', [['class', 'b']]);
		bDiv.innerHTML = '&nbsp;';
		CreateChild(infoDiv, 'span', [['class', 'wm_info_image']]);

		var obj = this;
		if (this._hasCloseImage) {
			var closeImageDiv = CreateChild(infoDiv, 'div', [['class', 'wm_close_info_image wm_control']]);
			closeImageDiv.onclick = function () {
				eval(obj._hideFunction);
			};
		}

		td = tr.insertCell(2);
			td.className = 'wm_shadow';
			td.style.width = '2px';
			td.style.fontSize = '1px';

		tr = tbl.insertRow(1);
		td = tr.insertCell(0);
		td.colSpan = 3;
		td.className = 'wm_shadow';
			td.style.height = '2px';
			td.style.background = 'none';

		aDiv = CreateChild(td, 'div', [['class', 'a']]);
		aDiv.innerHTML = '&nbsp;';
		bDiv = CreateChild(td, 'div', [['class', 'b']]);
		bDiv.innerHTML = '&nbsp;';

		tr = tbl.insertRow(2);
			tr.style.position = 'relative';
			tr.style.zIndex = '19';

		td = tr.insertCell(0);
			td.colSpan = 3;
			td.style.height = '2px';
		var div = CreateChild(td, 'div', [['class', 'a wm_shadow'],
			['style', 'margin:0px 2px;height:2px; top:-4px; position:relative; border:0px;background:#555;']]);
		div.innerHTML = '&nbsp;';

		this._containerObj = tbl;
		this._messageObj = CreateChild(infoDiv, 'span', [['class', 'wm_info_text']]);
		this._controlObj = new CInformation(tbl, this._className);
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
