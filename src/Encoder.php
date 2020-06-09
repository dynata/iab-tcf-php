<?php

namespace IABTcf;

use IABTcf\Exceptions\UnsupportedVersionException;
use IABTcf\TCFv1;
use IABTcf\TCFv2;

class Encoder implements EncoderIFace {
	private function __construct(){}

	/**
	 * @param ConsentString $consentString
	 * @return string
	 * @throws Exceptions\FieldNotFoundException
	 * @throws Exceptions\InvalidEncodingTypeException
	 * @throws Exceptions\UnsupportedSegmentException
	 * @throws UnsupportedVersionException
	 */
	public static function encode(ConsentString $consentString): string
	{
		switch ($consentString->getVersion()) {
			case 1:
				return TCFv1\Encoder::encode($consentString);
			case 2:
				return TCFv2\Encoder::encode($consentString);
			default:
				throw new UnsupportedVersionException();
		}
	}
}