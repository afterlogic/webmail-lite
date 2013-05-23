/*
 * Classes:
 *  CVariableColumn(id, params, selection)
 *  CVariableTable(sortHandler, selection, dragNDrop, controller)
 *  CCheckBoxCell()
 *  CImageCell(className, id, content)
 *  CTextCell(text, title)
 *  CSelection(fillSelectedContactsHandler)
 *  CDragNDrop(langField)
 */

function CVariableColumn(id, params, selection)
{
	this.id = -1;
	this.field = '';
	this._langField = '';
	this._imgClassName = '';
	this._langNumber = -1;

	this.sortField = SORT_FIELD_NOTHING;
	this.sortOrder = SORT_ORDER_ASC;
	this.Sorted = false;
	this._sortIconPlace = 2;
	this._sortHandler = null;
	this._freeSort = false;

	this.Align = 'center';
	this.Width = 100;
	this.MinWidth = 100;
	this._left = 0;
//	this._padding = 2;

	this._htmlElem = null;
	this.LineElems = [];

	this._isResize = false;
	this.resizer = null;
	this._separator = null;
	this.isLast = false;
	this._resizerWidth = 3;

	this.filled = false;
	this.CheckBox = null;
	if (id == IH_CHECK || id == CH_CHECK) {
		this._isCheckBox = true;
		this.oSelection = selection;
	}
	else {
		this._isCheckBox = false;
		this.oSelection = null;
	}
	this.changeField(id, params, false);
}

CVariableColumn.prototype = 
{
	changeField: function (id, params, setContent)
	{
		this.id = id;
		this.field = 'f' + params.DisplayField;
		this._langField = params.LangField;
		this._imgClassName = params.Picture;
		this.sortField = params.sortField;
		this._sortIconPlace = params.SortIconPlace;
		if (params.Align == 'left' || params.Align == 'center' || params.Align == 'right') {
			this.Align = params.Align;
		}
		else {
			this.Align = 'center';
		}
		if (this.filled == false) {
			this.Width = params.Width;
			this.filled = true;
		}
		this.MinWidth = params.MinWidth;
		this._isResize = params.isResize;
        if (setContent) {
            this.SetContent();
        }
	},
	
	SetContent: function ()
	{
		var contentNode = null;
		if (this._isCheckBox) {
			contentNode = document.createElement('input');
			contentNode.type = 'checkbox';
			this.CheckBox = contentNode;
			this.oSelection.SetCheckBox(this.CheckBox);
		}
		else if (this._langField.length > 0) {
			contentNode = document.createTextNode(Lang[this._langField]);
		}
		else if (this._imgClassName.length > 0) {
			contentNode = document.createElement('div');
			contentNode.className = this._imgClassName;
		}
		CleanNode(this._htmlElem);
		var nobr = CreateChild(this._htmlElem, 'nobr');
		if (this.Sorted) {
			var sortNode = document.createElement('span');
			sortNode.innerHTML = '&nbsp;';
			if (SORT_ORDER_ASC == this.sortOrder) {
				sortNode.className = 'wm_inbox_lines_sort_asc';
			}
			else {
				sortNode.className = 'wm_inbox_lines_sort_desc';
			}
			switch (this._sortIconPlace) {
				case 0:
					nobr.appendChild(sortNode);
					if (null != contentNode) {
						nobr.appendChild(contentNode);
					}
					break;
				case 1:
					nobr.appendChild(sortNode);
					break;
				case 2:
					if (null != contentNode) {
						nobr.appendChild(contentNode);
					}
					nobr.appendChild(sortNode);
					break;
			}
		}
		else {
			if (null != contentNode) {
				nobr.appendChild(contentNode);
			}
		}
	},
	
	RemoveSort: function ()
	{
		this.sortOrder = 1 - this.sortOrder;
		this.Sorted = false;
		this.SetContent();
	},
	
	setSort: function (sortOrder)
	{
		this.sortOrder = sortOrder;
		this.Sorted = true;
		this.SetContent();
	},

	SetLineElem: function (lineElem)
	{
		this.LineElems.push(lineElem);
	},

	clean: function ()
	{
		this.LineElems = [];
	},

	SetWidth: function (width)
	{
//		var newWidth = width - 2*this._padding - this._resizerWidth;
		var newWidth = width - this._resizerWidth;
		if (newWidth < 0) {
			this._htmlElem.className = 'wm_hide';
		}
		else {
			if (this._freeSort || this.sortField == SORT_FIELD_NOTHING) {
				if (this._isCheckBox) {
					this._htmlElem.className = 'wm_inbox_header wm_inbox_headers_checkbox';
				}
				else {
					this._htmlElem.className = 'wm_inbox_header';
				}
			}
			else {
				this._htmlElem.className = 'wm_inbox_header wm_control';
			}
			if (this.Width != width) {
				this.Width = width;
				this._htmlElem.style.width = newWidth + 'px';
				var lineItemWidth = newWidth + this._resizerWidth;
				for (var i = 0; i < this.LineElems.length; i++) {
					this.LineElems[i].style.width = lineItemWidth + 'px';
				}
				Cookies.create('wm_column_' + this.id, width);
			}
		}
	},
	
	resizeWidth: function ()
	{
		if (window.RTL) {
			var width = this.Width + this._left - this.resizer.leftPosition;
			this.SetWidth(width);
			this._left = this.resizer.leftPosition;
			this._htmlElem.style.left = this._left + 'px';
			return this._left;
		}
		else {
			var width = this.resizer.leftPosition - this._left + this._resizerWidth;
			this.SetWidth(width);
			return this.resizer.leftPosition + this._resizerWidth;
		}
	},
	
	resizeLeft: function (left)
	{
		if (this.isLast) {
			this.SetWidth(this.Width + this._left - left);
		}
		this._left = left;
		this._htmlElem.style.left = left + 'px';
		if (null !== this.resizer) {
			this.resizer.updateLeftPosition(left + this.Width - this._resizerWidth);
		}
		else if (null !== this._separator) {
			this._separator.style.left = (this.Width + left - this._resizerWidth) + 'px';
		}
		if (this.isLast) {
			return left;
		}
		else {
			return left + this.Width;
		}
	},
	
	// only for rtl
	resizeRight: function (right, isLast)
	{
		if (isLast) {
			this.SetWidth(right - this._left);
		}
		this._left = right - this.Width;
		this._htmlElem.style.left = (this._left + this._resizerWidth) + 'px';
		if (null != this.resizer) {
			this.resizer.updateLeftPosition(this._left);
		}
		else if (null != this._separator) {
			this._separator.style.left = (this._left) + 'px';
		}
		return this.Width;
	},
	
	freeSort: function ()
	{
		if (this._isCheckBox) {
			this._htmlElem.className = 'wm_inbox_header wm_inbox_headers_checkbox';
		}
		else {
			this._htmlElem.className = 'wm_inbox_header';
		}
		this._htmlElem.onclick = function () {};
		if (this.Sorted) {
			this.RemoveSort();
		}
		this._freeSort = true;
	},
	
	useSort: function ()
	{
		if (this.sortField != SORT_FIELD_NOTHING) {
			this._htmlElem.className = 'wm_inbox_header wm_control';
		}
		if (this._sortHandler != null) {
			var obj = this;
			this._htmlElem.onclick = function () {obj._sortHandler.call({sortField: obj.sortField, sortOrder: 1-obj.sortOrder});};
		}
		this._freeSort = false;
	},
	
	build: function (parent, xleft, isLast, resizeHandler, sortHandler)
	{
		this._parent = parent;
		this.isLast = isLast;
		var child = CreateChild(parent, 'div');
		child.className = 'wm_inbox_header';
		if (SORT_FIELD_NOTHING != this.sortField) {
			child.className = 'wm_inbox_header wm_control';
			this._sortHandler = sortHandler;
			var obj = this;
			child.onclick = function () {
				sortHandler.call({sortField: obj.sortField, sortOrder: 1-obj.sortOrder});
			};
		}
		if (this._isCheckBox) {
			child.className = 'wm_inbox_header wm_inbox_headers_checkbox';
		}
		
		child.style.textAlign = this.Align;
		child.style.paddingLeft = '2px';
		child.style.paddingRight = '2px';
//		child.style.width = (this.Width - 2*this._padding - this._resizerWidth) + 'px';
		child.style.width = (this.Width - this._resizerWidth) + 'px';
		child.style.left = xleft + 'px';
		child.style.overflow = 'hidden';
		
		this._left = xleft;
		this._htmlElem = child;
		this.SetContent();
		if (!isLast) {
			child = CreateChild(parent, 'div');
			child.className = 'wm_inbox_headers_separate';

			var left;
			if (window.RTL) {
				left = (xleft - this._resizerWidth) + 'px';
			} else {
				left = (xleft + this.Width - this._resizerWidth) + 'px';
			}
			
			child.style.width = this._resizerWidth + 'px';
			child.style.left = left;

			CreateChild(child, 'div');
			if (this._isResize) {
				if (window.RTL) {
					this.resizer = new CVerticalResizer(child, parent, this._resizerWidth, 10, xleft + this.Width - this.MinWidth,
						xleft + this.Width - this._resizerWidth, resizeHandler, 2);
				} else {
					this.resizer = new CVerticalResizer(child, parent, this._resizerWidth, xleft + this.MinWidth, 10,
						xleft + this.Width - this._resizerWidth, resizeHandler, 2);
				}
			}
			this._separator = child;
			return xleft + this.Width;
		}
		return xleft;
	}
};

