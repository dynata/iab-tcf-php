<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use IABTcf\Exceptions\InvalidConsentStringException;
use IABTcf\Exceptions\InvalidSegmentException;
use IABTcf\Exceptions\InvalidVersionException;
use IABTcf\Exceptions\UnsupportedVersionException;
use PHPUnit\Framework\TestCase;
use IABTcf\Decoder;

final class DecoderTest extends TestCase
{
	/**
	 * @throws InvalidConsentStringException
	 * @throws InvalidSegmentException
	 * @throws InvalidVersionException
	 * @throws UnsupportedVersionException
	 */
	public function testDecodeCoreString()
	{
		$cs = Decoder::decode("COtybn4PA_zT4KjACBENAPCIAEBAAECAAIAAAAAAAAAA");

		$this->assertEquals(2, $cs->getVersion());

		$this->assertEquals("2020-01-26T17:01:00+00:00", $cs->getCreated()->format("c"));
		$this->assertEquals("2021-02-02T17:01:00+00:00", $cs->getLastUpdated()->format("c"));

		$this->assertEquals(675, $cs->getCmpId());
		$this->assertEquals(2, $cs->getCmpVersion());
		$this->assertEquals(1, $cs->getConsentScreen());
		$this->assertEquals(15, $cs->getVendorListVersion());
		$this->assertEquals(2, $cs->getTcfPolicyVersion());
		$this->assertEquals("en", $cs->getConsentLanguage());
		$this->assertEquals("aa", $cs->getPublisherCC());
		$this->assertFalse($cs->getIsServiceSpecific());
		$this->assertTrue($cs->getPurposeOneTreatment());
		$this->assertFalse($cs->getUseNonStandardStacks());

		$this->assertSame([2, 10], $cs->getPurposesConsent());
		$this->assertSame([1], $cs->getSpecialFeatureOptIns());
		$this->assertSame([2, 9], $cs->getPurposesLITransparency());
	}

	/**
	 * @throws InvalidConsentStringException
	 * @throws InvalidSegmentException
	 * @throws InvalidVersionException
	 * @throws UnsupportedVersionException
	 */
	public function testPublisherRestrictions()
	{
		$cs = Decoder::decode("COtybn4PA_zT4KjACBENAPCIAEBAAECAAIAAAAAAAABAoAMAAQACCQAgAAgAIA");
		$pubRestrictions = $cs->getPublisherRestrictions();

		$this->assertEquals(1, $pubRestrictions[0]->getPurposeId());
		$this->assertEquals(1, $pubRestrictions[0]->getRestrictionType()->getId());
		$this->assertEquals([1, 2], $pubRestrictions[0]->getVendorIds());
		$this->assertEquals(2, $pubRestrictions[1]->getPurposeId());
		$this->assertEquals(1, $pubRestrictions[1]->getRestrictionType()->getId());
		$this->assertEquals([1, 8], $pubRestrictions[1]->getVendorIds());
	}

	/**
	 * @throws InvalidConsentStringException
	 * @throws InvalidSegmentException
	 * @throws InvalidVersionException
	 * @throws UnsupportedVersionException
	 */
	public function testDecodeFromBitFields()
	{
		$cs = Decoder::decode("COrEAV4OrXx94ACABBENAHCIAD-AAAAAAACAAxAAAAgAIAwgAgAAAAEAgQAAAAAEAYQAQAAAACAAAABAAA");
		$this->assertSame([3, 4, 5, 6, 7, 8, 9], $cs->getPurposesConsent());
		$this->assertSame([23, 37, 47, 48, 53, 65, 98], $cs->getVendorConsent());
		$this->assertSame([37, 47, 48, 53, 65, 98, 129], $cs->getVendorLegitimateInterest());
	}

	/**
	 * @throws InvalidConsentStringException
	 * @throws InvalidSegmentException
	 * @throws InvalidVersionException
	 * @throws UnsupportedVersionException
	 */
	public function testCanParseRangeEncodedVendorLegitimateInterests()
	{
		$cs = Decoder::decode("COv__-wOv__-wC2AAAENAPCgAAAAAAAAAAAAA_wAQA_gEBABAEAAAA");

		$this->assertSame([128], $cs->getVendorLegitimateInterest());
	}

	/**
	 * Segment 1: Disclosed Vendors Bits
	 * @throws InvalidConsentStringException
	 * @throws InvalidSegmentException
	 * @throws InvalidVersionException
	 * @throws UnsupportedVersionException
	 */
	public function testCanParseDisclosedVendorsBits()
	{
		$cs = Decoder::decode("COrEAV4OrXx94ACABBENAHCIAD-AAAAAAACAAxAAAAgAIAwgAgAAAAEAgQAAAAAEAYQAQAAAACAAAABAAA.IBAgAAAgAIAwgAgAAAAEAAAACA");
		$this->assertSame([23, 37, 47, 48, 53, 65, 98, 129], $cs->getDisclosedVendors());
	}

	/**
	 * Segment 1: Disclosed Vendors Ranges
	 * @throws InvalidConsentStringException
	 * @throws InvalidSegmentException
	 * @throws InvalidVersionException
	 * @throws UnsupportedVersionException
	 */
	public function testCanParseDisclosedVendorsRanges()
	{
		$cs = Decoder::decode("COrEAV4OrXx94ACABBENAHCIAD-AAAAAAACAAxAAAAgAIAwgAgAAAAEAgQAAAAAEAYQAQAAAACAAAABAAA.Iu4QCAALgAlgBeAGAANQAggBiAECF3A");
		$this->assertSame([23, 37, 47, 48, 53, 65, 98, 129, 6000], $cs->getDisclosedVendors());
	}

