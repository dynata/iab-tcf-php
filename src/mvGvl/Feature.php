<?php

namespace IABTcf\Gvl;

class Feature implements FeatureIFace {
	private $id;
	private $name;
	private $description;
	private $descriptionLegal;

	/**
	 * Feature constructor.
	 * @param int $id
	 * @param string $name
	 * @param string $description
	 * @param string $descriptionLegal
	 */
	public function __construct(int $id, string $name, string $description, string $descriptionLegal)
	{
		$this->id = $id;
		$this->name = $name;
		$this->description = $description;
		$this->descriptionLegal = $descriptionLegal;
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
	 * @return string
	 */
	public function getDescriptionLegal(): string
	{
		return $this->descriptionLegal;
	}
}