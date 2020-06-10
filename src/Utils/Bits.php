<?php

namespace IABTcf\Utils;

use DateTime;
use IABTcf\Definitions;
use IABTcf\Exceptions\InvalidConsentStringException;
use IABTcf\Exceptions\InvalidEncodingTypeException;
use IABTcf\Exceptions\InvalidSegmentException;
use IABTcf\Exceptions\InvalidVersionException;
use IABTcf\Field;

class Bits
{
	/**
	 * @param $bitString
	 * @return array
	 */
	public static function decodeBitsToIds(string $bitString): array
	{
		$index = 0;
		$reduce = function ($acc, $bit) use (&$index) {
			if ($bit === '1' && (array_search($index + 1, $acc) === false)) {
				$acc[] = $index + 1;
			}
			$index++;

			return $acc;
		};
		$bitExploded = str_split($bitString, 1);

		return array_reduce($bitExploded, $reduce, []);
	}

	/**
	 * @param  string $bitString
	 * @param  int $start
	 * @param  int $length
	 * @return DateTime
	 */
	private static function decodeBitsToDate(string $bitString, int $start, int $length): DateTime
	{
		$date = new DateTime;
		$date->setTimestamp(self::decodeBitsToInt($bitString, $start, $length) / 10);

		return $date;
	}

	/**
	 * @param  string $bitString
	 * @return string
	 */
	private static function decodeBitsToLetter(string $bitString): string
	{
		$letterCode = self::decodeBitsToInt($bitString);

		return strtolower(chr($letterCode + 65));
	}

	/**
	 * @param  string $bitString
	 * @param  int $start
	 * @return bool
	 */
	private static function decodeBitsToBool(string $bitString, int $start): bool
	{
		return intval(substr($bitString, $start, 1), 2) === 1;
	}

	/**
	 * @param  int $count
	 * @param  string $string
	 * @return string
	 */
	private static function repeat(int $count, string $string = '0'): string
	{
		$padString = "";
		for ($i = 0; $i < $count; $i++) {
			$padString .= $string;
		}
		return $padString;
	}

	/**
	 * @param  string $string
	 * @param  int $padding
	 * @return string
	 */
	public static function padLeft(string $string, int $padding): string
	{
		return self::repeat(max([0, $padding])) . $string;
	}

	/**
	 * @param  string $string
	 * @param  int $padding
	 * @return string
	 */
	public static function padRight(string $string, int $padding): string
	{
		return $string . self::repeat(max([0, $padding]));
	}

	/**
	 * @param string $bitString
	 * @param int $start
	 * @param int $length
	 * @return int
	 */
	private static function decodeBitsToInt(string $bitString, int $start = 0, int $length = 0): int
	{
		if ($start === 0 && $length === 0) {
			return intval($bitString, 2);
		}

		return intval(substr($bitString, $start, $length), 2);
	}

	/**
	 * @param  int $number
	 * @param  int $numBits
	 * @return string
	 */
	private static function encodeIntToBits(int $number, int $numBits = null): string
	{
		$bitString = "";
		if (is_numeric($number)) {
			$bitString = decbin(intval($number, 10));
		}
		// Pad the string if not filling all bits
		if (! is_null($numBits) && $numBits >= strlen($bitString)) {
			$bitString = self::padLeft($bitString, $numBits - strlen($bitString));
		}
		// Truncate the string if longer than the number of bits
		if (! is_null($numBits) && strlen($bitString) > $numBits) {
			$bitString = substr($bitString, 0, $numBits);
		}

		return $bitString;
	}

	/**
	 * @param  bool $value
	 * @return string
	 */
	private static function encodeBoolToBits(bool $value): string
	{
		return self::encodeIntToBits($value === true ? 1 : 0, 1);
	}

	/**
	 * @param  DateTime $date
	 * @param  int      $numBits
	 * @return string
	 */
	private static function encodeDateToBits(DateTime $date, int $numBits = null): string
	{
		return self::encodeIntToBits($date->getTimestamp() * 10, $numBits);
	}

	/**
	 * @param  string $letter
	 * @param  int    $numBits
	 * @return string
	 */
	private static function encodeLetterToBits(string $letter, int $numBits = null): string
	{
		$upperLetter = strtoupper($letter);
		return self::encodeIntToBits(ord($upperLetter[0]) - 65, $numBits);
	}

