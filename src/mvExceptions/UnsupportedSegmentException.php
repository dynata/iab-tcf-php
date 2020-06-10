<?php

namespace IABTcf\Exceptions;

use Exception;

class UnsupportedSegmentException extends Exception {
	public function __construct(Exception $previous = null) {
		parent::__construct("Segment ID is not Supported", 0, $previous);
	}
}