<?php
namespace IABTcf;

use DateTime;

class ConsentString implements ConsentStringIFace {

	protected $created = null;
	protected $lastUpdated = null;
	protected $version = 1;
	protected $vendorListVersion = null;
	protected $cmpId = null;
	protected $cmpVersion = null;
	protected $tcfPolicyVersion = null;
	protected $consentScreen = null;
	protected $consentLanguage = null;
	protected $isServiceSpecific = null;
	protected $purposeOneTreatment = null;
	protected $useNonStandardStacks = null;
	protected $publisherCC = null;
	protected $allowedVendorIds = [];
	protected $specialFeatureOptIns = [];
	protected $purposesConsentIds = [];
	protected $purposesLITransparency = [];
	protected $vendorConsentIds = [];
	protected $vendorLegitimateInterests = [];
	protected $disclosedVendorIds = [];
	protected $pubPurposesConsent = [];
	protected $pubPurposesLITransparency = [];
	protected $customPurposesConsent = [];
	protected $customPurposesLITransparency = [];
	protected $pubRestrictions = [];

	/**
	 * ConsentString constructor.
	 * @param array $params
	 */
	public function __construct($params = [])
	{
		foreach ($params as $key => $value) {
			if (property_exists($this, $key)) {
				$this->{$key} = $value;
			}
		}
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		$arr = $this->toArray();
		$arr['created'] = $arr['created']->format("c");
		$arr['lastUpdated'] = $arr['lastUpdated']->format("c");

		return json_encode($arr);
	}

	public function toArray(): array
	{
		$pubRestrictions = [];
		foreach ($this->pubRestrictions as $restriction) {
			$pubRestrictions[] = $restriction->toArray();
		}
		return [
			'created' => $this->created,
			'lastUpdated' => $this->lastUpdated,
			'version' => $this->version,
			'vendorListVersion' => $this->vendorListVersion,
			'cmpId' => $this->cmpId,
			'cmpVersion' => $this->cmpVersion,
			'tcfPolicyVersion' => $this->tcfPolicyVersion,
			'consentScreen' => $this->consentScreen,
			'consentLanguage' => $this->consentLanguage,
			'isServiceSpecific' => $this->isServiceSpecific,
			'purposeOneTreatment' => $this->purposeOneTreatment,
			'useNonStandardStacks' => $this->useNonStandardStacks,
			'publisherCC' => $this->publisherCC,
			'allowedVendorsIds' => $this->allowedVendorIds,
			'specialFeatureOptIns' => $this->specialFeatureOptIns,
			'purposesConsentIds' => $this->purposesConsentIds,
			'purposesLITransparency' => $this->purposesLITransparency,
			'vendorConsentIds' => $this->vendorConsentIds,
			'vendorLegitimateInterests' => $this->vendorLegitimateInterests,
			'disclosedVendorIds' => $this->disclosedVendorIds,
			'pubPurposesConsent' => $this->pubPurposesConsent,
			'pubPurposesLITransparency' => $this->pubPurposesLITransparency,
			'customPurposesConsent' => $this->customPurposesConsent,
			'customPurposesLITransparency' => $this->customPurposesLITransparency,
			'pubRestrictions' => $pubRestrictions,
		];
	}

	/**
	 * @return int
	 */
	public function getVersion(): int
	{
		return $this->version;
	}

	/**
	 * @param int $version
	 */
	public function setVersion(int $version)
	{
		$this->version = $version;
	}

	/**
	 * @return DateTime
	 */
	public function getCreated(): DateTime
	{
		return $this->created;
	}

	/**
	 * @param DateTime $created
	 */
	public function setCreated(DateTime $created)
	{
		$this->created = $created;
	}

	/**
	 * @return DateTime
	 */
	public function getLastUpdated(): DateTime
	{
		return $this->lastUpdated;
	}

