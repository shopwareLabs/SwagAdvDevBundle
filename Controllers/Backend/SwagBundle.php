<?php
declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Shopware\Components\Model\QueryBuilder;
use SwagAdvDevBundle\Models\Bundle;

class Shopware_Controllers_Backend_SwagBundle extends Shopware_Controllers_Backend_Application
{
    protected $model = Bundle::class;
    protected $alias = 'bundle';

    /**
     * {@inheritdoc}
     */
    protected function getDetailQuery($id): QueryBuilder
    {
        $builder = parent::getDetailQuery($id);
        $builder->leftJoin('bundle.products', 'products')
            ->addSelect('products');

        return $builder;
    }
}
