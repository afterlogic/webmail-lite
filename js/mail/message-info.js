/*
 * Classes:
 *  CMessagePicturesController(bInNewWindow)
 *  CMessageReadConfirmationController(readConfirmationHandler, parent)
 *  CMessageSensivityController()
 *  CAppointmentConfirmationController()
 *  CIcsController()
 *  CVcfController()
 */

function CMessagePicturesController(bInNewWindow)
{
	this._bInNewWindow = bInNewWindow;
	this._fromAddr = '';
	this._offerAlwaysShowPictures = false;
	this._safety = SAFETY_NOTHING;
	this._offerShowPictures = false;
	
	this._container = null;
	this._showPicturesText = null;
}

CMessagePicturesController.prototype =
{
	SetSafety: function (safety)
	{
		this._safety = safety;
		this._offerShowPictures = (this._safety == SAFETY_NOTHING);
		this._setClassName();
	},
	
	SetFromAddr: function (fromAddr)
	{
		this._fromAddr = fromAddr;
		this._offerAlwaysShowPictures = (this._fromAddr.length > 0 && window.UseDb !== false);
		this._setClassName();
	},
	
	_setClassName: function ()
	{
		this._showPicturesText.className = (this._offerShowPictures) ? '' : 'wm_hide';
		this._showAlwaysPicturesText.className = (this._offerAlwaysShowPictures) ? '' : 'wm_hide';
	},
	
	show: function ()
	{
		this._setClassName();
		this._container.className = (this._offerShowPictures || this._offerAlwaysShowPictures) ? 'wm_safety_info' : 'wm_hide';
	},
	
	hide: function ()
	{
		this._container.className = 'wm_hide';
	},
	
	getHeight: function ()
	{
		return this._container.offsetHeight;
	},
	
	resizeWidth: function (width)
	{
		var paddings = 16;
		var borders = 2;
		this._container.style.width = (width - paddings - borders) + 'px';
	},
	
	showPictures: function ()
	{
		this.SetSafety(SAFETY_MESSAGE);
		this.show();
		ShowPicturesHandler(SAFETY_MESSAGE);

		if (this._bInNewWindow) {
			if (window.opener) {
				window.opener.SetMessageSafetyHandler(ViewMessage);
				window.opener.ShowPicturesHandler(SAFETY_MESSAGE);
			}
		}
		else {
			SetMessageSafetyHandler();
		}
	},
	
	showPicturesFromSender: function ()
	{
		this.hide();
		ShowPicturesHandler(SAFETY_FULL);
		
		if (this._bInNewWindow) {
			if (window.opener) {
				window.opener.SetSenderSafetyHandler(this._fromAddr);
				window.opener.ShowPicturesHandler(SAFETY_FULL);
			}
		}
		else {
			SetSenderSafetyHandler(this._fromAddr);
		}
	},
	
	build: function (parent)
	{
		this._container = CreateChild(parent, 'div', [['style', 'font:12px Tahoma,Arial,Helvetica,sans-serif;']]);
		
		var span = CreateChild(this._container, 'span');
		this._showPicturesText = span;
		var text = CreateChild(span, 'span');
		text.innerHTML = Lang.PicturesBlocked + '&nbsp;';
		WebMail.langChanger.register('innerHTML', text, 'PicturesBlocked', '&nbsp;');
		var a = CreateChild(span, 'a');
		a.innerHTML = Lang.ShowPictures;
		a.href = '#';
		var obj = this;
		a.onclick = function () {
			obj.showPictures();
			return false;
		};
		WebMail.langChanger.register('innerHTML', a, 'ShowPictures', '');
		text = CreateChild(span, 'span');
		text.innerHTML = '.&nbsp;';
		
		span = CreateChild(this._container, 'span');
		a = CreateChild(span, 'a');
		a.innerHTML = Lang.ShowPicturesFromSender;
		a.href = '#';
		a.onclick = function () {
			obj.showPicturesFromSender();
			return false;
		};
		WebMail.langChanger.register('innerHTML', a, 'ShowPicturesFromSender', '');
		text = CreateTextChild(span, '.');
		this._showAlwaysPicturesText = span;
		this.hide();
	}
};

function CMessageReadConfirmationController(readConfirmationHandler, parent)
{
	this._readConfirmationHandler = readConfirmationHandler;
	this._parent = parent;

	this._container = null;
}

