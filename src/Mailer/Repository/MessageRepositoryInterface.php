<?php


namespace App\Mailer\Repository;


use App\Mailer\Model\MessageReadyToSend;

interface MessageRepositoryInterface
{
    public function add(MessageReadyToSend $message): void;

    public function get(int $typeId, string $resourceId): ?array;
}