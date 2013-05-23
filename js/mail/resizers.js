/*
 * Classes:
 *  CVerticalResizer(DIVMovable, parentTable, divHSize, minLeftWidth, minRightWidth, leftPosition, endMoveHandler, type)
 *  CHorizontalResizer(DIVMovable, parentTable, divVSize, minUpperHeight, minLowerHeight, topPosition, endMoveHandler)
 */

var VR_TYPE_INBOX = 0;
var VR_TYPE_MESS = 1;
var VR_TYPE_HEAD = 2;

function CVerticalResizer(DIVMovable, parentTable, divHSize, minLeftWidth, minRightWidth, leftPosition, endMoveHandler, type, aResizeCallback) {
	// set internal data by outside parameters
	this._type = (type) ? type : VR_TYPE_INBOX;
	switch (this._type) {
	case VR_TYPE_MESS:
		this._class = 'wm_vresizer_mess';
		this._classPress = 'wm_vresizer_mess';
		break;
	case VR_TYPE_HEAD:
		this._class = 'wm_inbox_headers_separate';
		this._classPress = 'wm_inbox_headers_separate';
		break;
	default:
		this._type = VR_TYPE_INBOX;
		this._class = 'wm_vresizer';
		this._classPress = 'wm_vresizer_press';
		break;
	}
	this._DIVMovable = DIVMovable;
	this._parentTable = parentTable;
	this._divHSize = divHSize;
	this._minLeftWidth = minLeftWidth;
	this._minRightWidth = minRightWidth;
	this.leftPosition = leftPosition;
	this._beginPosition = 0;
	this._endMoveHandler = endMoveHandler;
	this._resizeCallback = aResizeCallback;

	// set some internal data by default values (this values must be overwritten)
	this._leftBorder = 0;
	this._rightBorder = 600;
	this._leftLimit = 80;
	this._rightLimit = 550;

	this._divVSize = 2;
	this._divVSizePress = 2;
	
	this._DIVMovable.style.width = this._divHSize + 'px';
	this._DIVMovable.style.height = this._divVSize + 'px';
	this._DIVMovable.style.cursor = 'e-resize';
	
	switch (this._type) {
	case VR_TYPE_INBOX:
		this._leftShear = 0;
		this._DIVMovable.style.left = '1px';
		break;
	case VR_TYPE_MESS:
	case VR_TYPE_HEAD:
		this._leftShear = leftPosition;
		break;
	}
	this._DIVMovable.className = this._class;
	if (this._type != VR_TYPE_HEAD) {
		this._DIVMovable.innerHTML = '&nbsp;';
	}
	
	// this handler is necessary to begins moving
	var obj = this;
	this._DIVMovable.onmousedown = function (e) {
		obj.beginMoving(e);
		return false; // don't select content in opera and ff
	};
}

