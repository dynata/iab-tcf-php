<?php

namespace IABTcf\Exceptions;

use Exception;

class InvalidConsentStringException extends Exception {
	public function __construct(Exception $previous = null) {
		parent::__construct("Invalid Consent String", 0, $previous);
	}
}