<?php

namespace IABTcf\Utils;

interface VendorEncoderIFace {
	public function setEmitRangeEncoding(bool $emitRangeEncoding);
	public function setEmitMaxVendorId(bool $emitMaxVendorId);
	public function setEmitIsRangeEncoding(bool $emitIsRangeEncoding);
	public function setDefaultConsent(bool $defaultConsent);
}