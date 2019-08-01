<?php
declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SwagAdvDevBundle\Components\Api\Resource;

use Shopware\Components\Api\Exception\NotFoundException;
use Shopware\Components\Api\Exception\ValidationException;
use Shopware\Components\Api\Resource\Resource;
use Shopware\Components\Model\QueryBuilder;
use Shopware\Models\Article\Article;
use SwagAdvDevBundle\Models\Bundle as BundleModel;

class Bundle extends Resource
{
    public function getList(int $offset = 0, int $limit = 10, ?array $filter = null, ?array $sort = null): array
    {
        $builder = $this->getBaseQuery();
        $builder = $this->addQueryLimit($builder, $offset, $limit);

        if (!empty($filter)) {
            $builder->addFilter($filter);
        }
        if (!empty($sort)) {
            $builder->addOrderBy($sort);
        }

        $query = $builder->getQuery();

        $query->setHydrationMode($this->getResultMode());

        $paginator = $this->getManager()->createPaginator($query);
        $totalResult = $paginator->count();
        $bundles = $paginator->getIterator()->getArrayCopy();
        // wrong result without paginator
//        $bundles = $query->getArrayResult();

        return ['data' => $bundles, 'total' => $totalResult];
    }

    /**
     * @throws NotFoundException
     *
     * @return BundleModel|array
     */
    public function getOne(int $id)
    {
        $builder = $this->getBaseQuery();

        $builder->where('bundle.id = :id')
            ->setParameter('id', $id);

        /** @var BundleModel|array $bundle */
        $bundle = $builder->getQuery()->getOneOrNullResult($this->getResultMode());

        if (!$bundle) {
            throw new NotFoundException("Bundle by id $id not found");
        }

        return $bundle;
    }

    /**
     * @throws ValidationException
     */
    public function create(array $data): BundleModel
    {
        $data = $this->prepareBundleData($data);

        $bundle = new BundleModel();
        $bundle->fromArray($data);

        $violations = $this->getManager()->validate($bundle);

        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }

        $this->getManager()->persist($bundle);
        $this->flush();

        return $bundle;
    }

    /**
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function update(int $id, array $data): BundleModel
    {
        /** @var BundleModel $bundle */
        $bundle = $this->getManager()->find(BundleModel::class, $id);

        if (!$bundle) {
            throw new NotFoundException("Bundle by id $id not found");
        }

        $data = $this->prepareBundleData($data);
        $bundle->fromArray($data);

        $violations = $this->getManager()->validate($bundle);
        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }

        $this->flush();

        return $bundle;
    }

    /**
     * @throws NotFoundException
     */
    public function delete(int $id): void
    {
        /** @var BundleModel $bundle */
        $bundle = $this->getManager()->find(BundleModel::class, $id);

        if (!$bundle) {
            throw new NotFoundException("Bundle by id $id not found");
        }

        $this->getManager()->remove($bundle);
        $this->flush();
    }

    /**
     * @param array[] $data
     *
     * @throws NotFoundException
     */
    protected function prepareBundleData(array $data): array
    {
        if (!array_key_exists('products', $data)) {
            return $data;
        }

        $products = [];
        foreach ($data['products'] as $productId) {
            $product = $this->getManager()->find(Article::class, $productId);
            if (!$product instanceof Article) {
                throw new NotFoundException("Product by id $productId not found");
            }
            $products[] = $product;
        }
        $data['products'] = $products;

        return $data;
    }

    protected function addQueryLimit(QueryBuilder $builder, int $offset, ?int $limit = null): QueryBuilder
    {
        $builder->setFirstResult($offset)
            ->setMaxResults($limit);

        return $builder;
    }

    protected function getBaseQuery(): QueryBuilder
    {
        $builder = $this->getManager()->createQueryBuilder();
        $builder->select(['bundle', 'products'])
            ->from(BundleModel::class, 'bundle')
            ->leftJoin('bundle.products', 'products');

        return $builder;
    }
}
