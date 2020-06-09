<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use IABTcf\Exceptions\FieldNotFoundException;
use IABTcf\Exceptions\InvalidEncodingTypeException;
use IABTcf\Exceptions\UnsupportedSegmentException;
use IABTcf\Exceptions\UnsupportedVersionException;
use PHPUnit\Framework\TestCase;
use IABTcf\ConsentString;
use IABTcf\TCFv2\Encoder;
use IABTcf\Definitions;
use IABTcf\TCFv2\PublisherRestriction;
use IABTcf\TCFv2\RestrictionType;

final class EncoderTest extends TestCase
{
	/**
	 * @throws FieldNotFoundException
	 * @throws InvalidEncodingTypeException
	 * @throws UnsupportedSegmentException
	 * @throws UnsupportedVersionException
	 */
	public function testEncodeCoreString()
	{
		$cs = new ConsentString();
		$cs->setVersion(2);
		$cs->setCreated(new DateTime('2020-01-26 17:01:00'));
		$cs->setLastUpdated(new DateTime('2021-02-02 17:01:00'));
		$cs->setCmpId(675);
		$cs->setCmpVersion(2);
		$cs->setConsentScreen(1);
		$cs->setVendorListVersion(15);
		$cs->setTcfPolicyVersion(2);
		$cs->setConsentLanguage('en');
		$cs->setPublisherCC('aa');
		$cs->setIsServiceSpecific(false);
		$cs->setPurposeOneTreatment(true);
		$cs->setUseNonStandardStacks(false);
		$cs->setAllowedPurposes([2, 10]);
		$cs->setSpecialFeatureOptIns([1]);
		$cs->setPurposesLITransparency([2, 9]);

		$enc = Encoder::buildCoreString($cs, new Definitions(2));

		$this->assertEquals("COtybn4PA_zT4KjACBENAPCIAEBAAECAAIAAAAAAAAAA", $enc);
	}

	/**
	 * @throws FieldNotFoundException
	 * @throws InvalidEncodingTypeException
	 * @throws UnsupportedSegmentException
	 * @throws UnsupportedVersionException
	 */
	public function testEncodeBitFields()
	{
		$cs = new ConsentString();
		$cs->setVersion(2);
		$cs->setCreated(new DateTime('2019-12-04 18:53:00'));
		$cs->setLastUpdated(new DateTime('2019-12-10 18:53:00'));
		$cs->setCmpId(2);
		$cs->setCmpVersion(1);
		$cs->setConsentScreen(1);
		$cs->setVendorListVersion(7);
		$cs->setTcfPolicyVersion(2);
		$cs->setConsentLanguage('en');
		$cs->setPublisherCC('aq');
		$cs->setIsServiceSpecific(false);
		$cs->setPurposeOneTreatment(false);
		$cs->setUseNonStandardStacks(false);
		$cs->setAllowedPurposes([2, 10]);
		$cs->setSpecialFeatureOptIns([1]);
		$cs->setPurposesConsent([3, 4, 5, 6, 7, 8, 9]);
		$cs->setVendorConsent([23, 37, 47, 48, 53, 65, 98]);
		$cs->setVendorLegitimateInterest([37, 47, 48, 53, 65, 98, 129]);

		$enc = Encoder::buildCoreString($cs, new Definitions(2));
		$this->assertEquals("COrEAV4OrXx94ACABBENAHCIAD-AAAAAAACAAxAAAAgAIAwgAgAAAAEAgQAAAAAEAYQAQAAAACAAAABAAA", $enc);
	}

	/**
	 * @throws FieldNotFoundException
	 * @throws InvalidEncodingTypeException
	 * @throws UnsupportedSegmentException
	 * @throws UnsupportedVersionException
	 */
	public function testEncodeRangeFields()
	{
		$cs = new ConsentString();
		$cs->setVersion(2);
		$cs->setCreated(new DateTime('2019-12-04 18:53:00'));
		$cs->setLastUpdated(new DateTime('2019-12-10 18:53:00'));
		$cs->setCmpId(2);
		$cs->setCmpVersion(1);
		$cs->setConsentScreen(1);
		$cs->setVendorListVersion(7);
		$cs->setTcfPolicyVersion(2);
		$cs->setConsentLanguage('en');
		$cs->setPublisherCC('aq');
		$cs->setIsServiceSpecific(false);
		$cs->setPurposeOneTreatment(false);
		$cs->setUseNonStandardStacks(false);
		$cs->setAllowedPurposes([2, 10]);
		$cs->setSpecialFeatureOptIns([1]);
		$cs->setPurposesConsent([3, 4, 5, 6, 7, 8, 9]);
		$cs->setVendorConsent([23, 37, 47, 48, 53, 65, 98, 6000]);
		$cs->setVendorLegitimateInterest([37, 47, 48, 53, 65, 98, 129, 6000]);

		$enc = Encoder::buildCoreString($cs, new Definitions(2));

		$this->assertEquals("COrEAV4OrXx94ACABBENAHCIAD-AAAAAAACAu4QBwALgAlgBeAGAANQAggBiC7gLuEAcAEsALwAwABqAEEAMQAgQu4AAAA", $enc);
	}

