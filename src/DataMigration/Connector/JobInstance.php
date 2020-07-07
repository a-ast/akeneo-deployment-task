<?php

namespace Aa\DeploymentTask\DataMigration\Connector;

use Akeneo\Tool\Bundle\BatchBundle\Job\JobInstanceFactory;
use Akeneo\Tool\Component\Batch\Job\JobInterface;
use Akeneo\Tool\Component\Batch\Job\JobParametersFactory;
use Akeneo\Tool\Component\Batch\Job\JobParametersValidator;
use Akeneo\Tool\Component\Batch\Job\JobRegistry;
use Akeneo\Tool\Component\StorageUtils\Saver\SaverInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use \Akeneo\Tool\Component\Batch\Model\JobInstance as BaseJobInstance;

class JobInstance implements Connector
{
    /**
     * @var \Akeneo\Tool\Bundle\BatchBundle\Job\JobInstanceFactory
     */
    private $factory;

    /**
     * @var \Akeneo\Tool\Component\Batch\Job\JobRegistry
     */
    private $registry;

    /**
     * @var \Akeneo\Tool\Component\Batch\Job\JobParametersFactory
     */
    private $parametersFactory;

    /**
     * @var \Akeneo\Tool\Component\Batch\Job\JobParametersValidator
     */
    private $parametersValidator;

    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    private $validator;

    /**
     * @var \Akeneo\Tool\Component\StorageUtils\Saver\SaverInterface
     */
    private $saver;

    public function __construct(
        JobInstanceFactory $factory,
        JobRegistry $registry,
        JobParametersFactory $parametersFactory,
        JobParametersValidator $parametersValidator,
        ValidatorInterface $validator,
        SaverInterface $saver
    ) {
        $this->factory = $factory;
        $this->registry = $registry;
        $this->parametersFactory = $parametersFactory;
        $this->parametersValidator = $parametersValidator;
        $this->validator = $validator;
        $this->saver = $saver;
    }

    public function load(array $data): void
    {
        $jobInstance = $this->buildJobInstanceFromData($data);
        $job = $this->getRegisteredJobInstance($jobInstance);

        $jobParameters = $this->parametersFactory->create($job, $data['configuration'] ?? []);
        $jobInstance->setRawParameters($jobParameters->all());

        $violations = $this->parametersValidator->validate($job, $jobParameters);
        $this->checkViolations($violations, $jobInstance->getJobName());

        $violations = $this->validator->validate($jobInstance);

        // Don't try to override existing jobs
        if ($this->isJobCodeAlreadyRegistered($violations)) {
            return;
        }

        $this->checkViolations($violations, $jobInstance->getJobName());

        $this->saver->save($jobInstance);
    }

    private function buildJobInstanceFromData(array $data): BaseJobInstance
    {
        $jobInstance = $this->factory->createJobInstance($data['type']);
        $jobInstance
            ->setConnector($data['connector'])
            ->setJobName($data['code'])
            ->setCode($data['code'])
            ->setLabel($data['label'])
            ->setRawParameters($data['configuration'] ?? []);

        return $jobInstance;
    }

    private function getRegisteredJobInstance(BaseJobInstance $jobInstance): JobInterface
    {
        return $this->registry->get($jobInstance->getJobName());
    }

    private function checkViolations(ConstraintViolationListInterface $violations, string $jobName): void
    {
        if (0 === count($violations)) {
            return;
        }

        $message = 'A validation error occurred with the job configuration %s: %s.';

        throw new \Exception(sprintf($message, $jobName, implode(PHP_EOL, (array)$violations)));
    }

    private function isJobCodeAlreadyRegistered(ConstraintViolationListInterface $violations): bool
    {
        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            if ($violation->getConstraint() instanceof UniqueEntity) {
                return true;
            }
        }

        return false;
    }
}
