function CBrowser() {
	this.Init = function() {
		var len = this.Profiles.length;
		for (var i = 0; i < len; i++) {
			if (this.Profiles[i].Criterion) {
				this.Name = this.Profiles[i].id;
				this.Version = this.Profiles[i].Version();
				this.Allowed = this.Version >= this.Profiles[i].AtLeast;
				break;
			}
		}
		this.IE = (this.Name == 'Microsoft Internet Explorer' && this.Version < 9);
		this.IE9 = (this.Name == 'Microsoft Internet Explorer' && this.Version >= 9);
		this.Opera = (this.Name == "Opera");
		this.Mozilla = (this.Name == "Mozilla" || this.Name == "Firefox" || this.Name == "Netscape");
		this.Safari = (this.Name == 'Safari');
		this.Gecko = (this.Opera || this.Mozilla);
	};

	this.Profiles = [
		{
			id: "Opera",
			Criterion: window.opera,
			AtLeast: 8,
			Version: function() {
				var start, end;
				var r = navigator.userAgent;
				var start1 = r.indexOf("Opera/");
				var start2 = r.indexOf("Opera ");
				if (-1 == start1) {
					start = start2 + 6;
					end = r.length;
				} else {
					start = start1 + 6;
					end = r.indexOf(" ");
				}
				r = parseFloat(r.slice(start, end));
				return r;
			}
		},
		{
			id: "Safari",
			Criterion:
			(
				(navigator.appCodeName.toLowerCase() == "mozilla") &&
				(navigator.appName.toLowerCase() == "netscape") &&
				(navigator.product.toLowerCase() == "gecko") &&
				(navigator.userAgent.toLowerCase().indexOf("safari") != -1)
			),
			AtLeast: 1.2,
			Version: function() {
				var r = navigator.userAgent;
				return parseFloat(r.split("Version/").reverse().join(" "));
			}
		},
		{
			id: "Firefox",
			Criterion:
			(
				(navigator.appCodeName.toLowerCase() == "mozilla") &&
				(navigator.appName.toLowerCase() == "netscape") &&
				(navigator.product.toLowerCase() == "gecko") &&
				(navigator.userAgent.toLowerCase().indexOf("firefox") != -1)
			),
			AtLeast: 1,
			Version: function() {
				var r = navigator.userAgent.split(" ").reverse().join(" ");
				r = parseFloat(r.slice(r.indexOf("/")+1,r.indexOf(" ")));
				return r;
			}
		},
		{
			id: "Netscape",
			Criterion:
			(
				(navigator.appCodeName.toLowerCase() == "mozilla") &&
				(navigator.appName.toLowerCase() == "netscape") &&
				(navigator.product.toLowerCase() == "gecko") &&
				(navigator.userAgent.toLowerCase().indexOf("netscape") != -1)
			),
			AtLeast: 7,
			Version: function() {
				var r = navigator.userAgent.split(" ").reverse().join(" ");
				r = parseFloat(r.slice(r.indexOf("/")+1,r.indexOf(" ")));
				return r;
			}
		},
		{
			id: "Mozilla",
			Criterion:
			(
				(navigator.appCodeName.toLowerCase() == "mozilla") &&
				(navigator.appName.toLowerCase() == "netscape") &&
				(navigator.product.toLowerCase() == "gecko") &&
				(navigator.userAgent.toLowerCase().indexOf("mozilla") != -1)
			),
			AtLeast: 1,
			Version: function() {
				var r = navigator.userAgent;
				return parseFloat(r.split("Firefox/").reverse().join(" "));
			}
		},
		{
			id: "Microsoft Internet Explorer",
			Criterion:
			(
				(navigator.appName.toLowerCase() == "microsoft internet explorer") &&
				(navigator.appVersion.toLowerCase().indexOf("msie") !== 0) &&
				(navigator.userAgent.toLowerCase().indexOf("msie") !== 0) &&
				(!window.opera)
			),
			AtLeast: 5,
			Version: function() {
				var r = navigator.userAgent.toLowerCase();
				r = parseFloat(r.slice(r.indexOf("msie")+4,r.indexOf(";",r.indexOf("msie")+4)));
				return r;
			}
		}
	];

	this.Init();
}

var Browser = new CBrowser();

function CreateChild(parent, tagName) {
	var node = document.createElement(tagName);
	parent.appendChild(node);
	return node;
}

