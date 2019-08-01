<?php
declare(strict_types=1);

/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Shopware_Controllers_Api_Bundles extends Shopware_Controllers_Api_Rest
{
    /**
     * @var SwagAdvDevBundle\Components\Api\Resource\Bundle
     */
    protected $resource;

    public function init(): void
    {
        $this->resource = $this->get('shopware.api.bundle');
    }

    /**
     * Get list of bundles
     *
     * GET /api/bundles/
     */
    public function indexAction(): void
    {
        $offset = (int) $this->Request()->getParam('start', 0);
        $limit = (int) $this->Request()->getParam('limit', 1000);
        $sort = $this->Request()->getParam('sort', []);
        $filter = $this->Request()->getParam('filter', []);

        $result = $this->resource->getList($offset, $limit, $filter, $sort);

        $view = $this->View();
        $view->assign($result);
        $view->assign('success', true);
    }

    /**
     * Get one bundle
     *
     * GET /api/bundles/{id}
     */
    public function getAction(): void
    {
        $id = (int) $this->Request()->getParam('id');

        $bundle = $this->resource->getOne($id);

        $view = $this->View();
        $view->assign('data', $bundle);
        $view->assign('success', true);
    }

    /**
     * Create new bundle
     *
     * POST /api/bundles/
     */
    public function postAction(): void
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
    public function putAction(): void
    {
        $id = (int) $this->Request()->getParam('id');
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
    public function deleteAction(): void
    {
        $id = (int) $this->Request()->getParam('id');

        $this->resource->delete($id);

        $this->View()->assign(['success' => true]);
    }
}
