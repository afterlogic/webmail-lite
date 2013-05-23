/*
 * Classes:
 *  CManageFoldersPane(oParent)
 *  CFolderLine(fold, tr, opt, parent, prevIndex, index, protocol, folders)
 *  CSpecialFoldersPane(htmlParent, objParent)
 */

function CManageFoldersPane(oParent)
{
	this._oParent = oParent;
	
	this._folders = new CFolderList();
	this.folderList = [];
	
	this.hasChanges = false;
	this.isChangedFolders = false;
	this.shown = false;
	this.disableCount = 0;

	this._idAcct = -1;
	this._changedIdAcct = false;
	this._protocol = POP3_PROTOCOL;
	
	this._mainCont = null;
	this._infoTbl = null;
	this._foldersEditZone = null;
	this._newFolderDiv = null;
	this._foldersSelLabel = null;
	this._foldersSelCell = null;
	this._foldersSelObj = null;
	this._foldersFakeSelObj = null;
	this._noParentObj = null;
	this._nameObj = null;
	this._checkAllObj = null;

	this._eSetupSpecialFoldersButton = {};
	this._eSetupSpecialFoldersInfo = {};
	this._oSpecialFoldersPane = null;
}

CManageFoldersPane.prototype = {
	show: function ()
	{
		this.shown = true;
		this.hasChanges = false;
		this._mainCont.className = '';
		this._foldersEditZone.className = 'wm_email_settings_edit_zone';
		if (this.disableCount > 0) this._infoTbl.className = 'wm_secondary_info';
		this.CloseNewFolder();
		if (this._idAcct !== WebMail.Accounts.editableId) {
			this._idAcct = WebMail.Accounts.editableId;
		    GetHandler(TYPE_FOLDER_LIST, {idAcct: WebMail.Accounts.editableId, sync: GET_FOLDERS_NOT_CHANGE_ACCT}, [], '');
		}
		else {
			this.fill();
		}
	},
	
	hide: function ()
	{
		this.shown = false;
		this._saveOrRevert();
		this.hasChanges = false;
		this._mainCont.className = 'wm_hide';
		this._foldersEditZone.className = 'wm_hide';
		this._infoTbl.className = 'wm_hide';
		this.CloseNewFolder();
		this._oSpecialFoldersPane.hide();
	},

	_saveOrRevert: function ()
	{
		if (this.hasChanges) {
			Dialog.confirm(
				Lang.ConfirmAddFolder,
				(function (obj) {
					return function () {
						obj.saveChanges();
					};
				})(this),
				(function (obj) {
					return function () {
						obj.fill();
					};
				})(this)
			);
		}
	},
	
	addNewFolder: function ()
	{
		this._saveOrRevert();
		this._noParentObj.selected = true;
		this._nameObj.value = '';
		this._newFolderDiv.className = '';
		this._foldersSelObj.className = '';
		this.resize();
	},

	SaveNewFolder: function ()
	{
		var folderName = this._nameObj.value;
		if (Validator.isEmpty(folderName)) {
			Dialog.alert(Lang.WarningEmptyFolderName);
			return;
		}
		if (!Validator.isCorrectFileName(folderName) || Validator.hasSpecSymbols(folderName)) {
			Dialog.alert(Lang.WarningCorrectFolderName);
			return;
		}
		
		if (!Validator.isCorrectFolderNameLength(folderName)) {
			Dialog.alert(Lang.WarningCorrectFolderName);
			return;
		}
		if (!Validator.isCorrectFolderNameValue(folderName)) {
			Dialog.alert(Lang.WarningCorrectFolderName);
			return;
		}

		var xml = '<param name="id_acct" value="' + this._idAcct + '"/>';
		var values = this._foldersSelObj.value.split(STR_SEPARATOR);
		var idParent = values[0];
		var fullNameParent = values[1];
		xml += '<param name="id_parent" value="' + idParent + '"/>';
		xml += '<param name="full_name_parent">' + GetCData(fullNameParent, false, true) + '</param>';
		xml += '<param name="name">' + GetCData(this._nameObj.value) + '</param>';
		this.isChangedFolders = true;
		RequestHandler('new', 'folder', xml);
		this.CloseNewFolder();
	},

	CloseNewFolder: function ()
	{
		this._newFolderDiv.className = 'wm_hide';
		this.resize();
	},

	resize: function ()
	{
		if (this._oParent) {
			this._oParent.resizeBody();
		}
	},
	
	setupSpecialFolders: function ()
	{
		this._saveOrRevert();
		this._oSpecialFoldersPane.show(this._folders.folders);
		this.resize();
	},

	UpdateFolders: function (folders)
	{
		if (this.isChangedFolders) {
			WebMail.showReport(Lang.ReportFoldersUpdatedSuccessfuly);
			this.isChangedFolders = false;
		}
		this._folders = folders;
		this._changedIdAcct = false;
		this.fill();
	},

	UpdateProtocol: function (protocol)
	{
		this._protocol = protocol;
	},

	CheckAll: function (value)
	{
		this._checkAllObj.checked = value;
		var count = this.folderList.length;
		for (var i = 0; i < count; i++) {
			this.folderList[i].setChecked(value);
		}
	},
	
	deleteSelected: function ()
	{
		if (this.hasChanges) {
			this._saveOrRevert();
			return;
		}

		var count = this.folderList.length;
		var xml = '<param name="id_acct" value="' + this._idAcct + '"/>';
		var folders = '';
		for (var i = 0; i < count; i++) {
			folders += this.folderList[i].getCheckedXml();
		}
		xml += '<folders>' + folders + '</folders>';
		if (folders != '') {
			this.isChangedFolders = true;
			RequestHandler('delete', 'folders', xml);
			WebMail.ClearFilterCache();
		}
	},

	_cleanFolderSelects: function ()
	{
		CleanNode(this._foldersSelObj);
		CleanNode(this._foldersFakeSelObj);
	},

	_addNoParentOpt: function ()
	{
		var noParentId = -1;
		var noParentName = '';
		if (this._folders.nameSpace.length > 0) {
			noParentId = this._folders.getNameSpaceFolderId();
			noParentName = this._folders.nameSpace.substr(0, this._folders.nameSpace.length - 1);
		}
		var opt = CreateChild(this._foldersSelObj, 'option', [['value', noParentId + STR_SEPARATOR + noParentName]]);
		opt.innerHTML = '&nbsp;' + Lang.NoParent;
		this._noParentObj = opt;
	},

	fill: function ()
	{
		if (!this.shown || this._folders.idAcct !== this._idAcct) {
			return;
		}
		
		if (this._protocol == POP3_PROTOCOL) {
			this._eSetupSpecialFoldersButton.className = 'wm_hide';
			this._eSetupSpecialFoldersInfo.className = 'wm_hide';
		}
		else {
			this._eSetupSpecialFoldersButton.className = 'wm_button';
			this._eSetupSpecialFoldersInfo.className = '';
		}
		this._cleanFolderSelects();
		this._addNoParentOpt();

		var bHideSystemFolders = (this._folders.nameSpace.length > 0) ? true : false;

		CleanNode(this._mainCont);
		var tbl = CreateChild(this._mainCont, 'table');
		tbl.className = 'wm_settings_manage_folders';
		this.buildHeader(tbl);

		var rowIndex = 1;
		var folders = this._folders.folders;
		var prevIndex;
		var count = 0;
		this.folderList = Array();
		var prevFoldIndexes = Array();
		var iCount = folders.length;
		this.disableCount = 0;
		var bIsShowFolderSelect = false;
		for (var i = 0; i < iCount; i++) {
			var fold = folders[i];

			count += fold.msgCount;

			var opt;
			if (!bHideSystemFolders || fold.type == FOLDER_TYPE_DEFAULT) {
				bIsShowFolderSelect = true;
				opt = CreateChild(this._foldersSelObj, 'option');
			}
			else {
				opt = CreateChild(this._foldersFakeSelObj, 'option');
			}
			opt.value = fold.id + STR_SEPARATOR + fold.fullName;
			opt.innerHTML = fold.name;
			if (typeof(prevFoldIndexes[fold.idParent]) == 'number') {
				prevIndex = prevFoldIndexes[fold.idParent];
				this.folderList[prevIndex].setNextFoldLine(i);
			}
			else {
				prevIndex = -1;
			}

			var tr = tbl.insertRow(rowIndex++);
			var foldLine = new CFolderLine(fold, tr, opt, this, prevIndex, i, this._protocol, folders);

			this.disableCount += foldLine.checkDisable;
			this.folderList[i] = foldLine;
			prevFoldIndexes[fold.idParent] = i;
		}

		if (!bIsShowFolderSelect) {
			this._foldersSelLabel.className = 'wm_hide';
			this._foldersSelCell.className = 'wm_hide';
		}
		else {
			this._foldersSelLabel.className = '';
			this._foldersSelCell.className = '';
		}

		this._infoTbl.className = (this.disableCount > 0 && this.shown)
			? 'wm_secondary_info' : 'wm_hide';

		this.buildTotal(tbl, rowIndex, count);
		this.hasChanges = false;

		this.CloseNewFolder();
	},//fill

	ChangeFoldersPlaces: function (prevIndex, index)
	{
		var fold = this.folderList[index];
		var prop = fold.getProperties();
		var fldOrder = prop.fldOrder;
		var nextIndex = prop.nextIndex;
		var prevFold = this.folderList[prevIndex];
		var prevProp = prevFold.getProperties();
		prop.fldOrder = prevProp.fldOrder;
		prevProp.fldOrder = fldOrder;
		if (nextIndex != -1) {
			var nextFold = this.folderList[nextIndex];
			var nextProp = nextFold.getProperties();
		}
		var foldPrevIndex = prevProp.prevIndex;
		var prevFoldPrevIndex = prop.prevIndex;
		var prevFoldNextIndex = prop.nextIndex;
		
		var childFold, childProp;
		var prevChilds = Array();
		for (var i = prevIndex + 1; i < index; i++) {
			childFold = this.folderList[i];
			childProp = childFold.getProperties();
			prevChilds.push(childProp);
		}
		var prevChCount = prevChilds.length;

		var childs = Array();
		var flag = true;
		var idParent = prop.idParent;
		var level = prop.level;
		for (i = index + 1; flag; i++) {
			if (i == nextIndex) {
				flag = false;
			}
			else {
				childFold = this.folderList[i];
				if (childFold) {
					childProp = childFold.getProperties();
					if (idParent == childProp.idParent || level >= childProp.level) {
						flag = false;
					}
					else {
						childs.push(childProp);
					}
				}
				else {
					flag = false;
				}
			}
		}
		var chCount = childs.length;
		
		var newIndex = prevIndex + chCount + 1;
		
		prop.prevIndex = foldPrevIndex;
		prop.index = prevIndex;
		prop.nextIndex = newIndex;
		this.folderList[prevIndex].setProperties(prop);
		
		for (i = 0; i < chCount; i++) {
			if (childs[i].prevIndex != -1) {
				childs[i].prevIndex = childs[i].prevIndex - prevChCount - 1;
			}
			childs[i].index = childs[i].index - prevChCount - 1;
			if (childs[i].nextIndex != -1) {
				childs[i].nextIndex = childs[i].nextIndex - prevChCount - 1;
			}
			this.folderList[prevIndex + 1 + i].setProperties(childs[i]);
		}
		
		prevProp.prevIndex = prevFoldPrevIndex;
		prevProp.index = newIndex;
		prevProp.nextIndex = prevFoldNextIndex;
		this.folderList[newIndex].setProperties(prevProp);

		for (i = 0; i < prevChCount; i++) {
			if (prevChilds[i].prevIndex != -1) {
				prevChilds[i].prevIndex = prevChilds[i].prevIndex + chCount + 1;
			}
			prevChilds[i].index = prevChilds[i].index + chCount + 1;
			if (prevChilds[i].nextIndex != -1) {
				prevChilds[i].nextIndex = prevChilds[i].nextIndex + chCount + 1;
			}
			this.folderList[newIndex + 1 + i].setProperties(prevChilds[i]);
		}

		if (nextIndex != -1) {
			nextProp.prevIndex = newIndex;
			this.folderList[nextIndex].setProperties(nextProp);
		}
		this.hasChanges = true;
	},//ChangeFoldersPlaces
	
	_setInputKeyPressSaveNewFolder: function (inp)
	{
	    var obj = this;
		inp.onkeypress = function (ev) {
			if (isEnter(ev)) obj.SaveNewFolder();
		};
	},

	applySpecialFolders: function (folders)
	{
		for (var i = 0; i < this.folderList.length; i++) {
			var foldLine = this.folderList[i];
			if (foldLine.isInboxType()) continue;
			var newFoldType = FOLDER_TYPE_DEFAULT;
			for (var foldType in folders) {
				var fold = folders[foldType];
				if (typeof(fold) === 'function') continue;
				if (foldLine.isCorrectFolderId(fold.id, fold.fullName)) {
					newFoldType = foldType;
					break;
				}
			}
			foldLine.setFolderType(newFoldType);
		}
		this.saveChanges();
	},
	
	saveChanges: function ()
	{
		var nodes = '';
		var count = this.folderList.length;
		for (var i = 0; i < count; i++) {
			nodes += this.folderList[i].getInXml();
		}
		var xml = '<param name="id_acct" value="' + this._idAcct + '"/>';
		xml += '<folders>' + nodes + '</folders>';
		RequestHandler('update', 'folders', xml);
		this.hasChanges = false;
		this.isChangedFolders = true;
	},
	
	buildHeader: function (tbl)
	{
		var obj = this;
		var colIndex = 0;
		var tr = tbl.insertRow(0);
		tr.className = 'wm_settings_mf_headers';
		var td = tr.insertCell(colIndex++);
		td.style.width = '26px';
		var inp = CreateChild(td, 'input', [['type', 'checkbox'], ['class', 'wm_checkbox']]);
		inp.onclick = function () {obj.CheckAll(this.checked);};
		this._checkAllObj = inp;

		td = tr.insertCell(colIndex++);
		var folderNameColumnWidth = 490;
		td.style.width = folderNameColumnWidth + 'px';
		td.className = 'wm_settings_mf_folder';

		var headDiv = CreateChild(td, 'div');
		headDiv.innerHTML = Lang.Folder;
		
		td = tr.insertCell(colIndex++);
		td.style.width = '40px';
		td.innerHTML = Lang.Msgs;

		td = tr.insertCell(colIndex++);
		td.style.width = '94px';
		if (this._protocol == IMAP4_PROTOCOL) {
			td.innerHTML = Lang.CaptionSubscribed;
		}
		else {
			td.innerHTML = Lang.ShowThisFolder;
		}

		td = tr.insertCell(colIndex++);
		td.style.width = '50px';
	},
	
	buildTotal: function (tbl, index, totalCount)
	{
		var tr = tbl.insertRow(index);
		tr.className = 'wm_settings_mf_total';
		var td = tr.insertCell(0);
		
		td = tr.insertCell(1);
		td.className = 'wm_settings_mf_folder';
		td.innerHTML = Lang.Total;

		td = tr.insertCell(2);
		td.innerHTML = totalCount;

		td = tr.insertCell(3);
		td.colSpan = 2;
		td.className = 'wm_settings_mf_page_switcher';
	},

	build: function(container)
	{
		this._foldersEditZone = CreateChild(container, 'div', [['class', 'wm_hide']]);

		this._mainCont = CreateChild(this._foldersEditZone, 'div');
		this._mainCont.className = 'wm_hide';

		var tbl = CreateChild(this._foldersEditZone, 'table');
		tbl.className = 'wm_hide';
		this._infoTbl = tbl;
		var tr = tbl.insertRow(0);
		var td = tr.insertCell(0);
		td.className = 'wm_secondary_info';
		td.innerHTML = Lang.InfoDeleteNotEmptyFolders;
		WebMail.langChanger.register('innerHTML', td, 'InfoDeleteNotEmptyFolders', '');

		tbl = CreateChild(this._foldersEditZone, 'table');
		tbl.className = 'wm_settings_buttons';
		var iRowIndex = 0;
		
		if (window.UseDb && WebMail.Settings.allowChangeAccountSettings) {
			tr = tbl.insertRow(iRowIndex++);
			tr.className = 'wm_hide';
			td = tr.insertCell(0);
			td.colSpan = 2;
			td.className = 'wm_secondary_info';
			td.innerHTML = Lang.InfoSetupSpecialFolders;
			WebMail.langChanger.register('innerHTML', td, 'InfoSetupSpecialFolders', '');
			this._eSetupSpecialFoldersInfo = tr;
		}

		tr = tbl.insertRow(iRowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_delete_button';

		var obj = this;
		ButtonsBuilder.addStandard(td, 'AddNewFolder', function () {obj.addNewFolder();}, true);
		if (window.UseDb && WebMail.Settings.allowChangeAccountSettings) {
			this._eSetupSpecialFoldersButton = ButtonsBuilder.addStandard(td, 'ButtonSetupSpecialFolders',
				function () { obj.setupSpecialFolders(); }, true);
		}
		ButtonsBuilder.addStandard(td, 'DeleteSelected', function () { obj.deleteSelected(); }, true);

		td = tr.insertCell(1);
		ButtonsBuilder.addStandard(td, 'ButtonSaveChanges', function () { obj.saveChanges(); }, false);
		
		this._buildNewFolderPane(this._foldersEditZone);
		this._oSpecialFoldersPane = new CSpecialFoldersPane(this._foldersEditZone, this);
	},

	_buildNewFolderPane: function (mainTd)
	{
		var div = CreateChild(mainTd, 'div');
		div.className = 'wm_hide';
		this._newFolderDiv = div;

		var tbl = CreateChild(div, 'table', [['class', 'wm_settings_new_folder']]);
		var iRowIndex = 0;
		var tr = tbl.insertRow(iRowIndex++);
		var td = tr.insertCell(0);
		td.colSpan = '2';
		td.className = 'wm_settings_part_info';
		td.innerHTML = Lang.NewFolder;
		WebMail.langChanger.register('innerHTML', td, 'NewFolder', '');


		tr = tbl.insertRow(iRowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		this._foldersSelLabel = CreateChild(td, 'span');
		this._foldersSelLabel.innerHTML = Lang.ParentFolder;
		WebMail.langChanger.register('innerHTML', this._foldersSelLabel, 'ParentFolder', '');
		td = tr.insertCell(1);
		this._foldersSelCell = CreateChild(td, 'span');
		var sel = CreateChild(this._foldersSelCell, 'select');
		var sel2 = CreateChild(this._foldersSelCell, 'select', [['class', 'wm_hide']]);
		this._foldersSelObj = sel;
		this._foldersFakeSelObj = sel2;
		var opt = CreateChild(sel, 'option', [['value', '0']]);
		opt.innerHTML = Lang.NoParent;
		this._noParentObj = opt;

		tr = tbl.insertRow(iRowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = Lang.FolderName;
		WebMail.langChanger.register('innerHTML', td, 'FolderName', '');
		td = tr.insertCell(1);
		var inp = CreateChild(td, 'input', [['type', 'text'], ['class', 'wm_input'], ['maxlength', '30']]);
		this._setInputKeyPressSaveNewFolder(inp);
		this._nameObj = inp;

		tr = tbl.insertRow(iRowIndex++);
		td = tr.insertCell(0);
		td.colSpan = '2';
		td.className = 'wm_settings_buttons_cell';
		
		inp = CreateChild(td, 'input', [['type', 'button'], ['class', 'wm_button'], ['value', Lang.OK]]);
		WebMail.langChanger.register('value', inp, 'OK', '');
		var obj = this;
		inp.onclick = function () {obj.SaveNewFolder();};
		CreateTextChild(td, ' ');
		inp = CreateChild(td, 'input', [['type', 'button'], ['class', 'wm_button'], ['value', Lang.Cancel]]);
		WebMail.langChanger.register('value', inp, 'Cancel', '');
		inp.onclick = function () {obj.CloseNewFolder();};
	}
};

function CFolderLine(fold, tr, opt, parent, prevIndex, index, protocol, folders)
{
	this.checkDisable = 0;

	this._protocol = protocol;
	this._folders = folders;
	this._opt = opt;
	this._parent = parent;

	this._fold = {};
	this._fold.id = fold.id;
	this._fold.idParent = fold.idParent;
	this._fold.type = fold.type;
	this._fold.syncType = fold.syncType;
	this._fold.hide  = fold.hide;
	this._fold.noselect  = fold.noselect;
	this._fold.fldOrder = fold.fldOrder;
	this._fold.hasChilds = fold.hasChilds;
	this._fold.msgCount = fold.msgCount;
	this._fold.newMsgCount = fold.newMsgCount;
	this._fold.size = fold.size;
	this._fold.name = fold.name;
	this._fold.fullName = fold.fullName;
	this._fold.level = fold.level;
	this._fold.intIndent = fold.intIndent;
	this._fold.strIndent = fold.strIndent;
	this._fold.checked = false;
	this._fold.prevIndex = prevIndex;
	this._fold.index = index;
	this._fold.nextIndex = -1;
	
	this._directModeOpt = null;
	this._mapSel = null;

	this._eUpArrow = {};
	this._eDownArrow = {};

	this._fill(tr, fold);
	this._init();
}

CFolderLine.prototype = {
//public
	isCorrectFolderId: function (id, fullName)
	{
		if (this._fold.id == id && this._fold.fullName == fullName) {
			return true;
		}
		return false;
	},

	setFolderType: function (foldType)
	{
		this._fold.type = foldType;
		this._fillInfo();
		this._init();
	},

	isInboxType: function ()
	{
		if (this._fold.type == FOLDER_TYPE_INBOX) {
			return true;
		}
		return false;
	},

	setNextFoldLine: function (index)
	{
		this._fold.nextIndex = index;
		if (this._fold.nextIndex == -1) {
			this._eDownArrow.className = 'wm_settings_mf_down_inactive';
			this._eDownArrow.onclick = function () {};
		}
		else {
			this._eDownArrow.className = 'wm_settings_mf_down wm_control_img';
			var obj = this;
			this._eDownArrow.onclick = function () {
				obj.changeWithNext();
			};
		}
	},
	
	changeWithPrev: function ()
	{
		this._parent.ChangeFoldersPlaces(this._fold.prevIndex, this._fold.index);
	},
	
	changeWithNext: function ()
	{
		this._parent.ChangeFoldersPlaces(this._fold.index, this._fold.nextIndex);
	},

	setChecked: function (value) {
		if (this._fold.type == FOLDER_TYPE_DEFAULT && (this._protocol == POP3_PROTOCOL || !this._fold.hasChilds && this._fold.msgCount == 0)) {
			this._fold.checked = value;
			this._checkInp.checked = value;
		}
	},
	
	getCheckedXml: function () {
		if (this._fold.type == FOLDER_TYPE_DEFAULT && this._fold.checked) {
			return '<folder id="' + this._fold.id + '"><full_name>' + GetCData(this._fold.fullName) + '</full_name></folder>';
		}
		return '';
	},
	
	editName: function () {
		this._linkNameObj.className = 'wm_hide';
		this._editNameObj.value = HtmlDecode(this._fold.name);
		this._editNameObj.className = 'wm_folder_name wm_input';
		this._editNameObj.focus();
	},
	
	saveName: function () {
		var value = this._editNameObj.value;
		if (Trim(value).length != 0 && this._fold.name != value) {
			if (!Validator.isCorrectFileName(value)) {
				Dialog.alert(Lang.WarningCantUpdateFolder);
			}
			else {
				this._fold.name = HtmlEncodeWithQuotes(value);
				this._setOptName();
				this._linkNameObj.innerHTML = '<a href="#" onclick="return false;">' + this._fold.name + '</a>';
				this._parent.hasChanges = true;
			}
		}
		this._editNameObj.className = 'wm_hide';
		this._editNameObj.blur();
		this._linkNameObj.className = 'wm_folder_name';
	},
	
	changeHide: function () {
		this._fold.hide = !this._hideCheckbox.checked;
		this._parent.hasChanges = true;
	},
	
	getProperties: function () {
		return this._fold;
	},
	
	setProperties: function (fold) {
		this._fold = fold;
		this._init();
		this.setNextFoldLine(this._fold.nextIndex);
	},
	
	getInXml: function () {
		var attrs = ' id="' + this._fold.id + '"';
		attrs += ' sync_type="' + this._fold.syncType + '"';
		attrs += ' type="' + this._fold.type + '"';
		attrs += (this._fold.hide) ? ' hide="1"' : ' hide="0"';
		attrs += ' fld_order="' + this._fold.fldOrder + '"';
		var name = this._fold.name;
		var nodes = '<name>' + GetCData(name, false, true) + '</name>';
		nodes += '<full_name>' + GetCData(this._fold.fullName, false, true) + '</full_name>';
		return '<folder' + attrs + '>' + nodes + '</folder>';
	},

//private
	_fill: function (tr, fold) {
		var colIndex = 0;
		var td = tr.insertCell(colIndex++);
		this._checkInp = CreateChild(td, 'input', [['type', 'checkbox'], ['class', 'wm_checkbox']]);

		this._container = tr.insertCell(colIndex++);
		this._container.className = 'wm_settings_mf_folder';
		var desc = FolderDescriptions[fold.type];

		this._folderDiv = CreateChild(this._container, 'div', [['class', 'wm_folder']]);
		var secondDiv = CreateChild(this._folderDiv, 'div');

		this._spanIcon = CreateChild(secondDiv, 'span', [['class', 'wm_folder_img', 'style', 'background-position: -' + desc.x*X_ICON_SHIFT + 'px -' + desc.y*Y_ICON_SHIFT + 'px;']]);
		this._spanIcon.innerHTML = '&nbsp;';

		this._viewNameObj = CreateChild(secondDiv, 'span', [['class', 'wm_folder_name']]);
		this._viewNameObj.innerHTML = fold.name;
		this._linkNameObj = CreateChild(secondDiv, 'span', [['class', 'wm_hide']]);
		var a = CreateChild(this._linkNameObj, 'a', [['href', '#'], ['onclick', 'return false;']]);
		a.innerHTML = HtmlEncodeWithQuotes(fold.name);
		this._eInfoNameObj = CreateChild(secondDiv, 'span', [['class', 'wm_secondary_info'], ['style', 'line-height: 12px;']]);
		this._editNameObj = CreateChild(secondDiv, 'input', [['type', 'text'], ['class', 'wm_hide'], ['style', 'width: 200px;'], ['maxlength', '30']]);

		this._countTd = tr.insertCell(colIndex++);
		this._countTd.innerHTML = fold.msgCount;

		td = tr.insertCell(colIndex++);
		this._hideCheckbox = CreateChild(td, 'input', [['type', 'checkbox'], ['class', 'wm_checkbox']]);
		this._hideCheckbox.checked = !fold.hide;
		var oAccount = WebMail.Accounts.getEditableAccount();
		if (oAccount.bDisableManageSubscribe) {
			this._hideCheckbox.disabled = true;
		}

		td = tr.insertCell(colIndex++);
		td.className = 'wm_settings_mf_up_down';
		if (window.UseDb) {
			this._eUpArrow = CreateChild(td, 'span', [['class', 'wm_settings_mf_up_inactive']]);
			this._eUpArrow.innerHTML = '&nbsp;';
			this._eDownArrow = CreateChild(td, 'span', [['class', 'wm_settings_mf_down_inactive']]);
			this._eDownArrow.innerHTML = '&nbsp;';
		}
	},

	_setOptName: function ()
	{
		this._opt.innerHTML = '&nbsp;' + this._fold.strIndent + this._fold.name;
	},

	_fillInfo: function () {
		var sAlias;
		if (this._fold.type === FOLDER_TYPE_DEFAULT) {
			this._eInfoNameObj.innerHTML = '';
		}
		else {
			sAlias = CFolder.prototype.getNameByType(this._fold.type, this._fold.name);
			this._eInfoNameObj.innerHTML = Lang.FolderUsedAs + ' ' + sAlias;
		}
	},

	_init: function () {
		this._fillInfo();
		var obj = this;
		if (this._fold.type == FOLDER_TYPE_DEFAULT) {
			if (this._protocol != POP3_PROTOCOL && (this._fold.hasChilds || this._fold.msgCount > 0)) {
				this._checkInp.checked = false;
				this._checkInp.disabled = true;
				this._checkInp.onchange = function () {};
				this.checkDisable = 1;
			}
			else {
				this._checkInp.checked = this._fold.checked;
				this._checkInp.disabled = false;
				this._checkInp.onchange = function () {
					obj.setChecked(this.checked);
				};
			}
			this._checkInp.className = 'wm_checkbox';
		}
		else {
			this._checkInp.className = 'wm_hide';
		}

		if (window.RTL) {
			this._folderDiv.style.marginRight = this._fold.intIndent + 'px';
		}
		else {
			this._folderDiv.style.marginLeft = this._fold.intIndent + 'px';
		}
		this._setOptName();
		if (this._fold.type == FOLDER_TYPE_DEFAULT && this._fold.noselect === false) {
			this._viewNameObj.innerHTML = '';
			this._viewNameObj.className = 'wm_hide';
			this._linkNameObj.innerHTML = '<a href="#" onclick="return false;">' + this._fold.name + '</a>';
			this._linkNameObj.onclick = function () {
				obj.editName();
				return false;
			};
			this._linkNameObj.className = 'wm_folder_name';
		}
		else {
			this._viewNameObj.innerHTML = this._fold.name;
			this._viewNameObj.className = 'wm_folder_name';
			this._linkNameObj.innerHTML = '';
			this._linkNameObj.onclick = function () {
				return false;
			};
			this._linkNameObj.className = 'wm_hide';
		}
		var desc = FolderDescriptions[this._fold.type];
		this._spanIcon.style.backgroundPosition = '-' + desc.x * X_ICON_SHIFT + 'px -' + desc.y * Y_ICON_SHIFT + 'px';
		this._editNameObj.onkeyup = function (ev) {
			if (isEnter(ev)) {
				obj.saveName();
			}
		};
		this._editNameObj.onblur = function () {
			obj.saveName();
		};

		this._countTd.innerHTML = this._fold.msgCount;

		this._hideCheckbox.checked = !this._fold.hide;
		this._hideCheckbox.onclick = function () {
			obj.changeHide();
		};

		if (this._fold.prevIndex == -1) {
			this._eUpArrow.className = 'wm_settings_mf_up_inactive';
			this._eUpArrow.onclick = function () {};
		}
		else {
			this._eUpArrow.className = 'wm_settings_mf_up wm_control_img';
			this._eUpArrow.onclick = function () {
				obj.changeWithPrev();
			};
		}
	}
};

function CSpecialFoldersPane(htmlParent, objParent)
{
	this._htmlParent = htmlParent;
	this._objParent = objParent;
	this._mainContainer = null;
	
	this._spFoldersDesc = [];

	this._sentItemsSelect = null;
	this._draftsSelect = null;
	this._trashSelect = null;
	this._spamCont = null;
	this._spamSelect = null;

	this._builded = false;
}

CSpecialFoldersPane.prototype = {
// public
	show: function (folders)
	{
		if (!window.UseDb) {
			return;
		}
		if (!this._builded) {
			this._build();
		}
		var account = WebMail.Accounts.getEditableAccount();
		if (account.allowSpamFolder) {
			this._spamCont.className = '';
		}
		else {
			this._spamCont.className = 'wm_hide';
		}
		this._mainContainer.className = '';
		this._spFoldersDesc = [];
		this._spFoldersDesc.push({type: FOLDER_TYPE_SENT, Select: this._sentItemsSelect});
		this._spFoldersDesc.push({type: FOLDER_TYPE_DRAFTS, Select: this._draftsSelect});
		this._spFoldersDesc.push({type: FOLDER_TYPE_TRASH, Select: this._trashSelect});
		this._spFoldersDesc.push({type: FOLDER_TYPE_SPAM, Select: this._spamSelect});
		this._fill(folders);
	},

	hide: function ()
	{
		if (!window.UseDb) {
			return;
		}
		if (!this._builded) return;
		this._mainContainer.className = 'wm_hide';
		this._objParent.resize();
	},

// private
	_apply: function ()
	{
		var folders = [];
		for (var spFldIndex = 0; spFldIndex < this._spFoldersDesc.length; spFldIndex++) {
			var desc = this._spFoldersDesc[spFldIndex];
			var foldFromSel = this._getFolderFromSelect(desc.Select);
			if (foldFromSel.id != -1 && foldFromSel.fullName != '') {
				folders[desc.type] = this._getFolderFromSelect(desc.Select);
			}
		}
		this._objParent.applySpecialFolders(folders);
		this.hide();
	},

	_repairAllSelects: function (select)
	{
		for (var spFldIndex = 0; spFldIndex < this._spFoldersDesc.length; spFldIndex++) {
			var desc = this._spFoldersDesc[spFldIndex];
			if (select != desc.Select && select.value == desc.Select.value) {
				desc.Select.value = '-1' + STR_SEPARATOR;
			}
		}
		select.prev_value = select.value;
	},

	_getFolderFromSelect: function (select)
	{
		var values = select.value.split(STR_SEPARATOR);
		if (values.length < 2) return null;
		return {id: values[0], fullName: values[1]};
	},

	_fill: function (folders)
	{
		this._cleanFolderSelects();
		var notUsedFold = {id: -1, fullName: '', name: Lang.FolderNoUsageAssigned, type: FOLDER_TYPE_DEFAULT, level: 0, strIndent: ''};
		for (var spFldIndex = 0; spFldIndex < this._spFoldersDesc.length; spFldIndex++) {
			var desc = this._spFoldersDesc[spFldIndex];
			this._addFolderOption(desc.Select, notUsedFold, desc.type);
		}
		for (var i = 0; i < folders.length; i++) {
			var fold = folders[i];
			for (spFldIndex = 0; spFldIndex < this._spFoldersDesc.length; spFldIndex++) {
				desc = this._spFoldersDesc[spFldIndex];
				this._addFolderOption(desc.Select, fold, desc.type);
			}
		}
		for (spFldIndex = 0; spFldIndex < this._spFoldersDesc.length; spFldIndex++) {
			desc = this._spFoldersDesc[spFldIndex];
			this._prepareSelect(desc.Select);
		}
	},

	_prepareSelect: function (select)
	{
		var obj = this;
		select.onchange = function () {
			obj._repairAllSelects(select);
		};
	},

	_cleanFolderSelects: function ()
	{
		for (var spFldIndex = 0; spFldIndex < this._spFoldersDesc.length; spFldIndex++) {
			var desc = this._spFoldersDesc[spFldIndex];
			CleanNode(desc.Select);
		}
	},

	_addFolderOption: function (select, fold, selectType)
	{
		var opt = CreateChild(select, 'option', [['value', fold.id + STR_SEPARATOR + fold.fullName]]);
		opt.innerHTML = '&nbsp;' + fold.strIndent + fold.name;
		if (selectType == fold.type) {
			opt.selected = true;
		}
		if (fold.type === FOLDER_TYPE_INBOX || fold.noselect === true) {
			opt.disabled = true;
		}
	},

	_addFolderSelectRow: function (tr, langField)
	{
		var td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = Lang[langField];
		WebMail.langChanger.register('innerHTML', td, langField, '');
		td = tr.insertCell(1);
		var sel = CreateChild(td, 'select');
		return sel;
	},

	_build: function ()
	{
		var div = CreateChild(this._htmlParent, 'div', [['class', 'wm_hide']]);
		this._mainContainer = div;

		var tbl = CreateChild(div, 'table');
		tbl.className = 'wm_settings_new_folder';
		var rowIndex = 0;
		var tr = tbl.insertRow(rowIndex++);
		var td = tr.insertCell(0);
		td.colSpan = '2';
		td.className = 'wm_secondary_info';
		td.innerHTML = Lang.InfoPreDefinedFolders;
		WebMail.langChanger.register('innerHTML', td, 'InfoPreDefinedFolders', '');

		this._sentItemsSelect = this._addFolderSelectRow(tbl.insertRow(rowIndex++), 'FolderSentItems');
		this._draftsSelect = this._addFolderSelectRow(tbl.insertRow(rowIndex++), 'FolderDrafts');
		this._trashSelect = this._addFolderSelectRow(tbl.insertRow(rowIndex++), 'FolderTrash');
		this._spamCont = tbl.insertRow(rowIndex++);
		this._spamSelect = this._addFolderSelectRow(this._spamCont, 'FolderSpam');
		
		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.colSpan = '2';
		td.className = 'wm_settings_buttons_cell';
//		td.style.margin = '0';
//		td.style.textAlign = 'right';
//		td.style.width = 'auto';
		var obj = this;
		ButtonsBuilder.addStandard(td, 'Apply', function () {obj._apply();}, true);
		ButtonsBuilder.addStandard(td, 'Cancel', function () {obj.hide();}, false);

		this._builded = true;
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
