<?php
declare(strict_types=1);


namespace App\Mailer\Model;


final class MessageReadyToSend extends Message
{
    public string $receiverEmail;

    public function __construct(Message $message, string $receiverEmail)
    {
        parent::__construct(
            $message->getSenderEmail(),
            $message->getMessageType(),
            $message->getResource(),
            $message->getMessage()
        );

        if (!filter_var($receiverEmail, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('$receiverEmail must be a valid email, ' . $receiverEmail . ' given');
        }

        $this->receiverEmail = $receiverEmail;
    }

    public function getReceiverEmail(): string
    {
        return $this->receiverEmail;
    }

}