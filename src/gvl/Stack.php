<?php

namespace IABTcf\Gvl;

class Stack implements StackIFace {
	private $id;
	private $name;
	private $description;
	private $purposes;
	private $specialFeatures;

	/**
	 * Stack constructor.
	 * @param int $id
	 * @param string $name
	 * @param string $description
	 * @param array $purposes
	 * @param array $specialFeatures
	 */
	public function __construct(int $id, string $name, string $description, array $purposes, array $specialFeatures)
	{
		$this->id = $id;
		$this->name = $name;
		$this->description = $description;
		$this->purposes = $purposes;
		$this->specialFeatures = $specialFeatures;
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
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
	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @return array
	 */
	public function getPurposes(): array
	{
		return $this->purposes;
	}

	/**
	 * @return array
	 */
	public function getSpecialFeatures(): array
	{
		return $this->specialFeatures;
	}
}