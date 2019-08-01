<?php
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SwagAdvDevBundle\Tests\Functional;

use Doctrine\DBAL\Connection;

trait DatabaseTestCaseTrait
{
    /**
     * @before
     */
    public function startTransactionBefore(): void
    {
        /** @var Connection $dbalConnection */
        $dbalConnection = Shopware()->Container()->get('dbal_connection');
        $dbalConnection->beginTransaction();
    }

    /**
     * @after
     */
    public function rollbackTransactionAfter(): void
    {
        /** @var Connection $dbalConnection */
        $dbalConnection = Shopware()->Container()->get('dbal_connection');
        $dbalConnection->rollBack();
    }
}
