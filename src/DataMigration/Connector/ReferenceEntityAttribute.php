<?php

namespace Aa\DeploymentTask\DataMigration\Connector;

use Akeneo\ReferenceEntity\Infrastructure\Connector\Api\Attribute\CreateOrUpdateAttributeAction;
use Symfony\Component\HttpFoundation\Request;

class ReferenceEntityAttribute implements Connector
{
    use ActionConnectorTrait;

    /**
     * @var CreateOrUpdateAttributeAction
     */
    private $action;

    public function __construct(CreateOrUpdateAttributeAction $action)
    {
        $this->action = $action;
    }

    public function load(array $data): void
    {
        $referenceEntity = $data['reference_entity'];
        unset($data['reference_entity']);

        $request = $this->createRequest($data);

        $response = ($this->action)($request, $referenceEntity, $data['code']);

        $this->validateResponse($response);
    }
}
