<?php 

namespace MiniSprite;

/**
* Core class for MiniSprite generator.
*
* @author Roman Mátyus
* @copyright (c) Roman Mátyus 2014
* @license MIT
*/
class MiniSprite
{
	/** CONFIGURATION */

	/**
	 * Directory of source CSS file. From this path are finding images with relative path's.
	 * @var string
	 */
	protected $imageDirSource;

	/**
	 * Directory where to save sprites.
	 * @var string
	 */
	protected $imageDirOutput;

	/**
	 * Prefix for CSS background-image with sprite filename. 
	 * @var string
	 */
	protected $imageDirOutputCss;

	/** NEEDED OBJECTS */

	/** @var IAnalyzer */
	protected $analyzer;

	/** @var array of IFolder */
	protected $folders = array();

	/** INTERNAL VARIABLES */

	/** @var string */
	private $rawInput;

	/** @var array of Image's */
	private $images = array();

	/** @var array of aarray of Fold's */
	private $collections = array();

	/**
	 * Compile od CSS input.
	 * @param  string $input          Content of CSS source.
	 * @param  string $imageDirSource Path of base dir for finding images with relative path.
	 * @return string Content of regenerated CSS source.
	 */
	public function compile($input, $imageDirSource = NULL)
	{
		$this->rawInput = $input;

		if (!is_null($imageDirSource))
			$this->imageDirSource = $imageDirSource;

		$this->getImages();
		$this->callFolders();

		/** @var Fold */
		$bestFold = $this->analyzer->getBest($this->collections);

		$this->compose($bestFold);

		$output = $this->getCssOutput($bestFold);

		$this->rawInput = NULL;
		$this->images = array();
		$this->collections = array();

		return $output;
	}

	/**
	 * Setter Directory of source CSS file. From this path are finding images with relative path's.
	 * @param string $imageDirSource
	 * @return MiniSprite
	 */
	public function setImageDirSource($imageDirSource)
	{
		if (empty($imageDirSource))
			throw new InvalidArgumentException("Argument \$imageDirSource not be empty");
		if (!is_string($imageDirSource))
			throw new InvalidArgumentException("Argument \$imageDirSource must be string");
		$this->imageDirSource = $imageDirSource;
		return $this;
	}

	/**
	 * Setter Directory where to save sprites.
	 * @param string $imageDirOutput
	 * @return MiniSprite
	 */
	public function setImageDirOutput($imageDirOutput)
	{
		if (empty($imageDirOutput))
			throw new InvalidArgumentException("Argument \$imageDirOutput not be empty");
		if (!is_string($imageDirOutput))
			throw new InvalidArgumentException("Argument \$imageDirOutput must be string");
		if (!is_dir($imageDirOutput))
			throw new InvalidArgumentException("Argument \$imageDirOutput must be directory");
		if (!is_writable($imageDirOutput))
			throw new InvalidArgumentException("Argument \$imageDirOutput must be writable directory");
		$this->imageDirOutput = $imageDirOutput;
		return $this;
	}

	/**
	 * Setter Directory where to save sprites.
	 * @param string $imageDirOutputCss
	 * @return MiniSprite
	 */
	public function setImageDirOutputCss($imageDirOutputCss)
	{
		if (empty($imageDirOutputCss))
			throw new InvalidArgumentException("Argument \$imageDirOutputCss not be empty");
		if (!is_string($imageDirOutputCss))
			throw new InvalidArgumentException("Argument \$imageDirOutputCss must be string");
		$this->imageDirOutputCss = $imageDirOutputCss;
		return $this;
	}

	/**
	 * Add Folder with folding algorithms.
	 * @param IFolder $folder
	 * @return MiniSprite
	 */
	public function addFolder(IFolder $folder)
	{
		$name = explode("\\",get_class($folder));
		$name = lcfirst(array_pop($name));
		$this->folders[$name] = $folder;
		return $this;
	}

	/**
	 * Set analyzer of folding algorithms.
	 * @param IAnalyzer $analyzer
	 * @return MiniSprite
	 */
	public function setAnalyzer(IAnalyzer $analyzer)
	{
		$this->analyzer = $analyzer;
	}

	/**
	 * Get all images files from source CSS.
	 * @return bool
	 */
	protected function getImages()
	{
		preg_match_all('/\s*(?<block>[^\{]*\{[^\}]*\})\s*/i', $this->rawInput, $blocks);
		foreach ($blocks["block"] as $block) {
			preg_match_all('~\bbackground(-image)?\s*:(.*?)url\s*\(\s*(\'|")?(?<image>.*?)\3?\s*\)~i', $block, $matches);
			foreach ($matches['image'] as $image)
				if (!(substr($image,0,5)==="data:") && !(strpos($image,"base64"))) {
					$this->images[] = new Image(realpath($this->imageDirSource.$image), new CssBlock($block));
				}
		}
		return $this->images;
	}

	protected function callFolders()
	{
		foreach ($this->folders as $name => $folder)
			$this->collections[$name] = $folder->generate($this->images);
	}

	protected function compose(array $bestFold)
	{
		foreach ($bestFold as $type => $fold) {
			$sprite = imagecreatetruecolor($fold->getWidth(), $fold->getHeight());
			imagesavealpha($sprite, TRUE);
			imagealphablending($sprite, FALSE);
			$transparent = imagecolorallocatealpha($sprite,255,255,255,127);
			imagefilledrectangle($sprite, 0, 0, $fold->getWidth(), $fold->getHeight(), $transparent);

			foreach ($fold->getImages() as $image) {
				if (is_null($image->getContent()))
					continue;
				imagecopy($sprite, $image->getContent(), $image->positionX, $image->positionY ,0 ,0 , $image->getWidth() , $image->getHeight());
			}

			imagepng($sprite,$this->imageDirOutput.$type.".png");
			imagedestroy($sprite);
		}
	}

	protected function getCssOutput(array $bestFold)
	{
		$css = $this->rawInput;
		foreach ($bestFold as $type => $fold) {
			foreach ($fold->getImages() as $image) {
				$image->getCssBlock()->{"background-image"} = "url('".$this->imageDirOutputCss.$type.".png')";
				$css = str_replace($image->getCssBlock()->getOrigin(), $image->getCssBlock(), $css);
			}
		}
		return $css;
	}
}
