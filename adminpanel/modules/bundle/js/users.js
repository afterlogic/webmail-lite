$(function() {

	$('#IdUsersNewMailingListButton').click(function(){
		document.location = AP_INDEX + '?list';
	});

	// Mailing list
	$('#btnAddUser').bind('click', function() {
		AddUserToList('txtNewUserAddress', 'selListMembersDDL');
	});

	$('#txtNewUserAddress').bind('keypress', function(ev) {
		ev = (window.event) ? window.event : ev;
		if (ev.keyCode == 13) {
			AddUserToList('txtNewUserAddress', 'selListMembersDDL');
			return false;
		}
		return true;
	});

	$('#btnDeleteUser').bind('click', function() {
		DeleteSelectedFromList('selListMembersDDL');
	});

	$('#selListMembersDDL').bind('keypress', function(ev) {
		ev = (window.event) ? window.event : ev;
		if (ev.keyCode == 46) {
			DeleteSelectedFromList('selListMembersDDL');
		}
		return (ev.keyCode != 13);
	});

	// Aliases
	$('#btnAddUserAlias').bind('click', function() {
		AddUserToList('txtNewUserAlias', 'selAliasesDDL', $('#hiddenDomainName').val());
	});

	$('#txtNewUserAlias').bind('keypress', function(ev) {
		ev = (window.event) ? window.event : ev;
		if (ev.keyCode == 13) {
			AddUserToList('txtNewUserAlias', 'selAliasesDDL', $('#hiddenDomainName').val());
			return false;
		}
		return true;
	});

	$('#btnDeleteUserAlias').bind('click', function() {
		DeleteSelectedFromList('selAliasesDDL');
	});

	$('#selAliasesDDL').bind('keypress', function(ev) {
		ev = (window.event) ? window.event : ev;
		if (ev.keyCode == 46) {
			DeleteSelectedFromList('selAliasesDDL');
		}
		return (ev.keyCode != 13);
	});

	// Forwards
	$('#btnAddUserForward').bind('click', function() {
		AddUserToList('txtNewUserForward', 'selForwardsDDL');
	});

	$('#txtNewUserForward').bind('keypress', function(ev) {
		ev = (window.event) ? window.event : ev;
		if (ev.keyCode == 13) {
			AddUserToList('txtNewUserForward', 'selForwardsDDL');
			return false;
		}
		return true;
	});

	$('#btnDeleteUserForward').bind('click', function() {
		DeleteSelectedFromList('selForwardsDDL');
	});

	$('#selForwardsDDL').bind('keypress', function(ev) {
		ev = (window.event) ? window.event : ev;
		if (ev.keyCode == 46) {
			DeleteSelectedFromList('selForwardsDDL');
		}
		return (ev.keyCode != 13);
	});

	$("#main_form").submit(function(e){
		e.preventDefault();
		SelectListAll('selListMembersDDL');
		SelectListAll('selAliasesDDL');
		SelectListAll('selForwardsDDL');
	});
});
