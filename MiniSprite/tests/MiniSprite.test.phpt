<?php

require '../vendor/Tester/Tester/bootstrap.php';
require '../MiniSprite.php';
require '../InvalidArgumentException.php';
require '../Folders/IFolder.php';
require '../Folders/HorizontalFolder.php';

use Tester\Assert;
use MiniSprite\MiniSprite;
use MiniSprite\InvalidArgumentException;
use MiniSprite\HorizontalFolder;

$miniSprite = new MiniSprite;
//Assert::type('MiniSprite\MiniSprite',$miniSprite->addFolder(new HorizontalFolder));
Assert::true(TRUE);