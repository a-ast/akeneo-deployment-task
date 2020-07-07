<?php

namespace Aa\DeploymentTask\DataMigration\Connector;

use Akeneo\ReferenceEntity\Infrastructure\Connector\Api\ReferenceEntity\CreateOrUpdateReferenceEntityAction;
use Symfony\Component\HttpFoundation\Request;

class ReferenceEntity implements Connector
{
    use ActionConnectorTrait;

    /**
     * @var CreateOrUpdateReferenceEntityAction
     */
    private $action;

    public function __construct(CreateOrUpdateReferenceEntityAction $action)
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
