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

	/** @var string */
	protected $basePath;

	/** NEEDED OBJECTS */

	/** @var array of FoldDescriptor */
	protected $foldersOutput = array();

	/** @var IAnalyzer */
	protected $analyzer;

	/** @var IComposer */
	protected $composer;

	/** @var ICssWriter */
	protected $cssWriter;

	/** INTERNAL VARIABLES */

	/** @var string */
	protected $rawInput;

	/** @var array of Image */
	protected $images = array();

	/** @var array of IFolder */
	protected $folders = array();

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
	public function compile($input, $path = NULL)
	{
		$this->rawInput = $input;
		if (!is_null($path))
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
		preg_match_all('/\s*(?<block>[^\{]*\{[^\}]*\})\s*/i', $this->rawInput, $blocks);
		foreach ($blocks["block"] as $block) {
			preg_match_all('~\bbackground(-image)?\s*:(.*?)url\s*\(\s*(\'|")?(?<image>.*?)\3?\s*\)~i', $block, $matches);
			foreach ($matches['image'] as $image)
				if (!(substr($image,0,5)==="data:") && !(strpos($image,"base64"))) {
					$this->images[] = new Image($image, new CssBlock($cssDef));
				}
		}
		return $this->images;
	}

	protected function callFolders()
	{
		foreach ($this->folders as $name => $folder)
			$this->foldersOutput[$name] = $folder($this->images);
	}

	public function setConfig($config = array())
	{
		foreach ($config as $key => $value)
			$this->$key = $value;
	}

	/**
	 * Setter of Base Path.
	 * @param string $basePath
	 * @return MiniSprite
	 */
	public function setBasePath($basePath)
	{
		if (empty($basePath))
			throw new InvalidArgumentException("Argument \$basePath not be empty");
		if (is_string($basePath))
			throw new InvalidArgumentException("Argument \$basePath must be string");
		$this->basePath = $basePath;
		return $this;
	}

	/**
	 * Setter names of sprite files.
	 * @param string $outputNormal
	 * @param string $outputHorizontal
	 * @param string $outputVertical
	 * @return MiniSprite
	 */
	public function setOutput($outputNormal = NULL, $outputHorizontal = NULL, $outputVertical = NULL)
	{
		foreach (array("outputNormal", "outputHorizontal", "outputVertical") as $variable) {
			if (!is_null($$variable)) {
				if (is_string($$variable))
					throw new InvalidArgumentException("Argument \$" . $variable . " must be string");
				$this->$variable = $$variable;
			}
		}
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
		$this->folders[$name] = call_user_func(array($folder, 'generate'));
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
	 * Set Composer for generating sprite images.
	 * @param IComposer $composer
	 * @return MiniSprite
	 */
	public function setComposer(IComposer $composer)
	{
		$this->composer = $composer;
	}

	/**
	 * Set Css Writer for writing sprite changes to css.
	 * @param ICssWriter $cssWriter
	 * @return MiniSprite
	 */
	public function setCssWriter(ICssWriter $cssWriter)
	{
		$this->cssWriter = $cssWriter;
	}
}