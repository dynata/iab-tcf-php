<?php

namespace IABTcf;

interface FieldIFace {
	public function getName(): string;
	public function getType(): string;
	public function getListCount(): callable;
	public function getNumBits(): callable;
	public function getValidator(): callable;
	public function getFields(): array;
	public function setValidator(callable $validator);
}