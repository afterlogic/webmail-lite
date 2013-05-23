/*
 * Functions:
 *  CreateEditIdentityClickFunc(id)
 * Classes:
 *  CIdentitiesPane(oParent)
 */

function CreateEditIdentityClickFunc(iId, iAcctId, oIdentitiesPane)
{
	return function () {
		WebMail.oIdentities.changeCurrentIdentity(iId, iAcctId);
		oIdentitiesPane.fill(false);
		return false;
	};
}

function CIdentitiesPane(oParent)
{
	this._bBuilded = false;
	this._bShown = false;

	this._eAddButtonZone = null;
	this._eAccountsContainer = null;
	this._eAccountsControl = null;
	this._eEditZone = null;
	this._eEmailContainer = null;
	this._eEmailControl = null;
	this._eListZone = null;
	this._eModeSwitcher = null;
	this._eName = null;
	this._eNoSignatureControl = null;
	this._ePlainEditorControl = null;
	this._eRequiredFieldsInfo = null;
	this._eUseSignatureControl = null;

	this._oParent = oParent;
}

CIdentitiesPane.prototype = {
	show: function(bNewMode)
	{
		this._bShown = true;
		if (!WebMail.Settings.bAllowIdentities) {
			return;
		}
		if (!this._bBuilded) {
			this._build();
		}
		if (!this._bBuilded) {
			return;
		}
		this._eListZone.className = 'wm_settings_list';
		this._eAddButtonZone.className = 'wm_settings_add_account_button';
		HtmlEditorField.setPlainEditor(this._ePlainEditorControl, this._eModeSwitcher, -1, false);
		this.fill(bNewMode);
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
		this._eListZone.className = 'wm_hide';
		this._eAddButtonZone.className = 'wm_hide';
		this._eEditZone.className = 'wm_hide';
//		HtmlEditorField.makeActive();
		HtmlEditorField.hide();
	},

	getNewSignature: function ()
	{
	},

	fill: function (bNewMode)
	{
		if (bNewMode !== undefined) {
			this._bNewMode = bNewMode;
		}
		if (WebMail.oIdentities.iCount > 0) {
			this._fillTable();
		}
		else {
			this._fillEmptyIdentities();
		}
		this._fillIdentity();
		if (this._oParent) {
			this._oParent.resizeBody();
		}
	},

	_fillEmptyIdentities: function ()
	{
		CleanNode(this._eListZone);
		var eTable = CreateChild(this._eListZone, 'table');
		var eRow = eTable.insertRow(0);
		var eCell = eRow.insertCell(0);
		eCell.innerHTML = Lang.NoIdentities;
	},

	_fillTable: function ()
	{
		CleanNode(this._eListZone);
		var eTable = CreateChild(this._eListZone, 'table');
		var oIdentitiesPane = this;
		var aIdentities = WebMail.oIdentities.aItems;
		for (var i = 0; i < aIdentities.length; i++) {
			var oIdentity = aIdentities[i];
			var eRow = eTable.insertRow(i);
			var eCell = eRow.insertCell(0);
			if (!this._bNewMode && WebMail.oIdentities.isCurrentIdentity(oIdentity)) {
				eRow.className = 'wm_settings_list_select';
				eCell.innerHTML = '<b>' + oIdentity.sEmail + '</b>';
			}
			else {
				eCell.className = 'wm_control';
				eCell.innerHTML = oIdentity.sEmail;
				eCell.onclick = CreateEditIdentityClickFunc(oIdentity.iId, oIdentity.iAcctId, oIdentitiesPane);
			}
			eCell = eRow.insertCell(1);
			eCell.style.width = '10px';

			var eDeleteLink = CreateChild(eCell, 'a', [['href', '#']]);
			eDeleteLink.innerHTML = Lang.Delete;
			eDeleteLink.onclick = (function (iId, oIdentitiesPane) {
				return function () {
					Dialog.confirm(Lang.ConfirmDeleteAccount, function () {
						WebMail.oIdentities.removeById(iId);
						oIdentitiesPane.fill(false);
						WebMail.DataSource.request({action: 'delete', request: 'identity', 'id': iId}, '');
					});
					return false;
				};
			})(oIdentity.iId, oIdentitiesPane);
		}
		
	},

	_fillAccounts: function (iAcctId)
	{
		CleanNode(this._eAccountsControl);
		var aAccounts = WebMail.Accounts.items;
		for (var i = 0; i < aAccounts.length; i++) {
			var oAccount = aAccounts[i];
			var eOpt = CreateChild(this._eAccountsControl, 'option', [['value', oAccount.id]]);
			eOpt.innerHTML = oAccount.email;
			if (oAccount.id === iAcctId) {
				eOpt.selected = true;
			}
		}
	},

	_fillIdentity: function ()
	{
		var oIdentity = null;
		if (this._bNewMode) {
			oIdentity = new CIdentity();
			this._eRequiredFieldsInfo.className = 'wm_secondary_info';
		}
		else {
			oIdentity = WebMail.oIdentities.getCurrentIdentity();
			this._eRequiredFieldsInfo.className = 'wm_hide';
		}
		if (oIdentity === null) {
			this._eEditZone.className = 'wm_hide';
			HtmlEditorField.hide();
			return;
		}
		this._fillAccounts(oIdentity.iAcctId);
		this._eEditZone.className = 'wm_identity_edit_zone';
		if (this._bNewMode) {
			this._eEmailContainer.className = '';
			this._eEmailControl.value = oIdentity.sEmail;
		}
		else {
			this._eEmailContainer.className = 'wm_hide';
		}
		this._eName.value = oIdentity.sName;
		if (oIdentity.bHtmlSignature) {
			HtmlEditorField.setHtml(oIdentity.getHtmlSignature());
		}
		else {
			HtmlEditorField.setPlain(oIdentity.getPlainSignature());
		}
		this.setUseSignature(oIdentity.bUseSignature);
		HtmlEditorField.resize(607, 200);
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

	_getNewIdentity: function ()
	{
		var oIdentity = null;
		if (this._bNewMode) {
			oIdentity = new CIdentity();
		}
		else {
			oIdentity = WebMail.oIdentities.getCurrentIdentity();
		}
		if (oIdentity === null) {
			return null;
		}
		oIdentity.iAcctId = this._eAccountsControl.value - 0;
		if (this._bNewMode) {
			oIdentity.sEmail = this._eEmailControl.value;
		}
		oIdentity.sName = this._eName.value;
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
		return oIdentity;
	},

	_isCorrectData: function (oIdentity)
	{
		if (oIdentity.sEmail.length > 0) {
			var aIncorrectEmails = validateMessageAddressString(oIdentity.sEmail);
			if (aIncorrectEmails.length > 0) {
				Dialog.alert(Lang.WarningCorrectEmail);
				return false;
			}
		}
		else if (this._bNewMode) {
			Dialog.alert(Lang.WarningEmailFieldBlank);
			return false;
		}
		return true;
	},

	saveChanges: function ()
	{
		var oIdentity = this._getNewIdentity();
		if (oIdentity === null) {
			return;
		}

		if (!this._isCorrectData(oIdentity)) {
			return;
		}

		var sXml = oIdentity.getInXml();
		if (this._bNewMode) {
			RequestHandler('new', 'identity', sXml);
		}
		else {
			RequestHandler('update', 'identity', sXml);
		}
	},

	build: function (eParent)
	{
		this._eListZone = CreateChild(eParent, 'div', [['class', 'wm_hide']]);
		this._eAddButtonZone = CreateChild(eParent, 'div', [['class', 'wm_hide']]);
		this._eEditZone = CreateChild(eParent, 'div', [['class', 'wm_hide']]);
	},

	_build: function()
	{
		if (this._eEditZone === null) {
			return;
		}

		var eAddButton = CreateChild(this._eAddButtonZone, 'input', [['class', 'wm_button'], ['type', 'button'], ['value', Lang.AddIdentity]]);
		WebMail.langChanger.register('value', eAddButton, 'AddIdentity', '');
		eAddButton.onclick = function () {
			oIdentitiesPane.fill(true);
		};
		
		var eIdentityTbl = CreateChild(this._eEditZone, 'table', [['class', 'wm_identity_controls']]);

		var iRowIndex = 0;
		var eRow = eIdentityTbl.insertRow(iRowIndex++);
		var eCell = eRow.insertCell(0);
		eCell.className = 'wm_settings_title';
		eCell.innerHTML = Lang.Account;
		WebMail.langChanger.register('innerHTML', eCell, 'Account', '');
		eCell = eRow.insertCell(1);
		eCell = eRow.insertCell(2);
		this._eAccountsControl = CreateChild(eCell, 'select', [['class', 'wm_input']]);
		this._eAccountsContainer = eRow;

		eRow = eIdentityTbl.insertRow(iRowIndex++);
		eCell = eRow.insertCell(0);
		eCell.className = 'wm_settings_title';
		eCell.innerHTML = '* ' + Lang.Email;
		WebMail.langChanger.register('innerHTML', eCell, 'Email', '', '* ');
		eCell = eRow.insertCell(1);
		eCell = eRow.insertCell(2);
		this._eEmailControl = CreateChild(eCell, 'input', [['class', 'wm_input'], ['type', 'text']]);
		this._eEmailContainer = eRow;

		eRow = eIdentityTbl.insertRow(iRowIndex++);
		eCell = eRow.insertCell(0);
		eCell.className = 'wm_settings_title';
		eCell.innerHTML = Lang.MailFriendlyName;
		WebMail.langChanger.register('innerHTML', eCell, 'MailFriendlyName', '');
		eCell = eRow.insertCell(1);
		eCell = eRow.insertCell(2);
		this._eName = CreateChild(eCell, 'input', [['class', 'wm_input'], ['type', 'text']]);

		eRow = eIdentityTbl.insertRow(iRowIndex++);
		eCell = eRow.insertCell(0);
		eCell.className = 'wm_settings_title';
		eCell.innerHTML = Lang.Signature;
		WebMail.langChanger.register('innerHTML', eCell, 'Signature', '');
		eCell = eRow.insertCell(1);
		eCell.style.paddingBottom = '3px';
		this._eNoSignatureControl = CreateChild(eCell, 'input', [['class', 'wm_checkbox'],
			['type', 'radio'], ['id', 'noSignature'], ['name', 'signatureType'], ['style', 'margin-top: 1px;']]);
		var obj = this;
		this._eNoSignatureControl.onclick = function () {
			var bUseSignature = !this.checked;
			obj.setUseSignature(bUseSignature);
		}
		eCell = eRow.insertCell(2);
		var eNoSignatureLabel = CreateChild(eCell, 'label', [['for', 'noSignature'], ['style', 'margin-left: 0px;']]);
		eNoSignatureLabel.innerHTML = Lang.NoSignature;
		WebMail.langChanger.register('innerHTML', eNoSignatureLabel, 'NoSignature', '');

		eRow = eIdentityTbl.insertRow(iRowIndex++);
		eCell = eRow.insertCell(0);
		eCell = eRow.insertCell(1);
		eCell.style.verticalAlign = 'top';
		this._eUseSignatureControl = CreateChild(eCell, 'input', [['class', 'wm_checkbox'],
			['type', 'radio'], ['id', 'useSignature'], ['name', 'signatureType']]);
		this._eUseSignatureControl.onclick = function () {
			var bUseSignature = this.checked;
			obj.setUseSignature(bUseSignature);
		}
		eCell = eRow.insertCell(2);
		var ePlainEditorContainer = CreateChild(eCell, 'div', [['class', 'wm_input wm_plain_editor_container']]);
		var ePlainEditorControl = CreateChild(ePlainEditorContainer, 'textarea', [['class', 'wm_plain_editor_text']]);
		this._ePlainEditorControl = ePlainEditorControl;
		
		eRow = eIdentityTbl.insertRow(iRowIndex++);
		eCell = eRow.insertCell(0);
		eCell = eRow.insertCell(1);
		eCell.colSpan = 2;
		eCell.className = 'wm_settings_title';
		var eModeSwitcher = CreateChild(eCell, 'a', [['href', '#']]);
		eModeSwitcher.innerHTML = Lang.SwitchToHTMLMode;
		this._eModeSwitcher = eModeSwitcher;

		var eButtonsPane = CreateChild(this._eEditZone, 'div', [['class', 'wm_identity_buttons']]);

		var eRequiredFieldsInfo = CreateChild(eButtonsPane, 'span', [['class', 'wm_secondary_info'],
			['style', 'float: left;']]);
		eRequiredFieldsInfo.innerHTML = Lang.InfoRequiredFields;
		WebMail.langChanger.register('innerHTML', eRequiredFieldsInfo, 'InfoRequiredFields', '');
		this._eRequiredFieldsInfo = eRequiredFieldsInfo;

		var eSaveButton = CreateChild(eButtonsPane, 'input', [['class', 'wm_button'], ['type', 'button'], ['value', Lang.Save]]);
		WebMail.langChanger.register('value', eSaveButton, 'Save', '');
		eSaveButton.onclick = function () {
			obj.saveChanges();
		};
		
		var eIndent = CreateChild(eButtonsPane, 'span');
		eIndent.innerHTML = '&nbsp;';

		var eCancelButton = CreateChild(eButtonsPane, 'input', [['class', 'wm_button'], ['type', 'button'], ['value', Lang.Cancel]]);
		WebMail.langChanger.register('value', eCancelButton, 'Cancel', '');
		var oIdentitiesPane = this;
		eCancelButton.onclick = function () {
			WebMail.oIdentities.clearCurrentId();
			oIdentitiesPane.fill(false);
		};

		this._bBuilded = true;
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}