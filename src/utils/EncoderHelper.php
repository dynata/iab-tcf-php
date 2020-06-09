<?php

namespace IABTcf\Utils;

use IABTcf\Definitions;
use IABTcf\Exceptions\FieldNotFoundException;
use IABTcf\Exceptions\InvalidEncodingTypeException;
use IABTcf\Exceptions\UnsupportedSegmentException;

class EncoderHelper {

	/**
	 * Convert a list of IDs to ranges
	 *
	 * @param array $list List of IDs
	 * @return array
	 */
	public static function convertListToRanges(array $list): array
	{
		sort($list);
		$listCount = count($list);
		$range = $ranges = [];
		for ($index = 0; $index < $listCount; $index++) {
			$vendorId = $list[$index];
			$range[] = $vendorId;
			// Do we need to close the current range?
			if (
				(
					$index === ($listCount - 1) // There is no more vendor to evaluate
					|| $list[$index+1] !== ($vendorId + 1) // gap in vendors
				)
				&& count($range)
			) {
				$startId = array_shift($range);
				$endId = array_pop($range);
				$range = [];
				$ranges[] = [
					'isARange' => is_int($endId),
					'startId' => $startId,
					'endId' => $endId,
				];
			}
		}

		return $ranges;
	}

	/**
	 * Encode a list of IDs into bits
	 *
	 * @param int $maxId
	 * @param array $allowedIds IDs that the user has given consent to
	 * @return string
	 */
	public static function encodeIdsToBits(int $maxId, array $allowedIds = []): string
	{
		$returnValue = "";
		for ($id = 1; $id <= $maxId; $id++) {
			$returnValue .= (array_search($id, $allowedIds) !== false) ? '1' : '0';
		}

		return Bits::padRight($returnValue, max([0, $maxId - strlen($returnValue)]));
	}

	/**
	 * @param int $segmentId
	 * @param string $fieldKey
	 * @param $fieldValue
	 * @param Definitions $definitions
	 * @param bool $validate
	 * @param array $extras
	 * @return string
	 * @throws FieldNotFoundException
	 * @throws InvalidEncodingTypeException
	 * @throws UnsupportedSegmentException
	 */
	public static function encodeField(Definitions $definitions, int $segmentId, string $fieldKey, $fieldValue, bool $validate = true, $extras = []): string
	{
		return Bits::encodeField(array_merge([$fieldKey => $fieldValue], $extras), $definitions->fetchField($segmentId, $fieldKey), $validate);
	}
}