function CVariableTable(sortHandler, selection, dragNDrop, controller)
{
	this._sortHandler = sortHandler;
	
	this._columnsCount = 0;
	this._columnsArr = [];
	this._sortedColumn = null;
	this.isSortFree = false;
	this._width = 0;
	
	this._headers = null;
	this._eLinesCont = null;
	this._linesTbl = null;
	
	this.oSelection = selection;
	this._dragNDrop = dragNDrop;
	this._timer = null;
	this.LastClickLineId = '';
	
	this._controller = controller;
	
	this._lineHeight = 20;

	this.HorBordersWidth = 0;
	this.VertBordersWidth = 0;

	this.bFilled = false;
	this._eInfoMsg = null;
}

CVariableTable.prototype = 
{
	_clean: function ()
	{
		this.removeMessageFromList();
		this.oSelection.Free();
		if (null != this._dragNDrop) {
			this._dragNDrop.SetSelection(null);
		}
		CleanNode(this._eLinesCont);
		this._eInfoMsg = null;
		this._linesTbl = null;
		this.LastClickLineId = '';
		for (var i = this._columnsCount - 1; i >= 0; i--) {
			this._columnsArr[i].clean();
		}
	},
	
	cleanLines: function (msg1, msg2)
	{
		this._clean();
		if (msg2 != undefined) {
			this.addMessageToList(msg1, false, true);
			msg1 = msg2;
		}
		var div = CreateChild(this._eLinesCont, 'div', [['class', 'wm_inbox_info_message']]);
		div.innerHTML = msg1;
	},
	
	resizeColumnsHeight: function ()
	{
		var hOffsetHeight = this._headers.offsetHeight;
		var lOffsetHeight = this._eLinesCont.offsetHeight;
		var minRightWidth = 0;
		for (var i = this._columnsCount - 1; i >= 0; i--) {
			var column = this._columnsArr[i];
			if (column.resizer != null) {
				column.resizer.updateVerticalSize(hOffsetHeight - 1, hOffsetHeight + lOffsetHeight - 2);
				if (!window.RTL) {
					column.resizer.updateMinRightWidth(minRightWidth);
				}
			}
			minRightWidth += (i == this._columnsCount-1) ? column.MinWidth : column.Width;
		}
	},
	
	resizeColumnsWidth: function (number)
	{
		var left = this._columnsArr[number].resizeWidth();
		if (window.RTL) {
			var right = left;
			for (var i = number - 1; i >= 0; i--) {
				var column = this._columnsArr[i];
				if (column.resizer != null) {
					column.resizer.updateMinRightWidth(this._width - (right - column.MinWidth));
				}
				right -= column.resizeRight(right, (i==0));
			}
		}
		else {
			for (var i = number + 1; i < this._columnsCount; i++) {
				left = this._columnsArr[i].resizeLeft(left);
			}
			this._width = left;
		}
		this.resizeColumnsHeight();
	},
	
	resize: function (width)
	{
		if (this._linesTbl != null) {
			if (this._linesTbl.offsetHeight > this._eLinesCont.offsetHeight) {
				width = width - 18;
			}
			this._linesTbl.style.width = width + 'px';
		}
		
		if (window.RTL) {
			var right = width;
			if (Browser.mozilla && Browser.version >=3 || Browser.opera && Browser.version < 9.5 || Browser.safari || Browser.chrome) {
				right = this._eLinesCont.clientWidth;
				if (this._linesTbl != null) {
					this._linesTbl.style.width = right + 'px';
				}
			}
			for (var i = this._columnsCount - 1; i >= 0; i--) {
				var column = this._columnsArr[i];
				if (column.resizer != null) {
					column.resizer.updateMinRightWidth(width - (right - column.MinWidth));
				}
				right -= column.resizeRight(right, (i == 0));
				if (column.resizer != null) {
					if (i > 0) {
						column.resizer.updateMinLeftWidth(right - this._columnsArr[i-1].MinWidth);
					}
				}
			}
			this._width = width;
		}
		else {
			var lastCell = this._columnsArr[this._columnsCount - 1];
			if (lastCell != null) {
				lastCell.SetWidth(width - this._width);
			}
		}
		this.resizeColumnsHeight();
	},
	
	resizeFromField: function ()
	{

	},
	
	getHeight: function ()
	{
		return this._headers.offsetHeight + this._eLinesCont.offsetHeight;
	},
	
	getLines: function ()
	{
		return this._eLinesCont;
	},
	
	setLinesHeight: function (height)
	{
		this._eLinesCont.style.height = (height - this._headers.offsetHeight) +'px';
	},
	
    changeField: function (oldId, newId, params)
    {
        for (var i = 0; i < this._columnsCount; i++) {
            var column = this._columnsArr[i];
            if (column.id == oldId) {
                column.changeField(newId, params, true);
            }
        }
    },

	addColumn: function (id, params)
	{
		var column = new CVariableColumn(id, params, this.oSelection);
		this._columnsArr[this._columnsCount++] = column;
		return column;
	},
	
	setSort: function (sortField, sortOrder)
	{
		if (this.isSortFree) {
			return;
		}
		if (this._sortedColumn != null && this._sortedColumn.sortField !== sortField) {
			this._sortedColumn.RemoveSort();
		}
		for (var i = 0; i < this._columnsCount; i++) {
			var column = this._columnsArr[i];
			if (column.sortField === sortField) {
				column.setSort(sortOrder);
				this._sortedColumn = column;
			}
		}
	},

	freeSort: function (bEveryplace)
	{
		this.isSortFree = bEveryplace;
		for (var i = 0; i < this._columnsCount; i++) {
			var oColumn = this._columnsArr[i];
			if (bEveryplace || oColumn.id !== IH_DATE) {
				oColumn.freeSort();
			}
			else {
				oColumn.useSort();
			}
		}
	},
	
	useSort: function (bSortByFlags)
	{
		this.isSortFree = false;
		for (var i = 0; i < this._columnsCount; i++) {
			var bFlagField = (this._columnsArr[i].id === IH_FLAGGED
				|| this._columnsArr[i].id === IH_ATTACHMENTS);
			if (bFlagField && !bSortByFlags) {
				this._columnsArr[i].freeSort();
			}
			else {
				this._columnsArr[i].useSort();
			}
		}
	},
	
	setNoMessagesFoundMessage: function (sText)
	{
		var
			$clearSearchCont = null
		;
		
		sText = sText || Lang.InfoNoMessagesFound;
		this._clean();
		
		$clearSearchCont = $('<div style="padding: 12px;"></div>').appendTo(this._eLinesCont);
		this._addRightTopLink($clearSearchCont, Lang.SearchClear, ClearSearchHandler)
		
		$('<div class="wm_inbox_info_message"></div>')
			.html(sText)
			.appendTo(this._eLinesCont);
	},
	
	setSearchErrorMessage: function ()
	{
		var
			$clearSearchCont = null
		;
		
		this._clean();
		
		$clearSearchCont = $('<div style="padding: 12px;"></div>').appendTo(this._eLinesCont);
		this._addRightTopLink($clearSearchCont, Lang.BackToMessageList, ClearSearchHandler);
		this._addRightTopLink($clearSearchCont, Lang.RetryGettingMessageList, RetryRetrievingMessagesHandler);
		
		$('<div class="wm_inbox_info_message"></div>')
			.html(Lang.ErrorDuringSearch)
			.appendTo(this._eLinesCont);
	},
	
	setRetrievingErrorMessage: function ()
	{
		var
			$clearSearchCont = null
		;
		
		this._clean();
		
		$clearSearchCont = $('<div style="padding: 12px;"></div>').appendTo(this._eLinesCont);
		this._addRightTopLink($clearSearchCont, Lang.RetryGettingMessageList, RetryRetrievingMessagesHandler)
		
		$('<div class="wm_inbox_info_message"></div>')
			.html(Lang.ErrorRetrievingMessages)
			.appendTo(this._eLinesCont);
	},
	
	_addRightTopLink: function (oParent, sText, fClick)
	{
		$('<a style="margin-left: 10px;"></a>')
			.attr({'href': 'javascript: void(0)', 'class': 'wm_search_result_clear_button'})
			.html(sText)
			.click(fClick)
			.appendTo(oParent);
	},

	addMessageToList: function (msg, addClearLink, addStopLink)
	{
		this.removeMessageFromList();
		if (typeof(msg) == 'string' && msg.length > 0) {
			this._eInfoMsg = CreateChild(this._eLinesCont, 'div', [['class', 'wm_search_result_header']]);
			if (addClearLink) {
				this._addRightTopLink(this._eInfoMsg, Lang.SearchClear, ClearSearchHandler);
			}
			if (addStopLink) {
				this._addRightTopLink(this._eInfoMsg, Lang.SearchStop, StopSearchHandler)
			}
			var span = CreateChild(this._eInfoMsg, 'div', [['class', 'wm_search_result_title']]);
			span.innerHTML = msg;
		}
	},

	removeMessageFromList: function ()
	{
		if (this._eInfoMsg !== null) {
			this._eLinesCont.removeChild(this._eInfoMsg);
			this._eInfoMsg = null;
		}
	},

	fill: function (objsArr, screenId, msg, addClearLink, bNextPageMsgList, bEqualMsgsList)
	{
		if (!bNextPageMsgList) {
			if (!bEqualMsgsList) {
				this._scrollLines(0);
			}
			this.oSelection.SaveCheckedLines();
			this._clean();
		}
		else {
			this.removeMessageFromList();
		}
		if (null != this._dragNDrop) {
			this._dragNDrop.SetSelection(this.oSelection);
		}
		this.addMessageToList(msg, addClearLink, false);

		var tbl = this._linesTbl;
		if (!bNextPageMsgList || tbl === null) {
			tbl = CreateChild(this._eLinesCont, 'table', [['dir', 'ltr']]);
			this._linesTbl = tbl;
		}
		var tr = null;
		var iRowIndex = this.oSelection.iLength;
		for (var i = 0; i < objsArr.length; i++) {
			tr = tbl.insertRow(iRowIndex++);
			var obj = objsArr[i];
			var line = this._controller.CreateLine(obj, tr, screenId);
			for (var j = 0; j < this._columnsCount; j++) {
				var column = this._columnsArr[j];
				var td = tr.insertCell(j);
				var span = CreateChild(td, 'span', [['class', 'wm_inbox_item_cell_content']]);
				line.SetContainer(column.field, span);
				td.style.textAlign = column.Align;
				column.SetLineElem(td);
				td.style.width = column.Width + 'px';
			}
			this.oSelection.addLine(line);
			if (null != this._dragNDrop) this._dragNDrop.addDragObject(tr);
			this._controller.SetEventsHandlers(this, tr);
		}
		if (tr != null) {
			this._lineHeight = tr.offsetHeight;
		}
		this.oSelection.SetCheckboxChecked(false);
		this.bFilled = true;
		return this.oSelection.CheckSavedLines();
	},
	
	_scrollLines: function (lineIndex)
	{
		if (lineIndex == undefined || lineIndex == 0) {
			this._eLinesCont.scrollTop = '0';
			return;
		}
		var lineShift = (lineIndex + 1) * this._lineHeight;
		var linesHeight = this._eLinesCont.offsetHeight;
		var scrollTop = GetScrollY(this._eLinesCont);
		if (lineShift > linesHeight) {
			this._eLinesCont.scrollTop = (lineShift > scrollTop) ? lineShift - linesHeight : lineShift;
		}
	},
	
	_clickCheckLine: function (sId)
	{
		if (sId.length === 0) return;
		var tr = this.oSelection.CheckLine(sId);
		if (this._controller.type === TYPE_MESSAGE)
		{
			this._controller.ClickLine(sId, this);
		}
		else
		{
			this._controller.ClickLine(sId, tr.global);
		}
		this._scrollLines(this.oSelection.GetLineIndex());
	},
	
	onKeyDown: function (key, ev)
	{
		switch (key) {
			case Keys.enter:
				if (typeof(this._controller.DblClickLine) != 'function') {
					return;
				}
				var tr = this.oSelection.getCurrentTr();
				if (tr == null) {
					return;
				}
				this._controller.DblClickLine(tr, this);
				break;
			case Keys.up:
				if (ev.shiftKey) {
					var id = this.oSelection.GetPrevLineId();
					if (id < 0) {
						return;
					}
					this.oSelection.CheckShiftLine(id);
					this._scrollLines(this.oSelection.GetLineIndex());
				}
				else if (!ev.ctrlKey) {
					var id = this.oSelection.GetPrevViewLineId();
					this._clickCheckLine(id);
				}
				break;
			case Keys.down:
				if (ev.shiftKey) {
					var id = this.oSelection.GetNextLineId();
					if (id < 0) {
						return;
					}
					this.oSelection.CheckShiftLine(id);
					this._scrollLines(this.oSelection.GetLineIndex());
				}
				else if (!ev.ctrlKey) {
					var id = this.oSelection.GetNextViewLineId();
					this._clickCheckLine(id);
				}
				break;
			case Keys.del:
				if (!ev.shiftKey && !ev.ctrlKey) {
					this._controller.del();
				}
				break;
			case Keys.home:
				var id = this.oSelection.GetFirstLineId();
				this._clickCheckLine(id);
				break;
			case Keys.end:
				var id = this.oSelection.GetLastLineId();
				this._clickCheckLine(id);
				break;
			case Keys.pageUp:
				var visibleCount = Math.round(this._eLinesCont.offsetHeight/this._lineHeight);
				var id = this.oSelection.GetPrevViewLineId(visibleCount);
				this._clickCheckLine(id);
				break;
			case Keys.pageDown:
				var visibleCount = Math.round(this._eLinesCont.offsetHeight/this._lineHeight);
				var id = this.oSelection.GetNextViewLineId(visibleCount);
				this._clickCheckLine(id);
				break;
			case Keys.a:
				if (ev.ctrlKey) {
					this.oSelection.CheckAll();
				}
				break;
		}
	},
	
	build: function (parent)
	{
		var div = CreateChild(parent, 'div');
		div.className = this._controller.listContanerClass;
		this._inbox_contaner = div;

		var borders = GetBorders(div);
		this.HorBordersWidth = borders.Left + borders.Right;
		this.VertBordersWidth = borders.Top + borders.Bottom;

		this._headers = CreateChild(div, 'div', [['class', 'wm_inbox_headers']]);
		
		$(this._headers).disableSelection();

		if (window.RTL) {
			this._columnsArr.reverse();
		}
		var left = 0;
		for (var i=0; i<this._columnsCount; i++) {
			var column = this._columnsArr[i];
			var isLast = (i == this._columnsCount-1);
			if (window.RTL) {
				isLast = (i == 0);
			}
			left = column.build(this._headers, left, isLast, this._controller.resizeHandler + '(' + i + ');', this._sortHandler);
		}
		this._width = left;
		
		var eLinesCont = CreateChild(div, 'div', [['class', 'wm_inbox_lines']]);
		$(eLinesCont).disableSelection();
		if (Browser.mozilla) {
			eLinesCont.tabIndex = 1;
		}
		eLinesCont.onkeydown = function (ev) {
			var key = Keys.getCodeFromEvent(ev);
			if (key == Keys.up || key == Keys.down || key == Keys.pageUp || key == Keys.pageDown || key == Keys.space) {
				return false;
			}
			return true;
		};
		this._eLinesCont = eLinesCont;
	}
};

