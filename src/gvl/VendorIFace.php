<?php

namespace IABTcf\Gvl;

interface VendorIFace {
	public function getId(): int;
	public function getName(): string;
	public function getPurposes(): array;
	public function getLegIntPurposes(): array;
	public function getFlexiblePurposes(): array;
	public function getSpecialPurposes(): array;
	public function getFeatures(): array;
	public function getSpecialFeatures(): array;
	public function getPolicyUrl(): string;
}