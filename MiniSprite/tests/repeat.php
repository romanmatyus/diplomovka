<?php
//print_r(extension_loaded("gd"));exit;
//print_r(get_loaded_extensions());exit;

$surX = 0;
$sprite = array();
foreach ($images as $image) {
	$position = CssBlock::parseBackgroundPosition($image->getCssBlock->{"background-position"});
	$sameImage = findByUrl($image->getUrl());

	if(is_null($sameImage)) {
		$image->positionX = $surX;
		$image->positionX = 0;
		$image->getCssBlock->{"background-position"} = (-1*$surX+$position["horizontal"]) . "px ".$position["vertical"]."px";
		$surX += $image->getWidth();
	} else {
		$sameImagePosition = CssBlock::parseBackgroundPosition($sameImage->getCssBlock->{"background-position"});
		$image->getCssBlock->{"background-position"} = $sameImagePosition["horizontal"]+$position["horizontal"] . "px ".$sameImagePosition["vertical"]+$position["vertical"]."px";
	}
	$sprite[] = $image;
}

function findByUrl(array $sprite, $url)
{
	foreach ($sprite as $image) {
		if ($image->getUrl() === $url)
			return $image;
	}
	return NULL;
}