	/**
	 * @param DateTime $lastUpdated
	 */
	public function setLastUpdated(DateTime $lastUpdated)
	{
		$this->lastUpdated = $lastUpdated;
	}

	/**
	 * @return int
	 */
	public function getCmpId(): int
	{
		return $this->cmpId;
	}

	/**
	 * @param int $cmpId
	 */
	public function setCmpId(int $cmpId)
	{
		$this->cmpId = $cmpId;
	}

	/**
	 * @return int
	 */
	public function getCmpVersion(): int
	{
		return $this->cmpVersion;
	}

	/**
	 * @param int $cmpVersion
	 */
	public function setCmpVersion(int $cmpVersion)
	{
		$this->cmpVersion = $cmpVersion;
	}

	/**
	 * @return int
	 */
	public function getConsentScreen(): int
	{
		return $this->consentScreen;
	}

	/**
	 * @param int $consentScreen
	 */
	public function setConsentScreen(int $consentScreen)
	{
		$this->consentScreen = $consentScreen;
	}

	/**
	 * @return string
	 */
	public function getConsentLanguage(): string
	{
		return $this->consentLanguage;
	}

	/**
	 * @param string $consentLanguage
	 */
	public function setConsentLanguage(string $consentLanguage)
	{
		$this->consentLanguage = $consentLanguage;
	}

	/**
	 * @return int
	 */
	public function getVendorListVersion(): int
	{
		return $this->vendorListVersion;
	}

	/**
	 * @return array
	 */
	public function getPurposesConsent(): array
	{
		return $this->purposesConsentIds;
	}

	/**
	 * @param array $purposesConsentIds
	 */
	public function setPurposesConsent(array $purposesConsentIds)
	{
		$this->purposesConsentIds = $purposesConsentIds;
	}

	/**
	 * @return array
	 */
	public function getVendorConsent(): array
	{
		return $this->vendorConsentIds;
	}

	/**
	 * @param array $vendorConsentIds
	 */
	public function setVendorConsent(array $vendorConsentIds)
	{
		$this->vendorConsentIds = $vendorConsentIds;
	}

	/**
	 * @return bool
	 */
	public function getDefaultVendorConsent(): bool
	{
		return false;
	}

	/**
	 * @return int
	 */
	public function getTcfPolicyVersion(): int
	{
		return $this->tcfPolicyVersion;
	}

	/**
	 * @return bool
	 */
	public function getIsServiceSpecific(): bool
	{
		return $this->isServiceSpecific;
	}

	/**
	 * @param bool $isServiceSpecific
	 */
	public function setIsServiceSpecific(bool $isServiceSpecific)
	{
		$this->isServiceSpecific = $isServiceSpecific;
	}

	/**
	 * @return bool
	 */
	public function getUseNonStandardStacks(): bool
	{
		return $this->useNonStandardStacks;
	}

	/**
	 * @param bool $useNonStandardStacks
	 */
	public function setUseNonStandardStacks(bool $useNonStandardStacks)
	{
		$this->useNonStandardStacks = $useNonStandardStacks;
	}

	/**
	 * @return array
	 */
	public function getSpecialFeatureOptIns(): array
	{
		return $this->specialFeatureOptIns;
	}

	/**
	 * @param array $specialFeatureOptIns
	 */
	public function setSpecialFeatureOptIns(array $specialFeatureOptIns)
	{
		$this->specialFeatureOptIns = $specialFeatureOptIns;
	}

	/**
	 * @return array
	 */
	public function getPurposesLITransparency(): array
	{
		return $this->purposesLITransparency;
	}

	/**
	 * @param array $purposesLITransparency
	 */
	public function setPurposesLITransparency(array $purposesLITransparency)
	{
		$this->purposesLITransparency = $purposesLITransparency;
	}

	/**
	 * @return bool
	 */
	public function getPurposeOneTreatment(): bool
	{
		return $this->purposeOneTreatment;
	}

