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
	/** @var string */
	protected $rawInput;

	/** @var string */
	protected $basePath;

	/** @var array */
	protected $images = array();

	/** @var array of IFolder */
	protected $folders = array();

	/** @var array of FoldDescriptor */
	protected $foldersOutput = array();

	/** @var IAnalyzer */
	protected $analyzer;

	/** @var IComposer */
	protected $composer;

	/** @var ICssWriter */
	protected $cssWriter;

	public function __construct($config = array())
	{
		$this->setConfig($config);
	}

	/**
	 * Compile od CSS input.
	 * @param  string $input Content of CSS source.
	 * @param  string $path  Path of base dir for finding images with relative path.
	 * @return string Content of regenerated CSS source.
	 */
	public function compile($input, $dir = NULL)
	{
		$this->rawInput = $input;
		$this->basePath = $path;

		$this->getImages();
		$this->callFolders();

		/** @var FoldDescriptor */
		$bestFolder = $this->analyzer->compare($this->foldersOutput);
		
		$this->composer->compose($bestFolder);

		return $this->cssWriter->write($bestFolder);
	}

	/**
	 * Get all images files from source CSS.
	 * @return bool
	 */
	protected function getImages()
	{
		preg_match_all('~\bbackground(-image)?\s*:(.*?)url\s*\(\s*(\'|")?(?<image>.*?)\3?\s*\)~i', $this->input, $matches);
		$images = array();
		foreach ($matches['image'] as $image)
			if (!(substr($image,0,5)==="data:") && !(strpos($image,"base64")))
				$images[] = $image;
		$this->images = array_unique($images);
		return TRUE;
	}

	protected function callFolders()
	{
		foreach ($this->folders as $name => $folder)
			$this->foldersOutput[$name] = $folder($this->images);
	}

	public function addFolder(IFolder $folder)
	{
		if (!($folder instanceof IFolder))
			throw new InvalidArgumentException("Argument \$folder must be instance of IFolder!");
		$name = explode("\\",get_class($folder));
		$name = lcfirst(array_pop($name));
		$this->folders[$name] = call_user_func(array($folder, 'generate'));
		return $this;
	}

	public function setConfig($config = array())
	{
		foreach ($config as $key => $value)
			$this->$key = $value;
	}
}