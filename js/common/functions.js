function ReadStyle(element, property)
{
	if (element.style[property]) {
		return element.style[property];
	} else if (element.currentStyle) {
		return element.currentStyle[property];
	} else if (document.defaultView && document.defaultView.getComputedStyle) {
		var style = document.defaultView.getComputedStyle(element, null);
		return style.getPropertyValue(property);
	}
	return null;
}

function GetBorderWidth(style, width)
{
	if (style == 'none') {
		return 0;
	}
	else {
		return ParseStyleWidth(width);
	}
}

function ParseStyleWidth(width)
{
	var floatWidth = parseFloat(width);
	return (isNaN(floatWidth)) ? 0 : Math.round(floatWidth);
}


function GetTopBorderWidth(element)
{
	if (Browser.mozilla) {
		return GetBorderWidth(ReadStyle(element, 'border-top-style'), ReadStyle(element, 'border-top-width'));
	}
	else {
		return GetBorderWidth(ReadStyle(element, 'borderTopStyle'), ReadStyle(element, 'borderTopWidth'));
	}
}

function GetBorders(element)
{
	var right, bottom, left;
	if (Browser.mozilla) {
		right = GetBorderWidth(ReadStyle(element, 'border-right-style'), ReadStyle(element, 'border-right-width'));
		bottom = GetBorderWidth(ReadStyle(element, 'border-bottom-style'), ReadStyle(element, 'border-bottom-width'));
		left = GetBorderWidth(ReadStyle(element, 'border-left-style'), ReadStyle(element, 'border-left-width'));
	}
	else {
		right = GetBorderWidth(ReadStyle(element, 'borderRightStyle'), ReadStyle(element, 'borderRightWidth'));
		bottom = GetBorderWidth(ReadStyle(element, 'borderBottomStyle'), ReadStyle(element, 'borderBottomWidth'));
		left = GetBorderWidth(ReadStyle(element, 'borderLeftStyle'), ReadStyle(element, 'borderLeftWidth'));
	}
	return {Top: GetTopBorderWidth(element), Right: right, Bottom: bottom, Left: left};
}

function GetHeightStyle(element)
{
	return GetBorderWidth(ReadStyle(element, 'display'), ReadStyle(element, 'height'));
}

function GetPaddings(element)
{
	return GetStyleWidth(element, 'padding');
}

function GetMargins(element)
{
	return GetStyleWidth(element, 'margin');
}

function GetStyleWidth(element, style)
{
	var top, right, bottom, left;
	if (Browser.mozilla) {
		top = ParseStyleWidth(ReadStyle(element, style + '-top'));
		right = ParseStyleWidth(ReadStyle(element, style + '-right'));
		bottom = ParseStyleWidth(ReadStyle(element, style + '-bottom'));
		left = ParseStyleWidth(ReadStyle(element, style + '-left'));
	}
	else {
		top = ParseStyleWidth(ReadStyle(element, style + 'Top'));
		right = ParseStyleWidth(ReadStyle(element, style + 'Right'));
		bottom = ParseStyleWidth(ReadStyle(element, style + 'Bottom'));
		left = ParseStyleWidth(ReadStyle(element, style + 'Left'));
	}
	return {Top: top, Right: right, Bottom: bottom, Left: left};
}

function GetMarginLeft(element)
{
	var mLeft = (Browser.mozilla || Browser.safari) ? ReadStyle(element, 'margin-left') : ReadStyle(element, 'marginLeft');
	if (mLeft != null) {
		return mLeft.replace(/px/, '') - 0;
	}
	return Number.NaN;
}

function GetMarginRight(element)
{
	var mRight = (Browser.mozilla || Browser.safari) ?
		ReadStyle(element, 'margin-right') : ReadStyle(element, 'marginRight');
	if (mRight != null) {
		return mRight.replace(/px/, '') - 0;
	}
	return Number.NaN;
}

function Trim(str) {
    return str.replace(/^\s+/, '').replace(/\s+$/, '');
}