CVerticalResizer.prototype = {
	updateVerticalSize: function (vert_size, vert_size_press)
	{
		this._divVSize = vert_size - 1;
		this._DIVMovable.style.height = this._divVSize + 'px';
		this._divVSizePress = (this._type == VR_TYPE_HEAD) ? vert_size_press : vert_size;
	},
	
	updateMinLeftWidth: function (minLeftWidth)
	{
		this._minLeftWidth = minLeftWidth;
	},
	
	updateMinRightWidth: function (minRightWidth)
	{
		this._minRightWidth = minRightWidth;
	},
	
	updateLeftPosition: function (leftPosition)
	{
		var diff = leftPosition - this.leftPosition;
		this._minLeftWidth += diff;
		this.leftPosition = leftPosition;
		this._DIVMovable.style.left = leftPosition + 'px';
	},

	beginMoving: function (e)
	{
		if (this._resizeCallback)
		{
			this._resizeCallback[1].apply(this._resizeCallback[0], ['begin']);
		}
		
		var bounds, obj;
		e = e ? e : event;
		this._beginPosition = e.clientX;
		this._DIVMovable.className = this._classPress;
		if (this._type == VR_TYPE_HEAD) {
			this._DIVMovable.style.height = this._divVSizePress + 'px';
		}
		//don't select content in ie
		document.onselectstart = function () {
			return false;
		};
		document.onselect = function () {
			return false;
		};
		
		// calculate borders of this._parentTable
		bounds = GetBounds(this._parentTable);
		this._leftBorder = bounds.Left;
		this._rightBorder = bounds.Left + bounds.Width;

		// calculate moving limits (for center of movable td/div)
		if (window.RTL && this._type == VR_TYPE_INBOX) {
			this._leftLimit = this._leftBorder  + this._minRightWidth;
			this._rightLimit = this._rightBorder - this._minLeftWidth;
		}
		else {
			this._leftLimit = this._minLeftWidth + this._beginPosition - this.leftPosition;
			this._rightLimit = this._rightBorder - this._minRightWidth - ((this.leftPosition + 6) - this._beginPosition) - this._leftBorder;
			if (this._rightLimit < this._leftLimit) {
				this._rightLimit = this._leftLimit;
			}
		}

		// hang moving handlers	
		obj = this;
		this._parentTable.onmousemove = function (e) {
			if (arguments.length == 0) {
				e = event;
			}
			obj.processMoving(e.clientX); 
		};
		
		this._parentTable.onmouseup = function () {
			obj.endMoving();
		};
		
		this._parentTable.onmouseout = function (e) {
			var b, left_border, top_border, right_border, bottom_border;
			
			if (arguments.length == 0) {
				e = event;
			}

			b = GetBounds(this);
			left_border = b.Left;
			top_border = b.Top;
			right_border = left_border + b.Width;
			bottom_border = top_border + b.Height;
			
			// it is necessary to prevent incorrect action on mouseout event
			if (e.clientX <= left_border || e.clientX >= right_border ||
				e.clientY <= top_border || e.clientY >= bottom_border) {
				obj.endMoving();
			}
		};
	},
	
	processMoving: function (mouse_x)	
	{
		var new_left;
		// check and correct mouse_x if it is necessary
		if (mouse_x < this._leftLimit) {
			mouse_x = this._leftLimit;
		}
		if (mouse_x > this._rightLimit) {
			mouse_x = this._rightLimit;
		}
		switch (this._type) {
		case VR_TYPE_INBOX:
			this._DIVMovable.style.left = mouse_x - this._beginPosition + 1 + 'px';
			this._leftShear = mouse_x - this._beginPosition;
			break;
		case VR_TYPE_MESS:
			new_left = this.leftPosition + mouse_x - this._beginPosition;
			if (new_left < (this._leftLimit - (this._beginPosition - this.leftPosition))) {
				new_left = this._leftLimit - (this._beginPosition - this.leftPosition);
			}
			if (new_left > this._rightLimit) {
				new_left = this._rightLimit + ((this.leftPosition + 6) - this._beginPosition);
			}
			this._leftShear = new_left;
			eval(this._endMoveHandler);
			break;
		case VR_TYPE_HEAD:
			new_left = this.leftPosition + mouse_x - this._beginPosition;
			if (new_left < (this._leftLimit - (this._beginPosition - this.leftPosition))) {
				new_left = this._leftLimit - (this._beginPosition - this.leftPosition);
			}
			if (new_left > this._rightLimit) {
				new_left = this._rightLimit + ((this.leftPosition + 6) - this._beginPosition);
			}
			this._DIVMovable.style.left = new_left + 'px';
			this._leftShear = new_left;
			break;
		}
	},
	
	endMoving: function ()
	{
		if (this._resizeCallback)
		{
			this._resizeCallback[1].apply(this._resizeCallback[0], ['end']);
		}
		
		document.onselectstart = function () {};
		document.onselect = function () {};
		this._parentTable.onmousemove = '';
		this._parentTable.onmouseup = '';
		this._parentTable.onmouseout = '';
		this._DIVMovable.className = this._class;
		switch (this._type) {
		case VR_TYPE_INBOX:
			this._DIVMovable.style.left = '1px';
			var new_left = this.leftPosition;
			if (window.RTL) {
				new_left -= this._leftShear;
			} else {
				new_left += this._leftShear;
				if (new_left < (this._leftLimit - (this._beginPosition - this.leftPosition))) {
					new_left = this._leftLimit - (this._beginPosition - this.leftPosition);
				}
				if (new_left > this._rightLimit) {
					new_left = this._rightLimit + ((this.leftPosition + 6) - this._beginPosition);
				}
			}
			this.leftPosition = new_left;
			this._leftShear = 0;
			eval(this._endMoveHandler);
			break;
		case VR_TYPE_MESS:
			this.leftPosition = this._leftShear;
			break;
		case VR_TYPE_HEAD:
			this._DIVMovable.style.height = this._divVSize + 'px';
			this.leftPosition = this._leftShear;
			eval(this._endMoveHandler);
			break;
		}
	},
	
	free: function ()
	{
		this._parentTable.onmousemove = '';
		this._parentTable.onmouseup = '';
		this._parentTable.onmouseout = '';
		this._DIVMovable.onmousedown = '';
		this._DIVMovable.style.cursor = 'default';
	},
	
	busy: function (width)
	{
		var obj = this;
		this.leftPosition = width;
		this._DIVMovable.style.cursor = 'e-resize';
		
		// this handler is necessary to begins moving
		this._DIVMovable.onmousedown = function (e) {
			obj.beginMoving(e);
			return false; // don't select content in opera and ff
		};
	}
};

