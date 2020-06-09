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
	 * @throws UnsupportedVersionException
	 * @throws InvalidConsentStringException
	 * @throws InvalidSegmentException
	 * @throws InvalidVersionException
	 */
	public function testDecodeConsentStringBits()
	{
		$consentDataBits = Decoder::decode('BOOMzbgOOQww_AtABAFRAb-AAAsvOA3gACAAkABgArgBaAF0AMAA1gBuAH8AQQBSgCoAL8AYQBigDIAM0AaABpgDYAOYAdgA8AB6gD4AQoAiABFQCMAI6ASABIgCTAEqAJeATIBQQCiAKSAU4BVQCtAK-AWYBaQC2ALcAXMAvAC-gGAAYcAxQDGAGQAMsAZsA0ADTAGqANcAbMA4ADjAHKAOiAdQB1gDtgHgAeMA9AD2AHzAP4BAACBAEEAIbAREBEgCKQEXARhZeYA');
		$allowedVendorIds = [1, 2, 3, 4, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 45, 46, 48, 49, 50, 51, 52, 53, 55, 56, 57, 58, 59, 60, 61, 62, 63, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 84, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 97, 98, 100, 101, 102, 104, 105, 108, 109, 110, 111, 112, 113, 114, 115, 118, 120, 122, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 136, 138, 140, 141, 142, 144, 145, 147, 149, 151, 153, 154, 155, 156, 157, 158, 159, 160, 162, 163, 164, 167, 168, 169, 170, 173, 174, 175, 179, 180, 182, 183, 185, 188, 189, 190, 192, 193, 194, 195, 197, 198, 200, 203, 205, 208, 209, 210, 211, 213, 215, 217, 224, 225, 226, 227, 229, 232, 234, 235, 237, 240, 241, 244, 245, 246, 249, 254, 255, 256, 258, 260, 269, 273, 274, 276, 279, 280, 45811];

		// compare 1 of the strings with base data
		$this->assertEquals(1, $consentDataBits->getVersion());
		$this->assertEquals(45, $consentDataBits->getCmpId());
		$this->assertEquals(1, $consentDataBits->getCmpVersion());
		$this->assertEquals(0, $consentDataBits->getConsentScreen());
		$this->assertEquals('fr', $consentDataBits->getConsentLanguage());
		$this->assertEquals(27, $consentDataBits->getVendorListVersion());
		$this->assertEquals(new DateTime("2018-05-23T07:58:14.000Z"), $consentDataBits->getCreated());
		$this->assertEquals(new DateTime("2018-05-24T12:47:40.000Z"), $consentDataBits->getLastUpdated());
		$this->assertEquals([1, 2, 3, 4, 5], $consentDataBits->getAllowedPurposes());
		$this->assertEquals($allowedVendorIds, $consentDataBits->getAllowedVendors());
	}

	/**
	 * @throws InvalidConsentStringException
	 * @throws InvalidSegmentException
	 * @throws InvalidVersionException
	 * @throws UnsupportedVersionException
	 */
	public function testDecodeConsentStringRanges()
	{
		$consentDataRange = Decoder::decode('BOOMzbgOOQww_AtABAFRAb-AAAsvPA2AAKACwAF4ANgAgABTADAAGMAM8AagBrgDoAOoAdwA8gB7gEMAQ4AiQBFgCPAEkAJQASwAmABQwClAKaAVYBWQCwALIAWoAuIBdAF2AL8AYgAx4BkgGUAMyAZwBngDUAGsANiAbQBvgDkgHMAc4A6QB2QDuAO-AeQB5wD3APiAfQB-gEBAIHAQUBDICHAIgAROAioCLQEZsvI');

		$allowedVendorIds = [1, 2, 3, 4, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 45, 46, 48, 49, 50, 51, 52, 53, 55, 56, 57, 58, 59, 60, 61, 62, 63, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 84, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 97, 98, 100, 101, 102, 104, 105, 108, 109, 110, 111, 112, 113, 114, 115, 118, 120, 122, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 136, 138, 140, 141, 142, 144, 145, 147, 149, 151, 153, 154, 155, 156, 157, 158, 159, 160, 162, 163, 164, 167, 168, 169, 170, 173, 174, 175, 179, 180, 182, 183, 185, 188, 189, 190, 192, 193, 194, 195, 197, 198, 200, 203, 205, 208, 209, 210, 211, 213, 215, 217, 224, 225, 226, 227, 229, 232, 234, 235, 237, 240, 241, 244, 245, 246, 249, 254, 255, 256, 258, 260, 269, 273, 274, 276, 279, 280, 45811];

		// compare 1 of the strings with base data
		$this->assertEquals(1, $consentDataRange->getVersion());
		$this->assertEquals(45, $consentDataRange->getCmpId());
		$this->assertEquals(1, $consentDataRange->getCmpVersion());
		$this->assertEquals(0, $consentDataRange->getConsentScreen());
		$this->assertEquals('fr', $consentDataRange->getConsentLanguage());
		$this->assertEquals(27, $consentDataRange->getVendorListVersion());
		$this->assertEquals(new DateTime("2018-05-23T07:58:14.000Z"), $consentDataRange->getCreated());
		$this->assertEquals(new DateTime("2018-05-24T12:47:40.000Z"), $consentDataRange->getLastUpdated());
		$this->assertEquals([1, 2, 3, 4, 5], $consentDataRange->getAllowedPurposes());
		$this->assertEquals($allowedVendorIds, $consentDataRange->getAllowedVendors());
	}
}