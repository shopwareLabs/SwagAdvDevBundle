<?php

namespace SwagAdvDevBundle\Bundle\SearchBundle;

use Enlight_Controller_Request_RequestHttp as Request;
use Shopware\Bundle\SearchBundle\Criteria;
use Shopware\Bundle\SearchBundle\CriteriaRequestHandlerInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;
use SwagAdvDevBundle\Bundle\SearchBundle\Condition\BundleCondition;
use SwagAdvDevBundle\Bundle\SearchBundle\Facet\BundleFacet;

class CriteriaRequestHandler implements CriteriaRequestHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handleRequest(
        Request $request,
        Criteria $criteria,
        ShopContextInterface $context
    ) {
        if ($request->has('bundle')) {
            $criteria->addCondition(new BundleCondition());
        }

        $criteria->addFacet(new BundleFacet());
    }
}