function CHorizontalResizer(DIVMovable, parentTable, divVSize, minUpperHeight, minLowerHeight, topPosition, endMoveHandler, aResizeCallback) {
	// set internal data by outside parameters
	this._DIVMovable = DIVMovable;
	this._parentTable = parentTable;// table (HTML Element) which contents all changable TRs
	this._divVSize = divVSize;// vertical size of movable TR/TD/DIV
	this._minUpperHeight = minUpperHeight;// minimal height when upper TR has good look
	this._minLowerHeight = minLowerHeight;// minimal height when lower TR has good look
	this._topPosition = topPosition;
	this._topShear = 0;
	this._beginPosition = 0;
	this._endMoveHandler = endMoveHandler;
	this._resizeCallback = aResizeCallback;

	this._class = 'wm_hresizer';
	this._classPress = 'wm_hresizer_press';
	
	// set some internal data by default values (this values must be overwritten)
	this._upperBorder = 114;
	this._lowerBorder = 815;
	this._upperLimit = 268;
	this._lowerLimit = 665;

	this._divHSize = 2;

	this._DIVMovable.style.width = this._divHSize + 'px';
	this._DIVMovable.style.height = this._divVSize + 'px';
	this._DIVMovable.style.cursor = 's-resize';
	this._DIVMovable.style.top = '0px';
	this._DIVMovable.className = this._class;
	this._DIVMovable.innerHTML = '&nbsp;';

	// this handler is necessary to begins moving
	var obj = this;
	this._DIVMovable.onmousedown = function (e) {
		obj.beginMoving(e);
		return false; // don't select content in opera and ff
	};
}

CHorizontalResizer.prototype = 
{
	updateHorizontalSize: function (horiz_size)
	{
		this._divHSize = horiz_size;
		this._DIVMovable.style.width = this._divHSize + 'px';
	},

	beginMoving: function (e)
	{
		if (this._resizeCallback)
		{
			this._resizeCallback[1].apply(this._resizeCallback[0], ['begin']);
		}
		
		var obj, bounds;
		e = e ? e : event;
		this._beginPosition = e.clientY;
		this._DIVMovable.className = this._classPress;
		//don't select content in ie
		document.onselectstart = function () {
			return false;
		};
		document.onselect = function () {
			return false;
		};

		// calculate borders of this._parentTable
		bounds = GetBounds(this._parentTable);
		this._upperBorder = bounds.Top;
		this._lowerBorder = bounds.Top + bounds.Height;

		// calculate moving limits (for center of movable td/div)
		this._upperLimit = this._upperBorder + this._minUpperHeight + (this._beginPosition - this._topPosition) - this._upperBorder;
		this._lowerLimit = this._lowerBorder - this._minLowerHeight - ((this._topPosition + 6) - this._beginPosition) - this._upperBorder;

		// hang moving handlers	
		obj = this;
		this._parentTable.onmousemove = function (e) {
			if (arguments.length == 0) {
				e = event;
			}
			obj.processMoving(e.clientY); 
		};

		this._parentTable.onmouseup = function () {
			obj.endMoving();
		};

		this._parentTable.onmouseout = function (e) {
			var b, left_border, top_border, right_border, bottom_border;
			
			if (arguments.length == 0) {
				e = event;
			}

			b = GetBounds(this);
			left_border = b.Left;
			top_border = b.Top;
			right_border = left_border + b.Width;
			bottom_border = top_border  + b.Height;
			
			// it is necessary to prevent incorrect action on mouseout event
			if (e.clientX <= left_border || e.clientX >= right_border ||
				e.clientY <= top_border || e.clientY >= bottom_border) {
				obj.endMoving();
			}
		};
	},

	processMoving: function (mouse_y)	
	{
		// check and correct mouse_y if it is necessary
		if (mouse_y < this._upperLimit) {
			mouse_y = this._upperLimit;
		}
		if (mouse_y > this._lowerLimit) {
			mouse_y = this._lowerLimit;
		}
		this._DIVMovable.style.top = mouse_y - this._beginPosition + 'px';
		this._topShear = mouse_y - this._beginPosition;
	},
	
	endMoving: function ()
	{
		if (this._resizeCallback)
		{
			this._resizeCallback[1].apply(this._resizeCallback[0], ['end']);
		}
		
		var new_top;
		this._DIVMovable.className = this._class;
		this._DIVMovable.style.top = '0px';
		document.onselectstart = function () {};
		document.onselect = function () {};
		new_top = this._topPosition + this._topShear;
		if (new_top < (this._upperLimit - (this._beginPosition - this._topPosition))) {
			new_top = this._upperLimit - (this._beginPosition - this._topPosition);
		}
		if (new_top > this._lowerLimit + ((this._topPosition + 6) - this._beginPosition)) {
			new_top = this._lowerLimit + ((this._topPosition + 6) - this._beginPosition);
		}
		this._topPosition = new_top;
		this._topShear = 0;
		this._parentTable.onmousemove = '';
		this._parentTable.onmouseup = '';
		this._parentTable.onmouseout = '';
		eval(this._endMoveHandler);
	},
	
	free: function ()
	{
		this._parentTable.onmousemove = '';
		this._parentTable.onmouseup = '';
		this._parentTable.onmouseout = '';
		this._DIVMovable.onmousedown = '';
		this._DIVMovable.style.cursor = 'default';
	}
};

