<?php

namespace YouCan\Pay\API\Exceptions\Keys;

use YouCan\Pay\API\Exceptions\ValidationException;

class UnsetPrivateKeyException extends ValidationException
{
    public function __construct() {
        parent::__construct("private key not set");
    }
}
