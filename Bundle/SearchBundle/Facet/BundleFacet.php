<?php

namespace SwagAdvDevBundle\Bundle\SearchBundle\Facet;

use Shopware\Bundle\SearchBundle\FacetInterface;

class BundleFacet implements FacetInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'swag_bundle';
    }
}
