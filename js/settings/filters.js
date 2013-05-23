/*
 * Classes:
 *  CFiltersPane(oParent)
 *  CFilterEditor(filter, parentDiv, filterFields, filterConditions, filterActions, parentObj)
 *  CPopupChooser(values)
 */

function CFiltersPane(oParent)
{
	this._oParent = oParent;

	this.shown = false;
	this.isSaveFilters = false;

	this._foldersIdAcct = -1;

	this._mainTbl = null;
	this._filtersParentDiv = null;
	this._noFiltersDiv = null;

	this._filterFields = [	{id: 0, value: Lang.From, shift: ''},
							{id: 1, value: Lang.To, shift: ''},
							{id: 2, value: Lang.Subject, shift: ''}];
	this._filterConditions = [	{id: 0, value: Lang.FiltersCondContainSubstr, shift: ''},
								{id: 1, value: Lang.FiltersCondEqualTo, shift: ''},
								{id: 2, value: Lang.FiltersCondNotContainSubstr, shift: ''}];
	this._filterActions = [	{id: 1, value: Lang.FiltersActionDelete, shift: ''},
							{id: 3, value: Lang.FiltersActionMove, shift: ''}];
	this._filterFolders = [];

	this.FieldChooser = null;
	this.ConditionChooser = null;
	this.ActionChooser = null;
	this.FolderChooser = null;

	this._fltEditors = [];
	this.InboxId = -1;
	this.InboxName = '';
}