function CreateChildWithAttrs(parent, tagName, arAttrs)
{
	var i, t, key, val, node, attrsLen, strAttrs;
	if (Browser.IE) {
		strAttrs = '';
		attrsLen = arAttrs.length;
		for (i = attrsLen - 1; i >= 0; i--) {
			t = arAttrs[i];
			key = t[0];
			val = t[1];
			strAttrs += ' ' + key + '="' + val + '"';
		}
		tagName = '<' + tagName + strAttrs + '>';
		node = document.createElement(tagName);
	} else {
		node = document.createElement(tagName);
		attrsLen = arAttrs.length;
		for (i = attrsLen - 1; i >= 0; i--) {
			t = arAttrs[i];
			key = t[0];
			val = t[1];
			node.setAttribute(key, val);
		}
	}
	parent.appendChild(node);
	return node;
}

function GetWidth() {
	var width = 1024;
	if (document.documentElement && document.documentElement.clientWidth)
	{
		width = document.documentElement.clientWidth;
	}
	else if (document.body.clientWidth)
	{
		width = document.body.clientWidth;
	}
	else if (self.innerWidth)
	{
		width = self.innerWidth;
	}
	return width;
}

/* for control placement and displaying of information block */
function CInformation(cont, cls) {
	this._mainContainer = cont;
	this._containerClass = cls;
}

CInformation.prototype = {
	Show: function () {
		this._mainContainer.className = this._containerClass;
	},

	Hide: function () {
		this._mainContainer.className = 'wm_hide';
	},

	Resize: function () {
		var tbl = this._mainContainer;
		tbl.style.width = 'auto';
		var offsetWidth = tbl.offsetWidth;
		var width = GetWidth();

		var tblLeft = Math.round(width / 2 - offsetWidth / 2);
		tbl.style.left =  tblLeft + 'px';
		/* tbl.style.top = this.GetScrollY() + 'px'; */
	}/* ,

	GetScrollY: function() {
		var scrollY = 0;
		if (document.body && typeof document.body.scrollTop != "undefined") {
			scrollY += document.body.scrollTop;
			if (scrollY === 0 && document.body.parentNode && typeof document.body.parentNode != "undefined") {
				scrollY += document.body.parentNode.scrollTop;
			}
		}
		else if (typeof window.pageXOffset != "undefined")  {
			scrollY += window.pageYOffset;
		}
		return scrollY;
	} */
};

function CError(name)
{
	this._name = name;
	this._containerObj = null;
	this._messageObj = null;
	this._controlObj = null;
	this._fadeObj = null;
	this._delay = 10000;

	this.Build = function ()
	{
		var tbl, tr, td, div, shadowDiv, aDiv, infoDiv, bDiv, imageDiv, closeImageDiv, obj;
		tbl = CreateChildWithAttrs(document.body, 'table', [['class', 'wm_hide']]);
		tr = tbl.insertRow(0);
		td = tr.insertCell(0);
		div = CreateChildWithAttrs(td, 'div', [['class', 'wm_info_block']]);
		shadowDiv = CreateChildWithAttrs(div, 'div', [['class', 'wm_shadow']]);
		aDiv = CreateChildWithAttrs(shadowDiv, 'div', [['class', 'a']]);
		aDiv.innerHTML = '&nbsp;';
		infoDiv = CreateChildWithAttrs(div, 'div', [['class', 'wm_info_message']]);
		aDiv = CreateChildWithAttrs(div, 'div', [['class', 'a']]);
		aDiv.innerHTML = '&nbsp;';
		bDiv = CreateChildWithAttrs(div, 'div', [['class', 'b']]);
		bDiv.innerHTML = '&nbsp;';
		this._containerObj = tbl;
		CreateChildWithAttrs(infoDiv, 'div', [['class', 'wm_info_image']]);
		this._messageObj = CreateChild(infoDiv, 'span');
		closeImageDiv = CreateChildWithAttrs(infoDiv, 'div', [['class', 'wm_close_info_image wm_control']]);
		obj = this;
		closeImageDiv.onclick = function () {
			obj.Hide();
		};
		this._controlObj = new CInformation(tbl, 'wm_information wm_error_information');
	};
}