CMessageReadConfirmationController.prototype =
{
	show: function ()
	{
		this._container.className = 'wm_safety_info';
	},

	hide: function ()
	{
		this._container.className = 'wm_hide';
	},

	SendConfirmationMail: function ()
	{
		this.hide();
		this._readConfirmationHandler.call(this._parent);
	},

	getHeight: function ()
	{
		return this._container.offsetHeight;
	},

	resizeWidth: function (width)
	{
		var paddings = 16;
		var borders = 2;
		this._container.style.width = (width - paddings - borders) + 'px';
	},
	
	build: function (container)
	{
		var obj = this;
		this._container = CreateChild(container, 'div', [['class', 'wm_hide']]);
		var span = CreateChild(this._container, 'span');
		var text = CreateChild(span, 'span');
		text.innerHTML = Lang.ReturnReceiptTopText + '&nbsp;';
		WebMail.langChanger.register('innerHTML', text, 'ReturnReceiptTopText', '&nbsp;');
		var a = CreateChild(span, 'a');
		a.innerHTML = Lang.ReturnReceiptTopLink;
		a.href = '#';
		a.onclick = function () {
			obj.SendConfirmationMail();
			return false;
		};
		WebMail.langChanger.register('innerHTML', a, 'ReturnReceiptTopLink', '');
	}
};

function CMessageSensivityController()
{
	this._message = null;
}

CMessageSensivityController.prototype =
{
	show: function (sensivity)
	{
		this._message.className = 'wm_safety_info';
		switch (sensivity) {
			case SENSIVITY_CONFIDENTIAL:
				this._message.innerHTML = Lang.SensivityConfidential;
				break;
			case SENSIVITY_PRIVATE:
				this._message.innerHTML = Lang.SensivityPrivate;
				break;
			case SENSIVITY_PERSONAL:
				this._message.innerHTML = Lang.SensivityPersonal;
				break;
			default:
				this.hide();
				break;
		}
	},

	hide: function ()
	{
		this._message.className = 'wm_hide';
	},
	
	getHeight: function ()
	{
		return this._message.offsetHeight;
	},
	
	resizeWidth: function (width)
	{
		var paddings = 16;
		var borders = 2;
		this._message.style.width = (width - paddings - borders) + 'px';
	},
	
	build: function (container)
	{
		this._message = CreateChild(container, 'div');
		this.hide();
	}
};

function CAppointmentConfirmationController()
{
	this.$container = null;
	this.$location = null;
	this.$calendars = null;
	this.$calendar = null;
	this.$when = null;
	this.$description = null;
	this.$actionsCont = null;
	this.$accept = null;
	this.$decline = null;
	this.$tentative = null;
	this.$replyDecision = null;
	this.oAppointment = null;
	this.oMsg = null;
}

