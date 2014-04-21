<?php

namespace MiniSprite;

require '../vendor/Tester/Tester/bootstrap.php';
require '../Image.php';
require '../CssBlock.php';
require '../Folders/IFolder.php';
require '../Folders/HorizontalFolder.php';
require '../Folders/VerticalFolder.php';
require '../Analyzers/IAnalyzer.php';
require '../Analyzers/MinimalAreaAnalyzer.php';
require '../Analyzers/MinimalTransfersAnalyzer.php';
require '../InvalidArgumentException.php';
require '../Fold.php';
require '../MiniSprite.php';

use \Tester\Assert;
use \Tester\TestCase;

/**
 * @testCase
 */
class MiniSpriteTest extends TestCase
{
	const DIR_SOURCE = "./assets/";

	const DIR_OUTPUT = "./";

	const DIR_OUTPUT_CSS = "./images/";

	/** @var MiniSprite */
	private $miniSprite;

	public function setUp() {
		$this->miniSprite = new MiniSprite;

		$this->miniSprite->setImageDirSource(self::DIR_SOURCE);
		$this->miniSprite->setImageDirOutput(self::DIR_OUTPUT);
		$this->miniSprite->setImageDirOutputCss(self::DIR_OUTPUT_CSS);

		$this->miniSprite->addFolder(new HorizontalFolder);
		$this->miniSprite->addFolder(new VerticalFolder);

		$this->miniSprite->setAnalyzer(new MinimalAreaAnalyzer);
	}

	/**
	 * @dataProvider assets/MiniSprice.complete.data.ini
	 */
	public function testComplete($input, $output) {
		Assert::equal($output, $this->miniSprite->compile($input));
		Assert::equal($output, $this->miniSprite->compile($input));
	}

	public function tearDown()
	{
		@unlink(self::DIR_OUTPUT."normal.png");
		@unlink(self::DIR_OUTPUT."vertical.png");
		@unlink(self::DIR_OUTPUT."horizontal.png");
	}
}

$testCase = new MiniSpriteTest;
$testCase->run();