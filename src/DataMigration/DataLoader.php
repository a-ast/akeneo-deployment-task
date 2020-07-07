<?php

namespace Aa\DeploymentTask\DataMigration;

use Aa\DeploymentTask\DataMigration\Connector\Connector;

class DataLoader
{
    /**
     * @var Connector[]
     */
    private $connectors;

    public function __construct(array $connectors)
    {
        $this->connectors = $connectors;
    }

    public function load(string $alias, array $items)
    {
        if (false === isset($this->connectors[$alias])) {
            throw new \Exception(sprintf('Data type %s not found', $alias));
        }

        $connector = $this->connectors[$alias];

        foreach ($items as $item) {
            $connector->load($item);
        }
    }
}
