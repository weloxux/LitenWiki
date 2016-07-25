<?php
$name = 'LitenWiki';                                 // How your wiki will refer to itself
$mainpage = 'MainPage';                              // Landing page when no specific page is requested
$allpages = 'AllPages';                              // Page listing all pages on the wiki
$footertext = "Copyright &copy; 2016 - [[About]]";   // Footer text, markup enabled

$captcha_enabled = false;                            // Whether or not captcha is enabled
$captcha_text = '7 - 3 =';                           // Captcha question that needs to be answered if captcha is enabled
$captcha_answer = '4';                               // Answer to the captcha question

$templates = array(
	'EX'		=> '<b>This is an example template.</b>',
	'STUB'		=> '<i>This article is a stub. Please know that its information is incomplete.</i><br />',
	'PAGECOUNT'	=> count(scandir("pages")) - 2, // - 2 because of '.' and '..'
);

$enabled = true;                                     // Whether or not the wiki is accessible
