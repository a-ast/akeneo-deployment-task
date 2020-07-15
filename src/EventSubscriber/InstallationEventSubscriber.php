<?php

namespace Aa\DeploymentTask\EventSubscriber;

use Aa\DeploymentTask\Task\AdminUserSanitizerTask;
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

    /**
     * @var \Aa\DeploymentTask\Task\AdminUserSanitizerTask
     */
    private $adminUserSanitizerTask;

    public function __construct(
        ApiClientTask $apiClientCreator,
        AdminUserSanitizerTask $adminUserSanitizerTask,
        DataMigrationTask $dataMigrationTask
    ) {
        $this->apiClientCreator = $apiClientCreator;
        $this->adminUserSanitizerTask = $adminUserSanitizerTask;
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
            InstallerEvents::PRE_LOAD_FIXTURE => 'onPreLoadFixture',
        ];
    }

    public function onPreLoadFixtures(InstallerEvent $event)
    {
        // Important: reference entities must be initialized with codes before attributes are imported
        // The reason is because Akeneo activates locales only after importing channels
        // and channel fixtures executed after attributes
        $this->dataMigrationTask->execute(
            [
                'migrations' => [
                    [
                        'type' => 'reference_entity',
                        'file' => 'reference_entity_init'
                    ],
                ]
            ]
        );
    }

    public function onPostLoadFixtures(InstallerEvent $event)
    {
        $this->adminUserSanitizerTask->execute([]);
    }

    public function onPreLoadFixture(InstallerEvent $event)
    {
        if ('fixtures_product_model_csv' !== $event->getSubject()) {
            return;
        }

        $this->dataMigrationTask->execute(
            [
                'migrations' => [
                    [
                        'type' => 'reference_entity',
                        'file' => 'reference_entity'
                    ],
                    [
                        'type' => 'reference_entity_attribute',
                        'file' => 'reference_entity_attribute'
                    ],
                    [
                        'type' => 'reference_entity_record',
                        'file' => 'reference_entity_record'
                    ],
                ]
            ]
        );
    }
}
