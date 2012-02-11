<?php
function __autoload($class_name)
{
	$file = 'classes/' . $class_name . '.php';
	if (file_exists($file)) {
		require_once($file);
	} else {
		throw new Exception("Unable to load $class_name.");
	}
}
require_once("config.php");
$idSite = $config['piwikCode'];
//$idSite = 7;
$fileSocialLinks = file_get_contents('sociallinks.txt');
$socialLinks = unserialize($fileSocialLinks);

function checkView(SocialLink $socialLink)
{
	$view = $_GET['v'];
	return $view === $socialLink->getInternalUrl();
}

$view = null;
// check vars
foreach ($socialLinks as $socialLink) {
	if (checkView($socialLink)) {
		$view = $socialLink;
	}
}

if ($view == null) {
	header("Location: /social");
	exit;
}

if (!(boolean)$_SERVER['HTTP_DNT']) {

	PiwikTracker::$URL = 'http://piwik.schult.info/';

	$piwikTracker = new PiwikTracker($idSite);
	$piwikTracker->setUserAgent($_SERVER['HTTP_USER_AGENT']);

	// set agent language
	$language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], ';'));
	if (strlen($language) > 5) {
		$language = substr($language, 0, strpos($language, ','));
	}
	$piwikTracker->setBrowserLanguage($language);

	$piwikTracker->setIp($_SERVER['REMOTE_ADDR']);
	$piwikTracker->setUrl($url = 'http://' . $_SERVER['SERVER_NAME'] . $view->getInternalUrl());
	$piwikTracker->setTokenAuth('0d07ecba9fef4676ac7cadae92508f5f');

	$piwikTracker->doTrackPageView($view->getName());

}
header("Location: " . $view->getUrl());
?>
