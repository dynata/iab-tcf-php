<?php

namespace IABTcf;

interface EncoderIFace {
	public static function encode(ConsentString $consentString): string;
}