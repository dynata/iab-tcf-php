<?php

namespace IABTcf\Exceptions;

use Exception;

class FieldNotFoundException extends Exception {
	public function __construct(Exception $previous = null) {
		parent::__construct("Field not found", 0, $previous);
	}
}