<?php

namespace MiniSprite;

require __DIR__ . '/bootstrap.php';

use \Tester\Assert;
use \Tester\TestCase;

/**
 * @testCase
 */
class CssBlockTest extends TestCase
{
	public function testOne() {
		$source = '

BODY td .div a[href^="ddd"] {
	background:url("../foo.png") repeat-x;
	width:100px;
	height   	:   1 px
}';

		$cssBlock = new CssBlock($source);

		Assert::equal('url("../foo.png") repeat-x', $cssBlock->background);
		Assert::equal('url("../foo.png")', $cssBlock->{"background-image"});
		Assert::equal('repeat-x', $cssBlock->{"background-repeat"});
		Assert::equal('100px', $cssBlock->width);
		Assert::equal('1 px', $cssBlock->height);
		Assert::equal('BODY td .div a[href^="ddd"] {
	background: url("../foo.png") repeat-x;
	width: 100px;
	height: 1 px;
}
', (string)$cssBlock);

		$cssBlock->{"background-position"} = "-10px -13px";
		Assert::equal('url("../foo.png") -10px -13px repeat-x', $cssBlock->background);

		$cssBlock->{"background-image"} = 'url("../bar.png")';
		Assert::equal('url("../bar.png") -10px -13px repeat-x', $cssBlock->background);

		$cssBlock->{"background-color"} = 'white';
		Assert::equal('white url("../bar.png") -10px -13px repeat-x', $cssBlock->background);

		$cssBlock->{"background-image"} = 'none';
		Assert::equal('white none -10px -13px repeat-x', $cssBlock->background);

		$cssBlock->{"background-image"} = NULL;
		Assert::equal('white -10px -13px repeat-x', $cssBlock->background);

		$cssBlock->{"background-position"} = "left top";
		Assert::equal('white 0 0 repeat-x', $cssBlock->background);

		$cssBlock->{"background-repeat"} = "repeat";
		$cssBlock->{"background-attachment"} = "scroll";
		Assert::equal('white 0 0 repeat scroll', $cssBlock->background);

		$cssBlock->{"background-position"} = "bottom";
		Assert::equal('white bottom repeat scroll', $cssBlock->background);

		$cssBlock->{"background-position"} = "10 % -30%";
		$cssBlock->{"background-color"} = "RGB(0 , 0 , 0 )";
		Assert::equal('RGB(0 , 0 , 0 ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "RGB(0 , 0 , 0 )";
		Assert::equal('RGB(0 , 0 , 0 ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "rgb (1 , 0 , 0 )";
		Assert::equal('rgb (1 , 0 , 0 ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "rgb (63 , 0 , 0 )";
		Assert::equal('rgb (63 , 0 , 0 ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "rgb (99 , 0 , 0 )";
		Assert::equal('rgb (99 , 0 , 0 ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "rgb (199 , 0 , 0 )";
		Assert::equal('rgb (199 , 0 , 0 ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "rgb (255 , 0 , 0 )";
		Assert::equal('rgb (255 , 0 , 0 ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "rgba(0 , 0 , 0, 0 )";
		Assert::equal('rgba(0 , 0 , 0, 0 ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "rgba(0 , 0 , 0, 0.24 )";
		Assert::equal('rgba(0 , 0 , 0, 0.24 ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "rgba(0 , 0 , 0, 1 )";
		Assert::equal('rgba(0 , 0 , 0, 1 ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "hsl (0 , 50% , 10% )";
		Assert::equal('hsl (0 , 50% , 10% ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "hsl (10 , 50% , 10% )";
		Assert::equal('hsl (10 , 50% , 10% ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "hsl (30 , 50% , 10% )";
		Assert::equal('hsl (30 , 50% , 10% ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "hsl (99 , 50% , 10% )";
		Assert::equal('hsl (99 , 50% , 10% ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "hsl (100 , 50% , 10% )";
		Assert::equal('hsl (100 , 50% , 10% ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "hsl (199 , 50% , 10% )";
		Assert::equal('hsl (199 , 50% , 10% ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "hsl (299 , 50% , 10% )";
		Assert::equal('hsl (299 , 50% , 10% ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "hsl (300 , 50% , 10% )";
		Assert::equal('hsl (300 , 50% , 10% ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "hsl (359 , 50% , 10% )";
		Assert::equal('hsl (359 , 50% , 10% ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "hsl (360 , 50% , 10% )";
		Assert::equal('hsl (360 , 50% , 10% ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "hsl (300 , 0% , 0 %)";
		Assert::equal('hsl (300 , 0% , 0 %) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "hsl (359 , 0 %, 0% )";
		Assert::equal('hsl (359 , 0 %, 0% ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "hsl (360 , 0% , 0 %)";
		Assert::equal('hsl (360 , 0% , 0 %) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "hsla (360 , 0% , 0 %, 0.2)";
		Assert::equal('hsla (360 , 0% , 0 %, 0.2) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "HSLa(0 , 0% , 0%, 0 )";
		Assert::equal('HSLa(0 , 0% , 0%, 0 ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "hsla(0 , 0% , 0%, 0.24 )";
		Assert::equal('hsla(0 , 0% , 0%, 0.24 ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "hsla(0 , 0% , 0%, 1 )";
		Assert::equal('hsla(0 , 0% , 0%, 1 ) 10 % -30% repeat scroll', $cssBlock->background);

		$cssBlock->{"background-color"} = "transparent";
		Assert::equal('transparent 10 % -30% repeat scroll', $cssBlock->background);

		Assert::equal('BODY td .div a[href^="ddd"] {
	background: transparent 10 % -30% repeat scroll;
	width: 100px;
	height: 1 px;
}
', (string)$cssBlock);

		$cssBlock->background = "url(../images/twitter.png) -32 px 0 no-repeat";
		unset($cssBlock->{"background-color"});
		unset($cssBlock->{"background-attachment"});
		Assert::equal('url(../images/twitter.png) -32 px 0 no-repeat', $cssBlock->background);

		Assert::equal(NULL, $cssBlock->{"background-color"});
		Assert::equal("url(../images/twitter.png)", $cssBlock->{"background-image"});
		Assert::equal("-32 px 0", $cssBlock->{"background-position"});
		Assert::equal("no-repeat", $cssBlock->{"background-repeat"});
		Assert::equal(NULL, $cssBlock->{"background-attachment"});

		$cssBlock->{"background-position"} = "0px 0px";
		Assert::equal("0 0", $cssBlock->{"background-position"});
	}
}

$testCase = new CssBlockTest;
$testCase->run();
