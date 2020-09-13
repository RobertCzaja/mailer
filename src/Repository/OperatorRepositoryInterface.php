<?php


namespace App\Repository;


use App\Mailer\Exception\OperatorNotFound;

interface OperatorRepositoryInterface
{
    /**
     * @throws OperatorNotFound
     */
    public function getOperatorEmailByType(int $typeId): string;
}