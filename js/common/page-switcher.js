/*
 * Classes:
 *  CPageSwitcher(locationObj, withoutBorders)
 */

function CPageSwitcher(locationObj, withoutBorders)
{
	this._mainCont = null;
	this._pagesCont = null;
	this._count = 0;
	this._perPage = 0;
	this.pagesCount = 0;
	this._withoutBorders = (withoutBorders) ? true : false;
	this._mainClassName = 'wm_inbox_page_switcher';
	this._locationObj = locationObj;
	this._build();
}

CPageSwitcher.prototype = {
	show: function (page, perPage, count, beginOnclick, endOnclick)
	{
		if (page === 0) {
			this._mainCont.className = this._mainClassName;
		}
		else {
    		this.pagesCount = 0;
			this.hide();
			this._count = count;
			this._perPage = perPage;
			if (count > perPage) {
				var strPages = '';
				var pagesCount = Math.ceil(count / perPage);
				this.pagesCount = pagesCount;
				var firstPage, lastPage;
				if (pagesCount > 4) {
					firstPage = page - 2;
					if (firstPage < 1) {
						firstPage = 1;
					}
					lastPage = firstPage + 4;
					if (lastPage > pagesCount) {
						lastPage = pagesCount;
						firstPage = lastPage - 4;
					}
				}
				else {
					firstPage = 1;
					lastPage = pagesCount;
				}
				if (firstPage != lastPage) {
					if (firstPage > 1) {
						var strFirstPage = '<a href="#" onclick="' + beginOnclick + '1' + endOnclick + ' return false;" class="wm_page_switcher_first" title="' + Lang.FirstPage + '"></a>';
						var strPrevPage = '<a href="#" onclick="' + beginOnclick + (firstPage - 1) + endOnclick + ' return false;" class="wm_page_switcher_prev" title="' + Lang.PreviousPage + '"></a>';
						
						if (window.RTL) {
							strPages = strPrevPage + strFirstPage + strPages;
						} else {
							strPages += strFirstPage + strPrevPage;
						}
					}
					for (var i = firstPage; i <= lastPage; i++) {
						if (page == i) {
							if (window.RTL) {
								strPages = '<font>' + i + '</font>' + strPages;
							} else {
								strPages += '<font>' + i + '</font>';
							}
						}
						else {
							if (window.RTL) {
								strPages = '<a href="#" onclick="' + beginOnclick + i + endOnclick + ' return false;">' + i + '</a>' + strPages;
							} else {
								strPages += '<a href="#" onclick="' + beginOnclick + i + endOnclick + ' return false;">' + i + '</a>';
							}
						}
					}
					if (pagesCount > lastPage) {
						var strNextPage = '<a href="#" onclick="' + beginOnclick + (lastPage + 1) + endOnclick + ' return false;" class="wm_page_switcher_next" title="' + Lang.NextPage + '"></a>';
						var strLastPage = '<a href="#" onclick="' + beginOnclick + pagesCount + endOnclick + ' return false;" class="wm_page_switcher_last" title="' + Lang.LastPage + '"></a>';
						if (window.RTL) {
							strPages = strLastPage + strNextPage + strPages;
						} else {
							strPages += strNextPage + strLastPage;
						}
					}
					this._mainCont.className = this._mainClassName;
					this._pagesCont.innerHTML = strPages;
				}
			} // if (count > perPage)
		}
	},
	
	GetLastPage: function (removeCount, perPage)
	{
		var count = this._count - removeCount;
		if (perPage) {
			this._perPage = perPage;
		}
		var page = Math.ceil(count / this._perPage);
		if (page < 1) {
			page = 1;
		}
		return page;
	},
	
	hide: function ()
	{
		this._mainCont.className = 'wm_hide';
	},

	Replace: function ()
	{
		if (this._locationObj == null) return;
		var oBounds = GetBounds(this._locationObj);
		var ps = this._mainCont;
		var indent = 13;
		if (this._withoutBorders) {
			ps.style.top = (oBounds.Top + indent) + 'px';
		}
		else {
			ps.style.top = (oBounds.Top - ps.offsetHeight) + 'px';
			indent = 18;
		}
		/* rtl */
		if (window.RTL) {
			ps.style.left = (oBounds.Left + indent) + 'px';
		}
		else {
			ps.style.left = (oBounds.Left + oBounds.Width - ps.offsetWidth - indent) + 'px';
		}
	},
	
	_build: function ()
	{
		if (this._withoutBorders) {
			var tbl = CreateChild(document.body, 'table');
			this._mainCont = tbl;
			tbl.className = 'wm_hide';
			var tr = tbl.insertRow(0);
			var td = tr.insertCell(0);
			this._pagesCont = td;
			td.className = 'wm_inbox_page_switcher_pages';
		}
		else {
			var tbl = CreateChild(document.body, 'table');
			this._mainCont = tbl;
			tbl.className = 'wm_hide';
			var tr = tbl.insertRow(0);
			var td = tr.insertCell(0);
			var div = CreateChild(td, 'div');
			div.className = 'wm_inbox_page_switcher_left';
			td = tr.insertCell(1);
			this._pagesCont = td;
			td.className = 'wm_inbox_page_switcher_pages wm_inbox_page_switcher_borders';
			td = tr.insertCell(2);
			div = CreateChild(td, 'div');
			div.className = 'wm_inbox_page_switcher_right';
		}
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
