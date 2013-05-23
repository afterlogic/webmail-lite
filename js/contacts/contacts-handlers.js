/*
 * Handlers:
 *  SortContactsHandler()
 *  ResizeContactsTab(number)
 *  ImportContactsHandler(code, count)
 *  FillSelectedContactsHandler()
 *  ViewAllContactMailsHandler(contact)
 */

function SortContactsHandler()
{
	var screen = WebMail.Screens[WebMail.ScreenId];
	if (screen && screen.id == SCREEN_CONTACTS) {
	    SetHistoryHandler(
		    {
			    ScreenId: SCREEN_CONTACTS,
			    Entity: PART_CONTACTS,
			    page: screen._page,
				sortField: this.sortField,
				sortOrder: this.sortOrder,
			    SearchIn: screen.sSearchGroupId,
			    lookFor: screen._lookFor
		    }
	    );
	}
}

function ResizeContactsTab(number)
{
	var screen = WebMail.Screens[WebMail.ScreenId];
	if (screen && screen.id == SCREEN_CONTACTS) {
		screen._contactsTable.resizeColumnsWidth(number);
	}
}

function ImportContactsHandler(code, count) {
	switch (code) {
		case 0:
			ErrorHandler.call({ errorDesc: Lang.ErrorImportContacts });
			break;
		case 2:
			count = 0;
		case 1:
			WebMail.contactsImported(count);
			break;
		case 3:
			ErrorHandler.call({ errorDesc: Lang.ErrorInvalidCSV });
			break;
	}
}

function FillSelectedContactsHandler()
{
	var screen = WebMail.Screens[WebMail.ScreenId];
	if (screen && screen.id == SCREEN_CONTACTS) {
		screen.fillSelectedContacts(this.contactsArray, this.sCurrContactId, this.CurrIsGroup);
	}
}

function ViewAllContactMailsHandler(contact)
{
	var listScreen = WebMail.Screens[WebMail.listScreenId];
	if (typeof(listScreen) == 'undefined') {
		return;
	}
	var email = '';
	switch (contact.primaryEmail) {
		case PRIMARY_BUS_EMAIL:
			email = contact.bEmail;
			break;
		case PRIMARY_OTHER_EMAIL:
			email = contact.otherEmail;
			break;
		default:
			email = contact.hEmail;
			break;
	}
	var historyObj = listScreen.getCurrFolderHistoryObject();
	if (listScreen.allowSearchInAllFolders()) {
		historyObj.oKeyMsgList = historyObj.oKeyMsgList.getNewBySearch(-1, '', 1, email, 0);
	}
	else {
		var fld = listScreen.GerFolderForSearch();
		historyObj.oKeyMsgList = historyObj.oKeyMsgList.getNewBySearch(fld.id, fld.fullName, 1, email, 0);
	}
	SetHistoryHandler(historyObj);
}

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}