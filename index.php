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

makeheader();
makepage($page, $edit);
if (isset($_POST['send'])) {
	if(empty($_POST['content'])) { die("<p>Empty post!</p>"); }
	else { post($page, $_POST['content']); }
}
makefooter($page, $edit);
?>
