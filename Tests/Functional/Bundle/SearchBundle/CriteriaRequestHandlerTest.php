<?php

namespace SwagAdvDevBundle\Tests\Functional\Bundle\SearchBundle;

use Shopware\Bundle\SearchBundle\Criteria;
use Shopware\Bundle\StoreFrontBundle\Service\Core\ContextService;
use SwagAdvDevBundle\Bundle\SearchBundle\Condition\BundleCondition;
use SwagAdvDevBundle\Bundle\SearchBundle\CriteriaRequestHandler;
use SwagAdvDevBundle\Tests\KernelTestCaseTrait;

class CriteriaRequestHandlerTest extends \PHPUnit\Framework\TestCase
{
    use KernelTestCaseTrait;

    public function test_handleRequest_should_be_false()
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

        $this->assertFalse($result);
    }

    public function test_handleRequest_should_be_true()
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

        $this->assertTrue($result);
    }
}