	/**
	 * Segment 2: Allowed Vendors Bits
	 * @throws InvalidConsentStringException
	 * @throws InvalidSegmentException
	 * @throws InvalidVersionException
	 * @throws UnsupportedVersionException
	 */
	public function testCanParseAllowedVendorsBits()
	{
		$cs = Decoder::decode("COrEAV4OrXx94ACABBENAHCIAD-AAAAAAACAAxAAAAgAIAwgAgAAAAEAgQAAAAAEAYQAQAAAACAAAABAAA.QAagAQAgAIAwgA");
		$this->assertSame([12, 23, 37, 47, 48, 53], $cs->getAllowedVendors());
	}

	/**
	 * Segment 2: Allowed Vendors Ranges
	 * @throws InvalidConsentStringException
	 * @throws InvalidSegmentException
	 * @throws InvalidVersionException
	 * @throws UnsupportedVersionException
	 */
	public function testCanParseAllowedVendorsRanges()
	{
		$cs = Decoder::decode("COrEAV4OrXx94ACABBENAHCIAD-AAAAAAACAAxAAAAgAIAwgAgAAAAEAgQAAAAAEAYQAQAAAACAAAABAAA.Qu4QBQAGAAXABLAC8AMAu4A");
		$this->assertSame([12, 23, 37, 47, 48, 6000], $cs->getAllowedVendors());
	}

	/**
	 * Segment 3: Publisher Purposes Transparency and Consent
	 * @throws InvalidConsentStringException
	 * @throws InvalidSegmentException
	 * @throws InvalidVersionException
	 * @throws UnsupportedVersionException
	 */
	public function testPublisherPurposes()
	{
		$cs = Decoder::decode("COrEAV4OrXx94ACABBENAHCIAD-AAAAAAACAAxAAAAgAIAwgAgAAAAEAgQAAAAAEAYQAQAAAACAAAABAAA.cAAAAAAAITg");

		$this->assertSame([1], $cs->getPubPurposesConsent());
		$this->assertSame([24], $cs->getPubPurposesLITransparency());
		$this->assertSame([2], $cs->getCustomPurposesConsent());
		$this->assertSame([1, 2], $cs->getCustomPurposesLITransparency());
	}

	/**
	 * @throws InvalidConsentStringException
	 * @throws InvalidSegmentException
	 * @throws InvalidVersionException
	 * @throws UnsupportedVersionException
	 */
	public function testDecodeAllParts()
	{
		$cs = Decoder::decode("COtybn4PA_zT4KjACBENAPCIAD-AAECAAIAAAxAAAAgAIAwgAgAAAAEAgQAAAAAEAYQAQAAAACAAAABACBQAYAAgAEEgBAABAAQA.IBAgAAAgAIAwgAgAAAAEAAAACA.Qu4QBQAGAAXABLAC8AMAu4A.cAAAAAAAITg");
		// core
		$this->assertEquals(2, $cs->getVersion());
		$this->assertEquals("2020-01-26T17:01:00+00:00", $cs->getCreated()->format("c"));
		$this->assertEquals("2021-02-02T17:01:00+00:00", $cs->getLastUpdated()->format("c"));
		$this->assertEquals(675, $cs->getCmpId());
		$this->assertEquals(2, $cs->getCmpVersion());
		$this->assertEquals(1, $cs->getConsentScreen());
		$this->assertEquals(15, $cs->getVendorListVersion());
		$this->assertEquals(2, $cs->getTcfPolicyVersion());
		$this->assertEquals("en", $cs->getConsentLanguage());
		$this->assertEquals("aa", $cs->getPublisherCC());
		$this->assertFalse($cs->getIsServiceSpecific());
		$this->assertTrue($cs->getPurposeOneTreatment());
		$this->assertFalse($cs->getUseNonStandardStacks());
		$this->assertSame([3, 4, 5, 6, 7, 8, 9], $cs->getPurposesConsent());
		$this->assertSame([23, 37, 47, 48, 53, 65, 98], $cs->getVendorConsent());
		$this->assertSame([37, 47, 48, 53, 65, 98, 129], $cs->getVendorLegitimateInterest());
		$this->assertSame([1], $cs->getSpecialFeatureOptIns());
		$this->assertSame([2, 9], $cs->getPurposesLITransparency());

		// pub restrictions
		$pubRestrictions = $cs->getPublisherRestrictions();
		$this->assertEquals(1, $pubRestrictions[0]->getPurposeId());
		$this->assertEquals(1, $pubRestrictions[0]->getRestrictionType()->getId());
		$this->assertEquals([1, 2], $pubRestrictions[0]->getVendorIds());
		$this->assertEquals(2, $pubRestrictions[1]->getPurposeId());
		$this->assertEquals(1, $pubRestrictions[1]->getRestrictionType()->getId());
		$this->assertEquals([1, 8], $pubRestrictions[1]->getVendorIds());
		// disclosed vendors
		$this->assertSame([23, 37, 47, 48, 53, 65, 98, 129], $cs->getDisclosedVendors());
		// allowed vendors
		$this->assertSame([12, 23, 37, 47, 48, 6000], $cs->getAllowedVendors());
		// publisher purposes
		$this->assertSame([1], $cs->getPubPurposesConsent());
		$this->assertSame([24], $cs->getPubPurposesLITransparency());
		$this->assertSame([2], $cs->getCustomPurposesConsent());
		$this->assertSame([1, 2], $cs->getCustomPurposesLITransparency());

	}
}