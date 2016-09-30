<?php

function makeheader ($page, $name) {
	echo("<!doctype html><html><head><meta charset=\"UTF-8\" /><link type=\"text/css\" rel=\"stylesheet\" href=\"style.css\" /><title>$page - $name</title></head><body><div id=\"wrapper\">");
}

function makepage ($page, $edit) {
	include('config.php');
	$path = 'pages/' . $page . '.tm2';

	if ($page == $allpages) { // Special page that lists all pages
		echo("<div id=\"pagecontent\"><p>This page lists all pages in this wiki</p><ul>");
		$handler = opendir('pages');
		while ($file = readdir($handler)) {
			if ($file != "." && $file != ".." && substr($file, -4) == ".tm2" ) {
				$pagename = substr($file, 0, -4); // Slice file extensions
				echo("<li><a href=\"?page=$pagename\">$pagename</a></li>");
			}
		}
		echo("</ul><br /></div><hr />");

		return;
	}

	if (file_exists($path)) {
		$file = fopen($path, "r");
		$rawcontent = fread($file, filesize($path));
	} else {
		$rawcontent = 'Page does not exist. You can create it here.';
	}

	$content = tsukimark2($page, $rawcontent);

	echo("<div id=\"pagecontent\">$content<br /></div><hr />");

	if ($edit) {
		makeform($rawcontent, $edit);
	}
}

function makeform ($content, $edit) {
	include('config.php');
	echo('<form method="post" name="post"><textarea name="content" rows="16" cols="90">');
	if ($edit) {
		echo($content);
	}
	echo('</textarea><input name="send" type="hidden" /><br /><br />');
	if ($captcha_enabled) {
		echo("Captcha: $captcha_text <input name=\"captcha\" type=\"text\" /> ");
	}
	echo('<input type="submit" value="Submit" /></form><br />');
}

function makefooter ($page, $edit) {
	include("config.php");
	echo('<div class="toolbar">[<a href="?page=' . $mainpage . '">MainPage</a>] ' . ($page != $allpages ? '[<a href="?page=' . $page  . ($edit == true ? '&mode=view' : '&mode=edit') . '">' . ($edit == true ? 'ViewPage' : 'EditPage') . '</a>] ' : '') .  '[<a href="?page=' . $allpages . '">' . $allpages . '</a>] <span style="float:right;">' . tsukimark2(false, $footertext) . '</span></div></div></body></html>');
}

function tsukimark2 ($page, $content, $iscontent) {
	include("config.php");
	$textAr = explode("\n", $content);
	if ($page) { $content = "<h1>$page</h1>"; }
	else { $content = ""; }

	foreach($textAr as $line) {
		$line = preg_replace('/((https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \? \.#=-]*)*\/?)/', '<a href="$1">$1</a>', $line);

		if ($page) { $line = preg_replace('/^\s*(?!#)(?!\*)(.+)$/', '<p>$1</p>', $line); }
		$line = preg_replace("/^###(.*$)/", '<h3>$1</h3>', $line);
		$line = preg_replace("/^##(.*$)/", '<h2>$1</h2>', $line);
		$line = preg_replace("/^#(.*$)/", '<h1>$1</h1>', $line);
		$line = preg_replace("/----(-)*/", "<hr />", $line);

		$line = preg_replace("/\|([^\|]+)\|/", '<i>$1</i>', $line);
		$line = preg_replace("/_([^_]+)_/", '<b>$1</b>', $line);

		$line = preg_replace("/^.*\*\*\/.*$/", '</ul>', $line);
		$line = preg_replace("/^.*\/\*\*.*$/", '<ul>', $line);
		$line = preg_replace("/\s*\*\*\s*(.*)$/", '<li>$1</li>', $line);

		// Inserts and stats
		foreach ($templates as $template => $replacement) {
			$line = preg_replace("/\{\{" . $template . "\}\}/", $replacement, $line);
		}
		$line = preg_replace("/\{\{.*\}\}/", '<b>Unknown TEMPLATE!</b>', $line);

		$line = preg_replace("/\[\[([^\|]+)\|([^]]+)\]\]/", '<a href="?page=$1">$2</a>', $line, 1);
		$line = preg_replace("/\[\[([^\]]+)\]\]/", '<a href="?page=$1">$1</a>', $line);

		$content = $content . $line;
	}
	$content = trim(preg_replace('/\s+/', ' ', $content));

	return $content;
}

function post ($page, $content, $captcha) {
	include('config.php');
	if ($captcha_enabled) {
		if ($captcha != $captcha_answer) { die('Incorrect captcha!'); }
	}

	if (empty($_POST['content'])) { unlink("pages/$page.tm2"); return; }

	$content = trim(htmlspecialchars($content));
	$path = 'pages/' . $page . '.tm2';
	$file = fopen($path, "w") or die("Can't access file!");
	fwrite($file, $content);
	fclose($file);
	die('Post successful! Refreshing!<meta http-equiv="refresh" content="0;url="">');
}

/* Main execution starts here */

if (!file_exists('config.php')) {
	die('Config file doesn\'t exist. Did you rename the example file?');
}

include('config.php');

if (!$enabled) {
	die('Wiki is not currently enabled. You can change this in the config file.');
}

if (isset($_GET["page"])) {
	$page = ucfirst($_GET["page"]);
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
	post($page, $_POST['content'], $_POST['captcha']);
}
makefooter($page, $edit);
