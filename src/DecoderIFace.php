<?php

namespace IABTcf;

interface DecoderIFace {
	public static function decode(string $consentString): ConsentString;
}