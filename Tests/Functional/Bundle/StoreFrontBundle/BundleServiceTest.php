<?php
declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SwagAdvDevBundle\Tests\Functional\Bundle\StoreFrontBundle;

use Doctrine\DBAL\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Shopware\Bundle\StoreFrontBundle\Service\Core\ContextService;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;
use SwagAdvDevBundle\Bundle\StoreFrontBundle\BundleService;
use SwagAdvDevBundle\Bundle\StoreFrontBundle\Struct\Bundle;

class BundleServiceTest extends TestCase
{
    public function testGetProductBundles(): void
    {
        /** @var QueryBuilder $builder */
        $builder = Shopware()->Container()->get('dbal_connection')->createQueryBuilder();
        $productId = (int) $builder->select('product_id')
            ->from('s_bundle_products')
            ->setMaxResults(1)
            ->execute()
            ->fetchColumn();

        $context = $this->getContext();
        $result = $this->getService()->getProductBundles($productId, $context);

        static::assertInternalType('array', $result);
        static::assertInstanceOf(Bundle::class, $result[0]);
    }

    public function testGetProductNumbersByBundle(): void
    {
        $service = $this->getService();
        $bundle = new Bundle();
        $bundle->setId(5);

        $result = $service->getProductNumbersByBundle($bundle);

        static::assertNotEmpty($result);
    }

    private function getContext(): ShopContextInterface
    {
        /** @var ContextService $contextService */
        $contextService = Shopware()->Container()->get('shopware_storefront.context_service');

        return $contextService->createShopContext(1);
    }

    private function getService(): BundleService
    {
        return new BundleService(
            Shopware()->Container()->get('dbal_connection'),
            Shopware()->Container()->get('swag_bundle.list_product_service.inner'),
            Shopware()->Container()->get('legacy_struct_converter')
        );
    }
}