function CReport(name)
{
	this._name = name;
	this._containerObj = null;
	this._messageObj = null;
	this._controlObj = null;
	this._fadeObj = null;
	this._delay = 5000;

	this.Build = function ()
	{
		var tbl, tr, td, div, shadowDiv, aDiv, infoDiv, bDiv;
		tbl = CreateChildWithAttrs(document.body, 'table', [['class', 'wm_hide']]);
		tr = tbl.insertRow(0);
		td = tr.insertCell(0);
		div = CreateChildWithAttrs(td, 'div', [['class', 'wm_info_block']]);
		shadowDiv = CreateChildWithAttrs(div, 'div', [['class', 'wm_shadow']]);
		aDiv = CreateChildWithAttrs(shadowDiv, 'div', [['class', 'a']]);
		aDiv.innerHTML = '&nbsp;';
		infoDiv = CreateChildWithAttrs(div, 'div', [['class', 'wm_info_message']]);
		aDiv = CreateChildWithAttrs(div, 'div', [['class', 'a']]);
		aDiv.innerHTML = '&nbsp;';
		bDiv = CreateChildWithAttrs(div, 'div', [['class', 'b']]);
		bDiv.innerHTML = '&nbsp;';
		this._containerObj = tbl;
		this._messageObj = infoDiv;
		this._controlObj = new CInformation(tbl, 'wm_information wm_report_information');
	};
}

function CInfo(name) {
	this._name = name;
	this._containerObj = null;
	this._messageObj = null;
	this._controlObj = null;
	this._fadeObj = null;
	this._delay = 5000;

	this.Build = function () {
		var tbl, tr, td, div, shadowDiv, aDiv, infoDiv, bDiv;
		tbl = CreateChildWithAttrs(document.body, 'table', [['class', 'wm_hide']]);
		tr = tbl.insertRow(0);
		td = tr.insertCell(0);
		div = CreateChildWithAttrs(td, 'div', [['class', 'wm_info_block']]);
		shadowDiv = CreateChildWithAttrs(div, 'div', [['class', 'wm_shadow']]);
		aDiv = CreateChildWithAttrs(shadowDiv, 'div', [['class', 'a']]);
		aDiv.innerHTML = '&nbsp;';
		infoDiv = CreateChildWithAttrs(div, 'div', [['class', 'wm_info_message']]);
		aDiv = CreateChildWithAttrs(div, 'div', [['class', 'a']]);
		aDiv.innerHTML = '&nbsp;';
		bDiv = CreateChildWithAttrs(div, 'div', [['class', 'b']]);
		bDiv.innerHTML = '&nbsp;';
		this._containerObj = tbl;
		this._messageObj = infoDiv;
		this._controlObj = new CInformation(tbl, 'wm_information wm_status_information');
	};
}

var iReportTimeOut = 0;
var ReportPrototype = {
	Show: function (msg, priorDelay, bUseMultipleMessage) {
		bUseMultipleMessage = bUseMultipleMessage || false;

		if (!bUseMultipleMessage)
		{
			this._messageObj.innerHTML = msg;
		}
		else if (bUseMultipleMessage)
		{
			this._messageObj.innerHTML += ('' === this._messageObj.innerHTML) ? msg :
				'<div class="msg_seporator"></div>' + msg;
		}

		this._controlObj.Show();
		this._controlObj.Resize();
		window.clearTimeout(iReportTimeOut);
		if (null !== this._fadeObj && !bUseMultipleMessage) {
			var interval = (priorDelay) ?
				this._fadeObj.Go(this._containerObj, priorDelay) :
				this._fadeObj.Go(this._containerObj, this._delay);

			if (this._name) {
				iReportTimeOut = setTimeout(this._name + '.Hide()', interval);
			}
		} else {
			if (this._name) {
				iReportTimeOut = setTimeout(this._name + '.Hide()',
					(priorDelay) ? priorDelay : this._delay);
			}
		}
	},

	SetFade: function (fadeObj) {
		this._fadeObj = fadeObj;
	},

	Hide: function () {
		this._controlObj.Hide();
		if (null !== this._fadeObj)
		{
			this._fadeObj.RemoveOpacity();
		}
		this._messageObj.innerHTML = '';
	},

	Resize: function () {
		this._controlObj.Resize();
	}
};

CInfo.prototype = ReportPrototype;
CReport.prototype = ReportPrototype;
CError.prototype = ReportPrototype;

function CFadeEffect(name) {
	this._name = name;
	this._elem = null;
	this._duration = 600;
}

CFadeEffect.prototype = {
	Go: function (elem, delay) {
		this._elem = elem;
		
		setTimeout((function (elem, duration) {
			return function () {
				$(elem).fadeOut(duration);
			};
		})(this._elem, this._duration), delay);
		
		return this._duration + delay;
	},

	RemoveOpacity: function () {
		$(this._elem).css({'opacity': 1});
	}
};

