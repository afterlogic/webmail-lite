var PageSwitcher, List;
$(function(){
	$('#tableAllCheck').click(function(){
		Selection.CheckAllBox(this);
	});
});

function CListSelection() {
	this.lines = [];
	this.length = 0;
	this.prev = -1;
	this.AllCheckBox = document.getElementById('tableAllCheck');
}

CListSelection.prototype = {
	AddLine: function (line) {
		this.lines.push(line);
		this.length = this.lines.length;
	},

	GetCheckedLines: function () {
		var idArray = Array();
		for (var i = this.length-1; i >= 0; i--) {
			var line = this.lines[i];
			if (line.Checked === true) {
				idArray.push(line.Id);
			}
		}
		return idArray;
	},

	CheckCtrlLine: function(id) {
		for (var i = this.length-1; i >= 0; i--) {
			var line = this.lines[i];
			if (line.Id == id){
				if (line.Checked === false) {
					line.Check();
					this.prev = i;
				} else {
					line.Uncheck();
				}
			}
		}
		this.ReCheckAllBox();
	},

	CheckLine: function(id) {
		for (var i = this.length-1; i >= 0; i--) {
			var line = this.lines[i];
			if (line.Id == id){
				line.Check();
				this.prev = i;
			} else {
				line.Uncheck();
			}
		}
		this.ReCheckAllBox();
	},

	CheckShiftLine: function(id) {
		if (this.prev == -1) {
			this.CheckLine(id);
		} else {
			var isChecking = false;
			var prev_ = this.prev;
			for (var i = 0; i < this.length; i++) {
				var line = this.lines[i];
				if (this.prev == i || line.Id == id)
				{
					isChecking = isChecking ? false : true;
				}
				if (line.Id == id)
				{
					prev_ = i;
				}
				if (isChecking || this.prev == i || line.Id == id) {
					line.Check();
				} else {
					line.Uncheck();
				}
			}
			/* this.prev = prev; */
		}
		this.ReCheckAllBox();
	},

	UncheckAll: function () {
		for (var i = this.length-1; i >= 0; i--) {
			this.lines[i].Uncheck();
		}
		this.prev = -1;
	},

	ReCheckAllBox: function() {
		var isAllCheck = true;
		for (var i = this.length-1; i >= 0; i--) {
			if (this.lines[i].Checked === false) {isAllCheck = false;}
		}
		if (this.AllCheckBox){
			this.AllCheckBox.checked = isAllCheck;
		}
	},

	CheckCBox: function(id) {
		for (var i = this.length-1; i >= 0; i--) {
			var line = this.lines[i];
			if (line.Id == id){
				if (line.Checked === false) {
					line.Check();
					this.prev = i;
				} else {
					line.Uncheck();
				}
			}
		}
		this.ReCheckAllBox();
	},

	CheckAllBox: function(objCheckbox) {
		for (var i = this.length-1; i >= 0; i--) {
			var line = this.lines[i];
			if (objCheckbox.checked) {
				line.Check(true);
			} else {
				line.Uncheck();
			}
		}
	},

	CheckCount: function() {
		var cnt = 0;
		for (var i = this.length-1; i >= 0; i--) {
			var line = this.lines[i];
			if (line.Checked) {
				cnt++;
			}
		}
		return cnt;
	}
};

function CListSelectionPart(tr) {
	/* don't select content in Opera/IE */
	tr.onmousedown = function() {return false;};
	tr.onselectstart = function() {return false;};
	tr.onselect = function() {return false;};
	this._tr = tr;
	this._className = tr.className;
	this.Id = tr.id;
	this.Checked = false;

	var collection = this._tr.getElementsByTagName('td');
	if (collection.length > 1) {
		this._checkTd = collection[0];
		var checkboxcoll = this._checkTd.getElementsByTagName('input');
		if (checkboxcoll.length > 0) {
				this._checkbox = checkboxcoll[0];
		}
	}
	this.ApplyClassName();
}

CListSelectionPart.prototype = {
	Check: function(bIsAll) {
		if (('undefined' !== typeof(bIsAll) && bIsAll) && 'uid0' === this.Id)
		{
			this.Uncheck();
			return;
		}
		this.Checked = true;
		this.ApplyClassName();
		this.AppleCheckBox();
	},

	Uncheck: function() {
		this.Checked = false;
		this.ApplyClassName();
		this.AppleCheckBox();
	},

	ApplyClassName: function () {
		if (this.Checked) {
			this._tr.className = this._className + '_select';
		} else {
			this._tr.className = this._className;
		}
	},

	AppleCheckBox: function () {
		if (this._checkbox)
		{
			this._checkbox.checked = (this.Checked);
		}
	}
};

function MainAjaxRequest(event)
{
	$.post($(this).attr('action'), $(this).serialize(), function(data) {
		if (data && data.message) {
			OnlineMsgInfo(data.message);
		} else if (data && data.error) {
			OnlineMsgError(data.error);
		} else {
			OnlineMsgError('Internal error.');
		}
		if (data && data.ref && 0 < data.ref.length) {
			document.location = data.ref;
		}
	}, 'json');
	event.preventDefault();
}

