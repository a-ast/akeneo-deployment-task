<?php

namespace Aa\DeploymentTask\DataMigration\Connector;

use Akeneo\Pim\Automation\FranklinInsights\Application\Structure\Service\AddAttributeToFamilyInterface;
use Akeneo\Pim\Automation\FranklinInsights\Domain\Common\ValueObject\AttributeCode;
use Akeneo\Pim\Automation\FranklinInsights\Domain\Common\ValueObject\FamilyCode;

class AttributeToFamilyAssignment implements Connector
{
    /**
     * @var AddAttributeToFamilyInterface
     */
    private $attributeToFamily;

    public function __construct(AddAttributeToFamilyInterface $attributeToFamily)
    {
        $this->attributeToFamily = $attributeToFamily;
    }

    public function load(array $data): void
    {
        $attributeCode = new AttributeCode($data['attribute']);
        $families = $data['families'];

        foreach ($families as $familyCode) {
            $this->attributeToFamily->addAttributeToFamily(
                $attributeCode,
                new FamilyCode($familyCode)
            );
        }
    }
}
