/*
 * Classes:
 *  CImportContactsScreenPart()
 */

function CImportContactsScreenPart()
{
	this._mainFrm = null;
	this._importFile = null;
	/* this._fileType1 = null;
	this._fileType2 = null; */
}

CImportContactsScreenPart.prototype = {
	show: function ()
	{
		this._mainFrm.className = '';
	},
	
	hide: function ()
	{
		this._mainFrm.className = 'wm_hide';
	},
	
	build: function (container)
	{
		var obj = this;
		
		CreateChild(container, 'iframe name="ImportFrame"', [['src', EmptyHtmlUrl],
			['style', 'width:1px; height:1px; border:0px; display:none;'], ['id', 'ImportFrame']]);

		var frm = CreateChild(container, 'form', [['action', ImportUrl], ['method', 'post'],
			['enctype', 'multipart/form-data'], ['target', 'ImportFrame']]);
		frm.onsubmit = function () {
			// if (!obj._fileType1.checked && !obj._fileType2.checked ) {
				// Dialog.alert(Lang.WarningImportFileType);
				// return false;
			// }
			if (Validator.isEmpty(obj._importFile.value)) {
				Dialog.alert(Lang.WarningEmptyImportFile);
				return false;
			}
			if (!Validator.hasFileExtention(obj._importFile.value, 'csv')) {
				Dialog.alert(Lang.WarningCsvExtention);
				return false;
			}
			return true;
		};
		frm.className = 'wm_hide';
		this._mainFrm = frm;

		var tbl = CreateChild(frm, 'table');
		tbl.className = 'wm_contacts_view';
		tbl.style.marginTop = '0';
		var rowIndex = 0;
		var tr = tbl.insertRow(rowIndex++);
		var td = tr.insertCell(0);
		var b = CreateChild(td, 'b');
		b.innerHTML = Lang.UseImportTo;
		WebMail.langChanger.register('innerHTML', b, 'UseImportTo', '');
		/* tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		this._fileType1 = CreateChild(td, 'input', [['type', 'radio'], ['class', 'wm_checkbox'], ['id', 'file_type_1'], ['name', 'file_type'], ['value', '0']]);
		if (Browser.mozilla) {
		    this._fileType1.style.margin = '4px 0 4px 0';
		}
		var lbl = CreateChild(td, 'label', [['for', 'file_type_1']]);
		lbl.innerHTML = Lang.Outlook1;
		WebMail.langChanger.register('innerHTML', lbl, 'Outlook1', '');
		CreateChild(td, 'br');
		this._fileType2 = CreateChild(td, 'input', [['type', 'radio'], ['class', 'wm_checkbox'], ['id', 'file_type_2'], ['name', 'file_type'], ['value', '1']]);
		if (Browser.mozilla) {
		    this._fileType2.style.margin = '4px 0 4px 0';
		}
		lbl = CreateChild(td, 'label', [['for', 'file_type_2']]);
		lbl.innerHTML = Lang.Outlook2;
		WebMail.langChanger.register('innerHTML', lbl, 'Outlook2', ''); */
		
		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.innerHTML = Lang.SelectImportFile + ':';
		WebMail.langChanger.register('innerHTML', td, 'SelectImportFile', ':');
		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		this._importFile = CreateChild(td, 'input', [['type', 'file'], ['class', 'wm_file'], ['size', '30'], ['name', 'Filedata']]);

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		var a = CreateChild(td, 'a', [['href', 'http://www.afterlogic.com/wiki/Importing_contacts_%28WebMail_Pro_6%29'], ['target', 'blank']]);
		a.innerHTML = Lang.ReadAboutCSVLink;
		WebMail.langChanger.register('innerHTML', a, 'ReadAboutCSVLink', '');
		
		tbl = CreateChild(frm, 'table');
		tbl.className = 'wm_contacts_view';
		tbl.style.width = '95%';
		tr = tbl.insertRow(0);
		td = tr.insertCell(0);
		td.style.textAlign = 'right';
		td.style.borderTop = 'solid 1px #8D8C89';
		var inp = CreateChild(td, 'input', [['type', 'submit'], ['class', 'wm_file'], ['value', Lang.Import]]);
		WebMail.langChanger.register('value', inp, 'Import', '');
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}