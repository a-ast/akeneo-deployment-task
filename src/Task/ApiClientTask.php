<?php

namespace Aa\DeploymentTask\Task;

use Akeneo\Tool\Bundle\ApiBundle\Entity\Client;
use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use OAuth2\OAuth2;

class ApiClientTask
{
    /**
     * @var ClientManagerInterface
     */
    private $clientManager;

    public function __construct(ClientManagerInterface $clientManager)
    {
        $this->clientManager = $clientManager;
    }

    public function execute(array $config)
    {
        $client = $this->clientManager->createClient();
        $client->setAllowedGrantTypes([OAuth2::GRANT_TYPE_USER_CREDENTIALS, OAuth2::GRANT_TYPE_REFRESH_TOKEN]);

        if ($client instanceof Client) {
            $client->setLabel('Import');
        }

        $this->clientManager->updateClient($client);
    }
}