	/**
	 * @param  string $language
	 * @param  int    $numBits
	 * @return string
	 */
	private static function encodeLanguageToBits(string $language, int $numBits = 12): string
	{
		return self::encodeLetterToBits(substr($language, 0, 1), $numBits / 2) . self::encodeLetterToBits(substr($language, 1), $numBits / 2);
	}

	/**
	 * @param  string $string
	 * @return string
	 * @throws InvalidConsentStringException
	 */
	public static function decodeFromBase64(string $string): string
	{
		// add padding
		while (strlen($string) % 4 !== 0) {
			$string .= "=";
		}
		// replace unsafe characters
		$string = str_replace("-", "+", $string);
		$string = str_replace("_", "/", $string);

		$bytes = base64_decode($string, true);
		if ($bytes === false) {
			throw new InvalidConsentStringException();
		}
		$inputBits = "";
		for ($i = 0; $i < strlen($bytes); $i++) {
			$bitString = decbin(ord($bytes[$i]));
			$inputBits .= self::padLeft($bitString, 8 - strlen($bitString));
		}
		return $inputBits;
	}

	/**
	 * @param  string $bitString
	 * @return int
	 * @throws InvalidVersionException
	 */
	public static function extractVersion(string $bitString): int {
		$version = self::decodeBitsToInt($bitString, 0, Definitions::getVersionNumBits());
		if (! is_int($version)) {
			throw new InvalidVersionException();
		}

		return $version;
	}

	/**
	 * @param  string $bitString
	 * @return int
	 * @throws InvalidSegmentException
	 */
	public static function extractSegment(string $bitString): int {
		$segmentId = self::decodeBitsToInt($bitString, 0, Definitions::getSegmentNumBits());
		if (! is_int($segmentId)) {
			throw new InvalidSegmentException();
		}

		return $segmentId;
	}

	/**
	 * @param string $bitString
	 * @param array $definitionMap
	 * @return array
	 */
	public static function decodeConsentStringBitValue(string $bitString, array $definitionMap): array
	{
		$decodedObject = self::decodeFields($bitString, $definitionMap['fields']);
		unset($decodedObject['newPosition']);

		return $decodedObject;
	}

	/**
	 * @param array $input
	 * @param Field $field
	 * @param bool $validate
	 * @return string
	 * @throws InvalidEncodingTypeException
	 */
	public static function encodeField(array $input, Field $field, bool $validate = true): string
	{
		if ($validate && (! $field->getValidator()($input))) {
			return '';
		}
		$bitCount = $field->getNumBits()($input);
		$inputValue = $input[$field->getName()];
		$fieldValue = is_null($inputValue) ? '' : $inputValue;
		switch ($field->getType()) {
			case 'int':
				return self::encodeIntToBits($fieldValue, $bitCount);
			case 'bool':
				return self::encodeBoolToBits($fieldValue);
			case 'date':
				return self::encodeDateToBits($fieldValue, $bitCount);
			case 'bits':
				return substr(self::padRight($fieldValue, $bitCount - strlen($fieldValue)), 0, $bitCount);
			case 'list':
				$reduce = function ($acc, $listValue) use ($field) {
					return $acc . self::encodeFields($listValue, $field->getFields());
				};
				return array_reduce($fieldValue, $reduce, '');
			case 'language':
				return self::encodeLanguageToBits($fieldValue, $bitCount);
			default:
				throw new InvalidEncodingTypeException();
		}
	}

	/**
	 * @param array $input
	 * @param array $fields
	 * @return string
	 */
	private static function encodeFields(array $input, $fields): string
	{
		$reduce = function (string $acc, Field $field) use ($input) {
			return $acc . self::encodeField($input, $field);
		};

		return array_reduce($fields, $reduce, '');
	}

