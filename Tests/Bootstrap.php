<?php
declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__ . '/../../../../autoload.php';

use Doctrine\DBAL\Connection;
use Shopware\Kernel;
use Shopware\Models\Shop\Repository as ShopRepository;
use Shopware\Models\Shop\Shop;

class AdvDevBundleTestKernel extends Kernel
{
    public static function start(): void
    {
        $kernel = new self(getenv('SHOPWARE_ENV') ?: 'testing', true);
        $kernel->boot();

        $container = $kernel->getContainer();
        $container->get('plugins')->Core()->ErrorHandler()->registerErrorHandler(E_ALL | E_STRICT);

        /** @var ShopRepository $repository */
        $repository = $container->get('models')->getRepository(Shop::class);

        $container->get('shopware.components.shop_registration_service')->registerResources(
            $repository->getActiveDefault()
        );

        if (!self::isPluginInstalledAndActivated()) {
            die('Error: The plugin is not installed or activated, tests aborted!');
        }

        Shopware()->Loader()->registerNamespace('SwagAdvDevBundle', __DIR__ . '/../');
    }

    private static function isPluginInstalledAndActivated(): bool
    {
        /** @var Connection $db */
        $db = Shopware()->Container()->get('dbal_connection');

        $sql = "SELECT active FROM s_core_plugins WHERE name='SwagAdvDevBundle'";
        $active = $db->fetchColumn($sql);

        return (bool) $active;
    }
}

AdvDevBundleTestKernel::start();
