<?php

namespace Aa\DeploymentTask\Task;

use Akeneo\UserManagement\Bundle\Manager\UserManager;

class AdminUserSanitizerTask
{
    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    public function execute(array $config)
    {
        $password = getenv('ADMIN_PASSWORD');

        if (false === $password) {
            return;
        }

        $user = $this->userManager->findUserByUsername('admin');

        if (null === $user) {
            return;
        }

        $user->setPlainPassword($password);
        $this->userManager->updateUser($user);
    }
}
