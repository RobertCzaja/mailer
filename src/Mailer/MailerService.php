<?php
declare(strict_types=1);

namespace App\Mailer;

use App\Mailer\Exception\InvalidMessageType;
use App\Mailer\Exception\MessageBodyIsEmpty;
use App\Mailer\Exception\MessageCanNotBeSent;
use App\Mailer\Exception\OperatorNotFound;
use App\Mailer\Exception\OrderNotFound;
use App\Mailer\Exception\ProductNotFound;
use App\Mailer\Model\Message;
use App\Mailer\Model\MessageReadyToSend;
use App\Mailer\Repository\MessageRepositoryInterface;
use App\Mailer\SenderResource\SenderResourceFactory;

class MailerService
{
    private SenderResourceFactory $senderResourceFactory;
    private MessageRepositoryInterface $messageRepository;

    public function __construct(
        MessageRepositoryInterface $messageRepository,
        SenderResourceFactory $senderResourceFactory
    ) {
        $this->senderResourceFactory = $senderResourceFactory;
        $this->messageRepository = $messageRepository;
    }

    /**
     * @throws OperatorNotFound
     * @throws OrderNotFound
     * @throws ProductNotFound
     * @throws InvalidMessageType
     * @throws MessageBodyIsEmpty
     * @throws MessageCanNotBeSent
     */
    public function send(array $requestPayload): void
    {
        foreach ($requestPayload as $payload) {
            $message = new Message(
                $payload['senderEmail'] ?? null,
                $payload['messageType'] ?? null,
                $payload['resource'] ?? null,
                $payload['messageBody'] ?? null
            );

            $receiverEmail = $this->senderResourceFactory
                ->getInstance($payload['senderType'] ?? null, $message->getMessageType())
                ->getReceiverEmail($message->getResource());

            $this->messageRepository->add(new MessageReadyToSend($message,$receiverEmail));
        }
    }

}
