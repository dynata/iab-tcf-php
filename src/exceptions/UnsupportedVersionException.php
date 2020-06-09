<?php

namespace IABTcf\Exceptions;

use Exception;

class UnsupportedVersionException extends Exception {
	public function __construct(Exception $previous = null) {
		parent::__construct("Version is not Supported", 0, $previous);
	}
}