//email parts for adding to contacts
function GetEmailParts(fullEmail)
{
	var quote1, quote2, leftBrocket, prevLeftBroket, rightBrocket, name, email;
	quote1 = fullEmail.indexOf('"');
	quote2 = fullEmail.indexOf('"', quote1 + 1);
	leftBrocket = fullEmail.indexOf('<', quote2);
	prevLeftBroket = -1;
	while (leftBrocket != -1) {
		prevLeftBroket = leftBrocket;
		leftBrocket = fullEmail.indexOf('<', leftBrocket + 1);
	}
	leftBrocket = prevLeftBroket;
	rightBrocket = fullEmail.indexOf('>', leftBrocket + 1);
	name = email = '';
	if (leftBrocket == -1) {
		email = Trim(fullEmail);
	} else {
		name = (quote1 == -1) ?
			Trim(fullEmail.substring(0, leftBrocket)) :
			Trim(fullEmail.substring(quote1 + 1, quote2));

		email = Trim(fullEmail.substring(leftBrocket + 1, rightBrocket));
	}
	return {name: name, email: email, FullEmail: fullEmail};
}

function PopupWindow(wUrl, wName, wWidth, wHeight, toolbar)
{
	var toolbarVar, wLeft, wTop, wArgs, shown;
	toolbarVar = (toolbar) ? 'yes' : 'no';
	wTop = (window.screen) ? (screen.height - wHeight) / 2 : 200;
	wLeft = (window.screen) ? (screen.width - wWidth) / 2 : 200;
	wArgs = 'toolbar=' + toolbarVar + ',location=no,directories=no,copyhistory=no,';
	wArgs += 'status=yes,scrollbars=yes,resizable=yes,';
	wArgs += 'width=' + wWidth + ',height=' + wHeight + ',left=' + wLeft + ',top=' + wTop;
	shown = window.open(wUrl, wName, wArgs);
	shown.focus();
}

function PopupPrintMessage(sUrl)
{
	WindowOpener.open(sUrl, 'PopupPrintMessage', true);
//	PopupWindow(sUrl, 'PopupPrintMessage', 640, 480, true);
	return false;
}

function PopupContacts(wUrl)
{
	PopupWindow(wUrl, 'PopupContacts', 300, 400);
	return false;
}

function SetBodyAutoOverflow(isAuto, bOverFlowXHidden)
{
	var OverFlow, Scroll;
	OverFlow = 'hidden';
	Scroll = 'no';
	if (isAuto) {
		OverFlow = 'auto';
		Scroll = 'yes';
	}
	if (Browser.ie) {
		WebMail._html.style.overflow = OverFlow;
		if (bOverFlowXHidden) {
			$(WebMail._html).css('overflow-x', 'hidden').css('overflow-y', OverFlow);
		}
	}
	else {
		document.body.scroll = Scroll;
		document.body.style.overflow = OverFlow;
		if (bOverFlowXHidden) {
			$(document.body).css('overflow-x', 'hidden').css('overflow-y', OverFlow);
		}
	}
}

function OpenURL(strUrl)
{
	strUrl = Validator.correctWebPage(Trim(strUrl));
	if (strUrl.length > 0) {
		var strProt = strUrl.substr(0, 4);
		if (strProt != "http" && strProt != "ftp:") {
			strUrl = "http://" + strUrl;
		}
		var newWin = window.open(encodeURI(strUrl), null, "toolbar=yes,location=yes,directories=yes,status=yes,scrollbars=yes,resizable=yes,copyhistory=yes");
		newWin.focus();
	}
}

function EncodeStringForEval(source)
{
	source = source || '';
	return source.replace(/\\/g, '\\\\').replace(/'/g, '\\\'').replace(/"/g, '\\"');
}

function HtmlEncode(source)
{
	source = source || '';
	source = source.toString().replace(/&/g, '&amp;').replace(/>/g, '&gt;').replace(/</g, '&lt;');
	return source;
}

function HtmlEncodeWithQuotes(source)
{
	source = HtmlEncode(source).replace(/"/g, '&quot;');
	return source;
}

function HtmlDecode(source)
{
	source = source || '';
	return source.replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&amp;/g, '&');
}

function HtmlDecodeWithQuotes(source)
{
	source = HtmlDecode(source).replace(/&quot;/g, '"');
	return source;
}

function HtmlEncodeBody(source)
{
	source = source || '';
	return source.replace(/]]>/g, '&#93;&#93;&gt;');
}

