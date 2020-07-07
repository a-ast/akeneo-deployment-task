<?php

namespace Aa\DeploymentTask\DataMigration\Connector;

use Akeneo\Pim\Structure\Bundle\Controller\ExternalApi\AttributeController;

class Attribute implements Connector
{
    use ActionConnectorTrait;

    /**
     * @var AttributeController
     */
    private $controller;

    public function __construct(AttributeController $controller)
    {
        $this->controller = $controller;
    }

    public function load(array $data): void
    {
        $request = $this->createRequest($data);

        $this->controller->partialUpdateAction($request, $data['code']);
    }
}
