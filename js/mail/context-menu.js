/*
 * Object:
 *  ContextMenu
 */

ContextMenu = {
	_bInit: false,

	_oReplyItem: null,
	_oReplyallItem: null,
	_oForwardItem: null,
	_oMarkasunreadItem: null,
	_oMarkasreadItem: null,
	_oPrintItem: null,
	_oDigdosItem: null,
	_$Menu: null,
	_$MenuContent: null,

	openManyItemsMenu: function (iX, iY, bDigdos)
	{
		this._init();
		this._hideItem(this._oReplyItem);
		this._hideItem(this._oReplyallItem);
		this._hideItem(this._oForwardItem);
		this._hideItem(this._oReplySeparator);
		this._showItem(this._oMarkasreadItem);
		this._showItem(this._oMarkasunreadItem);
		this._hideItem(this._oPrintItem);
		if (this._oDigdosItem !== null) {
//			if (bDigdos) {
//				this._hideItem(this._oDigdosItem);
//			}
//			else {
				this._showItem(this._oDigdosItem);
//			}
		}
		this.placeAndShow(iX, iY);
	},

	openOneItemMenu: function (iX, iY, bDigdos, bUnreaded)
	{
		this._init();
		this._showItem(this._oReplyItem);
		this._showItem(this._oReplyallItem);
		this._showItem(this._oForwardItem);
		this._showItem(this._oReplySeparator);
		if (bUnreaded) {
			this._hideItem(this._oMarkasunreadItem);
			this._showItem(this._oMarkasreadItem);
		}
		else {
			this._showItem(this._oMarkasunreadItem);
			this._hideItem(this._oMarkasreadItem);
		}
		this._showItem(this._oPrintItem);
		if (this._oDigdosItem !== null) {
//			if (bDigdos) {
//				this._hideItem(this._oDigdosItem);
//			}
//			else {
				this._showItem(this._oDigdosItem);
//			}
		}
		this.placeAndShow(iX, iY);
	},

	placeAndShow: function (iMenuLeft, iMenuTop)
	{
		var
			iScreenHeight = GetHeight(),
			iMenuHeight = this._$Menu.height(),
			iMenuBottom = iMenuTop + iMenuHeight
		;

		if (iMenuBottom > iScreenHeight) {
			iMenuTop = iMenuTop - iMenuHeight;
		}
		
		this._$Menu.css({ position: 'absolute', top: iMenuTop, left: iMenuLeft }).show();
	},

	close: function ()
	{
		this._init();
		this._$Menu.hide();
	},

	enablePrint: function (fPrint)
	{
		this._init();
		this._oPrintItem.$item.removeClass('wm_context_menu_item_disable');
		this._oPrintItem.$item.bind('click', fPrint);
		this._oPrintItem.$item.bind('click', (function (obj) {
			return function () {
				obj.close();
			};
		})(this));
	},

	disablePrint: function ()
	{
		this._init();
		this._oPrintItem.$item.addClass('wm_context_menu_item_disable');
		this._oPrintItem.$item.bind('click', function () { });
	},

	_showItem: function (oItem)
	{
		oItem.$item.show();
		oItem.$icon.show();
	},

	_hideItem: function (oItem)
	{
		oItem.$item.hide();
		oItem.$icon.hide();
	},

	_addItem: function (sField, fClick, sIconClass)
	{
		var $icon = $('<div></div>').addClass('wm_context_menu_icon').addClass(sIconClass).html('<span>&nbsp</span>').appendTo(this._$LeftPane);
		var $item = $('<div></div>').addClass('wm_context_menu_item').appendTo(this._$RightPane);
		var $itemContent = $('<div></div>').addClass('item_content').appendTo($item);
		WebMail.langChanger.register$({ sType: 'html', $elem: $itemContent, sField: sField });
		$item.bind({
			'click': fClick,
			'mouseover': function () {
				$(this).addClass('selected');
			},
			'mouseout': function () {
				$(this).removeClass('selected');
			}
		});
		return { $item: $item, $icon: $icon};
	},

	_addSeparator: function ()
	{
		var $icon = $('<div>').addClass('wm_context_menu_separator_left').appendTo(this._$LeftPane);
		var $item = $('<div>').addClass('wm_context_menu_separator').appendTo(this._$RightPane);
		return { $item: $item, $icon: $icon};
	},

	_init: function ()
	{
		if (this._bInit) {
			return;
		}
		this._$Menu = $('<table></table>')
			.addClass('wm_context_menu')
			.appendTo('body')
			.hide();
		var tr = $('<tr></tr>').appendTo(this._$Menu);
		this._$MenuContent = $('<td></td>').appendTo(tr),
		
		$('<div></div>').addClass('box_corner1').append($('<div></div>')).appendTo(this._$MenuContent);

		var div = $('<div>').addClass('wm_context_menu_left').appendTo(this._$MenuContent);
		this._$LeftPane = $('<div>').addClass('content').appendTo(div);
		
		div = $('<div>').addClass('wm_context_menu_right').appendTo(this._$MenuContent);
		this._$RightPane = $('<div>').addClass('content').appendTo(div);
		
		$('<div></div>').addClass('box_corner2').append($('<div></div>')).appendTo(this._$MenuContent);

		this._addItem('Open', OpenAllSelectedMessagesHandler, '');
		this._addSeparator();
		
		this._oReplyItem = this._addItem('Reply', CreateReplyClick(TOOLBAR_REPLY), 'wm_context_menu_icon_reply');
		this._oReplyallItem = this._addItem('ReplyAll', CreateReplyClick(TOOLBAR_REPLYALL), 'wm_context_menu_icon_replyall');
		this._oForwardItem = this._addItem('Forward', CreateReplyClick(TOOLBAR_FORWARD), 'wm_context_menu_icon_forward');
		this._oReplySeparator = this._addSeparator();

		this._oMarkasunreadItem = this._addItem('MarkAsUnread', CreateToolBarItemClick(TOOLBAR_MARK_UNREAD), 'wm_context_menu_icon_markasunread');
		this._oMarkasreadItem = this._addItem('MarkAsRead', CreateToolBarItemClick(TOOLBAR_MARK_READ), '');
		this._addSeparator();

		this._oPrintItem = this._addItem('Print', function () {}, 'wm_context_menu_icon_print');
		if (WebMail.Settings.bAllowDigDos) {
			this._oDigdosItem = this._addItem('DigDosMenuItem', OpenDigDosDialog, '');
		}
		this._addItem('Delete', CreateToolBarItemClick(TOOLBAR_DELETE), 'wm_context_menu_icon_delete');

		$('body').bind('click', (function (obj) {
			return function () {
				obj.close();
			};
		})(this));

		this._bInit = true;
	}
}