	/**
	 * @param bool $purposeOneTreatment
	 */
	public function setPurposeOneTreatment(bool $purposeOneTreatment)
	{
		$this->purposeOneTreatment = $purposeOneTreatment;
	}

	/**
	 * @return string
	 */
	public function getPublisherCC(): string
	{
		return $this->publisherCC;
	}

	/**
	 * @param string $publisherCC
	 */
	public function setPublisherCC(string $publisherCC)
	{
		$this->publisherCC = $publisherCC;
	}

	/**
	 * @return array
	 */
	public function getVendorLegitimateInterest(): array
	{
		return $this->vendorLegitimateInterests;
	}

	/**
	 * @param array $vendorLegitimateInterests
	 */
	public function setVendorLegitimateInterest(array $vendorLegitimateInterests)
	{
		$this->vendorLegitimateInterests = $vendorLegitimateInterests;
	}

	/**
	 * @return array
	 */
	public function getPublisherRestrictions(): array
	{
		return $this->pubRestrictions;
	}

	/**
	 * @param array $pubRestrictions
	 */
	public function setPublisherRestrictions(array $pubRestrictions)
	{
		$this->pubRestrictions = $pubRestrictions;
	}

	/**
	 * @return array
	 */
	public function getAllowedVendors(): array
	{
		return $this->allowedVendorIds;
	}

	/**
	 * @param array $allowedVendorIds
	 */
	public function setAllowedVendors(array $allowedVendorIds)
	{
		$this->allowedVendorIds = $allowedVendorIds;
	}

	/**
	 * @return array
	 */
	public function getAllowedPurposes(): array
	{
		return $this->getPurposesConsent();
	}

	/**
	 * @param array $allowedPurposes
	 */
	public function setAllowedPurposes(array $allowedPurposes)
	{
		$this->purposesConsentIds = $allowedPurposes;
	}

	/**
	 * @return array
	 */
	public function getDisclosedVendors(): array
	{
		return $this->disclosedVendorIds;
	}

	/**
	 * @param array $disclosedVendorIds
	 */
	public function setDisclosedVendors(array $disclosedVendorIds)
	{
		$this->disclosedVendorIds = $disclosedVendorIds;
	}

	/**
	 * @return array
	 */
	public function getPubPurposesConsent(): array
	{
		return $this->pubPurposesConsent;
	}

	/**
	 * @param array $pubPurposesConsent
	 */
	public function setPubPurposesConsent(array $pubPurposesConsent)
	{
		$this->pubPurposesConsent = $pubPurposesConsent;
	}

	/**
	 * @return array
	 */
	public function getPubPurposesLITransparency(): array
	{
		return $this->pubPurposesLITransparency;
	}

	/**
	 * @param array $pubPurposesLITransparency
	 */
	public function setPubPurposesLITransparency(array $pubPurposesLITransparency)
	{
		$this->pubPurposesLITransparency = $pubPurposesLITransparency;
	}

	/**
	 * @return array
	 */
	public function getCustomPurposesConsent(): array
	{
		return $this->customPurposesConsent;
	}

	/**
	 * @param array $customPurposesConsent
	 */
	public function setCustomPurposesConsent(array $customPurposesConsent)
	{
		$this->customPurposesConsent = $customPurposesConsent;
	}

	/**
	 * @return array
	 */
	public function getCustomPurposesLITransparency(): array
	{
		return $this->customPurposesLITransparency;
	}

	/**
	 * @param array $customPurposesLITransparency
	 */
	public function setCustomPurposesLITransparency(array $customPurposesLITransparency)
	{
		$this->customPurposesLITransparency = $customPurposesLITransparency;
	}

	/**
	 * @param int $version
	 */
	public function setVendorListVersion(int $version)
	{
		$this->vendorListVersion = $version;
	}

	/**
	 * @param int $version
	 */
	public function setTcfPolicyVersion(int $version)
	{
		$this->tcfPolicyVersion = $version;
	}
}