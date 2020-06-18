# PHP Library for Parsing the IAB TC String

This project includes a PHP Library for working with: 

* IAB's [Transparency & Consent Framework v1.1](https://github.com/InteractiveAdvertisingBureau/GDPR-Transparency-and-Consent-Framework/blob/master/Consent%20string%20and%20vendor%20list%20formats%20v1.1%20Final.md)
* IAB's [Transparency & Consent Framework v2.0](https://github.com/InteractiveAdvertisingBureau/GDPR-Transparency-and-Consent-Framework/blob/master/TCFv2/IAB%20Tech%20Lab%20-%20Consent%20string%20and%20vendor%20list%20formats%20v2.md)

The TC String is a technical component of the IAB Europe Transparency & Consent Framework (TCF).

The General Data Protection Regulation (GDPR) requires a high level of accountability for how personal data is processed. While important to all parties in the digital advertising ecosystem, implementation of the GDPR came with heavy technical challenges.

The GDPR requires, amongst others, a legal basis for such processing. The two most relevant legal bases are the consent of the user to the processing of their personal data, and the legitimate interests of the controller or a third party to the processing of a user’s personal data, provided that the interests and fundamental rights of the user are not overriding. Both legal bases require the provision of disclosures to ensure transparency, and the opportunity for user choice either through the user’s consent to the processing of their personal data before the processing starts if the legal basis is consent, or through the user’s objection to the processing of their personal data after the processing starts if the legal basis is a legitimate interest. Under the GDPR, controllers are required to create and maintain records of compliance, including, but not limited to user consent records. This warrants clear standards for a common technical solution for all affected parties and policies to govern how that solution is used.

IAB Europe established the TCF to support compliance with the GDPR in the context of digital advertising.

## Installation

Set-up github authentication in composer

```
composer config --global --auth github-oauth.github.com <token>
```

Add the repo to your composer.json file

```
"repositories":[
    {
        "type": "vcs",
        "url": "https://github.com/dynata/iab-tcf-php.git"
    }
]
```

Run Composer install

```
composer install dynata/iabtcf
```

## Usage

### Decode a Consent String

```
$cs = IABTcf\Decoder::decode("COtybn4PA_zT4KjACBENAPCIAD-AAECAAIAAAxAAAAgAIAwgAgAAAAEAgQAAAAAEAYQAQAAAACAAAABACBQAYAAgAEEgBAABAAQA.IBAgAAAgAIAwgAgAAAAEAAAACA.Qu4QBQAGAAXABLAC8AMAu4A.cAAAAAAAITg");

// Core String
var_dump($cs->getVersion());

int(2)

var_dump($cs->getCreated()->format("c"));

string(25) "2020-01-26T17:01:00+00:00"

var_dump($cs->getLastUpdated()->format("c"));

string(25) "2021-02-02T17:01:00+00:00"

var_dump($cs->getCmpId());

int(675)

var_dump($cs->getCmpVersion());

int(2)

var_dump($cs->getConsentScreen());

int(1)

var_dump($cs->getVendorListVersion());

int(15)

var_dump($cs->getTcfPolicyVersion());

int(2)

var_dump($cs->getConsentLanguage());

string(2) "en"

var_dump($cs->getPublisherCC());

string(2) "aa"

var_dump($cs->getIsServiceSpecific());

bool(false)

var_dump($cs->getPurposeOneTreatment());

bool(true)

var_dump($cs->getUseNonStandardStacks());

bool(false)

var_dump($cs->getPurposesConsent());

array(7) {
  [0]=>
  int(3)
  [1]=>
  int(4)
  [2]=>
  int(5)
  [3]=>
  int(6)
  [4]=>
  int(7)
  [5]=>
  int(8)
  [6]=>
  int(9)
}

var_dump($cs->getVendorConsent());

array(7) {
  [0]=>
  int(23)
  [1]=>
  int(37)
  [2]=>
  int(47)
  [3]=>
  int(48)
  [4]=>
  int(53)
  [5]=>
  int(65)
  [6]=>
  int(98)
}

var_dump($cs->getVendorLegitimateInterest());

array(7) {
  [0]=>
  int(37)
  [1]=>
  int(47)
  [2]=>
  int(48)
  [3]=>
  int(53)
  [4]=>
  int(65)
  [5]=>
  int(98)
  [6]=>
  int(129)
}

var_dump($cs->getSpecialFeatureOptIns());

array(1) {
  [0]=>
  int(1)
}

var_dump($cs->getPurposesLITransparency());

array(2) {
  [0]=>
  int(2)
  [1]=>
  int(9)
}

$pubRestrictions = $cs->getPublisherRestrictions();
var_dump($pubRestrictions[0]->getPurposeId());

int(1)

var_dump($pubRestrictions[0]->getRestrictionType()->getId());

int(1)

var_dump($pubRestrictions[0]->getVendorIds());

array(2) {
  [0]=>
  int(1)
  [1]=>
  int(2)
}

// Segment 1: Disclosed vendors
var_dump($cs->getDisclosedVendors());

array(8) {
  [0]=>
  int(23)
  [1]=>
  int(37)
  [2]=>
  int(47)
  [3]=>
  int(48)
  [4]=>
  int(53)
  [5]=>
  int(65)
  [6]=>
  int(98)
  [7]=>
  int(129)
}

// Segment 2: Allowed vendors
var_dump($cs->getAllowedVendors());

array(6) {
  [0]=>
  int(12)
  [1]=>
  int(23)
  [2]=>
  int(37)
  [3]=>
  int(47)
  [4]=>
  int(48)
  [5]=>
  int(6000)
}

// Segment 3: Publisher purposes
var_dump($cs->getPubPurposesConsent());

array(1) {
  [0]=>
  int(1)
}

var_dump($cs->getPubPurposesLITransparency());

array(1) {
  [0]=>
  int(24)
}

var_dump($cs->getCustomPurposesConsent());

array(1) {
  [0]=>
  int(2)
}

var_dump($cs->getCustomPurposesLITransparency());

array(2) {
  [0]=>
  int(1)
  [1]=>
  int(2)
}
```

