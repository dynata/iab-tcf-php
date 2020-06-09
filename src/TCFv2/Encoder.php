<?php

namespace IABTcf\TCFv2;

use IABTcf\Definitions;
use IABTcf\ConsentString;
use IABTcf\Exceptions\FieldNotFoundException;
use IABTcf\Exceptions\InvalidEncodingTypeException;
use IABTcf\Exceptions\UnsupportedSegmentException;
use IABTcf\Exceptions\UnsupportedVersionException;
use IABTcf\Utils\Bits;
use IABTcf\Utils\AllowedDisclosedVendorFieldEncoder;
use IABTcf\Utils\EncoderHelper;
use IABTcf\Utils\VendorFieldEncoder;
use IABTcf\Utils\VendorLitFieldEncoder;

class Encoder {

	/**
	 * @param ConsentString $consentString
	 * @return string
	 * @throws UnsupportedVersionException
	 * @throws FieldNotFoundException
	 * @throws InvalidEncodingTypeException
	 * @throws UnsupportedSegmentException
	 */
	public static function encode(ConsentString $consentString): string
	{
		$definitions = new Definitions(2);

		$strings = [];
		$coreString = self::buildCoreString($consentString, $definitions);
		$disclosedVendorsString = self::buildDisclosedVendorsString($definitions, $consentString);
		$allowedVendorsString = self::buildAllowedVendorsString($definitions, $consentString);
		$publisherTcString = self::buildPublisherTcString($definitions, $consentString);

		$strings[] = $coreString;
		if ($disclosedVendorsString !== "") {
			$strings[] = $disclosedVendorsString;
		}
		if ($allowedVendorsString !== "") {
			$strings[] = $allowedVendorsString;
		}
		if ($publisherTcString !== "") {
			$strings[] = $publisherTcString;
		}

		return implode(".", $strings);
	}

	/**
	 * @param ConsentString $consentString
	 * @param Definitions $definitions
	 * @return string
	 * @throws FieldNotFoundException
	 * @throws InvalidEncodingTypeException
	 * @throws UnsupportedSegmentException
	 */
	public static function buildCoreString(ConsentString $consentString, Definitions $definitions): string
	{
		$bitString  = EncoderHelper::encodeField($definitions, 0, 'version', $consentString->getVersion());
		$bitString .= EncoderHelper::encodeField($definitions, 0, 'created', $consentString->getCreated());
		$bitString .= EncoderHelper::encodeField($definitions, 0, 'lastUpdated', $consentString->getLastUpdated());
		$bitString .= EncoderHelper::encodeField($definitions, 0, 'cmpId', $consentString->getCmpId());
		$bitString .= EncoderHelper::encodeField($definitions, 0, 'cmpVersion', $consentString->getCmpVersion());
		$bitString .= EncoderHelper::encodeField($definitions, 0, 'consentScreen', $consentString->getConsentScreen());
		$bitString .= EncoderHelper::encodeField($definitions, 0, 'consentLanguage', $consentString->getConsentLanguage());
		$bitString .= EncoderHelper::encodeField($definitions, 0, 'vendorListVersion', $consentString->getVendorListVersion());
		$bitString .= EncoderHelper::encodeField($definitions, 0, 'tcfPolicyVersion', $consentString->getTcfPolicyVersion());
		$bitString .= EncoderHelper::encodeField($definitions, 0, 'isServiceSpecific', $consentString->getIsServiceSpecific());
		$bitString .= EncoderHelper::encodeField($definitions, 0, 'useNonStandardStacks', $consentString->getUseNonStandardStacks());
		$bitString .= EncoderHelper::encodeField($definitions, 0, 'specialFeatureOptIns', implode('', $consentString->getSpecialFeatureOptIns()));
		$bitString .= EncoderHelper::encodeIdsToBits($definitions->fetchField(0, 'purposesConsentBitString')->getNumBits()(), $consentString->getAllowedPurposes());
		$bitString .= EncoderHelper::encodeIdsToBits($definitions->fetchField(0, 'purposesLITransparencyBitString')->getNumBits()(), $consentString->getPurposesLITransparency());

		$bitString .= EncoderHelper::encodeField($definitions, 0, 'purposeOneTreatment', $consentString->getPurposeOneTreatment());
		$bitString .= EncoderHelper::encodeField($definitions, 0, 'publisherCC', $consentString->getPublisherCC());
		$vendorFieldEncoder = new VendorFieldEncoder(
			$definitions,
			$consentString->getVendorConsent(),
			$consentString->getDefaultVendorConsent(),
			false,
			true,
			true
		);
		$bitString .= $vendorFieldEncoder->build(false);
		$vendorFieldLitEncoder = new VendorLitFieldEncoder(
			$definitions,
			$consentString->getVendorLegitimateInterest(),
			$consentString->getDefaultVendorConsent(),
			false,
			true,
			true
		);

		$bitString .= $vendorFieldLitEncoder->build();
		$publisherRestrictions = $consentString->getPublisherRestrictions();
		$publisherRestrictionsCount = count($publisherRestrictions);
		$bitString .= EncoderHelper::encodeField($definitions, 0, 'numPubRestrictions', $publisherRestrictionsCount);
		if ($publisherRestrictionsCount > 0) {
			// make the publisher restrictions readable to the encoder
			$pubRestrictionList = [];
			foreach ($publisherRestrictions as $publisherRestriction) {
				$ranges = EncoderHelper::convertListToRanges($publisherRestriction->getVendorIds());
				$pubRestrictionList[] = [
					'purposeId' => $publisherRestriction->getPurposeId(),
					'restrictionType' => $publisherRestriction->getRestrictionType()->getId(),
					'numEntries' => count($ranges),
					'vendorIds' => $ranges,
				];
			}
			$bitString .= EncoderHelper::encodeField($definitions, 0, 'pubRestrictions', $pubRestrictionList, false);
		}

		return Bits::encodeBitStringToBase64($bitString);
	}

