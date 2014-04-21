<?php 

namespace MiniSprite;

/**
* @author Roman Mátyus
* @copyright (c) Roman Mátyus 2014
* @license MIT
*/
class MinimalAreaAnalyzer implements IAnalyzer
{
	public function getBest(array $collections)
	{
		$winner = NULL;
		foreach ($collections as $collection) {
			$area = 0;
			foreach ($collection as $fold)
				$area += $fold->getWidth() * $fold->getHeight();

			if (isset($winnerArea)) {
				if ($area<$winnerArea) {
					$winnerArea = $area;
					$winner = $collection;
				}
			} else {
				$winnerArea = $area;
				$winner = $collection;
			}
		}
		return $winner;
	}
}
