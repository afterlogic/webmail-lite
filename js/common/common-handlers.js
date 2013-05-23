/*
 * Handlers:
 *  SetHistoryHandler(args)
 *  GetHandler(type, params, parts, xml, background)
 *  SelectScreenHandlerr(screenId)
 *  ShowScreenHandler()
 *  LoadHandler()
 *  ErrorHandler()
 *  ShowLoadingInfoHandler()
 *  TakeDataHandler()
 *  RequestHandler(action, request, xml, bBackground)
 *  ResizeBodyHandler()
 *  ClickBodyHandler(ev)
 *  EventBodyHandler()
 *  DisplayCalendarHandler()
 *  CreateSessionSaver()
 */

function SetHistoryHandler(args)
{
	args = WebMail.CheckHistoryObject(args);
	if (null != args) {
		if ((args.ScreenId == WebMail.listScreenId && WebMail.ScreenId == WebMail.listScreenId) ||
			(args.ScreenId == SCREEN_CONTACTS && WebMail.ScreenId == SCREEN_CONTACTS))
		{
			WebMail.restoreFromHistory(args);
		}
        else {
			HistoryStorage.addStep({functionName: 'WebMail.restoreFromHistory', args: args});
		}
	}
}

function GetHandler(type, params, parts, xml, background) {
	var currDefOrder = WebMail._defOrder;
	WebMail.DataSource.get(type, params, parts, xml, background);
	if (!background && type == TYPE_MESSAGE_LIST && WebMail.DataSource.lastFromCache && currDefOrder != WebMail._defOrder) {
		WebMail.DataSource.needInfo = false;
		RequestHandler('update', 'def_order', '<param name="def_order" value="' + WebMail._defOrder + '"/>');
	}
	return WebMail.DataSource.lastFromCache;
}

function SelectScreenHandler(screenId) {
	WebMail.ScreenIdForLoad = screenId;
	ShowScreenHandler();
}

function ShowScreenHandler() {
	WebMail.showScreen(ShowScreenHandler);
}

function LoadHandler() {
	WebMail.DataSource.parseXml(this.responseXML, this.action, this.request);
}

function ErrorHandler() {
	if (typeof this.background !== 'boolean') {
		this.background = false;
	}
	if (!this.background) {
		switch (this.request) {
		case 'delete':
		case 'move_to_folder':
			if (this.action == 'operation_messages') {
				var listScreen = WebMail.Screens[WebMail.listScreenId];
				if (!listScreen) return;
				if (this.request == 'delete') {
					listScreen.ClearDeleteTools();
				}
				listScreen.RevertMessageList();
			}
			break;
		case 'message':
			if (WebMail.ScreenId === SCREEN_MESSAGE_LIST_CENTRAL_PANE &&
					(this.action == 'send' || this.action == 'save')) {
				var oListScreen = WebMail.Screens[SCREEN_MESSAGE_LIST_CENTRAL_PANE];
				if (oListScreen) {
					oListScreen.slideAndShowReplyPane(false);
				}
			}
			break;
		case 'messages':
			if (this.action === 'get') {
				var oScreen = WebMail.Screens[WebMail.listScreenId];
				if (oScreen) {
					if (!WebMail.bCheckmail) {
						if (oScreen.oKeyMsgList.sLookFor.length > 0) {
							oScreen.setSearchErrorMessage();
						}
						else {
							oScreen.setRetrievingErrorMessage();
						}
					}
					if (WebMail.bHiddenCheckmail) {
						this.errorDesc = '';
						oScreen.enableTools();
					}
				}
			}
			break;
		}
		WebMail.hideInfo();
		WebMail.showError(this.errorDesc);
	}
}

function ShowLoadingInfoHandler() {
    var infoMessage = Lang.Loading;
    if (this.request == 'message') {
        switch (this.action) {
            case 'save':
                infoMessage = Lang.Saving;
                break;
            case 'send':
                infoMessage = Lang.Sending;
                break;
        }
    }
	WebMail.showInfo(infoMessage);
}

function TakeDataHandler() {
	if (this.data) {
		WebMail.placeData(this.data);
	}
}

function RequestHandler(action, request, xml, bBackground) {
	WebMail.DataSource.request({action: action, request: request}, xml, bBackground);
}

function ResizeBodyHandler() {
	if (WebMail) {
		WebMail.resizeBody(RESIZE_MODE_ALL);
	}
}

function ClickBodyHandler(ev) {
	if (WebMail) {
		WebMail.clickBody(ev);
        EventBodyHandler();
	}
}

function EventBodyHandler() {
	if (WebMail && WebMail.Settings) {
		var sett = WebMail.Settings;
		if (typeof(sett.idleSessionTimeout) != 'undefined' && sett.idleSessionTimeout > 0) {
			WebMail.startIdleTimer();
		}
	}
}

function DisplayCalendarHandler()
{
	var screen = WebMail.Screens[WebMail.ScreenId];
	if (screen && screen.id == SCREEN_CALENDAR) {
		screen.display();
	}
}

function CreateSessionSaver()
{
	CreateChild(document.body, 'iframe name="session_saver"', [['id', 'session_saver'], ['src', SessionSaverUrl], ['class', 'wm_hide']]);
}

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}