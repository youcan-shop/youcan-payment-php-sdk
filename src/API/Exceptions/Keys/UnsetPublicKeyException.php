<?php

namespace YouCan\Pay\API\Exceptions\Keys;

use YouCan\Pay\API\Exceptions\BaseException;

class UnsetPublicKeyException extends BaseException
{
    public function __construct() {
        parent::__construct("public key not set");
    }
}
