<?php

namespace MiniSprite;

require __DIR__ . '/../bootstrap.php';

use \Tester\Assert;
use \Tester\TestCase;

/**
 * @testCase
 */
class AnalyzersTest extends TestCase
{
	public function getTestData()
	{
		$images = array();

		$images[] = new Image(
			"../assets/100x200.png", 
			new CssBlock('div#block1 {background:url(../images/100x200.png) 0 0 no-repeat;}')
		);

		$images[] = new Image(
			"../assets/300x150-2.png", 
			new CssBlock('div#block2 {background:url(../images/300x150-2.png) -25px -25px no-repeat;}')
		);

		$images[] = new Image(
			"../assets/300x150-2.png", 
			new CssBlock('div#block3 {background:url(../images/300x150-2.png) -175px -25px no-repeat;}')
		);

		$images[] = new Image(
			"../assets/200x100.png", 
			new CssBlock('div#block2 {background:url(../images/200x100.png) 0px 0px no-repeat;}')
		);

		$horizontalFolder = new HorizontalFolder;
		$verticalFolder = new VerticalFolder;

		return array(
			array(
				array(
					"horizontalCollection" => $horizontalFolder->generate($images), // 600 x 200 = 120000
					"verticalCollection" => $verticalFolder->generate($images), // 300 x 450 = 135000
				)
			)
		);
	}

	/**
	 * @dataProvider getTestData
	 */
	public function testMinimalAreaAnalyzer($collections) {
		
		$analyzer = new MinimalAreaAnalyzer;

		$best = $analyzer->getBest(
			array(
				$collections["horizontalCollection"],
				$collections["verticalCollection"],
			)
		);
		Assert::same($best,$collections["horizontalCollection"]);

		$best = $analyzer->getBest(
			array(
				$collections["verticalCollection"],
				$collections["horizontalCollection"],
			)
		);
		Assert::same($best,$collections["horizontalCollection"]);
	}

	/**
	 * @dataProvider getTestData
	 */
	public function testMinimalTransfersAnalyzer($collections) {
		
		$analyzer = new MinimalTransfersAnalyzer;

		$best = $analyzer->getBest(
			array(
				$collections["horizontalCollection"],
				$collections["verticalCollection"],
			)
		);
		Assert::same($best,$collections["horizontalCollection"]);

		$best = $analyzer->getBest(
			array(
				$collections["verticalCollection"],
				$collections["horizontalCollection"],
			)
		);
		Assert::same($best,$collections["verticalCollection"]);
	}
}

$testCase = new AnalyzersTest;
$testCase->run();