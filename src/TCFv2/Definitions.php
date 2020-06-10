<?php

namespace IABTcf\TCFv2;

use IABTcf\Field;

abstract class Definitions
{
	/**
	 * @return array
	 */
	public static function getVendorVersionMap(): array
	{
		return [
			0 => [
				'version' => 2,
				'fields' => [
					0 => new Field('version', 'int', function() { return 6; }),
					1 => new Field('created', 'date', function() { return 36; }),
					2 => new Field('lastUpdated', 'date', function() { return 36; }),
					3 => new Field('cmpId', 'int', function() { return 12; }),
					4 => new Field('cmpVersion', 'int', function() { return 12; }),
					5 => new Field('consentScreen', 'int', function() { return 6; }),
					6 => new Field('consentLanguage', 'language', function() { return 12; }),
					7 => new Field('vendorListVersion', 'int', function() { return 12; }),
					8 => new Field('tcfPolicyVersion', 'int', function() { return 6; }),
					9 => new Field('isServiceSpecific', 'bool', function() { return 1; }),
					10 => new Field('useNonStandardStacks', 'bool', function() { return 1; }),
					11 => new Field('specialFeatureOptIns', 'bits', function() { return 12; }),
					12 => new Field('purposesConsentBitString', 'bits', function() { return 24; }),
					13 => new Field('purposesLITransparencyBitString', 'bits', function() { return 24; }),
					14 => new Field('purposeOneTreatment', 'bool', function() { return 1; }),
					15 => new Field('publisherCC', 'language', function() { return 12; }),
					16 => new Field('vendorConsentMaxVendorId', 'int', function() { return 16; }),
					17 => new Field('vendorConsentIsRangeEncoding', 'bool', function() { return 1; }),
					18 => new Field(
						'vendorConsentBitString',
						'bits',
						function($obj) { return $obj["vendorConsentMaxVendorId"]; },
						function($obj) { return ! $obj["vendorConsentIsRangeEncoding"]; }
					),
					19 => new Field(
						'vendorConsentNumEntries',
						'int',
						function() { return 12; },
						function($obj) { return $obj["vendorConsentIsRangeEncoding"]; }
					),
					20 => new Field(
						'vendorConsentRangeList',
						'list',
						null,
						function($obj) { return $obj["vendorConsentIsRangeEncoding"]; },
						function($obj) { return $obj["vendorConsentNumEntries"]; },
						[
							0 => new Field(
								'isARange',
								'bool',
								function() { return 1; }
							),
							1 => new Field(
								'startId',
								'int',
								function() { return 16; }
							),
							2 => new Field(
								'endId',
								'int',
								function() { return 16; },
								function($obj) { return $obj["isARange"]; }
							),
						]
					),
					21 => new Field('legitimateInterestsMaxVendorId', 'int', function() { return 16; }),
					22 => new Field('legitimateInterestsIsRangeEncoding', 'bool', function() { return 1; }),
					23 => new Field(
						'legitimateInterestsVendorIdBitString',
						'bits',
						function($obj) { return $obj["legitimateInterestsMaxVendorId"]; },
						function($obj) { return ! $obj["legitimateInterestsIsRangeEncoding"]; }
					),
					24 => new Field(
						'legitimateInterestsNumEntries',
						'int',
						function() { return 12; },
						function($obj) { return $obj["legitimateInterestsIsRangeEncoding"]; }
					),
					25 => new Field(
						'legitimateInterestsVendorRangeList',
						'list',
						null,
						function($obj) { return $obj["legitimateInterestsIsRangeEncoding"]; },
						function($obj) { return $obj["legitimateInterestsNumEntries"]; },
						[
							0 => new Field('isARange', 'bool', function() { return 1; }),
							1 => new Field('startId', 'int', function() { return 16; }),
							2 => new Field(
								'endId',
								'int',
								function() { return 16; },
								function ($obj) { return $obj["isARange"]; }
							),
						]
					),
					26 => new Field('numPubRestrictions', 'int', function() { return 12; }),
					27 => new Field(
						'pubRestrictions',
						'list',
						null,
						function($obj) { return $obj['numPubRestrictions'] > 0; },
						function($obj) { return $obj["numPubRestrictions"]; },
						[
							0 => new Field('purposeId', 'int', function() { return 6; }),
							1 => new Field('restrictionType', 'int', function() { return 2; }),
							2 => new Field('numEntries', 'int', function() { return 12; }),
							3 => new Field(
								'vendorIds',
								'list',
								null,
								function($obj) { return $obj['numEntries'] > 0; },
								function($obj) { return $obj["numEntries"]; },
								[
									0 => new Field('isARange', 'int', function() { return 1; }),
									1 => new Field('startId', 'int', function() { return 16; }),
									2 => new Field(
										'endId',
										'int',
										function() { return 16; },
										function($obj) { return $obj["isARange"]; }
									),
								]
							),
						]
					),
				],
			],
			1 => [
				'fields' => [
					0 => new Field('segmentType', 'int', function() { return 3; }),
					1 => new Field('maxVendorId', 'int', function() { return 16; }),
					2 => new Field('isRangeEncoding', 'bool', function() { return 1; }),
					3 => new Field(
						'vendorBits',
						'bits',
						function($obj) { return $obj['maxVendorId']; },
						function($obj) { return ! $obj['isRangeEncoding']; }
					),
					4 => new Field(
						'vendorNumEntries',
						'int',
						function() { return 12; },
						function($obj) { return $obj['isRangeEncoding']; }
					),
					5 => new Field(
						'vendorRangeList',
						'list',
						null,
						function($obj) { return $obj["isRangeEncoding"]; },
						function($obj) { return $obj["vendorNumEntries"]; },
						[
							0 => new Field('isARange', 'bool', function() { return 1; }),
							1 => new Field('startId', 'int', function() { return 16; }),
							2 => new Field(
								'endId',
								'int',
								function() { return 16; },
								function($obj) { return $obj["isARange"]; }
							),
						]
					),
				],
			],
			2 => [
				'fields' => [
					0 => new Field('segmentType', 'int', function() { return 3; }),
					1 => new Field('maxVendorId', 'int', function() { return 16; }),
					2 => new Field('isRangeEncoding', 'bool', function() { return 1; }),
					3 => new Field(
						'vendorBits',
						'bits',
						function ($obj) { return $obj["maxVendorId"]; },
						function ($obj) { return ! $obj["isRangeEncoding"]; }
					),
					4 => new Field(
						'vendorNumEntries',
						'int',
						function() { return 12; },
						function ($obj) { return $obj["isRangeEncoding"]; }
					),
					5 => new Field(
						'vendorRangeList',
						'list',
						null,
						function($obj) { return $obj["isRangeEncoding"]; },
						function ($obj) { return $obj["vendorNumEntries"]; },
						[
							0 => new Field('isARange', 'bool', function() { return 1; }),
							1 => new Field('startId', 'int', function() { return 16; }),
							2 => new Field(
								'endId',
								'int',
								function() { return 16; },
								function ($obj) { return $obj["isARange"]; }
							),
						]
					),
				],
			],
			3 => [
				'fields' => [
					0 => new Field('segmentType', 'int', function() { return 3; }),
					1 => new Field('pubPurposesConsent', 'bits', function() { return 24; }),
					2 => new Field('pubPurposesLITransparency', 'bits', function() { return 24; }),
					3 => new Field('numCustomPurposes', 'int', function() { return 6; }),
					4 => new Field(
						'customPurposesConsent',
						'bits',
						function($obj) { return $obj["numCustomPurposes"]; }
					),
					5 => new Field(
						'customPurposesLITransparency',
						'bits',
						function ($obj) { return $obj["numCustomPurposes"]; }
					),
				],
			],
		];
	}
}