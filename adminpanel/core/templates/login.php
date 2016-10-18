<div class="wm_content">
	<div id="login_screen" class="wm_login">
		<form autocomplete="off" action="<?php echo AP_INDEX_FILE; ?>?login" method="post">
			<?php $this->Data->PrintValue('LoginErrorDesc'); ?>
			<div class="wm_login">
				<div class="a top"></div>
				<div class="b top"></div>
				<div class="login_table" style="margin: 0px;">
					<div class="wm_login_header"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LOGIN_TITLE'); ?></div>
					<div class="wm_login_content">
						<table id="login_table" border="0" cellspacing="0" cellpadding="10">
						<tr>
							<td class="wm_title" style="font-size: 12px; width: 70px"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LOGIN_USERNAME'); ?></td>
							<td>
								<input class="wm_input" size="20" type="text" id="loginId" name="AdmloginInput"
									autocomplete="off" spellcheck="false"
									style="width: 99%; font-size: 16px;" onfocus="this.style.background = '#FFF9B2';"
									onblur="this.style.background = 'white';" value="<?php $this->Data->PrintInputValue('AdminLogin'); ?>"
								/>
							</td>
						</tr>
						<tr>
							<td class="wm_title" style="font-size: 12px; width: 70px"><?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LOGIN_PASSWORD'); ?></td>
							<td>
								<input class="wm_input" type="password" size="20" id="passwordId" name="AdmpasswordInput"
									autocomplete="off" spellcheck="false"
									style="width: 99%; font-size: 16px;" onfocus="this.style.background = '#FFF9B2';"
									onblur="this.style.background = 'white';" value="<?php $this->Data->PrintInputValue('AdminPassword'); ?>"
								/>
							</td>
						</tr>
						<tr>
							<td align="right" colspan="2">
								<span class="wm_login_button">
									<input class="wm_button" type="submit" name="enter" value="<?php echo CApi::I18N('ADMIN_PANEL/SCREEN_LOGIN_ENTER'); ?>" />
								</span>
							</td>
						</tr>
					</table>
					</div>
				</div>
				<div class="b"></div>
				<div class="a"></div>
			</div>
			<?php $this->Data->PrintValue('LoginDemoFooter'); ?>
		</form>
	</div>
</div>