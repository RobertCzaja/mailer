<?php
declare(strict_types=1);

namespace App\Mailer\Exception;

class InvalidMessageType extends \Exception
{
    public function __construct(int $typeId)
    {
        parent::__construct('Invalid message type ' . $typeId);
    }
}