function SwitcherTabHandler()
{
	$('#switchers_content_div > div').hide();
	$('#switchers_tab_div > div.wm_settings_switcher_select_item')
		.removeClass('wm_settings_switcher_select_item')
		.addClass('wm_settings_switcher_item');

	$(this).removeClass('wm_settings_switcher_item').addClass('wm_settings_switcher_select_item');
	$('#' + $(this).attr('rel')).show();
}

function PageSwitcherPager(page) {
	document.location = "index.php?page=" + page;
}

function ViewAddressRecord(id) {
	if ('uid' === id.toString().substr(0, 3))
	{
		document.location = AP_INDEX + '?edit&tab=' + AP_TAB + '&uid=' + id.toString().substr(3);
	}
}

function CheckSelection() {
	if (Selection && Selection.CheckCount() === 0)
	{
		return false;
	}
	return true;
}

/* pageswitcher classic */
function CPageSwitcher() {
	this._mainCont = null;
	this._pagesCont = null;
	this._count = 0;
	this._perPage = 0;
}

CPageSwitcher.prototype = {
	Show: function (page, perPage, count, beginOnclick, endOnclick) {
		var
			firstPage, lastPage
		;

		this._count = count;
		this._perPage = perPage;
		if (count > perPage) {
			var strPages = '';
			var pagesCount = Math.ceil(count/perPage);
			if (pagesCount > 4) {
				firstPage = page - 2;
				if (firstPage < 1)
				{
					firstPage = 1;
				}
				lastPage = firstPage + 4;
				if (lastPage > pagesCount) {
					lastPage = pagesCount;
					firstPage = lastPage - 4;
				}
			} else {
				firstPage = 1;
				lastPage = pagesCount;
			}
			if (firstPage != lastPage) {
				if (firstPage > 1) {
					strPages += '<a href="javascript:void(0);" onclick="' + beginOnclick + '1' + endOnclick + ' return false;"><img title="First Page" style="width: 8px; height: 9px;" src="static/images/page_switchers/inbox_first_page.gif" /></a>';
					strPages += '<a href="javascript:void(0);" onclick="' + beginOnclick + firstPage + endOnclick + ' return false;"><img title="Previous Page" style="width: 5px; height: 9px;" src="static/images/page_switchers/inbox_prev_page.gif" /></a>';
				}
				for (var i = firstPage; i <= lastPage; i++) {
					if (page == i) {
						strPages += '<font>' + i + '</font>';
					} else {
						strPages += '<a href="javascript:void(0);" onclick="' + beginOnclick + i + endOnclick + ' return false;">' + i + '</a>';
					}
				}
				if (pagesCount > lastPage) {
					strPages += '<a href="javascript:void(0);" onclick="' + beginOnclick + lastPage + endOnclick + ' return false;"><img title="Next Page" style="width: 5px; height: 9px;" src="static/images/page_switchers/inbox_next_page.gif" /></a>';
					strPages += '<a href="javascript:void(0);" onclick="' + beginOnclick + pagesCount + endOnclick + ' return false;"><img title="Last Page" style="width: 8px; height: 9px;" src="static/images/page_switchers/inbox_last_page.gif" /></a>';
				}
				this._mainCont.className = 'wm_inbox_page_switcher';
				this._pagesCont.innerHTML = strPages;
			}
		}
	},

	GetLastPage: function (removeCount) {
		var count = this._count - removeCount;
		var perPage = this._perPage;
		var page = Math.ceil(count/perPage);
		if (page < 1)
		{
			page = 1;
		}
		return page;
	},

	Hide: function () {
		this._mainCont.className = 'wm_hide';
	},

	Replace: function (obj) {
		/*
		var oBounds = GetBounds(obj);
		var ps = this._mainCont;
		ps.style.top = (oBounds.Top + 4) + 'px';
		ps.style.left = (oBounds.Left + oBounds.Width - ps.offsetWidth - 11) + 'px';
		*/
	},

	ChangeSkin: function (skinName) {
		this._skinName = skinName;
	},

	Build: function () {
		this._mainCont = document.getElementById('ps_container');
		this._pagesCont = document.getElementById('ps_pages');
	}
};

