<?php

namespace IABTcf\Utils;

use IABTcf\Definitions;
use IABTcf\Exceptions\FieldNotFoundException;
use IABTcf\Exceptions\InvalidEncodingTypeException;
use IABTcf\Exceptions\UnsupportedSegmentException;

class AllowedDisclosedVendorFieldEncoder extends VendorEncoder {

	/**
	 * AllowedDisclosedVendorFieldEncoder constructor.
	 * @param Definitions $definitions
	 * @param array $vendors
	 * @param bool $defaultConsent
	 * @param bool $emitRangeEncoding
	 * @param bool $emitMaxVendorId
	 * @param bool $emitIsRangeEncoding
	 */
	public function __construct(Definitions $definitions, array $vendors, bool $defaultConsent = false, bool $emitRangeEncoding = false, bool $emitMaxVendorId = true, bool $emitIsRangeEncoding = true)
	{
		parent::__construct($definitions, $vendors, $defaultConsent, $emitRangeEncoding, $emitMaxVendorId, $emitIsRangeEncoding);
	}

	/**
	 * @param int $segmentId
	 * @return string
	 * @throws FieldNotFoundException
	 * @throws InvalidEncodingTypeException
	 * @throws UnsupportedSegmentException
	 */
	public function build(int $segmentId): string
	{
		if (count($this->vendors) == 0) {
			$resultString = EncoderHelper::encodeField($this->definitions, $segmentId, 'maxVendorId', 0);
			$resultString .= EncoderHelper::encodeField($this->definitions, $segmentId, 'isRangeEncoding', false);

			return $resultString;
		}

		$resultString = '';
		if ($this->emitMaxVendorId) {
			$resultString = EncoderHelper::encodeField($this->definitions, $segmentId, 'maxVendorId', max($this->vendors));
		}

		$ranges = EncoderHelper::convertListToRanges($this->vendors);
		$rangeBits = EncoderHelper::encodeField(
			$this->definitions,
			$segmentId,
			'vendorRangeList',
			$ranges,
			false
		);
		$rangeNumEntriesBits = EncoderHelper::encodeField($this->definitions, $segmentId, 'vendorNumEntries', count($ranges), false);
		if ((strlen($rangeNumEntriesBits) + strlen($rangeBits)) < max($this->vendors) || $this->emitRangeEncoding) {
			if ($this->emitIsRangeEncoding) {
				$resultString .= EncoderHelper::encodeField($this->definitions, $segmentId, 'isRangeEncoding', true);
			}
			$resultString .= $rangeNumEntriesBits;
			$resultString .= $rangeBits;
		} else {
			$resultString .= EncoderHelper::encodeField($this->definitions, $segmentId, 'isRangeEncoding', false);
			$resultString .= EncoderHelper::encodeIdsToBits(max($this->vendors), $this->vendors);
		}

		return $resultString;
	}
}