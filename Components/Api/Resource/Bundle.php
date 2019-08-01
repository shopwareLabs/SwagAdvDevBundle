<?php

namespace SwagAdvDevBundle\Components\Api\Resource;

use Shopware\Components\Api\Exception as ApiException;
use Shopware\Components\Api\Resource\Resource;
use Shopware\Components\Model\QueryBuilder;
use Shopware\Models\Article\Article;
use SwagAdvDevBundle\Models\Bundle as BundleModel;

class Bundle extends Resource
{
    /**
     * @param $offset
     * @param $limit
     * @param $filter
     * @param $sort
     *
     * @return array
     */
    public function getList($offset, $limit, $filter, $sort)
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
     * @param $id
     *
     * @throws ApiException\NotFoundException
     *
     * @return BundleModel
     */
    public function getOne($id)
    {
        $builder = $this->getBaseQuery();

        $builder->where('bundle.id = :id')
            ->setParameter('id', $id);

        /** @var $bundle BundleModel */
        $bundle = $builder->getQuery()->getOneOrNullResult($this->getResultMode());

        if (!$bundle) {
            throw new ApiException\NotFoundException("Bundle by id $id not found");
        }

        return $bundle;
    }

    /**
     * @param $data
     *
     * @throws ApiException\ValidationException
     *
     * @return BundleModel
     */
    public function create($data)
    {
        $data = $this->prepareBundleData($data);

        $bundle = new BundleModel();
        $bundle->fromArray($data);

        $violations = $this->getManager()->validate($bundle);

        if ($violations->count() > 0) {
            throw new ApiException\ValidationException($violations);
        }

        $this->getManager()->persist($bundle);
        $this->flush();

        return $bundle;
    }

    /**
     * @param $id
     * @param array $data
     *
     * @throws ApiException\NotFoundException
     * @throws ApiException\ParameterMissingException
     * @throws ApiException\ValidationException
     *
     * @return BundleModel
     */
    public function update($id, array $data)
    {
        if (empty($id)) {
            throw new ApiException\ParameterMissingException();
        }

        /** @var $bundle BundleModel */
        $bundle = $this->getManager()->find(BundleModel::class, $id);

        if (!$bundle) {
            throw new ApiException\NotFoundException("Bundle by id $id not found");
        }

        $data = $this->prepareBundleData($data);
        $bundle->fromArray($data);

        $violations = $this->getManager()->validate($bundle);
        if ($violations->count() > 0) {
            throw new ApiException\ValidationException($violations);
        }

        $this->flush();

        return $bundle;
    }

    /**
     * @param $id
     *
     * @throws ApiException\NotFoundException
     * @throws ApiException\ParameterMissingException
     */
    public function delete($id)
    {
        if (empty($id)) {
            throw new ApiException\ParameterMissingException();
        }

        /** @var $bundle BundleModel */
        $bundle = $this->getManager()->find(BundleModel::class, $id);

        if (!$bundle) {
            throw new ApiException\NotFoundException("Bundle by id $id not found");
        }

        $this->getManager()->remove($bundle);
        $this->flush();
    }

    /**
     * @param array $data
     *
     * @throws ApiException\NotFoundException
     *
     * @return array
     */
    protected function prepareBundleData(array $data)
    {
        if (!array_key_exists('products', $data)) {
            return $data;
        }

        $products = [];
        foreach ($data['products'] as $productId) {
            $product = $this->getManager()->find(Article::class, $productId);
            if (!$product instanceof Article) {
                throw new ApiException\NotFoundException("Product by id $productId not found");
            }
            $products[] = $product;
        }
        $data['products'] = $products;

        return $data;
    }

    /**
     * @param QueryBuilder $builder
     * @param              $offset
     * @param null         $limit
     *
     * @return QueryBuilder
     */
    protected function addQueryLimit(QueryBuilder $builder, $offset, $limit = null)
    {
        $builder->setFirstResult($offset)
            ->setMaxResults($limit);

        return $builder;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder|QueryBuilder
     */
    protected function getBaseQuery()
    {
        $builder = $this->getManager()->createQueryBuilder();
        $builder->select(['bundle', 'products'])
            ->from(BundleModel::class, 'bundle')
            ->leftJoin('bundle.products', 'products');

        return $builder;
    }
}
