<?php

namespace IABTcf\TCFv1;

use IABTcf\ConsentString;
use IABTcf\Definitions;
use IABTcf\Exceptions\FieldNotFoundException;
use IABTcf\Exceptions\InvalidEncodingTypeException;
use IABTcf\Exceptions\UnsupportedSegmentException;
use IABTcf\Exceptions\UnsupportedVersionException;
use IABTcf\Utils\Bits;
use IABTcf\Gvl\VendorList;
use IABTcf\Utils\EncoderHelper;
use IABTcf\Utils\VendorFieldEncoder;

class Encoder {

	/**
	 * @param ConsentString $consentString
	 * @param VendorList $gvl
	 * @return string
	 * @throws UnsupportedVersionException
	 * @throws FieldNotFoundException
	 * @throws InvalidEncodingTypeException
	 * @throws UnsupportedSegmentException
	 */
	public static function encode(ConsentString $consentString): string
	{
		$definitions = new Definitions(1);
		$bitString  = EncoderHelper::encodeField($definitions, 0, 'version', $consentString->getVersion());
		$bitString .= EncoderHelper::encodeField($definitions, 0, 'created', $consentString->getCreated());
		$bitString .= EncoderHelper::encodeField($definitions, 0, 'lastUpdated', $consentString->getLastUpdated());
		$bitString .= EncoderHelper::encodeField($definitions, 0, 'cmpId', $consentString->getCmpId());
		$bitString .= EncoderHelper::encodeField($definitions, 0, 'cmpVersion', $consentString->getCmpVersion());
		$bitString .= EncoderHelper::encodeField($definitions, 0, 'consentScreen', $consentString->getConsentScreen());
		$bitString .= EncoderHelper::encodeField($definitions, 0, 'consentLanguage', $consentString->getConsentLanguage());
		$bitString .= EncoderHelper::encodeField($definitions, 0, 'vendorListVersion', $consentString->getVendorListVersion());
		$bitString .= EncoderHelper::encodeIdsToBits($definitions->fetchField(0, 'purposeIdBitString')->getNumBits()(), $consentString->getAllowedPurposes());
		$vendorFieldEncoder = new VendorFieldEncoder(
			$definitions,
			$consentString->getAllowedVendors(),
			$consentString->getDefaultVendorConsent(),
			false,
			true,
			true
		);
		$bitString .= $vendorFieldEncoder->build(true);

		return Bits::encodeBitStringToBase64($bitString);
	}
}