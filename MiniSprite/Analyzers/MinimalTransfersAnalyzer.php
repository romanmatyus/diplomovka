<?php 

namespace MiniSprite;

/**
* @author Roman Mátyus
* @copyright (c) Roman Mátyus 2014
* @license MIT
*/
class MinimalTransfersAnalyzer implements IAnalyzer
{
	public function getBest(array $collections)
	{
		$winner = NULL;
		foreach ($collections as $collection) {
			$count = 0;
			foreach ($collection as $fold)
				$count += count($fold->getImages());

			if (isset($winnerCount)) {
				if ($count>$winnerCount) {
					$winnerCount = $count;
					$winner = $collection;
				}
			} else {
				$winnerCount = $count;
				$winner = $collection;
			}
		}
		return $winner;
	}
}
