<?php

namespace IABTcf\TCFv1;

use IABTcf\Field;

abstract class Definitions {

	/**
	 * @return array
	 */
	public static function getVendorVersionMap(): array
	{
		return [
			0 => [
				'version' => 1,
				'fields' => [
					0 => new Field('version', 'int', function() { return 6; }),
					1 => new Field('created', 'date', function() { return 36; }),
					2 => new Field('lastUpdated', 'date', function() { return 36; }),
					3 => new Field('cmpId', 'int', function() { return 12; }),
					4 => new Field('cmpVersion', 'int', function() { return 12; }),
					5 => new Field('consentScreen', 'int', function() { return 6; }),
					6 => new Field('consentLanguage', 'language', function() { return 12; }),
					7 => new Field('vendorListVersion', 'int', function() { return 12; }),
					8 => new Field('purposeIdBitString', 'bits', function() { return 24; }),
					9 => new Field('vendorConsentMaxVendorId', 'int', function() { return 16; }),
					10 => new Field('vendorConsentIsRangeEncoding', 'bool', function() { return 1; }),
					11 => new Field(
						'vendorIdBitString',
						'bits',
						function($obj) { return $obj["vendorConsentMaxVendorId"]; },
						function($obj) { return ! $obj["vendorConsentIsRangeEncoding"]; }
					),
					12 => new Field(
						'defaultConsent',
						'bool',
						function() { return 1; },
						function($obj) { return $obj["vendorConsentIsRangeEncoding"]; }
					),
					13 => new Field(
						'vendorConsentNumEntries',
						'int',
						function() { return 12; },
						function($obj) { return $obj["vendorConsentIsRangeEncoding"]; }
					),
					14 => new Field(
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
				],
			],
		];
	}
}