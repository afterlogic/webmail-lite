<?php die(); ?><html><body>
<?php
require_once ("recaptchalib.php");

// get a key at http://www.google.com/recaptcha/mailhide/apikey
$mailhide_pubkey = '01h2tv-Bf9FJAgYBrNFNJ9Jw==';
$mailhide_privkey = '77ceb70bb976050a91035d9e2037b409';

?>

The Mailhide version of example@example.com is
<?php echo recaptcha_mailhide_html ($mailhide_pubkey, $mailhide_privkey, "example@example.com"); ?>. <br>

The url for the email is:
<?php echo recaptcha_mailhide_url ($mailhide_pubkey, $mailhide_privkey, "example@example.com"); ?> <br>

</body></html>
