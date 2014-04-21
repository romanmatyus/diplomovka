<?php

namespace MiniSprite;

require __DIR__ . '/bootstrap.php';

use \Tester\Assert;
use \Tester\TestCase;

/**
 * @testCase
 */
class ImageTest extends TestCase
{
	public function testOne() {
		$cssDef = 'div#google {
			background:url(../images/google.png) 0 0 no-repeat;
		}';

		$cssBlock = new CssBlock($cssDef);

		$image = new Image("assets/google.png", $cssBlock);

		Assert::equal("assets/google.png", $image->getUrl());
		Assert::equal(Image::NORMAL, $image->getRepeating());
		Assert::equal(1987, $image->getSize());
		Assert::equal(Image::PNG, $image->getType());
		Assert::equal(32, $image->getWidth());
		Assert::equal(32, $image->getHeight());
		Assert::same($cssBlock, $image->getCssBlock());

		$image->getCssBlock()->width = "100 px";
		Assert::equal("100 px", $image->getCssBlock()->width);
	}
}

$testCase = new ImageTest;
$testCase->run();
