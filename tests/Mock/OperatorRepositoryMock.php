<?php
declare(strict_types=1);


namespace App\Test\Mock;


use App\Mailer\Exception\OperatorNotFound;
use App\Repository\OperatorRepositoryInterface;

final class OperatorRepositoryMock implements OperatorRepositoryInterface
{
    private const OPERATOR = [
        [
            'typeId' => 1,
            'email'  => 'order@operator.com'
        ],
        [
            'typeId' => 2,
            'email'  => 'product@operator.com'
        ]
    ];

    /**
     * @inheritDoc
     */
    public function getOperatorEmailByType(int $typeId): string
    {
        foreach (self::OPERATOR as $operator) {
            if ($operator['typeId'] === $typeId) {
                return $operator['email'];
            }
        }
        throw new OperatorNotFound($typeId);
    }
}