<?php

namespace IABTcf;

class Field implements FieldIFace {

	private $type;
	private $listCount;
	private $numBits;
	private $validator;
	private $name;
	private $fields;

	/**
	 * Field constructor.
	 * @param string $name
	 * @param string $type
	 * @param callable|null $numBits
	 * @param callable|null $validator
	 * @param callable|null $listCount
	 * @param array $fields
	 */
	public function __construct(
		string $name,
		string $type,
		callable $numBits = null,
		callable $validator = null,
		callable $listCount = null,
		array $fields = []
	) {
		$this->name = $name;
		$this->type = $type;
		if (is_null($numBits)) {
			$numBits = function() { return null; };
		}
		if (is_null($validator)) {
			$validator = function() { return true; };
		}
		if (is_null($listCount)) {
			$listCount = function() { return null; };
		}
		$this->listCount = $listCount;
		$this->numBits = $numBits;
		$this->validator = $validator;
		$this->fields = $fields;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @return callable
	 */
	public function getListCount(): callable
	{
		return $this->listCount;
	}

	/**
	 * @return callable
	 */
	public function getNumBits(): callable
	{
		return $this->numBits;
	}

	/**
	 * @return callable
	 */
	public function getValidator(): callable
	{
		return $this->validator;
	}

	/**
	 * @param callable $validator
	 */
	public function setValidator(callable $validator)
	{
		$this->validator = $validator;
	}

	/**
	 * @return array
	 */
	public function getFields(): array
	{
		return $this->fields;
	}
}