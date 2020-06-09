<?php

namespace IABTcf\Utils;

use IABTcf\Definitions;

abstract class VendorEncoder implements VendorEncoderIFace {

	protected $defaultConsent = false;
	protected $emitRangeEncoding;
	protected $emitMaxVendorId;
	protected $emitIsRangeEncoding;
	protected $vendors = [];
	protected $definitions;

	/**
	 * VendorEncoder constructor.
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
		$this->definitions = $definitions;
		$this->vendors = $vendors;
		$this->defaultConsent = $defaultConsent;
		$this->emitRangeEncoding = $emitRangeEncoding;
		$this->emitMaxVendorId = $emitMaxVendorId;
		$this->emitIsRangeEncoding = $emitIsRangeEncoding;
	}

	/**
	 * Whether to force range encoding even if it consumes more bits than bit field encoding.
	 * @param bool $emitRangeEncoding
	 */
	public function setEmitRangeEncoding(bool $emitRangeEncoding)
	{
		$this->emitRangeEncoding = $emitRangeEncoding;
	}

	/**
	 * Whether to emit the maximum Vendor ID in this encoding. This should be set to false when encoding
	 * PublisherRestriction segment.
	 * @param bool $emitMaxVendorId
	 */
	public function setEmitMaxVendorId(bool $emitMaxVendorId)
	{
		$this->emitMaxVendorId = $emitMaxVendorId;
	}

	/**
	 * Whether to emit the IsRangeEncoding flag. When set to false, the field is not encoded. This
	 * should be set to false when encoding PublisherRestriction section.
	 * @param bool $emitIsRangeEncoding
	 */
	public function setEmitIsRangeEncoding(bool $emitIsRangeEncoding)
	{
		$this->emitIsRangeEncoding = $emitIsRangeEncoding;
	}

	/**
	 * For V1, default consent for VendorIds not covered by a RangeEntry. VendorIds covered by a
	 * RangeEntry have a consent value the opposite of DefaultConsent. Defaults to false.
	 * @param bool $defaultConsent
	 */
	public function setDefaultConsent(bool $defaultConsent)
	{
		$this->defaultConsent = $defaultConsent;
	}
}