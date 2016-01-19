<?php
function makeheader ($page, $name) {
	echo("<!doctype html><html><head><meta charset=\"UTF-8\" /><title>$page - $name</title></head><body><div id=\"wrapper\">");
}

function makepage ($page, $edit) {
	include('config.php');
	$path = 'pages/' . $page . '.tm';

	if ($page == "AllPages") { // Special page that lists all pages
		echo("<div id=\"pagecontent\"><p>This page lists all pages in this wiki</p><ul>");
		$handler = opendir('pages');
		while ($file = readdir($handler)) {
			if ($file != "." && $file != "..") {
				$pagename = substr($file, 0, -3); // Slice file extensions
				echo("<li><a href=\"?page=$pagename\">$pagename</a></li>");
			}
		}
		echo("</ul><br /></div><hr />");

		return;
	}

	if(file_exists($path)){
		$file = fopen($path, "r");
		$rawcontent = fread($file, filesize($path));
	} else {
		//echo("<div id=\"pagecontent\"><p>Page does not exist. You can create it yourself.</p></div>");
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
	echo('<form method="post" name="post"><textarea name="content" rows="16" cols="90">' . ($edit == true ? $content : '') . '</textarea><input name="send" type="hidden" /><br /><br />' . ($captcha_enabled = false ? 'Captcha: $captcha_text <input name=\"captcha\" type=\"text\" />' : '') .  '<input type="submit" value="Submit" /></form><br />');
}

function makefooter ($page, $edit) {
	include("config.php");
        $self = $_SERVER['PHP_SELF']; // get current page
	echo('<div class="toolbar">[<a href="?page=' . $mainpage . '">MainPage</a>] ' . ($page != 'AllPages' ? '[<a href="?page=' . $page  . ($edit == true ? '&mode=view' : '&mode=edit') . '">' . ($edit == true ? 'ViewPage' : 'EditPage') . '</a>] ' : '') .  '[<a href="?page=AllPages">AllPages</a>]</div></div></body></html>');
}

function tsukimark2 ($page, $content) {
	$textAr = explode("\n", $content);
	$content = "<h1>$page</h1>";

	foreach($textAr as $line) {
		$line = preg_replace('/((https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \? \.#=-]*)*\/?)/', '<a href="$1">$1</a>', $line);

		$line = preg_replace('/^\s*(?!#)(.+)$/', '<p>$1</p>', $line);
		$line = preg_replace("/^###(.*$)/", '<h3>$1</h3>', $line);
		$line = preg_replace("/^##(.*$)/", '<h2>$1</h2>', $line);
		$line = preg_replace("/^#(.*$)/", '<h1>$1</h1>', $line);
		$line = preg_replace("/----/", "<hr />", $line);

		$line = preg_replace("/([A-Z][a-z]+([A-Z][a-z]+)+)/", '<a href="?page=$1">$1</a>', $line);

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
	$content = trim(htmlspecialchars($content));
	$path = 'pages/' . $page . '.tm';
	$file = fopen($path, "w") or die("Can't access file!");
	fwrite($file, $content);
	fclose($file);
	die('Post successful! Refreshing!<meta http-equiv="refresh" content="0;url="">');
}
?>
