<?php
declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SwagAdvDevBundle\Tests\Functional\Components\Api\Resource;

use PHPUnit\Framework\TestCase;
use Shopware\Components\Api\Exception\NotFoundException;
use Shopware\Components\Api\Exception\ValidationException;
use Shopware\Components\Model\QueryBuilder;
use Shopware\Models\Article\Article;
use SwagAdvDevBundle\Components\Api\Resource\Bundle;
use SwagAdvDevBundle\Tests\Functional\Components\Api\Resource\Mock\ManagerMock;
use SwagAdvDevBundle\Tests\Functional\DatabaseTestCaseTrait;
use SwagAdvDevBundle\Tests\ReflectionHelper;

class BundleTest extends TestCase
{
    use DatabaseTestCaseTrait;

    public function testGetList(): void
    {
        $resource = $this->getResource();

        $limit = 3;
        $result = $resource->getList(0, $limit);

        $expectedSubset = ['data' => [], 'total' => 19];

        static::assertArraySubset($expectedSubset, $result);
        static::assertCount($limit, $result['data']);
    }

    public function testGetOneExpectsException(): void
    {
        $resource = $this->getResource();
        $id = 122; // not existent id

        $this->expectException(NotFoundException::class);

        $resource->getOne($id);
    }

    public function testGetOne(): void
    {
        $resource = $this->getResource();
        $id = 2;

        $result = $resource->getOne($id);

        $expectedSubset = [
            'id' => 2,
            'name' => 'Bundle2',
            'active' => true,
            'products' => [],
        ];

        static::assertInternalType('array', $result);
        static::assertArraySubset($expectedSubset, $result);
    }

    public function testCreate(): void
    {
        $data = [
            'active' => true,
            'id' => null,
            'name' => 'A new Bundle',
            'products' => [
                4,
                5,
                7,
            ],
        ];

        $resource = $this->getResource();

        $resource->create($data);

        $sql = 'SELECT * FROM s_bundles WHERE `name` LIKE "A new Bundle"';

        $result = Shopware()->Container()->get('dbal_connection')->fetchAll($sql);

        $expectedSubsetResult = [
            [
                'name' => 'A new Bundle',
                'active' => '1',
            ],
        ];

        static::assertArraySubset($expectedSubsetResult, $result);
    }

    public function testUpdate(): void
    {
        $id = 5;
        $newName = 'This is simple test';

        $data = [
            'name' => $newName,
            'products' => [
                4,
                5,
                7,
            ],
        ];

        $resource = $this->getResource();
        $resource->update($id, $data);

        $sql = 'SELECT `name` FROM s_bundles WHERE id = 5';
        $result = Shopware()->Container()->get('dbal_connection')->fetchColumn($sql);

        static::assertSame($newName, $result);
    }

    public function testUpdateExpectsNotFoundException(): void
    {
        $resource = $this->getResource();

        $this->expectException(NotFoundException::class);

        $resource->update(999, []);
    }

    public function testUpdateExpectsValidationException(): void
    {
        $resource = $this->getResource();

        $property = ReflectionHelper::getProperty(Bundle::class, 'manager');
        $property->setValue($resource, new ManagerMock());

        $this->expectException(ValidationException::class);
        $resource->update(5, ['products' => []]);
    }

    public function testDelete(): void
    {
        $data = [
            'active' => true,
            'id' => null,
            'name' => 'Bundle to delete',
            'products' => [
                4,
            ],
        ];

        $resource = $this->getResource();
        $resource->create($data);

        $sql = 'SELECT id FROM s_bundles WHERE `name` LIKE "Bundle to delete"';
        $id = (int) Shopware()->Container()->get('dbal_connection')->fetchColumn($sql);

        $resource->delete($id);

        $result = Shopware()->Container()->get('dbal_connection')->fetchColumn($sql);

        static::assertFalse($result);
    }

    public function testDeleteExpectsNotFoundException(): void
    {
        $resource = $this->getResource();

        $this->expectException(NotFoundException::class);

        $resource->delete(9999);
    }

    public function testPrepareBundleDataExpectsNotFoundException(): void
    {
        $data = [
            'active' => true,
            'id' => 5,
            'name' => 'Bundle to delete',
            'products' => [
                99999, // invalid article Id
            ],
        ];

        $resource = $this->getResource();

        $method = ReflectionHelper::getMethod(Bundle::class, 'prepareBundleData');

        $this->expectException(NotFoundException::class);

        $method->invokeArgs($resource, [$data]);
    }

    public function testPrepareBundleData(): void
    {
        $data = [
            'active' => true,
            'id' => 5,
            'name' => 'Bundle to delete',
            'products' => [
                4,
            ],
        ];

        $resource = $this->getResource();

        $result = ReflectionHelper::getMethod(Bundle::class, 'prepareBundleData')->invokeArgs($resource, [$data]);

        $expectedSubset = [
            'active' => true,
            'id' => 5,
            'name' => 'Bundle to delete',
            'products' => [],
        ];

        static::assertInternalType('array', $result);
        static::assertArraySubset($expectedSubset, $result);
        static::assertInstanceOf(Article::class, $result['products'][0]);
    }

    public function testAddQueryLimit(): void
    {
        $resource = $this->getResource();

        $method = ReflectionHelper::getMethod(Bundle::class, 'addQueryLimit');

        $queryBuilder = Shopware()->Models()->createQueryBuilder();
        $queryBuilder->select('*')
            ->from('s_articles', 'art');

        $result = $method->invokeArgs($resource, [$queryBuilder, 10, 100]);

        static::assertInstanceOf(QueryBuilder::class, $result);
    }

    public function testGetBaseQuery(): void
    {
        $resource = $this->getResource();

        $method = ReflectionHelper::getMethod(Bundle::class, 'getBaseQuery');

        $expectedSubset = [
            'distinct' => false,
            'select' => [],
            'from' => [],
            'join' => [],
            'set' => [],
            'where' => [],
        ];

        $result = $method->invoke($resource);

        static::assertInstanceOf(QueryBuilder::class, $result);
        static::assertArraySubset($expectedSubset, $result->getDQLParts());
    }

    private function getResource(): Bundle
    {
        $bundle = new Bundle();

        $bundle->setManager(Shopware()->Models());

        return $bundle;
    }
}
