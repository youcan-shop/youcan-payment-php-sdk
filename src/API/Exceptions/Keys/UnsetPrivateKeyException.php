<?php

namespace YouCan\Pay\API\Exceptions\Keys;

use YouCan\Pay\API\Exceptions\BaseException;

class UnsetPrivateKeyException extends BaseException
{
    public function __construct() {
        parent::__construct("private key not set");
    }
}