### Encode consent data

```
$cs = new IABTcf\ConsentString();
$cs->setVersion(2);
$cs->setCreated(new DateTime('2020-01-26 17:01:00'));
$cs->setLastUpdated(new DateTime('2021-02-02 17:01:00'));
$cs->setCmpId(675);
$cs->setCmpVersion(2);
$cs->setConsentScreen(1);
$cs->setVendorListVersion(15);
$cs->setTcfPolicyVersion(2);
$cs->setConsentLanguage('en');
$cs->setPublisherCC('aa');
$cs->setIsServiceSpecific(false);
$cs->setPurposeOneTreatment(true);
$cs->setUseNonStandardStacks(false);
$cs->setAllowedPurposes([2, 10]);
$cs->setSpecialFeatureOptIns([1]);
$cs->setPurposesConsent([3, 4, 5, 6, 7, 8, 9]);
$cs->setVendorConsent([23, 37, 47, 48, 53, 65, 98]);
$cs->setVendorLegitimateInterest([37, 47, 48, 53, 65, 98, 129]);
$cs->setPurposesLITransparency([2, 9]);
$restrictionType = new IABTcf\TCFv2\RestrictionType(1);
$cs->setPublisherRestrictions([
    new IABTcf\TCFv2\PublisherRestriction(1, $restrictionType, [1, 2]),
    new IABTcf\TCFv2\PublisherRestriction(2, $restrictionType, [1, 8])
]);

$cs->setDisclosedVendors([23, 37, 47, 48, 53, 65, 98, 129]);

$cs->setAllowedVendors([12, 23, 37, 47, 48, 6000]);

$cs->setPubPurposesConsent([1]);
$cs->setPubPurposesLITransparency([24]);
$cs->setCustomPurposesConsent([2]);
$cs->setCustomPurposesLITransparency([1, 2]);

// Encode the data into a web-safe base64 string
$enc = IABTcf\Encoder::encode($cs);


echo $enc;

Outputs:

COtybn4PA_zT4KjACBENAPCIAD-AAECAAIAAAxAAAAgAIAwgAgAAAAEAgQAAAAAEAYQAQAAAACAAAABACBQAYAAgAEEgBAABAAQA.IBAgAAAgAIAwgAgAAAAEAAAACA.Qu4QBQAGAAXABLAC8AMAu4A.cAAAAAAAITg
```
