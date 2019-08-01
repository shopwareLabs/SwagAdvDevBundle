<?php

namespace SwagAdvDevBundle\Tests\Functional\Bundle\StoreFrontBundle;

use Doctrine\DBAL\Query\QueryBuilder;
use Shopware\Bundle\StoreFrontBundle\Service\Core\ContextService;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContext;
use SwagAdvDevBundle\Bundle\StoreFrontBundle\BundleService;
use SwagAdvDevBundle\Bundle\StoreFrontBundle\Struct\Bundle;
use SwagAdvDevBundle\Tests\KernelTestCaseTrait;

class BundleServiceTest extends \PHPUnit\Framework\TestCase
{
    use KernelTestCaseTrait;

    public function test_getProductBundles()
    {
        /** @var QueryBuilder $builder */
        $builder = Shopware()->Container()->get('dbal_connection')->createQueryBuilder();
        $productId = $builder->select('product_id')
            ->from('s_bundle_products')
            ->setMaxResults(1)
            ->execute()
            ->fetchColumn();

        $context = $this->getContext();
        $service = $this->getService();

        $result = $service->getProductBundles($productId, $context);

        $this->assertTrue(is_array($result));
        $this->assertInstanceOf(Bundle::class, $result[0]);
    }

    public function test_getProductNumbersByBundle()
    {
        $service = $this->getService();
        $bundle = new Bundle();
        $bundle->setId(5);

        $result = $service->getProductNumbersByBundle($bundle);

        $this->assertTrue(is_array($result));
        $this->assertNotEmpty($result);
    }

    /**
     * @return \Shopware\Bundle\StoreFrontBundle\Struct\ProductContextInterface|ShopContext
     */
    private function getContext()
    {
        /** @var ContextService $contextService */
        $contextService = Shopware()->Container()->get('shopware_storefront.context_service');

        return $contextService->createShopContext(1);
    }

    /**
     * @return BundleService
     */
    private function getService()
    {
        return new BundleService(
            Shopware()->Container()->get('dbal_connection'),
            Shopware()->Container()->get('swag_bundle.list_product_service.inner'),
            Shopware()->Container()->get('legacy_struct_converter')
        );
    }
}