var MsgBox = {
	_fadeObj: null,
	_errorObj: null,
	_reportObj: null,
	_infoObj: null,
	_skin: 'adminpanel',

	Init: function () {
		this._skin = '.';
		if (this._fadeObj === null)
		{
			this._fadeObj = new CFadeEffect('MsgBox._fadeObj');
		}
		if (this._errorObj === null)
		{
			this._errorObj = new CError('MsgBox._errorObj');
			this._errorObj.Build(this._skin);
			this._errorObj.SetFade(this._fadeObj);
		}
		if (this._reportObj === null)
		{
			this._reportObj = new CReport('MsgBox._reportObj');
			this._reportObj.Build();
			this._reportObj.SetFade(this._fadeObj);
		}
		if (this._infoObj === null)
		{
			this._infoObj = new CInfo('MsgBox._infoObj');
			this._infoObj.Build();
			this._infoObj.SetFade(this._fadeObj);
		}
	},

	/*
	 * type = 0 - info
	 * type = 1 - report
	 * type = 2 - error
	 */
	Show: function (msg, type, delay, bUseMultipleMessage) {
		this.Init();
		if (!type)
		{
			type = 0;
		}
		switch (type) {
		case 0:
			this._infoObj.Show(msg, delay, bUseMultipleMessage);
			break;
		case 1:
			this._reportObj.Show(msg, delay, bUseMultipleMessage);
			break;
		case 2:
			this._errorObj.Show(msg, delay, bUseMultipleMessage);
			break;
		}
	}
};

String.prototype.trim = function () {
	return (this !== null && this !== undefined) ?
		this.replace(/^\s+/, '').replace(/\s+$/, '') : this;
};

function ShowError(errorMsg, errorCode) {
	MsgBox.Show('Error on client side - Code: ' + errorCode + '<br/>' + errorMsg, 2);
}

function PopUpWindow(url) {
	var shown = window.open(url, 'Popup',
		'left=50,top=150, toolbar=no,location=no,directories=no,status=yes,scrollbars=yes,resizable=yes,'+
		'copyhistory=no,width=750,height=500');
	shown.focus();
	return false;
}

function GoToLocation(url) {
	window.location.href = url;
}

function GetHeight() {
	var height = 768;
	if (self.innerHeight)
	{
		height = self.innerHeight;
	}
	else if (document.documentElement && document.documentElement.clientHeight)
	{
		height = document.documentElement.clientHeight;
	}
	else if (document.body.clientHeight)
	{
		height = document.body.clientHeight;
	}
	return height;
}

function GetBounds(object) {
	if (object === null || object === undefined)
	{
		return {Left: 0, Top: 0, Width: 0, Height: 0};
	}
	var left = object.offsetLeft;
	var top = object.offsetTop;
	for (var parent = object.offsetParent; parent; parent = parent.offsetParent) {
		left += parent.offsetLeft;
		top += parent.offsetTop;
	}
	return {Left: left, Top: top, Width: object.offsetWidth, Height: object.offsetHeight};
}

function OnlineMsgError(text) {
	MsgBox.Show(text, 2, null, true);
}

function OnlineMsgInfo(text) {
	MsgBox.Show(text, 1);
}

function OnlineLoadInfo(text) {
	MsgBox.Show(text, 0, 20000);
}

var Tip = {
	_container: null,
	_message: null,
	_base: '',
	_initialized: false,

	_init: function () {
		if (this._initialized)
		{
			return;
		}
		this._container = CreateChild(document.body, 'table');
		this._container.className = 'wm_hide';
		var tr = this._container.insertRow(0);
		var td = tr.insertCell(0);
		td.className = 'wm_tip_arrow';
		this._message = tr.insertCell(1);
		this._message.className = 'wm_tip_info';
		this._initialized = true;
	},

	SetMessageText: function(text) {
		this._message.innerHTML = text;
	},

	SetCoord: function(element) {
		var bounds = GetBounds(element);
		this._container.style.top = (bounds.Top + bounds.Height/2 - 16) + 'px';
		this._container.style.left = (bounds.Left + bounds.Width - 5) + 'px';
	},

	Show: function(text, element, base) {
		this._init();
		this.SetMessageText(text);
		this.SetCoord(element);
		this._base = base;
		this._container.className = 'wm_tip';
	},

	Hide: function(base) {
		this._init();
		if (this._base === base || this._base === '')
		{
			this._container.className = 'wm_hide';
		}
	}
};

