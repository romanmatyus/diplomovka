<?php 

namespace MiniSprite;

/**
* Fold prototype for MiniSprite generator.
*
* @author Roman Mátyus
* @copyright (c) Roman Mátyus 2014
* @license MIT
*/
class Fold
{
	/** array of Image's */
	protected $images = array();

	/** @int */
	protected $width;

	/** @int */
	protected $height;

	/**
	 * @param array of Image $image
	 */
	public function __construct(array $images)
	{
		foreach ($images as $image) {
			if (!($image instanceof Image))
				throw new InvalidArgumentException("Array items must be instanece of Image");
			$this->images[] = $image;
		}
	}

	/**
	 * Getter for images in Fold.
	 * @return array of Image's
	 */
	public function getImages()
	{
		return $this->images;
	}

	public function getWidth()
	{
		if (is_null($this->width))
			$this->calculateSize();
		return $this->width;
	}

	public function getHeight()
	{
		if (is_null($this->height))
			$this->calculateSize();
		return $this->height;
	}

	private function calculateSize()
	{
		foreach ($this->images as $image) {
			$width = $image->getWidth() + $image->positionX;
			if ($width>$this->width)
				$this->width = $width;

			$height = $image->getHeight() + $image->positionY;
			if ($height>$this->height)
				$this->height = $height;
		}
	}
}