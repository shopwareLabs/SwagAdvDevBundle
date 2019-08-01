<?php
declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SwagAdvDevBundle\Tests\Functional\Bundle\SearchBundle;

use PHPUnit\Framework\TestCase;
use Shopware\Bundle\SearchBundle\Criteria;
use Shopware\Bundle\StoreFrontBundle\Service\Core\ContextService;
use SwagAdvDevBundle\Bundle\SearchBundle\Condition\BundleCondition;
use SwagAdvDevBundle\Bundle\SearchBundle\CriteriaRequestHandler;

class CriteriaRequestHandlerTest extends TestCase
{
    public function testHandleRequestShouldBeFalse(): void
    {
        $handler = new CriteriaRequestHandler();
        $request = new \Enlight_Controller_Request_RequestHttp();
        $criteria = new Criteria();

        /** @var ContextService $contextService */
        $contextService = Shopware()->Container()->get('shopware_storefront.context_service');
        $context = $contextService->getShopContext();

        $handler->handleRequest(
            $request,
            $criteria,
            $context
        );

        $result = $criteria->hasConditionOfClass(BundleCondition::class);

        static::assertFalse($result);
    }

    public function testHandleRequestShouldBeTrue(): void
    {
        $handler = new CriteriaRequestHandler();

        /** @var ContextService $contextService */
        $contextService = Shopware()->Container()->get('shopware_storefront.context_service');
        $context = $contextService->getShopContext();

        $criteria = new Criteria();
        $request = new \Enlight_Controller_Request_RequestHttp();
        $request->setParam('bundle', true);

        $handler->handleRequest(
            $request,
            $criteria,
            $context
        );

        $result = $criteria->hasConditionOfClass(BundleCondition::class);

        static::assertTrue($result);
    }
}