function CCheckBoxCell()
{
	this.Node = document.createElement('input');
	this.Node.type = 'checkbox';

	this.SetContainer = function (container) {
		container.appendChild(this.Node);
	};
}

function CImageCell(className, id, content)
{
	this.Node = document.createElement('div');
	this.Node.className = className + ' ' + content;
	if (id.length > 0) this.Node.id = id;

	this.SetContainer = function (container) {
		container.appendChild(this.Node);
	};
	
	this.SetContent = function (content) {
		this.Node.className = className + ' ' + content;
	};
}

function CTextCell(text, title)
{
	this.Content = text;
	this.title = (title) ? ' title="' + title + '"' : '';
	this.Node = null;
	
	this.SetContainer = function (container) {
		this.Node = container;
		this._applyContentToContainer();
	};

	this.SetContent = function (content) {
		this.Content = content;
		this._applyContentToContainer();
	};
	
	this._applyContentToContainer = function () {
		this.Node.innerHTML = '<span' + this.title + '>' + this.Content + '</span>';
	};
};

function CSelection(fillSelectedContactsHandler)
{
	this._fillSelectedContactsHandler = (fillSelectedContactsHandler) ? fillSelectedContactsHandler : null;
	this._lines = [];
	this.iLength = 0;
	this._currIdx = -1;
	this._checkbox = null;

	this._savedLinesIds = [];
	this._savedCurrId = '';
	
	this._shiftStartIdx = -1;
	this._shiftEndIdx = -1;
}

