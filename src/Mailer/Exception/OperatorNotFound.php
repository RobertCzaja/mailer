<?php
declare(strict_types=1);

namespace App\Mailer\Exception;

class OperatorNotFound extends \Exception
{
    public function __construct(int $typeId)
    {
        parent::__construct('Operator for type ' . $typeId . ' not found');
    }
}
