<?php

namespace SwagAdvDevBundle\Bundle\StoreFrontBundle\Struct;

use Shopware\Bundle\StoreFrontBundle\Struct\Extendable;
use Shopware\Bundle\StoreFrontBundle\Struct\ListProduct;

class Bundle extends Extendable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var ListProduct[]
     */
    private $products = [];

    /**
     * @var array[]
     */
    private $legacyProducts = [];

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return \array[]
     */
    public function getLegacyProducts()
    {
        return $this->legacyProducts;
    }

    /**
     * @param \array[] $legacyProducts
     */
    public function setLegacyProducts($legacyProducts)
    {
        $this->legacyProducts = $legacyProducts;
    }

    /**
     * @return ListProduct[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param ListProduct[] $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }
}