CSelection.prototype = 
{
	SaveCheckedLines: function ()
	{
		for (var i = this.iLength - 1; i >= 0; i--) {
			var line = this._lines[i];
			if (line.Checked == true) {
				this._savedLinesIds.push(line.id);
			}
		}
	},

	ClearSavedLines: function ()
	{
		this._savedLinesIds = [];
	},

	CheckSavedLines: function ()
	{
		var
			line = null,
			currLine = null,
			i = this.iLength - 1
		;
		
		for (; i >= 0; i--) {
			line = this._lines[i];
			if (this._lineIdInSaved(line.id)) {
				line.Check();
			}
			if (line.id == this._savedCurrId) {
				line.view(true);
				this._currIdx = i;
				currLine = line;
			}
		}
		this.ClearSavedLines();
		
		return currLine;
	},

	clearCurrLine: function ()
	{
		var line = this._lines[this._currIdx];
		if (line) {
			line.view(false);
		}
		this._currIdx = -1;
		this._savedCurrId = '';
	},

	_lineIdInSaved: function (id)
	{
		for (var i = 0; i < this._savedLinesIds.length; i++) {
			if (id == this._savedLinesIds[i]) {
				return true;
			}
		}
		return false;
	},

	SetCheckBox: function (checkbox)
	{
		var selection = this;
		checkbox.onclick = function () {
			if (this.checked) {
				selection.CheckAll();
			}
			else {
				selection.UncheckAll();
			}
		};
		this._checkbox = checkbox;
	},
	
	Free: function ()
	{
		this._lines = [];
		this.iLength = 0;
		this._currIdx = -1;
		this._shiftStartIdx = -1;
		this._shiftEndIdx = -1;
	},
	
	addLine: function (line)
	{
		this._lines.push(line);
		this.iLength = this._lines.length;
	},

	copyFlags: function (oMsgsList, iListScreenId)
	{
		var
			iMsgIdx = 0,
			iLen = oMsgsList.list.length,
			oMsg = null,
			sId = '',
			iLineIdx = 0,
			iStartLineIdx = 0,
			oLine = null,
			iUnread = 0
		;
		for (; iMsgIdx < iLen; iMsgIdx++) {
			oMsg = oMsgsList.list[iMsgIdx];
			if (oMsg === undefined) {
				continue;
			}
			sId = oMsg.getIdForList(iListScreenId);
			for (iLineIdx = iStartLineIdx; iLineIdx < this.iLength; iLineIdx++) {
				oLine = this._lines[iLineIdx];
				if (sId === oLine.id) {
					if (oLine.Hidden) {
						oMsgsList.list.splice(iMsgIdx, 1);
						iMsgIdx--;
					}
					else {
						oMsg.deleted = oLine.deleted;
						oMsg.flagged = oLine.flagged;
						oMsg.forwarded = oLine.forwarded;
						if (!oMsg.read && oLine.read) {
							iUnread -= 1;
						}
						if (oMsg.read && !oLine.read) {
							iUnread += 1;
						}
						oMsg.read = oLine.read;
						oMsg.replied = oLine.replied;
						oMsgsList.list[iMsgIdx] = oMsg;
					}
					iStartLineIdx = iLineIdx + 1;
					break;
				}
			}
		}
		oMsgsList.newMsgsCount += iUnread;
		return oMsgsList;
	},

	SetParams: function (idArray, field, value, isAllMess)
	{
		var readed = 0;
		if (isAllMess) {
			for (var i = this.iLength - 1; i >= 0; i--) {
				var line = this._lines[i];
				readed += line.SetParams(field, value);
			}
		}
		else {
			for (var j = 0; j < idArray.length; j++) {
				for (var i = this.iLength - 1; i >= 0; i--) {
					var line = this._lines[i];
					if (line.id == idArray[j]) {
						readed += line.SetParams(field, value);
						break;
					}
				}
			}
		}
		return readed;
	},
	
	getLineId: function (sObjectId)
	{
		var
			iIndex = 0,
			oLine = null
		;
		
		for (; iIndex < this.iLength; iIndex++) {
			oLine = this._lines[iIndex];
			if (oLine.sContactId === sObjectId) {
				return oLine.id;
			}
		}
		
		return '';
	},

	GetFirstLine: function ()
	{
		for (var i = 0; i < this.iLength; i++) {
			var line = this._lines[i];
			if (!line.Hidden) {
				return line;
			}
		}
		return null;
	},

	getFirstCheckedMessage: function ()
	{
		for (var i = 0; i < this.iLength; i++) {
			var line = this._lines[i];
			if (!line.Hidden && line.Checked) {
				return line.getMessage();
			}
		}
		return null;
	},

	hideLines: function (idArray)
	{
		this.SaveCheckedLines();
		for (var j = 0; j < idArray.length; j++) {
			for (var i = this.iLength - 1; i >= 0; i--) {
				var line = this._lines[i];
				if (line.id == idArray[j]) {
					line.hide();
					break;
				}
			}
		}
	},

	ChangeLineId: function (msg, newId)
	{
		for (var i = this.iLength - 1; i >= 0; i--) {
			var line = this._lines[i];
			if (line.isCorrectIdData(msg)) {
				line.ChangeFromSubjData(msg, newId);
			}
		}
	},

	GetViewedLine: function ()
	{
		if (this._currIdx != -1) {
			return this._lines[this._currIdx];
		}
		return null;
	},
	
	GetCheckedLines: function ()
	{
		var idArray = [];
		var sizeArray = [];
		var unreaded = 0;
		for (var i = this.iLength - 1; i >= 0; i--) {
			var line = this._lines[i];
			if (line.Checked == true) {
				if (!line.read) {
					unreaded++;
				}
				idArray.push(line.id);
				sizeArray.push(line.MsgSize);
			}
		}
		if (idArray.length == 0) {
			var viewedLine = this.GetViewedLine();
			if (viewedLine != null) {
				if (!viewedLine.read) {
					unreaded++;
				}
				idArray.push(viewedLine.id);
				sizeArray.push(viewedLine.MsgSize);
			}
		}
		return {idArray: idArray, SizeArray: sizeArray, Unreaded: unreaded};
	},

	GetLineById: function (id)
	{
		for (var i = this.iLength - 1; i >= 0; i--) {
			var line = this._lines[i];
			if (line.id == id) {
				return line;
			}
		}
		return null;
	},
	
	/* return bool */
	SingleForDrag: function (id)
	{
		var checked = false;
		var singleChecked = true;
		for (var i = this.iLength - 1; i >= 0; i--) {
			var line = this._lines[i];
			if (line.Checked == true) {
				if (line.id == id) {
					checked = true;
				} else {
					singleChecked = false;
				}
			}
		}
		if (!checked || singleChecked) {
			return true;
		}
		return false;
	},
	
	GetLineIndex: function ()
	{
		return this._currIdx;
	},
	
	_readyKeysCheckLines: function ()
	{
		return (this.iLength != 0);
	},
	
	GetFirstLineId: function ()
	{
		var oLine = this.GetFirstLine();
		if (oLine !== null) {
			return oLine.id;
		}
		return '';
	},
	
	GetNextLineId: function ()
	{
		if (!this._readyKeysCheckLines()) {
			return '';
		}
		var indexes = this._getShiftIndexes();
		var idx = indexes.end + 1;
		if (idx >= this.iLength) {
			idx = this.iLength - 1;
		}
		return this._lines[idx].id;
	},
	
	GetPrevLineId: function ()
	{
		if (!this._readyKeysCheckLines()) {
			return '';
		}
		var indexes = this._getShiftIndexes();
		var idx = indexes.end - 1;
		if (idx < 0) {
			idx = 0;
		}
		return this._lines[idx].id;
	},
	
	GetNextViewLineId: function (count)
	{
		if (!this._readyKeysCheckLines()) {
			return '';
		}
		if (!count) {
			count = 1;
		}
		var idx = this._currIdx + count;
		if (idx >= this.iLength) {
			idx = this.iLength - 1;
		}
		return this._lines[idx].id;
	},
	
	GetPrevViewLineId: function (count)
	{
		if (!this._readyKeysCheckLines()) {
			return '';
		}
		if (!count) {
			count = 1;
		}
		var idx = this._currIdx - count;
		if (idx < 0) {
			idx = 0;
		}
		return this._lines[idx].id;
	},
	
	GetLastLineId: function ()
	{
		if (!this._readyKeysCheckLines()) {
			return '';
		}
		return this._lines[this.iLength - 1].id;
	},
	
	getCurrentTr: function ()
	{
		if (this._currIdx < 0 || this._currIdx >= this.iLength) {
			return null;
		}
		return this._lines[this._currIdx].Node;
	},

	DragItemsNumber: function (id)
	{
		var findLine = null;
		var number = 0;
		for (var i = this.iLength - 1; i >= 0; i--) {
			var line = this._lines[i];
			if (line.id == id) {
				findLine = line;
			}
			if (line.Checked) {
				number++;
			}
		}
		if (null == findLine) {
			return 0;
		}
		else if (findLine.Checked) {
			return number;
		}
		else {
			this.CheckLine(id);
			return 1;
		}
	},
	
	FlagLine: function (id)
	{
		for (var i = this.iLength - 1; i >= 0; i--) {
			var line = this._lines[i];
			if (line.id == id) {
				if (line.flagged) {
					line.Unflag();
				}
				else {
					line.Flag();
				}
			}
		}
	},
	
	CheckLine: function (id, bUncheckOther)
	{
		var node = null;
		for (var i = this.iLength - 1; i >= 0; i--) {
			var line = this._lines[i];
			if (line.id == id) {
				line.view(true);
				if (bUncheckOther) {
					line.Check();
				}
				this._currIdx = i;
				this._savedCurrId = id;
				node = line.Node;
			}
			else {
				line.view(false);
				if (bUncheckOther) {
					line.Uncheck();
				}
			}
		}
		this.ReCheckAllBox();
		return node;
	},
	
	CheckCtrlLine: function (id)
	{
		for (var i = this.iLength - 1; i >= 0; i--) {
			var line = this._lines[i];
			if (line.id == id) {
				if (line.Checked == false) {
					line.Check();
					if (this._shiftStartIdx == -1) {
						this._shiftStartIdx = i;
						this._shiftEndIdx = i;
					}
				}
				else {
					line.Uncheck();
					if (this._shiftStartIdx == i) {
						this._shiftStartIdx = -1;
						this._shiftEndIdx = -1;
					}
				}
			}
		}
		this.ReCheckAllBox();
	},
	
	_getShiftIndexes: function (id)
	{
		var startIdx = -1;
		var endIdx = -1;
		if (this._shiftStartIdx != -1) {
			startIdx = this._shiftStartIdx;
		}
		for (var i = 0; i < this.iLength; i++) {
			var line = this._lines[i];
			if (startIdx == -1 && line.Checked == true) {
				startIdx = i;
			}
			if (line.id == id) {
				endIdx = i;
			}
		}
		if (startIdx == -1) {
			startIdx = this._currIdx;
		}
		if (startIdx == -1) {
			startIdx = endIdx;
		}
		if (endIdx == -1) {
			endIdx = this._shiftEndIdx;
		}
		if (endIdx == -1) {
			endIdx = startIdx;
		}
		this._shiftStartIdx = startIdx;
		this._shiftEndIdx = endIdx;
		return {start: startIdx, end: endIdx};
	},

	CheckShiftLine: function (id)
	{
		var indexes = this._getShiftIndexes(id);
		var startIdx = indexes.start;
		var endIdx = indexes.end;
		if (startIdx > endIdx) {
			startIdx = indexes.end;
			endIdx = indexes.start;
		}
		for (var i = 0; i < this.iLength; i++) {
			var line = this._lines[i];
			if (i < startIdx || i > endIdx) {
				line.Uncheck();
			}
			else {
				line.Check();
			}
		}
		this.ReCheckAllBox();
	},
	
	CheckAll: function ()
	{
		for (var i = this.iLength - 1; i >= 0; i--) {
			this._lines[i].Check();
		}
		this.CheckSelectedContacts();
		this.SetCheckboxChecked(true);
	},
	
	UncheckAll: function ()
	{
		for (var i = this.iLength - 1; i >= 0; i--) {
			this._lines[i].Uncheck();
		}
		this._shiftStartIdx = -1;
		this._shiftEndIdx = -1;
		this.CheckSelectedContacts();
		this.SetCheckboxChecked(false);
	},
	
	ReCheckAllBox: function ()
	{
		var isAllCheck = true;
		for (var i = this.iLength - 1; i >= 0; i--) {
			if (this._lines[i].Checked == false) {
				isAllCheck = false;
				break;
			}
		}
		this.SetCheckboxChecked(isAllCheck);
		this.CheckSelectedContacts();
	},
	
	CheckSelectedContacts: function ()
	{
		if (this._fillSelectedContactsHandler != null) {
			var contactsArray = [];
			for (var i = 0; i < this.iLength; i++) {
				var line = this._lines[i];
				if (line.Checked) {
					contactsArray.push(line.Node.email);
				}
			}
			var sCurrContactId = '';
			var currIsGroup = false;
			if (this._currIdx != -1) {
				var currLine = this._lines[this._currIdx];
				sCurrContactId = currLine.sContactId;
				currIsGroup = (currLine.isGroup ? true : false);
			}
			this._fillSelectedContactsHandler.call({contactsArray: contactsArray, sCurrContactId: sCurrContactId, CurrIsGroup: currIsGroup});
		}
	},
	
	SetCheckboxChecked: function (checkedValue)
	{
		if (null != this._checkbox) {
			this._checkbox.checked = checkedValue;
		}
	}
};

