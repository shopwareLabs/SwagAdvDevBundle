<?php

use SwagAdvDevBundle\Models\Bundle;

class Shopware_Controllers_Backend_SwagBundle extends Shopware_Controllers_Backend_Application
{
    protected $model = Bundle::class;
    protected $alias = 'bundle';

    /**
     * {@inheritdoc}
     */
    protected function getDetailQuery($id)
    {
        $builder = parent::getDetailQuery($id);
        $builder->leftJoin('bundle.products', 'products')
            ->addSelect('products');

        return $builder;
    }
}
