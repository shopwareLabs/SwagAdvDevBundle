<?php
declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array[]
     */
    public function getLegacyProducts(): array
    {
        return $this->legacyProducts;
    }

    /**
     * @param array[] $legacyProducts
     */
    public function setLegacyProducts(array $legacyProducts): void
    {
        $this->legacyProducts = $legacyProducts;
    }

    /**
     * @return ListProduct[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param ListProduct[] $products
     */
    public function setProducts(array $products): void
    {
        $this->products = $products;
    }
}
