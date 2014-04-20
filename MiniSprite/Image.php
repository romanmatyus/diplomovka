<?php 

namespace MiniSprite;

/**
* Image prototype for MiniSprite generator.
*
* @author Roman Mátyus
* @copyright (c) Roman Mátyus 2014
* @license MIT
*/
class Image
{
	const GIF = "gif";

	const JPG = "jpg";

	const PNG = "png";

	const NORMAL = "normal";

	const HORIZONTAL = "horizontal";

	const VERTICAL = "vertical";

	const WITHOUT = "without";

	/** @var string */
	protected $url;

	/** @var string */
	protected $type;

	/** @var binary */
	protected $content;

	/** @var CssBlock */
	protected $cssBlock;

	/** @var int */
	protected $size;

	/** @var string */
	protected $repeating;

	/** @var string */
	protected $width;

	/** @var string */
	protected $height;

	/** @int */
	public $positionX;

	/** @int */
	public $positionY;

	public function __construct($url, CssBlock $cssBlock)
	{
		$this->url = $url;
		$this->cssBlock = $cssBlock;
		$this->getImageInfo();
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return binary
	 */
	public function getContent()
	{
		if (is_null($this->content)) {
			switch ($this->type) {
				case self::GIF:
					$this->content = imagecreatefromgif($this->url);
					break;
				case self::JPG:
					$this->content = imagecreatefromjpeg($this->url);
					break;
				case self::PNG:
					$this->content = imagecreatefrompng($this->url);
					break;
			}
		}
		return $this->content;
	}

	/**
	 * @return CssBlock
	 */
	public function getCssBlock()
	{
		return $this->cssBlock;
	}

	/**
	 * @return integer
	 */
	public function getSize()
	{
		if (is_null($this->size))
			$this->size = filesize($this->url);
		return $this->size;
	}

	/**
	 * @return string
	 */
	public function getRepeating()
	{
		if (is_null($this->repeating)) {
			switch ($this->cssBlock->{"background-repeat"}) {
				case "no-repeat":
					$this->repeating = self::NORMAL;
					break;
				case "repeat-x":
					$this->repeating = self::HORIZONTAL;
					break;
				case "repeat-y":
					$this->repeating = self::VERTICAL;
					break;
				default:
					$this->repeating = self::WITHOUT;
					break;
			}
		}
		return $this->repeating;
	}

	/**
	 * @return integer
	 */
	public function getWidth()
	{
		return $this->width;
	}

	/**
	 * @return integer
	 */
	public function getHeight()
	{
		return $this->height;
	}

	protected function getImageInfo()
	{
		$info = getimagesize($this->url);
		$this->width = $info[0];
		$this->height = $info[1];

		$codes2extension = array(
			1 => "GIF",
			2 => "JPG",
			3 => "PNG",
			4 => "SWF",
			5 => "PSD",
			6 => "BMP",
			7 => "TIFF",
			8 => "TIFF",
			9 => "JPC",
			10 => "JP2",
			11 => "JPX",
			12 => "JB2",
			13 => "SWC",
			14 => "IFF",
			15 => "WBMP",
			16 => "XBM",
		);

		switch ($info[2]) {
			case 1:
				$this->type = self::GIF;
				break;
			case 2:
				$this->type = self::JPG;
				break;
			case 3:
				$this->type = self::PNG;
				break;
			default:
				throw new InvalidArgumentException("Image '" . $this->url . "' must be '" . self::JPG . "', '" . self::GIF . "' or '" . self::PNG . "', not '" . $codes2extension[$info[2]] . "'.");
		};
	}
}