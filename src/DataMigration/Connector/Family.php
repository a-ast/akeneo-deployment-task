<?php

namespace Aa\DeploymentTask\DataMigration\Connector;

use Akeneo\Pim\Structure\Bundle\Controller\ExternalApi\FamilyController;

class Family implements Connector
{
    use ActionConnectorTrait;

    /**
     * @var FamilyController
     */
    private $controller;

    public function __construct(FamilyController $controller)
    {
        $this->controller = $controller;
    }

    public function load(array $data): void
    {
        $request = $this->createRequest($data);

        $this->controller->partialUpdateAction($request, $data['code']);
    }
}
