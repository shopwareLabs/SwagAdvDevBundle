<?php
declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use SwagAdvDevBundle\Components\Api\Resource\Bundle;

/**
 * This Controller is a playground for you in order to test you bundle API a bit.
 *
 * THIS IS NOT RELATED TO ANY FUNCTIONALITY OF THE PLUGIN
 */
class Shopware_Controllers_Frontend_BundleTest extends Enlight_Controller_Action
{
    /**
     * Returns an instance of the bundle API
     */
    public function getBundleAPI(): Bundle
    {
        /** @var Bundle $resource */
        $resource = $this->get('shopware.api.bundle');

        return $resource;
    }

    /**
     * Disable the template loading
     */
    public function preDispatch(): void
    {
        $this->Front()->Plugins()->ViewRenderer()->setNoRender();
    }

    /**
     * Create a bundle
     */
    public function createAction(): void
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
    public function readAction(): void
    {
        $bundle = $this->getBundleAPI()->getOne((int) $this->Request()->getParam('id'));

        echo '<pre>';
        print_r($bundle);
    }

    /**
     * List bundles
     */
    public function listAction(): void
    {
        $bundles = $this->getBundleAPI()->getList();

        echo '<pre>';
        print_r($bundles);
    }

    /**
     * Randomly set bundle with $id (in)active
     */
    public function updateAction(): void
    {
        $bundle = $this->getBundleAPI()->update((int) $this->Request()->getParam('id'), ['active' => random_int(0, 1)]);

        echo $bundle->getActive() ? 'aktiv' : 'inaktiv';
    }

    /**
     * Delete bundle $id
     */
    public function deleteAction(): void
    {
        $this->getBundleAPI()->delete((int) $this->Request()->getParam('id'));

        echo 'Bundle was deleted';
    }

    /**
     * Get a random name for a bundle
     */
    protected function getRandomName(): string
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