	/**
	 * @throws FieldNotFoundException
	 * @throws InvalidEncodingTypeException
	 * @throws UnsupportedSegmentException
	 * @throws UnsupportedVersionException
	 */
	public function testEncodeDisclosedVendorsBits()
	{
		$cs = new ConsentString();
		$cs->setDisclosedVendors([23, 37, 47, 48, 53, 65, 98, 129]);
		$enc = Encoder::buildDisclosedVendorsString(new Definitions(2), $cs);
		$this->assertEquals("IBAgAAAgAIAwgAgAAAAEAAAACA", $enc);
	}

	/**
	 * @throws FieldNotFoundException
	 * @throws InvalidEncodingTypeException
	 * @throws UnsupportedSegmentException
	 * @throws UnsupportedVersionException
	 */
	public function testEncodeDisclosedVendorsRanges()
	{
		$cs = new ConsentString();
		$cs->setDisclosedVendors([23, 37, 47, 48, 53, 65, 98, 129, 6000]);
		$enc = Encoder::buildDisclosedVendorsString(new Definitions(2), $cs);
		$this->assertEquals("Iu4QCAALgAlgBeAGAANQAggBiAECF3A", $enc);
	}

	/**
	 * @throws FieldNotFoundException
	 * @throws InvalidEncodingTypeException
	 * @throws UnsupportedSegmentException
	 * @throws UnsupportedVersionException
	 */
	public function testEncodeAllowedVendorsBits()
	{
		$cs = new ConsentString();
		$cs->setAllowedVendors([12, 23, 37, 47]);
		$enc = Encoder::buildAllowedVendorsString(new Definitions(2), $cs);
		$this->assertEquals("QAXgAQAgAIAg", $enc);
	}

	/**
	 * @throws FieldNotFoundException
	 * @throws InvalidEncodingTypeException
	 * @throws UnsupportedSegmentException
	 * @throws UnsupportedVersionException
	 */
	public function testEncodeAllowedVendorsRanges()
	{
		$cs = new ConsentString();
		$cs->setAllowedVendors([12, 23, 37, 47, 48, 6000]);
		$enc = Encoder::buildAllowedVendorsString(new Definitions(2), $cs);
		$this->assertEquals("Qu4QBQAGAAXABLAC8AMAu4A", $enc);
	}

	/**
	 * @throws FieldNotFoundException
	 * @throws InvalidEncodingTypeException
	 * @throws UnsupportedSegmentException
	 * @throws UnsupportedVersionException
	 */
	public function testEncodePublisherPurposes()
	{
		$cs = new ConsentString();
		$cs->setPubPurposesConsent([1]);
		$cs->setPubPurposesLITransparency([24]);
		$cs->setCustomPurposesConsent([2]);
		$cs->setCustomPurposesLITransparency([1, 2]);
		$enc = Encoder::buildPublisherTcString(new Definitions(2), $cs);

		$this->assertEquals("cAAAAAAAITg", $enc);
	}

	/**
	 * @throws FieldNotFoundException
	 * @throws InvalidEncodingTypeException
	 * @throws UnsupportedSegmentException
	 * @throws UnsupportedVersionException
	 */
	public function testEncodeAllParts()
	{
		$cs = new ConsentString();
		$cs->setVersion(2);
		$cs->setCreated(new DateTime('2020-01-26 17:01:00'));
		$cs->setLastUpdated(new DateTime('2021-02-02 17:01:00'));
		$cs->setCmpId(675);
		$cs->setCmpVersion(2);
		$cs->setConsentScreen(1);
		$cs->setVendorListVersion(15);
		$cs->setTcfPolicyVersion(2);
		$cs->setConsentLanguage('en');
		$cs->setPublisherCC('aa');
		$cs->setIsServiceSpecific(false);
		$cs->setPurposeOneTreatment(true);
		$cs->setUseNonStandardStacks(false);
		$cs->setAllowedPurposes([2, 10]);
		$cs->setSpecialFeatureOptIns([1]);
		$cs->setPurposesConsent([3, 4, 5, 6, 7, 8, 9]);
		$cs->setVendorConsent([23, 37, 47, 48, 53, 65, 98]);
		$cs->setVendorLegitimateInterest([37, 47, 48, 53, 65, 98, 129]);
		$cs->setPurposesLITransparency([2, 9]);
		$restrictionType = new RestrictionType(1);
		$cs->setPublisherRestrictions([
			new PublisherRestriction(1, $restrictionType, [1, 2]),
			new PublisherRestriction(2, $restrictionType, [1, 8])
		]);

		$cs->setDisclosedVendors([23, 37, 47, 48, 53, 65, 98, 129]);

		$cs->setAllowedVendors([12, 23, 37, 47, 48, 6000]);

		$cs->setPubPurposesConsent([1]);
		$cs->setPubPurposesLITransparency([24]);
		$cs->setCustomPurposesConsent([2]);
		$cs->setCustomPurposesLITransparency([1, 2]);

		$enc = Encoder::encode($cs);

		$this->assertEquals("COtybn4PA_zT4KjACBENAPCIAD-AAECAAIAAAxAAAAgAIAwgAgAAAAEAgQAAAAAEAYQAQAAAACAAAABACBQAYAAgAEEgBAABAAQA.IBAgAAAgAIAwgAgAAAAEAAAACA.Qu4QBQAGAAXABLAC8AMAu4A.cAAAAAAAITg", $enc);
	}
}