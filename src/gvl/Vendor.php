<?php

namespace IABTcf\Gvl;

class Vendor implements VendorIFace {
	private $id;
	private $name;
	private $purposes;
	private $legIntPurposes;
	private $flexiblePurposes;
	private $specialPurposes;
	private $features;
	private $specialFeatures;
	private $policyUrl;

	/**
	 * Vendor constructor.
	 * @param int $id
	 * @param string $name
	 * @param array $purposes
	 * @param array $legIntPurposes
	 * @param array $flexiblePurposes
	 * @param array $specialPurposes
	 * @param array $features
	 * @param array $specialFeatures
	 * @param string $policyUrl
	 */
	public function __construct(
		int $id,
		string $name,
		array $purposes,
		array $legIntPurposes,
		array $flexiblePurposes,
		array $specialPurposes,
		array $features,
		array $specialFeatures,
		string $policyUrl
	)
	{
		$this->id = $id;
		$this->name = $name;
		$this->purposes = $purposes;
		$this->legIntPurposes = $legIntPurposes;
		$this->flexiblePurposes = $flexiblePurposes;
		$this->specialPurposes = $specialPurposes;
		$this->features = $features;
		$this->specialFeatures = $specialFeatures;
		$this->policyUrl = $policyUrl;
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
	 * @return array
	 */
	public function getPurposes(): array
	{
		return $this->purposes;
	}

	/**
	 * @return array
	 */
	public function getLegIntPurposes(): array
	{
		return $this->legIntPurposes;
	}

	/**
	 * @return array
	 */
	public function getFlexiblePurposes(): array
	{
		return $this->flexiblePurposes;
	}

	/**
	 * @return array
	 */
	public function getSpecialPurposes(): array
	{
		return $this->specialPurposes;
	}

	/**
	 * @return array
	 */
	public function getFeatures(): array
	{
		return $this->features;
	}

	/**
	 * @return array
	 */
	public function getSpecialFeatures(): array
	{
		return $this->specialFeatures;
	}

	/**
	 * @return string
	 */
	public function getPolicyUrl(): string
	{
		return $this->policyUrl;
	}
}