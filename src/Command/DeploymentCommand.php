<?php

namespace Aa\DeploymentTask\Command;

use Akeneo\Tool\Component\Api\Exception\ViolationHttpException;
use Aa\DeploymentTask\Task\DataMigrationTask;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeploymentCommand extends Command
{
    /**
     * @var DataMigrationTask
     */
    private $dataMigrationTask;

    public function __construct(DataMigrationTask $dataMigrationTask)
    {
        $this->dataMigrationTask = $dataMigrationTask;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('pim:deployment:run-tasks')
            ->setDescription('Run deployment tasks');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->dataMigrationTask->execute(
                [
                    'migrations' => [
                        'reference_entity' => 'reference_entity',
                        'reference_entity_attribute' => 'reference_entity_attribute',
                        'association_type' => 'association_type',
                        'attribute' => 'attribute',
                        'attribute_option' => 'attribute_option',
                        'attribute_in_family' => 'attribute_in_family',
                        'job_instance' => 'job',
                    ],
                ]
            );
        } catch (ViolationHttpException $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');

            foreach ($e->getViolations() as $violation) {
                $output->writeln('<error>' . $violation . '</error>');
            }

            return 1;
        }

        return 0;
    }
}
