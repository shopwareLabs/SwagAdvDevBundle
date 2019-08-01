<?php

class Shopware_Controllers_Api_Bundles extends Shopware_Controllers_Api_Rest
{
    /**
     * @var SwagAdvDevBundle\Components\Api\Resource\Bundle
     */
    protected $resource = null;

    public function init()
    {
        $this->resource = \Shopware\Components\Api\Manager::getResource('Bundle');
    }

    /**
     * Get list of bundles
     *
     * GET /api/bundles/
     */
    public function indexAction()
    {
        $limit = $this->Request()->getParam('limit', 1000);
        $offset = $this->Request()->getParam('start', 0);
        $sort = $this->Request()->getParam('sort', []);
        $filter = $this->Request()->getParam('filter', []);

        $result = $this->resource->getList($offset, $limit, $filter, $sort);

        $this->View()->assign($result);
        $this->View()->assign('success', true);
    }

    /**
     * Get one bundle
     *
     * GET /api/bundles/{id}
     */
    public function getAction()
    {
        $id = $this->Request()->getParam('id');

        $bundle = $this->resource->getOne($id);

        $this->View()->assign('data', $bundle);
        $this->View()->assign('success', true);
    }

    /**
     * Create new bundle
     *
     * POST /api/bundles/
     */
    public function postAction()
    {
        $bundle = $this->resource->create($this->Request()->getPost());

        $location = $this->apiBaseUrl . 'bundles/' . $bundle->getId();
        $data = [
            'id' => $bundle->getId(),
            'location' => $location,
        ];

        $this->View()->assign(['success' => true, 'data' => $data]);
        $this->Response()->setHeader('Location', $location);
    }

    /**
     * Update bundle
     *
     * PUT /api/bundles/{id}
     */
    public function putAction()
    {
        $id = $this->Request()->getParam('id');
        $params = $this->Request()->getPost();
        $bundle = $this->resource->update($id, $params);

        $location = $this->apiBaseUrl . 'bundles/' . $bundle->getId();
        $data = [
            'id' => $bundle->getId(),
            'location' => $location,
        ];

        $this->View()->assign(['success' => true, 'data' => $data]);
        $this->Response()->setHeader('Location', $location);
    }

    /**
     * Delete bundle
     *
     * DELETE /api/bundles/{id}
     */
    public function deleteAction()
    {
        $id = $this->Request()->getParam('id');

        $this->resource->delete($id);

        $this->View()->assign(['success' => true]);
    }
}
