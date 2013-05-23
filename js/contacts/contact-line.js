/*
 * Classes:
 *  CContactsTableController(contactsScr)
 *  CContactLine(contact, tr)
 */

function CContactsTableController(contactsScr)
{
	this.type = TYPE_CONTACT;
	this.resizeHandler = 'ResizeContactsTab';
	this.listContanerClass = 'wm_contact_list_div';
	this.sCurrContactId = '';
	this.CurrIsGroup = false;
	
	this.CreateLine = function (obj, tr)
	{
		tr.id = obj.sContactId + STR_SEPARATOR + obj.isGroup + STR_SEPARATOR + obj.clearName + STR_SEPARATOR + obj.clearEmail;
		tr.email = (!obj.isGroup && obj.name.length > 0) ? '"' + obj.name + '" ' + '&lt;' + obj.clearEmail + '&gt;' : obj.clearEmail;
		tr.global = obj.isGlobal;
		return new CContactLine(obj, tr);
	};

	this.ClickLine = function (sContactLineId, sGlobal)
	{
		var params = sContactLineId.split(STR_SEPARATOR);
		if (params.length == 4)
		{
			this.sCurrContactId = params[0];
			if (params[1] == '0') {
				this.CurrIsGroup = false;
				SetHistoryHandler(
					{
						ScreenId: SCREEN_CONTACTS,
						Entity: PART_VIEW_CONTACT,
						sContactId: params[0],
						isGlobalContact: sGlobal
					}
				);
			}
			else {
				this.CurrIsGroup = true;
				SetHistoryHandler(
					{
						ScreenId: SCREEN_CONTACTS,
						Entity: PART_VIEW_GROUP,
						sGroupId: params[0]
					}
				);
			}
		}
	};
	
	this.DblClickLine = function (tr)
	{
		MailAllHandler(HtmlDecode(tr.email), '', '');
	};
	
	this.del = function ()
	{
		contactsScr.deleteSelected();
	};
	
	this.SetEventsHandlers = function (obj, tr)
	{
		var objController, clickElem, clickTagName, tdElem;
		objController = this;
		tr.onclick = function(e) {
			e = e ? e : window.event;
			clickElem = (Browser.mozilla) ? e.target : e.srcElement;
			clickTagName = (clickElem) ? clickElem.tagName : 'NOTHING';
			if (clickTagName == 'INPUT' || e.ctrlKey) {
				obj.oSelection.CheckCtrlLine(this.id);
			} else if (e.shiftKey) {
				obj.oSelection.CheckShiftLine(this.id);
			}
			else {
				tdElem = clickElem;
				while (tdElem && tdElem.tagName != 'TD') {
					tdElem = tdElem.parentNode;
				}
				if (tdElem.name != 'not_view') {
					objController.ClickLine(this.id, this.global);
					obj.oSelection.CheckLine(this.id);
				}
			}
		};
		tr.ondblclick = function (e) {
			objController.DblClickLine(this);
			return false;//?
		};
	};
}

function CContactLine(oContact, eTr)
{
	this.sContactId = oContact.sContactId;
	this.isGroup = oContact.isGroup;
	
	DontSelectContent(eTr);
	
	this.Node = eTr;
	this.id = eTr.id;
	this._className = 'wm_inbox_read_item';
	this.Checked = false;
	this.ApplyClassName();
	
	this.fCheck = new CCheckBoxCell();
	
	var content = oContact.isGroup ? 'wm_inbox_lines_group' : '';
	this.fIsGroup = new CImageCell('', '', content);

	this.fName = new CTextCell(oContact.name);
	this.fEmail = new CTextCell(oContact.email);
}

CContactLine.prototype = 
{
    view: function (bViewed)
    {
		this._viewed = bViewed;
		this.ApplyClassName();
    },
    
	Check: function()
	{
		this.Checked = true;
		this.fCheck.Node.checked = true;
		this.ApplyClassName();
	},

	Uncheck: function()
	{
		this.Checked = false;
		this.fCheck.Node.checked = false;
		this.ApplyClassName();
	},
	
	ApplyClassName: function ()
	{
		if (this._viewed) {
			this.Node.className = this._className + '_view';
		}
		else if (this.Checked) {
			this.Node.className = this._className + '_select';
		}
		else {
			this.Node.className = this._className;
		}
	},
	
	SetContainer: function (sField, eContainer)
	{
		if (sField == 'fCheck' || sField == 'fIsGroup') {
			eContainer.name = 'not_view';
		}
		this[sField].SetContainer(eContainer);
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
