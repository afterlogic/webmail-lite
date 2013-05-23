/*
 * Classes:
 *	CMessageListDisplay(selection, dragNDrop, controller)
 */

function CMessageListDisplay(selection, dragNDrop, controller)
{
	this._eLinesCont = null;
	this._linesTbl = null;
	this._linesContainer = null;
	this._controller = controller;
	this._totalWidth = 160; // sum of the widths of all columns (except for From) and paddings of all columns
	this.oSelection = selection;
	this._dragNDrop = dragNDrop;
	this._lineHeight = 39; // the height of one line is needed for scrolling by keyboard
	this._columnsArr = [];
	this._columnsCount = 0;
	this.LastClickLineId = '';
	this._fromWidth = 150;

	this.HorBordersWidth = 2;
	this.VertBordersWidth = 0;
	this.LinesMarginWidth = 2;

	this.bFilled = false;
	this._eInfoMsg = null;
}

CMessageListDisplay.prototype =
{
	_clean: function ()
	{
		this.oSelection.Free();
		if (null != this._dragNDrop) {
			this._dragNDrop.SetSelection(null);
		}
		CleanNode(this._eLinesCont);
		this._eInfoMsg = null;
		this._linesTbl = null;
		this.LastClickLineId = '';
	},

	resize: function (width)
	{
		var maxWidth = this._linesContainer.offsetWidth;
		if (!maxWidth) maxWidth = width;
		var minWidth = this._totalWidth + 200;
		width = Validator.correctNumber(width - this.HorBordersWidth - this.LinesMarginWidth, minWidth, maxWidth);

		this._eLinesCont.style.width = width + 'px';
		var scrollWidth = 0;
		if (this._linesTbl != null) {
			if (this._linesTbl.offsetHeight > this._eLinesCont.offsetHeight) {
				scrollWidth = 18;
			}
			this._linesTbl.style.width = (width - scrollWidth) + 'px';
		}
		this._fromWidth = width - scrollWidth - this._totalWidth;
		this.resizeFromField();
	},

	resizeFromField: function ()
	{
		var firstLine = this.oSelection.GetFirstLine();
		if (firstLine != null) {
			firstLine.fromDisplay.style.width = (this._fromWidth) + 'px';
		}
	},

	setLinesHeight: function (height)
	{
		this._eLinesCont.style.height = height +'px';
	},

	getHeight: function ()
	{
		return this._eLinesCont.offsetHeight;
	},

	addColumn: function (id, params)
	{
		var columnIndex = this._columnsCount++;
		this._setColumn(id, params, columnIndex);
	},

	_setColumn: function (id, params, index)
	{
		params.id = id;
		this._columnsArr[index] = params;
	},

    changeField: function (oldId, newId, params)
    {
        for (var i = 0; i < this._columnsCount; i++) {
            var column = this._columnsArr[i];
            if (column.id == oldId) {
                this._setColumn(newId, params, i);
            }
        }
    },

	fill: function (msgArr, screenId, info, addClearLink, bNextPageMsgList, bEqualMsgsList)
	{
		if (!bEqualMsgsList) {
			this._scrollLines(0);
		}
		this.oSelection.SaveCheckedLines();
		this._clean();
		if (null != this._dragNDrop) {
			this._dragNDrop.SetSelection(this.oSelection);
		}
		this.addMessageToList(info, addClearLink);
		var tbl = CreateChild(this._eLinesCont, 'table', [['dir', 'ltr']]);
		this._linesTbl = tbl;

		var tr = null;
		for (var msgIndex = 0; msgIndex < msgArr.length; msgIndex++) {
			tr = tbl.insertRow(msgIndex);
			var msg = msgArr[msgIndex];
			var line = this._controller.CreateLine(msg, tr, screenId);
			var td = tr.insertCell(0);
			td.className = 'wm_inbox_read_item_view_corner';
			CreateChild(td, 'div');

			var totalWidth = 0;
			var columnIndex = 0;
			for (; columnIndex < this._columnsCount; columnIndex++) {
				td = tr.insertCell(columnIndex + 1);
				var params = this._columnsArr[columnIndex];
				td.style.paddingTop = params.PaddingTopBottom + 'px';
				td.style.paddingRight = params.PaddingLeftRight + 'px';
				td.style.paddingBottom = params.PaddingTopBottom + 'px';
				td.style.paddingLeft = params.PaddingLeftRight + 'px';
				td.style.textAlign = params.Align;
				td.style.width = params.PermanentWidth + 'px';
				if (params.id == IH_FROM || params.id == IH_TO) {
					line.SetFromDisplay(td);
				}
				if (msgIndex == 0) {
					if (params.id == IH_FROM || params.id == IH_TO) {
						totalWidth += 2 * params.PaddingLeftRight;
					}
					else {
						totalWidth += params.PermanentWidth + 2 * params.PaddingLeftRight;
					}
				}
				line.SetContainer(params.DisplayField, td);
			}

			td = tr.insertCell(columnIndex + 1);
			td.className = 'wm_inbox_read_item_view_corner';
			CreateChild(td, 'div');

			if (msgIndex == 0) {
				this._totalWidth = totalWidth + 2;
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

	build: function (parent)
	{
        if (window.RTL) {
            this._columnsArr.reverse();
        }
		this._linesContainer = parent;
		this._eLinesCont = CreateChild(parent, 'div', [['class', 'wm_inbox_lines']]);
		this._eLinesCont.onkeydown = function (ev) {
			var key = Keys.getCodeFromEvent(ev);
			if (key == Keys.up || key == Keys.down || key == Keys.pageUp || key == Keys.pageDown || key == Keys.space) {
				return false;
			}
			return true;
		};
		var borders = GetBorders(this._eLinesCont);
		this.HorBordersWidth = borders.Left + borders.Right;
		this.VertBordersWidth = borders.Top + borders.Bottom;
	}
};

CMessageListDisplay.prototype.cleanLines = CVariableTable.prototype.cleanLines;
CMessageListDisplay.prototype.setNoMessagesFoundMessage = CVariableTable.prototype.setNoMessagesFoundMessage;
CMessageListDisplay.prototype.setSearchErrorMessage = CVariableTable.prototype.setSearchErrorMessage;
CMessageListDisplay.prototype.setRetrievingErrorMessage = CVariableTable.prototype.setRetrievingErrorMessage;
CMessageListDisplay.prototype._addRightTopLink = CVariableTable.prototype._addRightTopLink;
CMessageListDisplay.prototype.getLines = CVariableTable.prototype.getLines;
CMessageListDisplay.prototype._scrollLines = CVariableTable.prototype._scrollLines;
CMessageListDisplay.prototype._clickCheckLine = CVariableTable.prototype._clickCheckLine;
CMessageListDisplay.prototype.onKeyDown = CVariableTable.prototype.onKeyDown;
CMessageListDisplay.prototype.addMessageToList = CVariableTable.prototype.addMessageToList;
CMessageListDisplay.prototype.removeMessageFromList = CVariableTable.prototype.removeMessageFromList;

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
