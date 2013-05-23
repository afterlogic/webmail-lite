/*
 * Classes:
 *  CSignaturePane(oParent)
 */

function CSignaturePane (oParent)
{
	this._bBuilded = false;
	this._bShown = false;

	this._eModeSwitcher = null;
	this._eNoSignatureControl = null;
	this._ePlainEditorControl = null;
	this._eUseSignatureControl = null;
	this._eEditZone = null;

	this._oParent = oParent;
}

CSignaturePane.prototype = {
	show: function()
	{
		this._bShown = true;
		if (WebMail.Settings.bAllowIdentities) {
			return;
		}
		if (!this._bBuilded) {
			this._build();
		}
		if (!this._bBuilded) {
			return;
		}
		HtmlEditorField.setPlainEditor(this._ePlainEditorControl, this._eModeSwitcher, -1, false);
		this.fill();
	},

	clickBody: function ()
	{
		if (this._bShown) {
			HtmlEditorField.clickBody();
		}
	},

	replaceHtmlEditorField: function ()
	{
		HtmlEditorField.replace();
	},

	hide: function()
	{
		this._bShown = false;
		this._eEditZone.className = 'wm_hide';
//		HtmlEditorField.makeActive();
		HtmlEditorField.hide();
	},

	fill: function ()
	{
		var oIdentity = WebMail.oIdentities.getCurrentIdentity();
		if (oIdentity === null) {
			this._eEditZone.className = 'wm_hide';
			HtmlEditorField.hide();
			return;
		}
		this._eEditZone.className = 'wm_identity_edit_zone';
		if (oIdentity.bHtmlSignature) {
			HtmlEditorField.setHtml(oIdentity.getHtmlSignature());
		}
		else {
			HtmlEditorField.setPlain(oIdentity.getPlainSignature());
		}
		this.setUseSignature(oIdentity.bUseSignature);
		HtmlEditorField.resize(679, 200);
		if (this._oParent) {
			this._oParent.resizeBody();
		}
	},

	saveChanges: function ()
	{
		var oIdentity = WebMail.oIdentities.getCurrentIdentity();
		if (oIdentity === null) {
			return;
		}
		oIdentity.iAcctId = WebMail.Accounts.editableId;
		oIdentity.bUseSignature = this._eUseSignatureControl.checked;
		oIdentity.bHtmlSignature = HtmlEditorField.htmlMode;
		if (oIdentity.bHtmlSignature) {
			var sHtmlSignature = HtmlEditorField.getHtml(true);
			if (TextFormatter.removeAllTags(sHtmlSignature) === Lang.SignatureEnteringHere) {
				sHtmlSignature = '';
			}
			oIdentity.sSignature = sHtmlSignature;
		}
		else {
			var sPlainSignature = HtmlEditorField.getPlain();
			if (sPlainSignature === Lang.SignatureEnteringHere) {
				sPlainSignature = '';
			}
			oIdentity.sSignature = sPlainSignature;
		}
		var sXml = oIdentity.getInXml();
		RequestHandler('update', 'identity', sXml);
	},

	setUseSignature: function (bUseSignature)
	{
		this._eNoSignatureControl.checked = !bUseSignature;
		this._eUseSignatureControl.checked = bUseSignature;
		if (bUseSignature) {
			HtmlEditorField.makeActive();
		}
		else {
			HtmlEditorField.makeInactive(this);
		}
	},

	build: function (eParent)
	{
		this._eEditZone = CreateChild(eParent, 'div', [['class', 'wm_hide']]);
	},

	_build: function()
	{
		if (this._eEditZone === null) {
			return;
		}

		var eIdentityTbl = CreateChild(this._eEditZone, 'table', [['class', 'wm_identity_controls']]);

		var iRowIndex = 0;
		var eRow = eIdentityTbl.insertRow(iRowIndex++);
		var eCell = eRow.insertCell(0);
		eCell.style.paddingBottom = '3px';
		this._eNoSignatureControl = CreateChild(eCell, 'input', [['class', 'wm_checkbox'],
			['type', 'radio'], ['id', 'noSignature'], ['name', 'signatureType'], ['style', 'margin-top: 1px;']]);
		var obj = this;
		this._eNoSignatureControl.onclick = function () {
			var bUseSignature = !this.checked;
			obj.setUseSignature(bUseSignature);
		}
		eCell = eRow.insertCell(1);
		var eNoSignatureLabel = CreateChild(eCell, 'label', [['for', 'noSignature'], ['style', 'margin-left: 0px;']]);
		eNoSignatureLabel.innerHTML = Lang.NoSignature;
		WebMail.langChanger.register('innerHTML', eNoSignatureLabel, 'NoSignature', '');

		eRow = eIdentityTbl.insertRow(iRowIndex++);
		eCell = eRow.insertCell(0);
		eCell.style.verticalAlign = 'top';
		this._eUseSignatureControl = CreateChild(eCell, 'input', [['class', 'wm_checkbox'],
			['type', 'radio'], ['id', 'useSignature'], ['name', 'signatureType']]);
		this._eUseSignatureControl.onclick = function () {
			var bUseSignature = this.checked;
			obj.setUseSignature(bUseSignature);
		}
		eCell = eRow.insertCell(1);
		var ePlainEditorContainer = CreateChild(eCell, 'div', [['class', 'wm_input wm_plain_editor_container']]);
		var ePlainEditorControl = CreateChild(ePlainEditorContainer, 'textarea', [['class', 'wm_plain_editor_text']]);
		this._ePlainEditorControl = ePlainEditorControl;

		eRow = eIdentityTbl.insertRow(iRowIndex++);
		eCell = eRow.insertCell(0);
		eCell.colSpan = 2;
		eCell.className = 'wm_settings_title';
		var eModeSwitcher = CreateChild(eCell, 'a', [['href', '#']]);
		eModeSwitcher.innerHTML = Lang.SwitchToHTMLMode;
		this._eModeSwitcher = eModeSwitcher;

		var eButtonsPane = CreateChild(this._eEditZone, 'div', [['class', 'wm_identity_buttons']]);
		var eSaveButton = CreateChild(eButtonsPane, 'input', [['class', 'wm_button'], ['type', 'button'], ['value', Lang.Save]]);
		WebMail.langChanger.register('value', eSaveButton, 'Save', '');
		eSaveButton.onclick = function () {
			obj.saveChanges();
		};

		this._bBuilded = true;
	}
}