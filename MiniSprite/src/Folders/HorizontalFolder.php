<?php 

namespace MiniSprite;

/**
* @author Roman Mátyus
* @copyright (c) Roman Mátyus 2014
* @license MIT
*/
class HorizontalFolder implements IFolder
{
	public function generate(array $images)
	{
		$_imagesList = array();
		$_imagesProcessed = array();
		$coordinateX = 0;

		// filter usable files
		foreach ($images as $image) {
			if (!is_null($image->getCssBlock()->{"background-position"})) {
				$position = CssBlock::parseBackgroundPosition($image->getCssBlock()->{"background-position"});
				if (
					!preg_match("/\d{1,}\s*(px)?/i",$position["horizontal"])&&
					!preg_match("/\d{1,}\s*(px)?/i",$position["horizontal"])
				)
					continue;
			}
			$_imagesList[$image->getRepeating()][] = $image;
		}

		foreach ($_imagesList[Image::NORMAL] as $image) {
			$position = CssBlock::parseBackgroundPosition($image->getCssBlock()->{"background-position"});
			
			foreach ($position as $type => $value)
				$position[$type] = trim(str_replace("px", NULL, $value));

			$sameImage = (isset($_imagesProcessed[Image::NORMAL]))
				? $this->findByUrl($_imagesProcessed[Image::NORMAL], $image->getUrl())
				: NULL;

			if(is_null($sameImage)) {
				$image->positionX = $coordinateX;
				$image->positionY = 0;
				$image->getCssBlock()->{"background-position"} = (-1*$coordinateX+$position["horizontal"]) . "px ".$position["vertical"]."px";
				$coordinateX += $image->getWidth();
			} else {
				$image->positionX = $sameImage->positionX;
				$image->positionY = $sameImage->positionY;
				$image->getCssBlock()->{"background-position"} = (-1*$sameImage->positionX+$position["horizontal"]) . "px ".(-1*$sameImage->positionY+$position["vertical"])."px";
			}
			$_imagesProcessed[$image->getRepeating()][] = clone $image;
		}
		
		return array(
			Image::NORMAL => new Fold($_imagesProcessed[Image::NORMAL]),
		);
	}

	private function findByUrl($images, $url)
	{
		$i=0;
		foreach ($images as $image) {
			if ($image->getUrl() === $url)
				return $image;
		}
		return NULL;
	}
}
