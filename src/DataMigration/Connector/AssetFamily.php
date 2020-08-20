<?php

namespace Aa\DeploymentTask\DataMigration\Connector;

use Akeneo\AssetManager\Infrastructure\Connector\Api\AssetFamily\CreateOrUpdateAssetFamilyAction;
use Symfony\Component\HttpFoundation\Request;

class AssetFamily implements Connector
{
    use ActionConnectorTrait;

    /**
     * @var CreateOrUpdateAssetFamilyAction
     */
    private $action;

    public function __construct(CreateOrUpdateAssetFamilyAction $action)
    {
        $this->action = $action;
    }

    public function load(array $data): void
    {
        $request = $this->createRequest($data);

        $response = ($this->action)($request, $data['code']);

        $this->validateResponse($response);
    }
}
