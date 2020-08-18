<?php

namespace Aa\DeploymentTask\DataMigration\Connector;

use Akeneo\ReferenceEntity\Infrastructure\Connector\Api\Attribute\CreateOrUpdateAttributeOptionAction;
use Symfony\Component\HttpFoundation\Request;

class ReferenceEntityAttributeOption implements Connector
{
    use ActionConnectorTrait;

    /**
     * @var CreateOrUpdateAttributeOptionAction
     */
    private $action;

    public function __construct(CreateOrUpdateAttributeOptionAction $action)
    {
        $this->action = $action;
    }

    public function load(array $data): void
    {
        $referenceEntity = $data['reference_entity'];
        $attribute = $data['attribute'];

        unset($data['reference_entity']);
        unset($data['attribute']);

        $request = $this->createRequest($data);

        $response = ($this->action)($request, $referenceEntity, $attribute, $data['code']);

        $this->validateResponse($response);
    }
}
