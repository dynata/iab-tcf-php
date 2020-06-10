<?php

namespace IABTcf\Gvl;

interface StackIFace {
	public function getId(): int;
	public function getName(): string;
	public function getDescription(): string;
	public function getPurposes(): array;
	public function getSpecialFeatures(): array;
}