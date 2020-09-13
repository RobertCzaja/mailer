<?php


namespace App\Mailer\SenderResource;


use App\Mailer\Exception\OperatorNotFound;
use App\Mailer\Exception\OrderNotFound;
use App\Mailer\Exception\ProductNotFound;

interface SenderResourceInterface
{
    /**
     * @throws OrderNotFound
     * @throws OperatorNotFound
     * @throws ProductNotFound
     */
    public function getReceiverEmail(string $resourceId): string;

}