function CResizer(oHandler, oParent, oFirst, oSecond, iStartWidth, iMinWidth, aResizeCallback) {
	this.oHandler = $(oHandler);
	this.iHandlerWidth = null;
	this.oParent = $(oParent);
	this.oFirst = $(oFirst);
	this.oSecond = $(oSecond);
	this.iStartWidth = iStartWidth;
	this.aResizeCallback = aResizeCallback;
	this.iMinWidth = iMinWidth;
	this.iRatio = null;
	
	this.oHandler.disableSelection();
	
	var obj = this;
	this.oHandler.bind({
		'mousedown.nsResize': function () {
			if (obj.aResizeCallback) {
				obj.aResizeCallback[1].apply(obj.aResizeCallback[0], ['begin']);
			}

			$('body').bind({
				'mouseup.nsResize': function () {
					if (obj.aResizeCallback) {
						obj.aResizeCallback[1].apply(obj.aResizeCallback[0], ['end']);
					}
					$('body').unbind('.nsResize');
				},
				'mousemove.nsResize': function (oEvent) {
					obj.move(oEvent);
				},
				'selectstart.nsResize': function () {
					return false;
				},
				'select': function () {
					return false;
				}
			});
		},
		'mousedrag.nsResize': function (oEvent) {
			oEvent.preventDefault();
			return false;
		}
	});
}

CResizer.prototype = {
	initStartState: function ()
	{
		

		if (null === this.iHandlerWidth) {
			this.iHandlerWidth = this.oHandler.outerWidth();
		}

//		this.update(firstWidth, secondWidth);
		this.refresh();
	},
	
	update: function (firstWidth, secondWidth) {
		var iPaddings = parseInt(this.oFirst.css('padding-left')) + parseInt(this.oFirst.css('padding-right'));
		this.oHandler.css({
			'left': firstWidth,
			'z-index': 100,
			'cursor': 'e-resize'
		});
		this.oFirst.css({
			'width': firstWidth - iPaddings,
			'left': 0
		});
		this.oSecond.css({
			'width': secondWidth - this.iHandlerWidth,
			'left': firstWidth +  this.iHandlerWidth 
		});
	},
	
	move: function (oEvent) {
		var 
			firstWidth = oEvent.clientX - this.oFirst.offset().left,
			secondWidth = this.oParent.width() - firstWidth
		;
		if (firstWidth >= this.iMinWidth && secondWidth >= 0) {
			this.iRatio = secondWidth / firstWidth;
			this.update(firstWidth, secondWidth);
		}
	},
	
	refresh: function () {
		var 
			iStartWidth = this.oSecond.hasClass('wm_hide') ? 0 : this.iStartWidth,
			width = this.oParent.width(),
			firstWidth = Math.round(width - width * iStartWidth / 100)
		;
		if (this.iRatio && iStartWidth != 0) {
			firstWidth = Math.round(width / (this.iRatio + 1));
		}
		var secondWidth = width - firstWidth;
		
		this.update(firstWidth, secondWidth);
	}
}

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}