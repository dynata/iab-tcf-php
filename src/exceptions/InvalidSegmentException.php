<?php

namespace IABTcf\Exceptions;

use Exception;

class InvalidSegmentException extends Exception {
	public function __construct(Exception $previous = null) {
		parent::__construct("Invalid Segment", 0, $previous);
	}
}