<?php
declare(strict_types=1);

namespace App\Mailer\Exception;

class MessageBodyIsEmpty extends \Exception
{
    public function __construct()
    {
        parent::__construct('Cannot send message because message body is empty');
    }
}
