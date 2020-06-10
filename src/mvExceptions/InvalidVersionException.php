<?php

namespace IABTcf\Exceptions;

use Exception;

class InvalidVersionException extends Exception {
	public function __construct(Exception $previous = null) {
		parent::__construct("Invalid Version", 0, $previous);
	}
}