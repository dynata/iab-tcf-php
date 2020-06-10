<?php

namespace IABTcf\TCFv2;


class PublisherRestriction implements PublisherRestrictionIFace {

	private $purposeId;
	private $restrictionType;
	private $vendorIds;

	/**
	 * PublisherRestriction constructor.
	 * @param int $purposeId
	 * @param RestrictionType $restrictionType
	 * @param array $vendorIds
	 */
	public function __construct(int $purposeId, RestrictionType $restrictionType, array $vendorIds)
	{
		$this->purposeId = $purposeId;
		$this->restrictionType = $restrictionType;
		$this->vendorIds = $vendorIds;
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return json_encode($this->toArray());
	}

	public function toArray(): array
	{
		return [
			'purpose_id' => $this->purposeId,
			'restriction_type' => $this->restrictionType->getId(), // force to_string
			'vendor_ids' => $this->vendorIds,
		];
	}

	/**
	 * @return int
	 */
	public function getPurposeId(): int
	{
		return $this->purposeId;
	}

	/**
	 * @return RestrictionType
	 */
	public function getRestrictionType(): RestrictionType
	{
        return $this->restrictionType;
    }

	/**
	 * @return array
	 */
	public function getVendorIds(): array
	{
        return $this->vendorIds;
    }
}