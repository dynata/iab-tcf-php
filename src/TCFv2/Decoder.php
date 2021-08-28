<?php

namespace IABTcf\TCFv2;

use IABTcf\ConsentString;
use IABTcf\Exceptions\InvalidConsentStringException;
use IABTcf\Exceptions\InvalidSegmentException;
use IABTcf\Utils\Bits;
use IABTcf\Utils\Range;

abstract class Decoder {

	/**
	 * @param array $csSplit
	 * @return ConsentString
	 * @throws InvalidConsentStringException
	 * @throws InvalidSegmentException
	 */
	public static function decode(array $csSplit): ConsentString
	{
		$versionMap = Definitions::getVendorVersionMap();
		$returnValue = [];
		foreach ($csSplit as $k => $v) {
			$bitString = Bits::decodeFromBase64($v);
			// decode the segment first
			if ($k !== 0) {
				$segmentId = Bits::extractSegment($bitString);
			} else {
				// core string doesn't store a segment
				$segmentId = 0;
			}
			if (! isset($versionMap[$segmentId])) {
				// no values assigned to segment, skip
				continue;
			}
			$decoded = Bits::decodeConsentStringBitValue($bitString, $versionMap[$segmentId]);
			switch ($segmentId) {
				case 0:
					$returnValue['version'] = $decoded['version'];
					$returnValue['created'] = $decoded['created'];
					$returnValue['lastUpdated'] = $decoded['lastUpdated'];
					$returnValue['cmpId'] = $decoded['cmpId'];
					$returnValue['cmpVersion'] = $decoded['cmpVersion'];
					$returnValue['consentScreen'] = $decoded['consentScreen'];
					$returnValue['consentLanguage'] = $decoded['consentLanguage'];
					$returnValue['vendorListVersion'] = $decoded['vendorListVersion'];
					$returnValue['tcfPolicyVersion'] = $decoded['tcfPolicyVersion'];
					$returnValue['isServiceSpecific'] = $decoded['isServiceSpecific'];
					$returnValue['useNonStandardStacks'] = $decoded['useNonStandardStacks'];
					$returnValue['specialFeatureOptIns'] = Bits::decodeBitsToIds($decoded['specialFeatureOptIns']);
					$returnValue['purposeOneTreatment'] = $decoded['purposeOneTreatment'];
					$returnValue['publisherCC'] = $decoded['publisherCC'];
					$returnValue['purposesConsentIds'] = Bits::decodeBitsToIds($decoded['purposesConsentBitString']);
					$returnValue['purposesLITransparency'] = Bits::decodeBitsToIds($decoded['purposesLITransparencyBitString']);
					if (! empty($decoded['pubRestrictions'])) {
						$returnValue['pubRestrictions'] = [];
						foreach ($decoded['pubRestrictions'] as $pubRestriction) {
							if (! empty($pubRestriction['vendorIds'])) {
								$restriction = new RestrictionType($pubRestriction['restrictionType']);
								$vendorIds = [];
								foreach ($pubRestriction['vendorIds'] as $vendorId) {
									if ($vendorId['isARange']) {
										for ($i = $vendorId['startId']; $i <= $vendorId['endId']; $i++) {
											$vendorIds[] = $i;
										}
									} else {
										$vendorIds[] = $vendorId['startId'];
									}
								}
								$returnValue['pubRestrictions'][] = new PublisherRestriction($pubRestriction['purposeId'], $restriction, $vendorIds);
							}
						}
					}
					if ($decoded['vendorConsentIsRangeEncoding']) {
						$returnValue['vendorConsentIds'] = Range::decodeRange($decoded['vendorConsentMaxVendorId'], $decoded['vendorConsentRangeList'], false);
					} else {
						$returnValue['vendorConsentIds'] = Bits::decodeBitsToIds($decoded['vendorConsentBitString']);
					}
					if ($decoded['legitimateInterestsIsRangeEncoding']) {
						$returnValue['vendorLegitimateInterests'] = Range::decodeRange($decoded['legitimateInterestsMaxVendorId'], $decoded['legitimateInterestsVendorRangeList'], false);
					} else {
						$returnValue['vendorLegitimateInterests'] = Bits::decodeBitsToIds($decoded['legitimateInterestsVendorIdBitString']);
					}
					break;
				case 1:
					if ($decoded['isRangeEncoding']) {
						$returnValue['disclosedVendorIds'] = Range::decodeRange($decoded['maxVendorId'], $decoded['vendorRangeList'], false);
					} else {
						$returnValue['disclosedVendorIds'] = Bits::decodeBitsToIds($decoded['vendorBits']);
					}
					break;
				case 2:
					if ($decoded['isRangeEncoding']) {
						$returnValue['allowedVendorIds'] = Range::decodeRange($decoded['maxVendorId'], $decoded['vendorRangeList'], false);
					} else {
						$returnValue['allowedVendorIds'] = Bits::decodeBitsToIds($decoded['vendorBits']);
					}
					break;
				case 3:
					$returnValue['pubPurposesConsent'] = Bits::decodeBitsToIds($decoded['pubPurposesConsent']);
					$returnValue['pubPurposesLITransparency'] = Bits::decodeBitsToIds($decoded['pubPurposesLITransparency']);
					$returnValue['customPurposesConsent'] = Bits::decodeBitsToIds($decoded['customPurposesConsent']);
					$returnValue['customPurposesLITransparency'] = Bits::decodeBitsToIds($decoded['customPurposesLITransparency']);
					break;
			}
		}

		return new ConsentString($returnValue);
	}
}