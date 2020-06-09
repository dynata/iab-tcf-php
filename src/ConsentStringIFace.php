<?php

namespace IABTcf;

use DateTime;

interface ConsentStringIFace {
	public function getVersion(): int;
	public function setVersion(int $version);
	public function getCreated(): DateTime;
	public function setCreated(DateTime $created);
	public function getLastUpdated(): DateTime;
	public function setLastUpdated(DateTime $lastUpdated);
	public function getCmpId(): int;
	public function setCmpId(int $cmpId);
	public function getCmpVersion(): int;
	public function setCmpVersion(int $cmpVersion);
	public function getConsentScreen(): int;
	public function setConsentScreen(int $consentScreen);
	public function getConsentLanguage(): string;
	public function setConsentLanguage(string $consentLanguage);
	public function getVendorListVersion(): int;
	public function getPurposesConsent(): array;
	public function setPurposesConsent(array $purposesConsentIds);
	public function getVendorConsent(): array;
	public function setVendorConsent(array $vendorConsentIds);
	public function getDefaultVendorConsent(): bool;
	public function getTcfPolicyVersion(): int;
	public function getIsServiceSpecific(): bool;
	public function setIsServiceSpecific(bool $isServiceSpecific);
	public function getUseNonStandardStacks(): bool;
	public function setUseNonStandardStacks(bool $useNonStandardStacks);
	public function getSpecialFeatureOptIns(): array;
	public function setSpecialFeatureOptIns(array $specialFeatureOptIns);
	public function getPurposesLITransparency(): array;
	public function setPurposesLITransparency(array $purposesLITransparency);
	public function getPurposeOneTreatment(): bool;
	public function setPurposeOneTreatment(bool $purposeOneTreatment);
	public function getPublisherCC(): string;
	public function setPublisherCC(string $publisherCC);
	public function getVendorLegitimateInterest(): array;
	public function setVendorLegitimateInterest(array $vendorLegitimateInterests);
	public function getPublisherRestrictions(): array;
	public function setPublisherRestrictions(array $publisherRestrictions);
	public function getAllowedVendors(): array;
	public function setAllowedVendors(array $allowedVendorIds);
	public function getAllowedPurposes(): array; // old name for getPurposesConsent
	public function setAllowedPurposes(array $allowedPurposes); // old name for setPurposesConsent
	public function getDisclosedVendors(): array;
	public function setDisclosedVendors(array $disclosedVendorIds);
	public function getPubPurposesConsent(): array;
	public function setPubPurposesConsent(array $pubPurposesConsent);
	public function getPubPurposesLITransparency(): array;
	public function setPubPurposesLITransparency(array $pubPurposesLITransparency);
	public function getCustomPurposesConsent(): array;
	public function setCustomPurposesConsent(array $customPurposesConsent);
	public function getCustomPurposesLITransparency(): array;
	public function setCustomPurposesLITransparency(array $customPurposesLITransparency);
	public function setVendorListVersion(int $version);
	public function setTcfPolicyVersion(int $version);
}