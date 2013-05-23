<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	function PrintMessagePreview($skin, $isRtlOn, $body, $textCharset, $from, $to, $date, $subject, $attachments = null, $cc = null, $usePrint = false)
	{
		$CCTr = $AttachTr = '';

		$headLink = ($isRtlOn) ? '<link rel="stylesheet" href="skins/'.$skin.'/styles-rtl.css" type="text/css" id="skin-rtl">' : '';

		$dirTextStyle = ' style="text-align: left;"';
		if ($textCharset)
		{
			if (strtolower($textCharset) == 'iso-8859-8-i')
			{
				$dirTextStyle = ' style="text-align: right;"';
			}
			else
			{
				switch (ConvertUtils::GetCodePageNumber($textCharset))
				{
					case 1255:
					case 1256:
					case 28596:
					case 28598:
						$dirTextStyle = ' style="text-align: right;"';
						break;
					case 65001:
						if ($isRtlOn)
						{
							$dirTextStyle = ' style="text-align: right;"';
						}
						break;
				}
			}
		}

		if ($cc && strlen($cc) > 0)
		{
			$CCTr = '
		<tr>
			<td class="wm_print_title" width="60px">
				'.JS_LANG_CC.'
			</td>
			<td class="wm_print_value">
				'.ConvertUtils::WMHtmlSpecialChars($cc).'
			</td>
		</tr>';
		}

		if ($attachments && strlen($attachments) > 0)
		{
			$AttachTr = '
		<tr>
			<td class="wm_print_title" width="60px">
				'.Attachments.':
			</td>
			<td class="wm_print_value">
				'.$attachments.'
			</td>
		</tr>';
		}

		$usePrintStr = ($usePrint) ? ' onload="window.print();"' : '';

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<link rel="shortcut icon" href="favicon.ico" />
		<link rel="stylesheet" href="./skins/<?php echo $skin; ?>/styles.css" type="text/css" />
		<?php echo $headLink; ?>
	</head>
	<body class="wm_body"<?php echo $usePrintStr; ?>>
		<div align="center" class="wm_space_before">
			<table class="wm_print">
				<tr>
					<td class="wm_print_title" width="60px">
					<?php echo JS_LANG_From;?>:
					</td>
					<td class="wm_print_value">
						<?php echo ConvertUtils::WMHtmlSpecialChars($from); ?>
					</td>
				</tr>
				<tr>
					<td class="wm_print_title" width="60px">
						<?php echo JS_LANG_To;?>:
					</td>
					<td class="wm_print_value">
						<?php echo ConvertUtils::WMHtmlSpecialChars($to); ?>
					</td>
				</tr>
				<?php echo $CCTr; ?>
				<tr>
					<td class="wm_print_title" width="60px">
						<?php echo JS_LANG_Date;?>:
					</td>
					<td class="wm_print_value">
						<?php echo ConvertUtils::WMHtmlSpecialChars($date);	?>
					</td>
				</tr>
				<tr>
					<td class="wm_print_title" width="60px">
						<?php echo JS_LANG_Subject;?>:
					</td>
					<td class="wm_print_value">
						<?php echo ConvertUtils::WMHtmlSpecialChars($subject); ?>
					</td>
				</tr>
				<?php echo $AttachTr; ?>
				<tr>
					<td colspan="2" class="wm_print_body">
						<div class="wm_space_before"<?php echo $dirTextStyle; ?>>
							<?php echo $body; ?>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
<?php
	}

	/**
	 *
	 * @param <type> $msg
	 * @param CAccount $account
	 * @param bool $isEncode
	 * @return string
	 */
	function PrintHtmlBodyForViewMsgScreen(&$msg, $account, $isEncode = true)
	{
		$newtext = '';
		if (!CApi::GetSettings()->GetConf('WebMail/AlwaysShowImagesInMessage'))
		{
			$newtext = ConvertUtils::HtmlBodyWithoutImages($msg->GetCensoredHtmlWithImageLinks($isEncode));
			if (isset($GLOBALS[GL_WITHIMG]) && $GLOBALS[GL_WITHIMG])
			{
				$GLOBALS[GL_WITHIMG] = false;
			}
		}
		else
		{
			$newtext = $msg->GetCensoredHtmlWithImageLinks($isEncode);
		}

		return $newtext;
	}