	public static function encodeBitStringToBase64(string $bitString): string {
		// Pad length to multiple of 8
		$paddedBinaryValue = self::padRight($bitString, 7 - ((strlen($bitString) + 7) % 8));
		$bytes = "";
		for ($i = 0; $i < strlen($paddedBinaryValue); $i += 8) {
			$bytes .= chr(intval(substr($paddedBinaryValue, $i, 8), 2));
		}
		// Make base64 string URL friendly
		return self::urlSafeB64Encode($bytes);
	}
	/**
	 * @param string $bytes
	 * @return string
	 */
	private static function urlSafeB64Encode(string $bytes): string {
		return str_replace(
			"+",
			"-",
			str_replace(
				"/",
				"_",
				rtrim(base64_encode($bytes), '=')
			)
		);
	}

	/**
	 * @param string $bitString
	 * @param array $output
	 * @param int $startPosition
	 * @param Field $field
	 * @return array
	 * @throws InvalidEncodingTypeException
	 */
	private static function decodeField(string $bitString, array $output, int $startPosition, Field $field): array
	{
		if (! $field->getValidator()($output)) {
			// Not decoding this field so make sure we start parsing the next field at the same point
			return ['newPosition' => $startPosition];
		}

		$returnValue = [];
		$bitCount = $field->getNumBits()($output);
		if (! is_null($bitCount)) {
			$returnValue['newPosition'] = $startPosition + $bitCount;
		}

		switch ($field->getType()) {
			case 'int':
				return array_merge(['fieldValue' => self::decodeBitsToInt($bitString, $startPosition, $bitCount)], $returnValue);
			case 'bool':
				return array_merge(['fieldValue' => self::decodeBitsToBool($bitString, $startPosition)], $returnValue);
			case 'date':
				return array_merge(['fieldValue' => self::decodeBitsToDate($bitString, $startPosition, $bitCount)], $returnValue);
			case 'bits':
				return array_merge(['fieldValue' => substr($bitString, $startPosition, $bitCount)], $returnValue);
			case 'list':
				return array_merge(self::decodeList($bitString, $output, $startPosition, $field), $returnValue);
			case 'language':
				return array_merge(['fieldValue' => self::decodeBitsToLanguage($bitString, $startPosition, $bitCount)], $returnValue);
			default:
				throw new InvalidEncodingTypeException();
		}
	}

	/**
	 * @param string $bitString
	 * @param array $output
	 * @param int $startPosition
	 * @param Field $field
	 * @return array
	 */
	private static function decodeList(string $bitString, array $output, int $startPosition, Field $field): array
	{
		$listEntryCount = $field->getListCount()($output);
		if (is_null($listEntryCount)) {
			$listEntryCount = 0;
		}

		$fields = $field->getFields();
		$newPosition = $startPosition;
		$fieldValue = [];
		for ($i = 0; $i < $listEntryCount; $i++) {
			$decodedFields = self::decodeFields($bitString, $fields, $newPosition);
			$newPosition = $decodedFields['newPosition'];
			unset($decodedFields['newPosition']);
			$fieldValue[] = $decodedFields;
		}

		return ['fieldValue' => $fieldValue, 'newPosition' => $newPosition];
	}

	/**
	 * @param string $bitString
	 * @param int $start
	 * @param int $length
	 * @return string
	 */
	private static function decodeBitsToLanguage(string $bitString, int $start, int $length): string
	{
		$languageBitString = substr($bitString, $start, $length);

		return self::decodeBitsToLetter(substr($languageBitString, 0, $length / 2)) . self::decodeBitsToLetter(substr($languageBitString, $length / 2));
	}

	/**
	 * @param string $bitString
	 * @param array $fields
	 * @param int $startPosition
	 * @return array
	 */
	private static function decodeFields(string $bitString, array $fields, int $startPosition = 0): array
	{
		$position = $startPosition;
		$reducer = function(array $acc, Field $field) use ($bitString, &$position): array {
			$fieldDecoded = self::decodeField($bitString, $acc, $position, $field);
			$fieldValue = isset($fieldDecoded['fieldValue']) ? $fieldDecoded['fieldValue'] : null;
			$newPosition = isset($fieldDecoded['newPosition']) ? $fieldDecoded['newPosition'] : null;
			if (! is_null($fieldValue)) {
				$acc[$field->getName()] = $fieldValue;
			}
			if (! is_null($newPosition)) {
				$position = $newPosition;
			}

			return $acc;
		};
		$decodedObject = array_reduce($fields, $reducer, []);
		$decodedObject['newPosition'] = $position;

		return $decodedObject;
	}
}