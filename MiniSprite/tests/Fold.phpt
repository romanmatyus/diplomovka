<?php

namespace MiniSprite;

require __DIR__ . '/bootstrap.php';

use \Tester\Assert;
use \Tester\TestCase;

/**
 * @testCase
 */
class FoldTest extends TestCase
{
	public function testOne() {
		$images = array();

		$cssDef = 'div#google {
			background:url(../images/google.png) 0 0 no-repeat;
		}';
		$cssBlock = new CssBlock($cssDef);
		$image1 = new Image("assets/google.png", $cssBlock);
		$image1->positionX = 0;
		$image1->positionY = 0;
		$images[] = $image1;

		$cssDef = 'div#twitter {
			background:url(../images/twitter.png) -32 px 0 no-repeat;
		}';
		$cssBlock = new CssBlock($cssDef);
		$image2 = new Image("assets/twitter.png", $cssBlock);
		$image2->positionX = 32;
		$image2->positionY = 0;
		$images[] = $image2;

		$cssDef = 'div#youtube {
			background:url(../images/youtube.png) -64px 0 no-repeat;
		}';
		$cssBlock = new CssBlock($cssDef);
		$image3 = new Image("assets/youtube.png", $cssBlock);
		$image3->positionX = 64;
		$image3->positionY = 0;
		$images[] = $image3;

		$fold = new Fold($images);

		Assert::equal($images, $fold->getImages());

		Assert::equal(96, $fold->getWidth());
		Assert::equal(32, $fold->getHeight());

		$i = 0;
		foreach ($fold->getImages() as $image) {
			Assert::equal(${"image".++$i}, $image);
		}
	}
}

$testCase = new FoldTest;
$testCase->run();
