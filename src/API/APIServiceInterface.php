<?php

namespace YouCan\Pay\API;

interface APIServiceInterface
{
    public static function setIsSandboxMode(bool $isSandboxMode): void;

    public function getPrivateKey(): ?string;

    public function getPublicKey(): ?string;
}
