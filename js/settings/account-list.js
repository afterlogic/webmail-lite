/*
 * Functions:
 *  CreateAccountClickFunc(id)
 * Classes:
 *  CAccountListPane(oParent, manageFolders)
 */

function CreateAccountClickFunc(id)
{
	return function () {
		SetHistoryHandler(
			{
				ScreenId: SCREEN_USER_SETTINGS,
				iEditableAcctId: id,
				iEntity: PART_ACCOUNT_PROPERTIES,
				bNewMode: false
			}
		);
		return false;
	};
}

function CAccountListPane(oParent, manageFolders)
{
	this._oParent = oParent;
	
	this._manageFoldersObj = manageFolders;
	this._mainContainer = null;
	this.shown = false;
}

CAccountListPane.prototype = {
	show: function()
	{
		if (!this.shown) {
			this.shown = true;
			this._mainContainer.className = (window.UseDb) ? 'wm_settings_list' : 'wm_hide';
		}
		this.fill();
	},
	
	hide: function()
	{
		this.shown = false;
		this._mainContainer.className = 'wm_hide';
	},
	
	fill: function ()
	{
		if (this.shown) {
			CleanNode(this._mainContainer);
			var tbl = CreateChild(this._mainContainer, 'table');
			var arrAccounts = WebMail.Accounts.items;
			var rowIndex = 0;
			for (var i = 0; i < arrAccounts.length; i++) {
				var account = arrAccounts[i];
				var tr = tbl.insertRow(rowIndex++);
				var td = tr.insertCell(0);
				if (account.id == WebMail.Accounts.editableId) {
					tr.className = 'wm_settings_list_select';
					td.innerHTML = '<b>' + account.email + '</b>';
					this._manageFoldersObj.UpdateProtocol(account.mailProtocol);
				} else {
					td.className = 'wm_control';
					td.innerHTML = account.email;
					td.onclick = CreateAccountClickFunc(account.id);
				}
				if (!WebMail.Settings.allowChangeAccountSettings) continue;
				td = tr.insertCell(1);
				td.style.width = '10px';
				if (!account.isInternal) {
					var a = CreateChild(td, 'a', [['href', '#']]);
					a.innerHTML = Lang.Delete;
					a.onclick = (function (iId, sEmail) {
						return function () {
							Dialog.confirm(Lang.ConfirmDeleteAccount, function () {
								WebMail.DataSource.request({action: 'delete', request: 'account', 'id_acct': iId}, '');
							}, function () {}, Lang.OK, sEmail);
							return false;
						};
					})(account.id, account.email);
				}
			}
			if (this._oParent) {
				this._oParent.resizeBody();
			}
		}
	},
	
	build: function(container)
	{
		this._mainContainer = CreateChild(container, 'div');
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}