function HtmlDecodeBody(source)
{
	source = source || '';
	return source.replace(/&#93;&#93;&gt;/g, ']]>');
}

function GetCData(source, isBody, encoded)
{
	if (!encoded) {
		source = (isBody) ? HtmlEncodeBody(source) : HtmlEncode(source, false);
	}
	return '<![CDATA[' + source + ']]>';
}

function isEnter(ev)
{
	var key = -1;
	if (window.event) {
		key = window.event.keyCode;
	} else if (ev) {
		key = ev.which;
	}
	return (key == 13);
}

function isEsc(ev)
{
	var key = -1;
	if (window.event) {
		key = window.event.keyCode;
	} else if (ev) {
		key = ev.which;
	}
	return (key == 27);
}

function TextAreaLimit(ev, obj, count)
{
	ev = ev ? ev : window.event;
	var key = -1;
	if (window.event) {
		key = window.event.keyCode;
	} else if (ev) {
		key = ev.which;
	}
	switch (key) {
	case 8:		//backspace
	case 13:	//enter
	case 16:	//shift
	case 17:	//ctrl
	case 18:	//alt
	case 35:	//end
	case 36:	//home
	case 37:	//to the right
	case 38:	//up
	case 39:	//to the left
	case 40:	//down
	case 46:	//delete
		break;
	default:
		if (!ev.ctrlKey && !ev.shiftKey) {
			if (obj.value.length >= count) {
				return false;
			}
		}
		break;
	}
	return true;
}

function isRightClick(ev)
{
	var key = -1;
	if (window.event) {
		key = window.event.button;
	} else if (ev) {
		key = ev.which;
	}
	return (key == 3 || key == 2);
}

function GetWidth()
{
	var width = 1024;
	if (document.documentElement && document.documentElement.clientWidth) {
		width = document.documentElement.clientWidth;
	} else if (document.body.clientWidth) {
		width = document.body.clientWidth;
	} else if (self.innerWidth) {
		width = self.innerWidth;
	}
	return width;
}

function GetHeight()
{
	var height = 768;
	if (self.innerHeight) {
		height = self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) {
		height = document.documentElement.clientHeight;
	} else if (document.body.clientHeight) {
		height = document.body.clientHeight;
	}
	return height;
}

function CreateChild(eParent, sTagName, aAttrs)
{
	var $Node = $('<' + sTagName + ' />');

	if ($.isArray(aAttrs))
	{
		var iIndex = 0, iLen = aAttrs.length;
		for (; iIndex < iLen; iIndex++)
		{
			$Node.attr(aAttrs[iIndex][0], aAttrs[iIndex][1]);
		}
	}

	$Node.appendTo(eParent);
	return $Node[0];
}

function CreateTextChild(parent, text)
{
	var node = document.createTextNode(text);
	parent.appendChild(node);
	return node;
}

function MakeOpaqueOnSelect(element)
{
	if (Browser.ie && Browser.version < 7) {
		CreateChild(element, 'iframe',
			[
				['src', EmptyHtmlUrl],
				['scrolling', 'no'],
				['frameborder', '0'],
				['class', 'wm_for_ie_select']
			]
		);
	}
}

function GetBounds(object)
{
	if (object == null) {
		return {Left: 0, Top: 0, Width: 0, Height: 0};
	}
	var left, top, parent;
	left = object.offsetLeft;
	top = object.offsetTop;
	for (parent = object.offsetParent; parent; parent = parent.offsetParent) {
		left += parent.offsetLeft;
		top += parent.offsetTop;
	}
	return {Left: left, Top: top, Width: object.offsetWidth, Height: object.offsetHeight};
}

function GetScrollY(object)
{
	if (object == null) {
		return 0;
	}
    var scrollY = 0;
    if (object && typeof(object.scrollTop) != 'undefined') {
	    scrollY += object.scrollTop;
	    if (scrollY == 0 && object.parentNode && typeof(object.parentNode) != 'undefined') {
		    scrollY += object.parentNode.scrollTop;
	    }
    } else if (typeof object.pageXOffset != 'undefined') {
	    scrollY += object.pageYOffset;
    }
	return scrollY;
}

function CleanNode(object)
{
	if (object)
	{
		try {
			while (object.firstChild) {
				object.removeChild(object.firstChild);
			}
		}
		catch (err) {
		}
	}
}

function HighlightMessageLine(source)
{
	return '<span class="wm_find">' + source + '</span>';
}

function HighlightContactLine(source)
{
	return '<b>' + source + '</b>';
}

function isEqualArray(arr1, arr2)
{
	if (!(arr1 instanceof Array) || !(arr2 instanceof Array)) {
		return false;
	}
	if (arr1.length != arr2.length) {
		return false;
	}
	for (var i = 0; i < arr1.length; i++) {
		if (arr1[i] != arr2[i]) {
			return false;
		}
	}
	return true;
}

String.prototype.PrepareForRegExp = function ()
{
	var search = this.replace(/\\/g, '\\\\').replace(/\^/g, '\\^').replace(/\$/g, '\\$');
	search = search.replace(/\./g, '\\.').replace(/\*/g, '\\*').replace(/\+/g, '\\+');
	search = search.replace(/\?/g, '\\?').replace(/\|/g, '\\|').replace(/\(/g, '\\(');
	search = search.replace(/\)/g, '\\)').replace(/\[/g, '\\[');
	return search;
};

String.prototype.replaceStr = function (search, replacement)
{
	return this.replace(new RegExp(search.PrepareForRegExp(), 'gi'), replacement);
};

function GetBirthDay(d, m, y)
{
	var res = '';
	if (y != 0) {
		res += y;
		if (d != 0 || m != 0) res += ',';
	}
	if (d != 0) {
		res += ' ' + d;
	}
	switch (m) {
		case 1:res += ' ' + Lang.ShortMonthJanuary;break;
		case 2:res += ' ' + Lang.ShortMonthFebruary;break;
		case 3:res += ' ' + Lang.ShortMonthMarch;break;
		case 4:res += ' ' + Lang.ShortMonthApril;break;
		case 5:res += ' ' + Lang.ShortMonthMay;break;
		case 6:res += ' ' + Lang.ShortMonthJune;break;
		case 7:res += ' ' + Lang.ShortMonthJuly;break;
		case 8:res += ' ' + Lang.ShortMonthAugust;break;
		case 9:res += ' ' + Lang.ShortMonthSeptember;break;
		case 10:res += ' ' + Lang.ShortMonthOctober;break;
		case 11:res += ' ' + Lang.ShortMonthNovember;break;
		case 12:res += ' ' + Lang.ShortMonthDecember;break;
	}
	return res;
}

function GetFriendlySize(byteSize)
{
	if (byteSize === undefined) {return '';}
	var size, mbSize;
	size = Math.ceil(byteSize / 1024);
	mbSize = size / 1024;
	return (mbSize > 1)
		? ((mbSize >= 1024)
			? Math.ceil((mbSize * 10) / 1024) / 10 + Lang.Gb
			: Math.ceil(mbSize * 10) / 10 + Lang.Mb)
		: size + Lang.Kb;
}

function GetFriendlySizeKB(kbSize)
{
	if (kbSize === undefined) {return '';}
	return GetFriendlySize(kbSize * 1024);
}

function GetExtension(fileName)
{
	var ext, dotPos;
	ext = '';
	dotPos = fileName.lastIndexOf('.');
	if (dotPos > -1) {
		ext = fileName.substr(dotPos + 1).toLowerCase();
	}
	return ext;
}

function GetFileParams(sFileName)
{
	var sShortName = sFileName;
	if (sShortName.length > 50){
		sShortName = sShortName.slice(0, 40) + '...' + sShortName.slice(-8);
	}
	var iY = 2 * Y_ICON_SHIFT;
	var sExtension = GetExtension(sFileName);
	switch (sExtension) {
		case 'ics':
			return {iX: 3 * X_ICON_SHIFT, iY: 3 * Y_ICON_SHIFT, bView: false, sShortName: sShortName};
		case 'asp':
		case 'asa':
		case 'inc':
			return {iX: 12 * X_ICON_SHIFT, iY: iY, bView: false, sShortName: sShortName};
		case 'css':
			return {iX: 11 * X_ICON_SHIFT, iY: iY, bView: false, sShortName: sShortName};
		case 'docx':
		case 'doc':
			return {iX: 10 * X_ICON_SHIFT, iY: iY, bView: false, sShortName: sShortName};
		case 'pptx':
		case 'ppt':
			return {iX: 2 * X_ICON_SHIFT, iY: 3 * Y_ICON_SHIFT, bView: false, sShortName: sShortName};
		case 'html':
		case 'shtml':
		case 'phtml':
		case 'htm':
			return {iX: 9 * X_ICON_SHIFT, iY: iY, bView: false, sShortName: sShortName};
		case 'pdf':
			return {iX: 8 * X_ICON_SHIFT, iY: iY, bView: false, sShortName: sShortName};
		case 'xlsx':
		case 'xls':
			return {iX: 7 * X_ICON_SHIFT, iY: iY, bView: false, sShortName: sShortName};
		case 'bat':
		case 'exe':
		case 'com':
			return {iX: 5 * X_ICON_SHIFT, iY: iY, bView: false, sShortName: sShortName};
		case 'bmp':
			return {iX: 4 * X_ICON_SHIFT, iY: iY, bView: true, sShortName: sShortName};
		case 'gif':
			return {iX: 3 * X_ICON_SHIFT, iY: iY, bView: true, sShortName: sShortName};
		case 'png':
		case 'jpg':
		case 'jpeg':
		case 'jpe':
			return {iX: 2 * X_ICON_SHIFT, iY: iY, bView: true, sShortName: sShortName};
		case 'tiff':
		case 'tif':
			return {iX: 1 * X_ICON_SHIFT, iY: iY, bView: false, sShortName: sShortName};
		case 'txt':
			return {iX: 0 * X_ICON_SHIFT, iY: iY, bView: false, sShortName: sShortName};
		case 'eml':
			return {iX: 6 * X_ICON_SHIFT, iY: iY, bView: true, sShortName: sShortName};
		default:
			return {iX: 6 * X_ICON_SHIFT, iY: iY, bView: false, sShortName: sShortName};
	}
}

function $addHandler(object, event, handler)
{
	if (object && event && handler)
	{
		if (typeof object.addEventListener != 'undefined')
		{
			if (event == 'mousewheel')
			{
				event = 'DOMMouseScroll';
			}
			object.addEventListener(event, handler, false);
		}
		else if (typeof object.attachEvent != 'undefined')
		{
			object.attachEvent('on' + event, handler);
		}
		return true;
	}
	return false;
}

function isRtlLanguage(langName) {
	return (langName === 'Hebrew' || langName === 'Arabic' || langName === 'Persian');
}

function isNum(value) {
	var reg = /^[0-9]+$/;
	return reg.test(value);
}

function ParseGetParams()
{
	var getRequestParams, paramsArray, keyValueArray, getRequest, i;
	getRequestParams = paramsArray = keyValueArray = [];
	getRequest = location.search;
	if (getRequest != '') {
		paramsArray = (getRequest.substr(1)).split('&');
		for (i = 0; i < paramsArray.length; i++) {
			keyValueArray = paramsArray[i].split('=');
			getRequestParams[keyValueArray[0]] = keyValueArray[1];
		}
	}
	return getRequestParams;
}

function checkLinkHref(href)
{
	if (href.substring(0, 7).toLowerCase() == 'mailto:') {
		var emailTo, questionPos;
		emailTo = href.substring(7);
		questionPos = emailTo.indexOf('?');
		if (questionPos > -1) {
			emailTo = emailTo.substring(0, questionPos);
		}
		MailToHandler(emailTo.toLowerCase());
		return false;
	}
	return true;
}

function BrowserLang()
{
	return (navigator.language || navigator.systemLanguage ||
            navigator.userLanguage || 'en').substr(0, 2).toLowerCase();
}

function validateMessageAddressString(addressStr)
{
	addressStr = Trim(addressStr);
	if (',' === addressStr.substr(addressStr.length - 1, 1)) {
		addressStr = addressStr.substr(0, addressStr.length - 1);
	}

	var emailsStrArray = addressStr.replace(/"[^"]*"/g, '').replace(/;/g, ',').split(',');
	var incorrectEmailsArray = [];
	for (var j = 0; j < emailsStrArray.length; j++) {
		var emailParts = GetEmailParts(Trim(emailsStrArray[j]));
		if (!Validator.isCorrectEmail(emailParts.email)) {
			incorrectEmailsArray.push(emailParts.email);
		}
	}
	return incorrectEmailsArray;
}

function FlashVersion() {
	var
		iVersion = 0,
		i = 0,
		iLastVersion = 11,
		iFirstVersion = 6,
		oPlugin = null,
		iParsedVersion = 0
	;
	if (Browser.ie || Browser.ie9) {
		for (i = iLastVersion; i >= iFirstVersion; i--) {
			try {
				if (eval('new ActiveXObject("ShockwaveFlash.ShockwaveFlash.' + i + '")')) {
					iVersion = i;
					break;
				}
			} catch(e) {}
		}
	}
	else {
		for (i = 0; i < navigator.plugins.length; i++) {
			oPlugin = navigator.plugins[i];
			if (oPlugin && oPlugin.name && oPlugin.name.indexOf('Flash') > -1) {
				iParsedVersion = parseInt(oPlugin.description.substring(16));
				iVersion = (iParsedVersion > iVersion) ? iParsedVersion : iVersion;
			}
		}
	}
	return iVersion;
}

function ReplySubjectAdd(sPrefix, sSubject)
{
	var oMatch, sResult = Trim(sSubject);

	if (null !== (oMatch = (new RegExp('^' + sPrefix + '[\\s]?\\:(.*)$', 'gi')).exec(sSubject)) && undefined !== oMatch[1])
	{
		sResult = sPrefix + '[2]: ' + oMatch[1];
	}
	else if (null !== (oMatch = (new RegExp('^(' + sPrefix + '[\\s]?[\\[\\(]?)([\\d]+)([\\]\\)]?[\\s]?\\:.*)$', 'gi')).exec(sSubject)) &&
		undefined !== oMatch[1] && undefined !== oMatch[2] && undefined !== oMatch[3])
	{
		sResult = oMatch[1] + (parseInt(oMatch[2], 10) + 1) + oMatch[3];
		sResult = oMatch[1] + (parseInt(oMatch[2], 10) + 1) + oMatch[3];
	}
	else
	{
		sResult = sPrefix + ': ' + sSubject;
	}

	return sResult;
}

var isEventSupported = (function(){
	var TAGNAMES = {
		'select':'input','change':'input',
		'submit':'form','reset':'form',
		'error':'img','load':'img','abort':'img'
	};
	function isEventSupported(eventName) {
		var el = document.createElement(TAGNAMES[eventName] || 'div');
		eventName = 'on' + eventName;
		//?? didn't work
		//var isSupported = (eventName in el);
		// if (!isSupported) {
			el.setAttribute(eventName, 'return;');
			var isSupported = typeof el[eventName] == 'function';
		// }
		el = null;
		return isSupported;
	}
	return isEventSupported;
})();

function DontSelectContent(elem)
{
	elem.onmousedown = function(e) {
		e = e ? e : window.event;
		if (e.ctrlKey || e.shiftKey) {
			return false; // don't select content in opera and ff
		}
		return true;
	};
	elem.onselectstart = function () {
		return false; // don't select content in ie
	};
	elem.onselect = function () {
		return false; // don't select content in ie
	};
}

function RemoveAllFlashes()
{
	if (Browser.ie) {
		var aObjects = document.all.tags("object");
		if (aObjects && aObjects.length > 0) {
			for (var i = aObjects.length - 1; 0 <= i; i--) {
				aObjects[i].removeNode(true);
			}
		}
	}
}

arrayIndexOf = function (array, item) {
	if (typeof array.indexOf == "function") {
		return array.indexOf(item);
	}
	for (var i = 0, j = array.length; i < j; i++){
		if (array[i] === item) {
			return i;
		}
	}
	return -1;
};

addClass = function (node, className) {
	var currentClassNames = (node.className || "").split(/\s+/);
	var index = arrayIndexOf(currentClassNames, className);
	if (index >= 0) {
		return;
	}
	currentClassNames.push(className);
	return node.className = currentClassNames.join(' ');
};

removeClass = function (node, className) {
	var currentClassNames = (node.className || "").split(/\s+/);
	var index = arrayIndexOf(currentClassNames, className);
	if (index == -1) {
		return;
	}
	currentClassNames.splice(index, 1)
	return node.className = currentClassNames.join(' ');
};

function hasCssClass(node, className) {
	var currentClassNames = (node.className || "").split(/\s+/);
	return arrayIndexOf(currentClassNames, className) >= 0;
};

getStyle = function getStyle(el,styleProp)
{
	if (el.currentStyle) {
		var y = el.currentStyle[camelCase(styleProp)];
	} else if (window.getComputedStyle) {
		var y = document.defaultView.getComputedStyle(el,'').getPropertyValue(styleProp);
	}
	return y;
}

camelCase = function( string ) {
	return string.replace(/-([a-z])/ig, function( all, letter ) {
		return letter.toUpperCase();
	});
},


stringTrim = function (string) {
	return (string || "").replace(/^(\s|\u00A0)+|(\s|\u00A0)+$/g, "");
};

$.fn.disableSelection = function() {
	this.each(function() {
        $(this).attr('unselectable', 'on')
			.css({
				'-moz-user-select': '-moz-none',
				'-webkit-user-select': 'none',
				'-khtml-user-select': 'none',
				'-o-user-select': 'none',
				'user-select': 'none'
			})
		   .bind('selectstart.select', function() {
			   return false;
			});
	});
	return this;
};

$.fn.enableSelection = function() {
   this.each(function() {
		$(this).attr('unselectable', 'off')
			.css({
				'-moz-user-select': 'text',
				'-webkit-user-select': 'text',
				'-khtml-user-select': 'text',
				'-o-user-select': 'text',
				'user-select': 'text'
			})
			.unbind('selectstart.select');
	});
	return this;
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
