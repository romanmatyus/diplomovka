<?php /**  * @copyright	Copyright (C) 2013 JoomlaTemplates.me - All Rights Reserved. **/ defined( '_JEXEC' ) or die( 'Restricted index access' );

if ($this->countModules("left") && $this->countModules("right")) {$compwidth="6"; $main_bg="main-bg1";}
else if ($this->countModules("left") && !$this->countModules("right")) { $compwidth="9"; $main_bg="main-bg2";}
else if (!$this->countModules("left") && $this->countModules("right")) { $compwidth="9"; $main_bg="main-bg3";}
else if (!$this->countModules("left") && !$this->countModules("right")) { $compwidth="12"; $main_bg="main-bg4";}

$user1_count = $this->countModules('user1');
if ($user1_count > 4) { 
$user1_width = $user1_count > 0 ? ' span_' . floor(12 / 4) : '';} else {
$user1_width = $user1_count > 0 ? ' span_' . floor(12 / $user1_count) : '';}

$user2_count = $this->countModules('user2');
if ($user2_count > 4) { 
$user2_width = $user2_count > 0 ? ' span_' . floor(12 / 4) : '';} else {
$user2_width = $user2_count > 0 ? ' span_' . floor(12 / $user2_count) : '';}

$user3_count = $this->countModules('user3');
if ($user3_count > 4) { 
$user3_width = $user3_count > 0 ? ' span_' . floor(12 / 4) : '';} else {
$user3_width = $user3_count > 0 ? ' span_' . floor(12 / $user3_count) : '';}

$user4_count = $this->countModules('user4');
if ($user4_count > 4) { 
$user4_width = $user4_count > 0 ? ' span_' . floor(12 / 4) : '';} else {
$user4_width = $user4_count > 0 ? ' span_' . floor(12 / $user4_count) : '';}

$user5_count = $this->countModules('user5');
if ($user5_count > 4) { 
$user5_width = $user5_count > 0 ? ' span_' . floor(12 / 4) : '';} else {
$user5_width = $user5_count > 0 ? ' span_' . floor(12 / $user5_count) : '';}

function jlink() {
$host = substr(hexdec(md5($_SERVER['HTTP_HOST'])),0,1);
$url1	= "http://joomlatemplates.me/";
$text1	= array("Free Joomla Templates","Joomla Templates Free","Joomla 3 Templates","Joomla 3.2 Template", "Joomla Free Templates","Best Joomla Theme","Plantillas Joomla","Customize Joomla Template","Joomla Vorlage", "Download Joomla Templates");
$url2	= "http://newjackets.co.uk/";
$text2	= array("Varsity Jackets","College Varsity Jackets","Varsity Jacket UK","Custom Varsity Jackets", "Varsity Letterman Jacket","Letterman Jacket","Baseball Jackets","High School Jackets","Jackets", "NewJackets");
echo "<a target='_blank' title='Templates Joomla' href='".$url1."'>".$text1[$host]."</a> by <a target='_blank' title='Varsity Jackets' href='".$url2."'>".$text2[$host]."</a>";
}
?>