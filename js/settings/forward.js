/*
 * Classes:
 *  CForwardPane(oParent)
 */

function CForwardPane(oParent)
{
	this._oParent = oParent;
	
	this._oForward = null;
	this._oNewForward = null;
	
	this.bChanges = false;
	this.bShown = false;

    this._$ForwardEditZone = null;
	this._eEnable = null;
	this._eEmail = null;

	this._idAcct = -1;
}

CForwardPane.prototype = {
	show: function()
	{
		this.bChanges = false;
		if (window.UseDb || window.UseLdapSettings) {
			this._$ForwardEditZone.removeClass('wm_hide');
		}
		else {
			this._$ForwardEditZone.addClass('wm_hide');
		}

		this.bShown = true;
		if (this._idAcct !== WebMail.Accounts.editableId) {
			this._idAcct = WebMail.Accounts.editableId;
			GetHandler(TYPE_FORWARD, { idAcct: this._idAcct }, [], '');
		}
		else this.fill();
	},
	
	clickBody: function (ev) {},
	
	hide: function()
	{
		this.bShown = false;
		if (this.bChanges) {
			Dialog.confirm(
				Lang.ConfirmSaveForward,
				(function (obj) {
					return function () {
						obj.saveChanges();
					};
				})(this)
			);
		}
		this.bChanges = false;
		this._$ForwardEditZone.addClass('wm_hide');
	},
	
	getNewForward: function ()
	{
		if (this._oForward === null) {
			this._oForward = new CForwardData();
		}
		this._oForward.copy(this._oNewForward);
		return this._oForward;
	},

	setForward: function (oForward)
	{
		this._oForward = oForward;
		this._idAcct = oForward.idAcct;
		this.fill();
	},

	fill: function ()
	{
		if ((null == this._oForward) || !this.bShown) return;
		this._eEnable.checked = this._oForward.enable;
		this._eEmail.value = this._oForward.email;
		if (this._eEnable.checked) {
			this._eEmail.disabled = false;
		}
		else {
			this._eEmail.disabled = true;
		}

		if (this._oParent) {
			this._oParent.resizeBody();
		}
	},
	
	_setInputKeyPress: function (inp)
	{
		$(inp).bind('keypress', (function (obj) {
			return function (ev) {
				if (isEnter(ev)) obj.saveChanges();
			};
		})(this));
	},
	
	_isCorrectDataForSaving: function (oForward)
	{
		if (!oForward.enable) {
			return true;
		}
		if (Validator.isEmpty(oForward.email)) {
			Dialog.alert(Lang.WarningEmailFieldBlank);
			return false;
		}
		if (!Validator.isCorrectEmail(oForward.email)) {
			Dialog.alert(Lang.WarningCorrectEmail);
			return false;
		}
		return true;
	},

	saveChanges: function ()
	{
		var oForward = new CForwardData();
		oForward.enable = this._eEnable.checked;
		oForward.email = this._eEmail.value;
		oForward.idAcct = this._idAcct;

		if (!this._isCorrectDataForSaving(oForward)) {
			return;
		}
		
		this._oNewForward = oForward;
		var xml = oForward.getInXml();
		RequestHandler('update', 'forward', xml);
		this.bChanges = false;
	},

	build: function(container)
	{
		var eForwardEditZone = CreateChild(container, 'div', [['class', 'wm_email_settings_edit_zone wm_hide']]);
		this._$ForwardEditZone = $(eForwardEditZone);

		var tbl = CreateChild(eForwardEditZone, 'table');
		tbl.className = 'wm_settings_signature';
		var rowIndex = 0;
		var tr = tbl.insertRow(rowIndex++);
		var td = tr.insertCell(0);
		td = tr.insertCell(1);
		var inp = CreateChild(td, 'input', [['class', 'wm_checkbox'], ['type', 'checkbox'], ['id', 'enable_fwd']]);
		var lbl = CreateChild(td, 'label', [['for', 'enable_fwd']]);
		WebMail.langChanger.register$({ $elem: $(lbl), sField: 'ForwardEnable', sType: 'html'});
		$(inp).bind('click', (function (obj) {
			return function () {
				obj.bChanges = true;
				obj._eEmail.disabled = !this.checked;
			};
		})(this));
		this._eEnable = inp;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		WebMail.langChanger.register$({ $elem: $(td), sField: 'Email', sType: 'html'});
		td = tr.insertCell(1);
		inp = CreateChild(td, 'input', [['class', 'wm_input'], ['type', 'text']]);
		inp.disabled = true;
		this._setInputKeyPress(inp);
		$(inp).bind('change', (function (obj) {
			return function () {
				obj.bChanges = true;
			};
		})(this));
		this._eEmail = inp;

		tbl = CreateChild(eForwardEditZone, 'table');
		tbl.className = 'wm_settings_buttons';
		tr = tbl.insertRow(0);
		td = tr.insertCell(0);
		inp = CreateChild(td, 'input', [['class', 'wm_button'], ['type', 'button']]);
		WebMail.langChanger.register$({ $elem: $(inp), sField: 'Save', sType: 'value'});
		$(inp).bind('click', (function (obj) {
			return function () {
				obj.saveChanges();
			}
		})(this));
	}//build
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}