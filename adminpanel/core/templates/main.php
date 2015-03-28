<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

	if (!isset($this)) exit();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php $this->Title(); ?></title>
	<link rel="shortcut icon" href="./static/images/favicon.ico" />
	<?php $this->IncludeCss(); ?>
	<script type="text/javascript">
		var AP_INDEX = '<?php echo AP_INDEX_FILE ?>';
		var AP_TAB = '<?php echo $this->Tab() ?>';
	</script>
</head>
<body>
	<div id="content" class="wm_content">
<?php
	$this->IncludeScreen();
?>
	</div>
<?php
	$this->IncludeJs();
?>
</body>
</html>