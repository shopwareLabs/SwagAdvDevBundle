<?php
declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SwagAdvDevBundle\Bundle\SearchBundleDBAL\Condition;

use Shopware\Bundle\SearchBundle\ConditionInterface;
use Shopware\Bundle\SearchBundleDBAL\ConditionHandlerInterface;
use Shopware\Bundle\SearchBundleDBAL\QueryBuilder;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;
use SwagAdvDevBundle\Bundle\SearchBundle\Condition\BundleCondition;

class BundleConditionHandler implements ConditionHandlerInterface
{
    public function supportsCondition(ConditionInterface $condition): bool
    {
        return $condition instanceof BundleCondition;
    }

    public function generateCondition(
        ConditionInterface $condition,
        QueryBuilder $query,
        ShopContextInterface $context
    ): void {
        $query->innerJoin(
            'product',
            's_bundle_products',
            'bundle',
            'bundle.product_id = product.id'
        );
    }
}
