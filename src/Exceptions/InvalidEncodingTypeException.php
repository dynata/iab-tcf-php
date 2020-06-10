<?php

namespace IABTcf\Exceptions;

use Exception;

class InvalidEncodingTypeException extends Exception {
	public function __construct(Exception $previous = null) {
		parent::__construct("Invalid Encoding Type", 0, $previous);
	}
}