CAppointmentConfirmationController.prototype =
{
	show: function (oMsg)
	{
		var
			aAllowedTypes = [EnumAppointmentType.Request, EnumAppointmentType.Reply, EnumAppointmentType.Cancel],
			sFrom = (oMsg.fromDisplayName.length > 0) ? oMsg.fromDisplayName : oMsg.fromAddr
		;
		if (oMsg.oAppointment !== null && -1 !== $.inArray(oMsg.oAppointment.sType, aAllowedTypes)) {
			this.oMsg = oMsg;
			this.$container.show();
			this.fill(oMsg.oAppointment, sFrom);
		}
		else {
			this.hide();
		}
	},

	hide: function ()
	{
		this.$container.hide();
		this.oAppointment = null;
		this.oMsg = null;
	},

	getHeight: function ()
	{
		return this.$container.is(':visible') ? this.$container.outerHeight() : 0;
	},

	resizeWidth: function (iWidth)
	{
		var
			iPaddings = 16,
			iBorders = 2
		;
		this.$container.css('width', (iWidth - iPaddings - iBorders));
	},
	
	onUpdate: function (sUid)
	{
		if (this.oAppointment.sUid === sUid) {
			this.fill(this.oAppointment);
		}
	},
	
	fill: function (oAppointment, sFrom)
	{
		this.oAppointment = oAppointment;
		switch (oAppointment.sType) {
			case EnumAppointmentType.Request:
				this.fillDescriptions(oAppointment);
				this.fillRequestAppointment(oAppointment);
				this.fillCalendars(oAppointment.aCalendars, oAppointment.sCalId);
				break;
			case EnumAppointmentType.Reply:
				this.fillDescriptions(oAppointment);
				this.fillReplyAppointment(oAppointment, sFrom);
				this.fillCalendars(oAppointment.aCalendars, oAppointment.sCalId);
				break;
			case EnumAppointmentType.Cancel:
				this.fillDescriptions(oAppointment);
				this.fillCancelAppointment(sFrom);
				this.fillCalendars(oAppointment.aCalendars, oAppointment.sCalId);
				break;
			default:
				this.$actionsCont.hide();
				this.$accept.hide();
				this.$decline.hide();
				this.$tentative.hide();
				this.$replyDecision.hide();
				break;
		}
	},
	
	fillDescriptions: function (oAppointment)
	{
		if (oAppointment.sLocation.length > 0) {
			this.$location.html(oAppointment.sLocation);
			this.$locationCont.show();
		}
		else {
			this.$locationCont.hide();
		}
		this.$when.html(oAppointment.sWhen);
		if (oAppointment.sDescription.length > 0) {
			this.$description.html(oAppointment.sDescription);
			this.$descriptionCont.show();
		}
		else {
			this.$descriptionCont.hide();
		}
	},
	
	fillCalendars: function (aCalendars, sCalId)
	{
		var
			iLen = aCalendars.length,
			iIndex = 0,
			oCalendar = null,
			bSelected = false,
			$option = null
		;
		this.$calendars.html('');
		for (; iIndex < iLen; iIndex++) {
			oCalendar = aCalendars[iIndex];
			$option = $('<option></option>')
				.text(oCalendar.sName)
				.attr('value', oCalendar.sId)
				.appendTo(this.$calendars);
			if (sCalId === oCalendar.sId) {
				$option.attr('selected', 'selected');
				bSelected = true;
				this.$calendar.text(oCalendar.sName);
			}
		}
		
		if (bSelected) {
			this.$calendars.hide();
			this.$calendar.show();
		}
		else {
			this.$calendars.show();
			this.$calendar.hide();
		}
		
		return bSelected;
	},
	
	fillRequestAppointment: function (oAppointment)
	{
		var
			self = this,
			sConfig = EnumAppointmentConfig.NeedAction
		;
		
		if (WebMail.existsCalendarEvent(oAppointment.sUid)) {
			if (WebMail.existsTentativeCalendarEvent(oAppointment.sUid)) {
				if (oAppointment.sConfig !== EnumAppointmentConfig.Tentative) {
					this.oAppointment.sConfig = EnumAppointmentConfig.Tentative;
					WebMail.DataSource.cache.setMessageSpecialAttach(this.oMsg.id, this.oMsg.uid, 
						this.oMsg.idFolder, this.oMsg.folderFullName, this.oAppointment, 
						'oAppointment');
					WebMail.deleteTentativeCalendarEvent(oAppointment.sUid);
				}
				sConfig = EnumAppointmentConfig.Tentative;
			}
			else {
				sConfig = oAppointment.sConfig;
			}
		}
		
		this.$actionsCont.show();
		this.$accept.show();
		this.$decline.show();
		this.$tentative.show();
		this.$replyDecision.hide();
		this.$accept.unbind('click');
		this.$decline.unbind('click');
		this.$tentative.unbind('click');
		
		switch (sConfig) {
			case EnumAppointmentConfig.Accepted:
				this.$accept.addClass('disable');
				this.$decline.bind('click', function () {self.sendAppointmentConfirmation(false, EnumAppointmentConfig.Declined);});
				this.$decline.removeClass('disable');
				this.$tentative.bind('click', function () {self.sendAppointmentConfirmation(false, EnumAppointmentConfig.Tentative);});
				this.$tentative.removeClass('disable');
				break;
			case EnumAppointmentConfig.Declined:
				this.$accept.bind('click', function () {self.sendAppointmentConfirmation(true, EnumAppointmentConfig.Accepted);});
				this.$accept.removeClass('disable');
				this.$decline.addClass('disable');
				this.$tentative.bind('click', function () {self.sendAppointmentConfirmation(false, EnumAppointmentConfig.Tentative);});
				this.$tentative.removeClass('disable');
				break;
			case EnumAppointmentConfig.Tentative:
				this.$accept.bind('click', function () {self.sendAppointmentConfirmation(true, EnumAppointmentConfig.Accepted);});
				this.$accept.removeClass('disable');
				this.$decline.bind('click', function () {self.sendAppointmentConfirmation(false, EnumAppointmentConfig.Declined);});
				this.$decline.removeClass('disable');
				this.$tentative.addClass('disable');
				break;
			case EnumAppointmentConfig.NeedAction:
				this.$accept.bind('click', function () {self.sendAppointmentConfirmation(true, EnumAppointmentConfig.Accepted);});
				this.$accept.removeClass('disable');
				this.$decline.bind('click', function () {self.sendAppointmentConfirmation(false, EnumAppointmentConfig.Declined);});
				this.$decline.removeClass('disable');
				this.$tentative.bind('click', function () {self.sendAppointmentConfirmation(false, EnumAppointmentConfig.Tentative);});
				this.$tentative.removeClass('disable');
				break;
			default:
				this.$accept.hide();
				this.$decline.hide();
				this.$tentative.hide();
				break;
		}
	},
	
	fillReplyAppointment: function (oAppointment, sFrom)
	{
		this.$actionsCont.show();
		this.$accept.hide();
		this.$decline.hide();
		this.$tentative.hide();
		this.$replyDecision.show();
		
		switch (oAppointment.sConfig) {
			case EnumAppointmentConfig.Accepted:
				this.$replyDecision.html(sFrom + ' ' + Lang.AppointmentAccepted);
				break;
			case EnumAppointmentConfig.Declined:
				this.$replyDecision.html(sFrom + ' ' + Lang.AppointmentDeclined);
				break;
			case EnumAppointmentConfig.Tentative:
				this.$replyDecision.html(sFrom + ' ' + Lang.AppointmentTentativelyAccepted);
				break;
			case EnumAppointmentConfig.NeedAction:
			default:
				this.$actionsCont.hide();
				break;
		}
	},
	
	fillCancelAppointment: function (sFrom)
	{
		this.$actionsCont.show();
		this.$accept.hide();
		this.$decline.hide();
		this.$tentative.hide();
		this.$replyDecision.show().html(Lang.AppointmentCanceled.replace('%SENDER%', sFrom));
	},
	
	sendAppointmentConfirmation: function (bAccepted, sAction)
	{
		if (sAction === undefined) {
			sAction = this.oAppointment.sConfig;
		}
		
		this.oAppointment.sCalId = this.$calendars.val();
		
		SendAppointmentConfirmationHandler(this.oAppointment, bAccepted, sAction);
		this.oAppointment.sConfig = sAction;
		this.fill(this.oAppointment);
	},
	
	build: function (eParent)
	{
		var
			$innerCont = null,
			$span = null
		;
		
		this.$container = $('<div class="wm_appointment_info"></div>').hide().appendTo(eParent);
		
		$innerCont = $('<div></div>').appendTo(this.$container);
		this.$calendars = $('<select></select>').css('float', 'right').appendTo($innerCont);
		this.$calendar = $('<span></span>').css('float', 'right').appendTo($innerCont);
		$span = $('<span></span>').addClass('wm_appointment_title').css('float', 'right').appendTo($innerCont);
		WebMail.langChanger.register$({sType: 'text', $elem: $span, sField: 'AppointmentCalendar', sEnd: ':'});
		
		this.$locationCont = $('<div></div>').appendTo(this.$container);
		$span = $('<span></span>').addClass('wm_appointment_title').appendTo(this.$locationCont);
		WebMail.langChanger.register$({sType: 'text', $elem: $span, sField: 'AppointmentLocation', sEnd: ':'});
		this.$location = $('<span></span>').appendTo(this.$locationCont);
		
		$innerCont = $('<div></div>').appendTo(this.$container);
		$span = $('<span></span>').addClass('wm_appointment_title').appendTo($innerCont);
		WebMail.langChanger.register$({sType: 'text', $elem: $span, sField: 'AppointmentWhen', sEnd: ':'});
		this.$when = $('<span></span>').appendTo($innerCont);
		
		this.$descriptionCont = $('<div class="wm_description"></div>').appendTo(this.$container);
		$span = $('<span></span>').addClass('wm_appointment_title').appendTo(this.$descriptionCont);
		WebMail.langChanger.register$({sType: 'text', $elem: $span, sField: 'AppointmentDescription', sEnd: ':'});
		this.$description = $('<span></span>').appendTo(this.$descriptionCont);
		
		this.$actionsCont = $('<div class="wm_appointment_decision"></div>').appendTo(this.$container);
		this.$accept = $('<a href="#"></a>').css('margin', '5px').appendTo(this.$actionsCont);
		WebMail.langChanger.register$({sType: 'html', $elem: this.$accept, sField: 'AppointmentButtonAccept'});
		this.$tentative = $('<a href="#"></a>').css('margin', '5px').appendTo(this.$actionsCont);
		WebMail.langChanger.register$({sType: 'html', $elem: this.$tentative, sField: 'AppointmentButtonTentative'});
		this.$decline = $('<a href="#"></a>').css('margin', '5px').appendTo(this.$actionsCont);
		WebMail.langChanger.register$({sType: 'html', $elem: this.$decline, sField: 'AppointmentButtonDecline'});
		this.$replyDecision = $('<span></span>').css('margin', '5px').appendTo(this.$actionsCont);
	}
};

