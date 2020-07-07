<?php

namespace Aa\DeploymentTask\DataMigration\Connector;

use Akeneo\Pim\Structure\Bundle\Controller\ExternalApi\AttributeOptionController;

class AttributeOption implements Connector
{
    use ActionConnectorTrait;

    /**
     * @var AttributeOptionController
     */
    private $controller;

    public function __construct(AttributeOptionController $controller)
    {
        $this->controller = $controller;
    }

    public function load(array $data): void
    {
        $request = $this->createRequest($data);

        $this->controller->partialUpdateAction($request, $data['attribute'], $data['code']);
    }
}
