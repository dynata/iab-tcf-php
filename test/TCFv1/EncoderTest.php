<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use IABTcf\Exceptions\FieldNotFoundException;
use IABTcf\Exceptions\InvalidEncodingTypeException;
use IABTcf\Exceptions\UnsupportedSegmentException;
use IABTcf\Exceptions\UnsupportedVersionException;
use PHPUnit\Framework\TestCase;
use IABTcf\TCFv1\Encoder;
use IABTcf\ConsentString;

final class EncoderTest extends TestCase
{

	/**
	 * @throws FieldNotFoundException
	 * @throws InvalidEncodingTypeException
	 * @throws UnsupportedSegmentException
	 * @throws UnsupportedVersionException
	 */
	public function testEncodeBits()
	{
		$cs = new ConsentString();
		$cs->setVersion(1);
		$cs->setCmpId(1);
		$cs->setCmpVersion(2);
		$cs->setConsentScreen(3);
		$cs->setConsentLanguage('en');
		$cs->setVendorListVersion(1);
		$cs->setAllowedVendors([1, 2, 4]);
		$cs->setAllowedPurposes([1, 2]);
		$aDate = new DateTime('2018-07-15 07:00:00');
		$cs->setCreated($aDate);
		$cs->setLastUpdated($aDate);

		$res = Encoder::encode($cs);

		$this->assertEquals('BOQ7WlgOQ7WlgABACDENABwAAAAARoA', $res);
	}

	/**
	 * @throws FieldNotFoundException
	 * @throws InvalidEncodingTypeException
	 * @throws UnsupportedSegmentException
	 * @throws UnsupportedVersionException
	 */
	public function testEncodeRanges()
	{
		$cs = new ConsentString();
		$cs->setVersion(1);
		$cs->setCmpId(1);
		$cs->setCmpVersion(2);
		$cs->setConsentScreen(3);
		$cs->setConsentLanguage('en');
		$cs->setVendorListVersion(1);
		$cs->setAllowedVendors([1, 2, 3, 100, 101, 102, 103, 104, 1078, 1079, 1080, 1081, 6000]);
		$cs->setAllowedPurposes([1, 2]);
		$aDate = new DateTime('2018-07-15 07:00:00');
		$cs->setCreated($aDate);
		$cs->setLastUpdated($aDate);

		$res = Encoder::encode($cs);
		$this->assertEquals('BOQ7WlgOQ7WlgABACDENABwAAAF3CAEgACAAcAZABoghsCHIXcA', $res);
	}
}