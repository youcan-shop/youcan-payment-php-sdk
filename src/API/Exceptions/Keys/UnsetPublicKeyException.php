<?php

namespace YouCan\Pay\API\Exceptions\Keys;

use YouCan\Pay\API\Exceptions\ValidationException;

class UnsetPublicKeyException extends ValidationException
{
    public function __construct() {
        parent::__construct("public key not set");
    }
}
