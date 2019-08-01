<?php
declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SwagAdvDevBundle\Bundle\StoreFrontBundle;

use Doctrine\DBAL\Connection;
use Shopware\Bundle\StoreFrontBundle\Service\ListProductServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;
use Shopware\Components\Compatibility\LegacyStructConverter;

class BundleService
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var ListProductServiceInterface
     */
    private $listProductService;

    /**
     * @var LegacyStructConverter
     */
    private $structConverter;

    public function __construct(
        Connection $connection,
        ListProductServiceInterface $listProductService,
        LegacyStructConverter $structConverter
    ) {
        $this->connection = $connection;
        $this->listProductService = $listProductService;
        $this->structConverter = $structConverter;
    }

    /**
     * @return Struct\Bundle[]
     */
    public function getProductBundles(int $productId, ShopContextInterface $context): array
    {
        $bundles = $this->getBundlesByProductId($productId);

        /** @var Struct\Bundle $bundle */
        foreach ($bundles as $bundle) {
            $productNumbers = $this->getProductNumbersByBundle($bundle);

            $products = $this->listProductService->getList($productNumbers, $context);
            $bundle->setProducts($products);

            $legacyProducts = $this->structConverter->convertListProductStructList($products);
            $bundle->setLegacyProducts($legacyProducts);
        }

        return $bundles;
    }

    public function getProductNumbersByBundle(Struct\Bundle $bundle): array
    {
        $builder = $this->connection->createQueryBuilder();
        $builder->select('ordernumber')
                ->from('s_bundle_products', 'bundleProducts')
                ->andWhere('bundleProducts.bundle_id = :bundleId')
                ->setParameter('bundleId', $bundle->getId())
                ->innerJoin('bundleProducts', 's_articles_details', 'details', 'details.articleID = bundleProducts.product_id')
                ->andWhere('details.kind = 1')
        ;

        return $builder->execute()->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * @return Struct\Bundle[]
     */
    private function getBundlesByProductId(int $productId): array
    {
        $builder = $this->connection->createQueryBuilder();
        $builder->select(['id', 'name'])
                ->from('s_bundle_products', 'bundleProducts')
                ->innerJoin('bundleProducts', 's_bundles', 'bundles', 'bundles.id = bundleProducts.bundle_id')
                ->andWhere('bundleProducts.product_id = :productId')
                ->setParameter('productId', $productId);

        $result = $builder->execute()->fetchAll();
        $bundles = [];

        foreach ($result as $row) {
            $bundle = new Struct\Bundle();
            $bundle->setId((int) $row['id']);
            $bundle->setName($row['name']);

            $bundles[] = $bundle;
        }

        return $bundles;
    }
}
