<?php
declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SwagAdvDevBundle\Bundle\SearchBundleDBAL\Facet;

use Doctrine\DBAL\Driver\ResultStatement;
use Shopware\Bundle\SearchBundle\Criteria;
use Shopware\Bundle\SearchBundle\FacetInterface;
use Shopware\Bundle\SearchBundle\FacetResult\BooleanFacetResult;
use Shopware\Bundle\SearchBundleDBAL\PartialFacetHandlerInterface;
use Shopware\Bundle\SearchBundleDBAL\QueryBuilderFactoryInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;
use SwagAdvDevBundle\Bundle\SearchBundle\Condition\BundleCondition;
use SwagAdvDevBundle\Bundle\SearchBundle\Facet\BundleFacet;

class BundleFacetHandler implements PartialFacetHandlerInterface
{
    /**
     * @var QueryBuilderFactoryInterface
     */
    private $queryBuilderFactory;

    public function __construct(QueryBuilderFactoryInterface $queryBuilderFactory)
    {
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    public function supportsFacet(FacetInterface $facet): bool
    {
        return $facet instanceof BundleFacet;
    }

    public function generatePartialFacet(
        FacetInterface $facet,
        Criteria $reverted,
        Criteria $criteria,
        ShopContextInterface $context
    ) {
        $query = $this->queryBuilderFactory->createQuery($reverted, $context);
        $query->select('product.id')
            ->innerJoin(
                'product',
                's_bundle_products',
                'bundle',
                'bundle.product_id = product.id'
            )
            ->setMaxResults(1);

        /** @var ResultStatement $statement */
        $statement = $query->execute();
        $total = $statement->fetch(\PDO::FETCH_COLUMN);
        //found some products?
        if ($total <= 0) {
            return null;
        }

        return new BooleanFacetResult(
            $facet->getName(),
            'bundle',
            $criteria->hasConditionOfClass(BundleCondition::class),
            'Only bundles'
        );
    }
}
