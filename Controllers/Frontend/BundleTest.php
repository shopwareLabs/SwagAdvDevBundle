<?php

use SwagAdvDevBundle\Components\Api\Resource\Bundle;

/**
 * This Controller is a playground for you in order to test you bundle API a bit.
 *
 * THIS IS NOT RELATED TO ANY FUNCTIONALITY OF THE PLUGIN
 *
 * Class Shopware_Controllers_Frontend_BundleTest
 */
class Shopware_Controllers_Frontend_BundleTest extends Enlight_Controller_Action
{
    /**
     * Returns an instance of the bundle API
     *
     * @return Bundle
     */
    public function getBundleAPI()
    {
        /** @var Bundle $resource */
        $resource = \Shopware\Components\Api\Manager::getResource('Bundle');

        return $resource;
    }

    /**
     * Disable the template loading
     */
    public function preDispatch()
    {
        $this->Front()->Plugins()->ViewRenderer()->setNoRender(true);
    }

    /**
     * Create a bundle
     */
    public function createAction()
    {
        $data = [
            'name' => $this->getRandomName(),
            'active' => true,
            'products' => [2, 3],
        ];
        $bundle = $this->getBundleAPI()->create($data);
        echo "Created bundle with id {$bundle->getId()} named {$bundle->getName()}";
    }

    /**
     * Print out bundle with $id
     */
    public function readAction()
    {
        $bundle = $this->getBundleAPI()->getOne($this->Request()->getParam('id'));

        echo '<pre>';
        print_r($bundle);
    }

    /**
     * List bundles
     */
    public function listAction()
    {
        $bundles = $this->getBundleAPI()->getList(0, 10, null, null);

        echo '<pre>';
        print_r($bundles);
    }

    /**
     * Randomly set bundle with $id (in)active
     */
    public function updateAction()
    {
        $bundle = $this->getBundleAPI()->update($this->Request()->getParam('id'), ['active' => mt_rand(0, 1)]);

        echo $bundle->getActive() ? 'aktiv' : 'inaktiv';
    }

    /**
     * Delete bundle $id
     */
    public function deleteAction()
    {
        $this->getBundleAPI()->delete($this->Request()->getParam('id'));

        echo 'Bundle was deleted';
    }

    /**
     * Get a random name for a bundle
     *
     * @return string
     */
    protected function getRandomName()
    {
        return array_rand(array_flip([
            'Mein Bundle',
            'Bundle 2000',
            'Bundle Red',
            'Bundle Green',
            'Bundle Black',
            'Bundle Orange',
            'Bundle White',
            'Bundle Magenta',
            'Bundle Blue',
            'Bundle Grey',
        ]));
    }
}
