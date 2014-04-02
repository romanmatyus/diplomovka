<?php

require '../vendor/Tester/Tester/bootstrap.php';
require '../MiniSprite.php';
require '../InvalidArgumentException.php';
require '../IFolder.php';
require '../HorizontalFolder.php';

use Tester\Assert;
use MiniSprite\MiniSprite;
use MiniSprite\InvalidArgumentException;
use MiniSprite\HorizontalFolder;

Assert::error(function() {
	Assert::error(function() {
		$miniSprite = new MiniSprite;
		$miniSprite->addFolder("test");
	}, E_RECOVERABLE_ERROR);
}, 'InvalidArgumentException', "Argument \$folder must be instance of IFolder!");


$miniSprite = new MiniSprite;
Assert::type('MiniSprite\MiniSprite',$miniSprite->addFolder(new HorizontalFolder));