function CIcsController()
{
	this.$container = null;
	this.$location = null;
	this.$calendars = null;
	this.$calendar = null;
	this.$when = null;
	this.$description = null;
	this.$actionsCont = null;
	this.$alreadyExists = null;
	this.$addToCalendar = null;
	this.oIcs = null;
	this.oMsg = null;
}

CIcsController.prototype =
{
	show: function (oMsg)
	{
		if (oMsg.oIcs !== null) {
			this.oMsg = oMsg;
			this.oIcs = oMsg.oIcs;
			this.$container.show();
			this.fill(oMsg.oIcs);
		}
		else {
			this.hide();
		}
	},

	hide: function ()
	{
		this.$container.hide();
	},

	onUpdate: function (sUid)
	{
		if (this.oIcs.sUid === sUid) {
			this.oIcs.sCalId = this.$calendars.val();
			this.$alreadyExists.html(Lang.ReportEventSaved).show();
			this.$addToCalendar.hide();
		}
	},
	
	fill: function (oIcs)
	{
		var
			self = this,
			bExist = false
		;
		this.fillDescriptions(oIcs);
		bExist = this.fillCalendars(oIcs.aCalendars, oIcs.sCalId) && WebMail.existsCalendarEvent(oIcs.sUid);
		this.$addToCalendar.unbind('click');
		if (bExist) {
			this.$alreadyExists.html(Lang.EventAlreadyExistsInCalendar).show();
			this.$addToCalendar.hide();
		}
		else {
			this.$alreadyExists.hide();
			this.$addToCalendar.show().one('click', function () {self.saveIcs();});
		}
	},
	
	saveIcs: function ()
	{
		SaveIcs(this.oIcs, this.$calendars.val());
	},
	
	build: function (eParent)
	{
		var
			$innerCont = null,
			$span = null
		;
		
		this.$container = $('<div class="wm_appointment_info"></div>').hide().appendTo(eParent);
		
		$innerCont = $('<div></div>').appendTo(this.$container);
		this.$calendars = $('<select></select>').css('float', 'right').appendTo($innerCont);
		this.$calendar = $('<span></span>').css('float', 'right').appendTo($innerCont);
		$span = $('<span></span>').addClass('wm_appointment_title').css('float', 'right').appendTo($innerCont);
		WebMail.langChanger.register$({sType: 'text', $elem: $span, sField: 'AppointmentCalendar', sEnd: ':'});
		
		this.$locationCont = $('<div></div>').appendTo(this.$container);
		$span = $('<span></span>').addClass('wm_appointment_title').appendTo(this.$locationCont);
		WebMail.langChanger.register$({sType: 'text', $elem: $span, sField: 'AppointmentLocation', sEnd: ':'});
		this.$location = $('<span></span>').appendTo(this.$locationCont);
		
		$innerCont = $('<div></div>').appendTo(this.$container);
		$span = $('<span></span>').addClass('wm_appointment_title').appendTo($innerCont);
		WebMail.langChanger.register$({sType: 'text', $elem: $span, sField: 'AppointmentWhen', sEnd: ':'});
		this.$when = $('<span></span>').appendTo($innerCont);
		
		this.$descriptionCont = $('<div class="wm_description"></div>').appendTo(this.$container);
		$span = $('<span></span>').addClass('wm_appointment_title').appendTo(this.$descriptionCont);
		WebMail.langChanger.register$({sType: 'text', $elem: $span, sField: 'AppointmentDescription', sEnd: ':'});
		this.$description = $('<span></span>').appendTo(this.$descriptionCont);
		
		this.$actionsCont = $('<div class="wm_appointment_decision"></div>').appendTo(this.$container);
		this.$alreadyExists = $('<span></span>').css('margin', '5px').hide().appendTo(this.$actionsCont);
		this.$addToCalendar = $('<a href="#"></a>').css('margin', '5px').appendTo(this.$actionsCont);
		WebMail.langChanger.register$({sType: 'html', $elem: this.$addToCalendar, sField: 'AddToCalendar'});
	}
};

