<?php

namespace IABTcf;

use IABTcf\Exceptions\InvalidConsentStringException;
use IABTcf\Exceptions\InvalidSegmentException;
use IABTcf\Exceptions\InvalidVersionException;
use IABTcf\Utils\Bits;
use IABTcf\TCFv1;
use IABTcf\TCFv2;
use IABTcf\Exceptions\UnsupportedVersionException;

class Decoder implements DecoderIFace
{
	private function __construct(){}

	/**
	 * @param $consentString
	 * @return ConsentString
	 * @throws UnsupportedVersionException
	 * @throws InvalidVersionException
	 * @throws InvalidConsentStringException
	 * @throws InvalidSegmentException
	 */
	public static function decode($consentString): ConsentString
	{
		$csSplit = explode(".", $consentString);
		$version = Bits::extractVersion(Bits::decodeFromBase64($csSplit[0]));
		switch($version) {
			case 1:
				return TCFv1\Decoder::decode($csSplit);
			case 2:
				return TCFv2\Decoder::decode($csSplit);
			default:
				throw new UnsupportedVersionException();
		}
	}
}