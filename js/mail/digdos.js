/*
 * Classes:
 *  CDossiersData()
 * Functions:
 *  OpenDigDosDialog()
 *  MoveToDigDosFolderHandler()
 * Objects:
 *  DigDosDialog
 */

function CDossiersData()
{
	this.type = TYPE_DOSSIERS_DATA;

	this.sFolderName = '';
	this.aItems = [];
}

CDossiersData.prototype = {
	getStringDataKeys: function ()
	{
		return '';
	},

	getFromXml: function(eRoot)
	{
		var
			aDossiers = eRoot.childNodes,
			iIndex = 0,
			iLen = aDossiers.length,
			eDossier = null,
			eName = null,
			sName = '',
			eId = null,
			sId = ''
		;
		
		this.sFolderName = XmlHelper.getAttributeByName(eRoot, 'folder', this.sFolderName);

		for (; iIndex < iLen; iIndex++) {
			eDossier = aDossiers[iIndex];
			eName = XmlHelper.getFirstChildNodeByName(eDossier, 'name');
			sName = XmlHelper.getFirstChildValue(eName, '');
			eId = XmlHelper.getFirstChildNodeByName(eDossier, 'value');
			sId = XmlHelper.getFirstChildValue(eId, '');
			this.aItems.push({ sName: sName, sId: sId });
		}
	}
}

function OpenDigDosDialog()
{
	var sCurrFldName = WebMail.getCurrentFolderName();
	if (sCurrFldName === WebMail.Settings.sDigDosName) {
		MoveToDigDosFolderHandler(sCurrFldName);
	}
	else {
		DigDosDialog.open();
	}
}

function MoveToDigDosFolderHandler(sCurrFldName)
{
	var
		oDigDosFld = null,
		oInboxFld = null
	;
	if (sCurrFldName === WebMail.Settings.sDigDosName) {
		DigDosDialog.setSelected();
		oInboxFld = WebMail.getCurrentInboxFolder();
		RequestMessagesOperationHandler(TOOLBAR_MOVE_TO_FOLDER, [], [], oInboxFld.id, oInboxFld.fullName);
	}
	else {
		oDigDosFld = WebMail.getFolderByName(WebMail.Settings.sDigDosName, true);
		if (oDigDosFld !== null) {
			RequestMessagesOperationHandler(TOOLBAR_MOVE_TO_FOLDER, [], [], oDigDosFld.id, oDigDosFld.fullName);
		}
	}
}

var DigDosDialog = {
	aDossiers: [],
	$Dialog: null,
	$Select: null,
	bSelected: false,
	sSelectedId: '',
	
	setDossiers: function (oDossiers)
	{
		this.aDossiers = oDossiers.aItems;
	},
	
	open: function ()
	{
		var
			self = this,
			aButtons = [
				{
					text: Lang.OK,
					click: function() { self.onClickOkButton(); }
				},
				{
					text: Lang.Cancel,
					click: function() { $(this).dialog('close'); }
				}
			]
		;
		
		this._init();
		this.sSelectedId = '';
		this.bSelected = false;
		this.$Dialog.dialog('option', 'buttons', aButtons);
		this.$Dialog.dialog({ title: Lang.DigDosTitle + ':' });
		this.$Dialog.dialog('open');
	},
	
	setSelected: function ()
	{
		this.bSelected = true;
		this.sSelectedId = '';
	},
	
	isSelected: function ()
	{
		var bSelected = this.bSelected;
		this.bSelected = false;
		return bSelected;
	},
	
	getSelectedId: function ()
	{
		return this.sSelectedId;
	},
	
	onClickOkButton: function ()
	{
		this.sSelectedId = this.$Select.val();
		this.bSelected = true;
		MoveToDigDosFolderHandler();
		this.$Dialog.dialog('close');
	},
	
	_init: function ()
	{
		if (this.$Dialog !== null) {
			return;
		}
		
		var
			iIndex = 0,
			iLen = this.aDossiers.length,
			oDossier
		;
		
		this.$Dialog = $('<div></div>').appendTo(document.body);
		this.$Select = $('<select></select>').appendTo(this.$Dialog);
		
		for (; iIndex < iLen; iIndex++) {
			oDossier = this.aDossiers[iIndex];
			$('<option></option>').attr('value', oDossier.sId).text(oDossier.sName).appendTo(this.$Select);
		}
		
		this.$Dialog.dialog({
					modal: true,
					autoOpen: false,
					minHeight: 100,
					minWidth: 140,
					position: 'center'
				});
	}
}