function InitList(ListId) {
	Selection = new CListSelection();
	var list = document.getElementById(ListId);
	if (!list)
	{
		return false;
	}
	var tr_arr = list.getElementsByTagName("tr");

	var clickLineFunction = function (e) {
		e = e ? e : window.event;
		if(e.ctrlKey) {
			Selection.CheckCtrlLine(this.id);
		} else if (e.shiftKey) {
			Selection.CheckShiftLine(this.id);
		} else {
			var elem = (Browser.Mozilla) ? e.target : e.srcElement;
			if (!elem || $(elem).hasClass("wm_inbox_none")) {
				return false;
			}
			var loverTag = elem.tagName.toLowerCase();
			if (loverTag == "input") {
				Selection.CheckCBox(this.id);
			} else {
				Selection.CheckLine(this.id);
				ViewAddressRecord(this.id);
			}
		}
	};

	var dbclickLineFunction = function (e) {
		Selection.CheckCtrlLine(this.id);
		e = e ? e : window.event;
		var elem = (Browser.Mozilla) ? e.target : e.srcElement;
		if (!elem || $(elem).hasClass("wm_inbox_none") || elem.tagName.toLowerCase() == "input") {
			return false;
		}
	};

	for (var i = 0; i < tr_arr.length; i++) {
		this.line = null;

		if (tr_arr[i].id != "contact_list_headers" && tr_arr[i].id.length  > 0) {
			this.line = document.getElementById(tr_arr[i].id);
		}

		if (this.line) {
			Selection.AddLine(new CListSelectionPart(tr_arr[i]));

			this.line.onclick = clickLineFunction;
			this.line.ondblclick = dbclickLineFunction;
		}
	}
}

/* contacts resizer */
function ResizeElements() {
	ResizeMainError();
	if (window.List) {
		List.ResizeBody();
	}

	window.setTimeout(WindowForceResize, 50);
}

function CList() {
	this._accountsBar = document.getElementById('accountslist');
	this._toolBar = document.getElementById('toolbar');
	this._lowToolBar = document.getElementById('lowtoolbar');

	/* logo + accountslist + toolbar + lowtoolbar */
	this._externalHeight = 32 + 27 + 20;
	this._contactsHeadersWidth = 175;

	this._mainDiv = document.getElementById('main_contacts');
	this._leftDiv = document.getElementById('contacts');
	this._rightDiv = document.getElementById('contacts_viewer');

	this._contactListTbl = document.getElementById('list');
	this._contactListDiv = document.getElementById('contact_list_div');
	this._contactListHeaders = document.getElementById('contact_list_headers');
	this._contactListTop = document.getElementById('list_top_search');
	this._emailObj = document.getElementById('emailobj');
	this._pageSwitcher = PageSwitcher;

	this._cardTable = document.getElementById('wm_contacts_card');

	this.minListHeight = 300;
}

CList.prototype = {
	ResizeBody: function(mode) {
		if (!Browser.IE || (Browser.IE && Browser.Version >= 7)) {
			var listBorderHeight = 1;
			var height = GetHeight() - this.GetExternalHeight();
			if (height < this.minListHeight) {
				height = this.minListHeight;
			}
			var tableHeight = (this._contactListTbl ? this._contactListTbl.offsetHeight : 0) +
				(this._contactListTop ? this._contactListTop.offsetHeight : 0);

			var cardHeight = 0;
			if (this._cardTable) {
				cardHeight = this._cardTable.offsetHeight;
			}

			var biggerH  = (tableHeight > cardHeight) ? tableHeight : cardHeight;
			if (height < biggerH)
			{
				height = biggerH;
			}

			this._mainDiv.style.height = height + 'px';
			this._contactListDiv.style.height = height - listBorderHeight + 'px';

			this.ResizeListTable(this._leftDiv.offsetWidth);
			// if (this._cardTable)
			// {
				// this._cardTable.style.width = 'auto';
				// var cardWidth = this._cardTable.offsetWidth;
				// var rightWidth = this._rightDiv.offsetWidth;
				// if (cardWidth < rightWidth) {
					// cardWidth = rightWidth;
				// }
				// if (cardWidth < 550) {
					// cardWidth = 550;
				// }
				// this._cardTable.style.width = cardWidth - 2 + 'px';
			// }
		} else {
			this._mainDiv.style.width = ((document.documentElement.clientWidth || document.body.clientWidth) < 850) && (this._cardTable) ? '850px' : '100%';
			var listWidth = this._leftDiv.offsetWidth;
			this.ResizeListTable(listWidth);

			if (this._cardTable) {
				var width = GetWidth();
				if (width < 550) {
					width = 550;
				}
				this._cardTable.style.width = width - listWidth - 4 + 'px';
			}
		}
		this._pageSwitcher.Replace(this._contactListHeaders);
	},

	ResizeListTable: function (listWidth) {
		var emailWidth = listWidth - this._contactsHeadersWidth;
		if (this._emailObj && emailWidth > 0) {
			this._emailObj.style.width = emailWidth + 'px';
		}
	},

	GetExternalHeight: function(){
		var res = 0;
		var offsetHeight = this._accountsBar.offsetHeight;if (offsetHeight || offsetHeight === 0) {res += offsetHeight;} else {return this._externalHeight;}
		offsetHeight = this._toolBar.offsetHeight;if (offsetHeight || offsetHeight === 0) {res += offsetHeight;} else {return this._externalHeight;}
		offsetHeight = this._lowToolBar.offsetHeight;if (offsetHeight || offsetHeight === 0) {res += offsetHeight;} else {return this._externalHeight;}
		this._externalHeight = res;
		return this._externalHeight;
	}
};
