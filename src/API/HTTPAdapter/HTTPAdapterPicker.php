<?php

namespace YouCan\Pay\API\HTTPAdapter;

use Exception;

class HTTPAdapterPicker
{
    /**
     * @throws Exception
     */
    public function pickAdapter(bool $isSandboxMode): HTTPAdapter
    {
        if ($this->guzzleIsDetected()) {
            $guzzleVersion = $this->guzzleMajorVersionNumber();

            if (in_array($guzzleVersion, [6, 7])) {
                return new Guzzle67HTTPAdapter($isSandboxMode);
            }

            throw new Exception('unsupported guzzle version, we support 6 or 7');
        }

        return new CurlHTTPAdapter($isSandboxMode);
    }

    private function guzzleIsDetected(): bool
    {
        return interface_exists("\GuzzleHttp\ClientInterface");
    }

    private function guzzleMajorVersionNumber(): ?int
    {
        if (defined('\GuzzleHttp\ClientInterface::MAJOR_VERSION')) {
            return (int) \GuzzleHttp\ClientInterface::MAJOR_VERSION;
        }

        if (defined('\GuzzleHttp\ClientInterface::VERSION')) {
            return (int) \GuzzleHttp\ClientInterface::VERSION[0];
        }

        return null;
    }
}
