<?php

namespace IABTcf\TCFv2;


interface PublisherRestrictionIFace {
	public function getPurposeId(): int;
	public function getRestrictionType(): RestrictionType;
	public function getVendorIds(): array;
}