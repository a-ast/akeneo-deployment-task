<?php

namespace Aa\DeploymentTask\DataMigration\Connector;

use Akeneo\ReferenceEntity\Infrastructure\Connector\Api\Record\CreateOrUpdateRecordAction;

class ReferenceEntityRecord implements Connector
{
    use ActionConnectorTrait;

    /**
     * @var CreateOrUpdateRecordAction
     */
    private $action;

    public function __construct(CreateOrUpdateRecordAction $action)
    {
        $this->action = $action;
    }

    public function load(array $data): void
    {
        $code = $data['code'];
        $referenceEntityIdentifier = $data['reference_entity'];

        unset($data['reference_entity']);

        $request = $this->createRequest($data, ['code' => $code]);

        $response = ($this->action)($request, $referenceEntityIdentifier, $code);

        $this->validateResponse($response);
    }
}
