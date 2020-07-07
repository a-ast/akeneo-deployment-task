<?php

namespace Aa\DeploymentTask\DataMigration\Connector;

interface Connector
{
    public function load(array $data): void;
}
