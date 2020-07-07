<?php

namespace Aa\DeploymentTask\Task;

use Aa\DeploymentTask\DataMigration\DataLoader;
use Symfony\Component\Yaml\Yaml;
use Webmozart\Assert\Assert;

class DataMigrationTask
{
    /**
     * @var DataLoader
     */
    private $dataLoader;

    /**
     * @var string
     */
    private $dataMigrationPath;

    public function __construct(string $dataMigrationPath, DataLoader $dataLoader)
    {
        $this->dataMigrationPath = $dataMigrationPath;
        $this->dataLoader = $dataLoader;
    }

    public function execute(array $config)
    {
        Assert::isArray($config['migrations']);

        foreach ($config['migrations'] as $alias => $fileName) {
            $this->loadMigrationFile($alias, $fileName);
        }
    }

    private function loadMigrationFile(string $alias, string $fileName): void
    {
        $data = Yaml::parseFile($this->dataMigrationPath.$fileName.'.yaml');

        $this->dataLoader->load($alias, $data);
    }
}
