<?php

namespace Aa\DeploymentTask\DataMigration\Connector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait ActionConnectorTrait
{
    public function createRequest(array $data): Request
    {
        $content = json_encode($data);

        return new Request([], [], [], [], [], [], $content);
    }

    public function validateResponse(Response $response): void
    {
        if (true === $response->isSuccessful()) {
            return;
        }

        $responseData = json_decode($response->getContent(), true);

        if (false === $responseData['message']) {
            return;
        }

        $violations = [$responseData['message']];
        foreach ($responseData['errors'] ?? [] as $error) {
            $violations[] = $error['message'] ?? '';
        }

        throw new \Exception(implode(PHP_EOL, $violations));
    }
}
