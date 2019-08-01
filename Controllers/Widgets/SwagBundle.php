<?php
declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use SwagAdvDevBundle\Bundle\StoreFrontBundle\BundleService;
use SwagAdvDevBundle\Bundle\StoreFrontBundle\Struct\Bundle;

class Shopware_Controllers_Widgets_SwagBundle extends Enlight_Controller_Action
{
    public function addBundleAction(): void
    {
        $bundleId = (int) $this->Request()->getParam('bundleId');
        if ($bundleId === 0) {
            throw new RuntimeException('No bundle id passed!');
        }

        $bundle = new Bundle();
        $bundle->setId($bundleId);

        /** @var BundleService $bundleService */
        $bundleService = $this->get('swag_bundle.bundle_service');
        $productNumbersByBundle = $bundleService->getProductNumbersByBundle($bundle);

        /** @var Shopware_Components_Modules $moduleManager */
        $moduleManager = $this->container->get('modules');
        $sBasket = $moduleManager->getModule('basket');

        foreach ($productNumbersByBundle as $orderNumber) {
            $sBasket->sAddArticle($orderNumber, 1);
        }

        $this->redirect([
            'controller' => 'checkout',
            'action' => 'cart',
        ]);
    }
}
