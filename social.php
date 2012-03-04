<?php
/**
 * Created by JetBrains PhpStorm.
 * User: stefans
 * Date: 02.12.11
 * Time: 09:51
 * To change this template use File | Settings | File Templates.
 */
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
header("HTTP/1.0 300 Multiple Choices", false, 300);
@header("Content-Type:text/html;charset=utf-8");
$fileSocialLinks = @file_get_contents('sociallinks.txt');
if (isset($_GET['rewrite']) || trim($fileSocialLinks) == "") {
	$styleConfig = new StyleConfig(array());
	$styleConfig->append(new StyleSheet('only all', '../images/64x64/', 64, 64, true));
	$styleConfig->append(new StyleSheet('only all and (min-width: 720px)', '../images/96x96/', 96));
	$styleConfig->append(new StyleSheet('only all and (min-width: 1024px)', '../images/128x128/', 128));
	$styleConfig->append(new StyleSheet('only all and (min-width: 1280px)', '../images/256x256/', 256));
	$styleConfig->append(new StyleSheet('only screen and (min-device-width: 720px) and (max-device-width: 1024px)', '../images/128x128/', 128));
	$styleConfig->append(new StyleSheet('only screen and (min-device-width: 720px) and (max-device-width: 1024px) and (orientation:portrait)', '../images/256x256/', 192));
	$styleConfig->append(new StyleSheet('only screen and (max-device-width: 480px) and (-webkit-min-device-pixel-ratio: 2)', '../images/128x128/', 72));
	$styleConfig->append(new StyleSheet('only screen and (max-device-width: 480px) and (orientation:portrait)', '../images/96x96/', 96));
	$styleConfig->append(new StyleSheet('only screen and (max-device-width: 480px) and (-webkit-min-device-pixel-ratio: 2) and (orientation:portrait)', '../images/256x256/', 96));
	$htaccess = file_get_contents('.htaccess');
	$redirectsExp = explode("\n###", $htaccess);
	$redirectsExp = str_replace("##\nRedirect 301 ", "|", $redirectsExp[0]);
	$redirectsExp = str_replace("##", "", $redirectsExp);
	$redirects = explode("\n", $redirectsExp);
	$socialLinks = array();
	$icons = array();
	foreach ($redirects as $redirect) {
		$socialLink = SocialLink::initFromHtAccess($redirect . "");
		array_push($socialLinks, $socialLink);
		array_push($icons, substr($socialLink->getInternalUrl(), 1));
	}
	$stylesSrc = file_get_contents('css/style.src.css');

	file_put_contents('css/style.css', str_replace("/*<QUERIES/>*/", $styleConfig->getSource($icons), $stylesSrc));
	@file_put_contents('sociallinks.txt', serialize($socialLinks));
} else {
	$socialLinks = unserialize($fileSocialLinks);
}

$title = 'Stefan Schult';
?><!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<html>
<head>
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1" />
	<link href='css/style.css' rel="stylesheet" type='text/css' />
	<script src="js/functions.js"></script>
	<title><?php echo $title ?></title>

</head>
<body>
<h1><?php echo $title ?></h1>
<ul>
	<?php foreach ($socialLinks as $socialLink) { ?>
	<li><a class="icon-<?php echo substr($socialLink->getInternalUrl(), 1) ?>" title="<?php echo $socialLink->getName() ?>" href="http://<?php echo $_SERVER['HTTP_HOST'] . $socialLink->getInternalUrl() ?>">
		<span><?php echo $socialLink->getName() ?></span>
	</a>
	</li>
	<?php } ?>
</ul>
<!-- Piwik -->
<script type="text/javascript">
	var pkBaseURL = (("https:" == document.location.protocol) ? "https://<?php echo $config['piwikUrl'] ?>/" : "http://<?php echo $config['piwikUrl'] ?>/");
	document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
	try {
		var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", <?php echo $config['piwikCode'] ?>);
		piwikTracker.trackPageView();
		piwikTracker.enableLinkTracking();
	} catch (err) {
	}
</script>
<noscript><p><img src="http://<?php echo $config['piwikUrl'] ?>/piwik.php?idsite=<?php echo $config['piwikCode'] ?>" style="border:0" alt="" /></p></noscript>
<!-- End Piwik Tracking Code -->
</body>
</html>


