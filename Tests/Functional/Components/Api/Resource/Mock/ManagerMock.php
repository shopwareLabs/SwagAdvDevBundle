<?php declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SwagAdvDevBundle\Tests\Functional\Components\Api\Resource\Mock;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class ManagerMock
{
    public function find($entityName, $id)
    {
        return Shopware()->Models()->find($entityName, $id);
    }

    public function validate(): ConstraintViolationList
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