	/**
	 * @param Definitions $definitions
	 * @param ConsentString $consentString
	 * @return string
	 * @throws FieldNotFoundException
	 * @throws InvalidEncodingTypeException
	 * @throws UnsupportedSegmentException
	 */
	public static function buildDisclosedVendorsString(Definitions $definitions, ConsentString $consentString): string
	{
		$bitString = EncoderHelper::encodeField($definitions, 1, 'segmentType', 1);
		$disclosedVendorFieldEncoder = new AllowedDisclosedVendorFieldEncoder(
			$definitions,
			$consentString->getDisclosedVendors(),
			$consentString->getDefaultVendorConsent(),
			false,
			true,
			true
		);
		$bitString .= $disclosedVendorFieldEncoder->build(1);

		return Bits::encodeBitStringToBase64($bitString);
	}

	/**
	 * @param Definitions $definitions
	 * @param ConsentString $consentString
	 * @return string
	 * @throws FieldNotFoundException
	 * @throws InvalidEncodingTypeException
	 * @throws UnsupportedSegmentException
	 */
	public static function buildAllowedVendorsString(Definitions $definitions, ConsentString $consentString): string
	{
		$bitString = EncoderHelper::encodeField($definitions, 2, 'segmentType', 2);
		$allowedDisclosedVendorFieldEncoder = new AllowedDisclosedVendorFieldEncoder(
			$definitions,
			$consentString->getAllowedVendors(),
			$consentString->getDefaultVendorConsent(),
			false,
			true,
			true
		);
		$bitString .= $allowedDisclosedVendorFieldEncoder->build(2);

		return Bits::encodeBitStringToBase64($bitString);
	}

	/**
	 * @param Definitions $definitions
	 * @param ConsentString $consentString
	 * @return string
	 * @throws FieldNotFoundException
	 * @throws InvalidEncodingTypeException
	 * @throws UnsupportedSegmentException
	 */
	public static function buildPublisherTcString(Definitions $definitions, ConsentString $consentString): string
	{
		$bitString  = EncoderHelper::encodeField($definitions, 3, 'segmentType', 3);
		$bitString .= EncoderHelper::encodeIdsToBits($definitions->fetchField(3, 'pubPurposesConsent')->getNumBits()(), $consentString->getPubPurposesConsent());
		$bitString .= EncoderHelper::encodeIdsToBits($definitions->fetchField(3, 'pubPurposesLITransparency')->getNumBits()(), $consentString->getPubPurposesLITransparency());
		$customPurposes = $consentString->getCustomPurposesConsent();
		$customPurposesLiTransparency = $consentString->getCustomPurposesLITransparency();
		$numCustomPurposes = 0;
		if (count($customPurposes) > 0) {
			$numCustomPurposes = max($customPurposes);
		}
		if (count($customPurposesLiTransparency) > 0) {
			$numCustomPurposes = max($numCustomPurposes, max($customPurposesLiTransparency));
		}
		$bitString .= EncoderHelper::encodeField($definitions, 3, 'numCustomPurposes', $numCustomPurposes);
		if ($numCustomPurposes > 0) {
			$bitString .= EncoderHelper::encodeIdsToBits($numCustomPurposes, $consentString->getCustomPurposesConsent());
			$bitString .= EncoderHelper::encodeIdsToBits($numCustomPurposes, $consentString->getCustomPurposesLITransparency());
		}

		return Bits::encodeBitStringToBase64($bitString);
	}
}