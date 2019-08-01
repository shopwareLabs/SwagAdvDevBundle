<?php
declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SwagAdvDevBundle\Bundle\StoreFrontBundle;

use Shopware\Bundle\StoreFrontBundle\Service\ListProductServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Struct;
use Shopware\Bundle\StoreFrontBundle\Struct\ListProduct;
use Shopware\Bundle\StoreFrontBundle\Struct\ProductContextInterface;

class ListProductServiceDecorator implements ListProductServiceInterface
{
    /**
     * @var ListProductServiceInterface
     */
    private $coreService;

    /**
     * @var BundleService
     */
    private $bundleService;

    public function __construct(
        ListProductServiceInterface $coreService,
        BundleService $bundleService
    ) {
        $this->coreService = $coreService;
        $this->bundleService = $bundleService;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(array $numbers, ProductContextInterface $context): array
    {
        $products = $this->coreService->getList($numbers, $context);

        foreach ($products as $product) {
            $bundles = $this->bundleService->getProductBundles($product->getId(), $context);

            $attribute = new Struct\Attribute([
                'bundles' => $bundles,
                'has_bundle' => !empty($bundles),
            ]);
            $product->addAttribute('swag_bundle', $attribute);
        }

        return $products;
    }

    /**
     * {@inheritdoc}
     */
    public function get($number, ProductContextInterface $context): ?ListProduct
    {
        $products = $this->getList([$number], $context);

        return array_shift($products);
    }
}