CFiltersPane.prototype = {
	show: function()
	{
		if (!this.shown) {
			this.shown = true;
			this._mainTbl.className = (window.UseDb || window.UseLdapSettings)
				? 'wm_email_settings_edit_zone' : 'wm_hide';
		}
		if (this._foldersIdAcct != WebMail.Accounts.editableId) {
			this._foldersIdAcct = WebMail.Accounts.editableId;
			GetHandler(TYPE_FOLDER_LIST, {idAcct: WebMail.Accounts.editableId, sync: GET_FOLDERS_NOT_CHANGE_ACCT}, [], '');
		}
		this.fill();
	},

	hide: function()
	{
		this.shown = false;
		this._mainTbl.className = 'wm_hide';
		this.hideChoosers();
	},

	hideChoosers: function ()
	{
		this.FieldChooser.hide();
		this.ConditionChooser.hide();
		this.ActionChooser.hide();
		this.FolderChooser.hide();
	},

	SetFilters: function (filters)
	{
		if (this.isSaveFilters) {
			WebMail.showReport(Lang.ReportFiltersUpdatedSuccessfuly);
			this.isSaveFilters = false;
		}
		this.FillFilters();
	},

	fill: function ()
	{
		this.FillFilters();
		if (this._oParent) {
			this._oParent.resizeBody();
		}
	},

	FillFolders: function (folders)
	{
		this._filterFolders = [];
		var levelShift = ['', '&nbsp;&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;',
			'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'];
		var folderIndex = 0;
		for (var i = 0; i < folders.length; i++) {
			var folder = folders[i];
			if (folder.noselect === true) continue;

			var folderName = CFolder.prototype.getNameByType(folder.type, folder.name);
			var level = (folder.level > levelShift.length) ? levelShift.length : folder.level;
			this._filterFolders[folderIndex++] = {id: folder.id, value: folderName, shift: levelShift[level]};
			if (folder.type == FOLDER_TYPE_INBOX) {
				this.InboxId = folder.id;
				this.InboxName = folderName;
			}
		}
	},

	FillFilters: function ()
	{
		if (!this.shown) return;

        var filters = WebMail.Accounts.getEditableFilters();
		if (filters == null) {
			this._noFiltersDiv.innerHTML = Lang.FiltersLoading;
			this._noFiltersDiv.className = 'wm_filters_line';
			GetHandler(TYPE_FILTERS, {idAcct: WebMail.Accounts.editableId}, [], '');
			return;
		}
		CleanNode(this._filtersParentDiv);
		this._fltEditors = [];
		for (var i = 0; i < filters.length; i++) {
			var filter = filters[i];
			for(var j = 0; j < this._filterFolders.length; j++) {
				if (this._filterFolders[j].id == filter.idFolder) {
					filter.folderName = this._filterFolders[j].value;
				}
			}
			this.addFilter(filter);
		}
		this.CheckNoFilters();
	},

	CheckNoFilters: function ()
	{
		var hasFilters = false;
		for (var i = 0; i < this._fltEditors.length; i++) {
			if (this._fltEditors[i].status != FILTER_STATUS_REMOVED) {
				hasFilters = true;
				break;
			}
		}
		if (hasFilters) {
			this._noFiltersDiv.className = 'wm_hide';
		}
		else {
			this._noFiltersDiv.innerHTML = Lang.FiltersNo;
			this._noFiltersDiv.className = 'wm_filters_line';
		}
	},

	addFilter: function (filter, resize)
	{
		var obj = this;
		function CreateActionChooserShowFunc(obj, fltEditor)
		{
			return function() {
				obj.ActionChooser.show(this, fltEditor);
			};
		}
		var fltEditor = new CFilterEditor(filter, this._filtersParentDiv, this._filterFields, this._filterConditions, this._filterActions, this);
		fltEditor.FieldLink.onclick = function () {obj.FieldChooser.show(this);};
		fltEditor.ConditionLink.onclick = function () {obj.ConditionChooser.show(this);};
		fltEditor.ActionLink.onclick = CreateActionChooserShowFunc(obj, fltEditor);
		fltEditor.FolderLink.onclick = function () {obj.FolderChooser.show(this, undefined, obj._filterFolders);};
		this._fltEditors.push(fltEditor);
		if (resize && this._oParent) {
			this._oParent.resizeBody();
		}
		this.CheckNoFilters();
	},

	clickBody: function (ev)
	{
		ev = ev ? ev : window.event;
		var elem = (Browser.mozilla) ? ev.target : ev.srcElement;
		while (elem && elem.tagName != 'DIV' && elem.parentNode) {
			elem = elem.parentNode;
		}
		if (elem && elem.className != 'wm_choices_popup' && elem.parentNode) {
			elem = elem.parentNode;
		}
		if (elem && elem.className != 'wm_choices_popup') {
			this.hideChoosers();
		}
	},

	saveChanges: function ()
	{
		var xml = '';
		for (var i = 0; i < this._fltEditors.length; i++) {
			var filter = this._fltEditors[i].GetNewFilter();
			if (filter.status != FILTER_STATUS_REMOVED && Validator.isEmpty(filter.value)) {
				Dialog.alert(Lang.WarningEmptyFilter);
				this._fltEditors[i].StringInput.focus();
				return;
			}
			xml += filter.getInXml();
		}
		xml = '<filters id_acct="' + WebMail.Accounts.editableId + '">' + xml + '</filters>';
		RequestHandler('update', 'filters', xml);
		this.isSaveFilters = true;
	},

	build: function(container)
	{
		var obj = this;
		this._mainTbl = CreateChild(container, 'div', [['class', 'wm_hide']]);

		var filtersContDiv = CreateChild(this._mainTbl, 'div', [['class', 'wm_filters_cont']]);
		this._filtersParentDiv = CreateChild(filtersContDiv, 'div', []);
		this._noFiltersDiv = CreateChild(filtersContDiv, 'div', [['class', 'wm_filters_line']]);
		this._noFiltersDiv.innerHTML = Lang.FiltersLoading;

		var tbl = CreateChild(this._mainTbl, 'table');
		tbl.className = 'wm_settings_buttons';
		var tr = tbl.insertRow(0);
		var td = tr.insertCell(0);
		td.style.textAlign = 'left';
		var moreFiltersA = CreateChild(td, 'a', [['href', 'javascript:void(0)'], ['class', 'wm_filters_add_link']]);
		moreFiltersA.innerHTML = Lang.FiltersAdd;
		WebMail.langChanger.register('innerHTML', moreFiltersA, 'FiltersAdd', '');
		moreFiltersA.onclick = function () {
			obj.addFilter(new CFilterProperties(obj.InboxId, obj.InboxName), true);
		};

		td = tr.insertCell(1);
		var inp = CreateChild(td, 'input', [['class', 'wm_button'], ['type', 'button'], ['value', Lang.Save]]);
		WebMail.langChanger.register('value', inp, 'Save', '');
		inp.onclick = function () {
			obj.saveChanges();
		};

		this.FieldChooser = new CPopupChooser(this._filterFields);
		this.ConditionChooser = new CPopupChooser(this._filterConditions);
		this.ActionChooser = new CPopupChooser(this._filterActions);
		this.FolderChooser = new CPopupChooser(this._filterFolders);
	}//build
};

