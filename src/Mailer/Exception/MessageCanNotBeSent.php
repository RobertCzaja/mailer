<?php
declare(strict_types=1);

namespace App\Mailer\Exception;

use App\Mailer\Model\Message;
use App\Mailer\SenderResource\SenderResourceFactory;

class MessageCanNotBeSent extends \Exception
{
    public function __construct(int $messageType, int $senderType)
    {
        $messageType = Message::getTypeName($messageType);
        $senderType = SenderResourceFactory::getSenderTypeName($senderType);

        parent::__construct('Cannot send message with type ' . $messageType . ' from ' . $senderType);
    }
}
