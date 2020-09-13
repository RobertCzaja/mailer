<?php
declare(strict_types=1);


namespace App\Test\Mock;


use App\Mailer\Model\MessageReadyToSend;
use App\Mailer\Repository\MessageRepositoryInterface;


final class MessageRepositoryMock implements MessageRepositoryInterface
{
    /**
     * @var array[]
     */
    private array $messages = [];

    public function add(MessageReadyToSend $messageReadyToSend): void
    {
        $this->messages[] = [
            'type'      => $messageReadyToSend->getMessageType(),
            'resource'  => $messageReadyToSend->getResource(),
            'from'      => $messageReadyToSend->getSenderEmail(),
            'to'        => $messageReadyToSend->getReceiverEmail(),
            'message'   => $messageReadyToSend->getMessage(),
            'subject'   => $messageReadyToSend->getSubject()
        ];
    }

    public function get(int $typeId, string $resourceId): ?array
    {
        foreach ($this->messages as $message) {
            if ($message['type'] === $typeId && $message['resource'] === $resourceId) {
                return $message;
            }
        }
        return null;
    }
}