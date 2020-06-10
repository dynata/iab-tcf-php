<?php

namespace IABTcf\Gvl;

use IABTcf\Exceptions\InvalidVersionException;

class VendorList implements VendorListIFace {
	const GVL_V1_LATEST_URL = "https://vendorlist.consensu.org/vendorlist.json";
	const GVL_V2_LATEST_URL = "https://vendorlist.consensu.org/v2/vendor-list.json";
	const GVL_V1_VERSION_URL = "https://vendorlist.consensu.org/v-[VERSION]/vendorlist.json";
	const GVL_V2_VERSION_URL = "https://vendorlist.consensu.org/v2/archives/vendorlist-v[VERSION].json";

	private $gvlSpecificationVersion;
	private $vendorListVersion;
	private $tcfPolicyVersion;
	private $lastUpdated;
	private $purposes = [];
	private $specialPurposes = [];
	private $features = [];
	private $specialFeatures = [];
	private $vendors = [];
	private $stacks = [];
	private $maxVendorId = 0;
	private $maxPurpose = 0;
	private $maxFeature = 0;
	private $maxSpecialFeature = 0;
	private $maxSpecialPurpose = 0;

	/**
	 * @param int $tcfVersion
	 * @param int $listVersion
	 * @return string
	 * @throws InvalidVersionException
	 */
	public static function getGVLJson(int $tcfVersion, int $listVersion = 0): string
	{
		switch($tcfVersion) {
			case 1:
				if ($listVersion === 0) {
					$url = self::GVL_V1_LATEST_URL;
				} else {
					$url = str_replace("[VERSION]", $listVersion, self::GVL_V1_VERSION_URL);
				}
				break;
			case 2:
				if ($listVersion === 0) {
					$url = self::GVL_V2_LATEST_URL;
				} else {
					$url = str_replace("[VERSION]", $listVersion, self::GVL_V2_VERSION_URL);
				}
				break;
			default:
				throw new InvalidVersionException();
		}
		return file_get_contents($url);
	}

	/**
	 * VendorList constructor.
	 * @param string $jsonString
	 * @param int $version
	 * @throws InvalidVersionException
	 */
	public function __construct(string $jsonString, int $version)
	{
		$listArray = json_decode($jsonString, true);
		switch ($version) {
			case 1:
				$this->gvlSpecificationVersion = 1;
				$this->tcfPolicyVersion = 1;
				break;
			case 2:
				$this->gvlSpecificationVersion = $listArray['gvlSpecificationVersion'];
				$this->tcfPolicyVersion = $listArray['tcfPolicyVersion'];
				foreach($listArray['specialPurposes'] as $specialPurpose) {
					$this->maxSpecialPurpose = max($this->maxSpecialPurpose, $specialPurpose['id']);
					$this->specialPurposes[] = new SpecialPurpose(
						$specialPurpose['id'],
						$specialPurpose['name'],
						$specialPurpose['description'],
						$specialPurpose['descriptionLegal']
					);
				}
				foreach($listArray['specialFeatures'] as $specialFeature) {
					$this->maxSpecialFeature = max($this->maxSpecialFeature, $specialFeature['id']);
					$this->specialFeatures[] = new SpecialFeature(
						$specialFeature['id'],
						$specialFeature['name'],
						$specialFeature['description'],
						$specialFeature['descriptionLegal']
					);
				}
				foreach($listArray['stacks'] as $stack) {
					$this->stacks[] = new Stack(
						$stack['id'],
						$stack['name'],
						$stack['description'],
						$stack['purposes'],
						$stack['specialFeatures']
					);
				}
				break;
			default:
				throw new InvalidVersionException();
		}
		$this->vendorListVersion = $listArray['vendorListVersion'];
		$this->lastUpdated = $listArray['lastUpdated'];
		foreach($listArray['purposes'] as $purpose) {
			$this->maxPurpose = max($this->maxPurpose, $purpose['id']);
			$this->purposes[] = new Purpose(
				$purpose['id'],
				$purpose['name'],
				$purpose['description'],
				(isset($purpose['descriptionLegal']) ? $purpose['descriptionLegal'] : "")
			);
		}
		foreach($listArray['features'] as $feature) {
			$this->maxFeature = max($this->maxFeature, $feature['id']);
			$this->features[] = new Feature(
				$feature['id'],
				$feature['name'],
				$feature['description'],
				(isset($feature['descriptionLegal']) ? $feature['descriptionLegal'] : "")
			);
		}
		foreach($listArray['vendors'] as $vendor) {
			$this->maxVendorId = max($this->maxVendorId, $vendor['id']);
			$this->vendors[] = new Vendor(
				$vendor['id'],
				$vendor['name'],
				$vendor['purposes'],
				(isset($vendor['legIntPurposes']) ? $vendor['legIntPurposes'] : $vendor['legIntPurposeIds']),
				(isset($vendor['flexiblePurposes']) ? $vendor['flexiblePurposes'] : []),
				(isset($vendor['specialPurposes']) ? $vendor['specialPurposes'] : []),
				(isset($vendor['features']) ? $vendor['features'] : $vendor['featureIds']),
				(isset($vendor['specialFeatures']) ? $vendor['specialFeatures'] : []),
				$vendor['policyUrl']
			);
		}
	}

	/**
	 * @return int
	 */
	public function getGvlSpecificationVersion(): int
	{
		return $this->gvlSpecificationVersion;
	}

	/**
	 * @return int
	 */
	public function getVendorListVersion(): int
	{
		return $this->vendorListVersion;
	}

	/**
	 * @return int
	 */
	public function getTcfPolicyVersion(): int
	{
		return $this->tcfPolicyVersion;
	}

	/**
	 * @return string
	 */
	public function getLastUpdated(): string
	{
		return $this->lastUpdated;
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
	 * @return array
	 */
	public function getVendors(): array
	{
		return $this->vendors;
	}

	/**
	 * @return array
	 */
	public function getStacks(): array
	{
		return $this->stacks;
	}

	/**
	 * @return int
	 */
	public function getMaxVendorId(): int
	{
		return $this->maxVendorId;
	}

	/**
	 * @return int
	 */
	public function getMaxPurpose(): int
	{
		return $this->maxPurpose;
	}

	/**
	 * @return int
	 */
	public function getMaxFeature(): int
	{
		return $this->maxFeature;
	}

	/**
	 * @return int
	 */
	public function getMaxSpecialFeature(): int
	{
		return $this->maxSpecialFeature;
	}

	/**
	 * @return int
	 */
	public function getMaxSpecialPurpose(): int
	{
		return $this->maxSpecialPurpose;
	}
}