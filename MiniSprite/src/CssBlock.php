<?php 

namespace MiniSprite;

/**
* CSS Block prototype for MiniSprite generator.
*
* @author Roman Mátyus
* @copyright (c) Roman Mátyus 2014
* @license MIT
*/
class CssBlock
{
	/** @var string */
	protected $origin;

	/** @var string */
	protected $selector;

	/** @var array */
	private $parameters = array();

	/** @var array of color names from W3C specs */
	private $colorNames = array(
		"AliceBlue", "AntiqueWhite", "Aqua", "Aquamarine", "Azure", "Beige", "Bisque", "Black", "BlanchedAlmond", "Blue", "BlueViolet", "Brown", "BurlyWood", "CadetBlue", "Chartreuse", "Chocolate", "Coral", "CornflowerBlue", "Cornsilk", "Crimson", "Cyan", "DarkBlue", "DarkCyan", "DarkGoldenRod", "DarkGray", "DarkGreen", "DarkKhaki", "DarkMagenta", "DarkOliveGreen", "DarkOrange", "DarkOrchid", "DarkRed", "DarkSalmon", "DarkSeaGreen", "DarkSlateBlue", "DarkSlateGray", "DarkTurquoise", "DarkViolet", "DeepPink", "DeepSkyBlue", "DimGray", "DodgerBlue", "FireBrick", "FloralWhite", "ForestGreen", "Fuchsia", "Gainsboro", "GhostWhite", "Gold", "GoldenRod", "Gray", "Green", "GreenYellow", "HoneyDew", "HotPink", "IndianRed ", "Indigo ", "Ivory", "Khaki", "Lavender", "LavenderBlush", "LawnGreen", "LemonChiffon", "LightBlue", "LightCoral", "LightCyan", "LightGoldenRodYellow", "LightGray", "LightGreen", "LightPink", "LightSalmon", "LightSeaGreen", "LightSkyBlue", "LightSlateGray", "LightSteelBlue", "LightYellow", "Lime", "LimeGreen", "Linen", "Magenta", "Maroon", "MediumAquaMarine", "MediumBlue", "MediumOrchid", "MediumPurple", "MediumSeaGreen", "MediumSlateBlue", "MediumSpringGreen", "MediumTurquoise", "MediumVioletRed", "MidnightBlue", "MintCream", "MistyRose", "Moccasin", "NavajoWhite", "Navy", "OldLace", "Olive", "OliveDrab", "Orange", "OrangeRed", "Orchid", "PaleGoldenRod", "PaleGreen", "PaleTurquoise", "PaleVioletRed", "PapayaWhip", "PeachPuff", "Peru", "Pink", "Plum", "PowderBlue", "Purple", "Red", "RosyBrown", "RoyalBlue", "SaddleBrown", "Salmon", "SandyBrown", "SeaGreen", "SeaShell", "Sienna", "Silver", "SkyBlue", "SlateBlue", "SlateGray", "Snow", "SpringGreen", "SteelBlue", "Tan", "Teal", "Thistle", "Tomato", "Turquoise", "Violet", "Wheat", "White", "WhiteSmoke", "Yellow", "YellowGreen",
		);

	public function __construct($origin)
	{
		$this->origin = $origin;

		preg_match_all('/\s*(?<selector>[^\{]+)\{[^\}]+\}\s*/i', $origin, $selector);
		$this->selector = trim($selector["selector"][0]);

		preg_match_all('/[a-zA-Z-]+\s*:\s*[^;]*/i', trim($origin), $pairs);
		foreach ($pairs[0] as $pair) {
			preg_match_all('/(?<parameter>[^:]*):(?<value>[^\}]*)/i', trim($pair), $def);
			$this->parameters[trim($def["parameter"][0])] = trim($def["value"][0]);
			if (trim($def["parameter"][0])==="background")
				$this->updateBackgroundParams();
		}
	}

	public function getOrigin()
	{
		return $this->origin;
	}

	static public function parseBackgroundPosition($string)
	{
		$regularPositionHorizontal = '(?<horizontal>(left|center|right|\-?\d{1,}\s*(%|px)?)?)?';
		$regularPositionVertical = '(?<vertical>(top|center|bottom|\-?\d{1,}\s*(%|px)?)?)?';
		
		preg_match_all('/\s*'.$regularPositionHorizontal.'\s*'.$regularPositionVertical.'/i', $string, $position);

		return array(
			"horizontal" => trim($position["horizontal"][0]),
			"vertical" => trim($position["vertical"][0]),
		);
	}

