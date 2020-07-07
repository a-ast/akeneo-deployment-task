<?php

namespace Aa\DeploymentTask\DataMigration\Connector;

use Akeneo\Pim\Structure\Bundle\Controller\ExternalApi\AssociationTypeController;

class Association implements Connector
{
    use ActionConnectorTrait;

    /**
     * @var AssociationTypeController
     */
    private $controller;

    public function __construct(AssociationTypeController $controller)
    {
        $this->controller = $controller;
    }

    public function load(array $data): void
    {
        $request = $this->createRequest($data);

        $this->controller->partialUpdateAction($request, $data['code']);
    }
}
