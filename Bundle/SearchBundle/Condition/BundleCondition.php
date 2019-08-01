<?php

namespace SwagAdvDevBundle\Bundle\SearchBundle\Condition;

use Shopware\Bundle\SearchBundle\ConditionInterface;

class BundleCondition implements ConditionInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'swag_bundle';
    }
}
