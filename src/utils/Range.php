<?php

namespace IABTcf\Utils;

abstract class Range {
	/**
	 * @param int $maxId
	 * @param array $rangeList
	 * @param bool $defaultConsent
	 * @return array
	 */
	public static function decodeRange(int $maxId, array $rangeList, $defaultConsent = false): array
	{
		$reduce = function ($acc, $el) {
			$lastId = $el['isARange'] ? $el['endId'] : $el['startId'];
			for ($i = $el['startId']; $i <= $lastId; $i++) {
				$acc[$i] = true;
			}
			return $acc;
		};
		$idMap = array_reduce($rangeList, $reduce, []);
		$allowedIds = [];
		for ($i = 1; $i <= $maxId; $i++) {
			if (
				(
					$defaultConsent && (! isset($idMap[$i]) || ! $idMap[$i]) ||
					! $defaultConsent && isset($idMap[$i]) && $idMap[$i]
				) && array_search($i, $allowedIds) === false
			) {
				$allowedIds[] = $i;
			}
		}

		return $allowedIds;
	}
}