/*
 * Classes:
 *  CAutoresponderPane(oParent)
 */

function CAutoresponderPane(oParent)
{
	this._oParent = oParent;
	
	this._autoresponder = null;
	this._newAutoresponder = null;
	
	this.hasChanges = false;
	this.shown = false;

    this._responderEditZone = null;
	this._enableObj = null;
	this._subjectObj = null;
	this._subjectCont = null;
	this._messageObj = null;

	this._idAcct = -1;
}

CAutoresponderPane.prototype = {
	show: function()
	{
		this.hasChanges = false;
		this._responderEditZone.className = (window.UseDb || window.UseLdapSettings)
			? 'wm_email_settings_edit_zone' : 'wm_hide';

		this.shown = true;
		if (this._idAcct !== WebMail.Accounts.editableId) {
			this._idAcct = WebMail.Accounts.editableId;
			GetHandler(TYPE_AUTORESPONDER, { idAcct: this._idAcct }, [], '');
		}
		else this.fill();
	},
	
	clickBody: function (ev) {},
	
	hide: function()
	{
		this.shown = false;
		if (this.hasChanges) {
			Dialog.confirm(
				Lang.ConfirmSaveAutoresponder,
				(function (obj) {
					return function () {
						obj.saveChanges();
					};
				})(this)
			);
		}
		this.hasChanges = false;
		this._responderEditZone.className = 'wm_hide';
	},
	
	GetNewAutoresponder: function ()
	{
		this._autoresponder.copy(this._newAutoresponder);
		return this._autoresponder;
	},

	SetAutoresponder: function (autoresponder)
	{
		this._autoresponder = autoresponder;
		this._idAcct = autoresponder.idAcct;
		this.fill();
	},

	fill: function ()
	{
		if ((null == this._autoresponder) || !this.shown) return;
		this._enableObj.checked = this._autoresponder.enable;
		this._subjectObj.value = this._autoresponder.subject;
		this._messageObj.value = this._autoresponder.message;
		if (this._enableObj.checked) {
			this._subjectObj.disabled = false;
			this._messageObj.disabled = false;
		}
		else {
			this._subjectObj.disabled = true;
			this._messageObj.disabled = true;
		}

		if (this._oParent) {
			this._oParent.resizeBody();
		}
	},
	
	_setInputKeyPress: function (inp)
	{
	    var obj = this;
		inp.onkeypress = function (ev) { if (isEnter(ev)) obj.saveChanges(); };
	},
	
	saveChanges: function ()
	{
		var autoresponder = new CAutoresponderData();
		autoresponder.enable = this._enableObj.checked;
		autoresponder.subject = this._subjectObj.value;
		autoresponder.message = this._messageObj.value;
		autoresponder.idAcct = this._idAcct;
		this._newAutoresponder = autoresponder;
		var xml = autoresponder.getInXml();
		RequestHandler('update', 'autoresponder', xml);
		this.hasChanges = false;
	},

	setSubjectView: function(isHide)
	{
		this._subjectCont.className = (isHide) ? 'wm_hide' : '';
	},
	
	build: function(container)
	{
		var obj = this;
		this._responderEditZone = CreateChild(container, 'div', [['class', 'wm_hide']]);

		var tbl = CreateChild(this._responderEditZone, 'table');
		tbl.className = 'wm_settings_signature';
		var rowIndex = 0;
		var tr = tbl.insertRow(rowIndex++);
		var td = tr.insertCell(0);
		td = tr.insertCell(1);
		var inp = CreateChild(td, 'input', [['class', 'wm_checkbox'], ['type', 'checkbox'], ['id', 'enable_ar']]);
		var lbl = CreateChild(td, 'label', [['for', 'enable_ar']]);
		lbl.innerHTML = Lang.AutoresponderEnable;
		WebMail.langChanger.register('innerHTML', lbl, 'AutoresponderEnable', '');
		inp.onclick = function () {
			obj.hasChanges = true;
			if (this.checked) {
				obj._subjectObj.disabled = false;
				obj._messageObj.disabled = false;
			}
			else {
				obj._subjectObj.disabled = true;
				obj._messageObj.disabled = true;
			}
		};
		this._enableObj = inp;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = Lang.AutoresponderSubject;
		WebMail.langChanger.register('innerHTML', td, 'AutoresponderSubject', '');
		td = tr.insertCell(1);
		inp = CreateChild(td, 'input', [['class', 'wm_input'], ['type', 'text']]);
		this._setInputKeyPress(inp);
		inp.onchange = function () { obj.hasChanges = true; };
		this._subjectObj = inp;
		this._subjectCont = tr;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.innerHTML = Lang.AutoresponderMessage;
		td.style.verticalAlign = 'top';
		td.className = 'wm_settings_title';
		WebMail.langChanger.register('innerHTML', td, 'AutoresponderMessage', '');
		td = tr.insertCell(1);
		var txt = CreateChild(td, 'textarea', [['class', 'wm_input']]);
		txt.onchange = function () { obj.hasChanges = true; };
		this._messageObj = txt;

		tbl = CreateChild(this._responderEditZone, 'table');
		tbl.className = 'wm_settings_buttons';
		tr = tbl.insertRow(0);
		td = tr.insertCell(0);
		inp = CreateChild(td, 'input', [['class', 'wm_button'], ['type', 'button'], ['value', Lang.Save]]);
		WebMail.langChanger.register('value', inp, 'Save', '');
		inp.onclick = function () { obj.saveChanges(); };
	}//build
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}