function CFilterEditor(filter, parentDiv, filterFields, filterConditions, filterActions, parentObj)
{
	this._filter = filter;
	this._parentDiv = parentDiv;
	this.status = filter.status;

	this.appliedCheck = null;
	this.FieldLink = null;
	this.ConditionLink = null;
	this.ActionLink = null;
	this.RemoveLink = null;
	this.FolderPart = null;
	this.FolderLink = null;
	this.StringInput = null;

	this._filterDiv = null;

	this._build(filterFields, filterConditions, filterActions, parentObj);
}

CFilterEditor.prototype = {
	ActionChangedHandler: function ()
	{
		if (this.ActionLink.value == 3) {
			this.FolderPart.className = '';
		}
		else {
			this.FolderPart.className = 'wm_hide';
		}
	},

	GetNewFilter: function ()
	{
		var applied = this.appliedCheck.checked;
		var field = this.FieldLink.value;
		var condition = this.ConditionLink.value;
		var action = this.ActionLink.value;
		var idFolder = this.FolderLink.value;
		var folderName = this.FolderLink.innerHTML;
		var string = this.StringInput.value;

		var filter = this._filter;
		if (this.status == FILTER_STATUS_UNCHANGED && (applied != filter.applied || field != filter.field
			|| condition != filter.condition || action != filter.action
			|| idFolder != filter.idFolder || string != filter.value)) {
			this.status = FILTER_STATUS_UPDATED;
		}

		filter.field = field;
		filter.condition = condition;
		filter.action = action;
		filter.idFolder = idFolder;
		filter.folderName = folderName;
		filter.value = string;
		filter.status = this.status;
		filter.applied = applied;
		return filter;
	},

	_getValue: function (values, id)
	{
		for(var i = 0; i < values.length; i++) {
			if (values[i].id == id) return values[i].value;
		}
		return '';
	},

	_build: function (filterFields, filterConditions, filterActions, parentObj)
	{
		var filter = this._filter;
		var filterId = (filter.id == -1) ? Math.random() : filter.id;

		var checked = filter.applied ? 'checked="checked"' : '';
		var filterPhrase = '<input id="applied_check_' + filterId + '" type="checkbox" class="wm_checkbox" ' + checked + '/> ' + Lang.FilterPhrase;

		var fieldLink = '<a id="field_link_' + filterId + '" href="javascript:void(0)" class="wm_choices_menu_link">' + this._getValue(filterFields, filter.field) + '</a>';
		filterPhrase = filterPhrase.replace(/%field/g, fieldLink);

		var conditionLink = '<a id="condition_link_' + filterId + '" href="javascript:void(0)" class="wm_choices_menu_link">' + this._getValue(filterConditions, filter.condition) + '</a>';
		filterPhrase = filterPhrase.replace(/%condition/g, conditionLink);

		var inp = '<input id="string_input_' + filterId + '" type="text" value="' + filter.value.replace(/"/g, '&quot;') + '" class="wm_input" />';
		filterPhrase = filterPhrase.replace(/%string/g, inp);

		var actionLink = '<a id="action_link_' + filterId + '" href="javascript:void(0)" class="wm_choices_menu_link">' + this._getValue(filterActions, filter.action) + '</a>';
		filterPhrase = filterPhrase.replace(/%action/g, actionLink);

		var folderLink = '<a id="folder_link_' + filterId + '" href="javascript:void(0)" class="wm_choices_menu_link">' + filter.folderName + '</a>';
		filterPhrase += '<span id="folder_part_' + filterId + '" class="wm_hide"> ' + Lang.FiltersActionToFolder.replace(/%folder/g, folderLink) + '</span>';

		filterPhrase += ' <a id="remove_link_' + filterId + '" href="javascript:void(0)" class="wm_hide">' + Lang.Remove + '</a>';

		this._filterDiv = CreateChild(this._parentDiv, 'div', [['class', 'wm_filters_line']]);
		this._filterDiv.innerHTML = filterPhrase;

		this.appliedCheck = document.getElementById('applied_check_' + filterId);

		this.FieldLink = document.getElementById('field_link_' + filterId);
		this.FieldLink.value = filter.field;

		this.ConditionLink = document.getElementById('condition_link_' + filterId);
		this.ConditionLink.value = filter.condition;

		this.StringInput = document.getElementById('string_input_' + filterId);

		this.ActionLink = document.getElementById('action_link_' + filterId);
		this.ActionLink.value = filter.action;

		this.FolderPart = document.getElementById('folder_part_' + filterId);
		this.ActionChangedHandler();

		this.FolderLink = document.getElementById('folder_link_' + filterId);
		this.FolderLink.value = filter.idFolder;

		this.RemoveLink = document.getElementById('remove_link_' + filterId);
		var obj = this;
		this.RemoveLink.onclick = function () {
			obj.status = FILTER_STATUS_REMOVED;
			obj._filterDiv.className = 'wm_hide';
			parentObj.CheckNoFilters();
		};
		this._filterDiv.onmouseover = function () {
			obj.RemoveLink.className = '';
		};
		this._filterDiv.onmouseout = function () {
			obj.RemoveLink.className = 'wm_hide';
		};
	}
};

function CPopupChooser(values)
{
	this._values = values;
	this._choices = [];
	this._choosenId = null;
	this._link = null;
	this._popupDiv = CreateChild(document.body, 'div', [['class', 'wm_hide']]);
	this._builded = false;
	this._showStatus = 0;
	this._fltEditor = null;
}

CPopupChooser.prototype = {
	show: function (link, fltEditor, values)
	{
		this._link = link;
		this._link.className = 'wm_choices_menu_link';
		if (fltEditor != undefined) {
			this._fltEditor = fltEditor;
		}
		if (values != undefined) {
			this._values = values;
			this._builded = false;
		}
		if (!this._builded) {
			this._choosenId = link.value;
			this._build();
		}
		else {
			this._changeActiveChoice(link.value);
		}
		this._popupDiv.className = 'wm_choices_popup';
		var activeChoice = this._choices[this._choosenId];
		var linkBounds = GetBounds(this._link);
		var activeBounds = GetBounds(activeChoice);
		var popupBounds = GetBounds(this._popupDiv);
		this._popupDiv.style.left = linkBounds.Left + 'px';
		this._popupDiv.style.top = popupBounds.Top - activeBounds.Top + linkBounds.Top + 'px';
		this._showStatus = 1;
	},

	_changeActiveChoice: function (id)
	{
		this._choices[this._choosenId].className = 'wm_choice';
		this._choosenId = id;
		this._choices[this._choosenId].className = 'wm_active_choice';
	},

	hide: function (id)
	{
		if (!this._builded) return;
		if (id != undefined) {
			for(var i=0; i<this._values.length; i++) {
				if (this._values[i].id == id) {
					this._link.value = this._values[i].id;
					this._link.innerHTML = this._values[i].value;
				}
			}
			if (this._fltEditor != null) {
				this._fltEditor.ActionChangedHandler();
			}
		}
		if (this._showStatus == 2) {
			this._popupDiv.className = 'wm_hide';
			this._showStatus = 1;
		}
		else {
			this._showStatus = 2;
		}
	},

	_build: function ()
	{
		CleanNode(this._popupDiv);
		for (var i = 0; i < this._values.length; i++) {
			var value = this._values[i];
			var valueDiv = CreateChild(this._popupDiv, 'div');
			var valueLink = CreateChild(valueDiv, 'a', [['id', value.id]]);
			if (value.id == this._choosenId) {
				valueLink.className = 'wm_active_choice';
			}
			else {
				valueLink.className = 'wm_choice';
			}
			valueLink.innerHTML = value.shift + value.value;
			valueLink.href = 'javascript:void(0)';
			var obj = this;
			valueLink.onclick = function () {obj.hide(this.id);};
			this._choices[value.id] = valueLink;
		}
		this._builded = true;
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}