	public function __set($name, $value)
	{
		$this->parameters[$name] = $value;
		if (preg_match("/background-(color|image|position|repeat|attachment)/i",$name)) {
			if (preg_match("/background\-position/i",$name)) {
				$p = array();
				foreach (self::parseBackgroundPosition($value) as $type => $val) {
					if ($val!="") {
						if (in_array(trim($val),array("top", "left", "0px")))
							$val = "0";
						$p[$type] = trim($val);
					}
				}
				$this->parameters[$name] = implode(" ", $p);
			}
			$this->updateBackground();
		}elseif ($name==="background") {
			$this->updateBackgroundParams();
		}
	}

	public function &__get($name){
		$null = NULL;
		if (isset($this->parameters[$name]))
			return $this->parameters[$name];
		else
			return $null;
	}

	public function __unset($name)
	{
		unset($this->parameters[$name]);
		if (preg_match("/background-(color|image|position|repeat|attachment)/i",$name)) {
			$this->updateBackground();
		}elseif ($name==="background") {
			unset($this->parameters["background-color"]);
			unset($this->parameters["background-image"]);
			unset($this->parameters["background-position"]);
			unset($this->parameters["background-repeat"]);
			unset($this->parameters["background-attachment"]);
		}

	}

	public function __toString()
	{
		$output = $this->selector . " {\n";
		foreach ($this->parameters as $parameter => $value) {
			if (!preg_match("/background-(color|image|position|repeat|attachment)/i",$parameter))
				$output .= "\t" . $parameter . ": " . $value . ";\n";
		};
		$output .= "}\n";
		return $output;
	}

	private function updateBackground()
	{
		$this->parameters["background"] = implode(" ", array_filter(array(
			$this->{"background-color"},
			$this->{"background-image"},
			$this->{"background-position"},
			$this->{"background-repeat"},
			$this->{"background-attachment"},
		)));
	}

	private function updateBackgroundParams()
	{
		$r255 = '(1{0,1}(\d{1,2})|(2[0-5]{2}))'; // 0 - 255
		$r1 = '((0)|(0?\.\d{1,})|1)'; // 0, 0.1 - 1
		$r360 = '(0|(1|2)?\d{1,2}|3[0-5]\d|360)'; // 0 - 360
		$r100p = '(0|\d{1,2}|100)\s*%\s*'; // 0 - 100 %

		$regularColorNames = implode('|',$this->colorNames);
		$regularColorRgb = 'rgb\s*\((\s*'.$r255.'\s*,){2}\s*'.$r255.'\s*\)';
		$regularColorRgba = 'rgba\s*\((\s*'.$r255.'\s*,){3}\s*'.$r1.'\s*\)';
		$regularColorHsl = 'hsl\s*\(\s*'.$r360.'\s*,\s*'.$r100p.'\s*,\s*'.$r100p.'\s*\)';
		$regularColorHsla = 'hsla\s*\(\s*'.$r360.'\s*(,\s*'.$r100p.'\s*){2},\s*'.$r1.'\s*\)';
		$regularColorTransparent = 'transparent';
		$regularColor = '(?<color>('.$regularColorNames.'|'.$regularColorRgb.'|'.$regularColorRgba.'|'.$regularColorHsl.'|'.$regularColorHsla.'|'.$regularColorTransparent.')?)';

		$regularImage = '(?<image>((url\([^\)]*\))|none)?)';
		
		$regularPositionHorizontal = '(left|center|right|\-?\d{1,}\s*(%|px)?)?';
		$regularPositionVertical = '(top|center|bottom|\-?\d{1,}\s*(%|px)?)?';
		$regularPosition = '(?<position>'.$regularPositionHorizontal.'\s*'.$regularPositionVertical.')';
		
		$regularRepeat = '(?<repeat>(no\-repeat|repeat\-x|repeat\-y|repeat)?)';

		$regularAttachment = '(?<attachment>(scroll|fixed|local)?)';

		preg_match_all('/\s*'.$regularColor.'\s*'.$regularImage.'\s*'.$regularPosition.'\s*'.$regularRepeat.'\s*'.$regularAttachment.'/i', $this->background, $parts);

		if (!empty($parts["color"][0]))
			$this->{"background-color"} = trim($parts["color"][0]);
		if (!empty($parts["image"][0]))
			$this->{"background-image"} = trim($parts["image"][0]);
		if (!empty($parts["position"][0])) {
			$this->{"background-position"} = trim(str_replace(
				array(
					"top",
					"left",
				), "0", strtolower($parts["position"][0])));
		}
		if (!empty($parts["repeat"][0]))
			$this->{"background-repeat"} = trim($parts["repeat"][0]);
		if (!empty($parts["attachment"][0]))
			$this->{"background-attachment"} = trim($parts["attachment"][0]);

	}
}