<?php
include('config.php');
include('functions.php');

if(isset($_GET["page"])) {
	$page = $_GET["page"];
} else {
	$page = $mainpage;
}

if(isset($_GET["mode"]) && $_GET["mode"] == "edit") {
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
?>