function ResizeMainError()
{
	var mainErrorObj = document.getElementById('mainIdObj');
	if (mainErrorObj)
	{
		mainErrorObj.style.width = 'auto';
		mainErrorObj.style.left = Math.round(
			(GetWidth() / 2) - (mainErrorObj.offsetWidth / 2)) + 'px';
	}
}

var _seeToolTip = 0;
var l = 0;
var t = 0;
var IE = document.all ? true : false;
var tooltip = document.createElement("div");
tooltip.id = 'tooltip';

function getMouseXY(e) {
	try {
		if (IE) {
			l = event.clientX + document.documentElement.scrollLeft;
			t = event.clientY + document.documentElement.scrollTop;
		} else {
			l = e.pageX;
			t = e.pageY;
		}
		tooltip.style.left = l + "px";
		tooltip.style.top = t + "px";
		return true;
	}catch(errorMsg) {
		ShowError(errorMsg, '103');
	}
}

function AddToolTip(tooltip_text) {
	try {
		if (window.event)
		{
			getMouseXY(window.event);
		}
		document.onmousemove = getMouseXY;
		document.body.appendChild(tooltip);
		tooltip.innerHTML = tooltip_text;
		_seeToolTip++;
	} catch(errorMsg) {
		ShowError(errorMsg, '104');
	}
}

function RemoveToolTip() {
	try {
		document.onmousemove = '';
		if (tooltip && _seeToolTip > 0) {
			_seeToolTip--;
			document.body.removeChild(tooltip);
		}
	} catch(errorMsg) {
		ShowError(errorMsg, '105');
	}
}

function JAddToolTip(sId, sText)
{
	var oLabel = $('#' + sId);
	if (oLabel.length)
	{
		oLabel.addClass('DottedText');
		oLabel.mouseover(function() {AddToolTip(sText);});
		oLabel.mouseout(function() {RemoveToolTip();});
	}
}

function SetDisabled(obj, isDisabled, withLabel) {
	if (obj) {
		isDisabled = (typeof isDisabled == 'undefined') ? false : isDisabled;
		if (isDisabled) {
//			if (!obj.type || obj.type == 'checkbox' || obj.type == 'radio' || obj.type == 'button' || obj.type == 'submit') {}
//			else {
//				obj.style.background = "#ddd";
//			}
			if (obj.type && obj.type !== 'checkbox' && obj.type !== 'radio' && obj.type !== 'button' || obj.type !== 'button')
			{
				obj.style.background = "#ddd";
			}

			obj.disabled = true;
		}else {
			obj.disabled = false;
			if (obj.type && obj.type !== 'checkbox' && obj.type !== 'radio' && obj.type !== 'button' || obj.type !== 'button')
			{
				obj.style.background = "#fff";
			}
//			if (!obj.type || obj.type == 'checkbox' || obj.type == 'radio' || obj.type == 'button' || obj.type == 'submit') {}
//			else {
//				obj.style.background = "#fff";
//			}
		}

		withLabel =(typeof withLabel == 'undefined') ? false : withLabel;
		if (withLabel) {
			var _l = document.getElementById(obj.id + "_label");
			if (_l) {
				_l.style.color = (isDisabled) ? "#aaaaaa" : "#000000";
			}
		}
	}
}

function SetDisabledArray(chId, idsArray)
{
	var chObj = document.getElementById(chId);
	if (chObj) {
		for (var aId in idsArray)
		{
			if (idsArray.hasOwnProperty(aId))
			{
				SetDisabled(document.getElementById(idsArray[aId]), !chObj.checked, true);
			}
		}
	}
}

function WindowForceResize()
{
	$(window).trigger('resize');
}

function DeleteSelectedFromList(sListId)
{
	$('#' + sListId + ' option:selected').remove();
}

function AddUserToList(sFromId, sListId, sDomain)
{
	var oFromInput = $('#' + sFromId);
	var oList = $('#' + sListId);
	if (oFromInput && oList)
	{
		var sValue = oFromInput.val();
		if (sValue && 0 < sValue.length)
		{
			if (typeof(sDomain) != 'undefined')
			{
				sValue = sValue + '@' + sDomain;
			}
			$('<option>').text(sValue).val(sValue).appendTo(oList);
		}

		oFromInput.val('');
//		oFromInput.focus();
	}
}

function SelectListAll(sListId)
{
	var oList = $('#' + sListId);
	if (oList && oList.prop('multiple')) {
		oList.find('option').prop('selected', true);
	}
}
