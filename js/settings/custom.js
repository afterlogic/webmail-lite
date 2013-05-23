/*
 * Classes:
 *  CCustomForwardingData()
 *  CCustomPane(oParent)
 */

var CustomForwardingTypes = {
	Notifiations: 1,
	Forward: 2,
	Nothing: 3
}

function CCustomForwardingData() {
	this.type = TYPE_CUSTOM;
	this.value = CustomForwardingTypes.Notifiations;
	this.email = '';
}

CCustomForwardingData.prototype = {
	getStringDataKeys: function()
	{
		return '';
	},

	getInXml: function ()
	{
		var attrs = ' value="' + this.value + '"';
		var nodes = '<email>' + GetCData(this.email) + '</email>';
		var xml = '<notification_settings' + attrs + '>' + nodes + '</notification_settings>';
		return xml;
	},

	getFromXml: function (rootElement)
	{
		this.value = XmlHelper.getIntAttributeByName(rootElement, 'value', this.value);

		var emailNode = XmlHelper.getFirstChildNodeByName(rootElement, 'email');
		this.email = HtmlDecode(XmlHelper.getFirstChildValue(emailNode, this.email));

	}
};

function CCustomPane(oParent)
{
	this._oParent = oParent;
	
	this._$ForwardEditZone = null;
	this.$NotificationsCheck = null;
	this.$NotificationsLabel = null;
	this.$ForwardCheck = null;
	this.$ForwardLabel = null;
	this.$NothingCheck = null;
		
	this.bShown = false;
}

CCustomPane.prototype = {
	show: function()
	{
		this._$ForwardEditZone.removeClass('wm_hide');
		var oAccount = WebMail.Accounts.getEditableAccount();
		this.$NotificationsLabel.html(Lang.ForwardingNotificationsTo.replace(/%email/g, oAccount.email));
		this.$ForwardLabel.html(Lang.ForwardingForwardTo.replace(/%email/g, oAccount.email));
		RequestHandler('get', 'notification_settings', '<param name="email">' + GetCData(oAccount.email) + '</param>', false);
		
	},
	
	clickBody: function (ev) {},
	
	hide: function()
	{
		this.bShown = false;
		this._$ForwardEditZone.addClass('wm_hide');
	},
	
	fill: function (oCustomForwarding)
	{
		switch (oCustomForwarding.value) {
			case CustomForwardingTypes.Notifiations:
				this.$NotificationsCheck.attr('checked', 'checked');
				break;
			case CustomForwardingTypes.Forward:
				this.$ForwardCheck.attr('checked', 'checked');
				break;
			case CustomForwardingTypes.Nothing:
				this.$NothingCheck.attr('checked', 'checked');
				break;
		}

		if (this._oParent) {
			this._oParent.resizeBody();
		}
	},
	
	saveChanges: function ()
	{
		var
			oAccount = WebMail.Accounts.getEditableAccount(),
			oCustomForwarding = new CCustomForwardingData()
		;
		oCustomForwarding.email = oAccount.email;
		if (this.$NotificationsCheck.attr('checked') === 'checked') {
			oCustomForwarding.value = CustomForwardingTypes.Notifiations;
		}
		else if (this.$ForwardCheck.attr('checked') === 'checked') {
			oCustomForwarding.value = CustomForwardingTypes.Forward;
		}
		else {
			oCustomForwarding.value = CustomForwardingTypes.Nothing;
		}

		var xml = oCustomForwarding.getInXml();
		RequestHandler('update', 'notification_settings', xml);
	},

	build: function(container)
	{
		var eForwardEditZone = CreateChild(container, 'div', [['class', 'wm_email_settings_edit_zone wm_hide']]);
		this._$ForwardEditZone = $(eForwardEditZone);

		var $tbl = $('<table class="wm_settings_signature"></table>').appendTo(eForwardEditZone);
		var $td = $('<td></td>').appendTo($('<tr></tr>').appendTo($tbl));
		this.$NotificationsCheck = $('<input type="radio" id="notifications" name="forwarding" class="wm_checkbox"/>').appendTo($td);
		this.$NotificationsLabel = $('<label for="notifications"></label>').appendTo($td);
		var $a = $('<a href="#" style="margin: 0 5px;"></a>').appendTo($td);
		WebMail.langChanger.register$({$elem: $a, sField: 'ForwardingChange', sType: 'html'});
		
		$td = $('<td></td>').appendTo($('<tr></tr>').appendTo($tbl));
		this.$ForwardCheck = $('<input type="radio" id="forward" name="forwarding" class="wm_checkbox"/>').appendTo($td);
		this.$ForwardLabel = $('<label for="forward"></label>').appendTo($td);
		$a = $('<a href="#" style="margin: 0 5px;"></a>').appendTo($td);
		WebMail.langChanger.register$({$elem: $a, sField: 'ForwardingChange', sType: 'html'});
		
		$td = $('<td></td>').appendTo($('<tr></tr>').appendTo($tbl));
		this.$NothingCheck = $('<input type="radio" id="nothing" name="forwarding" class="wm_checkbox"/>').appendTo($td);
		var $lbl = $('<label for="nothing"></label>').appendTo($td);
		WebMail.langChanger.register$({$elem: $lbl, sField: 'ForwardingNothing', sType: 'html'});

		var tbl = CreateChild(eForwardEditZone, 'table');
		tbl.className = 'wm_settings_buttons';
		var tr = tbl.insertRow(0);
		var td = tr.insertCell(0);
		var inp = CreateChild(td, 'input', [['class', 'wm_button'], ['type', 'button']]);
		WebMail.langChanger.register$({$elem: $(inp), sField: 'Save', sType: 'value'});
		$(inp).bind('click', (function (obj) {
			return function () {
				obj.saveChanges();
			}
		})(this));
	}
};