function CDragNDrop(langField)
{
	this.oSelection = null;
	this._langField = langField;
	this._dragObjects = [];
	this._dragCount = 0;
	this._dropObjects = [];
	this._dropCount = 0;
	this._dropContainer = null;
	this._scrollY = 0;
	this._handle = CreateChild(document.body, 'div', [['class', 'wm_hide']]);
	this._handleImg = CreateChild(this._handle, 'div', [['class', 'wm_drag_handle_img']]);
	this._handleText = CreateChild(this._handle, 'span');
	this._dragId = '';
	this._dropId = '';
	this._dropElem = null;
	this._dropClassName = '';
	this.doMoveToInbox = true;
	this._inboxId = '';
	this._x1 = 0;
	this._y1 = 0;
	this._x2 = 0;
	this._y2 = 0;
	this.first = true;
}

CDragNDrop.prototype = {
	SetMoveToInbox: function (doMoveToInbox)
	{
		this.doMoveToInbox = doMoveToInbox;
	},
	
	SetDropContainer: function (dropContainer)
	{
		this._dropContainer = dropContainer;
	},
	
	SetInboxId: function (id)
	{
		this._inboxId = id;
	},
	
	SetSelection: function (selection)
	{
		this.oSelection = selection;
		if (null == selection) {
			this._dragObjects = [];
			this._dragCount = 0;
		}
	},
	
	addDragObject: function (element)
	{
		this._dragObjects[this._dragCount] = element;
		this._dragCount++;
	},
	
	SetCoordinates: function (element)
	{
		var bounds = GetBounds(element);
		element._x1 = bounds.Left;
		element._y1 = bounds.Top - this._scrollY;
		element._x2 = bounds.Left + bounds.Width;
		element._y2 = bounds.Top - this._scrollY + bounds.Height;
		if (this._x1 == 0 && this._y1 == 0 && this._x2 == 0 && this._y2 == 0) {
			this._x1 = element._x1;
			this._y1 = element._y1 + this._scrollY;
			this._x2 = element._x2;
			this._y2 = element._y2 + this._scrollY;
		}
		else {
			if (this._x1 > element._x1) {
				this._x1 = element._x1;
			};
			if (this._y1 > element._y1) {
				this._y1 = element._y1;
			};
			if (this._x2 < element._x2) {
				this._x2 = element._x2;
			};
			if (this._y2 < element._y2) {
				this._y2 = element._y2;
			}
		}
	},
	
	addDropObject: function (element)
	{
		this.SetCoordinates(element);
		this._dropObjects[this._dropCount] = element;
		this._dropCount++;
	},
	
	resize: function ()
	{
		this._x1 = 0;
		this._y1 = 0;
		this._x2 = 0;
		this._y2 = 0;
		for (var i = 0; i < this._dropCount; i++) {
			this.SetCoordinates(this._dropObjects[i]);
		}
	},
	
	CleanDropObjects: function ()
	{
		this._dropObjects = [];
		this._dropCount = 0;
	},
	
	ready: function ()
	{
		if (null == this.oSelection) return false;
		if (0 == this._dragCount) return false;
		if (0 == this._dragId.length) return false;
		return true;
	},
	
	RequestDrag: function (e, element)
	{
		if (!e.ctrlKey && !e.shiftKey) {
			this._dragId = element.id;
			this._moveCount = 0;
			element.blur();
			var obj = this;
			document.body.onmousemove = function (e) {
				e = e ? e : event;
				obj._moveCount++;
				if (obj._moveCount >= 5) {
					obj.startDrag(e);
				}
			};
		}
	},
	
	startDrag: function (e)
	{
		document.body.onmousemove = function () {};
		if (this.ready()) {
			var number = this.oSelection.DragItemsNumber(this._dragId);
			var handle = this._handle;
			handle.className = 'wm_drag_handle';
			handle.style.top = (e.clientY + 5) + 'px';
			handle.style.left = (e.clientX + 5) + 'px';
			this._handleText.innerHTML = number + ' ' + Lang[this._langField];
			this._handleImg.className = 'wm_not_drag_handle_img';
			var obj = this;
			document.body.onmousemove = function(e) {
				e = e ? e : event;
				obj.ProcessDrag(e); 
			};
			document.body.onmouseup = function() {
				obj.endDrag();
			};
		}
	},
	
	ProcessDrag: function (e)
	{
		var x = e.clientX;
		var y = e.clientY;
		with (this._handle.style) {
			top = (e.clientY + 5) + 'px';
			left = (e.clientX + 5) + 'px';
		}
		if (null != this._dropElem) {
			this._dropElem.className = this._dropClassName;
		}
		var scrollY = GetScrollY(this._dropContainer);
		if (scrollY != this._scrollY) {
			this._scrollY = scrollY;
			this.resize();
		}
		if (x > this._x1 && x < this._x2 && y > this._y1 && y < this._y2) {
			for (var i = 0; i < this._dropCount; i++) {
				var element = this._dropObjects[i];
				if (x > element._x1 && x < element._x2 && y > element._y1 && y < element._y2) {
					if (-1 == this._dragId.indexOf(element.id) && (this.doMoveToInbox || this._inboxId != element.id)) {
						this._dropId = element.id;
						this._dropElem = element;
						this._dropClassName = element.className;
						this._handleImg.className = 'wm_drag_handle_img';
						document.body.style.cursor = 'pointer';
						element.className = 'wm_folder_over';
					}
					else {
						this._clearDropElem();
					}
					break;
				}
				else {
					this._clearDropElem();
				}
			}
		}
		else {
			this._clearDropElem();
		}
	},

	_clearDropElem: function ()
	{
		this._dropId = '';
		this._dropElem = null;
		this._handleImg.className = 'wm_not_drag_handle_img';
		document.body.style.cursor = 'auto';
	},
	
	endDrag: function ()
	{
		if (this._dropId.length > 0) {
			MoveToFolderHandler(this._dropId);
			this.first = false;
		}
		this._clearDropElem();
		this._handle.className = 'wm_hide';
		this._dragId = '';
		document.body.onmousemove = function () {};
		document.body.onmouseup = function () {};

		for (var i = 0; i < this._dropCount; i++) {
			var element = this._dropObjects[i];
			element.className = (element.className == 'wm_select_folder')
				? 'wm_select_folder' : 'wm_folder';
		}
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
