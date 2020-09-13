<?php
declare(strict_types=1);


namespace App\Mailer\Model;


use App\Mailer\Exception\InvalidMessageType;
use App\Mailer\Exception\MessageBodyIsEmpty;
use InvalidArgumentException;

class Message
{
    /** @var int */
    public const TYPE_ORDER = 1;
    /** @var int */
    public const TYPE_PRODUCT = 2;

    private string $senderEmail;
    private int $messageType;
    private string $resource;
    private string $message;
    private string $subject;

    /**
     * @throws InvalidMessageType
     * @throws MessageBodyIsEmpty
     */
    public function __construct(
        ?string $senderEmail,
        ?int $messageType,
        ?string $resource,
        ?string $message
    ) {
        if (is_null($senderEmail) || is_null($messageType) || is_null($resource) || is_null($message)) {
            throw new InvalidArgumentException('All parameters must be provided');
        }

        if (!filter_var($senderEmail, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('$senderEmail must be a valid email, ' . $senderEmail . ' given');
        }

        if (!in_array($messageType, [self::TYPE_ORDER, self::TYPE_PRODUCT])) {
            throw new InvalidMessageType($messageType);
        }

        if ('' === $resource) {
            throw new InvalidArgumentException('$resource cannot be empty');
        }

        if ('' === $message = trim(strip_tags($message))) {
            throw new MessageBodyIsEmpty();
        }

        $this->senderEmail = $senderEmail;
        $this->messageType = $messageType;
        $this->resource = $resource;
        $this->message = $message;
        $this->subject = self::TYPE_ORDER === $this->messageType
            ? "Question about order no $resource"
            : "Question about product no $resource";
    }

    public static function getTypeName(int $type): string
    {
        switch ($type) {
            case self::TYPE_ORDER:
                return 'order';
            case self::TYPE_PRODUCT:
                return 'product';
            default:
                return 'unknown';
        }
    }

    public function getSenderEmail(): string
    {
        return $this->senderEmail;
    }

    public function getMessageType(): int
    {
        return $this->messageType;
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

}