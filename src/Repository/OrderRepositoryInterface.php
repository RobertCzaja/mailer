<?php


namespace App\Repository;


use App\Mailer\Exception\OrderNotFound;

interface OrderRepositoryInterface
{
    /**
     * @throws OrderNotFound
     */
    public function getSellerEmail(string $orderId): string;

    /**
     * @throws OrderNotFound
     */
    public function getCustomerEmail(string $orderId): string;
}