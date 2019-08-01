<?php

namespace SwagAdvDevBundle\Bundle\StoreFrontBundle;

use Shopware\Bundle\StoreFrontBundle\Service\ListProductServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Struct;

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

    /**
     * @param ListProductServiceInterface $coreService
     * @param BundleService               $bundleService
     */
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
    public function getList(array $numbers, Struct\ProductContextInterface $context)
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
    public function get($number, Struct\ProductContextInterface $context)
    {
        $products = $this->getList([$number], $context);

        return array_shift($products);
    }
}
