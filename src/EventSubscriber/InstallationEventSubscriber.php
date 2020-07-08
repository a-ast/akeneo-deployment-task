<?php

namespace Aa\DeploymentTask\EventSubscriber;

use Akeneo\Platform\Bundle\InstallerBundle\Event\InstallerEvent;
use Akeneo\Platform\Bundle\InstallerBundle\Event\InstallerEvents;
use Aa\DeploymentTask\Task\ApiClientTask;
use Aa\DeploymentTask\Task\DataMigrationTask;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InstallationEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var ApiClientTask
     */
    private $apiClientCreator;

    /**
     * @var DataMigrationTask
     */
    private $dataMigrationTask;

    public function __construct(
        ApiClientTask $apiClientCreator,
        DataMigrationTask $dataMigrationTask
    ) {
        $this->apiClientCreator = $apiClientCreator;
        $this->dataMigrationTask = $dataMigrationTask;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            InstallerEvents::PRE_LOAD_FIXTURES => 'onPreLoadFixtures',
            InstallerEvents::POST_LOAD_FIXTURES => 'onPostLoadFixtures',
        ];
    }

    public function onPreLoadFixtures(InstallerEvent $event)
    {
        $this->dataMigrationTask->execute(['migrations' => [
            'reference_entity' => 'reference_entity_init']
        ]);
    }

    public function onPostLoadFixtures(InstallerEvent $event)
    {
        $this->dataMigrationTask->execute([
            'migrations' => [
                'reference_entity' => 'reference_entity',
                'reference_entity_attribute' => 'reference_entity_attribute',
                'reference_entity_record' => 'reference_entity_record',
            ]
        ]);
    }
}