CIcsController.prototype.getHeight = CAppointmentConfirmationController.prototype.getHeight;
CIcsController.prototype.resizeWidth = CAppointmentConfirmationController.prototype.resizeWidth;
CIcsController.prototype.fillDescriptions = CAppointmentConfirmationController.prototype.fillDescriptions;
CIcsController.prototype.fillCalendars = CAppointmentConfirmationController.prototype.fillCalendars;

function CVcfController()
{
	this.$container = null;
	this.$name = null;
	this.$email = null;
	this.$actionsCont = null;
	this.$alreadyExists = null;
	this.$addToContacts = null;
	this.oVcf = null;
	this.oMsg = null;
}

CVcfController.prototype =
{
	show: function (oMsg)
	{
		if (oMsg.oVcf !== null) {
			this.oMsg = oMsg;
			this.oVcf = oMsg.oVcf;
			this.$container.show();
			this.fill(oMsg.oVcf);
		}
		else {
			this.hide();
		}
	},

	hide: function ()
	{
		this.$container.hide();
	},

	onUpdate: function (sUid)
	{
		if (this.oVcf.sUid === sUid) {
			this.oVcf.bExists = true;
			this.$alreadyExists.html(Lang.ReportContactSuccessfulyAdded).show();
			this.$addToContacts.hide();
		}
	},
	
	fill: function (oVcf)
	{
		var self = this;
		if (oVcf.sName.length > 0) {
			this.$name.html(oVcf.sName);
			this.$nameCont.show();
		}
		else {
			this.$nameCont.hide();
		}
		
		if (oVcf.sEmail.length > 0) {
			this.$email.html(oVcf.sEmail);
			this.$emailCont.show();
		}
		else {
			this.$emailCont.hide();
		}
		
		this.$addToContacts.unbind('click');
		if (oVcf.bExists) {
			this.$alreadyExists.html(Lang.ContactAlreadyExistsInAddressBook).show();
			this.$addToContacts.hide();
		}
		else {
			this.$alreadyExists.hide();
			this.$addToContacts.show().one('click', function () {self.saveVcf();});
		}
	},
	
	saveVcf: function ()
	{
		SaveVcf(this.oVcf);
	},
	
	build: function (eParent)
	{
		var
			$span = null
		;
		
		this.$container = $('<div class="wm_appointment_info"></div>').hide().appendTo(eParent);
		
		this.$nameCont = $('<div></div>').appendTo(this.$container);
		$span = $('<span></span>').addClass('wm_appointment_title').appendTo(this.$nameCont);
		WebMail.langChanger.register$({sType: 'text', $elem: $span, sField: 'Name', sEnd: ':'});
		this.$name = $('<span></span>').appendTo(this.$nameCont);
		
		this.$emailCont = $('<div class="wm_description"></div>').appendTo(this.$container);
		$span = $('<span></span>').addClass('wm_appointment_title').appendTo(this.$emailCont);
		WebMail.langChanger.register$({sType: 'text', $elem: $span, sField: 'Email', sEnd: ':'});
		this.$email = $('<span></span>').appendTo(this.$emailCont);
		
		this.$actionsCont = $('<div class="wm_appointment_decision"></div>').appendTo(this.$container);
		this.$alreadyExists = $('<span></span>').css('margin', '5px').hide().appendTo(this.$actionsCont);
		this.$addToContacts = $('<a href="#"></a>').css('margin', '5px').appendTo(this.$actionsCont);
		WebMail.langChanger.register$({sType: 'html', $elem: this.$addToContacts, sField: 'AddToContacts'});
	}
};

CVcfController.prototype.getHeight = CAppointmentConfirmationController.prototype.getHeight;
CVcfController.prototype.resizeWidth = CAppointmentConfirmationController.prototype.resizeWidth;

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}