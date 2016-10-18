$(function () {

	var 
		oChEnableWebmail = $('#chEnableWebmail'),
		oChAllowNewUsersRegister = $('#chAllowNewUsersRegister')
	;

	if (0 < oChEnableWebmail.length && 0 < oChAllowNewUsersRegister.length)
	{
		oChAllowNewUsersRegister.click(function () {
			if (!this.checked && !oChEnableWebmail[0].checked)
			{
				this.checked = true;
				OnlineMsgError(Lang.AllowWebmailError);
			}
		});
		oChEnableWebmail.click(function () {
			if (!this.checked && !oChAllowNewUsersRegister[0].checked)
			{
				this.checked = true;
				OnlineMsgError(Lang.AllowWebmailError);
			}
		});
	}
	
});