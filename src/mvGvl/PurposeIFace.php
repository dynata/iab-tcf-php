<?php

namespace IABTcf\Gvl;

interface PurposeIFace {
	public function getId(): int;
	public function getName(): string;
	public function getDescription(): string;
	public function getDescriptionLegal(): string;
}