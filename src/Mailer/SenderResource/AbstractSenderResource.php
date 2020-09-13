<?php
declare(strict_types=1);


namespace App\Mailer\SenderResource;


use App\Mailer\Exception\MessageCanNotBeSent;

abstract class AbstractSenderResource implements SenderResourceInterface
{
    private int $messageType;

    /**
     * @throws MessageCanNotBeSent
     */
    public function __construct(int $messageType)
    {
        if ($this->handlerNotProvided($messageType)) {
            throw new MessageCanNotBeSent($messageType, static::senderType());
        }

        $this->messageType = $messageType;
    }

    public static abstract function senderType(): int;

    public static abstract function senderName(): string;

    /**"
     * @return array|callable[] callable should returns after resolve string which is "receiver email"
     */
    protected abstract function messageTypeHandlers(): array;

    /**
     * @inheritDoc
     */
    public final function getReceiverEmail(string $resourceId): string
    {
        return $this->messageTypeHandlers()[$this->messageType]($resourceId);
    }

    private function handlerNotProvided(int $messageType): bool
    {
        return !in_array($messageType, array_keys($this->messageTypeHandlers()));
    }

}