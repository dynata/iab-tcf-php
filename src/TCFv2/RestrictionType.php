<?php
namespace IABTcf\TCFv2;

class RestrictionType {
	private $id;

	const MAPPINGS = [
		0 => 'NOT_ALLOWED',
		1 => 'REQUIRE_CONSENT',
		2 => 'REQUIRE_LEGITIMATE_INTEREST',
		3 => 'UNDEFINED'
	];

	/**
	 * RestrictionType constructor.
	 * @param $id
	 */
	public function __construct($id)
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		if (! isset(self::MAPPINGS[$this->id])) {
			return 'NOT_ALLOWED';
		}
		return self::MAPPINGS[$this->id];
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}
}