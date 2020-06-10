<?php

namespace IABTcf\Utils;

use IABTcf\Definitions;
use IABTcf\Exceptions\FieldNotFoundException;
use IABTcf\Exceptions\InvalidEncodingTypeException;
use IABTcf\Exceptions\UnsupportedSegmentException;

class VendorFieldEncoder extends VendorEncoder {

	/**
	 * VendorFieldEncoder constructor.
	 * @param Definitions $definitions
	 * @param array $vendors
	 * @param bool $defaultConsent
	 * @param bool $emitRangeEncoding
	 * @param bool $emitMaxVendorId
	 * @param bool $emitIsRangeEncoding
	 */
	public function __construct(
		Definitions $definitions,
		array $vendors,
		bool $defaultConsent = false,
		bool $emitRangeEncoding = false,
		bool $emitMaxVendorId = true,
		bool $emitIsRangeEncoding = true
	)
	{
		parent::__construct($definitions, $vendors, $defaultConsent, $emitRangeEncoding, $emitMaxVendorId, $emitIsRangeEncoding);
	}

	/**
	 * @param bool $emitDefaultConsent
	 * @return string
	 * @throws FieldNotFoundException
	 * @throws InvalidEncodingTypeException
	 * @throws UnsupportedSegmentException
	 */
	public function build(bool $emitDefaultConsent): string
	{
		if (count($this->vendors) == 0) {
			$resultString = EncoderHelper::encodeField($this->definitions, 0, 'vendorConsentMaxVendorId', 0);
			$resultString .= EncoderHelper::encodeField($this->definitions, 0, 'vendorConsentIsRangeEncoding', false);

			return $resultString;
		}

		$resultString = '';
		if ($this->emitMaxVendorId) {
			$resultString = EncoderHelper::encodeField(
				$this->definitions,
				0,
				'vendorConsentMaxVendorId',
				max($this->vendors)
			);
		}

		$ranges = EncoderHelper::convertListToRanges($this->vendors);
		$rangeBits = EncoderHelper::encodeField(
			$this->definitions,
			0,
			'vendorConsentRangeList',
			$ranges,
			false
		);
		$rangeNumEntriesBits = EncoderHelper::encodeField(
			$this->definitions,
			0,
			'vendorConsentNumEntries',
			count($ranges),
			false
		);
		if ((strlen($rangeNumEntriesBits) + strlen($rangeBits)) < max($this->vendors) || $this->emitRangeEncoding) {
			if ($this->emitIsRangeEncoding) {
				$resultString .= EncoderHelper::encodeField($this->definitions, 0, 'vendorConsentIsRangeEncoding', true);
			}
			if ($emitDefaultConsent) {
				$resultString .= EncoderHelper::encodeField($this->definitions, 0, 'defaultConsent', $this->defaultConsent, false);
			}
			$resultString .= $rangeNumEntriesBits;
			$resultString .= $rangeBits;
		} else {
			$resultString .= EncoderHelper::encodeField($this->definitions, 0, 'vendorConsentIsRangeEncoding', false);
			$resultString .= EncoderHelper::encodeIdsToBits(max($this->vendors), $this->vendors);
		}

		return $resultString;
	}
}