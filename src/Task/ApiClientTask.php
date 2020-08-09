<?php

namespace Aa\DeploymentTask\Task;

use Akeneo\Tool\Bundle\ApiBundle\Entity\Client;
use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use OAuth2\OAuth2;
use Symfony\Component\Dotenv\Dotenv;

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

        $client->setRandomId('TEST_API_CLIENT_ID');
        $client->setSecret('TEST_API_CLIENT_SECRET');

        $this->clientManager->updateClient($client);
    }
}
