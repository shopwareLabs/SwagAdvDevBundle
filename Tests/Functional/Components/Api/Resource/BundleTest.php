<?php

namespace SwagAdvDevBundle\Tests\Functional\Components\Api\Resource;

use Shopware\Components\Api\Exception\NotFoundException;
use Shopware\Components\Api\Exception\ParameterMissingException;
use Shopware\Components\Api\Exception\ValidationException;
use Shopware\Components\Model\QueryBuilder;
use Shopware\Models\Article\Article;
use SwagAdvDevBundle\Components\Api\Resource\Bundle;
use SwagAdvDevBundle\Tests\KernelTestCaseTrait;
use SwagAdvDevBundle\Tests\ReflectionHelper;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class BundleTest extends \PHPUnit\Framework\TestCase
{
    use KernelTestCaseTrait;

    public function test_getList()
    {
        $resource = $this->getResource();

        $limit = 3;
        $result = $resource->getList(0, $limit, null, null);

        $expectedSubset = ['data' => [], 'total' => 19];

        $this->assertTrue(is_array($result));
        $this->assertArraySubset($expectedSubset, $result);
        $this->assertCount($limit, $result['data']);
    }

    public function test_getOne_expects_exception()
    {
        $resource = $this->getResource();
        $id = 122; // not existent id

        $this->expectException(NotFoundException::class);

        $resource->getOne($id);
    }

    public function test_getOne()
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

        $this->assertTrue(is_array($result));
        $this->assertArraySubset($expectedSubset, $result);
    }

    public function test_create()
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

        $this->assertArraySubset($expectedSubsetResult, $result);
    }

    public function test_update()
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

        $this->assertSame($newName, $result);
    }

    public function test_update_expects_ParameterMissingException()
    {
        $resource = $this->getResource();

        $this->expectException(ParameterMissingException::class);

        $resource->update(null, []);
    }

    public function test_update_expects_NotFoundException()
    {
        $resource = $this->getResource();

        $this->expectException(NotFoundException::class);

        $resource->update(999, []);
    }

    public function test_update_expects_ValidationException()
    {
        $resource = $this->getResource();

        $property = ReflectionHelper::getProperty(Bundle::class, 'manager');
        $property->setValue($resource, new ManagerMock());

        $this->expectException(ValidationException::class);
        $resource->update(5, ['products' => []]);
    }

    public function test_delete()
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
        $id = Shopware()->Container()->get('dbal_connection')->fetchColumn($sql);

        $resource->delete($id);

        $result = Shopware()->Container()->get('dbal_connection')->fetchColumn($sql);

        $this->assertFalse($result);
    }

    public function test_delete_expects_ParameterMissingException()
    {
        $resource = $this->getResource();

        $this->expectException(ParameterMissingException::class);

        $resource->delete(null);
    }

    public function test_delete_expects_NotFoundException()
    {
        $resource = $this->getResource();

        $this->expectException(NotFoundException::class);

        $resource->delete(9999);
    }

    public function test_prepareBundleData_expects_NotFoundException()
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

    public function test_prepareBundleData()
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

        $method = ReflectionHelper::getMethod(Bundle::class, 'prepareBundleData');
        $result = $method->invokeArgs($resource, [$data]);

        $expectedSubset = [
            'active' => true,
            'id' => 5,
            'name' => 'Bundle to delete',
            'products' => [],
        ];

        $this->assertTrue(is_array($result));
        $this->assertArraySubset($expectedSubset, $result);
        $this->assertInstanceOf(Article::class, $result['products'][0]);
    }

    public function test_addQueryLimit()
    {
        $resource = $this->getResource();

        $method = ReflectionHelper::getMethod(Bundle::class, 'addQueryLimit');

        $queryBuilder = Shopware()->Models()->createQueryBuilder();
        $queryBuilder->select('*')
            ->from('s_articles', 'art');

        $result = $method->invokeArgs($resource, [$queryBuilder, 10, 100]);

        $this->assertInstanceOf(QueryBuilder::class, $result);
    }

    public function test_getBaseQuery()
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

        $this->assertInstanceOf(QueryBuilder::class, $result);
        $this->assertArraySubset($expectedSubset, $result->getDQLParts());
    }

    private function getResource()
    {
        $bundle = new Bundle();

        $bundle->setManager(Shopware()->Models());

        return $bundle;
    }
}

class ManagerMock
{
    public function find($entityName, $id)
    {
        return Shopware()->Models()->find($entityName, $id);
    }

    public function validate($object)
    {
        $violationList = new ConstraintViolationList();
        $constraintViolation = new ConstraintViolation(
            'test',
            'foo',
            [],
            'bar',
            'fooBar',
            'invalid'
        );
        $violationList->add($constraintViolation);

        return $violationList;
    }
}
