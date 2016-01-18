<?php
function makeheader ($page, $name) {
	echo("<!doctype html><html><head><meta charset=\"UTF-8\" /><title>$page - $name</title></head><body><div id=\"wrapper\">");
}

function makepage ($page, $edit) {
	include('config.php');
	$path = 'pages/' . $page . '.tm';

	if(file_exists($path)){
		$file = fopen($path, "r");
		$rawcontent = fread($file, filesize($path));
	} else {
		echo("<div id=\"pagecontent\"><p>Page does not exist. You can create it yourself.</p></div>");
		$edit = true;
	}

	$content = tsukimark2($page, $rawcontent);

	echo("<div id=\"pagecontent\">$content<br /></div><hr />");

	if ($edit) {
		makeform($rawcontent);
	}
}

function makeform ($content) {
	echo('<form method="post" name="post"><textarea name="content" rows="16" cols="90">' . $content . '</textarea><input name="send" type="hidden" /><br /><br /><input type="submit" value="Submit" /></form><br />');
}

function makefooter ($page, $edit) {
        $self = $_SERVER['PHP_SELF']; // get current page
	echo('<div class="toolbar">[<a href="?page=MainPage">Home</a>] [<a href="?page=' . $page  . ($edit == true ? '&mode=view' : '&mode=edit') . '">' . ($edit == true ? 'View' : 'Edit') . '</a>]</div></div></body></html>');
}

function tsukimark2 ($page, $content) {
	$textAr = explode("\n", $content);
	$content = "<h1>$page</h1>";

	foreach($textAr as $line) {
		$line = preg_replace("/&gt;(.*)$/", '<span class="quote">&gt;$1</span>', $line);
		$line = preg_replace('/((https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \? \.#=-]*)*\/?)/', '<a href="$1">$1</a>', $line);

		$line = preg_replace('/^\s*(?!#)\s*(.+)\s*$/', '<p>$1</p>', $line);
		$line = preg_replace("/^###(.*$)/", '<h3>$1</h3>', $line);
		$line = preg_replace("/^##(.*$)/", '<h2>$1</h2>', $line);
		$line = preg_replace("/^#(.*$)/", '<h1>$1</h1>', $line);
		$line = preg_replace("/----/", "<hr />", $line);
		//$line = preg_replace("/^\s+(.*)/", '<pre>$1</pre>', $line);

		$line = preg_replace("/([A-Z][a-z]*([A-Z][a-z]*)+)/", '<a href="?page=$1">$1</a>', $line);

		$content = $content . $line;
	}

	$content = trim(preg_replace('/\s+/', ' ', $content));

	return $content;
}

function post ($page, $content) {
	$content = trim(htmlspecialchars($content));
	$path = 'pages/' . $page . '.tm';
	$file = fopen($path, "w") or die("Can't access file!");
	fwrite($file, $content);
	fclose($file);
	die('Post successful! Refreshing!<meta http-equiv="refresh" content="0;url="">');
}
?>
