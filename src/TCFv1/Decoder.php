<?php

namespace IABTcf\TCFv1;

use IABTcf\ConsentString;
use IABTcf\Exceptions\InvalidConsentStringException;
use IABTcf\Utils\Bits;
use IABTcf\Utils\Range;

abstract class Decoder {

	/**
	 * @param array $csSplit
	 * @return ConsentString
	 * @throws InvalidConsentStringException
	 */
	public static function decode(array $csSplit): ConsentString
	{
		$versionMap = Definitions::getVendorVersionMap();
		$decoded = Bits::decodeConsentStringBitValue(Bits::decodeFromBase64($csSplit[0]), $versionMap[0]);
		$decoded['purposesConsentIds'] = Bits::decodeBitsToIds($decoded['purposeIdBitString']);
		if ($decoded['vendorConsentIsRangeEncoding']) {
			$decoded['vendorConsentIds'] = Range::decodeRange($decoded['vendorConsentMaxVendorId'], $decoded['vendorConsentRangeList'] , $decoded['defaultConsent']);
		} else {
			$decoded['vendorConsentIds'] = Bits::decodeBitsToIds($decoded['vendorIdBitString']);
		}

		return new ConsentString($decoded);
	}
}