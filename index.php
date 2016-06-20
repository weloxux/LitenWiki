<?php
include('functions.php');

if (!file_exists('config.php')) {
	die('Config file doesn\'t exist. Did you rename the example file?');
}

include('config.php');

if (!$enabled) {
	die('Wiki is not currently enabled. You can change this in the config file.');
}

if (isset($_GET["page"])) {
	$page = $_GET["page"];
} else {
	$page = $mainpage;
}

if (isset($_GET["mode"]) && $_GET["mode"] == "edit") {
	$edit = true;
} else {
	$edit = false;
}

makeheader($page, $name);
makepage($page, $edit);
if (isset($_POST['send'])) {
	if(empty($_POST['content'])) { unlink("pages/$page.tm"); }
	else { post($page, $_POST['content'], $_POST['captcha']); }
}
makefooter($page, $edit);
