<?php

namespace YouCan\Pay\API;

interface APIServiceInterface
{
    public function post(string $endpoint, array $params = []): Response;

    public function get(string $endpoint, array $params = []): Response;

    public static function setIsSandboxMode(bool $isSandboxMode): void;

    public function getPrivateKey(): ?string;

    public function getPublicKey(): ?string;
}
