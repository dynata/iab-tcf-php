<?php

namespace IABTcf;

interface DefinitionsIFace {
	public static function getVersionNumBits(): int;
	public static function getSegmentNumBits(): int;
	public static function getVendorVersionMap(int $version): array;
	public function fetchField(int $segmentId, string $fieldKey): Field;
}