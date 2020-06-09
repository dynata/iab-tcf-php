<?php

namespace IABTcf;

use IABTcf\Exceptions\FieldNotFoundException;
use IABTcf\Exceptions\UnsupportedSegmentException;
use IABTcf\Exceptions\UnsupportedVersionException;
use IABTcf\TCFv1;
use IABTcf\TCFv2;

class Definitions implements DefinitionsIFace
{
	private $vendorVersionMap;

	/**
	 * Definitions constructor.
	 * @param int $version
	 * @throws UnsupportedVersionException
	 */
	public function __construct(int $version) {
		$this->vendorVersionMap = self::getVendorVersionMap($version);
	}
	/**
	 * @return int
	 */
	public static function getSegmentNumBits(): int
	{
		return 3;
	}

	/**
	 * @return int
	 */
	public static function getVersionNumBits(): int
	{
		return 6;
	}

	/**
	 * @param int $version
	 * @return array
	 * @throws UnsupportedVersionException
	 */
	public static function getVendorVersionMap(int $version): array
	{
		switch ($version) {
			case 1:
				return TCFv1\Definitions::getVendorVersionMap();
			case 2:
				return TCFv2\Definitions::getVendorVersionMap();
			default:
				throw new UnsupportedVersionException();
		}
	}

	/**
	 * @param int $segmentId
	 * @param string $fieldKey
	 * @return Field
	 * @throws FieldNotFoundException
	 * @throws UnsupportedSegmentException
	 */
	public function fetchField(int $segmentId, string $fieldKey): Field {
		if (! isset($this->vendorVersionMap[$segmentId])) {
			throw new UnsupportedSegmentException();
		}
		foreach ($this->vendorVersionMap[$segmentId]['fields'] as $field) {
			if ($field->getName() === $fieldKey) {
				return $field;
			}
		}
		throw new FieldNotFoundException();
	}
}