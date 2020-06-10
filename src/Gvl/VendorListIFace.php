<?php

namespace IABTcf\Gvl;

interface VendorListIFace {
	public static function getGVLJson(int $tcfVersion, int $listVersion): string;
	public function getGvlSpecificationVersion(): int;
	public function getVendorListVersion(): int;
	public function getTcfPolicyVersion(): int;
	public function getLastUpdated(): string;
	public function getPurposes(): array;
	public function getSpecialPurposes(): array;
	public function getFeatures(): array;
	public function getSpecialFeatures(): array;
	public function getVendors(): array;
	public function getStacks(): array;
	public function getMaxVendorId(): int;
	public function getMaxPurpose(): int;
	public function getMaxFeature(): int;
	public function getMaxSpecialPurpose(): int;
	public function getMaxSpecialFeature(): int;
}